<?php
/**
 * Plugin Name:       SMCS Functions
 * Plugin URI:        https://github.com/DioceseOfCharlotte/smcs-functions
 * Description:       Functions for altering or adding to the default behavior of various plugins on the SMCS site.
 * Version:           1.6.2
 * Author:            Marty Helmick
 * License:           GNU General Public License v2
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       smcs
 * GitHub Plugin URI: https://github.com/DioceseOfCharlotte/smcs-functions
 * Requires WP:       4.7
 * Requires PHP:      5.4
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
		require_once( $this->dir . 'inc/user-roles.php' );
		require_once( $this->dir . 'inc/post-types.php' );
		require_once( $this->dir . 'inc/taxonomies.php' );
		require_once( $this->dir . 'inc/restrict-content.php' );
		require_once( $this->dir . 'inc/functions-forms.php' );
		require_once( $this->dir . 'inc/functions-front-end.php' );
	}

	/**
	 * Sets up initial actions.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function setup_actions() {
		register_activation_hook( __FILE__, array( $this, 'activation' ) );
	}

	/**
	 * Sets up necessary actions for the plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	public function activation() {
		smcs_roles_on_plugin_activation();
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
