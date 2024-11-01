<?php
declare(strict_types=1);

namespace RocketCDN\Admin\Notices;

use RocketCDN\API\Client;
use RocketCDN\Dependencies\LaunchpadFrameworkOptions\Interfaces\OptionsAwareInterface;
use RocketCDN\Dependencies\LaunchpadFrameworkOptions\Traits\OptionsAwareTrait;

class Notices implements OptionsAwareInterface {
	use OptionsAwareTrait;

	/**
	 * API client instance
	 *
	 * @var Client
	 */
	private $api_client;

	/**
	 * Instantiate the class
	 *
	 * @param Client $api_client API client instance.
	 */
	public function __construct( Client $api_client ) {
		$this->api_client = $api_client;
	}

	/**
	 * Displays a notice if the API key is empty
	 *
	 * @return void
	 */
	public function empty_api_key_notice() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( $this->options->get( 'api_key' ) ) {
			return;
		}

		$message = sprintf(
			// translators: %1$s = strong opening tag, %2$s = strong closing tag, %3$s = link opening tag, %4$s = link closing tag.
			__( '%1$sRocketCDN:%2$s You are almost done. %3$sAdd your API key%4$s to deliver your content at the speed of light!', 'rocketcdn' ),
			'<strong>',
			'</strong>',
			'<a href="' . admin_url( 'options-general.php?page=rocketcdn' ) . '">',
			'</a>'
		);

		echo '<div class="notice notice-warning"><p>' . $message . '</p></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Displays a notice if the API key is wrong
	 *
	 * @return void
	 */
	public function wrong_api_key_notice() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$api_key = $this->options->get( 'api_key', '' );

		if (
			empty( $api_key )
			||
			! empty( $this->api_client->get_customer_data( $api_key ) )
		) {
			return;
		}

		$message = sprintf(
			// translators: %1$s = strong opening tag, %2$s = strong closing tag, %3$s = link opening tag, %4$s = link closing tag.
			__( '%1$sRocketCDN:%2$s Your API key is wrong. You can find your API key in your %3$sRocketCDN account%4$s.', 'rocketcdn' ),
			'<strong>',
			'</strong>',
			'<a href="https://rocketcdn.me/account/sites/" target="_blank" rel="noopener">',
			'</a>'
		);

		echo '<div class="notice notice-error"><p>' . $message . '</p></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Displays a notice on update
	 *
	 * @return void
	 */
	public function update_notice() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( ! $this->options->get( 'api_key', '' ) ) {
			return;
		}

		if ( $this->options->get( 'previous_version', '' ) > '1.0.6' ) {
			return;
		}

		$dismissed = (array) get_user_meta( get_current_user_id(), 'rocketcdn_dismissed_notices', true );

		if ( in_array( 'update_notice', $dismissed, true ) ) {
			return;
		}

		$message = sprintf(
			// translators: %1$s = strong opening tag, %2$s = strong closing tag, %3$s = previous CNAME, %4$s = new CNAME, %5$s = link opening tag, %6$s = link closing tag.
			__( '%1$sRocketCDN:%2$s We have updated your RocketCDN CNAME from %3$s to %4$s. The change is already applied to the plugin settings. If you were using the CNAME in your code, make sure to update it to: %4$s If you have any questions %5$sContact support%6$s.', 'rocketcdn' ),
			'<strong>',
			'</strong>',
			$this->options->get( 'previous_cdn_url', '' ),
			$this->options->get( 'cdn_url', '' ),
			'<a href="https://rocketcdn.me/contact/" target="_blank" rel="noopener">',
			'</a>'
		);

		echo '<div class="notice notice-info rocketcdn-is-dismissible" data-notice="update_notice"><p>' . $message . '</p><button class="rocketcdn-dismiss"><span class="screen-reader-text">' . esc_html__( 'Do not show this message again', 'rocketcdn' ) . '</span></button></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Dismisses a notice
	 *
	 * @return void
	 */
	public function dismiss_notice() {
		check_ajax_referer( 'rocketcdn_ajax', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'You do not have permissions to perform this action.', 'rocketcdn' ) );
		}

		$notice = isset( $_POST['notice_id'] ) ? sanitize_key( $_POST['notice_id'] ) : '';

		if ( empty( $notice ) ) {
			wp_send_json_error( __( 'The notice ID is missing', 'rocketcdn' ) );
		}

		$dismissed = get_user_meta( get_current_user_id(), 'rocketcdn_dismissed_notices', true );

		if ( in_array( $notice, $dismissed, true ) ) {
			wp_send_json_error( __( 'The notice is already dismissed', 'rocketcdn' ) );
		}

		$dismissed[] = $notice;

		update_user_meta( get_current_user_id(), 'rocketcdn_dismissed_notices', $dismissed );

		wp_send_json_success( 'Notice dismissed' );
	}
}
