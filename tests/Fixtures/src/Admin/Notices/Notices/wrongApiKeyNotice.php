<?php
return [
	'testUserHasNoRightShouldNoPrintWarning'        => [
		'config'   => [
			'api'        => '',
			'has_rights' => false,
			'data'       => [
				'data',
			],
		],
		'expected' => '',
	],
	'testAPIKeyNotExistShouldNoPrintWarning'        => [
		'config'   => [
			'api'        => '',
			'has_rights' => true,
			'data'       => [
				'data',
			],
		],
		'expected' => '',
	],
	'testNoUserdataShouldNoPrintWarning'            => [
		'config'   => [
			'api'        => 'api',
			'has_rights' => true,
			'data'       => [
				'data',
			],
		],
		'expected' => '',
	],
	'testAPIKeyUserdataHasRightsShouldPrintWarning' => [
		'config'   => [
			'api'        => 'api',
			'has_rights' => true,
			'data'       => [],
		],
		'expected' => file_get_contents( WP_ROCKET_CDN_TESTS_FIXTURES_DIR . '/src/Admin/Notices/Notices/HTML/testAPIKeyUserdataHasRightsShouldPrintWarning.html' ),
	],
];
