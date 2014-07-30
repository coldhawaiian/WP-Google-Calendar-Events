<?php

/**
 * Register all settings needed for the Settings API.
 *
 * @package    gce
 * @subpackage Includes
 * @author     Phil Derksen <pderksen@gmail.com>, Nick Young <mycorpweb@gmail.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 *  Main function to register all of the plugin settings
 *
 * @since 2.0.0
 */
function gce_register_settings() {
	
	$gce_settings = array(

		/* General Settings */
		'general' => array(
			'display_start' => array(
				'id'      => 'display_start',
				'name'    => __( 'Start Time Display', 'gce' ),
				'desc'    => __( 'Choose how you want the start time displayed.', 'gce' ),
				'type'    => 'select',
				'options' => array(
					'none'      => __( "Don't Display", 'gce' ),
					'time'      => __( 'Start Time', 'gce' ),
					'date'      => __( 'Start Date', 'gce' ),
					'time-date' => _x( 'Time, Date', 'Option for how event date and time should be ordered. Order is important here.', 'gce' ),
					'date-time' => _x( 'Date, Time', 'Option for how event date and time should be ordered. Order is important here.' , 'gce' )
				)
			),
			'display_start_text' => array(
				'id'   => 'display_start_text',
				'name' => __( 'Start Text', 'gce' ),
				'desc' => __( 'Text displayed before the start time.', 'gce' ),
				'type' => 'text'
			),
			'display_end' => array(
				'id'      => 'display_end',
				'name'    => __( 'End Time Display', 'gce' ),
				'desc'    => __( 'Choose how you want the end time displayed.', 'gce' ),
				'type'    => 'select',
				'options' => array(
					'none'      => __( "Don't Display", 'gce' ),
					'time'      => __( 'End Time', 'gce' ),
					'date'      => __( 'End Date', 'gce' ),
					'time-date' => _x( 'Time, Date', 'Option for how event date and time should be ordered. Order is important here.', 'gce' ),
					'date-time' => _x( 'Date, Time', 'Option for how event date and time should be ordered. Order is important here.' , 'gce' )
				)
			),
			'display_end_text' => array(
				'id'   => 'display_end_text',
				'name' => __( 'End Text', 'gce' ),
				'desc' => __( 'Text displayed before the end time.', 'gce' ),
				'type' => 'text'
			),
			'display_location' => array(
				'id'   => 'display_location',
				'name' => __( 'Display Location', 'gce' ),
				'desc' => __( 'Display the location for the event.', 'gce' ),
				'type' => 'checkbox'
			),
			'display_location_text' => array(
				'id'   => 'display_location_text',
				'name' => __( 'Location Text', 'gce' ),
				'desc' => __( 'Text displayed before the location.', 'gce' ),
				'type' => 'text'
			),
			'display_desc' => array(
				'id'   => 'display_desc',
				'name' => __( 'Display Description', 'gce' ),
				'desc' => __( 'Display the description for the event.', 'gce' ),
				'type' => 'checkbox'
			),
			'display_desc_text' => array(
				'id'   => 'display_desc_text',
				'name' => __( 'Description Text', 'gce' ),
				'desc' => __( 'Text displayed before the description.', 'gce' ),
				'type' => 'text'
			),
			'display_desc_limit' => array(
				'id'   => 'display_desc_limit',
				'name' => __( 'Description Limit', 'gce' ),
				'desc' => __( 'Number of words to limit the description to. Leave blank for no limit.', 'gce' ),
				'type' => 'text'
			),
			'display_link' => array(
				'id'   => 'display_link',
				'name' => __( 'Display Link', 'gce' ),
				'desc' => __( 'Display the link to the event.', 'gce' ),
				'type' => 'checkbox'
			),
			'display_link_text' => array(
				'id'   => 'display_link_text',
				'name' => __( 'Link Text', 'gce' ),
				'desc' => __( 'Text of the link displayed.', 'gce' ),
				'type' => 'text'
			),
			'save_settings' => array(
				'id'   => 'save_settings',
				'name' => __( 'Save Settings', 'gce' ),
				'desc' => __( 'Save your settings when uninstalling this plugin. Useful when upgrading or re-installing.', 'gce' ),
				'type' => 'checkbox'
			)
		)
	);

	/* If the options do not exist then create them for each section */
	if ( false == get_option( 'gce_settings_general' ) ) {
		add_option( 'gce_settings_general' );
	}

	/* Add the General Settings section */
	add_settings_section(
		'gce_settings_general',
		__( 'General Settings', 'gce' ),
		'__return_false',
		'gce_settings_general'
	);

	foreach ( $gce_settings['general'] as $option ) {
		add_settings_field(
			'gce_settings_general[' . $option['id'] . ']',
			$option['name'],
			function_exists( 'gce_' . $option['type'] . '_callback' ) ? 'gce_' . $option['type'] . '_callback' : 'gce_missing_callback',
			'gce_settings_general',
			'gce_settings_general',
			gce_get_settings_field_args( $option, 'general' )
		);
	}

	/* Register all settings or we will get an error when trying to save */
	register_setting( 'gce_settings_general',         'gce_settings_general',         'gce_settings_sanitize' );

}
add_action( 'admin_init', 'gce_register_settings' );

