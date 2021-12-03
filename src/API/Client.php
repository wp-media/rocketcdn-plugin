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

		if ( empty( $customer_data['websites'] ) ) {
			return false;
		}

		$home_url = home_url();

		foreach ( $customer_data['websites'] as $website ) {
			if ( ! isset( $website['url'] ) ) {
				continue;
			}

			if ( $home_url === $website['url'] ) {
				return true;
			}
		}

		return false;
	}

	public function get_website_cdn_url(): string {
		$customer_data = $this->get_customer_data();

		if ( empty( $customer_data['websites'] ) ) {
			return '';
		}

		$home_url = home_url();

		foreach ( $customer_data['websites'] as $website ) {
			if ( ! isset( $website['url'] ) ) {
				continue;
			}

			if ( $home_url !== $website['url'] ) {
				continue;
			}

			if ( ! isset( $website['cdn_url'] ) ) {
				return '';
			}

			return $website['cdn_url'];
		}

		return '';
	}

	public function purge_cache() {}
}
