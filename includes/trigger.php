<?
/**
 * Trigger(s) for JS Achievements
 *
 * @package BadgeOS Custom JS Steps
 */

 /**
 * Load the trigger for the new action, and associates it with the trigger handler
 *
 * @since 0.0.1
 */
function badgeos_custom_js_steps_load_trigger() {

  // Register the trigger action
  $trigger_slug = 'badgeos_custom_js_steps_js_call';
  $trigger_handler = 'badgeos_custom_js_steps_trigger_event';
  add_action( $trigger_slug, $trigger_handler, 10, 20 );

}

add_action( 'init', 'badgeos_custom_js_steps_load_trigger' );

/**
 * Trigger handler - processes a trigger and updates the database
 *
 * @since 0.0.1
 */

 function badgeos_custom_js_steps_trigger_event() {

 	// Access to the wp database
 	global $blog_id, $wpdb;

 	// Setup args
 	$args = func_get_args();

  // Determining the User ID
 	$userID = get_current_user_id();

 	if ( is_array( $args ) && isset( $args[ 'user' ] ) ) {
 		if ( is_object( $args[ 'user' ] ) ) {
 			$userID = (int) $args[ 'user' ]->ID;
 		}
 		else {
 			$userID = (int) $args[ 'user' ];
 		}
 	}

 	if ( empty( $userID ) ) {
 		return;
 	}

 	$user_data = get_user_by( 'id', $userID );

 	if ( empty( $user_data ) ) {
 		return;
 	}

 	// Grab the current trigger
 	$this_trigger = current_filter();

 	// Update hook count for this user
 	$new_count = badgeos_update_user_trigger_count( $userID, $this_trigger, $blog_id );

 	// Mark the count in the log entry
 	badgeos_post_log_entry( null, $userID, null, sprintf( __( '%1$s triggered %2$s (%3$dx)', 'badgeos' ), $user_data->user_login, $this_trigger, $new_count ) );

 	// Now determine if any badges are earned based on this trigger event
 	$triggered_achievements = $wpdb->get_results( $wpdb->prepare( "
 		SELECT post_id
 		FROM   $wpdb->postmeta
 		WHERE  meta_key = '_badgeos_custom_js_steps_trigger'
 				AND meta_value = %s
 		", $this_trigger ) );

 	foreach ( $triggered_achievements as $achievement ) {
 		badgeos_maybe_award_achievement_to_user( $achievement->post_id, $userID, $this_trigger, $blog_id, $args );
 	}
 }
