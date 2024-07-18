<?php

return [
	'plugin_name'       => sanitize_key( 'RocketCDN' ),
	'template_basepath' => realpath( plugin_dir_path( __DIR__ ) ) . '/views/',
	'assets_baseurl'    => plugin_dir_url( __DIR__ ) . 'assets/',
	'is_mu_plugin'      => false,
	'translation_key'   => 'rocketcdn',
	'prefix'            => 'rocketcdn_',
];
