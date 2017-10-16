<?php
/**
 * Plugin Name:       SMCS Functions
 * Plugin URI:        https://github.com/DioceseOfCharlotte/smcs-functions
 * Description:       Functions for altering or adding to the default behavior of various plugins on the SMCS site.
 * Version:           1.6.8
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
		require_once( $this->dir . 'inc/shortcodes.php' );
		require_once( $this->dir . 'inc/restrict-content.php' );
		require_once( $this->dir . 'inc/functions-forms.php' );
		require_once( $this->dir . 'inc/functions-front-end.php' );
		require_once( $this->dir . 'inc/functions-login-styles.php' );
		require_once( $this->dir . 'inc/functions-family-profile.php' );
		require_once( $this->dir . 'inc/extend-gravity-view.php' );
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
		add_action( 'wp_enqueue_scripts', array( $this, 'smcs_functions_scripts' ) );
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
	 * Loads the front end scripts and styles.  No styles are loaded if the theme supports the plugin.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function smcs_functions_scripts() {
		wp_register_style( 'smcs', $this->uri . 'css/smcs.css' );
		if ( is_singular( array( 'registration_pages', 'smcs_athletics', 'gravityview' ) ) ) {
			wp_enqueue_style( 'smcs' );
		}
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
