jQuery( function( $ ) {

	function cf_v3_hide_elementor_msg() {
		// console.log( 'Hide elementor' );
		$( 'h1' ).each( function() {
			var h1 = $( this );
			console.log('h1.text(): ' + h1.text());
			if( h1.text() === 'New - Premium Template Kits' ) {
				// console.log('esconder msg');
			} else {
				// console.log('não existe msg');
			}
		});

	}

	var cf_v3_hide_elementor_msg_loop = setInterval( cf_v3_hide_elementor_msg, 1000 );
});