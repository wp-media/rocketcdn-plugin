<?php
declare(strict_types=1);

namespace RocketCDN\Front;

use RocketCDN\Options\Options;

class CDN {
	/**
	 * Options instance
	 *
	 * @var Options
	 */
	private $options;

	/**
	 * Home URL host
	 *
	 * @var string
	 */
	private $home_host = '';

	/**
	 * Instantiates the class
	 *
	 * @param Options $options Options instance.
	 */
	public function __construct( Options $options ) {
		$this->options = $options;
	}

	/**
	 * Setup output buffering
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function start_buffering() {
		ob_start( [ $this->cdn, 'end_buffering' ] );
	}

	/**
	 * Processes the buffer content and return it
	 *
	 * @param string $html HTML content.
	 *
	 * @return string
	 */
	public function end_buffering( $html ) {
		$html = $this->rewrite( $html );
		$html = $this->rewrite_srcset( $html );

		return $html;
	}

	/**
	 * Search & Replace URLs with the CDN URLs in the provided content
	 *
	 * @since 1.0
	 *
	 * @param string $html HTML content.
	 * @return string
	 */
	private function rewrite( $html ) {
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
	 * @since 1.0
	 *
	 * @param string $html HTML content.
	 * @return string
	 */
	private function rewrite_srcset( $html ) {
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
	 * @since 1.0
	 *
	 * @param string $url Original URL.
	 * @return string
	 */
	public function rewrite_url( $url ) {
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
	 * @since 1.0
	 *
	 * @return string
	 */
	private function get_base_url() {
		return '//' . $this->get_home_host();
	}

	/**
	 * Gets the allowed paths as a regex pattern for the CDN rewrite
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	private function get_allowed_paths() {
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
	 * @since 1.0
	 *
	 * @return string
	 */
	private function get_home_host() {
		if ( empty( $this->home_host ) ) {
			$this->home_host = wp_parse_url( home_url(), PHP_URL_HOST );
		}

		return $this->home_host;
	}

	/**
	 * Get srcset attributes to rewrite to the CDN.
	 *
	 * @since 1.0
	 *
	 * @return string A pipe-separated list of srcset attributes.
	 */
	private function get_srcset_attributes() {
		/**
		 * Filter the srcset attributes.
		 *
		 * @since 3.8.7
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
}
