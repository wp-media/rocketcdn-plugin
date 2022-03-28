<?php
return [
    'testSiteURLShouldEnqueue' => [
        'config' => [
            'base' => '/'
        ],
        'expected' => [
            'id' => 'rocketcdn-admin-bar',
            'path' => '/css/admin-bar.css',
            'options' => [],
            'version' => ROCKETCDN_VERSION,
        ]
    ],
    'testSiteURLWithPathShouldEnqueue' => [
        'config' => [
            'base' => '/test/'
        ],
        'expected' => [
            'id' => 'rocketcdn-admin-bar',
            'path' => '/test/css/admin-bar.css',
            'options' => [],
            'version' => ROCKETCDN_VERSION,
        ]
    ]
];
