<?php
/**
 * Template for the About page
 *
 * @package shar-hair-salon
 */

get_header();
?>

<main id="primary" class="site-main">
    <?php while ( have_posts() ) : the_post();

    // Parse blocks to split image (right) from text (left)
    $blocks     = parse_blocks( get_the_content() );
    $img_url    = '';
    $img_alt    = '';
    $text_html  = '';
    $extra_html = '';

    foreach ( $blocks as $block_index => $block ) {
        $search = ( $block['blockName'] === 'core/group' ) ? $block['innerBlocks'] : array( $block );

        // First group — split into image + text columns
        if ( $block_index === 0 ) {
            foreach ( $search as $inner ) {
                if ( $inner['blockName'] === 'core/image' && ! $img_url ) {
                    preg_match( '/src=["\']([^"\']+)["\']/', $inner['innerHTML'], $src_m );
                    preg_match( '/alt=["\']([^"\']*)["\']/', $inner['innerHTML'], $alt_m );
                    $img_url = ! empty( $src_m[1] ) ? $src_m[1] : '';
                    $img_alt = ! empty( $alt_m[1] ) ? $alt_m[1] : get_the_title();
                } elseif ( $inner['blockName'] === 'core/group' && ! $text_html ) {
                    $text_html = render_block( $inner );
                }
            }
        } else {
            // All subsequent blocks render below the hero
            $extra_html .= render_block( $block );
        }
    }

    // Fallbacks
    if ( ! $img_url && has_post_thumbnail() ) {
        $img_url = get_the_post_thumbnail_url( null, 'large' );
        $img_alt = get_the_title();
    }
    if ( ! $text_html ) {
        $text_html = apply_filters( 'the_content', get_the_content() );
    }
    ?>

    <section class="about-hero">
        <div class="about-hero__inner">

            <div class="about-hero__text">
                <h1 class="about-hero__title"><?php the_title(); ?></h1>
                <div class="about-hero__content">
                    <?php echo $text_html; ?>
                </div>
            </div>

            <?php if ( $img_url ) : ?>
            <div class="about-hero__image">
                <img src="<?php echo esc_url( $img_url ); ?>"
                     alt="<?php echo esc_attr( $img_alt ); ?>">
            </div>
            <?php endif; ?>

        </div>
    </section>

    <?php if ( $extra_html ) : ?>
    <section class="about-extra">
        <div class="about-extra__inner">
            <?php echo $extra_html; ?>
        </div>
    </section>
    <?php endif; ?>

    <?php endwhile; ?>
</main>

<?php get_footer(); ?>