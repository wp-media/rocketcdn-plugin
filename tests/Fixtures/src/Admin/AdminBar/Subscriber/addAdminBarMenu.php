<?php
return [
	'testShouldDisplayAllTabs'       => [
		'config'   => [
			'option'  => [
				'api_key' => 'api_key_right',
			],
            'process_generate'   => [
                'response' => [
                    'body' => '{"websites":["test"]}',
                    'response' => [
                        'code' => 200,
                        'message' => "Success",
                    ],
                ],
            ],
			'request' => 'uri',
			'admin'   => true,
			'cap'     => true,
		],
		'expected' => [
			'base'     => (object) [
				'id'     => 'rocketcdn',
				'title'  => '<span class="rocketcdn-admin-bar-status rocketcdn-admin-bar-status-connected">RocketCDN</span>',
				'parent' => false,
				'href'   => false,
				'group'  => false,
				'meta'   => [],
			],
			'connect'  => null,
			'settings' => (object) [
				'id'     => 'rocketcdn-settings',
				'title'  => 'Settings',
				'parent' => 'rocketcdn',
				'href'   => 'http://example.org/wp-admin/options-general.php?page=rocketcdn',
				'group'  => false,
				'meta'   => [],
			],
			'cache'    => (object) [
				'id'     => 'rocketcdn-purge-cache',
				'title'  => 'Purge cache',
				'href'   => 'http://example.org/wp-admin/admin-post.php?action=rocketcdn-purge-cache&amp;_wp_http_referer=uri&amp;_wpnonce=wp_rocket_nonce',
				'parent' => 'rocketcdn',
				'group'  => false,
				'meta'   => [],
			],
			'faq'      => (object) [
				'id'     => 'rocketcdn-faq',
				'title'  => 'FAQ',
				'href'   => 'https://rocketcdn.me/faq/',
				'parent' => 'rocketcdn',
				'group'  => false,
				'meta'   => [
					'rel'    => 'noopener',
					'target' => '_blank',
				],
			],
			'support'  => (object) [
				'id'     => 'rocketcdn-support',
				'title'  => 'Support',
				'href'   => 'https://rocketcdn.me/contact/',
				'parent' => 'rocketcdn',
				'group'  => false,
				'meta'   => [
					'rel'    => 'noopener',
					'target' => '_blank',
					'html'   => '<div class="rocketcdn-admin-bar-subscription"><a href="https://rocketcdn.me/account/billing/" rel="noopener" target="_blank" class="rocketcdn-admin-bar-subscription-link">View my subscription</a></div>',
				],
			],
		],
	],
	'testNoApiShouldDisplayConnect'  => [
		'config'   => [
			'option'  => [
				'api_key' => null,
			],
			'request' => 'uri',
			'admin'   => true,
			'cap'     => true,
		],
		'expected' => [
			'base'     => (object) [
				'id'     => 'rocketcdn',
				'title'  => '<span class="rocketcdn-admin-bar-status rocketcdn-admin-bar-status-disconnected">RocketCDN is disconnected</span>',
				'parent' => false,
				'href'   => false,
				'group'  => false,
				'meta'   => [],
			],
			'connect'  => (object) [
				'id'     => 'rocketcdn-connect',
				'title'  => 'Connect RocketCDN',
				'parent' => 'rocketcdn',
				'href'   => 'http://example.org/wp-admin/options-general.php?page=rocketcdn',
				'group'  => false,
				'meta'   => [],
			],
			'settings' => null,
			'cache'    => null,
			'faq'      => null,
			'support'  => null,
		],
	],
	'testFrontShouldDisplayNothing'  => [
		'config'   => [
			'option'  => [
				'api_key' => null,
			],
			'request' => 'uri',
			'admin'   => false,
			'cap'     => false,
		],
		'expected' => [],
	],
	'testEditorShouldDisplayNothing' => [
		'config'   => [
			'option'  => [
				'api_key' => null,
			],
			'request' => 'uri',
			'admin'   => true,
			'cap'     => false,
		],
		'expected' => [],
	],
];
