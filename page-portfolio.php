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
			<?php if ( $thumb_url ) : ?>
			<img
				class="portfolio-item__thumb"
				src="<?php echo esc_url( $thumb_url ); ?>"
				alt="<?php echo esc_attr( $item->post_title ); ?>"
				loading="lazy"
			>
			<?php elseif ( $is_direct ) : ?>
			<div class="portfolio-item__skeleton" aria-hidden="true"></div>
			<video
				class="portfolio-item__thumb"
				src="<?php echo esc_url( $video_url ); ?>"
				muted
				loop
				playsinline
				preload="metadata"
				style="width:100%;height:auto;display:block;"
			></video>
			<?php endif; ?>

			<?php if ( $video_url && $thumb_url ) : ?>
			<div class="portfolio-item__play" aria-hidden="true">
				<svg viewBox="0 0 56 56" width="56" height="56">
					<circle cx="28" cy="28" r="28" fill="rgba(0,0,0,0.45)"/>
					<polygon points="22,18 42,28 22,38" fill="white"/>
				</svg>
			</div>
			<?php endif; ?>

			<?php if ( $item->post_title ) : ?>
			<div class="portfolio-item__caption"><?php echo esc_html( $item->post_title ); ?></div>
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
	const lightbox = document.getElementById('portfolio-lightbox');
	const inner    = document.getElementById('portfolio-lightbox-inner');
	const closeBtn = document.getElementById('portfolio-lightbox-close');

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

	// Hide skeleton once video first frame is ready
	document.querySelectorAll('video.portfolio-item__thumb').forEach(function (vid) {
		var skeleton = vid.parentElement.querySelector('.portfolio-item__skeleton');
		if (!skeleton) return;
		function reveal() { skeleton.classList.add('is-loaded'); }
		if (vid.readyState >= 1) { reveal(); return; }
		vid.addEventListener('loadedmetadata', reveal, { once: true });
	});

	document.querySelectorAll('.portfolio-item').forEach(function (item) {
		item.addEventListener('click', function () {
			var type  = item.dataset.type;
			var video = item.dataset.video;

			if (type === 'mp4') {
				var thumb = item.querySelector('.portfolio-item__thumb');
				var play  = item.querySelector('.portfolio-item__play');
				var cap   = item.querySelector('.portfolio-item__caption');
				var existingVid = item.querySelector('video[controls]');

				if (existingVid) {
					// Already playing — toggle play/pause
					existingVid.paused ? existingVid.play() : existingVid.pause();
					return;
				}

				var vid = document.createElement('video');
				vid.src         = video;
				vid.controls    = true;
				vid.autoplay    = true;
				vid.playsInline = true;
				vid.style.cssText = 'width:100%;height:auto;display:block;border-radius:8px;';

				if (thumb) thumb.style.display = 'none';
				if (play)  play.style.display  = 'none';
				if (cap)   cap.style.display   = 'none';
				item.appendChild(vid);
				item.style.cursor = 'default';
			} else {
				openLightbox(item);
			}
		});

		item.addEventListener('keydown', function (e) {
			if (e.key === 'Enter' || e.key === ' ') item.click();
		});
	});
}());
</script>

<?php get_footer(); ?>
