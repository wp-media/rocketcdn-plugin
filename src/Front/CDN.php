<?php
declare(strict_types=1);

namespace RocketCDN\Front;

use RocketCDN\Dependencies\LaunchpadFrameworkOptions\Interfaces\OptionsAwareInterface;
use RocketCDN\Dependencies\LaunchpadFrameworkOptions\Traits\OptionsAwareTrait;
use RocketCDN\Dependencies\LaunchpadOptions\Interfaces\OptionsInterface;

class CDN implements OptionsAwareInterface {
	use OptionsAwareTrait;
	/**
	 * Home URL host
	 *
	 * @var string
	 */
	private $home_host = '';

	/**
	 * Setup output buffering
	 *
	 * @return void
	 */
	public function start_buffering() {
		if ( ! $this->should_rewrite() ) {
			return;
		}

		ob_start( [ $this, 'end_buffering' ] );
	}

	/**
	 * Stop buffering and return the buffer contents
	 *
	 * @param string $html HTML content.
	 *
	 * @return string
	 */
	public function end_buffering( $html ): string {
		$html = $this->rewrite( $html );
		$html = $this->rewrite_srcset( $html );

		return $html;
	}

	/**
	 * Checks if we should rewrite the content
	 *
	 * @return bool
	 */
	protected function should_rewrite(): bool {
		if (
			! isset( $_SERVER['REQUEST_METHOD'] )
			||
			'GET' !== $_SERVER['REQUEST_METHOD']
		) {
			return false;
		}

		if (
			is_admin()
			||
			is_customize_preview()
			||
			is_embed()
		) {
			return false;
		}

		return true;
	}

	/**
	 * Search & Replace URLs with the CDN URLs in the provided content
	 *
	 * @param string $html HTML content.
	 * @return string
	 */
	private function rewrite( string $html ): string {
		$pattern = '#[("\']\s*(?<url>(?:(?:https?:|)' . preg_quote( $this->get_base_url(), '#' ) . ')\/(?:(?:(?:' . $this->get_allowed_paths() . ')[^"\',)]+))|\/[^/](?:[^"\')\s>]+\.[[:alnum:]]+))\s*["\')]#i';

		return preg_replace_callback(
			$pattern,
			function( $matches ) {
				return str_replace( $matches['url'], $this->rewrite_url( $matches['url'] ), $matches[0] );
			},
			$html
		);
	}

	/**
	 * Rewrites URLs in a srcset attribute using the CDN URL
	 *
	 * @param string $html HTML content.
	 * @return string
	 */
	private function rewrite_srcset( string $html ): string {
		$pattern = '#\s+(?:' . $this->get_srcset_attributes() . ')?srcset\s*=\s*["\']\s*(?<sources>[^"\',\s]+\.[^"\',\s]+(?:\s+\d+[wx])?(?:\s*,\s*[^"\',\s]+\.[^"\',\s]+(?:\s+\d+[wx])?)*)\s*["\']#i';

		if ( ! preg_match_all( $pattern, $html, $srcsets, PREG_SET_ORDER ) ) {
			return $html;
		}
		foreach ( $srcsets as $srcset ) {
			$sources    = explode( ',', $srcset['sources'] );
			$sources    = array_unique( array_map( 'trim', $sources ) );
			$cdn_srcset = $srcset['sources'];
			foreach ( $sources as $source ) {
				$url        = preg_split( '#\s+#', trim( $source ) );
				$cdn_source = str_replace( $url[0], $this->rewrite_url( $url[0] ), $source );
				$cdn_srcset = str_replace( $source, $cdn_source, $cdn_srcset );
			}

			$cdn_srcsets = str_replace( $srcset['sources'], $cdn_srcset, $srcset[0] );
			$html        = str_replace( $srcset[0], $cdn_srcsets, $html );
		}

		return $html;
	}

