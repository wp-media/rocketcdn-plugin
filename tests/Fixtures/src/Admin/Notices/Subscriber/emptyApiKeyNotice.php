<?php
return [
    'testNoRightShouldDisplayNothing' => [
        'config' => [
            'admin' => false,
            'option' => [
                'api_key' => null
            ],
        ],
        'expected' => [
            'contains' => false,
            'html' => file_get_contents( WP_ROCKET_CDN_TESTS_FIXTURES_DIR . '/src/Admin/Notices/Subscriber/HTML/testNoAPIAndHasRightShouldPrintAPINotice.html' ),
        ],
    ],
    'testApiKeyShouldDisplayNothing' => [
        'config' => [
            'admin' => true,
            'option' => [
                'api_key' => 'api_key'
            ],
        ],
        'expected' => [
            'contains' => false,
            'html' => file_get_contents( WP_ROCKET_CDN_TESTS_FIXTURES_DIR . '/src/Admin/Notices/Subscriber/HTML/testNoAPIAndHasRightShouldPrintAPINotice.html' ),
        ],
    ],
    'testShouldDisplayNotification' => [
        'config' => [
            'admin' => true,
            'option' => [
                'api_key' => null
            ],
        ],
        'expected' => [
            'contains' => true,
            'html' => file_get_contents( WP_ROCKET_CDN_TESTS_FIXTURES_DIR . '/src/Admin/Notices/Subscriber/HTML/testNoAPIAndHasRightShouldPrintAPINotice.html' ),
        ],
    ]
];
