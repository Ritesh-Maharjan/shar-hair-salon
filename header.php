<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package BDW_Massage
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<!-- Playfair Display Font -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<div id="page" class="site">
		<a class="skip-link screen-reader-text"
			href="#primary"><?php esc_html_e('Skip to content', 'shar-hair-salon'); ?></a>

		<header id="masthead" class="site-header">
			<div class="site-branding">
				<?php
				$shar_salon_description = get_bloginfo('description', 'display');
				if ($shar_salon_description || is_customize_preview()):
					?>
					<p class="site-description">
						<?php echo $shar_salon_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</p>
				<?php endif; ?>
			</div><!-- .site-branding -->

			<nav id="site-navigation" class="main-navigation">
				<?php $header_img = get_header_image(); ?>
				<?php if ( $header_img ) : ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nav-logo-link">
					<img
						src="<?php echo esc_url( $header_img ); ?>"
						alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
						class="nav-logo"
					>
				</a>
				<?php endif; ?>

				<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
					<span></span>
					<span></span>
					<span></span>
				</button>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'header-left',
						'menu_id' => 'primary-menu-left',
						'fallback_cb' => false,
					)
				);

				wp_nav_menu(
					array(
						'theme_location' => 'header-right',
						'menu_id' => 'primary-menu-right',
						'fallback_cb' => false,
					)
				);
				?>
			</nav><!-- #site-navigation -->
		</header><!-- #masthead -->