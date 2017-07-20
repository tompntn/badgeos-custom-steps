<?php

// Setting up our event listener
add_action( 'wp_ajax_required_steps_listener', 'required_steps_listener_action' );

/**
  * Updates the database that an action has been heard
  */
function required_steps_listener_action() {
  // Accessing the database
	global $wpdb;

  // The slug of the required action
	$action_slug = intval( $_POST['action_slug'] );

  // TODO: Process action

  // Terminate after processing, and return a proper response
	wp_die();
}
