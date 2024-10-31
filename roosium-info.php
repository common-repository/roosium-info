<?php
/**
 * Roosium Info Plugin
 *
 * @package     roosium-info
 * @author      Anton Roos <anton@roosium.com>
 * @copyright   2019 Roosium
 * @license     MIT
 *
 * @wordpress-plugin
 *
 * Plugin Name: Roosium Info
 * Plugin URI: https://wordpress.org/plugins/roosium-info
 * Description: Display information about your WordPress, PHP, Web Server and MySQL installation.
 * Author: Anton Roos
 * Author URI: https://github.com/anton-roos
 * Version: 1.0.1
 * License: MIT
 * GitHub Plugin URI: https://github.com/anton-roos/roosium-info
 * Text Domain: roosium-info
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

 class ROOS_RoosiumInfo {

	private $db;

	public function __construct( \wpdb $wpdb ) {
		$this->db = $wpdb;
		add_action( 'plugins_loaded', array( $this, 'load_text_domain' ) );
		add_filter( 'update_footer', array( $this, 'version_in_footer' ), 11 );
	}

	public function load_text_domain() {
		load_plugin_textdomain( 'roosium-info' );
	}

	public function version_in_footer() {
		$update     = core_update_footer();
		$wp_version = strpos( $update, '<strong>' ) === 0 ? get_bloginfo( 'version' ) . ' (' . $update . ')' : get_bloginfo( 'version' );
		$my_theme = wp_get_theme();
		$my_theme_domain = $my_theme->get( 'TextDomain' );
		$my_theme_version = $my_theme->get( 'Version' );

		return sprintf( esc_attr__( 'WordPress %s  | PHP %s | %s | MySQL %s | Theme %s %s', 'roosium-info' ), 
    		$wp_version, 
    		phpversion(), 
    		$_SERVER['SERVER_SOFTWARE'], 
    		$this->db->get_var('SELECT VERSION();'), 
    		$my_theme_domain, 
    		$my_theme_version 
		
		);
	}
}

global $wpdb;
new ROOS_RoosiumInfo( $wpdb );
