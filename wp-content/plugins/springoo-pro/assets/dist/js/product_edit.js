(function( $ ){
	$(document).ready(function() {
		function show_and_hide_panels() {
			const is_mockup      = $( 'input#_ppm_has_mockup:checked' ).length;

			// Hide/Show all with rules.
			let hide_classes = '.hide_if_has_mockup';
			let show_classes = '.show_if_has_mockup';

			$( hide_classes ).show();
			$( show_classes ).hide();

			if ( is_mockup ) {
				$( show_classes ).show();
			} else {
				$( hide_classes ).show();
			}
		}

		show_and_hide_panels();

		$( 'body' ).on( 'woocommerce-product-type-change', function() {
			if ( 'variable' !== $( '#product-type' ).val() ) {
				$( 'input#_ppm_has_mockup' ).prop( 'checked', false );
			}
		});

		$( 'input#_ppm_has_mockup' ).on( 'change', function() {
			show_and_hide_panels();
		});
	} );
})(jQuery);
