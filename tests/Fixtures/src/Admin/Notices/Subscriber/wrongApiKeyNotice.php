<?php
return [
	'testAPIKeyNotExistShouldNoPrintWarning'           => [
		'config'   => [
			'option' => [
				'api_key' => '',
			],
		],
		'expected' => [
			'contains' => false,
			'html'     => str_replace( "\n", '', file_get_contents( WP_ROCKET_CDN_TESTS_FIXTURES_DIR . '/src/Admin/Notices/Notices/HTML/testAPIKeyUserdataHasRightsShouldPrintWarning.html' ) ),
		],
	],
	'testNoUserdataShouldPrintWarning'                 => [
		'config'   => [
			'option' => [
				'api_key' => 'api_key',
			],
		],
		'expected' => [
			'contains' => true,
			'html'     => str_replace( "\n", '', file_get_contents( WP_ROCKET_CDN_TESTS_FIXTURES_DIR . '/src/Admin/Notices/Notices/HTML/testAPIKeyUserdataHasRightsShouldPrintWarning.html' ) ),
		],
	],
	'testAPIKeyUserdataHasRightsShouldNotPrintWarning' => [
		'config'   => [
			'option' => [
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
		],
		'expected' => [
			'contains' => false,
			'html'     => str_replace( "\n", '', file_get_contents( WP_ROCKET_CDN_TESTS_FIXTURES_DIR . '/src/Admin/Notices/Notices/HTML/testAPIKeyUserdataHasRightsShouldPrintWarning.html' ) ),
		],
	],
];
