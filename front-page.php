<?php
/**
 * Front page template
 *
 * @package BDW_Massage
 */

get_header();
?>

<main id="primary" class="main home-page">

    <!-- Hero -->
    <?php
    while ( have_posts() ) : the_post();

    // Featured image first; fall back to first image found in page content blocks
    $hero_img = '';
    if ( has_post_thumbnail() ) {
        $hero_img = get_the_post_thumbnail_url( null, 'full' );
    } else {
        preg_match( '/<img[^>]+src=["\']([^"\']+)["\']/', get_the_content(), $img_match );
        if ( ! empty( $img_match[1] ) ) {
            $hero_img = $img_match[1];
        }
    }

    $hero_style = $hero_img
        ? 'style="background-image: url(\'' . esc_url( $hero_img ) . '\')"'
        : '';
    ?>
    <section class="home-hero" <?php echo $hero_style; ?>>
        <h1 class="hero-title"><?php bloginfo( 'name' ); ?></h1>
        <a class="hero-cta" href="/service/">Book Now</a>
    </section>
    <?php endwhile; ?>

    <!-- Services -->
    <section class="home-services">
        <div class="home-services__header">
            <h2>Services</h2>
            <a href="/service/" class="home-services__link">Book Now</a>
        </div>
        <div class="home-services__grid">
            <?php
            $args = array(
                'post_type'      => 'shar-service',
                'posts_per_page' => 4,
            );
            $services_query = new WP_Query( $args );

            if ( $services_query->have_posts() ) :
                while ( $services_query->have_posts() ) :
                    $services_query->the_post();
                    $price    = function_exists( 'get_field' ) ? get_field( 'price' )    : '';
                    $duration = function_exists( 'get_field' ) ? get_field( 'duration' ) : '';
            ?>
            <article class="service-card">
                <?php if ( has_post_thumbnail() ) : ?>
                <div class="service-card__image">
                    <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail( 'large' ); ?>
                    </a>
                </div>
                <?php endif; ?>
                <div class="service-card__body">
                    <div class="service-card__top">
                        <h3 class="service-card__title"><?php the_title(); ?></h3>
                        <a href="<?php the_permalink(); ?>" class="service-card__btn">Book</a>
                    </div>
                    <?php if ( $price || $duration ) : ?>
                    <div class="service-card__meta">
                        <?php if ( $price ) : ?>
                        <span><strong>Price:</strong> <?php echo esc_html( $price ); ?>+</span>
                        <?php endif; ?>
                        <?php if ( $duration ) : ?>
                        <span><strong>Duration:</strong> <?php echo esc_html( $duration ); ?> min</span>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </article>
            <?php
                endwhile;
                wp_reset_postdata();
            else :
                echo '<p>No services found.</p>';
            endif;
            ?>
        </div>
        <div class="home-services__view-all">
            <a href="/service/">View All Services</a>
        </div>
    </section>

    <!-- Marquee ticker -->
    <?php
    $marquee_services = get_posts( array(
        'post_type'      => 'shar-service',
        'posts_per_page' => -1,
        'fields'         => 'ids',
    ) );
    if ( ! empty( $marquee_services ) ) :
    ?>
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
    $testimonial_args = array(
        'post_type'      => 'shar-testimonial',
        'posts_per_page' => -1,
        'orderby'        => 'rand',
    );
    $testimonial_query = new WP_Query( $testimonial_args );

    if ( $testimonial_query->have_posts() ) :
        $reviews     = array();
        $review_imgs = array();
        while ( $testimonial_query->have_posts() ) :
            $testimonial_query->the_post();
            $reviews[] = array(
                'title'   => get_the_title(),
                'content' => get_the_content(),
                'thumb'   => has_post_thumbnail() ? get_the_post_thumbnail_url( null, 'large' ) : '',
            );
        endwhile;
        wp_reset_postdata();

        $total       = count( $reviews );
        $side_img    = ! empty( $reviews[0]['thumb'] ) ? $reviews[0]['thumb'] : '';
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

                <?php if ( ! empty( $side_img ) ) : ?>
                <div class="reviews-cta">
                    <a href="<?php echo get_permalink( get_page_by_path( 'about' ) ); ?>">All Reviews</a>
                </div>
                <?php endif; ?>
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
(function () {
    var carousel = document.querySelector('.js-reviews');
    if (!carousel) return;
    var items = carousel.querySelectorAll('.review-item');
    var count = parseInt(carousel.dataset.total, 10);
    var current = 0;

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

<?php
get_footer();
?>