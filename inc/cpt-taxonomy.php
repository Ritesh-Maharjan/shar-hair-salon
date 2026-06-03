<?php
function create_service_post_type()
{
    //CPT for Services 
    $labels = array(
        'name' => __('Services', 'post type general name'),
        'singular_name' => __('Service', 'post type singular name'),
        'add_new' => __('Add New', 'Specialty'),
        'add_new_item' => __('Add New Service'),
        'edit_item' => __('Edit Service'),
        'new_item' => __('New Service'),
        'view_item' => __('View Service'),
        'search_items' => __('Search Services'),
        'not_found' => __('No services found'),
        'not_found_in_trash' => __('No services found in Trash'),
        'parent_item_colon' => '',
        'menu_name' => 'Services'
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_rest' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'service'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'menu_icon' => 'dashicons-admin-tools',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'template' => array(
            array('core/paragraph'),
            array('core/button'),
        ),
    );

    register_post_type('shar-service', $args);

    //CPT for Staff 
    $labels = array(
        'name' => __('Staffs', 'post type general name'),
        'singular_name' => __('Staff', 'post type singular name'),
        'add_new' => __('Add New', 'Specialty'),
        'add_new_item' => __('Add New Staff'),
        'edit_item' => __('Edit Staff'),
        'new_item' => __('New Staff'),
        'view_item' => __('View Staff'),
        'search_items' => __('Search Staffs'),
        'not_found' => __('No staffs found'),
        'not_found_in_trash' => __('No staffs found in Trash'),
        'parent_item_colon' => '',
        'menu_name' => 'Staffs'
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_rest' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'staff'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'menu_icon' => 'dashicons-admin-tools',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'template' => array(
            array('core/paragraph'),
            array('core/button'),
        ),
        'template_lock' => 'all',
    );

    register_post_type('shar-staff', $args);


    //CPT for Testimonials 
    $labels = array(
        'name' => __('Testimonial', 'post type general name'),
        'singular_name' => __('Staff', 'post type singular name'),
        'add_new' => __('Add New', 'Specialty'),
        'add_new_item' => __('Add New Staff'),
        'edit_item' => __('Edit Staff'),
        'new_item' => __('New Staff'),
        'view_item' => __('View Staff'),
        'search_items' => __('Search Testimonial'),
        'not_found' => __('No Testimonial found'),
        'not_found_in_trash' => __('No Testimonial found in Trash'),
        'parent_item_colon' => '',
        'menu_name' => 'Testimonial'
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_rest' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'testimonial'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'menu_icon' => 'dashicons-admin-users',
        'supports' => array('title', 'editor', 'thumbnail'),
        'template' => array(
            array('core/paragraph'),
        ),
        'template_lock' => 'all'
    );

    register_post_type('shar-testimonial', $args);

}
add_action('init', 'create_service_post_type');


// Taxonomies
function register_taxonomies()
{
    // taxonomy for the testimonial
    $labels = array(
        'name' => _x('Testimonial taxonomy', 'taxonomy general name'),
        'singular_name' => _x('Testimonial taxonomy', 'taxonomy singular name'),
        'search_items' => __('Search Testimonial taxonomy'),
        'all_items' => __('All Testimonial taxonomy'),
        'parent_item' => __('Parent Testimonial taxonomy'),
        'parent_item_colon' => __('Parent Testimonial taxonomy:'),
        'edit_item' => __('Edit Testimonial taxonomy'),
        'update_item' => __('Update Testimonial taxonomy'),
        'add_new_item' => __('Add New Testimonial taxonomy'),
        'new_item_name' => __('New Work Testimonial taxonomy'),
        'menu_name' => __('Testimonial taxonomy'),
    );

    $args = array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'staff'),
    );

    register_taxonomy('shar-testimonial-taxonomy', array('shar-testimonial'), $args);

    // Taxonomies for the service page
    $labels = array(
        'name' => _x('Staff taxonomy', 'taxonomy general name'),
        'singular_name' => _x('Staff taxonomy', 'taxonomy singular name'),
        'search_items' => __('Search Staff taxonomy'),
        'all_items' => __('All Staff taxonomy'),
        'parent_item' => __('Parent Staff taxonomy'),
        'parent_item_colon' => __('Parent Staff taxonomy:'),
        'edit_item' => __('Edit Staff taxonomy'),
        'update_item' => __('Update Staff taxonomy'),
        'add_new_item' => __('Add New Staff taxonomy'),
        'new_item_name' => __('New Work Staff taxonomy'),
        'menu_name' => __('Staff taxonomy'),
    );

    $args = array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'staff'),
    );

    register_taxonomy('shar-staff-taxonomy', array('shar-service'), $args);

}
add_action('init', 'register_taxonomies');

function create_service_taxonomy_from_staff($post_id) {
    // Check if the saved post is of the staf' post type
    if (get_post_type($post_id) === 'shar-staff') {
        // Get the staff name from the post title
        $staff_name = get_the_title($post_id);

        // Check if a term with the staff name already exists in the service taxonomy
        $term_exists = term_exists($staff_name, 'shar-staff-taxonomy');

        // If the term doesn't exist, create it
        if (!$term_exists) {
            // Insert the term into the your taxonomy
            wp_insert_term($staff_name, 'shar-staff-taxonomy');
        }
    }
}
add_action('save_post', 'create_service_taxonomy_from_staff');

function edit_service_taxonomy_from_staff($post_id, $post_after, $post_before)
{

    // Check if the saved post is of the staf' post type
    if (get_post_type($post_id) === 'shar-staff') {
        // gettig the old title to search for the taxonomies
        $old_title = $post_before->post_title;
        // getting new title to update the taxonmoies
        $new_title = $post_after->post_title;

        // getting the slug in order to update it if user wants to change
        $new_slug = get_post_field('post_name', $post_after);

        // getting the taxonomy id to update it
        $term = get_term_by('name', $old_title, 'shar-staff-taxonomy');

        // Check if the post is being deleted
        if ($post_after->post_status === 'trash') {
            // delete taxonomies 
            $term_id = $term->term_id;
            wp_delete_term($term_id, 'shar-staff-taxonomy');

        } else {
            // Perform actions specific to post update
            if ($term) {

                $term_id = $term->term_id;

                wp_update_term(
                    $term_id,
                    'shar-staff-taxonomy',
                    array(
                        'name' => $new_title,
                        'slug' => $new_slug,
                    )
                );
            }
        }

    }

}
add_action('post_updated', 'edit_service_taxonomy_from_staff', 10, 3);
?>