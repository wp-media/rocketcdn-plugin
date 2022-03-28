<?php
return [
    'testNeedRewriteShouldStartBuffer' => [
        'config' => [
            'has_request_method' => true,
            'request_method' => 'GET',
            'is_admin' => false,
            'is_customize_preview' => false,
            'is_embed' => false,
        ],
        'expected' => true
    ],
    'testNoRequestMethodShouldNotStartBuffer' => [
        'config' => [
            'has_request_method' => false,
            'request_method' => 'GET',
            'is_admin' => false,
            'is_customize_preview' => false,
            'is_embed' => false,
        ],
        'expected' => false
    ],
    'testNotGetRequestMethodShouldNotStartBuffer' => [
        'config' => [
            'has_request_method' => true,
            'request_method' => 'POST',
            'is_admin' => false,
            'is_customize_preview' => false,
            'is_embed' => false,
        ],
        'expected' => false
    ],
    'testAdminShouldNotStartBuffer' => [
        'config' => [
            'has_request_method' => true,
            'request_method' => 'GET',
            'is_admin' => true,
            'is_customize_preview' => false,
            'is_embed' => false,
        ],
        'expected' => false
    ],
    'testCustomizePreviewShouldNotStartBuffer' => [
        'config' => [
            'has_request_method' => true,
            'request_method' => 'GET',
            'is_admin' => false,
            'is_customize_preview' => true,
            'is_embed' => false,
        ],
        'expected' => false
    ],
    'testEmbdedShouldNotStartBuffer' => [
        'config' => [
            'has_request_method' => true,
            'request_method' => 'GET',
            'is_admin' => false,
            'is_customize_preview' => false,
            'is_embed' => true,
        ],
        'expected' => false
    ],
];
