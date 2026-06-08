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

	<!-- Marquee ticker -->
	<?php
	$marquee_services = get_posts( [
		'post_type'      => 'shar-service',
		'posts_per_page' => -1,
		'fields'         => 'ids',
	] );
	if ( ! empty( $marquee_services ) ) : ?>
	<div class="home-marquee" aria-hidden="true">
		<div class="marquee-track">
			<ul class="marquee-list">
				<?php foreach ( $marquee_services as $sid ) : ?>
				<li><span class="marquee-dot"> &bull; </span><?php echo esc_html( get_the_title( $sid ) ); ?></li>
				<?php endforeach; ?>
			</ul>
			<ul class="marquee-list" aria-hidden="true">
				<?php foreach ( $marquee_services as $sid ) : ?>
				<li><span class="marquee-dot"> &bull; </span><?php echo esc_html( get_the_title( $sid ) ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<?php endif; ?>

	<!-- Testimonials -->
	<?php
	$testimonial_query = new WP_Query( [
		'post_type'      => 'shar-testimonial',
		'posts_per_page' => -1,
		'orderby'        => 'rand',
	] );

	if ( $testimonial_query->have_posts() ) :
		$reviews = [];
		while ( $testimonial_query->have_posts() ) :
			$testimonial_query->the_post();
			$reviews[] = [
				'title'   => get_the_title(),
				'content' => get_the_content(),
				'thumb'   => has_post_thumbnail() ? get_the_post_thumbnail_url( null, 'large' ) : '',
			];
		endwhile;
		wp_reset_postdata();

		$total    = count( $reviews );
		$side_img = ! empty( $reviews[0]['thumb'] ) ? $reviews[0]['thumb'] : '';
	?>
	<section class="home-reviews">
		<div class="home-reviews__inner">
			<div class="home-reviews__content">
				<div class="reviews-stars">
					<?php for ( $i = 0; $i < 5; $i++ ) : ?>
					<svg viewBox="0 0 39 38" width="22" height="22" fill="currentColor" aria-hidden="true"><path d="M19.5 0L24.1 14.1H39L26.9 22.9L31.5 37L19.5 28.3L7.4 37L12 22.9L0 14.1H14.8L19.5 0Z"/></svg>
					<?php endfor; ?>
				</div>

				<div class="reviews-carousel js-reviews" data-total="<?php echo esc_attr( $total ); ?>">
					<?php foreach ( $reviews as $i => $review ) : ?>
					<blockquote class="review-item<?php echo $i === 0 ? ' is-active' : ''; ?>" data-index="<?php echo $i; ?>">
						<p><?php echo wp_kses_post( $review['content'] ); ?></p>
						<footer class="review-author"><?php echo esc_html( $review['title'] ); ?></footer>
					</blockquote>
					<?php endforeach; ?>
				</div>

				<nav class="reviews-nav" aria-label="Review pagination">
					<button type="button" class="reviews-nav__btn js-review-prev" aria-label="Previous review">
						<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
					</button>
					<span class="reviews-nav__count js-review-count">1 / <?php echo $total; ?></span>
					<button type="button" class="reviews-nav__btn js-review-next" aria-label="Next review">
						<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
					</button>
				</nav>
			</div>

			<?php if ( ! empty( $side_img ) ) : ?>
			<div class="home-reviews__image">
				<img src="<?php echo esc_url( $side_img ); ?>" alt="Review" loading="lazy">
			</div>
			<?php endif; ?>
		</div>
	</section>
	<?php endif; ?>

</main>

<script>
// Reviews carousel
(function () {
	const carousel = document.querySelector('.js-reviews');
	if (!carousel) return;
	const items = carousel.querySelectorAll('.review-item');
	const count = parseInt(carousel.dataset.total, 10);
	let current = 0;

	function show(idx) {
		items[current].classList.remove('is-active');
		current = (idx + count) % count;
		items[current].classList.add('is-active');
		document.querySelector('.js-review-count').textContent = (current + 1) + ' / ' + count;
	}

	document.querySelector('.js-review-prev').addEventListener('click', function () { show(current - 1); });
	document.querySelector('.js-review-next').addEventListener('click', function () { show(current + 1); });
}());
</script>

<?php get_footer(); ?>
