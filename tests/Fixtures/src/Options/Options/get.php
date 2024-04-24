<?php
return [
	'testShouldReturnValueWhenExisting'          => [
		'config'   => [
			'prefix'      => 'prefix',
			'option_name' => 'option_name',
			'default'     => 'default',
			'name'        => 'name',
			'option'      => 'option',
		],
		'expected' => 'option',
	],
	'testShouldReturnDefaultWhenNotExisting'     => [
		'config'   => [
			'prefix'      => 'prefix',
			'option_name' => 'option_name',
			'default'     => 'default',
			'name'        => 'name',
			'option'      => 'default',
		],
		'expected' => 'default',
	],
	'testShouldReturnConvertedValueWhenNotArray' => [
		'config'   => [
			'prefix'      => 'prefix',
			'option_name' => 'option_name',
			'default'     => [],
			'name'        => 'name',
			'option'      => 'option',
		],
		'expected' => (array) 'option',
	],
];
