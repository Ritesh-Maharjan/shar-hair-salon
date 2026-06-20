<?php
/**
 * Template Name: Portfolio Page
 */

get_header();

$items = get_posts( [
	'post_type'      => 'shar-portfolio',
	'posts_per_page' => -1,
	'post_status'    => 'publish',
	'orderby'        => 'menu_order date',
	'order'          => 'ASC',
] );

function shar_is_direct_video( $url ) {
	return (bool) preg_match( '/\.(mp4|mov|webm|ogg)(\?.*)?$/i', $url );
}

function shar_get_embed_url( $url ) {
	if ( preg_match( '/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $url, $m ) ) {
		return 'https://www.youtube.com/embed/' . $m[1] . '?autoplay=1&rel=0';
	}
	if ( preg_match( '/vimeo\.com\/(\d+)/', $url, $m ) ) {
		return 'https://player.vimeo.com/video/' . $m[1] . '?autoplay=1';
	}
	return '';
}
?>

<main id="primary" class="site-main portfolio-page">

	<div class="portfolio-header">
		<h1 class="portfolio-page__title"><?php the_title(); ?></h1>
	</div>

	<?php if ( ! empty( $items ) ) :
		$col1 = array_filter( $items, fn( $i, $k ) => $k % 2 === 0, ARRAY_FILTER_USE_BOTH );
		$col2 = array_filter( $items, fn( $i, $k ) => $k % 2 !== 0, ARRAY_FILTER_USE_BOTH );
	?>
	<div class="portfolio-grid">
		<?php foreach ( [ $col1, $col2 ] as $col_items ) : ?>
		<div class="portfolio-col">
		<?php foreach ( $col_items as $item ) :
			$video_url  = get_post_meta( $item->ID, '_shar_portfolio_video_url', true );
			$thumb_url  = get_the_post_thumbnail_url( $item->ID, 'large' );
			$is_direct  = $video_url && shar_is_direct_video( $video_url );
			$embed_url  = ( $video_url && ! $is_direct ) ? shar_get_embed_url( $video_url ) : '';
			$type       = $is_direct ? 'mp4' : ( $embed_url ? 'iframe' : 'image' );

			if ( ! $thumb_url && ! $video_url ) continue;
		?>
		<div
			class="portfolio-item has-<?php echo esc_attr( $type ); ?>"
			data-video="<?php echo esc_attr( $is_direct ? $video_url : $embed_url ); ?>"
			data-image="<?php echo esc_attr( $thumb_url ); ?>"
			data-type="<?php echo esc_attr( $type ); ?>"
			role="button"
			tabindex="0"
			aria-label="<?php echo esc_attr( $item->post_title ); ?>"
		>
			<?php if ( $is_direct ) : ?>
			<?php if ( $thumb_url ) : ?>
			<img
				class="portfolio-item__thumb portfolio-item__thumb--fallback"
				src="<?php echo esc_url( $thumb_url ); ?>"
				alt="<?php echo esc_attr( $item->post_title ); ?>"
				loading="lazy"
			>
			<?php endif; ?>
			<video
				class="portfolio-item__video"
				data-src="<?php echo esc_url( $video_url ); ?>"
				<?php if ( $thumb_url ) : ?>poster="<?php echo esc_url( $thumb_url ); ?>"<?php endif; ?>
				muted
				loop
				playsinline
				preload="none"
				style="width:100%;height:auto;display:block;"
			></video>
			<?php elseif ( $thumb_url ) : ?>
			<img
				class="portfolio-item__thumb"
				src="<?php echo esc_url( $thumb_url ); ?>"
				alt="<?php echo esc_attr( $item->post_title ); ?>"
				loading="lazy"
			>
			<?php endif; ?>
		</div>
		<?php endforeach; ?>
		</div><!-- /.portfolio-col -->
		<?php endforeach; ?>
	</div>
	<?php else : ?>
	<p class="portfolio-empty">No portfolio items yet. <a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=shar-portfolio' ) ); ?>">Add your first one</a>.</p>
	<?php endif; ?>

</main>

<!-- Lightbox (for YouTube/Vimeo/image) -->
<div id="portfolio-lightbox" role="dialog" aria-modal="true" aria-label="Media viewer">
	<button id="portfolio-lightbox-close" aria-label="Close">&times;</button>
	<div id="portfolio-lightbox-inner"></div>
</div>

<script>
(function () {
	var lightbox = document.getElementById('portfolio-lightbox');
	var inner    = document.getElementById('portfolio-lightbox-inner');
	var closeBtn = document.getElementById('portfolio-lightbox-close');

	function openLightbox(item) {
		var type  = item.dataset.type;
		var video = item.dataset.video;
		var img   = item.dataset.image;
		if (type === 'image') {
			inner.innerHTML = '<img src="' + img + '" alt="" style="max-width:90vw;max-height:88vh;border-radius:8px;display:block;">';
		} else {
			inner.innerHTML = '<iframe src="' + video + '" frameborder="0" allow="autoplay; fullscreen" allowfullscreen style="width:min(900px,90vw);height:min(506px,50vw);border-radius:8px;"></iframe>';
		}
		lightbox.classList.add('is-open');
		document.body.style.overflow = 'hidden';
		closeBtn.focus();
	}

	function closeLightbox() {
		lightbox.classList.remove('is-open');
		inner.innerHTML = '';
		document.body.style.overflow = '';
	}

	closeBtn.addEventListener('click', closeLightbox);
	lightbox.addEventListener('click', function (e) { if (e.target === lightbox) closeLightbox(); });
	document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeLightbox(); });

	// Lazy-load src and autoplay on scroll — all screen sizes
	if ('IntersectionObserver' in window) {
		var observer = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				var vid = entry.target;
				if (entry.isIntersecting) {
					if (!vid.src && vid.dataset.src) {
						vid.src = vid.dataset.src;

						var fallback = vid.parentElement.querySelector('.portfolio-item__thumb--fallback');

						// Hide thumbnail only when video is actually playing
						vid.addEventListener('playing', function () {
							if (fallback) fallback.style.display = 'none';
						}, { once: true });

						// Keep thumbnail visible on error
						vid.addEventListener('error', function () {
							vid.style.display = 'none';
						}, { once: true });
					}
					vid.play().catch(function () {});
				} else {
					vid.pause();
				}
			});
		}, { threshold: 0.4 });

		document.querySelectorAll('.portfolio-item__video').forEach(function (vid) {
			observer.observe(vid);
		});
	}

	// Click handler for iframe/image lightbox items
	document.querySelectorAll('.portfolio-item:not(.has-mp4)').forEach(function (item) {
		item.addEventListener('click', function () { openLightbox(item); });
		item.addEventListener('keydown', function (e) {
			if (e.key === 'Enter' || e.key === ' ') openLightbox(item);
		});
	});
}());
</script>

<?php get_footer(); ?>
