<?php

namespace Hallelujahbaby\Scout\Console;

use Hallelujahbaby\Scout\Tokenizer\ScwsTokenizer;
use Illuminate\Console\Command;
use Illuminate\Contracts\Events\Dispatcher;
use TeamTNT\TNTSearch\TNTSearch;

class ImportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tntsearch:import {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import the given model into the search index';

    /**
     * Execute the console command.
     *
     * @param \Illuminate\Contracts\Events\Dispatcher $events
     *
     * @return void
     */
    public function handle(Dispatcher $events)
    {
        $class = $this->argument('model');

        $model = new $class();
        $tnt = new TNTSearch();        
        $driver = $model->getConnectionName() ?: config('database.default');
        $config = config('scout.tntsearch') + config("database.connections.$driver");

        $tnt->loadConfig($config);
        $tnt->setTokenizer(new ScwsTokenizer(config('scout.tntsearch.tokenizer.scws')));
        $tnt->setDatabaseHandle(app('db')->connection($driver)->getPdo());

        $indexer = $tnt->createIndex($model->searchableAs().'.index');
        $indexer->setPrimaryKey($model->getKeyName());
        $indexer->setTokenizer($tnt->tokenizer);
        if (isset($tnt->config['stopWords'])) {
            $indexer->setStopWords($tnt->config['stopWords']);
        }

        $availableColumns = \Schema::getColumnListing($model->getTable());
        $desiredColumns = array_keys($model->toSearchableArray());

        $fields = implode(', ', array_intersect($desiredColumns, $availableColumns));

        $query = "{$model->getKeyName()}, $fields";

        if ($fields == '') {
            $query = '*';
        }

        $indexer->query("SELECT $query FROM {$model->getTable()};");

        $indexer->run();
        $this->info('All ['.$class.'] records have been imported.');
    }
}
