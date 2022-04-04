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
				'api_key' => '4f48814ffa6d1493c735ff109bb3i1eeafee6f4c',
			],
		],
		'expected' => [
			'contains' => false,
			'html'     => str_replace( "\n", '', file_get_contents( WP_ROCKET_CDN_TESTS_FIXTURES_DIR . '/src/Admin/Notices/Notices/HTML/testAPIKeyUserdataHasRightsShouldPrintWarning.html' ) ),
		],
	],
];
