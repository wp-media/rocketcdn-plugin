<?php

defined( 'ABSPATH' ) || exit;

?>
<div class="wrap">
	<hr class="wp-header-end">
	<div class="rocketcdn-wrapper">
		<section class="rocketcdn-settings-header">
			<h1 class="rocketcdn-logo"><span class="screen-reader-text"><?php echo esc_html( get_admin_page_title() ); ?></span></h1>
			<p><?php esc_html_e( 'Welcome to RocketCDN, the best way to deliver your content at the speed of light!', 'rocketcdn' ); ?></p>
		</section>
		<div id="rocketcdn-error-notice" class="rocketcdn-notice">
			<p></p>
			<button class="rocketcdn-notice-dismiss">
				<span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice', 'rocketcdn' ); ?></span>
			</button>
		</div>
		<div class="rocketcdn-settings">
			<section class="rocketcdn-settings-activate">
				<div>
					<h2 class="rocketcdn-section-title"><?php esc_html_e( 'Activate RocketCDN', 'rocketcdn' ); ?></h2>
					<p class="rocketcdn-section-subtitle"><?php esc_html_e( 'Enter your API key to connect your website with RocketCDN.', 'rocketcdn' ); ?></p>
					<p>
						<?php
						printf(
							// translators: %1$s = opening link tag, %2$s = closing link tag.
							esc_html__( 'You can find your API key in your %1$sRocketCDN account%2$s.', 'rocketcdn' ),
							'<a href="https://rocketcdn.me/account/" target="_blank" rel="noopener">',
							'</a>'
						);
						?>
					</p>
					<form action="options.php" method="post" id="rocketcdn-no-key">
						<input type="text" name="rocketcdn_api_key" id="rocketcdn_api_key" value="<?php echo esc_attr( $this->options->get( 'api_key' ) ); ?>" />
						<?php settings_fields( 'rocketcdn' ); ?>
						<p>
							<button type="submit" class="rocketcdn-submit">
								<span class="rocketcdn-submit-content"><?php esc_html_e( 'Save', 'rocketcdn' ); ?></span>
							</button>
						</p>
					</form>
				</div>
			</section>
			<p class="rocketcdn-settings-separator"><?php esc_html_e( 'Or', 'rocketcdn' ); ?></p>
			<section class="rocketcdn-settings-create">
				<div class="rocketcdn-settings-create-block">
					<h2 class="rocketcdn-section-title"><?php esc_html_e( 'Create an account', 'rocketcdn' ); ?></h2>
					<p class="rocketcdn-section-subtitle"><?php esc_html_e( "Don't have an API key yet?", 'rocketcdn' ); ?></p>
					<p><?php esc_html_e( 'Get a RocketCDN subscription to make your website faster and your visitors happier!', 'rocketcdn' ); ?></p>
					<p class="rocketcdn-get-started-container"><a href="https://rocketcdn.me/pricing/" target="_blank" rel="noopener" class="rocketcdn-get-started"><?php esc_html_e( 'Get started', 'rocketcdn' ); ?></a></p>
				</div>
			</section>
		</div>
	</div>
</div>