	/**
	 * Rewrites an URL with the CDN URL
	 *
	 * @param string $url Original URL.
	 * @return string
	 */
	public function rewrite_url( string $url ): string {
		if ( false !== stripos( $url, admin_url() ) ) {
			return $url;
		}

		$cdn_url = $this->options->get( 'cdn_url' );

		if ( ! $cdn_url ) {
			return $url;
		}

		$parsed_url = wp_parse_url( $url );

		if ( ! isset( $parsed_url['host'] ) ) {
			return $cdn_url . '/' . ltrim( $url, '/' );
		}

		$home_host = $this->get_home_host();

		if ( ! isset( $parsed_url['scheme'] ) ) {
			$cdn_url = preg_replace( '#^(https?:)?\/\/#im', '', $cdn_url );

			return str_replace( $home_host, $cdn_url, $url );
		}

		$home_url = [
			'http://' . $home_host,
			'https://' . $home_host,
		];

		return str_replace( $home_url, $cdn_url, $url );
	}

	/**
	 * Gets the base URL for the website
	 *
	 * @return string
	 */
	private function get_base_url(): string {
		return '//' . $this->get_home_host();
	}

	/**
	 * Gets the allowed paths as a regex pattern for the CDN rewrite
	 *
	 * @return string
	 */
	private function get_allowed_paths(): string {
		$wp_content_dirname  = ltrim( trailingslashit( wp_parse_url( content_url(), PHP_URL_PATH ) ), '/' );
		$wp_includes_dirname = ltrim( trailingslashit( wp_parse_url( includes_url(), PHP_URL_PATH ) ), '/' );

		$upload_dirname = '';
		$uploads_info   = wp_upload_dir();

		if ( ! empty( $uploads_info['baseurl'] ) ) {
			$upload_dirname = '|' . ltrim( trailingslashit( wp_parse_url( $uploads_info['baseurl'], PHP_URL_PATH ) ), '/' );
		}

		return $wp_content_dirname . $upload_dirname . '|' . $wp_includes_dirname;
	}

	/**
	 * Gets the home URL host
	 *
	 * @return string
	 */
	private function get_home_host(): string {
		if ( empty( $this->home_host ) ) {
			$this->home_host = wp_parse_url( home_url(), PHP_URL_HOST );
		}

		return $this->home_host;
	}

	/**
	 * Get srcset attributes to rewrite to the CDN.
	 *
	 * @return string
	 */
	private function get_srcset_attributes(): string {
		/**
		 * Filter the srcset attributes.
		 *
		 * @param array $srcset_attributes List of srcset attributes.
		 */
		$srcset_attributes = (array) apply_filters(
			'rocketcdn_srcset_attributes',
			[
				'data-lazy-',
				'data-',
			]
		);
		return implode( '|', $srcset_attributes );
	}

	/**
	 * Adds a preconnect tag for the CDN.
	 *
	 * @param array  $urls          The initial array of wp_resource_hint urls.
	 * @param string $relation_type The relation type for the hint: eg., 'preconnect', 'prerender', etc.
	 *
	 * @return array
	 */
	public function add_preconnect_cdn( array $urls, string $relation_type ): array {
		if (
			'preconnect' !== $relation_type
			||
			! $this->should_rewrite()
		) {
			return $urls;
		}

		$cdn_url = $this->options->get( 'cdn_url' );

		if ( empty( $cdn_url ) ) {
			return $urls;
		}

		// Note: As of 22 Feb, 2021 we cannot add more than one instance of a domain url
		// on the wp_resource_hint() hook -- wp_resource_hint() will
		// only actually print the first one.
		// Ideally, we want both because CSS resources will use the crossorigin version,
		// But JS resources will not.
		// Jonathan has submitted a ticket to change this behavior:
		// @see https://core.trac.wordpress.org/ticket/52465
		// Until then, we order these to prefer/print the non-crossorigin version.
		$urls[] = [ 'href' => $cdn_url ];
		$urls[] = [
			'href'        => $cdn_url,
			'crossorigin' => 'anonymous',
		];

		return $urls;
	}
}
