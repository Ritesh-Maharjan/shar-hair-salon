<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package BDW_Massage
 */

get_header();
?>

<main id="primary" class="site-main">

    <?php if (have_posts()): ?>

    <header class="page-header">
        <?php
            post_type_archive_title('<h1 class="page-title">', '</h1>');
            the_archive_description('<div class="archive-description">', '</div>');
            ?>
    </header><!-- .page-header -->

    <div class="services-container">
        <!-- Container for services -->
        <?php
            /* Start the Loop */
            while (have_posts()):
                the_post();
                ?>
        <article class="service-item">
            <!-- Service item container -->
            <h3 class="entry-title"><?php the_title(); ?></h3> <!-- Service title -->

            <?php if (has_post_thumbnail()): ?>
            <div class="service-thumbnail">
                <?php the_post_thumbnail('featured-image'); ?>
            </div> <!-- Service thumbnail -->
            <?php endif; ?>
            <div class="service-book-for">
                <p>Choose from</p>
            </div>
            <div class="price-list">
                <!-- Price list container -->
                <?php
                        if (function_exists('get_field')) {
                            $url = home_url('/product/');
                            $slug = get_post_field('post_name', get_the_ID());

                            if (get_field('price1')): ?>
                <a href="<?php echo $url . $slug . '-45-minutes'; ?>"><span>
                        45mins/$<?php echo get_field('price1'); ?></span></a>
                <?php endif;

                            if (get_field('price2')): ?>
                <a href="<?php echo $url . $slug . '-60-minutes'; ?>"><span>
                        60mins/$<?php echo get_field('price2'); ?></span></a>
                <?php endif;

                            if (get_field('price3')): ?>
                <a href="<?php echo $url . $slug . '-90-minutes'; ?>"><span>
                        90mins/$<?php echo get_field('price3'); ?></span></a>
                <?php endif;
                        }
                        ?>
            </div> <!-- Price list -->

          
                <!-- More info button -->
                <a href="<?php the_permalink(); ?>" class="wp-element-button cta-btn">Learn More</a>
          
        </article> <!-- End of service item -->
        <?php endwhile; ?>

    </div> <!-- End of services container -->

    <?php the_posts_navigation();

    else:

        get_template_part('template-parts/content', 'none');

    endif;
    ?>

</main><!-- #main -->


<?php
get_footer();