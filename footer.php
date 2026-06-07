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

		<!-- Left: Contact info -->
		<div class="footer-contact">
			<h3>Contact</h3>

			<?php if ( $address ) : ?>
			<p>
				<a href="https://www.google.com/maps/search/<?php echo rawurlencode( $address ); ?>" target="_blank" rel="noopener">
					<?php echo esc_html( $address ); ?>
				</a>
			</p>
			<?php endif; ?>

			<?php if ( $phone ) : ?>
			<p><a href="tel:<?php echo esc_attr( $phone ); ?>"><?php echo esc_html( $phone ); ?></a></p>
			<?php endif; ?>

			<?php if ( $email ) : ?>
			<p><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></p>
			<?php endif; ?>

			<?php if ( $insta ) : ?>
			<p><a href="<?php echo esc_url( $insta ); ?>" target="_blank" rel="noopener">Instagram</a></p>
			<?php endif; ?>

			<?php if ( $facebook ) : ?>
			<p><a href="<?php echo esc_url( $facebook ); ?>" target="_blank" rel="noopener">Facebook</a></p>
			<?php endif; ?>
		</div>

		<!-- Centre: Nav + Book Now + Copyright -->
		<div class="footer-mid">
			<h3>Menu</h3>
			<?php wp_nav_menu( [
				'theme_location' => 'footer-middle',
				'menu_id'        => 'footer-menu',
				'items_wrap'     => '<ul id="%1$s" class="footer-menu %2$s">%3$s</ul>',
				'fallback_cb'    => false,
			] ); ?>
			<a class="cta-btn" href="/services/">Book Now</a>
			<p class="made-with-love">&copy; <?php echo date( 'Y' ); ?> Shar Hair Salon</p>
		</div>

		<!-- Right: Opening hours -->
		<div class="opening-time">
			<h3>Hours</h3>
			<?php shar_business_hours_html(); ?>
		</div>

	</div>
</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
