<?php
/*
Plugin Name: BadgeOS Custom Steps JS Triggers
Description: A BadgeOS Plugin giving "Required Steps" the ability to listen for JS function calls.
Version: 0.0.2
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

    // Load plugin files
    add_action( 'plugins_loaded', array( $this, 'load_plugin_files' ), 11 );

  }

}

/**
  * Deactivates the plugin with an error message if dependencies are not met (BadgeOS not installed)
  */
public function check_plugin_dependencies() {

  // BadgeOS
  if ( !class_exists( 'BadgeOS' ) || !function_exists( 'badgeos_get_user_earned_achievement_types' ) ) {

    // Admin error message
    echo '<div id="message" class="error">';
    if ( !class_exists( 'BadgeOS' ) || !function_exists( 'badgeos_get_user_earned_achievement_types' ) ) {
      echo '<p>' . sprintf( __( 'BadgeOS Custom Steps Add-On requires BadgeOS and has been <a href="%s">deactivated</a>. Please install and activate BadgeOS and then reactivate this plugin.', 'badgeos-custom-steps' ), admin_url( 'plugins.php' ) ) . '</p>';
    }
    echo '</div>';

    // Deactivate plugin
    deactivate_plugins( $this->basename );
  }
}

/**
  * Loads the plugin files
  */
public function load_plugin_files() {

		if ( $this->meets_requirements() ) {
			require_once( $this->directory_path . '/includes/rules-engine.php' );
			require_once( $this->directory_path . '/includes/steps-ui.php' );

			$this->action_forwarding();
		}
	}

$GLOBALS[ 'badgeos_customsteps' ] = new BadgeOS_CustomSteps();
