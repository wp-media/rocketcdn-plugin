<?php

return [
    'testShouldDoNothingWhenNoUpdate' => [
        'config' => [
            'current_version' => '1.0.7',
            'plugin_version' => '1.0.7',
            'page' => 'rocketcdn',
        ],
        'expected' => false,
    ],
    'testShouldDoUpdate' => [
        'config' => [
            'current_version' => '',
            'plugin_version' => '1.0.7',
            'page' => 'rocketcdn',
        ],
        'expected' => 'wp_safe_redirect',
    ],
];