<?php

defined( 'ABSPATH' ) || exit;

?>
<div class="wrap">
	<div>
		<h1 class="screen-reader-text"><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<?php esc_html_e( 'Welcome to RocketCDN, the best way to deliver your content at the speed of light!', 'rocketcdn' ); ?>
	</div>
	<div>
		<section>
			<h2><?php esc_html_e( 'Activate RocketCDN', 'rocketcdn' ); ?></h2>
			<p><?php esc_html_e( 'Enter your API key to connect your website with RocketCDN.', 'rocketcdn' ); ?></p>
			<p>
				<?php
				printf(
					// translators: %1$s = opening link tag, %2$s = closing link tag.
					esc_html__( 'You can find your API key in your %1$sRocketCDN account%2$s', 'rocketcdn' ),
					'<a href="https://rocketcdn.me/account/" target="_blank" rel="noopener">',
					'</a>'
				);
				?>
			</p>
			<form action="options.php" method="post">
				<input type="text" name="rocketcdn_api_key" value="<?php echo esc_attr( $this->options->get( 'api_key' ) ); ?>" />
				<?php settings_fields( 'rocketcdn' ); ?>
				<input type="submit" value="<?php esc_attr_e( 'Save', 'rocketcdn' ); ?>" />
			</form>
		</section>
		<section>
			<h2><?php esc_html_e( 'Create an account', 'rocketcdn' ); ?></h2>
			<p><?php esc_html_e( 'Don\'t have an API key yet?', 'rocketcdn' ); ?></p>
			<p><?php esc_html_e( 'Get a RocketCDN subscription to make your website faster and your visitors happier!', 'rocketcdn' ); ?></p>
			<a href="https://rocketcdn.me/pricing/" target="_blank" rel="noopener"><?php esc_html_e( 'Get started', 'rocketcdn' ); ?></a>
		</section>
	</div>
</div>
