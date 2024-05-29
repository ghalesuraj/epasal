( function($) {
    /**
     * YITH wishlist plugin custom icon loader
     */
    /* global yith_woocompare */
    $( document ).ready( function(){
        $(document).on('click', '.product a.compare:not(.added)', function (e) {
            e.preventDefault();
            const button = $(this);

            if (typeof $.fn.block != 'undefined') {
                // eslint-disable-next-line no-constant-condition
                const intervalId = setInterval( function () {
                    if ( ! button.data('blockUI.isBlocked') ) {
                        setTimeout( function(){
                            clearInterval( intervalId );
                            button.text( '');
                            button.html( yith_woocompare.button_text);
                        }, 500 );
                    }
                }, 100 );
            }
        });
    });
} )(jQuery);
