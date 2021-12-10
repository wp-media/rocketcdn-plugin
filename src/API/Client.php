<?php
declare(strict_types=1);

namespace RocketCDN\API;

use RocketCDN\Options\Options;

class Client {
	const ROCKETCDN_API = 'https://staging-rocketcdn.wp-rocket.me/api/';

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
	 * @return array
	 */
	public function get_customer_data( $api_key = '' ): array {
		$cache = get_transient( 'rochetcdn_customer_data' );

		if ( false !== $cache ) {
			return $cache;
		}

		$result = $this->get_raw_customer_data( $api_key );

		if ( empty( $result ) ) {
			return [];
		}

		set_transient( 'rocketcdn_customer_data', $result, HOUR_IN_SECONDS );

		return $result;
	}

	private function get_raw_customer_data( $api_key = '' ): array {
		return [
			'id' => 1,
			'email' => 'jeanbaptiste@wp-media.me',
			'plan' => 'Basic',
			'subscription' => [
			  0 => [
				'id' => 10,
				'has_website_attached' => true,
				'cancel_date' => NULL,
				'creation_date' => '2021-11-29T09:13:35.657148Z',
				'next_date_update' => '2021-12-29T09:13:29Z',
				'customer' => 1,
				'stripe_subscription_id' => 'sub_1K15vREvnUEbK1G25Srvr8ZN',
				'plan' => 1,
				'status' => 'running',
				'is_monthly' => true,
				'website_url' => NULL,
			  ],
			],
			'websites' => [
			  0 => [
				'id' => 8,
				'is_active' => true,
				'cdn_url' => 'https://cname.rocketcdn.me',
				'cdn_id' => '123',
				'url' => 'https://tests.local',
				'subscription_id' => 10,
				'subscription_next_date_update' => '2021-12-29T09:13:29Z',
				'subscription_status' => 'running',
				'creation_date' => '2021-01-01T00:00:00Z',
				'consumption_next_date_update' => '2021-01-01',
				'consumption_last_date_saved' => '2021-01-01T00:00:00Z',
				'callback_url' => 'https://rocketcdn.',
				'status' => 'created',
				'task_id' => '1',
				'hostname' => 'tests.local',
			  ],
			],
			'token' => '8ee561890b3aaf0eb909cdbbd7e1becae81d81a0',
			'wp_rocket_user_id' => 1,
			'stripe_id' => '',
			'subscriptions_left' => 0,
		];

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

	/**
	 * Checks if the website is synchronized with a RocketCDN subscription
	 *
	 * @return bool
	 */
	public function is_website_sync( $api_key = '' ): bool {
		$customer_data = $this->get_customer_data( $api_key );

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
			self::ROCKETCDN_API . "website/{$cdn_url}/purge",
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
}
