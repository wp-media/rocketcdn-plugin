<?php

return [
	'shouldAddPreconnectLinkForCdn' => [
		'config'   => [
			'cdn_url' => '123456.rocketcdn.me',
		],
		'expected' => [
			'html' => <<<HTML
<link rel='dns-prefetch' href='//s.w.org' />
<link href='http://123456.rocketcdn.me' rel='preconnect' />
HTML,
		],
	],
];
