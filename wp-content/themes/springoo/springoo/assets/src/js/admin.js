
import '../scss/admin/admin.scss';

( function($) {
	$(document).ready( function() {
		$( '.editor-post-format select' ).change( function() {
			add_post_format( $(this).val() );
		} ).change();
	} );


	function add_post_format( post_format ) {
		post_format = ( 0 === post_format ) ? 'standard' : post_format;
		$( '.editor-styles-wrapper' ).removeClass().addClass( 'editor-styles-wrapper format-' + post_format );
	}

    const review_image_table = $('#woocommerce_enable_review_image').closest('table');
    $('#woocommerce_enable_reviews').on('change', function(){
        review_image_table.toggle($(this).is(':checked'));
    }).trigger('change');

    $('#woocommerce_review_image_allow_if_verified').on('change', function(){
        if ($(this).is(':checked')){
            $('#woocommerce_review_image_allow_if_logged_in').closest('label').hide();
        }else {
            $('#woocommerce_review_image_allow_if_logged_in').closest('label').show();
        }
    }).trigger('change');
} )(jQuery);
