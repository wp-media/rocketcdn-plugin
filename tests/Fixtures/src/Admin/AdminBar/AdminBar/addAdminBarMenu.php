<?php
return [
    'testNotAdminShouldNotAdd' => [
        'config' => [
            'is_admin' => false,
            'has_right' => false,
            'api' => '',
            'data' => ['data'],
            'request_uri' => 'uri',
        ],
        'expected' => [],
    ],
    'testNoRightShouldNotAdd' => [
        'config' => [
            'is_admin' => true,
            'has_right' => false,
            'api' => '',
            'data' => ['data'],
            'request_uri' => 'uri',
        ],
        'expected' => [],
    ],
    'testNoApiKeyShouldAddNoConnection' => [
        'config' => [
            'is_admin' => true,
            'has_right' => true,
            'api' => '',
            'data' => ['data'],
            'request_uri' => 'uri',
        ],
        'expected' => [
            [
                'id'    => 'rocketcdn',
                'title' => '<span class="rocketcdn-admin-bar-status rocketcdn-admin-bar-status-disconnected">RocketCDN is disconnected</span>',
            ],
            [
                'id'     => 'rocketcdn-connect',
                'title'  => 'Connect RocketCDN',
                'href'   => 'options-general.php?page=rocketcdn',
                'parent' => 'rocketcdn',
            ]
        ],
    ],
    'testNoDataShouldAddNoConnection' => [
        'config' => [
            'is_admin' => true,
            'has_right' => true,
            'api' => 'api',
            'data' => [],
            'request_uri' => 'uri',
        ],
        'expected' => [
            [
                'id'    => 'rocketcdn',
                'title' => '<span class="rocketcdn-admin-bar-status rocketcdn-admin-bar-status-disconnected">RocketCDN is disconnected</span>',
            ],
            [
                'id'     => 'rocketcdn-connect',
                'title'  => 'Connect RocketCDN',
                'href'   => 'options-general.php?page=rocketcdn',
                'parent' => 'rocketcdn',
            ]
        ],
    ],
    'testRequestURINotEmptyShouldRightReferrer' => [
        'config' => [
            'is_admin' => true,
            'has_right' => true,
            'api' => 'api',
            'data' => ['data'],
            'request_uri' => 'uri',
        ],
        'expected' => [
            [
                'id'    => 'rocketcdn',
                'title' => '<span class="rocketcdn-admin-bar-status rocketcdn-admin-bar-status-connected">RocketCDN</span>',
            ],
            [
                'id'     => 'rocketcdn-settings',
                'title'  => 'Settings',
                'href'   => 'options-general.php?page=rocketcdn',
                'parent' => 'rocketcdn',
            ],
            [
                'id' => 'rocketcdn-purge-cache',
                'title' => 'Purge cache',
                'href' => 'admin-post.php?action=rocketcdn-purge-cache&_wp_http_referer=uri',
                'parent' => 'rocketcdn'
            ],
            [
                'id'     => 'rocketcdn-faq',
                'title'  => 'FAQ',
                'href'   => 'https://rocketcdn.me/faq/',
                'parent' => 'rocketcdn',
                'meta'   => [
                    'rel'    => 'noopener',
                    'target' => '_blank',
                ],
            ],
            [
                'id'     => 'rocketcdn-support',
                'title'  => 'Support',
                'href'   => 'https://rocketcdn.me/contact/',
                'parent' => 'rocketcdn',
                'meta'   => [
                    'rel'    => 'noopener',
                    'target' => '_blank',
                    'html'   => '<div class="rocketcdn-admin-bar-subscription"><a href="https://rocketcdn.me/account/billing/" rel="noopener" target="_blank" class="rocketcdn-admin-bar-subscription-link">View my subscription</a></div>',
                ],
            ]
        ],
    ],
    'testRequestURIEmptyShouldDefaultReferrer' => [
        'config' => [
            'is_admin' => true,
            'has_right' => true,
            'api' => 'api',
            'data' => ['data'],
            'request_uri' => '',
        ],
        'expected' => [
            [
                'id'    => 'rocketcdn',
                'title' => '<span class="rocketcdn-admin-bar-status rocketcdn-admin-bar-status-connected">RocketCDN</span>',
            ],
            [
                'id'     => 'rocketcdn-settings',
                'title'  => 'Settings',
                'href'   => 'options-general.php?page=rocketcdn',
                'parent' => 'rocketcdn',
            ],
            [
                'id' => 'rocketcdn-purge-cache',
                'title' => 'Purge cache',
                'href' => 'admin-post.php?action=rocketcdn-purge-cache',
                'parent' => 'rocketcdn'
            ],
            [
                'id'     => 'rocketcdn-faq',
                'title'  => 'FAQ',
                'href'   => 'https://rocketcdn.me/faq/',
                'parent' => 'rocketcdn',
                'meta'   => [
                    'rel'    => 'noopener',
                    'target' => '_blank',
                ],
            ],
            [
                'id'     => 'rocketcdn-support',
                'title'  => 'Support',
                'href'   => 'https://rocketcdn.me/contact/',
                'parent' => 'rocketcdn',
                'meta'   => [
                    'rel'    => 'noopener',
                    'target' => '_blank',
                    'html'   => '<div class="rocketcdn-admin-bar-subscription"><a href="https://rocketcdn.me/account/billing/" rel="noopener" target="_blank" class="rocketcdn-admin-bar-subscription-link">View my subscription</a></div>',
                ],
            ]
        ],
    ]
];
