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

<main id="primary" class="site-main page-staff-list">

    <h1 class='staff-header'>Our Team</h1>

    <?php

    $args = array(
        'post_type' => 'bdw-staff',
        'posts_per_page' => -1, // To display all staff members
        'orderby' => 'title',
        'order' => 'ASC',
    );

    $staff_query = new WP_Query($args);

    if ($staff_query->have_posts()) {
        echo '<ul class="staffs-list">';
        while ($staff_query->have_posts()) {
            $staff_query->the_post();
            echo '<li>';
            

            if (has_post_thumbnail()) {
                the_post_thumbnail('staff-photo');
            }
            echo '<h2>' . get_the_title() .'</h2>';

            the_excerpt();

						// // More Info button
						// echo 'div class = '"more-info-button"'<a href=""' . get_permalink() . '" class="wp-element-button">More Info</a>';

            ?>

    <!-- More info button -->
    <a href="<?php the_permalink(); ?>" class="wp-element-button cta-btn">More Info</a>

    <?php


            $terms = get_the_term_list($post->ID, 'bdw-staff-type', '', ', ');
            if (!is_wp_error($terms) && !empty($terms)) {
                echo '<p>Categories: ' . $terms . '</p>';
            }
            echo '</li>';
        }
        echo '</ul>';
    } else {
        // no posts found
        echo '<p>No Staff found</p>';
    }

    wp_reset_postdata();

    // Testimonials Section
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

</main>

<?php
get_footer();
?>