<?php

return [
    'testShouldDoNothingWhenVersionGT106' => [
        'config' => [
            'old_version' => '1.0.7',
            'cdn_url' => 'https://xxxx.rocketcdn.me',
            'previous_cdn_url' => '',
            'remote_cdn_url' => 'https://xxxx.delivery.rocketcdn.me',
        ],
        'expected' => false,
    ],
    'testShouldDoNothingWhenEmptyCdnUrl' => [
        'config' => [
            'old_version' => '1.0.5',
            'cdn_url' => '',
            'previous_cdn_url' => '',
            'remote_cdn_url' => 'https://xxxx.delivery.rocketcdn.me',
        ],
        'expected' => false,
    ],
    'testShouldDoNothingWhenPreviousCdnUrlSet' => [
        'config' => [
            'old_version' => '1.0.6',
            'cdn_url' => 'https://xxxx.delivery.rocketcdn.me',
            'previous_cdn_url' => 'https://xxxx.rocketcdn.me',
            'remote_cdn_url' => 'https://xxxx.delivery.rocketcdn.me',
        ],
        'expected' => false,
    ],
    'testShouldDoNothingWhenNoRemoteCdnUrl' => [
        'config' => [
            'old_version' => '1.0.6',
            'cdn_url' => 'https://xxxx.rocketcdn.me',
            'previous_cdn_url' => '',
            'remote_cdn_url' => '',
        ],
        'expected' => false,
    ],
    'testShouldDoUpdate' => [
        'config' => [
            'old_version' => '1.0.6',
            'cdn_url' => 'https://xxxx.rocketcdn.me',
            'previous_cdn_url' => '',
            'remote_cdn_url' => 'https://xxxx.delivery.rocketcdn.me',
        ],
        'expected' => true,
    ],
];
