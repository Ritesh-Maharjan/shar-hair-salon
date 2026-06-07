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

    // Collect ACF hero images, skip empty slots
    $hero_images = array();
    if ( function_exists( 'get_field' ) ) {
        foreach ( array( 'hero_image_one', 'hero_image_two', 'hero_image_three', 'hero_image_four' ) as $key ) {
            $img = get_field( $key );
            if ( ! $img ) continue;
            $url = is_array( $img ) ? $img['url'] : $img;
            if ( $url ) {
                $hero_images[] = esc_url( $url );
            }
        }
    }
    ?>
    <section class="home-hero">
        <?php foreach ( $hero_images as $i => $url ) : ?>
        <div class="hero-slide<?php echo $i === 0 ? ' is-active' : ''; ?>"
             style="background-image: url('<?php echo $url; ?>')"></div>
        <?php endforeach; ?>

        <?php if ( count( $hero_images ) > 1 ) : ?>
        <div class="hero-progress">
            <?php foreach ( $hero_images as $i => $url ) : ?>
            <button class="hero-progress__bar<?php echo $i === 0 ? ' is-active' : ''; ?>"
                    data-index="<?php echo $i; ?>"
                    aria-label="Go to slide <?php echo $i + 1; ?>"></button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </section>
    <h1 class="home-brand-name">SHARS HAIR LAB</h1>
    <?php endwhile; ?>

    <!-- Services -->
    <section class="home-services">
        <div class="home-services__header">
            <h2>Services</h2>
            <a href="/services/" class="home-services__link">Book Now</a>
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
                    $price         = function_exists( 'get_field' ) ? get_field( 'price' )    : '';
                    $duration      = function_exists( 'get_field' ) ? get_field( 'duration' ) : '';
                    $attach_id     = get_post_meta( get_the_ID(), '_square_image_attachment_id', true );
                    $service_img   = $attach_id ? wp_get_attachment_image_url( $attach_id, 'large' ) : '';
                    // Extract plain description from post content (strip image block)
                    $raw_blocks    = parse_blocks( get_the_content() );
                    $service_desc  = '';
                    foreach ( $raw_blocks as $b ) {
                        if ( $b['blockName'] === 'core/paragraph' && ! empty( $b['innerHTML'] ) ) {
                            $service_desc = wp_strip_all_tags( $b['innerHTML'] );
                            break;
                        }
                    }
            ?>
            <article class="service-card">
                <?php if ( $service_img ) : ?>
                <div class="service-card__image">
                    <a href="<?php the_permalink(); ?>">
                        <img src="<?php echo esc_url( $service_img ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>">
                    </a>
                </div>
                <?php endif; ?>
                <div class="service-card__body">
                    <div class="service-card__top">
                        <h3 class="service-card__title"><?php the_title(); ?></h3>
                        <button
                            type="button"
                            class="service-card__btn"
                            data-shar-book
                            data-service-post-id="<?php the_ID(); ?>"
                            data-service-variation-id="<?php echo esc_attr( get_post_meta( get_the_ID(), '_square_service_variation_id', true ) ); ?>"
                            data-service-name="<?php echo esc_attr( get_the_title() ); ?>"
                            data-service-price="<?php echo esc_attr( get_post_meta( get_the_ID(), '_booking_price', true ) ); ?>"
                        >Book</button>
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
                    <?php if ( $service_desc ) : ?>
                    <p class="service-card__desc"><?php echo esc_html( wp_trim_words( $service_desc, 20, '…' ) ); ?></p>
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
        $reviews = array();
        while ( $testimonial_query->have_posts() ) :
            $testimonial_query->the_post();
            $acf_img = function_exists( 'get_field' ) ? get_field( 'testimonial_image' ) : '';
            $img_url = '';
            if ( $acf_img ) {
                $img_url = is_array( $acf_img ) ? $acf_img['url'] : $acf_img;
            } elseif ( has_post_thumbnail() ) {
                $img_url = get_the_post_thumbnail_url( null, 'large' );
            }
            $reviews[] = array(
                'title'   => get_the_title(),
                'content' => get_the_content(),
                'img'     => $img_url,
            );
        endwhile;
        wp_reset_postdata();

        $total    = count( $reviews );
        $has_imgs = ! empty( array_filter( array_column( $reviews, 'img' ) ) );
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
                        <cite class="review-author"><?php echo esc_html( $review['title'] ); ?></cite>
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

            <?php if ( $has_imgs ) : ?>
            <div class="home-reviews__image">
                <?php foreach ( $reviews as $i => $review ) : ?>
                <?php if ( ! empty( $review['img'] ) ) : ?>
                <img
                    src="<?php echo esc_url( $review['img'] ); ?>"
                    alt="<?php echo esc_attr( $review['title'] ); ?>"
                    class="review-img<?php echo $i === 0 ? ' is-active' : ''; ?>"
                    data-index="<?php echo $i; ?>"
                    loading="lazy"
                >
                <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>

</main>

<script>
// Hero slideshow with progress bars
(function () {
    var slides = document.querySelectorAll('.hero-slide');
    var bars   = document.querySelectorAll('.hero-progress__bar');
    if (slides.length < 2) return;

    var current = 0;
    var timer;

    function goTo(idx) {
        slides[current].classList.remove('is-active');

        current = ((idx % slides.length) + slides.length) % slides.length;

        bars.forEach(function (bar, i) {
            bar.classList.remove('is-active', 'is-done');
            if (i < current) {
                bar.classList.add('is-done');
            }
        });

        // Force reflow so animation restarts cleanly
        void bars[current].offsetWidth;
        bars[current].classList.add('is-active');

        slides[current].classList.add('is-active');

        clearInterval(timer);
        timer = setInterval(function () { goTo(current + 1); }, 4500);
    }

    bars.forEach(function (bar, i) {
        bar.addEventListener('click', function () { goTo(i); });
    });

    timer = setInterval(function () { goTo(current + 1); }, 4500);
}());

// Reviews carousel
(function () {
    var carousel = document.querySelector('.js-reviews');
    if (!carousel) return;
    var items   = carousel.querySelectorAll('.review-item');
    var imgs    = document.querySelectorAll('.review-img');
    var count   = parseInt(carousel.dataset.total, 10);
    var current = 0;
    var timer;

    function show(idx) {
        items[current].classList.remove('is-active');
        if (imgs[current]) imgs[current].classList.remove('is-active');
        current = (idx + count) % count;
        items[current].classList.add('is-active');
        if (imgs[current]) imgs[current].classList.add('is-active');
        document.querySelector('.js-review-count').textContent = (current + 1) + ' / ' + count;
    }

    function startAuto() {
        timer = setInterval(function () { show(current + 1); }, 3000);
    }

    document.querySelector('.js-review-prev').addEventListener('click', function () {
        clearInterval(timer);
        show(current - 1);
        startAuto();
    });
    document.querySelector('.js-review-next').addEventListener('click', function () {
        clearInterval(timer);
        show(current + 1);
        startAuto();
    });

    startAuto();
}());
</script>

<?php
get_footer();
?>