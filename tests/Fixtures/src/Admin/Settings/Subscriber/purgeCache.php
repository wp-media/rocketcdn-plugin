<?php

return [
	'testNoRightShouldReturnError'   => [
		'config'   => [
			'can_manage_options' => false,
			'api'                => 'api',
			'cdn'                => 'cdn',
		],
		'expected' => [
			'bailout' => true,
		],
	],
	'testFailedShouldReturnError'    => [
		'config'   => [
			'can_manage_options' => true,
			'api'                => 'api',
			'cdn'                => 'cdn',
			'process_generate'   => [
				'is_wp_error' => true,
				'message'     => 'error',
			],
		],
		'expected' => [
			'bailout' => true,
		],
	],
	'testSuccessShouldReturnSuccess' => [
		'config'   => [
			'can_manage_options' => true,
			'api'                => 'api',
			'cdn'                => 'cdn',
			'process_generate'   => [
				'is_wp_error' => true,
				'response'    => '{"status":200,"success":true,"message":"success"}',
			],
		],
		'expected' => [
			'bailout' => true,
		],
	],
];
