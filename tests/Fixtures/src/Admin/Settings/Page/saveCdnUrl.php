<?php

return [
	'testCdnShouldSave'     => [
		'config' => [
			'cdn' => 'cdn',
		],
	],
	'testNoCdnShouldNoSave' => [
		'config' => [
			'cdn' => '',
		],
	],
];
