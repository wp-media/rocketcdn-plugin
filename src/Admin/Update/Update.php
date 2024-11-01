<?php
declare(strict_types=1);

namespace RocketCDN\Admin\Update;

use RocketCDN\API\Client;
use RocketCDN\Dependencies\LaunchpadFrameworkOptions\Interfaces\OptionsAwareInterface;
use RocketCDN\Dependencies\LaunchpadFrameworkOptions\Traits\OptionsAwareTrait;

class Update implements OptionsAwareInterface {
	use OptionsAwareTrait;

	/**
	 * API client.
	 *
	 * @var Client
	 */
	private $api_client;

	/**
	 * Instantiate the class.
	 *
	 * @param Client $api_client API client.
	 */
	public function __construct( Client $api_client ) {
		$this->api_client = $api_client;
	}

	/**
	 * Updater routine.
	 *
	 * @return void
	 */
	public function updater() {
		$current_version = $this->options->get( 'current_version', '' );

		if ( rocketcdn_get_constant( 'ROCKETCDN_VERSION', '' ) !== $current_version ) {
			/**
			 * Fires when updating the plugin.
			 *
			 * @since 1.0.6
			 *
			 * @param string $current_version Current version.
			 * @param string $new_version     New version.
			 */
			do_action( 'rocketcdn_update', $current_version, rocketcdn_get_constant( 'ROCKETCDN_VERSION', '' ) );
		}

		if ( ! did_action( 'rocketcdn_update' ) ) {
			return;
		}

		$this->options->set( 'current_version', rocketcdn_get_constant( 'ROCKETCDN_VERSION', '' ) );

		if ( empty( $current_version ) ) {
			$current_version = rocketcdn_get_constant( 'ROCKETCDN_VERSION', '' );
		}

		$this->options->set( 'previous_version', $current_version );

		$page = isset( $_GET['page'] ) ? sanitize_key( $_GET['page'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		if ( 'rocketcdn' === $page ) {
			wp_safe_redirect( esc_url_raw( admin_url( 'options-general.php?page=rocketcdn' ) ) );
			exit;
		}
	}

	/**
	 * Update CDN URL.
	 *
	 * @param string $old_version Old version.
	 *
	 * @return void
	 */
	public function update_cdn_url( $old_version ) {
		if ( version_compare( $old_version, '1.0.6', '>' ) ) {
			return;
		}

		if ( ! $this->options->get( 'cdn_url', '' ) ) {
			return;
		}

		if ( $this->options->get( 'previous_cdn_url', '' ) ) {
			return;
		}

		delete_transient( 'rocketcdn_customer_data' );

		$cdn_url = $this->api_client->get_website_cdn_url();

		if ( empty( $cdn_url ) ) {
			return;
		}

		$this->options->set( 'cdn_url', $cdn_url );
		$this->options->set( 'previous_cdn_url', $this->options->get( 'cdn_url', '' ) );
	}
}
