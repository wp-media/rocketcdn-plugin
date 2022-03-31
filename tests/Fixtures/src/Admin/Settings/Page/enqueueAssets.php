<?php
return [
    'testShouldRegister' => [
        'config' => [
            'hook' => 'settings_page_rocketcdn',
            'should_register' => true,
        ],
        'expected' => [
            'version' => ROCKETCDN_VERSION,
            'style' => [
                'id' => 'rocketcdn-settings',
                'url' => '/css/settings.css',
                'dependencies' => [],
            ],
            'script' => [
                'id' => 'rocketcdn_ajax',
                'url' => '/js/ajax.js',
                'dependencies' => [],
            ],
            'localize' => [
                'handle' => 'rocketcdn_ajax',
                'object' => 'rocketcdn_ajax_data',
            ],
        ],
    ],
    'testShouldNotRegister' => [
        'config' => [
            'hook' => 'wrong',
            'should_register' => false,
        ],
        'expected' => [],
    ]
];
