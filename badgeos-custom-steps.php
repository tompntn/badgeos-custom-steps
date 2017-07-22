<?php
/**
 * Plugin Name: BadgeOS Custom JS Steps
 * Description: This BadgeOS add-on gives "Required Steps" the ability to listen for a JS function call.
 * Author: Teepee Studios
 * Version: 0.0.1
 * Author URI: http://www.teepeestudios.com/
 */

/**
 * The main plugin instantiation class
 *
 * This contains important things that our relevant to
 * the add-on running correctly. Things like registering
 * custom post types, taxonomies, posts-to-posts
 * relationships, and the like.
 *
 * @since 0.0.1
 */
class badgeos_custom_js_steps {

	/**
	 * Get everything running.
	 *
	 * @since 0.0.1
	 */
	function __construct() {

		// Define plugin constants
		$this->basename       = plugin_basename( __FILE__ );
		$this->directory_path = plugin_dir_path( __FILE__ );
		$this->directory_url  = plugins_url( dirname( $this->basename ) );

		// If BadgeOS is unavailable, deactivate the plugin
		add_action( 'admin_notices', array( $this, 'maybe_disable_plugin' ) );

		// Include the other plugin files
		add_action( 'init', array( $this, 'includes' ) );

	} /* __construct() */


	/**
	 * Include the plugin dependencies
	 *
	 * @since 0.0.1
	 */
	public function includes() {

		// If BadgeOS is available...
		if ( $this->meets_requirements() ) {

			// Set up the trigger(s)
			require_once( $this->directory_path . '/includes/trigger.php' );

		}

	} /* includes() */

	/**
	 * Check if BadgeOS is available
	 *
	 * @since 0.0.1
	 * @return bool True if BadgeOS is available, false otherwise
	 */
	public static function meets_requirements() {

		if ( class_exists('BadgeOS') )
			return true;
		else
			return false;

	} /* meets_requirements() */

	/**
	 * Potentially output a custom error message and deactivate
	 * this plugin, if we don't meet requriements.
	 *
	 * This fires on admin_notices.
	 *
	 * @since 0.0.1
	 */
	public function maybe_disable_plugin() {

		if ( ! $this->meets_requirements() ) {
			// Display the error
			echo '<div id="message" class="error">';
			echo '<p>' . sprintf( __( 'BadgeOS Custom JS Steps requires BadgeOS and has been <a href="%s">deactivated</a>. Please install and activate BadgeOS and then reactivate this plugin.', 'badgeos-custom-js-steps' ), admin_url( 'plugins.php' ) ) . '</p>';
			echo '</div>';

			// Deactivate the plugin
			deactivate_plugins( $this->basename );
		}

	} /* maybe_disable_plugin() */

} /* badgeos_custom_js_steps */

// Instantiate the class to a global variable that we can access elsewhere
$GLOBALS['badgeos_custom_js_steps'] = new badgeos_custom_js_steps();
