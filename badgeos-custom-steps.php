<?php
/*
Plugin Name: BadgeOS Custom Steps
Description: A BadgeOS Plugin adding extra flexibility to "Required Steps".
Version: 0.0.1
Author: Teepee Studios
Author URI: http://www.teepeestudios.com
*/

class BadgeOS_CustomSteps {

  function __construct() {

		// Plugin Constants
		$this->basename = plugin_basename( __FILE__ );
		$this->directory_path = plugin_dir_path( __FILE__ );
		$this->directory_url = plugin_dir_url( __FILE__ );

		// Dependency check
	  add_action( 'admin_notices', array( $this, 'check_plugin_dependencies' ) );

	}

}

/**
	 * Deactivates the plugin with an error message if dependencies are not met (BadgeOS not installed)
	 *
	 * @since 1.0.0
	 */
public function check_plugin_dependencies() {

    // BadgeOS
		if ( !class_exists( 'BadgeOS' ) || !function_exists( 'badgeos_get_user_earned_achievement_types' ) ) {

			echo '<div id="message" class="error">';

			if ( !class_exists( 'BadgeOS' ) || !function_exists( 'badgeos_get_user_earned_achievement_types' ) ) {
				echo '<p>' . sprintf( __( 'BadgeOS Custom Steps Add-On requires BadgeOS and has been <a href="%s">deactivated</a>. Please install and activate BadgeOS and then reactivate this plugin.', 'badgeos-custom-steps' ), admin_url( 'plugins.php' ) ) . '</p>';
			}

			echo '</div>';

			// Deactivate plugin
			deactivate_plugins( $this->basename );
		}
	}

$GLOBALS[ 'badgeos_customsteps' ] = new BadgeOS_CustomSteps();
