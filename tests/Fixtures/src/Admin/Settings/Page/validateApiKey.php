<?php
return [
	'testHasNoRightShouldCallJsonError'     => [
		'config'   => [
			'api'       => null,
			'has_right' => false,
			'is_valid'  => [],
			'is_sync'   => false,
		],
		'expected' => 'You do not have permissions to perform this action.',
	],
	'testHasNoAPIShouldCallJsonError'       => [
		'config'   => [
			'api'       => null,
			'has_right' => true,
			'is_valid'  => [],
			'is_sync'   => false,
		],
		'expected' => 'The API key field is not set',
	],
	'testHasInvalidAPIShouldCallJsonError'  => [
		'config'   => [
			'api'       => null,
			'has_right' => true,
			'is_valid'  => [],
			'is_sync'   => false,
		],
		'expected' => 'The API key field is not set',
	],
	'testHasInvalidDataShouldCallJsonError' => [
		'config'   => [
			'api'       => 'api',
			'has_right' => true,
			'is_valid'  => [],
			'is_sync'   => false,
		],
		'expected' => 'Invalid API key',
	],
	'testHasNotSyncShouldCallJsonError'     => [
		'config'   => [
			'api'       => 'api',
			'has_right' => true,
			'is_valid'  => [ 'data' ],
			'is_sync'   => false,
		],
		'expected' => 'Your website is not yet synchronized with your subscription. Please add your website from your <a href="https://rocketcdn.me/account/sites/" target="_blank" rel="noopener">RocketCDN account page</a>.',
	],
	'testShouldCallJsonSuccess'             => [
		'config'   => [
			'api'       => 'api',
			'has_right' => true,
			'is_valid'  => [ 'data' ],
			'is_sync'   => true,
		],
		'expected' => 'Your website is synchronized.',
	],
];
