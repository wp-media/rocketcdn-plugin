<?php
return [
    'testNoRightShouldReturnError' => [
        'config' => [
            'has_right' => false,
            'response' => [
                'success' => false,
                'message' => 'Message',
            ]
        ],
        'expected' => [
            'success' => false,
            'message' => 'You do not have permissions to perform this action.',
        ],
    ],
    'testFailedShouldReturnError' => [
        'config' => [
            'has_right' => true,
            'response' => [
                'success' => false,
                'message' => 'Message',
            ]
        ],
        'expected' => [
            'success' => false,
            'message' => 'Message',
        ],
    ],
    'testSuccessShouldReturnSuccess' => [
        'config' => [
            'has_right' => true,
            'response' => [
                'success' => true,
                'message' => 'Message',
            ]
        ],
        'expected' => [
            'success' => true,
            'message' => 'Done! Your cache has been cleared',
        ],
    ],
];
