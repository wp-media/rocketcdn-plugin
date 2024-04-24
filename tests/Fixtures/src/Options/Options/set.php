<?php

return [
	'testShouldSetValue' => [
		'config'   => [
			'prefix' => 'prefix',
			'name'   => 'name',
			'value'  => 'value',
		],
		'expected' => [
			'option_name' => 'prefixname',
			'value'       => 'value',
		],
	],
];
