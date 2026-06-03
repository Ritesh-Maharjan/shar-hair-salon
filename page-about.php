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
 * @package shar-hair-salon
 */

get_header();
?>

	<main id="primary" class="site-main">
		<div class="about">
			<h1>About Us</h1>
		</div>
		<?php

           while ( have_posts() ) :
			the_post();
			the_content();
		    endwhile; 
		?>

	</main>

<?php
get_footer();
?>