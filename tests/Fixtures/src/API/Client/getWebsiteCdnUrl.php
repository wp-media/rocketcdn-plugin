<?php
return [
    'testShouldReturnCDNUrlWhenHasValidHostname' => [
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
        'expected' => 'cdn_url',
    ],
    'testShouldReturnEmptyWhenNoWebsiteOffset' => [
        'config' => [
            'api' => 'api',
            'homeurl' => 'https://hostname',
            'hostname' => 'hostname',
            'customerdata' => [
            ],
        ],
        'expected' => '',
    ],
    'testShouldReturnEmptyWhenNoElementsWebsiteOffset' => [
        'config' => [
            'api' => 'api',
            'homeurl' => 'https://hostname',
            'hostname' => 'hostname',
            'customerdata' => [
                'websites' => []
            ],
        ],
        'expected' => '',
    ],
    'testShouldReturnEmptyWhenNoHostnameOffset' => [
        'config' => [
            'api' => 'api',
            'homeurl' => 'https://hostname',
            'hostname' => 'hostname',
            'customerdata' => [
                'websites' => []
            ],
        ],
        'expected' => '',
    ],
    'testShouldReturnEmptyWhenWrongHostnameOffset' => [
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
        'expected' => '',
    ],
];
