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
			<?php

			?>
			<h3>Contact</h3>

			<p> <a href="https://www.google.ca/maps/place/<?php echo get_field('address', 11); ?> " _target><?php get_template_part('images/address');
				 echo get_field('address', 11); ?></a> </p>
			<p> <a href="tel:<?php echo get_field('phone', 11); ?>"><?php get_template_part('images/phone');
				echo get_field('phone', 11); ?></a> </p>
			<p> <a href="mailto:<?php echo get_field('email', 11); ?>"> <?php get_template_part('images/mail');
				echo get_field('email', 11); ?> </a></p>
			<p> <a href="<?php echo get_field('insta', 11); ?>"><?php get_template_part('images/insta'); ?>
					BigDuckWellness</a> </p>
			<?php
			?>
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
			<a class="cta-btn" href="https://bdwmassage.bcitwebdeveloper.ca/service/">
				Book Now
			</a>
			<p class="made-with-love">
				&copy; Made it with love by:<br /> Nikko, William, Baagii, Ritesh
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