<?php

defined( 'ABSPATH' ) || exit;

?>
<div class="wrap">
	<div>
		<h1 class="screen-reader-text"><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<?php esc_html_e( 'Please rate RocketCDN on wordpress.org to help us spread the word. Thank you!', 'rocketcdn' ); ?>
	</div>
	<div>
		<div id="rocketcdn-error-notice"></div>
		<section>
			<h2><?php esc_html_e( 'CDN Settings', 'rocketcdn' ); ?></h2>
			<form action="options.php" method="post" id="rocketcdn-has-key">
				<label for="rocketcdn_api_key"><?php esc_html_e( 'API key', 'rocketcdn' ); ?></label>
				<input type="text" name="rocketcdn_api_key" id="rocketcdn_api_key" value="<?php echo esc_attr( $this->options->get( 'api_key' ) ); ?>" />
			</form>
			<?php esc_html_e( 'Purge Cache', 'rocketcdn' ); ?>
			<button id="rocketcdn-purge-cache"><?php esc_html_e( 'Clear cache', 'rocketcdn' ); ?></button><span id="rocketcdn-purge-cache-result"></span>
			<p><?php esc_html_e( "Clear your CDN cache to make sure your site shows your content's most recent version.", 'rocketcdn' ); ?></p>
		</section>
		<section>
			<h2><?php esc_html_e( 'Your subscription', 'rocketcdn' ); ?></h2>
			<p><?php esc_html_e( 'Your CDN URL is:', 'rocketcdn' ); ?></p>
			<p><?php echo esc_url( $this->options->get( 'cdn_url' ) ); ?></p>
			<a href="https://rocketcdn.me/account/sites/" target="_blank" rel="noopener"><?php esc_html_e( 'View my subscription', 'rocketcdn' ); ?></a>
		</section>
	</div>
</div>