/*
 * Return generic add_settings_field $args parameter array.
 *
 * @since     2.0.0
 *
 * @param   string  $option   Single settings option key.
 * @param   string  $section  Section of settings apge.
 * @return  array             $args parameter to use with add_settings_field call.
 */
function gce_get_settings_field_args( $option, $section ) {
	$settings_args = array(
		'id'      => $option['id'],
		'desc'    => $option['desc'],
		'name'    => $option['name'],
		'section' => $section,
		'size'    => isset( $option['size'] ) ? $option['size'] : null,
		'options' => isset( $option['options'] ) ? $option['options'] : '',
		'std'     => isset( $option['std'] ) ? $option['std'] : ''
	);

	// Link label to input using 'label_for' argument if text, textarea, password, select, or variations of.
	// Just add to existing settings args array if needed.
	if ( in_array( $option['type'], array( 'text', 'select', 'textarea', 'password', 'number' ) ) ) {
		$settings_args = array_merge( $settings_args, array( 'label_for' => 'gce_settings_' . $section . '[' . $option['id'] . ']' ) );
	}

	return $settings_args;
}

/*
 * Single checkbox callback function
 * 
 * @since 2.0.0
 * 
 */
function gce_checkbox_callback( $args ) {
	global $gce_options;

	$checked = isset( $gce_options[$args['id']] ) ? checked( 1, $gce_options[$args['id']], false ) : '';
	$html = "\n" . '<input type="checkbox" id="gce_settings_' . $args['section'] . '[' . $args['id'] . ']" name="gce_settings_' . $args['section'] . '[' . $args['id'] . ']" value="1" ' . $checked . '/>' . "\n";

	// Render description text directly to the right in a label if it exists.
	if ( ! empty( $args['desc'] ) )
		$html .= '<label for="gce_settings_' . $args['section'] . '[' . $args['id'] . ']"> '  . $args['desc'] . '</label>' . "\n";

	echo $html;
}

/*
 * Select box callback function
 * 
 * @since 2.0.0
 * 
 */
function gce_select_callback( $args ) {
	global $gce_options;

	// Return empty string if no options.
	if ( empty( $args['options'] ) ) {
		echo '';
		return;
	}

	$html = "\n" . '<select id="gce_settings_' . $args['section'] . '[' . $args['id'] . ']" name="gce_settings_' . $args['section'] . '[' . $args['id'] . ']"/>' . "\n";

	foreach ( $args['options'] as $option => $name ) :
		$selected = isset( $gce_options[$args['id']] ) ? selected( $option, $gce_options[$args['id']], false ) : '';
		$html .= '<option value="' . $option . '" ' . $selected . '>' . $name . '</option>' . "\n";
	endforeach;

	$html .= '</select>' . "\n";

	// Render and style description text underneath if it exists.
	if ( ! empty( $args['desc'] ) )
		$html .= '<p class="description">' . $args['desc'] . '</p>' . "\n";

	echo $html;
}

/**
 * Textbox callback function
 * Valid built-in size CSS class values:
 * small-text, regular-text, large-text
 * 
 * @since 2.0.0
 * 
 */
function gce_text_callback( $args ) {
	global $gce_options;

	if ( isset( $gce_options[ $args['id'] ] ) )
		$value = $gce_options[ $args['id'] ];
	else
		$value = isset( $args['std'] ) ? $args['std'] : '';

	$size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : '';
	$html = "\n" . '<input type="text" class="' . $size . '" id="gce_settings_' . $args['section'] . '[' . $args['id'] . ']" name="gce_settings_' . $args['section'] . '[' . $args['id'] . ']" value="' . esc_attr( $value ) . '"/>' . "\n";

	// Render and style description text underneath if it exists.
	if ( ! empty( $args['desc'] ) )
		$html .= '<p class="description">' . $args['desc'] . '</p>' . "\n";

	echo $html;
}

/*
 * Function we can use to sanitize the input data and return it when saving options
 * 
 * @since 2.0.0
 * 
 */
function gce_settings_sanitize( $input ) {
	//add_settings_error( 'gce-notices', '', '', '' );
	return $input;
}

/*
 *  Default callback function if correct one does not exist
 * 
 * @since 2.0.0
 * 
 */
function gce_missing_callback( $args ) {
	printf( __( 'The callback function used for the <strong>%s</strong> setting is missing.', 'gce' ), $args['id'] );
}

/*
 * Function used to return an array of all of the plugin settings
 * 
 * @since 2.0.0
 * 
 */
function gce_get_settings() {

	// Set default settings
	// If this is the first time running we need to set the defaults
	if ( ! get_option( 'gce_upgrade_has_run' ) ) {
		
		$general = get_option( 'gce_settings_general' );
		
		$general['display_start']      = 'time';
		$general['display_end']        = 'time';
		$general['display_start_text'] = _x( 'Starts:', 'Default for "Start Text" setting', 'gce' );
		$general['display_end_text']   = _x( 'Ends:', 'Default for "End Text" setting', 'gce' );
		$general['display_link']       = 1;
		$general['display_link_text']  = _x( 'More details...', 'Default for "Link Text" setting', 'gce' );
		$general['save_settings']      = 1;
		
		update_option( 'gce_settings_general', $general );
	}
	
	$general_settings = is_array( get_option( 'gce_settings_general' ) ) ? get_option( 'gce_settings_general' )  : array();

	return $general_settings;
}
