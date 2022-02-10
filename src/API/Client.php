<?php
declare(strict_types=1);

namespace RocketCDN\API;

use RocketCDN\Options\Options;

class Client {
	const ROCKETCDN_API = 'https://rocketcdn.me/api/';

	/**
	 * Options instance
	 *
	 * @var Options
	 */
	private $options;

	/**
	 * Instantiate the class
	 *
	 * @param Options $options Options instance.
	 */
	public function __construct( Options $options ) {
		$this->options = $options;
	}

	/**
	 * Gets the customer data associated with the API key
	 *
	 * @param string $api_key the API key to authenticate the request.
	 *
	 * @return array
	 */
	public function get_customer_data( $api_key = '' ): array {
		$cache = get_transient( 'rocketcdn_customer_data' );

		if ( false !== $cache ) {
			return $cache;
		}

		$result = $this->get_raw_customer_data( $api_key );

		if ( empty( $result ) ) {
			return [];
		}

		set_transient( 'rocketcdn_customer_data', $result, 2 * DAY_IN_SECONDS );

		return $result;
	}

	/**
	 * Gets the customer data from the API
	 *
	 * @param string $api_key the API key to authenticate the request.
	 *
	 * @return array
	 */
	private function get_raw_customer_data( $api_key = '' ): array {

		if ( empty( $api_key ) ) {
			return [];
		}

		$response = wp_remote_get(
			$this->get_base_api_url() . 'customer/me',
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

	/**
	 * Checks if the website is synchronized with a RocketCDN subscription
	 *
	 * @param string $api_key the API key to authenticate the request.
	 *
	 * @return bool
	 */
	public function is_website_sync( $api_key = '' ): bool {
		$customer_data = $this->get_customer_data( $api_key );

		if ( empty( $customer_data['websites'] ) ) {
			return false;
		}

		$hostname = $this->extract_hostname( home_url() );

		foreach ( $customer_data['websites'] as $website ) {
			if ( ! isset( $website['hostname'] ) ) {
				continue;
			}

			if ( $hostname === $website['hostname'] ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Gets the CDN URL associated with this website
	 *
	 * @return string
	 */
	public function get_website_cdn_url(): string {
		$customer_data = $this->get_customer_data();

		if ( empty( $customer_data['websites'] ) ) {
			return '';
		}

		$hostname = $this->extract_hostname( home_url() );

		foreach ( $customer_data['websites'] as $website ) {
			if ( ! isset( $website['hostname'] ) ) {
				continue;
			}

			if ( $hostname !== $website['hostname'] ) {
				continue;
			}

			if ( ! isset( $website['cdn_url'] ) ) {
				return '';
			}

			return $website['cdn_url'];
		}

		return '';
	}

	/**
	 * Purges the cache for the current CDN URL
	 *
	 * @return array
	 */
	public function purge_cache(): array {
		$api_key = $this->options->get( 'api_key' );

		if ( empty( $api_key ) ) {
			return [
				'success' => false,
				'message' => __( 'Your API key is empty.', 'rocketcdn' ),
			];
		}

		$cdn_url = $this->options->get( 'cdn_url' );

		if ( empty( $cdn_url ) ) {
			return [
				'success' => false,
				'message' => __( 'Your CDN URL is empty.', 'rocketcdn' ),
			];
		}

		$cdn_url = preg_replace( '#^(https?:)?\/\/#im', '', $cdn_url );

		$response = wp_remote_request(
			$this->get_base_api_url() . "website/{$cdn_url}/purge",
			[
				'method'  => 'DELETE',
				'headers' => [
					'Authorization' => 'token ' . $api_key,
				],
			]
		);

		if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return [
				'success' => false,
				'message' => __( 'The purge cache request failed.', 'rocketcdn' ),
			];
		}

		$body = wp_remote_retrieve_body( $response );

		if ( empty( $body ) ) {
			return [
				'success' => false,
				'message' => __( 'The purge cache request failed.', 'rocketcdn' ),
			];
		}

		return json_decode( $body, true );
	}

	/**
	 * Returns the base API URL
	 *
	 * @return string
	 */
	private function get_base_api_url(): string {
		/**
		 * Filters the base API URL
		 *
		 * @param string $api_url API URL.
		 */
		return apply_filters( 'rocketcdn_base_api_url', self::ROCKETCDN_API );
	}

	/**
	 * Extracts the hostname (host + path if set) from the URL
	 *
	 * @param string $url URL to parse.
	 *
	 * @return string
	 */
	private function extract_hostname( string $url ): string {
		$parsed_url = wp_parse_url( $url );
		$path       = '';

		if ( ! $parsed_url ) {
			return '';
		}

		if ( empty( $parsed_url['host'] ) ) {
			return '';
		}

		if (
			! empty( $parsed_url['path'] )
			&&
			'/' !== $parsed_url['path']
		) {
			$path = $parsed_url['path'];
		}

		return $parsed_url['host'] . $path;
	}
}
