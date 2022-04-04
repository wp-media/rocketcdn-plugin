<?php
return [
	'testNoNonceShouldStop'      => [
		'config'   => [
			'nonce'          => null,
			'is_nonce_valid' => false,
			'has_right'      => false,
			'referer'        => '',
		],
		'expected' => '',
	],
	'testInvalidNonceShouldStop' => [
		'config'   => [
			'nonce'          => 'nonce',
			'is_nonce_valid' => false,
			'has_right'      => false,
			'referer'        => '',
		],
		'expected' => '',
	],
	'testNoRightShouldStop'      => [
		'config'   => [
			'nonce'          => 'nonce',
			'is_nonce_valid' => true,
			'has_right'      => false,
			'referer'        => '',
		],
		'expected' => '',
	],
	'testShouldRedirect'         => [
		'config'   => [
			'nonce'          => 'nonce',
			'is_nonce_valid' => true,
			'has_right'      => true,
			'referer'        => 'ref',
		],
		'expected' => 'ref',
	],
];
