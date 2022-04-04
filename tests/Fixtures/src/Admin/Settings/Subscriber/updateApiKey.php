<?php
return [
    'testHasNoRightShouldCallJsonError' => [
        'config' => [
            'api' => '',
            'check_ajax_referer' => true,
            'can_manage_options' => false,
        ],
        'expected' => [
            'bailout' => true,
            'json'    => 'wp_send_json_error',
        ],
    ],
    'testHasNoAPIShouldCallJsonError' => [
        'config' => [
            'api' => '',
            'can_manage_options' => true,
        ],
        'expected' => [
            'bailout' => true,
            'data' => [
                'status' => 200,
            ]
        ],
    ],
    'testHasInvalidAPIShouldCallJsonError' => [
        'config' => [
            'api' => 'api',
            'can_manage_options' => true,
            'process_generate' => [
                'is_wp_error' => true,
                'message' => 'error'
            ]
        ],
        'expected' => [
            'bailout' => true,
            'data' => [
                'status' => 200,
            ]
        ],
    ],
    'testHasNotSyncShouldCallJsonError' => [
        'config' => [
            'api' => 'api',
            'can_manage_options' => true,
            'process_generate' => [
                'is_wp_error' => true,
                'response' => '{"status":200,"success":true,"message":"succes","websites":["test"]}'
            ]
        ],
        'expected' => [
            'bailout' => true,
        ],
    ],
    'testShouldCallJsonSuccess' => [
        'config' => [
            'can_manage_options' => true,
            'api' => 'api',
            'process_generate' => [
                'response' => '{"status":200,"success":true,"message":"succes","websites":["test"]}'
            ]
        ],
        'expected' => [
            'bailout' => true,
            'data' => [
                'status' => 200,
            ]
        ],
    ],
    'testCacheShouldReturnCacheShouldReturnCache' => [
        'config' => [
            'api' => 'api',
            'can_manage_options' => true,
            'rocketcdn_customer_data' => [
                "success" => true,
                "message" => "success",
                "websites" => ["hostname" => "example.org"]
            ]
        ],
        'expected' => [
            'bailout' => true,
            'data' => [
                'status' => 200,
            ]
        ],
    ]
];
