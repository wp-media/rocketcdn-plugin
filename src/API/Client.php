<?php
declare(strict_types=1);

namespace RocketCDN\API;

use RocketCDN\Options\Options;

class Client {
	const ROCKETCDN_API = 'https://rocketcdn.me/api/';

	private $options;

	public function __construct( Options $options ) {
		$this->options = $options;
	}

	public function get_customer_data(): array {
		$api_key = $this->options->get( 'api_key' );

		if ( empty( $api_key ) ) {
			return [];
		}

		$response = wp_remote_get(
			self::ROCKETCDN_API . 'customer/me',
			[
				'headers' => [
					'Authorization' => 'token ' . $api_key,
				],
			]
		);

		if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return [];
		}

		$body = wp_remote_retrieve_body( $response );

		if ( empty( $body ) ) {
			return [];
		}

		return json_decode( $body, true );
	}

	public function is_website_sync(): bool {
		$customer_data = $this->get_customer_data();

		if ( empty( $customer_data['subscription'] ) ) {
			return false;
		}

		$home_url = home_url();

		foreach ( $customer_data['subscription'] as $subscription ) {
			if ( ! isset( $subscription['website_url'] ) ) {
				continue;
			}

			if ( $home_url === $subscription['website_url'] ) {
				return true;
			}
		}

		return false;
	}
}
