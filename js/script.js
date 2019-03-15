(function( $ ) {

	//Initializing jQuery UI Datepicker
	$( '#mes-event-start-date' ).datetimepicker({
		dateFormat: 'M d, Y H:i',
		onClose: function( selectedDate ){
			$( '#mes-event-end-date' ).datetimepicker( 'option', 'minDate', selectedDate );
		}
	});
	$( '#mes-event-end-date' ).datetimepicker({
		dateFormat: 'M d, Y H:i',
		onClose: function( selectedDate ){
			$( '#mes-event-start-date' ).datetimepicker( 'option', 'maxDate', selectedDate );
		}
	});

})( jQuery );
