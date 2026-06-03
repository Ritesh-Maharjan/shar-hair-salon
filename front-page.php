<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package BDW_Massage
 */

get_header();
?>

<main id="primary" class="main">

    <?php
    while (have_posts()) :
        the_post();
        the_content();
        
    endwhile;
    ?>
    <a class="cta-btn hero" href="https://bdwmassage.bcitwebdeveloper.ca/service/">
        Book Now
    </a>

    <div class="company-info">
        <h2>Beloved Duck Wellness</h2>
        <p>At Beloved Duck Wellness, we are dedicated to revitalizing your body, renewing your spirit, and helping you rediscover inner harmony—one massage at a time. Our clinic specializes in massage therapy, providing a serene and rejuvenating space where you can experience the healing power of touch.</p>
        
        <h2>Brief Company Information</h2>
        <p>Beloved Duck Wellness is a premier massage therapy clinic committed to promoting wellness and enhancing our clients' overall well-being. Our mission is to provide a sanctuary where individuals can escape the stresses of everyday life and focus on restoring balance to their mind, body, and spirit.</p>
    </div>

    <!-- Our Services -->
<!-- Our Services -->
<div class="service-list">
    <h2>Our Services</h2>
    <?php
    $args = array(
        'post_type' => 'bdw-service',
        'posts_per_page' => -1
    );

    $services_query = new WP_Query($args);

    if ($services_query->have_posts()) :
        // Initialize Swiper container outside the loop
        echo '<div class="swiper-home">';
        echo '<div class="swiper-wrapper">';

        while ($services_query->have_posts()) :
            $services_query->the_post();
    ?>
    <div class="swiper-slide">
        <div class="service">
            <?php if (has_post_thumbnail()) : ?>
            <div class="service-thumbnail">
                <?php the_post_thumbnail('thumbnail'); ?>
            </div>
            <?php endif; ?>
            <div class="service-details">
                <h3><?php the_title(); ?></h3>
                <a class="cta-btn hero" href="<?php the_permalink(); ?>">
                    Book Now
                </a>
            </div>
        </div>
    </div>
    <?php
        endwhile;

        // Close Swiper container and add pagination/navigation buttons
        echo '</div>';
        echo '<div class="swiper-pagination"></div>';
        echo '<button class="swiper-button-prev"></button>';
        echo '<button class="swiper-button-next"></button>';
        echo '</div>';

        wp_reset_postdata();
    else :
        echo '<p>No services found.</p>';
    endif;
    ?>
</div>

    <!-- Our Therapists -->
    <div class="therapist-list">
        <h2>Our Therapists</h2>
        <div class="therapist-grid">
            <?php
            $therapists_args = array(
                'post_type' => 'bdw-staff', 
                'posts_per_page' => -1
            );

            $therapists_query = new WP_Query($therapists_args);

            if ($therapists_query->have_posts()) :
                while ($therapists_query->have_posts()) :
                    $therapists_query->the_post();
            ?>
            <div class="therapist">
                <?php if (has_post_thumbnail()) : ?>
                <div class="therapist-thumbnail">
                    <?php the_post_thumbnail('full'); ?>
                </div>
                <?php endif; ?>
                <div class="therapist-details">
                    <h3><?php the_title(); ?></h3>
                </div>
                <a href="<?php the_permalink(); ?>">
                    <button class="learn-more">
                        Learn More
                    </button>
                </a>
            </div>
            <?php
                endwhile;
                wp_reset_postdata();
            else :
                echo '<p>No therapists found.</p>';
            endif;
            ?>
        </div>
    </div>

    <!-- Client Testimonials -->
    <?php
        $testimonial_args = array(
            'post_type' => 'bdw-testimonial',
            'posts_per_page' => 2,
            'orderby' => 'rand', // Order randomly
        );

        $testimonial_query = new WP_Query($testimonial_args);

        if ($testimonial_query->have_posts()) {
            echo '<h2 class="testimonial-staff-header">Testimonials</h2>';
            echo '<div class="testimonial-wrapper">'; 

            while ($testimonial_query->have_posts()) {
                $testimonial_query->the_post();
                echo '<div class="testimonial-item">';
                if (has_post_thumbnail()) {
                    echo '<div class="testimonial-thumbnail">';
                    the_post_thumbnail('staff-photo');
                    echo '</div>'; 
                }
                the_title('<h3>', '</h3>');
                echo '<div class="testimonial-content">';
                the_content();
                echo '</div>'; 

                echo '</div>'; 
            }
            echo '</div>'; 
            wp_reset_postdata();
        } else {
            echo '<p>No testimonials found</p>';
        }
    ?>

</main><!-- #main -->

<?php
// get_sidebar();
get_footer();
?>