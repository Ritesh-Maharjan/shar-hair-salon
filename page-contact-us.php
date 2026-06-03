<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package BDW_Massage
 */

get_header();
?>

<main id="primary" class="site-main">

	<header class="entry-header">
		<?php the_title('<h1 class="entry-title">', '</h1>'); ?>
	</header><!-- .entry-header -->

	<section class="contact-us">
		<p>
			<?php
			get_template_part("images/phone");
			?>

			<?php
			if (function_exists('get_field')) {
				if (get_field('phone')) {
					echo get_field('phone');
				}
			}
			?>
		</p>
		<p>
			<?php
			get_template_part("images/address");
			?>
			<?php

			if (function_exists('get_field')) {
				if (get_field('address')) {
					echo get_field('address');
				}
			} ?>

		</p>

		<p>
			<?php
			get_template_part("images/mail");
			?>

			<?php

			if (function_exists('get_field')) {
				if (get_field('email')) {
					echo get_field('email');
				}
			} ?>
		</p>
	</section>

	<section class="contact-content">

		<?php

		the_content();

		?>
	</section>
</main><!-- #main -->

<?php
get_footer();
