<?php
return [
	'testNoCdnShouldReturnUrl'              => [
		'config'   => [
			'homeurl'    => 'http://example.org',
			'admin_url' => 'http://example.org/wp-admin/',
			'host'       => 'example.org',
			'cdn'        => null,
			'url'        => 'http://example.org/wp-content/uploads/2018/03/sticker-mule-189122-unsplash-1568x1046.jpg',
			'parsed_url' => [
				'host'   => 'example.org',
				'scheme' => 'http',
			],
		],
		'expected' => 'http://example.org/wp-content/uploads/2018/03/sticker-mule-189122-unsplash-1568x1046.jpg',
	],
	'testCdnShouldReturnUrlWithCdn'         => [
		'config'   => [
			'homeurl'    => 'http://example.org',
			'admin_url' => 'http://example.org/wp-admin/',
			'host'       => 'example.org',
			'url'        => 'http://example.org/wp-content/uploads/2018/03/sticker-mule-189122-unsplash-1568x1046.jpg',
			'cdn'        => 'cdn',
			'parsed_url' => [
				'host'   => 'example.org',
				'scheme' => 'http',
			],
		],
		'expected' => 'cdn/wp-content/uploads/2018/03/sticker-mule-189122-unsplash-1568x1046.jpg',
	],
	'testCdnNoHostShouldReturnUrlWithCdn'   => [
		'config'   => [
			'homeurl'    => 'http://example.org',
			'admin_url' => 'http://example.org/wp-admin/',
			'host'       => 'example.org',
			'url'        => '/wp-content/uploads/2018/03/sticker-mule-189122-unsplash-1568x1046.jpg',
			'cdn'        => 'cdn',
			'parsed_url' => [],
		],
		'expected' => 'cdn/wp-content/uploads/2018/03/sticker-mule-189122-unsplash-1568x1046.jpg',
	],
'testCdnAndAdminShouldReturnNotUrlWithCdn'         => [
		'config'   => [
			'is_admin' => true,
			'homeurl'    => 'http://example.org',
			'admin_url' => 'http://example.org/wp-admin/',
			'host'       => 'example.org',
			'url'        => 'http://example.org/wp-admin/wp-content/uploads/2018/03/sticker-mule-189122-unsplash-1568x1046.jpg',
			'cdn'        => 'cdn',
			'parsed_url' => [
				'host'   => 'example.org',
				'scheme' => 'http',
			],
		],
		'expected' => 'http://example.org/wp-admin/wp-content/uploads/2018/03/sticker-mule-189122-unsplash-1568x1046.jpg',
	],
];
