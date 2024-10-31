<?php

return [
    'testShouldSendErrorWhenNoCap' => [
        'config' => [
            'current_user_can' => false,
            'notice_id' => 'update_notice',
            'user_meta' => [],
        ],
        'expected' => 'You do not have permissions to perform this action.',
    ],
    'testShouldSendErrorWhenNoNoticeId' => [
        'config' => [
            'current_user_can' => true,
            'notice_id' => '',
            'user_meta' => [],
        ],
        'expected' => 'The notice ID is missing',
    ],
    'testShouldSendErrorWhenDismissed' => [
        'config' => [
            'current_user_can' => true,
            'notice_id' => 'update_notice',
            'user_meta' => [
                'update_notice',
            ],
        ],
        'expected' => 'The notice is already dismissed',
    ],
    'testShouldSendSuccess' => [
        'config' => [
            'current_user_can' => true,
            'notice_id' => 'update_notice',
            'user_meta' => [],
        ],
        'expected' => 'Notice dismissed',
    ],
];