<?php

return [
	'shouldAddPreconnectLinkForCdn' => [
		'config'   => [
			'cdn_url' => '123456.rocketcdn.me',
		],
		'expected' => [
			'html' => <<<HTML
<link href='http://123456.rocketcdn.me' rel='preconnect' />
HTML,
		],
	],
];
