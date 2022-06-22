<?php
/**
 * Plugin Name: WP Job Manager Force Company Name
 * Plugin URI: https://highrise.digital/wpjm-force-company-name-plugin/
 * Description: A WordPress plugin that connects sites to the Job Relay website in preparation for relaying jobs.
 * Version: 1.0
 * Author: Highrise Digital
 * Author URI: https://highrise.digital/
 * Text Domain: wpjm-force-company-name
 * Domain Path: /languages/
 * License: GPL2+
 */

// define variable for path to this plugin file.
define( 'WPJM_FORCE_COMPANY_NAME_LOCATION', dirname( __FILE__ ) );
define( 'WPJM_FORCE_COMPANY_NAME_LOCATION_URL', plugins_url( '', __FILE__ ) );

/**
 * Function to run on plugins load.
 */
function wpjm_fc_name_plugins_loaded() {

	$locale = apply_filters( 'plugin_locale', get_locale(), 'wpjm-force-company-name' );
	load_textdomain( 'wpjm-force-company-name', WP_LANG_DIR . '/wpjm-force-company-name/wpjm-force-company-name-' . $locale . '.mo' );
	load_plugin_textdomain( 'wpjm-force-company-name', false, plugin_basename( dirname( __FILE__ ) ) . '/languages/' );

}

add_action( 'plugins_loaded', 'wpjm_fc_name_plugins_loaded' );

/**
 * Add a setting in the WPJM general tab for the company name.
 *
 * @param  array $settings The current array of WPJM settings.
 * @return array $settings The modified array of WPJM settings.
 */
function wpjm_fc_name_add_company_name_setting( $settings ) {

	// add a setting to the general tab.
	$settings['general'][1][] = array(
		'name'          => 'job_manager_forced_company_name',
		'std'           => '',
		'placeholder'   => 'Company Name',
		'label'         => __( 'Company Name', 'wpjm-force-company-name' ),
		'desc'          => __( 'Add a company name to overide any company names added to each job. This will output as the hiring organisation in the Google jobs structured data markup.', 'wpjm-force-company-name' ),
		'attributes'    => array(),
	);

	// return the settings.
	return $settings;

}

add_filter( 'job_manager_settings', 'wpjm_fc_name_add_company_name_setting', 20, 1 );

/**
 * If a global company name is present, output this instead of the one added to a job.
 *
 * @param string  $company_name The current company name.
 * @param WP_Post $post         The current post object.
 *
 * @return string $company_name The maybe modified company name.
 */
function wpjm_fc_name_force_company_name( $company_name, $post ) {

	// get the global company name added in settings.
	$global_company_name = get_option( 'job_manager_forced_company_name' );

	// if we have a global company name.
	if ( ! empty( $global_company_name ) ) {

		// set the company name to the global one.
		$company_name = $global_company_name;

	}

	// return the company name.
	return $company_name;

}

add_filter( 'the_company_name', 'wpjm_fc_name_force_company_name', 99, 2 );
