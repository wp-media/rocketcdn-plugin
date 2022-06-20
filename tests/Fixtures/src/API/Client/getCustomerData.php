<?php
return [
	'testShouldReturnUserDataWhenSucceed'      => [
		'config'   => [
			'cache'          => false,
			'api'            => 'api',
			'baseurl'        => 'url',
			'url'            => 'urlcustomer/me',
			'headers'        => [
				'headers' => [
					'Authorization' => 'token api',
				],
			],
			'response'       => 'response',
			'body'           => '{"data":1}',
			'code'           => 200,
			'requestsucceed' => true,
		],
		'expected' => [
			'data' => 1,
		],
	],
	'testShouldReturnNothingWhenRequestFailed' => [
		'config'   => [
			'cache'          => false,
			'api'            => 'api',
			'baseurl'        => 'url',
			'url'            => 'urlcustomer/me',
			'headers'        => [
				'headers' => [
					'Authorization' => 'token api',
				],
			],
			'response'       => 'response',
			'body'           => '{"data":1}',
			'code'           => 400,
			'requestsucceed' => false,
		],
		'expected' => [],
	],
	'testShouldReturnNothingWhenBodyEmpty'     => [
		'config'   => [
			'cache'          => false,
			'api'            => 'api',
			'baseurl'        => 'url',
			'url'            => 'urlcustomer/me',
			'headers'        => [
				'headers' => [
					'Authorization' => 'token api',
				],
			],
			'response'       => 'response',
			'body'           => '',
			'code'           => 200,
			'requestsucceed' => true,
		],
		'expected' => [],
	],
	'testShouldReturnNothingWhenNoAPIKey'      => [
		'config'   => [
			'cache'          => false,
			'api'            => '',
			'baseurl'        => 'url',
			'url'            => 'urlcustomer/me',
			'headers'        => [
				'headers' => [
					'Authorization' => 'token api',
				],
			],
			'response'       => 'response',
			'body'           => '{"data":1}',
			'code'           => 200,
			'requestsucceed' => false,
		],
		'expected' => [],
	],
	'testShouldReturnCacheWhenAvailable'       => [
		'config'   => [
			'cache'          => [
				'data',
			],
			'api'            => 'api',
			'baseurl'        => 'url',
			'url'            => 'urlcustomer/me',
			'headers'        => [
				'headers' => [
					'Authorization' => 'token api',
				],
			],
			'response'       => 'response',
			'body'           => '{"data":1}',
			'code'           => 200,
			'requestsucceed' => false,
		],
		'expected' => [
			'data',
		],
	],
];
