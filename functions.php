<?php
/**
 * Shar Hair Salon functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package SHAR_HAIR_SALON
 */

if (!defined('_S_VERSION')) {
	// Replace the version number of the theme on each release.
	define('_S_VERSION', '1.0.0');
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function shar_salon_setup()
{
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on BDW Massage, use a find and replace
	 * to change 'shar-hair-salon' to the name of your theme in all the template files.
	 */
	load_theme_textdomain('shar-hair-salon', get_template_directory() . '/languages');

	// Add default posts and comments RSS feed links to head.
	add_theme_support('automatic-feed-links');

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support('title-tag');

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support('post-thumbnails');

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'footer-middle' => esc_html__('Footer - Middle', 'shar-hair-salon'),
			'footer-left' => esc_html__('Footer - Left Side', 'shar-hair-salon'),
			'footer-right' => esc_html__('Footer - Right Side', 'shar-hair-salon'),
			'header-right' => esc_html__('header-right', 'shar-hair-salon'),
			'header-left' => esc_html__('header-left', 'shar-hair-salon'),
		)
	);

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'shar_salon_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support('customize-selective-refresh-widgets');

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height' => 250,
			'width' => 250,
			'flex-width' => true,
			'flex-height' => true,
		)
	);
}
add_action('after_setup_theme', 'shar_salon_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function shar_salon_content_width()
{
	$GLOBALS['content_width'] = apply_filters('shar_salon_content_width', 640);
}
add_action('after_setup_theme', 'shar_salon_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */

add_action('wp_dashboard_setup', 'register_my_dashboard_widget');
function register_my_dashboard_widget()
{
	wp_add_dashboard_widget(
		'my_dashboard_widget',
		'Client tutorial',
		'my_dashboard_widget_display'
	);

}

function my_dashboard_widget_display()
{
	echo 'Hello, I am Mr. Widget. Download PDF for tutorial !';
	echo '<br>';
	echo '<br>';
	echo '<a href="#" target="_blank">Tutorial PDF coming soon.</a>';
}

function shar_salon_widgets_init()
{
	register_sidebar(
		array(
			'name' => esc_html__('Sidebar', 'shar-hair-salon'),
			'id' => 'sidebar-1',
			'description' => esc_html__('Add widgets here.', 'shar-hair-salon'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h2 class="widget-title">',
			'after_title' => '</h2>',
		)
	);


	register_sidebar(
		array(
			'name' => esc_html__('Footer', 'shar-hair-salon'),
			'id' => 'footer-sidebar',
			'description' => esc_html__('Add widgets here.', 'shar-hair-salon'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h2 class="widget-title">',
			'after_title' => '</h2>',
		)
	);
}
add_action('widgets_init', 'shar_salon_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function shar_salon_scripts()
{
	// Single combined request for both fonts — display=swap prevents render blocking
	wp_enqueue_style(
		'shar-google-fonts',
		'https://fonts.googleapis.com/css2?family=Archivo:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap',
		array(),
		null
	);
	wp_enqueue_style('shar-hair-salon-style', get_stylesheet_uri(), array( 'shar-google-fonts' ), _S_VERSION);
	wp_style_add_data('shar-hair-salon-style', 'rtl', 'replace');

	wp_enqueue_script('shar-hair-salon-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true);

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}

	//fixes the is_single_bdw_service error but do not no if it will work for swiper on the service individual page
	function is_single_shar_service()
	{

		if (get_post_type() == 'shar-service' && is_single()) {
			return true;
		}

		return false;
	}

	// swiper configuration
	if (is_front_page() || is_single_shar_service()) {
		wp_enqueue_style('swiper-styles', get_template_directory_uri() . '/css/swiper-bundle.css', array(), '11.0.6');
		wp_enqueue_script('swiper-scripts', get_template_directory_uri() . '/js/swiper-bundle.min.js', array(), '11.0.6', array('strategy' => 'defer'));
		wp_enqueue_script('swiper-settings', get_template_directory_uri() . '/js/swiper-settings.js', array('swiper-scripts'), _S_VERSION, array('strategy' => 'defer'));
	}

	// Remove admin menu links for non-Administrator accounts
	function fwd_remove_admin_links()
	{
		if (!current_user_can('manage_options')) {
			remove_menu_page('edit.php');           // Remove Posts link
			remove_menu_page('edit-comments.php');  // Remove Comments link
		}
	}
	add_action('admin_menu', 'fwd_remove_admin_links');

	// admin_init action works better than admin_menu in modern wordpress (at least v5+)
	function fwd_remove_menu_pages()
	{
		global $current_user;
		$user_ID = get_current_user_id();
		if ($user_ID == 5) {

			remove_menu_page('edit.php'); // Posts
			remove_menu_page('upload.php'); // Media
			remove_menu_page('link-manager.php'); // Links
			remove_menu_page('edit-comments.php'); // Comments
			remove_menu_page('edit.php?post_type=page'); // Pages
			remove_menu_page('plugins.php'); // Plugins
			remove_menu_page('themes.php'); // Appearance
			remove_menu_page('users.php'); // Users
			remove_menu_page('tools.php'); // Tools
			remove_menu_page('options-general.php'); // Settings
		}
	}
	add_action('admin_init', 'fwd_remove_menu_pages');

	wp_enqueue_script('top-feature', get_template_directory_uri() . '/js/top-feature.js', array(), _S_VERSION, array('strategy' => 'defer'));

	wp_enqueue_style('top-feature', get_template_directory_uri() . '/css/top-feature.css', array(), _S_VERSION);

}
add_action('wp_enqueue_scripts', 'shar_salon_scripts');

// ── Preload LCP hero image on homepage ───────────────────────────────────────
add_action( 'wp_head', 'shar_preload_hero_image', 1 );
function shar_preload_hero_image() {
	if ( ! is_front_page() || ! function_exists( 'get_field' ) ) return;
	$img = get_field( 'hero_image_one', get_queried_object_id() );
	if ( ! $img ) return;
	$url = is_array( $img ) ? ( $img['url'] ?? '' ) : $img;
	if ( ! $url ) return;
	echo '<link rel="preload" as="image" href="' . esc_url( $url ) . '" fetchpriority="high">' . "\n";
}

// ── Dequeue WooCommerce & Square scripts/styles on non-WC pages ──────────────
add_action( 'wp_enqueue_scripts', 'shar_dequeue_wc_on_non_wc_pages', 99 );
function shar_dequeue_wc_on_non_wc_pages() {
	// Only keep WC assets where they're actually needed.
	if ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) {
		return;
	}
	// WooCommerce block CSS
	wp_dequeue_style( 'wc-blocks-style' );
	wp_dequeue_style( 'wc-blocks-vendors-style' );
	wp_dequeue_style( 'woocommerce-inline' );
	// WooCommerce block JS
	wp_dequeue_script( 'wc-cart-fragments' );
	wp_dequeue_script( 'woocommerce' );
	// Square checkout blocks CSS (biggest offender — 29 KiB)
	wp_dequeue_style( 'wc-square-cart-checkout-blocks-style' );
	wp_dequeue_script( 'wc-square-cart-checkout-blocks' );
}

// ── Long browser cache for static assets via wp_headers ──────────────────────
add_filter( 'wp_headers', 'shar_long_cache_headers' );
function shar_long_cache_headers( $headers ) {
	if ( ! is_admin() ) {
		$headers['Cache-Control'] = 'public, max-age=31536000, immutable';
	}
	return $headers;
}

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';


/**
 * Customizer Post types.
 */
require get_template_directory() . '/inc/cpt-taxonomy.php';

/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Load WooCommerce compatibility file.
 */
if (class_exists('WooCommerce')) {
	require get_template_directory() . '/inc/woocommerce.php';
}

function shar_excerpt_length()
{
	return 40;
}

add_filter('excerpt_length', 'shar_excerpt_length', 999);

function shar_excerpt_more()
{
	return '...';
}
add_filter('excerpt_more', 'shar_excerpt_more');

add_theme_support('post-thumbnails');
add_post_type_support('shar-service', 'thumbnail');
add_post_type_support('shar-service', 'excerpt');
//custom crop size
add_image_size('blog-image', 200, 250, true);
add_image_size('featured-image', 285, 200, true);
add_image_size('staff-photo', 335, 335, true);


remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', '20');
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', '10');
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', '40');
remove_action('woocommerce_structured_data', array('WC_Structured_Data', 'generate_product_data'), 60);
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);