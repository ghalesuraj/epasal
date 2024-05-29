( function($) {
    /**
     * YITH wishlist plugin custom icon loader
     */
    $( document ).ready( function(){
        const icon_select2 = $('.icon-select');

        icon_select2.each( function(){
            const self = $(this);

            self.select2({
                ...self.data('select2').options.options,
                templateSelection: function (state, el) {
                    setTimeout( () => {
                        el.html('<span><i class="option-icon fa ' + state.element.value.toLowerCase() + '" ></i> ' + state.text + '</span>');
                    }, 100 );
                    return state.text;
                },
            });
        } );
    });
} )(jQuery);
