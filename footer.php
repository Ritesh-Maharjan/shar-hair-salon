<?php
/**
 * The template for displaying the footer
 *
 * @package SHAR_HAIR_SALON
 */

$address  = get_theme_mod( 'shar_contact_address', '' );
$phone    = get_theme_mod( 'shar_contact_phone',   '' );
$email    = get_theme_mod( 'shar_contact_email',   '' );
$insta    = get_theme_mod( 'shar_contact_insta',   '' );
$facebook = get_theme_mod( 'shar_contact_facebook', '' );
?>

<footer id="colophon">
	<div class="site-footer">

		<!-- Col 1: Logo -->
		<div class="footer-contact">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer-logo">
				<?php
				$logo_id = get_theme_mod( 'custom_logo' );
				if ( $logo_id ) {
					echo wp_get_attachment_image( $logo_id, 'medium', false, [ 'class' => 'footer-logo__img' ] );
				} else {
					echo '<span class="footer-logo__text">' . esc_html( get_bloginfo( 'name' ) ) . '</span>';
				}
				?>
			</a>
		</div>

		<!-- Col 2: Nav links -->
		<div class="footer-nav">
			<?php wp_nav_menu( [
				'theme_location' => 'footer-middle',
				'menu_id'        => 'footer-menu',
				'items_wrap'     => '<ul id="%1$s" class="footer-menu %2$s">%3$s</ul>',
				'fallback_cb'    => false,
			] ); ?>
		</div>

		<!-- Col 3: Social -->
		<div class="footer-social">
			<?php if ( $facebook ) : ?>
			<p><a href="<?php echo esc_url( $facebook ); ?>" target="_blank" rel="noopener">Facebook</a></p>
			<?php endif; ?>
			<?php if ( $insta ) : ?>
			<p><a href="<?php echo esc_url( $insta ); ?>" target="_blank" rel="noopener">Instagram</a></p>
			<?php endif; ?>
		</div>

		<!-- Col 4: Hours -->
		<div class="opening-time">
			<?php shar_business_hours_html(); ?>
		</div>

	</div>

	<div class="footer-bottom">
		<p>&copy; <?php echo date( 'Y' ); ?> Shar Hair Salon</p>
	</div>
</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
