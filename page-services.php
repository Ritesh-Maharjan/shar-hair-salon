<?php
/**
 * Template Name: Services Page
 * Description: Lists all services grouped by category.
 */

get_header();

// Format duration from minutes to "X hour Y minutes"
function shar_format_duration( $minutes ) {
    $minutes = (int) $minutes;
    if ( $minutes <= 0 ) return '';
    $h = intdiv( $minutes, 60 );
    $m = $minutes % 60;
    $parts = [];
    if ( $h ) $parts[] = $h . ' hour' . ( $h > 1 ? 's' : '' );
    if ( $m ) $parts[] = $m . ' minute' . ( $m > 1 ? 's' : '' );
    return implode( ' ', $parts );
}

$categories = get_terms( [
    'taxonomy'   => 'shar-service-category',
    'hide_empty' => true,
    'orderby'    => 'name',
    'order'      => 'ASC',
] );
?>

<main id="primary" class="site-main services-page">

    <?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>

    <!-- ── Page heading + filter ── -->
    <div class="services-header">
        <h1 class="services-page__title">Our Services</h1>
        <div class="services-filter">
            <button class="services-filter__btn is-active" data-filter="all">All</button>
            <?php foreach ( $categories as $category ) : ?>
            <button class="services-filter__btn" data-filter="<?php echo esc_attr( $category->slug ); ?>">
                <?php echo esc_html( ucwords( strtolower( $category->name ) ) ); ?>
            </button>
            <?php endforeach; ?>
        </div>
    </div>

    <?php foreach ( $categories as $category ) :

            $services = get_posts( [
                'post_type'      => 'shar-service',
                'posts_per_page' => -1,
                'post_status'    => 'publish',
                'orderby'        => 'title',
                'order'          => 'ASC',
                'tax_query'      => [ [
                    'taxonomy' => 'shar-service-category',
                    'field'    => 'term_id',
                    'terms'    => $category->term_id,
                ] ],
            ] );

            if ( empty( $services ) ) continue;
    ?>

    <section class="services-category" data-category="<?php echo esc_attr( $category->slug ); ?>">
        <h2 class="services-category__title"><?php echo esc_html( $category->name ); ?></h2>

        <div class="services-list">
            <?php foreach ( $services as $service ) :
                $price    = get_post_meta( $service->ID, '_booking_price', true );
                $duration = get_post_meta( $service->ID, '_booking_duration', true );

                // Build meta line e.g. "1 hour 30 minutes @ $50.00"
                $meta_parts = [];
                if ( $duration ) $meta_parts[] = shar_format_duration( $duration );
                if ( $price )    $meta_parts[] = '$' . number_format( (float) $price, 2 );
                $meta_line = implode( ' @ ', $meta_parts );

                // Get description from first paragraph block
                $blocks      = parse_blocks( $service->post_content );
                $description = '';
                foreach ( $blocks as $block ) {
                    if ( $block['blockName'] === 'core/paragraph' && ! empty( $block['innerHTML'] ) ) {
                        $description = wp_strip_all_tags( $block['innerHTML'] );
                        break;
                    }
                }
            ?>
            <div class="service-row">
                <?php
                $attachment_id = get_post_meta( $service->ID, '_square_image_attachment_id', true );
                $img_url = $attachment_id ? wp_get_attachment_image_url( $attachment_id, 'medium' ) : get_the_post_thumbnail_url( $service->ID, 'medium' );
                ?>
                <?php if ( $img_url ) : ?>
                <div class="service-row__image">
                    <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $service->post_title ); ?>" loading="lazy">
                </div>
                <?php else : ?>
                <div class="service-row__image service-row__image--empty"></div>
                <?php endif; ?>

                <div class="service-row__content">
                <div class="service-row__main">
                    <div class="service-row__info">
                        <h3 class="service-row__name"><?php echo esc_html( $service->post_title ); ?></h3>
                        <?php if ( $meta_line ) : ?>
                        <p class="service-row__meta"><?php echo esc_html( $meta_line ); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ( $description ) :
                    $needs_toggle = strlen( $description ) > 240;
                ?>
                <div class="service-row__desc-wrap">
                    <p class="service-row__desc"><?php echo esc_html( $description ); ?></p>
                    <?php if ( $needs_toggle ) : ?>
                    <button type="button" class="service-row__toggle">SHOW ALL</button>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <button
                    type="button"
                    class="service-row__book"
                    data-shar-book
                    data-service-post-id="<?php echo esc_attr( $service->ID ); ?>"
                    data-service-variation-id="<?php echo esc_attr( get_post_meta( $service->ID, '_square_service_variation_id', true ) ); ?>"
                    data-service-name="<?php echo esc_attr( $service->post_title ); ?>"
                    data-service-price="<?php echo esc_attr( $price ); ?>"
                    data-service-duration="<?php echo esc_attr( $duration ); ?>"
                >BOOK</button>
                </div><!-- /.service-row__content -->
            </div><!-- /.service-row -->
            <?php endforeach; ?>
        </div>
    </section>

    <?php
        endforeach;
    endif; ?>

</main>

<script>
// ── Category filter (multi-select) ──────────────────────────────────────
(function () {
    const allBtn   = document.querySelector('.services-filter__btn[data-filter="all"]');
    const catBtns  = document.querySelectorAll('.services-filter__btn:not([data-filter="all"])');
    const sections = document.querySelectorAll('.services-category');

    function applyFilter() {
        const active = Array.from(catBtns)
            .filter(function (b) { return b.classList.contains('is-active'); })
            .map(function (b) { return b.dataset.filter; });

        sections.forEach(function (sec) {
            sec.style.display = (active.length === 0 || active.includes(sec.dataset.category)) ? '' : 'none';
        });
    }

    // "All" pill — reset everything
    allBtn.addEventListener('click', function () {
        catBtns.forEach(function (b) { b.classList.remove('is-active'); });
        allBtn.classList.add('is-active');
        sections.forEach(function (sec) { sec.style.display = ''; });
    });

    // Category pills — toggle on/off
    catBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            btn.classList.toggle('is-active');
            // If nothing selected, re-activate "All"
            const anyActive = Array.from(catBtns).some(function (b) { return b.classList.contains('is-active'); });
            allBtn.classList.toggle('is-active', !anyActive);
            applyFilter();
        });
    });
}());

// ── Show all / Show less toggle ──────────────────────────────────────────
document.querySelectorAll('.service-row__toggle').forEach(function(btn) {
    const desc = btn.previousElementSibling;
    // Start clamped
    desc.classList.add('is-clamped');

    btn.addEventListener('click', function() {
        const expanded = desc.classList.toggle('is-expanded');
        desc.classList.toggle('is-clamped', !expanded);
        btn.textContent = expanded ? 'SHOW LESS' : 'SHOW ALL';
    });
});
</script>

<?php get_footer(); ?>
