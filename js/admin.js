// Mapping jQuery to $ in order to write nice code and avoid compatibility issues
(function($) {

  // Event listener for trigger type
  $( document ).on( 'change', '.select-trigger-type', function () {

    var trigger_type = $( this );

    // Only show our extra fields if the user has selected the relevant trigger type
    if ( 'custom_js_steps_trigger' == trigger_type.val() ) {
      trigger_type.siblings( '.badgeos_custom_js_steps_event_listener_slug' ).show();
    } else {
      trigger_type.siblings( '.badgeos_custom_js_steps_event_listener_slug' ).hide();
    }

  });

  // Copy our new data into the update step action
	$( document ).on( 'update_step_data', function ( event, step_details, step ) {

		step_details.custom_js_steps_event_listener_slug = $( 'input[name="badgeos_custom_js_steps_event_listener_slug"]', step ).val();

	});

})( jQuery );
