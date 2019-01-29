# Laravel Scout TNTSearch 驅動包 (支援SCWS中文分詞) - Laravel 5.3/5.4/5.5/5.6/5.7

## 安裝

通過 `composer` 安裝:

``` bash
composer require hallelujahbaby/laravel-scout-tntsearch-driver-chinese
```

(Laravel 5.5 以下版本) 添加服務提供者到 `config/app.php`:

```php
'providers' => [
    // ...
    Laravel\Scout\ScoutServiceProvider::class,
    TeamTNT\Scout\TNTSearchScoutServiceProvider::class,
],
```

然後就可以將 `scout.php` 配置文件發佈到 `config` 目錄。

```bash
php artisan vendor:publish --provider="Laravel\Scout\ScoutServiceProvider"
```

在 `config/scout.php` 中添加:

```php

'tntsearch' => [
        'storage' => storage_path('TNTSearch'), //place where the index files will be stored
        'fuzziness' => env('TNTSEARCH_FUZZINESS', false),
        'fuzzy' => [
            'prefix_length' => 2,
            'max_expansions' => 50,
            'distance' => 2
        ],
        'asYouType' => false,
        'searchBoolean' => env('TNTSEARCH_BOOLEAN', false),
        'tokenizer' => [
            'scws' => [
                'charset' => 'utf-8',
                'dict' => '/usr/local/scws/etc/dict.utf8.xdb', // SCWS 詞典路徑
                'add_dict' => '/usr/local/scws/etc/dict_cht.utf8.xdb', // 加入額外詞典到 SCWS
                'rule' => '/usr/local/scws/etc/rules_chts.utf8.ini', // SCWS 規則路徑
                'multi' => false,
                'ignore' => true,
                'duality' => false,
            ],
        ],
        'stopwords' => [
            'a',
            'as',
            'the',
            '的',
            '了',
            '而是',
        ],
    ],

```

你也可以在模型中直接添加 `asYouType` 選項, 參考下面的示例。

## 用法


```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Post extends Model
{
    use Searchable;

    public $asYouType = true;

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = $this->toArray();

        // 自定可搜索的Array

        return $array;
    }
}
```

同步數據到搜索服務:

```bash
php artisan scout:import App\\Post
```


使用模型進行搜索:
``` php
Post::search('你好世界')->get();
```

## 安裝中文分詞

目前默認只支援 `scws` 中文分詞。需要安裝**SCWS拓展包**和**SCWS PHP 擴展**

[安裝詳情](https://github.com/hallelujahbaby/SCWS)
