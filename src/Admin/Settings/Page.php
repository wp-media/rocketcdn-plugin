<?php
declare(strict_types=1);

namespace RocketCDN\Admin\Settings;

use RocketCDN\Dependencies\LaunchpadFrameworkOptions\Interfaces\OptionsAwareInterface;
use RocketCDN\Dependencies\LaunchpadFrameworkOptions\Traits\OptionsAwareTrait;
use RocketCDN\Options\Options;
use RocketCDN\API\Client;

class Page implements OptionsAwareInterface {

	use OptionsAwareTrait;

	/**
	 * API Client instance
	 *
	 * @var Client
	 */
	private $api_client;

	/**
	 * Templates base path
	 *
	 * @var string
	 */
	private $template_basepath;

	/**
	 * Assets base URL
	 *
	 * @var string
	 */
	private $assets_baseurl;

	/**
	 * Instantiates the class
	 *
	 * @param Client $api_client API Client instance.
	 * @param string $template_basepath Base filepath for the template.
	 * @param string $assets_baseurl Base URL for the assets.
	 */
	public function __construct( Client $api_client, $template_basepath, $assets_baseurl ) {
		$this->api_client        = $api_client;
		$this->template_basepath = $template_basepath;
		$this->assets_baseurl    = $assets_baseurl;
	}

	/**
	 * Registers the setting for the WP Settings API
	 *
	 * @return void
	 */
	public function configure_settings() {
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
		$api_key = $this->options->get( 'api_key' );

		if (
			empty( $api_key )
			||
			! $this->api_client->is_website_sync( $api_key )
		) {
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

	/**
	 * Validates the API key & website synchronization
	 *
	 * @return void
	 */
	public function validate_api_key() {
		check_ajax_referer( 'rocketcdn_ajax', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'You do not have permissions to perform this action.', 'rocketcdn' ) );
			return;
		}

		if ( ! isset( $_POST['api_key'] ) ) {
			wp_send_json_error( __( 'They API key field is not set', 'rocketcdn' ) );
			return;
		}

		$api_key = sanitize_key( $_POST['api_key'] );

		$valid_key = $this->api_client->get_customer_data( $api_key );

		if ( empty( $valid_key ) ) {
			wp_send_json_error( __( 'Invalid API key', 'rocketcdn' ) );
			return;
		}

		$is_sync = $this->api_client->is_website_sync( $api_key );

		if ( ! $is_sync ) {
			$message = sprintf(
				// translators: %1$s = opening link tag, %2$s = closing link tag.
				__( 'Your website is not yet synchronized with your subscription. Please add your website from your %1$sRocketCDN account page%2$s.', 'rocketcdn' ),
				'<a href="https://rocketcdn.me/account/sites/" target="_blank" rel="noopener">',
				'</a>'
			);

			wp_send_json_error( $message );
			return;
		}

		wp_send_json_success();
	}

	/**
	 * Updates the API key if valid and website is sync
	 *
	 * @return void
	 */
	public function update_api_key() {
		check_ajax_referer( 'rocketcdn_ajax', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'You do not have permissions to perform this action.', 'rocketcdn' ) );
			return;
		}

		if ( ! isset( $_POST['api_key'] ) ) {
			wp_send_json_error( __( 'They API key field is not set', 'rocketcdn' ) );
			return;
		}

		$api_key = sanitize_key( $_POST['api_key'] );

		if ( empty( $api_key ) ) {
			wp_send_json_error( __( 'The API key field is empty', 'rocketcdn' ) );
			return;
		}

		delete_transient( 'rocketcdn_customer_data' );

		$valid_key = $this->api_client->get_customer_data( $api_key );

		if ( empty( $valid_key ) ) {
			wp_send_json_error( __( 'Invalid API key', 'rocketcdn' ) );
			return;
		}

		$is_sync = $this->api_client->is_website_sync( $api_key );

		if ( ! $is_sync ) {
			$message = sprintf(
				// translators: %1$s = opening link tag, %2$s = closing link tag.
				__( 'Your website is not yet synchronized with your subscription. Please add your website from your %1$sRocketCDN account page%2$s.', 'rocketcdn' ),
				'<a href="https://rocketcdn.me/account/sites/" target="_blank" rel="noopener">',
				'</a>'
			);

			wp_send_json_error( $message );
			return;
		}

		$this->options->set( 'api_key', $api_key );

		wp_send_json_success( __( 'Your API key is valid.', 'rocketcdn' ) );
	}

	/**
	 * Saves the CDN URL into the database
	 *
	 * @return void
	 */
	public function save_cdn_url() {
		$cdn_url = $this->api_client->get_website_cdn_url();

		if ( empty( $cdn_url ) ) {
			return;
		}

		$this->options->set( 'cdn_url', $cdn_url );
	}

	/**
	 * Purges the CDN cache
	 *
	 * @return void
	 */
	public function purge_cache() {
		check_ajax_referer( 'rocketcdn_ajax', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'You do not have permissions to perform this action.', 'rocketcdn' ) );
			return;
		}

		$result = $this->api_client->purge_cache();

		if ( ! $result['success'] ) {
			wp_send_json_error( $result['message'] );
			return;
		}

		wp_send_json_success( __( 'Done! Your cache has been cleared', 'rocketcdn' ) );
	}

	/**
	 * Enqueue the assets for the settings page
	 *
	 * @param string $hook_suffix Current page hook.
	 */
	public function enqueue_assets( $hook_suffix ) {
		if ( 'settings_page_rocketcdn' !== $hook_suffix ) {
			return;
		}

		wp_enqueue_style( 'rocketcdn-settings', $this->assets_baseurl . 'css/settings.css', [], ROCKETCDN_VERSION );
		wp_enqueue_script( 'rocketcdn_ajax', $this->assets_baseurl . 'js/ajax.js', [], ROCKETCDN_VERSION, true );
		wp_localize_script(
			'rocketcdn_ajax',
			'rocketcdn_ajax_data',
			[
				'nonce' => wp_create_nonce( 'rocketcdn_ajax' ),
			]
		);
	}
}
