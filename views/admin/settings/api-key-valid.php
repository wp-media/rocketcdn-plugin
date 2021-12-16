<?php

defined( 'ABSPATH' ) || exit;

?>
<div class="wrap">
	<hr class="wp-header-end">
	<div class="rocketcdn-wrapper">
		<section class="rocketcdn-settings-header">
			<h1 class="rocketcdn-logo"><span class="screen-reader-text"><?php echo esc_html( get_admin_page_title() ); ?></span></h1>
			<p>
				<?php
				printf(
					// translators: %1$s = opening link tag, %2$s = closing link tag, %3$s = star icon.
					esc_html__( 'Please rate %1$sRocketCDN on wordpress.org%2$s %3$s %3$s %3$s %3$s %3$s to help us spread the word. Thank you!', 'rocketcdn' ),
					'<a href="https://wordpress.org/support/view/plugin-reviews/rocketcdn?rate=5#postform" target="_blank" rel="noopener">',
					'</a>',
					'<span class="rocketcdn-rating-star">&#9733;</span>'
				);
				?>
			</p>
		</section>
		<div id="rocketcdn-error-notice" class="rocketcdn-notice">
			<p></p>
			<button class="rocketcdn-notice-dismiss">
				<span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice', 'rocketcdn' ); ?></span>
			</button>
		</div>
		<div class="rocketcdn-settings-container">
			<section class="rocketcdn-settings-block rocketcdn-settings-subscription">
				<h2 class="rocketcdn-section-title"><?php esc_html_e( 'Your subscription', 'rocketcdn' ); ?></h2>
				<div class="rocketcdn-url-block">
					<p><strong><?php esc_html_e( 'Your CDN URL is:', 'rocketcdn' ); ?></strong><br>
					<?php echo esc_url( $this->options->get( 'cdn_url' ) ); ?></p>
					<a href="https://rocketcdn.me/account/sites/" target="_blank" rel="noopener" class="rocketcdn-view-subscription"><?php esc_html_e( 'View my subscription', 'rocketcdn' ); ?></a>
				</div>
			</section>
			<section class="rocketcdn-settings-block rocketcdn-settings-config">
				<h2 class="rocketcdn-section-title"><?php esc_html_e( 'CDN Settings', 'rocketcdn' ); ?></h2>
				<form action="options.php" method="post" id="rocketcdn-has-key" class="rocketcdn-form-key">
					<p class="rocketcdn-settings-title"><label for="rocketcdn_api_key"><?php esc_html_e( 'API key', 'rocketcdn' ); ?></label></p>
					<input type="text" name="rocketcdn_api_key" id="rocketcdn_api_key" value="<?php echo esc_attr( $this->options->get( 'api_key' ) ); ?>" />
				</form>
				<div class="rocketcdn-section-purge-cache">
					<p class="rocketcdn-settings-title"><?php esc_html_e( 'Purge Cache', 'rocketcdn' ); ?></p>
					<div>
						<div class="rocketcdn-purge-cache-action">
							<div id="rocketcdn-purge-cache-result" class="rocketcdn-purge-cache-result"></div>
							<button id="rocketcdn-purge-cache" class="rocketcdn-purge-cache"><?php esc_html_e( 'Clear cache', 'rocketcdn' ); ?></button>
						</div>
						<p><?php esc_html_e( "Clear your CDN cache to make sure your site shows your content's most recent version.", 'rocketcdn' ); ?></p>
					</div>
				</div>
			</section>
		</div>
	</div>
</div>

