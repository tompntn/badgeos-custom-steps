<?php
/**
 * Integrates the new step type in wp admin
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
  * UI to specify what function call to be listening for.
  *
  * @since 0.0.1
  *
  * @param integer $step_id The given step's post ID.
  * @param integer $post_id The given parent post's post ID.
  */
 function badgeos_custom_js_steps_etc_trigger_select( $step_id, $post_id ) {

   $current_trigger = get_post_meta( $step_id, '_badgeos_custom_js_steps_trigger', true );
	 $current_object_id = (int) get_post_meta( $step_id, '_badgeos_custom_js_steps_object_id', true );
	 $current_object_arg1 = (int) get_post_meta( $step_id, '_badgeos_custom_js_steps_object_arg1', true );

   ($current_trigger == 'badgeos-custom-js-steps') ? $function_name_placeholder = (string) $current_object_arg1 : $function_name_placeholder = "";

   echo '<span class="badgeos_custom_js_steps_function_name">Function: '.
   '<input name="badgeos_custom_js_steps_function_name" type="text" value="' . $function_name_placeholder . '" placeholder="my_func_name" /></span>';

 }

 add_action( 'badgeos_steps_ui_html_after_trigger_type', 'badgeos_custom_js_steps_etc_trigger_select', 10, 2 );

 /**
  * Enqueue our script for tidying up the modified admin menu
  *
  * @since 0.0.1
  */
 function badgeos_custom_js_steps_enqueue_js( ) {

   // Registered this script earlier (in plugin init file)
   wp_enqueue_script('badgeos-custom-js-steps-admin');

 }

 add_action( 'admin_footer', 'badgeos_custom_js_steps_enqueue_js', 10, 2 );

// TODO: Filter AJAX handler for saving all steps to save our custom steps
// TODO: Inject JS code so we can add our extra options to the UI
