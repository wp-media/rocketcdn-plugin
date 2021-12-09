<?php
declare(strict_types=1);

namespace RocketCDN\Admin\AdminBar;

use RocketCDN\API\Client;
use RocketCDN\Options\Options;

class AdminBar {
	/**
	 * Options instance
	 *
	 * @var Options
	 */
	private $options;

	private $api_client;

	/**
	 * Instantiate the class
	 *
	 * @param Options $options Options instance.
	 */
	public function __construct( Options $options, Client $api_client ) {
		$this->options    = $options;
		$this->api_client = $api_client;
	}

	/**
	 * Adds RocketCDN admin bar menu
	 *
	 * @param \WP_Admin_Bar $wp_admin_bar WP_Admin_Bar instance.
	 *
	 * @return void
	 */
	public function add_admin_bar_menu( $wp_admin_bar ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if (
			empty( $this->options->get( 'api_key' ) )
			||
			empty( $this->api_client->get_customer_data() )
		) {
			$wp_admin_bar->add_node(
				[
					'id'    => 'rocketcdn',
					'title' => __( 'RocketCDN is disconnected', 'rocketcdn' ),
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
				'title' => __( 'RocketCDN', 'rocketcdn' ),
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
					'html'   => '<a href="https://rocketcdn.me/account/billing/" rel="noopener" target="_blank">' . __( 'View my subscription', 'rocketcdn' ) . '</a>',
				],
			]
		);
	}

	public function purge_cache() {
		if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_GET['_wpnonce'] ), 'rocketcdn_purge_cache' ) ) {
			wp_nonce_ays( '' );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$this->api_client->purge_cache();

		wp_safe_redirect( esc_url_raw( wp_get_referer() ) );
		exit;
	}
}
