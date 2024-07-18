<?php
return [
	'testShouldReturnDataWhenSucceed'                 => [
		'config'   => [
			'api'            => 'api',
			'cdn'            => 'https://cdn',
			'response'       => 'response',
			'code'           => 200,
			'body'           => '{"data":1}',
			'baseurl'        => 'url',
			'url'            => 'urlwebsite/cdn/purge',
			'headers'        => [
				'method'  => 'DELETE',
				'headers' => [
					'Authorization' => 'token api',
				],
			],
			'requestsucceed' => true,
		],
		'expected' => [
			'data' => 1,
		],
	],
	'testShouldReturnFailureMessageWhenRequestFailed' => [
		'config'   => [
			'api'            => 'api',
			'cdn'            => 'https://cdn',
			'response'       => 'response',
			'code'           => 400,
			'body'           => '',
			'baseurl'        => 'url',
			'url'            => 'urlwebsite/cdn/purge',
			'headers'        => [
				'method'  => 'DELETE',
				'headers' => [
					'Authorization' => 'token api',
				],
			],
			'requestsucceed' => false,
		],
		'expected' => [
			'success' => false,
			'message' => 'The purge cache request failed.',
		],
	],
	'testShouldReturnFailureMessageWhenBodyEmpty'     => [
		'config'   => [
			'api'            => 'api',
			'cdn'            => 'https://cdn',
			'response'       => 'response',
			'code'           => 200,
			'body'           => '',
			'baseurl'        => 'url',
			'url'            => 'urlwebsite/cdn/purge',
			'headers'        => [
				'method'  => 'DELETE',
				'headers' => [
					'Authorization' => 'token api',
				],
			],
			'requestsucceed' => true,
		],
		'expected' => [
			'success' => false,
			'message' => 'The purge cache request failed.',
		],
	],
	'testShouldReturnFailureMessageWhenNoAPIKey'      => [
		'config'   => [
			'api'            => '',
			'cdn'            => '',
			'response'       => 'response',
			'code'           => 200,
			'body'           => '',
			'baseurl'        => 'url',
			'url'            => 'urlwebsite/cdn/purge',
			'headers'        => [
				'method'  => 'DELETE',
				'headers' => [
					'Authorization' => 'token ',
				],
			],
			'requestsucceed' => false,
		],
		'expected' => [
			'success' => false,
			'message' => 'Your API key is empty.',
		],
	],
	'testShouldReturnFailureMessageWhenNoCDN'         => [
		'config'   => [
			'api'            => 'api',
			'cdn'            => '',
			'response'       => 'response',
			'code'           => 200,
			'body'           => '',
			'baseurl'        => 'url',
			'url'            => 'urlwebsite/cdn/purge',
			'headers'        => [
				'method'  => 'DELETE',
				'headers' => [
					'Authorization' => 'token api',
				],
			],
			'requestsucceed' => false,
		],
		'expected' => [
			'success' => false,
			'message' => 'Your CDN URL is empty.',
		],
	],
];
