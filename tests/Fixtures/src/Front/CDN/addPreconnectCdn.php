<?php
return [
	'testNotPreconnectRelationTypeShouldReturnOriginalUrls' => [
		'config'   => [
			'urls'          => [
				'url1',
				'url2',
				'url3',
			],
			'rewrite'       => true,
			'relation_type' => 'prerender',
			'cdn'           => 'cdn',
		],
		'expected' => [
			'url1',
			'url2',
			'url3',
		],
		'cdn'      => 'cdn',
	],
	'testShouldNotRewriteShouldReturnOriginalUrls'        => [
		'config'   => [
			'urls'          => [
				'url1',
				'url2',
				'url3',
			],
			'rewrite'       => false,
			'relation_type' => 'preconnect',
			'cdn'           => 'cdn',
		],
		'expected' => [
			'url1',
			'url2',
			'url3',
		],
	],
	'testEmptyCdnShouldReturnOriginalUrls'                => [
		'config'   => [
			'urls'          => [
				'url1',
				'url2',
				'url3',
			],
			'rewrite'       => true,
			'relation_type' => 'preconnect',
			'cdn'           => null,
		],
		'expected' => [
			'url1',
			'url2',
			'url3',
		],
	],
	'testCdnPreconnectRelationTypeRewriteShouldReturnCdn' => [
		'config'   => [
			'urls'          => [
				'url1',
				'url2',
				'url3',
			],
			'rewrite'       => true,
			'relation_type' => 'preconnect',
			'cdn'           => 'cdn',
		],
		'expected' => [
			'url1',
			'url2',
			'url3',
			[
				'href' => 'cdn',
			],
			[
				'href'        => 'cdn',
				'crossorigin' => 'anonymous',
			],
		],
	],
];
