<?php
/**
 * Template Name: Contact Page
 * Template for Contact Us page
 *
 * @package SHAR_HAIR_SALON
 */

get_header();
?>

<main id="primary" class="site-main contact-page">

	<section class="contact-hero">
		<h1 class="contact-hero__heading">
			<?php the_title(); ?>
		</h1>

		<div class="contact-form-wrap">
			<?php the_content(); ?>
		</div>
	</section>

	<script>
	// Add placeholders to CF7 inputs since labels are hidden
	(function() {
		const placeholders = {
			'your-name':    'Name',
			'your-email':   'Email',
			'your-subject': 'Subject',
			'your-message': 'Message',
		};
		Object.keys(placeholders).forEach(function(name) {
			const el = document.querySelector('[name="' + name + '"]');
			if (el) el.setAttribute('placeholder', placeholders[name]);
		});
	})();
	</script>

</main>

<?php get_footer(); ?>
