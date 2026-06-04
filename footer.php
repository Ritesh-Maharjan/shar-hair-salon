<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package BDW_Massage
 */

?>

<footer id="colophon">
	<div class="site-footer">

		<div class="footer-contact">
			<h3>Contact</h3>
			<?php if ( function_exists('get_field') ) : ?>
				<?php $address = get_field('address', 'option'); ?>
				<?php $phone   = get_field('phone',   'option'); ?>
				<?php $email   = get_field('email',   'option'); ?>
				<?php $insta   = get_field('insta',   'option'); ?>
				<?php if ( $address ) : ?>
				<p><a href="https://www.google.ca/maps/place/<?php echo esc_attr( $address ); ?>" target="_blank"><?php echo esc_html( $address ); ?></a></p>
				<?php endif; ?>
				<?php if ( $phone ) : ?>
				<p><a href="tel:<?php echo esc_attr( $phone ); ?>"><?php echo esc_html( $phone ); ?></a></p>
				<?php endif; ?>
				<?php if ( $email ) : ?>
				<p><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></p>
				<?php endif; ?>
				<?php if ( $insta ) : ?>
				<p><a href="<?php echo esc_url( $insta ); ?>">Instagram</a></p>
				<?php endif; ?>
			<?php else : ?>
				<p>Contact information coming soon.</p>
			<?php endif; ?>
		</div><!-- .footer-contact -->

		<div class="footer-mid">

			<nav>
				<?php wp_nav_menu(
					array(
						'theme_location' => 'footer-middle',
						'menu_id' => 'footer-menu',
						'items_wrap' => '<ul id="%1$s" class="footer-menu %2$s">%3$s</ul>'
					)
				); ?>

			</nav>
			<a class="cta-btn" href="/service/">
				Book Now
			</a>
			<p class="made-with-love">
				&copy; <?php echo date('Y'); ?> Shar Hair Salon
			</p>
		</div>
		<div class="opening-time">
			<h3>Opening Hours</h3>
			<?php
			// Specify the sidebar ID or name where the widget is registered
			$sidebar_id = 'footer-sidebar';

			// Check if the specified sidebar is active and has widgets
			if (is_active_sidebar($sidebar_id)) {
				// Display the widgets in the specified sidebar
				dynamic_sidebar($sidebar_id);
			}
			?>
		</div>
	</div>
</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>

</html>