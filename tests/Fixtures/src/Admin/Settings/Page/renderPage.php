<?php
return [
    'testNoAPIShouldPrintNoKey' => [
        'config' => [
            'api' => null,
            'cdn_url' => 'cdn_url',
            'is_sync' => false,
            'title' => 'title',
            'configs' => 'configs',
        ],
        'expected' => file_get_contents(WP_ROCKET_CDN_TESTS_FIXTURES_DIR . '/src/Admin/Settings/Page/HTML/testNoAPIShouldPrintNoKey.php'),
    ],
    'testInvalidAPIShouldPrintNoKey' => [
        'config' => [
            'api' => 'api',
            'cdn_url' => 'cdn_url',
            'is_sync' => false,
            'title' => 'title',
            'configs' => 'configs',
        ],
        'expected' => file_get_contents(WP_ROCKET_CDN_TESTS_FIXTURES_DIR . '/src/Admin/Settings/Page/HTML/testInvalidAPIShouldPrintNoKey.php'),
    ],
    'testValidAPIShouldPrintKey' => [
        'config' => [
            'api' => 'api',
            'cdn_url' => 'cdn_url',
            'is_sync' => true,
            'title' => 'title',
            'configs' => 'configs',
        ],
        'expected' => file_get_contents(WP_ROCKET_CDN_TESTS_FIXTURES_DIR . '/src/Admin/Settings/Page/HTML/testValidAPIShouldPrintKey.php'),
    ]
];
