<?php
return [
	'testUserHasNoRightShouldNoPrintAPINotice' => [
		'config'   => [
			'api'        => 'api',
			'has_rights' => false,
			'admin_url'  => 'admin_url',
		],
		'expected' => '',
	],
	'testAPIKeyExistShouldNoPrintAPINotice'    => [
		'config'   => [
			'api'        => 'api',
			'has_rights' => true,
			'admin_url'  => 'admin_url',
		],
		'expected' => '',
	],
	'testNoAPIAndHasRightShouldPrintAPINotice' => [
		'config'   => [
			'api'        => '',
			'has_rights' => true,
			'admin_url'  => 'admin_url',
		],
		'expected' => file_get_contents( WP_ROCKET_CDN_TESTS_FIXTURES_DIR . '/src/Admin/Notices/Notices/HTML/testNoAPIAndHasRightShouldPrintAPINotice.html' ),
	],
];
