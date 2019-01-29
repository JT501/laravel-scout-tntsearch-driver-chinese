<?php

return [

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
                'dict' => '/usr/local/scws/etc/dict.utf8.xdb', // Path to SCWS Dictionary
                'add_dict' => '/usr/local/scws/etc/dict_cht.utf8.xdb', // Add Additional Dictionary to SCWS
                'rule' => '/usr/local/scws/etc/rules_chts.utf8.ini', // Path to SCWS Rule
                'multi' => false,
                'ignore' => true,
                'duality' => false,
            ],
        ],
        'stopWords' => [
            'a',
            'as',
            'the',
            '的',
            '了',
            '而是',
        ],
    ],
];
