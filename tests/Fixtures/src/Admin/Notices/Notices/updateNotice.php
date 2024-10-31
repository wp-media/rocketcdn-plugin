<?php

return [
    'testShouldDoNothingWhenNoCap' => [
        'config' => [
            'current_user_can' => false,
            'api_key' => '123456',
            'previous_version' => '1.0.5',
            'user_meta' => false,
            'cdn_url' => 'https://cdn.example.org',
            'previous_cdn_url' => 'https://cdn.example.org',
        ],
        'expected' => '',
    ],
    'testShouldDoNothingWhenNoApiKey' => [
        'config' => [
            'current_user_can' => true,
            'api_key' => '',
            'previous_version' => '1.0.5',
            'user_meta' => false,
            'cdn_url' => 'https://cdn.example.org',
            'previous_cdn_url' => 'https://cdn.example.org',
        ],
        'expected' => '',
    ],
    'testShouldDoNothingWhenVgt106' => [
        'config' => [
            'current_user_can' => true,
            'api_key' => '123456',
            'previous_version' => '1.0.7',
            'user_meta' => false,
            'cdn_url' => 'https://xxxx.delivery.rocketcdn.me',
            'previous_cdn_url' => 'https://xxxx.rocketcdn.me',
        ],
        'expected' => '',
    ],
    'testShouldDoNothingWhenDismissed' => [
        'config' => [
            'current_user_can' => true,
            'api_key' => '123456',
            'previous_version' => '1.0.5',
            'user_meta' => [
                'update_notice',
            ],
            'cdn_url' => 'https://xxxx.delivery.rocketcdn.me',
            'previous_cdn_url' => 'https://xxxx.rocketcdn.me',
        ],
        'expected' => '',
    ],
    'testShouldDisplayNotice' => [
        'config' => [
            'current_user_can' => true,
            'api_key' => '123456',
            'previous_version' => '1.0.5',
            'user_meta' => [],
            'cdn_url' => 'https://xxxx.delivery.rocketcdn.me',
            'previous_cdn_url' => 'https://xxxx.rocketcdn.me',
        ],
        'expected' => '<div class="notice notice-info rocketcdn-is-dismissible" data-notice="update_notice"><p><strong>RocketCDN:</strong> We have updated your RocketCDN CNAME from https://xxxx.rocketcdn.me to https://xxxx.delivery.rocketcdn.me. The change is already applied to the plugin settings. If you were using the CNAME in your code, make sure to update it to: https://xxxx.delivery.rocketcdn.me If you have any questions <a href="https://rocketcdn.me/contact/" target="_blank" rel="noopener">Contact support</a>.</p><button class="rocketcdn-dismiss"><span class="screen-reader-text">Do not show this message again</span></button></div>',
    ],
];