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
         echo '<div class="post-content-staff">';
         the_title('<h1 class="name-staff">', '</h1>');
         ?>

    <!-- More info button -->
    <a href="/service/" class="wp-element-button cta-btn">Book Now</a>

    <?php
         the_post_thumbnail('staff-photo');
         echo '<div class="staff-bio">';
         the_content();
         echo '</div>'; // Close staff-bio div
         echo '</div>'; // Close post-content-staff div
         
 
         // Start of service
         echo '<div class="services-staff">';
         echo '<h3>Services:</h3>';
 
         // Get the slug of the current staff page
         $slug = $post->post_name;
 
         // Grabbing all the services that match the staff
         $args = array(
             'post_type' => 'shar-service',
             'posts_per_page' => 3,
             'orderby' => 'title',
             'order' => 'ASC',
             'tax_query' => array(
                 array(
                     'taxonomy' => 'shar-staff-taxonomy',
                     'field' => 'slug',
                     'terms' => $slug
                 ),
             ),
         );
         $query = new WP_Query($args);
         while ($query->have_posts()) {
             $query->the_post();
             echo '<div class="service-item-staff">';
             if (has_post_thumbnail()) {
                 echo '<a href="' . get_permalink() . '">';
                 // You can specify the size here
                 the_post_thumbnail('staff-photo');
                 echo '</a>';
             }
             ?>
    <h3><?php the_title(); ?></h3>
    <?php
         
         wp_reset_postdata();
         ?>





    <?php
             echo '</div>'; // Close service-item
         }
         wp_reset_postdata();
         echo '</div>';
         // End of service
 
         // Start of testimonials
         echo '<div class="testimonials-staff">';
        //  echo '<h3>Testimonials:</h3>';
 
         // Get the slug of the current staff page
         $slug = $post->post_name;
         // Grabbing all the testimonials that match the staff
         $testimonial_args = array(
            'post_type' => 'shar-testimonial',
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
                // the_title('<h3>', '</h3>');
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
     endwhile;
     ?>
</main><!-- #main -->

<?php
 get_footer();
 ?>