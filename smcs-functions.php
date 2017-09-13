<?php
/**
 * Plugin Name: SMCS Functions
 * Plugin URI:  https://github.com/DioceseOfCharlotte/smcs-functions
 * Description: Functions for altering or adding to the default behavior of various plugins on the SMCS site.
 * Version:     1.0.0
 * Author:      Marty Helmick
 * Author URI:  https://github.com/m-e-h
 * Text Domain: smcs
 * GitHub Plugin URI: DioceseOfCharlotte/smcs-functions
 */

 /**
  * Sets up the plugin
  *
  * @since  1.0.0
  * @access public
  */
final class SmcsFunctions {

	/**
	 * Stores the directory path for this plugin.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $dir;

	/**
	 * Stores the directory URI for this plugin.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $uri;

	/**
	 * Plugin setup.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function __construct() {}

	/**
	 * Defines the directory path and URI for the plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function setup() {

		$this->dir = plugin_dir_path( __FILE__ );
		$this->uri = plugin_dir_url( __FILE__ );
	}

	/**
	 * Loads the initial files needed by the plugin.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function includes() {

		require_once( $this->dir . 'lib/extended-cpts/extended-cpts.php' );
		require_once( $this->dir . 'inc/post-types.php' );
		require_once( $this->dir . 'inc/restrict-content.php' );
	}

	/**
	 * Sets up necessary actions for the plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function setup_actions() {

		//flush_rewrite_rules();
	}

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self;
			$instance->setup();
			$instance->includes();
			$instance->setup_actions();
		}

		return $instance;
	}
}

/**
 * Wrapper function for the main plugin class.
 *
 * @since  1.0.0
 * @access public
 * @return object
 */
function smcs_functions() {

	return SmcsFunctions::get_instance();
}

// Launch the plugin!
smcs_functions();