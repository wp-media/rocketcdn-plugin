<?php
declare(strict_types=1);

namespace RocketCDN\Admin\Notices;

use RocketCDN\API\Client;
use RocketCDN\Dependencies\LaunchpadFrameworkOptions\Interfaces\OptionsAwareInterface;
use RocketCDN\Dependencies\LaunchpadFrameworkOptions\Traits\OptionsAwareTrait;
use RocketCDN\Options\Options;

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
			'<a href="https://rocketcdn.me/account/sites/">',
			'</a>'
		);

		echo '<div class="notice notice-error"><p>' . $message . '</p></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
