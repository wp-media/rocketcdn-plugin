<?php
return [
    'testPrefixEmptyShouldReturnExpected' => [
        'config' => [
            'prefix' => '',
            'name' => 'name',
        ],
        'expected' => 'name',
    ],
    'testPrefixNonEmptyShouldReturnExpected' => [
        'config' => [
            'prefix' => 'prefix',
            'name' => 'name',
        ],
        'expected' => 'prefixname',
    ]
];
