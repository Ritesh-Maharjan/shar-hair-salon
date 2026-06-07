<?php
/**
 * Shar Hair Salon Theme Customizer
 *
 * @package BDW_Massage
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function shar_salon_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'shar_salon_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'shar_salon_customize_partial_blogdescription',
			)
		);
	}
}
add_action( 'customize_register', 'shar_salon_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function shar_salon_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function shar_salon_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function shar_salon_customize_preview_js() {
	wp_enqueue_script( 'shar-hair-salon-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), _S_VERSION, true );
}
add_action( 'customize_preview_init', 'shar_salon_customize_preview_js' );

/**
 * Contact Info — Customizer section.
 *
 * Adds Appearance → Customize → Contact Info.
 */
add_action( 'customize_register', function ( WP_Customize_Manager $wp_customize ) {

	$wp_customize->add_section( 'shar_contact_info', [
		'title'    => __( 'Contact Info', 'shar-hair-salon' ),
		'priority' => 25,
	] );

	$fields = [
		'address' 	=> [ 'label' => 'Address',          'description' => 'e.g. 123 Main St, Vancouver, BC' ],
		'phone'   	=> [ 'label' => 'Phone',             'description' => 'e.g. +1 604-555-0100' ],
		'email'   	=> [ 'label' => 'Email',             'description' => 'e.g. hello@sharshairlab.com' ],
		'insta'   	=> [ 'label' => 'Instagram URL',     'description' => 'e.g. https://instagram.com/sharshairlab' ],
		'facebook'  => [ 'label' => 'Facebook URL',     'description' => 'e.g. https://facebook.com/sharshairlab' ],
	];

	foreach ( $fields as $key => $config ) {
		$wp_customize->add_setting( "shar_contact_{$key}", [
			'default'           => '',
			'sanitize_callback' => $key === 'insta' || $key === 'facebook' ? 'esc_url_raw' : 'sanitize_text_field',
		] );
		$wp_customize->add_control( "shar_contact_{$key}", [
			'label'       => $config['label'],
			'description' => $config['description'],
			'section'     => 'shar_contact_info',
			'type'        => 'text',
		] );
	}
} );

/**
 * Business Hours — Customizer section.
 *
 * Adds Appearance → Customize → Business Hours.
 * Each day has an "hours" text field (e.g. "9am – 6pm") and a "Closed" checkbox.
 * Leave the text blank OR tick Closed to show "Closed" for that day.
 */
add_action( 'customize_register', function ( WP_Customize_Manager $wp_customize ) {

	$wp_customize->add_section( 'shar_business_hours', [
		'title'    => __( 'Business Hours', 'shar-hair-salon' ),
		'priority' => 30,
	] );

	$days = [
		'monday'    => 'Monday',
		'tuesday'   => 'Tuesday',
		'wednesday' => 'Wednesday',
		'thursday'  => 'Thursday',
		'friday'    => 'Friday',
		'saturday'  => 'Saturday',
		'sunday'    => 'Sunday',
	];

	foreach ( $days as $key => $label ) {

		// Hours text field  e.g. "9am – 6pm"
		$wp_customize->add_setting( "shar_hours_{$key}", [
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		] );
		$wp_customize->add_control( "shar_hours_{$key}", [
			'label'       => $label,
			'description' => 'e.g. 9am – 6pm',
			'section'     => 'shar_business_hours',
			'type'        => 'text',
		] );

		// Closed checkbox
		$wp_customize->add_setting( "shar_hours_{$key}_closed", [
			'default'           => '',
			'sanitize_callback' => 'shar_sanitize_checkbox',
		] );
		$wp_customize->add_control( "shar_hours_{$key}_closed", [
			'label'   => $label . ' — Closed',
			'section' => 'shar_business_hours',
			'type'    => 'checkbox',
		] );
	}
} );

/** Sanitise checkbox values for the customizer. */
function shar_sanitize_checkbox( $value ) {
	return (bool) $value ? '1' : '';
}

/**
 * Output the business hours table for use in the footer.
 * Call shar_business_hours_html() inside footer.php.
 */
function shar_business_hours_html() {
	$days = [
		'monday'    => 'Monday',
		'tuesday'   => 'Tuesday',
		'wednesday' => 'Wednesday',
		'thursday'  => 'Thursday',
		'friday'    => 'Friday',
		'saturday'  => 'Saturday',
		'sunday'    => 'Sunday',
	];

	echo '<ul class="footer-hours">';
	foreach ( $days as $key => $label ) {
		$hours  = get_theme_mod( "shar_hours_{$key}", '' );
		$closed = get_theme_mod( "shar_hours_{$key}_closed", '' );

		$display = ( $closed || $hours === '' ) ? '<span class="hours-closed">Closed</span>' : esc_html( $hours );

		echo '<li class="hours-row">';
		echo '<span class="hours-day">' . esc_html( $label ) . '</span>';
		echo '<span class="hours-time">' . $display . '</span>';
		echo '</li>';
	}
	echo '</ul>';
}
