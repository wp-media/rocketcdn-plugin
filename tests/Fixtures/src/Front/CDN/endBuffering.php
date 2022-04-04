<?php

return [
	'testDefaultSiteURL'  => [
		'config'   => [
			'homeurl'       => 'http://example.org',
			'hostname'      => 'example.org',
			'wp-content'    => 'wp-content/',
			'wp-includes'   => 'wp-includes/',
			'wp_upload_dir' => 'wp-content/uploads/',
			'cdn'           => 'cdn',
			'html'          => file_get_contents( WP_ROCKET_CDN_TESTS_FIXTURES_DIR . '/src/Front/CDN/HTML/siteURL/original.html' ),
		],
		'expected' => file_get_contents( WP_ROCKET_CDN_TESTS_FIXTURES_DIR . '/src/Front/CDN/HTML/siteURL/rewrite.html' ),
	],
	'testSiteURLWithPath' => [
		'config'   => [
			'homeurl'       => 'http://example.org/blog',
			'hostname'      => 'example.org',
			'wp-content'    => 'blog/wp-content/',
			'wp-includes'   => 'blog/wp-includes/',
			'wp_upload_dir' => 'blog/wp-content/uploads/',
			'cdn'           => 'cdn',
			'html'          => file_get_contents( WP_ROCKET_CDN_TESTS_FIXTURES_DIR . '/src/Front/CDN/HTML/siteURLWithPath/original.html' ),
		],
		'expected' => file_get_contents( WP_ROCKET_CDN_TESTS_FIXTURES_DIR . '/src/Front/CDN/HTML/siteURLWithPath/rewrite.html' ),
	],
];
