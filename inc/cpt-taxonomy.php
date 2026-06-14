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
        'publicly_queryable' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_rest' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'service'),
        'capability_type' => 'post',
        'has_archive' => false,
        'hierarchical' => false,
        'menu_position' => null,
        'menu_icon' => 'dashicons-scissors',
        'supports' => array('title', 'editor'),
        'template' => array(
            array('core/image', array()),
            array('core/paragraph', array(
                'placeholder' => 'Write a short description of this service…',
            )),
        ),
        'template_lock' => 'all',
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

    // CPT for Portfolio
    register_post_type( 'shar-portfolio', [
        'labels' => [
            'name'          => 'Portfolio',
            'singular_name' => 'Portfolio Item',
            'add_new_item'  => 'Add New Portfolio Item',
            'edit_item'     => 'Edit Portfolio Item',
            'menu_name'     => 'Portfolio',
        ],
        'public'        => false,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'menu_icon'     => 'dashicons-format-video',
        'supports'      => [ 'thumbnail' ],
        'menu_position' => 6,
    ] );
}
add_action('init', 'create_service_post_type');

// ── Portfolio meta box ───────────────────────────────────────────────────
add_action( 'add_meta_boxes', function () {
    add_meta_box( 'shar_portfolio_video', 'Video', 'shar_portfolio_meta_box', 'shar-portfolio', 'normal', 'high' );
} );

function shar_portfolio_meta_box( $post ) {
    wp_nonce_field( 'shar_portfolio_save', 'shar_portfolio_nonce' );
    $video_url = get_post_meta( $post->ID, '_shar_portfolio_video_url', true );
    ?>
    <p>
        <strong>Upload or select a video from your media library.</strong><br>
        <small style="color:#666;">Convert .mov to .mp4 first (use cloudconvert.com). Set a Featured Image as the thumbnail shown in the grid.</small>
    </p>
    <div style="display:flex;gap:8px;align-items:center;margin-top:8px;">
        <input type="url" id="shar_portfolio_video_url" name="shar_portfolio_video_url"
            value="<?php echo esc_attr( $video_url ); ?>" style="flex:1;" placeholder="https://...">
        <button type="button" id="shar_portfolio_video_btn" class="button">Select Video</button>
    </div>
    <script>
    (function($){
        $('#shar_portfolio_video_btn').on('click', function(e){
            e.preventDefault();
            var frame = wp.media({ title: 'Select Portfolio Video', button: { text: 'Use this video' }, library: { type: 'video' }, multiple: false });
            frame.on('select', function(){ $('#shar_portfolio_video_url').val(frame.state().get('selection').first().toJSON().url); });
            frame.open();
        });
    }(jQuery));
    </script>
    <?php
}

add_action( 'save_post_shar-portfolio', function ( $post_id ) {
    if ( ! isset( $_POST['shar_portfolio_nonce'] ) || ! wp_verify_nonce( $_POST['shar_portfolio_nonce'], 'shar_portfolio_save' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( isset( $_POST['shar_portfolio_video_url'] ) ) {
        update_post_meta( $post_id, '_shar_portfolio_video_url', esc_url_raw( $_POST['shar_portfolio_video_url'] ) );
    }
    // Auto-title from filename so admin list isn't blank
    $post = get_post( $post_id );
    if ( empty( $post->post_title ) || $post->post_title === 'Auto Draft' ) {
        $url      = $_POST['shar_portfolio_video_url'] ?? '';
        $filename = $url ? pathinfo( parse_url( $url, PHP_URL_PATH ), PATHINFO_FILENAME ) : 'Portfolio Item';
        wp_update_post( [ 'ID' => $post_id, 'post_title' => sanitize_text_field( $filename ) ] );
    }
} );

add_action( 'admin_enqueue_scripts', function ( $hook ) {
    if ( ! in_array( $hook, [ 'post.php', 'post-new.php' ] ) ) return;
    $screen = get_current_screen();
    if ( $screen && $screen->post_type === 'shar-portfolio' ) wp_enqueue_media();
} );



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


    // Service categories — synced from Square.
    register_taxonomy('shar-service-category', array('shar-service'), array(
        'hierarchical'      => true,
        'labels'            => array(
            'name'          => 'Service Categories',
            'singular_name' => 'Service Category',
            'menu_name'     => 'Categories',
        ),
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'service-category'),
    ));

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