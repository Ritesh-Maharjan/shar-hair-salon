<?php
/**
 * The template for displaying a single service post
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package BDW_Massage
 */

get_header();
?>

<main id="primary" class="site-main">

    <?php
    while (have_posts()):
        the_post();

        // Display the post content
        echo '<h1 class="single-service-title">' . get_the_title() . '</h1>';
        the_post_thumbnail('', array('class' => 'single-service-image'));
        
        // Get the post content without applying filters
        $content = get_the_content();

        // Remove the <a> tag with specified classes from the content
        $content_without_button = preg_replace('/<a\b[^>]*class="wp-block-button__link wp-element-button"[^>]*>.*?<\/a>/i', '', $content);

        // Output the modified content with filters manually applied
        echo apply_filters('the_content', $content_without_button);

        if (function_exists('get_field')) {
            $url = home_url('/product/');
            $slug = get_post_field('post_name', get_the_ID());
            echo '<h3> Pricing </h3>';
            echo '<div class="price-list">';
            if (get_field('price1')) {
                echo '<a href="' . $url . $slug . '-45-minutes' . '"><span> 45mins/$' . get_field('price1') . '</span></a>';
            }

            if (get_field('price2')) {
                echo '<a href="' . $url . $slug . '-60-minutes' . '"><span> 60mins/$' . get_field('price2') . '</span></a>';
            }

            if (get_field('price3')) {
                echo '<a href="' . $url . $slug . '-90-minutes' . '"><span> 90mins/$' . get_field('price3') . '</span></a>';
            }

            echo '</div>';
        }

        // Start of therapist

        // Grab the current post id, then grab the taxonomies of that id
        $therapists = get_the_terms(get_the_ID(), 'bdw-staff-taxonomy');
        if ($therapists && !is_wp_error($therapists)) {
            echo '<div class="therapists">';
            echo '<h3>Therapists:</h3>';

            // Loop through each term
            foreach ($therapists as $therapist) {
                ?>
                <div class='therapist'>
                    <?php
                    $args = array(
                        'post_type' => 'bdw-staff',
                        'name' => $therapist->slug,
                        'posts_per_page' => 1
                    );

                    $query = new WP_Query($args);

                    if ($query->have_posts()) {
                        while ($query->have_posts()) {
                            $query->the_post();
                            the_post_thumbnail('staff-photo');
                            echo '<h3>' . get_the_title() . '</h3>';
                            echo '<div class="more-info-button">';
                            echo '<a class="wp-element-button cta-btn" href="' . esc_url(get_permalink()) . '">More Info</a>';
                            echo '</div>';
                        }
                        wp_reset_postdata(); // Restore original post data
                    }

                    ?>
                </div>
                <?php
            }
            echo '</div>';
        }

        // End of therapist

        // Testimonials Section
        $slug = $post->post_name;
        $testimonial_args = array(
            'post_type' => 'bdw-testimonial',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
            'tax_query' => array(
                array(
                    'taxonomy' => 'bdw-testimonial-taxonomy',
                    'field' => 'slug',
                    'terms' => $slug
                ),
            ),
        );

        $testimonial_query = new WP_Query($testimonial_args);

        if ($testimonial_query->have_posts()) {
            echo '<div class="swiper">';
            echo '<div class="swiper-wrapper">';
            while ($testimonial_query->have_posts()) {
                $testimonial_query->the_post();
                echo '<div class="swiper-slide">';
                echo '<div class="testimonial-item">';
                if (has_post_thumbnail()) {
									the_post_thumbnail();
                }
								echo '<h3>' . get_the_title() . '</h3>';
                the_content();
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
            echo '<div class="swiper-pagination"></div>';
            echo '<button class="swiper-button-prev"></button>';
            echo '<button class="swiper-button-next"></button>';
            echo '</div>';
            wp_reset_postdata(); // Reset post data after the loop
        } else {
            echo '<p>No testimonials found</p>';
        }

        // Get similar massages
        if (function_exists('get_field')) {
            $related_massages = get_field('reltaionship'); // Assuming 'reltaionship' is the correct field name
            if ($related_massages) {
                echo '<div class="similar-massage">';
								echo '<h3 class="similar-masssage-title">Similar Massage</h3>';
                echo '<div class="massage-container">'; // Start the container for flex styling
                foreach ($related_massages as $post):
                    setup_postdata($post);
                    ?>
                    <div class="massage-item">
                        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('featured-image'); ?></a>
                        <h4><?php the_title(); ?></h4>
                    </div>
                    <?php
                endforeach;
                echo '</div>'; // Close the massage-container
                echo '</div>'; // Close the similar-massage div
                wp_reset_postdata();
            }
        }
        ?>

    <?php endwhile; ?>

</main><!-- #primary -->

<?php get_footer(); ?>
