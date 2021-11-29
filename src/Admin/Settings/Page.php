<?php
declare(strict_types=1);

namespace RocketCDN\Admin\Settings;

use RocketCDN\Options\Options;

class Page {
	/**
	 * Options instance
	 *
	 * @var Options
	 */
	private $options;

	/**
	 * Templates base path
	 *
	 * @var string
	 */
	private $template_basepath;

	/**
	 * Instantiates the class
	 *
	 * @param Options $options Options instance.
	 * @param string  $template_basepath Base filepath for the template.
	 */
	public function __construct( Options $options, $template_basepath ) {
		$this->options           = $options;
		$this->template_basepath = $template_basepath;
	}

	/**
	 * Registers the setting for the WP Settings API
	 *
	 * @return void
	 */
	public function configure() {
		register_setting(
			'rocketcdn',
			'rocketcdn_api_key',
			[
				'sanitize_callback' => 'sanitize_key',
			]
		);
	}

	/**
	 * Renders the settings page
	 *
	 * @return void
	 */
	public function render_page() {
		if ( empty( $this->options->get( 'api_key' ) ) ) {
			$this->render_template( 'admin/settings/no-api-key' );
			return;
		}

		$this->render_template( 'admin/settings/api-key-valid' );
	}

	/**
	 * Renders the given template if it's readable.
	 *
	 * @param string $template Template name.
	 */
	protected function render_template( $template ) {
		$template_path = $this->template_basepath . $template . '.php';

		if ( ! is_readable( $template_path ) ) {
			return;
		}

		include $template_path;
	}
}
