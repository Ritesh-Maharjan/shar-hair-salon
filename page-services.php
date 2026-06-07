<?php
/**
 * Template Name: Services Page
 * Description: Lists all services grouped by category.
 */

get_header();

$categories = get_terms( [
    'taxonomy'   => 'shar-service-category',
    'hide_empty' => true,
    'orderby'    => 'name',
    'order'      => 'ASC',
] );
?>

<main id="primary" class="site-main services-page">

    <?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) :
        foreach ( $categories as $category ) :

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

    <section class="services-category">
        <h2 class="services-category__title"><?php echo esc_html( $category->name ); ?></h2>

        <div class="services-grid">
            <?php foreach ( $services as $service ) :
                $price         = get_post_meta( $service->ID, '_booking_price', true );
                $duration      = get_post_meta( $service->ID, '_booking_duration', true );
                $attachment_id = get_post_meta( $service->ID, '_square_image_attachment_id', true );
                $book_url      = home_url( '/book/?service=' . $service->post_name );

                // Get description from first paragraph block in post content.
                $blocks      = parse_blocks( $service->post_content );
                $description = '';
                foreach ( $blocks as $block ) {
                    if ( $block['blockName'] === 'core/paragraph' && ! empty( $block['innerHTML'] ) ) {
                        $description = wp_strip_all_tags( $block['innerHTML'] );
                        break;
                    }
                }
            ?>
            <article class="service-card">
                <?php if ( $attachment_id ) : ?>
                <div class="service-card__image">
                    <?php echo wp_get_attachment_image( $attachment_id, 'large', false, [ 'loading' => 'lazy', 'alt' => esc_attr( $service->post_title ) ] ); ?>
                </div>
                <?php endif; ?>

                <div class="service-card__body">
                    <div class="service-card__top">
                        <h3 class="service-card__title"><?php echo esc_html( $service->post_title ); ?></h3>
                    </div>

                    <?php if ( $description ) : ?>
                    <p class="service-card__desc"><?php echo esc_html( $description ); ?></p>
                    <?php endif; ?>

                    <div class="service-card__meta">
                        <?php if ( $price ) : ?>
                        <span class="service-card__price">From $<?php echo esc_html( number_format( (float) $price, 2 ) ); ?></span>
                        <?php endif; ?>
                        <?php if ( $duration ) : ?>
                        <span class="service-card__duration"><?php echo esc_html( $duration ); ?> min</span>
                        <?php endif; ?>
                    </div>

                    <button
                        type="button"
                        class="service-card__btn wp-element-button"
                        data-shar-book
                        data-service-post-id="<?php echo esc_attr( $service->ID ); ?>"
                        data-service-variation-id="<?php echo esc_attr( get_post_meta( $service->ID, '_square_service_variation_id', true ) ); ?>"
                        data-service-name="<?php echo esc_attr( $service->post_title ); ?>"
                        data-service-price="<?php echo esc_attr( $price ); ?>"
                        data-service-duration="<?php echo esc_attr( $duration ); ?>"
                    >Book Now</button>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </section>

    <?php
        endforeach;
    else : ?>
        <p>No services found. <a href="<?php echo esc_url( admin_url( 'options-general.php?page=shar-booking' ) ); ?>">Sync services from Square</a>.</p>
    <?php endif; ?>

</main>

<?php get_footer(); ?>
