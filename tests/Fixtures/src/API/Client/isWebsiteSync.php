<?php
return [
    'testShouldReturnTrueWhenHasValidHostname' => [
        'config' => [
            'api' => 'api',
            'homeurl' => 'https://hostname',
            'hostname' => 'hostname',
            'customerdata' => [
                'websites' => [
                    [
                        'hostname' => 'hostname',
                        'cdn_url' => 'cdn_url',
                    ]
                ]
            ],
        ],
        'expected' => true,
    ],
    'testShouldReturnFalseWhenNoWebsiteOffset' => [
        'config' => [
            'api' => 'api',
            'homeurl' => 'https://hostname',
            'hostname' => 'hostname',
            'customerdata' => [
            ],
        ],
        'expected' => false,
    ],

    'testShouldReturnFalseWhenNoElementsWebsiteOffset' => [
        'config' => [
            'api' => 'api',
            'homeurl' => 'https://hostname',
            'hostname' => 'hostname',
            'customerdata' => [
                'websites' => []
            ],
        ],
        'expected' => false,
    ],

    'testShouldReturnFalseWhenNoHostnameOffset' => [
        'config' => [
            'api' => 'api',
            'homeurl' => 'https://hostname',
            'hostname' => 'hostname',
            'customerdata' => [
                'websites' => []
            ],
        ],
        'expected' => false,
    ],

    'testShouldReturnFalseWhenWrongHostnameOffset' => [
        'config' => [
            'api' => 'api',
            'homeurl' => 'https://hostname',
            'hostname' => 'hostname',
            'customerdata' => [
                'websites' => [
                    [
                        'hostname' => 'wrongname',
                    ]
                ]
            ],
        ],
        'expected' => false,
    ],
];
