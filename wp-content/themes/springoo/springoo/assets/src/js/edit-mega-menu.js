import '../scss/admin/mega-menu.scss';

// noinspection ES6ConvertVarToLetConst
/* global ajaxurl */
( function( $, window, document ) {
    'use strict';

    const preventDefault = e => e.preventDefault();

    /**
     * Springoo Mega Menu
     * @param el
     * @constructor
     */
    const SpringooMegamenu = function( el ) {
        const base = this;

        // Access to jQuery and DOM versions of element
        const $elem = $( el );
        // base.$el = $( el );
        // base.el = el;
        // Add a reverse reference to the DOM object
        //$elem.data( 'SpringooMegamenu', base );

        let _timeout = 0;

        base.init = function() {
            $elem.on( 'click', '.is-mega', function() {
                base.flush( $( this ) );
                base.depends();
            } );

            $elem.on( 'mouseup', '.menu-item-bar', function() {
                clearTimeout( _timeout );
                _timeout = setTimeout( function() {
                    base.depends();
                }, 50 );
            } );

            $elem.on( 'change', '.is-width', function() {
                const _this = $( this );
                const type = _this.val();
                const _container = _this.closest( '.springoo-mega-menu' );

                // toggle class remove class if state is false.
                _container.find( '.mega-depend-width' ).toggleClass( 'hidden', type !== 'custom' );
                // show position dropdown only if item is not full width.
                _container.find( '.mega-depend-position' ).toggleClass( 'hidden', 'full' === type );
            } );

            $( '.is-width' ).trigger( 'change' );

            base.depends();
        };

        base.depends = function() {

            $elem.find( '.is-mega' ).each( function() {
                base.flush( $( this ) );
            } );

            // clear all mega columns
            $( 'li', $elem ).removeClass( 'active-mega-column' ).removeClass( 'active-sub-mega-column' );

            // Add columns for mega menu.
            const nextDepth = $( '.active-mega-menu', $elem ).nextUntil( '.menu-item-depth-0', 'li' );
            nextDepth.closest( 'li.menu-item-depth-1' ).addClass( 'active-mega-column' );
            nextDepth.closest( 'li:not(.menu-item-depth-1)' ).addClass( 'active-sub-mega-column' );
        };

        base.flush = function( _el ) {
            const isActive = _el.is( ':checked' );
            const item = _el.closest( 'li' );

            item.toggleClass( 'active-mega-menu', isActive );
            item.find( '.field-mega-width' ).toggleClass( 'hidden', ! isActive );
        };

        // Run initializer
        base.init();
    };

    $.fn.springooMegamenu = function() {
        return this.each( function() {
            new SpringooMegamenu( this );
        } );
    };

    // ======================================================

    /**
     * Springoo Megamenu Icon Picker
     */
    const springooMegamenuIcons = () => {
        const _iconDialog = $( '#icon-dialog' );
        const _iconOverlay = $( '#icon-overlay' );
        const _iconInsert = _iconDialog.find( '#icon-insert' );
        const _iconLoad = _iconDialog.find( '#icon-load' );
        const _iconSearch = _iconDialog.find( '#icon-search' );
        const _iconDialogHeight = ( $( window ).height() < 500 ) ? parseInt( $( window ).height() ) : 500;
        let _iconSelected = false;
        let _iconsLoaded = false;
        let _iconRemove;
        let _iconParent;
        let _iconValue;
        let _iconPreview;

        _iconDialog.dialog( {
            dialogClass: 'wp-dialog springoo-icon-dialog',
            width: 1000,
            height: _iconDialogHeight,
            closeOnEscape: true,
            autoOpen: false,
            create: function() {
                $( '.ui-dialog-titlebar-close' ).addClass( 'ui-button' );
            },
            open: function() {
                _iconLoad.height( _iconDialogHeight - 210 );
                _iconOverlay.show();
            },
            close: function() {
                // clear on close.
                _iconSelected = false;
                _iconOverlay.hide();
            },
            resize: function( event, ui ) {
                _iconLoad.height( parseInt( ui.size.height ) - 210 );
            },
        } );

        $( document ).on( 'click', '.pick-icon[data-name]', function( e ) {
            preventDefault(e);
            const self = $( this );
            if ( self.is( '.active-icon' ) ) {
                self.removeClass( 'active-icon' );
                _iconSelected = false;
            } else {
                self.addClass( 'active-icon' ).siblings().removeClass( 'active-icon' );
                _iconSelected = self.data( 'name' );
            }
        } );

        let iconList;

        _iconSearch.keyup( function() {
            if( ! _iconsLoaded ) {
                return;
            }
            if ( ! iconList ) {
                iconList = _iconLoad.find( 'a' );
            }
            const search = $( this ).val();
            if ( ! search ) {
                iconList.show();
                return;
            }
            const pattern = new RegExp( search, 'i' )
            iconList.each( function() {
                const _this = $( this );
                if ( _this.data( 'name' ).search( pattern ) < 0 ) {
                    _this.hide();
                } else {
                    _this.show();
                }
            } );

        } );

        _iconInsert.click( function( e ) {
            preventDefault(e);
            if( ! _iconsLoaded || ! _iconSelected ) {
                return;
            }

            const label = _iconPreview.data('label').replace( '%s', _iconSelected.replace( 'ti-', '' ).replace( '-', ' ' ) );
            // preview
            _iconPreview.removeClass( 'hidden' );
            _iconPreview.html( `<span class="springoo-icon ${_iconSelected}" aria-label="${label}"></span>` );
            _iconPreview.next( '.springoo-icon-add' ).data( 'icon', _iconSelected );

            // value
            _iconValue.val( _iconSelected ).trigger( 'keyup' );

            // remove icon class
            _iconRemove.removeClass( 'hidden' );

            // close dialog
            _iconDialog.dialog( 'close' );
        } );

        $( document.body ).on( 'click', '.springoo-icon-add', function( e ) {

            preventDefault(e);

            // set vars

            _iconParent = $( this ).parent();
            _iconPreview = _iconParent.find( '.icon-preview' );
            _iconRemove = _iconParent.find( '.springoo-icon-remove' );
            _iconValue = _iconParent.find( '.icon-value' );

            _iconDialog.dialog('open');

            if ( _iconsLoaded === false ) {
                $.ajax( {
                    type: 'POST',
                    url: ajaxurl,
                    data: { action: 'springoo-icons'},
                    success: function( data ) {
                        _iconLoad.html( data );
                        //_iconLoad.find( '[data-name]' ).removeClass( 'active-icon' );
                        _iconLoad.find( '[data-name="' + $( this ).data( 'icon' ) + '"]' ).addClass( 'active-icon' );
                        _iconsLoaded = true;
                    },
                } );
            } else {
                _iconLoad.find( 'a' ).removeClass( 'active-icon' );
                _iconLoad.find( '[data-name="' + $( this ).data( 'icon' ) + '"]' ).addClass( 'active-icon' );
            }
        } );

        // clear
        $( document.body ).on( 'click', '.springoo-icon-remove', function( e ) {
            preventDefault(e);
            const self = $( this );
            const item = self.parent();

            item.find( '.icon-preview' ).empty().addClass( 'hidden' );
            item.find( '.springoo-icon-add' ).data( 'icon', '' );
            item.find( '.icon-value' ).val( '' );
            self.addClass( 'hidden' );
        } );

        _iconOverlay.click( function( e ) {
            preventDefault(e);
            _iconOverlay.hide();
            _iconDialog.dialog( 'close' );
        } );
    };

    $( document ).ready( function() {
        $( '#menu-to-edit' ).springooMegamenu();
        //$( '.springoo-icon-select' ).
        springooMegamenuIcons();
    } );
} )( jQuery, window, document );
