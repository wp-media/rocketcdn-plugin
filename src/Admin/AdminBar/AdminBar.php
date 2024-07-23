<?php
declare(strict_types=1);

namespace RocketCDN\Admin\AdminBar;

use RocketCDN\API\Client;
use RocketCDN\Dependencies\LaunchpadFrameworkOptions\Interfaces\OptionsAwareInterface;
use RocketCDN\Dependencies\LaunchpadFrameworkOptions\Traits\OptionsAwareTrait;

class AdminBar implements OptionsAwareInterface {
	use OptionsAwareTrait;

	/**
	 * API client instance
	 *
	 * @var Client
	 */
	private $api_client;

	/**
	 * Assets base URL
	 *
	 * @var string
	 */
	private $assets_baseurl;

	/**
	 * Instantiate the class
	 *
	 * @param Client $api_client API client instance.
	 * @param string $assets_baseurl Assets base URL.
	 */
	public function __construct( Client $api_client, $assets_baseurl ) {
		$this->api_client     = $api_client;
		$this->assets_baseurl = $assets_baseurl;
	}

	/**
	 * Adds RocketCDN admin bar menu
	 *
	 * @param \WP_Admin_Bar $wp_admin_bar WP_Admin_Bar instance.
	 *
	 * @return void
	 */
	public function add_admin_bar_menu( $wp_admin_bar ) {
		if ( ! is_admin() ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$api_key = $this->options->get( 'api_key', '' );

		if (
			empty( $api_key )
			||
			empty( $this->api_client->get_customer_data( $api_key ) )
		) {
			$wp_admin_bar->add_node(
				[
					'id'    => 'rocketcdn',
					'title' => '<span class="rocketcdn-admin-bar-status rocketcdn-admin-bar-status-disconnected">' . __( 'RocketCDN is disconnected', 'rocketcdn' ) . '</span>',
				]
			);

			$wp_admin_bar->add_node(
				[
					'id'     => 'rocketcdn-connect',
					'title'  => __( 'Connect RocketCDN', 'rocketcdn' ),
					'href'   => admin_url( 'options-general.php?page=rocketcdn' ),
					'parent' => 'rocketcdn',
				]
			);

			return;
		}

		$wp_admin_bar->add_node(
			[
				'id'    => 'rocketcdn',
				'title' => '<span class="rocketcdn-admin-bar-status rocketcdn-admin-bar-status-connected">' . __( 'RocketCDN', 'rocketcdn' ) . '</span>',
			]
		);

		$wp_admin_bar->add_node(
			[
				'id'     => 'rocketcdn-settings',
				'title'  => __( 'Settings', 'rocketcdn' ),
				'href'   => admin_url( 'options-general.php?page=rocketcdn' ),
				'parent' => 'rocketcdn',
			]
		);

		$referer = '';

		if ( ! empty( $_SERVER['REQUEST_URI'] ) ) {
			$referer = filter_var( wp_unslash( $_SERVER['REQUEST_URI'] ), FILTER_SANITIZE_URL );
			$referer = '&_wp_http_referer=' . rawurlencode( $referer );
		}

		$wp_admin_bar->add_node(
			[
				'id'     => 'rocketcdn-purge-cache',
				'title'  => __( 'Purge cache', 'rocketcdn' ),
				'href'   => wp_nonce_url( admin_url( 'admin-post.php?action=rocketcdn-purge-cache' . $referer ), 'rocketcdn_purge_cache' ),
				'parent' => 'rocketcdn',
			]
		);

		$wp_admin_bar->add_node(
			[
				'id'     => 'rocketcdn-faq',
				'title'  => __( 'FAQ', 'rocketcdn' ),
				'href'   => 'https://rocketcdn.me/faq/',
				'parent' => 'rocketcdn',
				'meta'   => [
					'rel'    => 'noopener',
					'target' => '_blank',
				],
			]
		);

		$wp_admin_bar->add_node(
			[
				'id'     => 'rocketcdn-support',
				'title'  => __( 'Support', 'rocketcdn' ),
				'href'   => 'https://rocketcdn.me/contact/',
				'parent' => 'rocketcdn',
				'meta'   => [
					'rel'    => 'noopener',
					'target' => '_blank',
					'html'   => '<div class="rocketcdn-admin-bar-subscription"><a href="https://rocketcdn.me/account/billing/" rel="noopener" target="_blank" class="rocketcdn-admin-bar-subscription-link">' . __( 'View my subscription', 'rocketcdn' ) . '</a></div>',
				],
			]
		);
	}

	/**
	 * Purges the cache from the admin bar
	 *
	 * @return void
	 */
	public function purge_cache() {
		if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_GET['_wpnonce'] ), 'rocketcdn_purge_cache' ) ) {
			wp_nonce_ays( '' );
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die();
			return;
		}

		$this->api_client->purge_cache();

		wp_safe_redirect( esc_url_raw( wp_get_referer() ) );
		$this->exit();
	}

	/**
	 * Wrapped exit function to test
	 *
	 * @return void
	 */
	protected function exit() {
		defined( 'WP_ROCKET_CDN_IS_TESTING' ) && constant( 'WP_ROCKET_CDN_IS_TESTING' ) ? wp_die() : exit;
	}

	/**
	 * Enqueue style for the custom part of the admin bar
	 *
	 * @return void
	 */
	public function enqueue_style() {
		wp_enqueue_style( 'rocketcdn-admin-bar', $this->assets_baseurl . 'css/admin-bar.css', [], ROCKETCDN_VERSION );
	}
}
