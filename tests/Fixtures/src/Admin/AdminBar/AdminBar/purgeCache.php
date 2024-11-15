<?php
return [
	'testNoNonceShouldStop'      => [
		'config'   => [
			'nonce'          => null,
			'is_nonce_valid' => false,
			'has_right'      => false,
			'referer'        => '',
		],
		'expected' => 'wp_nonce_ays',
	],
	'testInvalidNonceShouldStop' => [
		'config'   => [
			'nonce'          => 'nonce',
			'is_nonce_valid' => false,
			'has_right'      => false,
			'referer'        => '',
		],
		'expected' => 'wp_nonce_ays',
	],
	'testNoRightShouldStop'      => [
		'config'   => [
			'nonce'          => 'nonce',
			'is_nonce_valid' => true,
			'has_right'      => false,
			'referer'        => '',
		],
		'expected' => 'wp_die',

	],
	'testShouldRedirect'         => [
		'config'   => [
			'nonce'          => 'nonce',
			'is_nonce_valid' => true,
			'has_right'      => true,
			'referer'        => 'ref',
		],
		'expected' => 'wp_safe_redirect',
	],
];
