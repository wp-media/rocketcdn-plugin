<?php
return [
	'testNoRightShouldReturnError'   => [
		'config'   => [
			'has_right' => false,
			'response'  => [
				'success' => false,
				'message' => 'Message',
			],
		],
		'expected' => 'You do not have permissions to perform this action.',
	],
	'testFailedShouldReturnError'    => [
		'config'   => [
			'has_right' => true,
			'response'  => [
				'success' => false,
				'message' => 'Error',
			],
		],
		'expected' => 'Error',
	],
	'testSuccessShouldReturnSuccess' => [
		'config'   => [
			'has_right' => true,
			'response'  => [
				'success' => true,
				'message' => 'Message',
			],
		],
		'expected' => 'Done! Your cache has been cleared',
	],
];
