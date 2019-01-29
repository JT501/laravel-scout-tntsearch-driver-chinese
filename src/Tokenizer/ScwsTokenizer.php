<?php
/**
 * Created by PhpStorm.
 * User: Kiu Cheong
 * Date: 29/1/2019
 * Time: 4:17 PM
 */

namespace Hallelujahbaby\Scout\Tokenizer;

use Latrell\Scws\Scws;
use TeamTNT\TNTSearch\Support\Tokenizer;

class ScwsTokenizer extends Tokenizer
{
    protected $scws;
    protected $stopwords;

    public function __construct(array $config = [], array $stopwords = [])
    {
        $this->scws = new Scws($config);
        $this->stopwords = $stopwords;
    }

    public function getScws()
    {
        return $this->scws;
    }

    public function tokenize($text, $stopword = [])
    {
        $text = mb_strtolower($text); // Set all english words to lower case
        $text = trim(preg_replace('/\s+/', ' ', $text)); // Replace new lines into space
        $this->scws->sendText($text);
        $tokens = [];

        while ($result = $this->scws->getResult()) {
            $tokens = array_merge($tokens, array_column($result, 'word'));
        }

        return array_diff($tokens,$this->stopwords);
    }
}