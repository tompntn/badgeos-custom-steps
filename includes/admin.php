<?php
/**
 * Integrates the new step type in the wordpress admin
 *
 * @package BadgeOS Custom JS Steps
 */

 /**
  * Update badgeos_get_step_requirements to include the new step requirements
  *
  * @since  0.0.1
  *
  * @param  array   $requirements The current step requirements.
  * @param  integer $step_id      The given step's post ID.
  *
  * @return array                 The updated step requirements.
  */
 function badgeos_custom_js_steps_step_requirements( $requirements, $step_id ) {

 	// Add our new requirements to the list
  $requirements[ 'custom_js_steps_trigger' ] = get_post_meta( $step_id, '_badgeos_custom_js_steps_trigger', true );
 	$requirements[ 'custom_js_steps_object_id' ] = (int) get_post_meta( $step_id, '_badgeos_custom_js_steps_object_id', true );
 	$requirements[ 'custom_js_steps_object_arg1' ] = (int) get_post_meta( $step_id, '_badgeos_custom_js_steps_object_arg1', true );

 	// Return the requirements array
 	return $requirements;

 }

 add_filter( 'badgeos_get_step_requirements', 'badgeos_custom_js_steps_step_requirements', 10, 2 );

 /**
  * Adds the new option to the BadgeOS Triggers selector
  *
  * @since  0.0.1
  *
  * @param  array $triggers The existing triggers array.
  *
  * @return array           The updated triggers array
  */
 function badgeos_custom_js_steps_activity_triggers( $triggers ) {

  // Adds our new trigger to the main triggers array
 	$triggers[ 'custom_js_steps_trigger' ] = __( 'JavaScript Event Listener', 'badgeos-custom-js-steps' );

 	return $triggers;

 }

 add_filter( 'badgeos_activity_triggers', 'badgeos_custom_js_steps_activity_triggers' );

 /**
  * UI to specify what event listener will trigger the step
  *
  * @since 0.0.1
  *
  * @param integer $step_id The given step's post ID.
  * @param integer $post_id The given parent post's post ID.
  */
 function badgeos_custom_js_steps_etc_trigger_select( $step_id, $post_id ) {

	 $current_event_listener_slug = get_post_meta( $step_id, '_badgeos_custom_js_steps_event_listener_slug', true );

   echo '<span class="badgeos_custom_js_steps_event_listener_slug"><br>Event Listener: '.
   '<input name="badgeos_custom_js_steps_event_listener_slug" type="text" value="' . $current_event_listener_slug . '" placeholder="my_event_listener" /><br></span>';

 }

 add_action( 'badgeos_steps_ui_html_after_trigger_type', 'badgeos_custom_js_steps_etc_trigger_select', 10, 2 );

 /**
  * Filter the AJAX Handler for saving all steps to accomodate our new one
  *
  * @since  0.0.1
  *
  * @param  string  $title     The original title for our step.
  * @param  integer $step_id   The given step's post ID.
  * @param  array   $step_data Our array of all available step data.
  *
  * @return string             Our potentially updated step title.
  */
 function badgeos_custom_js_steps_save_step( $title, $step_id, $step_data ) {

 	// Exit if we are not working on the trigger we added
  if ( $step_data[ 'trigger_type' ] != 'custom_js_steps_trigger' ) {
    return $title;
  }

  foreach ($step_data as $key => $value) {
    update_post_meta( $step_id, '_badgeos_custom_js_steps_FIELD:'.$key, $value );
  }

  // Storing the data the user entered
  $event_listener_slug = (string) $step_data[ 'custom_js_steps_event_listener_slug' ];
  update_post_meta( $step_id, '_badgeos_custom_js_steps_event_listener_slug', $event_listener_slug );

  // Re-writing the title
  $title = sprintf( __( 'The event listener %s was triggered.', 'badgeos-custom-js-steps' ), $event_listener_slug );

 	// Send back our custom title
 	return $title;

 }

 add_filter( 'badgeos_save_step', 'badgeos_custom_js_steps_save_step', 10, 3 );

 /**
  * Enqueue our script for tidying up the modified admin menu
  *
  * @since 0.0.1
  */
 function badgeos_custom_js_steps_enqueue_js() {

   // Registered this script earlier (in plugin init file)
   wp_enqueue_script('badgeos-custom-js-steps-admin');

 }

 add_action( 'admin_footer', 'badgeos_custom_js_steps_enqueue_js', 10, 2 );
