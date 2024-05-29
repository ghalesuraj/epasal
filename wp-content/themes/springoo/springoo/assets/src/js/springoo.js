/* global springoo_ajax, Plyr */

import 'bootstrap/dist/js/bootstrap.bundle';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';

// noinspection JSUnusedGlobalSymbols
let Springoo;

(
    function( $, window, document, Options, templateLoader ) {

        let $springoo_body, $back_to_top,
            $springoo_masthead, $springoo_main, $window,
            springoo_is_device,
            springoo_is_sticky,
            springoo_has_admin_bar, springoo_admin_bar_height,
            springoo_header_height,
            $springoo_admin_bar,
            $springoo_hasBoxLayout,
            $springoo_BoxWidth;

        Springoo = {
            initPreload: function() {
                $( window ).on( 'load', function() {
                    $( '#springoo-preloader' ).hide();
                } );
            },
            staticVariables: function() {
                $window = $( window );
                // $springoo_document = $(document);
                $springoo_body = $( 'body' );
                $back_to_top = $( '.cd-top' );
                $springoo_admin_bar = $( '#wpadminbar' );
                $springoo_masthead = $( '#masthead' );
                $springoo_main = $( '#main' );
                springoo_is_device = !!(
                    navigator.userAgent.toLowerCase().match(
                        /(android|webos|blackberry|ipod|iphone|ipad|opera mini|iemobile|windows phone|windows mobile)/ )
                );
                springoo_is_sticky = !!(
                    parseInt( Options.sticky )
                );
                springoo_has_admin_bar = !!$springoo_body.hasClass( 'admin-bar' );
                $springoo_hasBoxLayout = $springoo_body.hasClass( 'boxed' );
                $springoo_BoxWidth = $( '.boxed' ).outerWidth();
                //springoo_sticky_height = ( parseInt(Options.header)) ?
                // parseInt(Options.header) : 50;

                const self = this;
                self.getAdminBarHeight();
                self.getHeaderHeight();
                self.getHeaderTop();
            },
            getAdminBarHeight: function() {
                springoo_admin_bar_height = (
                    springoo_has_admin_bar
                )
                    ? $springoo_admin_bar.height()
                    : 0;
                return springoo_admin_bar_height;
            },
            getHeaderHeight: function() {
                springoo_header_height = (
                    $springoo_masthead
                )
                    ? $springoo_masthead.outerHeight()
                    : 0;
                return springoo_header_height;
            },
            getHeaderTop: function() {
                return (
                    $springoo_masthead && $springoo_masthead.offset()
                )
                    ? $springoo_masthead.offset().top
                    : 0;
            },
            initFormPlaceHolder: function() {
                const isOperaMini = window.operamini &&
                                    Object.prototype.toString.call( window.operamini ) ===
                                    '[object OperaMini]',
                    isInputSupported = 'placeholder' in
                                       document.createElement( 'input' ) && !isOperaMini,
                    isTextareaSupported = 'placeholder' in
                                          document.createElement( 'textarea' ) && !isOperaMini;

                if ( !isInputSupported || !isTextareaSupported ) {
                    const selector = [];
                    if ( !isInputSupported ) {
                        selector.push( 'input[placeholder]' );
                    }
                    if ( !isTextareaSupported ) {
                        selector.push( 'textarea[placeholder]' );
                    }
                    $( selector.join( ',' ) ).each( function() {
                        const el = $( this ),
                            placeholder = el.attr( 'placeholder' );

                        if ( el.val() === '' ) {
                            el.val( placeholder );
                        }

                        el.focus( function() {
                            if ( this.value === placeholder ) {
                                this.value = '';
                            }
                        } ).blur( function() {
                            if ( $.trim( this.value ) === '' ) {
                                this.value = placeholder;
                            }
                        } );
                    } );

                    // Clear default placeholder values on form submit
                    $( 'form' ).submit( function() {
                        $( this ).find( selector ).each( function() {
                            if ( this.value === $( this ).attr( 'placeholder' ) ) {
                                this.value = '';
                            }
                        } );
                    } );
                }
            },
            initTooltip: function() {
                $( '[data-bs-toggle=tooltip]' ).tooltip();
                $( '.toast' ).toast();
                $( '[data-bs-toggle="popover"]' ).popover( {container: 'body'} );
            },
            showBackToTop: function() {
                const offset = 300;
                const offset_opacity = 1200; //browser window scroll (in pixels)
                // after which the 'back to top' link
                // opacity is reduced

                if ( $window.scrollTop() > offset ) {
                    $back_to_top.addClass( 'cd-is-visible' );
                    $springoo_body.addClass( 'scrolled' );
                } else {
                    $back_to_top.removeClass( 'cd-is-visible cd-fade-out' );
                }

                if ( $window.scrollTop() > offset_opacity ) {
                    $back_to_top.addClass( 'cd-fade-out' );
                }
            },
            initBackToTop: function() {
                //smooth scroll to top
                $back_to_top.on( 'click', function( e ) {
                    e.preventDefault();
                    $( 'body,html' ).animate( {
                        scrollTop: 0,
                    }, 700 );
                } );
            },
            initTransparentHeader: function() {
                if ( $springoo_masthead.hasClass( 'transparent-header' ) ) {
                    const headerHeight = $springoo_masthead.outerHeight();
                    const bc = $( '#springoo-breadcrumb-area' );
                    if ( bc.length ) {

                        bc.css( {'padding-top': headerHeight * 2 + 'px'} );

                    } else {
                        //$('.site-content').css({'padding-top': headerHeight + 60 + 'px'});
                    }
                }
            },
            initSuperfish: function() {
                $( '.springoo-sf-menu' ).superfish();
            },
            initMobileMenu: function() {

                // $(window).on('keyup', function (e) {
                //     if ('Escape' === e.originalEvent.key) {
                //         //$('#navigation-mobile').addClass('open-mobile-menu')
                //         //$('.springoo-menu-overlay').toggleClass('active')
                //         if (springoo_has_admin_bar) {
                //             $('#navigation-mobile').css({
                //                 'top': $springoo_admin_bar.height() + 'px',
                //             })
                //         }
                //     }
                // })

                $( window ).on( 'click', function( e ) {
                    let mobileMenu = $( '#navigation-mobile' ),
                        mobileToggle = $( '#springoo-mobile-toggle' );

                    if ( !mobileMenu.is( e.target ) &&
                         mobileMenu.has( e.target ).length === 0 &&
                         !mobileToggle.is( e.target ) &&
                         mobileToggle.has( e.target ).length === 0 ) {
                        mobileMenu.removeClass( 'open-mobile-menu' );
                        $( '.springoo-menu-overlay' ).removeClass( 'active' );
                    }
                } );

                $( '#springoo-mobile-toggle' ).on( 'click', function( e ) {
                    e.preventDefault();
                    $( this ).toggleClass( 'cs-collapse' );
                    const mobileNav = $( '#navigation-mobile' );
                    mobileNav.addClass( 'open-mobile-menu' );
                    $( '.springoo-menu-overlay' ).toggleClass( 'active' );

                    if ( springoo_has_admin_bar ) {
                        mobileNav.css( {
                            '--admin-bar': $springoo_admin_bar.height() + 'px',
                        } );
                    }
                    $( 'body' ).addClass( 'mobile-nav-active' );
                } );

                $( '#navigation-mobile li:has(ul) > .springoo-dropdown-plus' ).on( 'click', function( e ) {
                    e.preventDefault();
                    $( this ).toggleClass( 'cs-times' );

                    if ( $( this ).find( 'i' ).hasClass( 'ai-arrow-down' ) ) {
                        $( this ).find( 'i' ).removeClass( 'ai-arrow-down' ).addClass( 'ai-arrow-up' );
                    } else {
                        $( this ).find( 'i' ).removeClass( 'ai-arrow-up' ).addClass( 'ai-arrow-down' );
                    }

                    $( this ).parent().find( '> ul' ).slideToggle( 500, 'easeInOutExpo' );
                } );

                $( '#navigation-mobile li:has(ul) > a:not(.springoo-dropdown-plus)' ).on( 'click', function( e ) {
                    if ( $( this ).attr( 'href' ) === '#' ) {
                        e.preventDefault();
                        const $parent = $( this ).parent();
                        $parent.find( '> .springoo-dropdown-plus' ).toggleClass( 'cs-times' );
                        // noinspection JSValidateTypes
                        $parent.find( '> ul' ).slideToggle( 500, 'easeInOutExpo' );
                    }
                } );

            },
            initVerticalMenu: function() {
                //menu click
                $( '#vertical-menu-toggle' ).on( 'click', function( e ) {
                    e.preventDefault();
                    $( '#vertical-nav' ).slideToggle( 250, 'easeInOutExpo' );
                } );

                //hide click outside
                $( document ).on( 'click', function( e ) {
                    const verticalMenuWrap = $( '.vertical-navigation-wrap' );
                    // if the target of the click isn't the verticalMenuWrap nor a descendant of the verticalMenuWrap
                    if ( !verticalMenuWrap.is( e.target ) && verticalMenuWrap.has( e.target ).length === 0 ) {
                        $( '#vertical-nav' ).slideUp( 250, 'easeInOutExpo' );
                    }
                } );

                //superfish
                //display sub menu and mega menu with superfish
            },
            closeMobileMenu: function() {

                $( '#springoo-mobile-close' ).on( 'click', function( e ) {
                    e.preventDefault();
                    $( '#springoo-mobile-toggle' ).removeClass( 'cs-collapse' );
                    $( '.springoo-menu-overlay' ).removeClass( 'active' );
                    $( '#navigation-mobile' ).removeClass( 'open-mobile-menu' );
                    $( 'body' ).removeClass( 'mobile-nav-active' );

                } );

                //if user change orientation after opening mobile nav in tab
                if (  window.innerWidth > parseInt( Options.viewport ) ){
                    $( '#springoo-mobile-toggle' ).removeClass( 'cs-collapse' );
                    $( '.springoo-menu-overlay' ).removeClass( 'active' );
                    $( '#navigation-mobile' ).removeClass( 'open-mobile-menu' );
                    $( 'body' ).removeClass( 'mobile-nav-active' );
                }

            },
            // mobile sidebar
            initMobileSidebar: function () {

                $(window).on('click', function (e) {
                    let mobileSidebar = $('#filter-toggle'),
                        mobileToggle = $('#springoo-filter-toggle')

                    if (!mobileSidebar.is(e.target) &&
                        mobileSidebar.has(e.target).length === 0 &&
                        !mobileToggle.is(e.target) &&
                        mobileToggle.has(e.target).length === 0) {
                        mobileSidebar.removeClass('open-filter-menu')
                        $('.springoo-filter-overlay').removeClass('active')
                    }
                })

                $('#springoo-filter-toggle').on('click', function (e) {
                    e.preventDefault();
                    $(this).toggleClass('cs-collapse');
                    const mobileNav = $('#filter-toggle');
                    mobileNav.addClass('open-filter-menu');
                    $('.springoo-filter-overlay').toggleClass('active');

                    if (springoo_has_admin_bar) {
                        mobileNav.css({
                            '--admin-bar': $springoo_admin_bar.height() + 'px',
                        });
                    }
                    $('body').addClass('filter-nav-active');
                })
            },
            closeMobileSidebar: function () {

                $('#springoo-filter-close').on('click', function (e) {
                    e.preventDefault()
                    $('#springoo-filter-toggle').removeClass('cs-collapse');
                    $('.springoo-filter-overlay').removeClass('active');
                    $('#filter-toggle').removeClass('open-filter-menu');
                    $('body').removeClass('filter-nav-active');

                })
            },
            springoo_is_small: function() {
                return (
                    window.innerWidth < parseInt( Options.viewport )
                );
            },
            initStickyHeader: function() {
                if ( springoo_is_sticky && !springoo_is_device ) {
                    let _header_top = this.getHeaderTop(), _header_height,
                        _scroll_top;
                    if ( springoo_has_admin_bar ) {
                        _header_top = parseInt(
                            '' + (
                                   this.getHeaderTop() - this.getAdminBarHeight()
                               ) );
                    }
                    $window.scroll( function() {
                        if ( !Springoo.springoo_is_small() ) {
                            _header_height = springoo_header_height;
                            _scroll_top = $( this ).scrollTop();
                            if ( _scroll_top > _header_top ) {
                                $springoo_masthead.trigger( 'close-modals' ).addClass( 'is-sticky' );
                                // match sticky menu width with box layout
                                if ( $springoo_hasBoxLayout ) {
                                    $springoo_masthead.css( 'width',
                                        $springoo_BoxWidth + 'px' );
                                }
                                $springoo_main.css( 'padding-top', _header_height );
                            } else {
                                $springoo_masthead.removeClass( 'is-sticky' );
                                $springoo_main.removeAttr( 'style' );
                            }
                            if ( _scroll_top > (
                                _header_height + _header_top
                            ) ) {
                                $springoo_masthead.addClass( 'is-compact' );
                            } else {
                                $springoo_masthead.removeClass( 'is-compact' );
                            }
                        }
                    } );
                }
            },
            initFixSticky: function() {
                if ( springoo_is_sticky && Springoo.springoo_is_small() ) {
                    $springoo_masthead.removeClass( 'is-sticky is-compact' );
                    $springoo_main.removeAttr( 'style' );
                } else {
                    $springoo_body.removeClass( 'cs-is-small' );
                }
            },
            responsiveEmbed: function() {
                $( 'iframe' ).each( function() {
                    if ( this.width && this.height ) {
                        // Calculate the proportion/ratio based on the width &
                        // height.
                        const proportion = parseFloat( this.width ) /
                                           parseFloat( this.height );
                        // Get the parent element's width.
                        const parentWidth = parseFloat(
                            window.getComputedStyle( this.parentElement, null ).width.replace( 'px', '' ) );
                        // Set the max-width & height.
                        this.style.maxWidth = '100%';
                        this.style.maxHeight = Math.round( parentWidth / proportion ).toString() + 'px';
                    }
                } );
            },
            onResize: function() {
                this.initTransparentHeader();
                this.responsiveEmbed();
                this.closeMobileMenu();
                // this.initSuperfish()
            },
            onScroll: function() {
                this.showBackToTop();
            },
            singleProductReviewsSort: function() {
                $( '#springoo_reviews_sort' ).on( 'change', function() {
                    const optionSelected = $( this ).find( 'option:selected' ).val();
                    //reset filter
                    $( '#springoo_reviews_filter' ).prop( 'selectedIndex', 0 );

                    let data = {
                        'action': 'springoo_sort_reviews',
                        'productID': Options.post_id,
                        'sort': optionSelected,
                        'nonce': Options.nonce,
                    };
                    $.post( {
                        url: Options.ajaxurl,
                        data: data,
                        context: this,
                        success: function( response ) {
                            $( '.commentlist' ).empty();
                            $( '.commentlist' ).append( response );
                        },
                        dataType: 'json',
                    } );
                } );
            },
            singleProductReviewsFilter: function() {
                $( '#springoo_reviews_filter' ).on( 'change', function() {
                    const optionSelected = $( this ).find( 'option:selected' ).val();
                    //reset sort
                    $( '#springoo_reviews_sort' ).prop( 'selectedIndex', 0 );

                    let data = {
                        'action': 'springoo_filter_reviews',
                        'productID': Options.post_id,
                        'filter': optionSelected,
                        'nonce': Options.nonce,
                    };
                    $.post( {
                        url: Options.ajaxurl,
                        data: data,
                        context: this,
                        success: function( response ) {
                            $( '.commentlist' ).empty();
                            $( '.commentlist' ).append( response );
                        },
                        dataType: 'json',
                    } );
                } );
            },
            singleProductReviewsFeedback: function() {
                const feedback = $( '.springoo-reviews-feedback' ).find( '.springoo-feedback' );
                feedback.on( 'click', function() {
                    const comment_id = $( this ).parent( '.springoo-reviews-feedback' ).attr( 'data-id' );
                    let data = {
                        'action': 'springoo_review_feedback',
                        'reviewID': comment_id,
                        'nonce': Options.nonce,
                    };
                    if ( $( this ).hasClass( 'feedback__yes' ) ) {
                        data['feedbackYes'] = 1;
                    } else {
                        data['feedbackNo'] = 1;
                    }

                    $.post( {
                        url: Options.ajaxurl,
                        data: data,
                        context: this,
                        success: function( response ) {
                            //if is array
                            if ( Array.isArray( response ) ) {
                                $( this ).parent( '.springoo-reviews-feedback' ).find( '.count-like' ).text( '(' + response[0].length + ')' );
                                $( this ).parent( '.springoo-reviews-feedback' ).find( '.count-unlike' ).text( '(' + response[1].length + ')' );
                            }
                        },
                        dataType: 'json',
                    } );
                } );
            },
            singleProductReviewsPopupGallery: function() {
                //.springoo-review-btn
                const commentForm = $( '#commentform' );
                if ( commentForm.length ) {
                    commentForm[0].encoding = 'multipart/form-data';
                }
                $( document ).on( 'show.bs.modal', '#review_dialog', function() {
                    import( 'filepond' ).then( ( {create, registerPlugin} ) => {
                        // Register the plugin with FilePond
                        registerPlugin( FilePondPluginFileValidateType );
                        registerPlugin( FilePondPluginImagePreview );
                        registerPlugin( FilePondPluginFileValidateSize );
                        create( document.querySelector( '#review-gallery' ), {
                            name: 'review_gallery',
                            storeAsFile: true,
                            allowSyncAcceptAttribute: true,
                            allowMultiple: true,
                            credits: null,
                            acceptedFileTypes: ['image/jpeg', 'image/jpg', 'image/png'],
                            labelIdle: [
                                '<span class="filepond--label-action"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28" fill="none">\n' +
                                '<path d="M15.75 5.97793V19.2506C15.75 20.2186 14.968 21.0006 14 21.0006C13.032 21.0006 12.25 20.2186 12.25 19.2506V5.97793L8.23594 9.99199C7.55234 10.6756 6.44219 10.6756 5.75859 9.99199C5.075 9.3084 5.075 8.19824 5.75859 7.51465L12.7586 0.514648C13.4422 -0.168945 14.5523 -0.168945 15.2359 0.514648L22.2359 7.51465C22.9195 8.19824 22.9195 9.3084 22.2359 9.99199C21.5523 10.6756 20.4422 10.6756 19.7586 9.99199L15.75 5.97793ZM3.5 19.2506H10.5C10.5 21.1811 12.0695 22.7506 14 22.7506C15.9305 22.7506 17.5 21.1811 17.5 19.2506H24.5C26.4305 19.2506 28 20.8201 28 22.7506V24.5006C28 26.4311 26.4305 28.0006 24.5 28.0006H3.5C1.56953 28.0006 0 26.4311 0 24.5006V22.7506C0 20.8201 1.56953 19.2506 3.5 19.2506ZM23.625 24.9381C24.3523 24.9381 24.9375 24.3529 24.9375 23.6256C24.9375 22.8982 24.3523 22.3131 23.625 22.3131C22.8977 22.3131 22.3125 22.8982 22.3125 23.6256C22.3125 24.3529 22.8977 24.9381 23.625 24.9381Z" fill="#10143E"/>\n' +
                                '</svg>' + Options.i18n.click_to_upload + '</span>',
                            ],
                            maxFiles: Options.review_image_number,
                            maxFileSize: Options.review_image_file_size + 'MB',
                            onwarning: () => alert( 'Maximum ' + Options.review_image_number + ' Images allowed' ),
                        } );
                    } ).catch( console.log );
                } );
            },
            initLightbox: function() {
                if ( $( '[data-lightbox]' ).length ) {
                    import( 'lightbox2' ).then( lightbox => {
                        lightbox.option( {
                            'resizeDuration': 200,
                            'wrapAround': true,
                        } );
                    } ).catch( console.log );
                }
            },
            headerWishlist: function() {
                const countWrapper = '.yith-count .count-badge';

                $springoo_body.on( 'added_to_wishlist', function() {
                    if ( $( countWrapper ).length > 0 ) {
                        let count = parseInt( $( countWrapper ).text() );
                        count = count + 1;
                        $( countWrapper ).text( count );
                    }
                } );
                $springoo_body.on( 'removed_from_wishlist', function() { /* eslint-disable */
                    if ( $( countWrapper ).length > 0 ) {
                        let count = parseInt( $( countWrapper ).text() );
                        count = count - 1;
                        if ( count < 0 ) {
                            count = 0;
                        }
                        $( countWrapper ).text( count );
                    }
                } );
            },
            productQuantity: function() {
                $( '.quantity input.qty' ).after( '<span class="qty-btn inc si si-thin-add"></span><span class="qty-btn dec si si-thin-minus"></span>' );

                $( document ).on( 'click', 'span.qty-btn', function( event ) {
                    const $target = $( event.target );
                    const $input = $target.parents( '.quantity' ).find( 'input[type="number"]' );
                    const max = Number( $input.attr( 'max' ) );
                    const min = Number( $input.attr( 'min' ) );
                    const step = Number( $input.attr( 'step' ) );
                    let value = Number( $input.val() );

                    if ( $target.hasClass( 'inc' ) ) {
                        value = value + step;
                        if ( max !== 0 && value > max ) {
                            return;
                        }
                        $input.val( value );
                        $input.trigger( 'change' );
                    }
                    if ( $target.hasClass( 'dec' ) ) {
                        value = value - step;
                        if ( value < min ) {
                            return;
                        }
                        $input.val( value );
                        $input.trigger( 'change' );
                    }
                } );

                $( document ).on( 'updated_wc_div', function() {
                    $( '.quantity input.qty' ).after( '<span class="qty-btn inc fi fi-thin-add"></span><span class="qty-btn dec fi fi-thin-minus"></span>' );
                } );
            },
            productSearch: function() {
                //click outside of search field
                $( document ).on( 'click', function( e ) {
                    if ( !$( e.target ).closest( '.search_result' ).length && !$( e.target ).closest( '.springoo-search-input' ).length ) {
                        $( '.search_result' ).hide( 'fast', function() {
                            //$('.search_result_inner' ).html('');
                            //$('.springoo-search-input' ).val('');
                            // if ( ! hasResults && ! keyword ) {}
                        } );
                    }
                } );

                $.fn.springooProductSearch = function() {
                    $( this ).each( function() {
                        const searchForm = $( this );
                        const uid = searchForm.data( 'uid' );
                        const searchEl = searchForm.find( '.springoo-search-input' );

                        const catElement = searchForm.find( '.product-category' );
                        const resultEl = searchForm.find( '.search_result' );
                        const resultWrap = searchForm.find( '.search_result_inner' );

                        const resultTemplate = wp.template( uid + '-search-result' );

                        let searchRequest, keyword, hasResults = false;

                        //on focus search field
                        searchEl.focus( () => {
                            // if ( searchEl.val().length > 2 ) {
                            //     getProducts();
                            // }
                            resultEl.show();
                        } );

                        //get products
                        const getProducts = _.debounce( function() {
                            resultEl.show();
                            keyword = searchEl.val();

                            if ( !keyword && !hasResults ) {
                                resultEl.hide();
                                return;
                            }

                            if ( keyword.length < 3 ) {
                                hasResults = false;
                                resultWrap.html( `<li class="disabled">${Options.i18n.min_3}</li>` );
                                return;
                            }

                            if ( hasResults ) {
                                resultWrap.find( 'li.disabled' ).remove();
                                resultWrap.find( 'ul' ).prepend( `<li class="disabled">${Options.i18n.wait}</li>` );
                            } else {
                                resultWrap.html( `<li class="disabled">${Options.i18n.wait}</li>` );
                            }

                            if ( searchRequest ) {
                                searchRequest.abort();
                            }

                            searchRequest = $.ajax( {
                                url: Options.ajaxurl,
                                type: 'GET',
                                data: {
                                    action: 'springoo_search_product',
                                    nonce: Options.nonce,
                                    s: keyword,
                                    cat: catElement.val(),
                                },
                                success: function( {success, data} ) {
                                    hasResults = success;
                                    resultWrap.empty();
                                    if ( success ) {
                                        let fiveItem = data.slice(0,5);
                                        for (const item of fiveItem) {
                                            resultWrap.append( resultTemplate( item ) );
                                        }
                                        let viewAllProduct = $('.view-all-products');
                                        if( viewAllProduct.length === 0 ){
                                            resultWrap.after('<button class="view-all-products">View All</button>');
                                        }
                                    } else {
                                        resultWrap.html( `<li class="disabled">${data}</li>` );
                                    }
                                },
                                error: function( error ) {
                                    if ( error.hasOwnProperty('statusText') && 'abort' === error.statusText ) {
                                        return;
                                    }

                                    resultWrap.html( `<li class="disabled">${Options.i18n.wrong}</li>` );
                                    hasResults = false;
                                },
                                done: () => {
                                    searchRequest = null;
                                },
                            } );
                        }, 200 );

                        //on keyup get products
                        searchEl.keyup( function( {keyCode} ) {
                            if ( 40 === keyCode ) {
                                resultWrap.find( 'a' ).first().focus();
                                return;
                            }
                            if ( keyCode === 27 ) {
                                resultEl.hide();
                            }
                            if ( keyCode < 65 || keyCode > 90 ) {
                                return;
                            }
                            getProducts();
                        } );

                        //on change product category get products
                        if ( 0 !== catElement.length ) {
                            catElement.on( 'change', getProducts );
                        }
                    } );
                };
                $( '.springoo-product-search' ).springooProductSearch();
            },
            productVariationChange: function() {
                $( '.variations_form .variations ul.variable-items-wrapper' ).each( function() {

                    const select = $( this ).prev( 'select' );
                    const li = $( this ).find( 'li' );
                    $( this ).on( 'click', 'li:not(.selected)', function() {
                        const value = $( this ).data( 'value' );
                        li.removeClass( 'selected' );
                        select.val( value ).trigger( 'change' );
                        $( this ).addClass( 'selected' );
                    } );

                    $( this ).on( 'click', 'li.selected', function() {
                        li.removeClass( 'selected' );
                        select.val( '' ).trigger( 'change' );
                        select.trigger( 'click' );
                        select.trigger( 'focusin' );
                        select.trigger( 'touchstart' );
                    } );
                } );
                $( document ).on( 'change', '.springoo-attributes input', function() {
                    $( '.springoo-attributes input:checked' ).each( function( index, element ) {
                        const $el = $( element );
                        $( 'select[name="' + $el.attr( 'name' ) + '"]' ).val( $el.attr( 'value' ) ).trigger( 'change' );
                    } );
                } );
                $( document ).on( 'woocommerce_update_variation_values', function() {
                    $( '.springoo-attributes input' ).each( function( index, element ) {
                        const $el = $( element );
                        $el.removeAttr( 'disabled' );
                        if ( $( 'select[name="' + $el.attr( 'name' ) + '"] option[value="' + $el.attr( 'value' ) + '"]' ).is( ':disabled' ) ) {
                            $el.prop( 'disabled', true );
                        }
                    } );
                } );
                $( '.variations_form' ).on( 'click', '.reset_variations', function() {
                    $( '.springoo-attributes input' ).removeAttr( 'checked' ).prop( 'checked', false );
                } );
            },
            saleCountdown: function() {
                $( '.springoo-sales-countdown' ).psgTimer( {
                    animation: false,
                } );
            },
            blogGrid: function() {
                // Grid masonary
                $( '.grid-container' ).masonry( {
                    itemSelector: '.post-grid-wrap',
                    gutter: 30,
                    percentPosition: true,
                } );
            },
            initSlickSlider: function() {
                $( '.springoo-slider-active' ).slick();
            },
            changeShopLayout: function() {
                const layout = $('.springoo_shop_views .springoo-change-layout');
                let url = document.location.href;
                const regex = new RegExp("columns=\\d+", 'g');

                //active layout
                if (url.indexOf('columns') >= 0) {
                    layout.removeClass('active');
                    let viewColumns = url.match(regex);
                    viewColumns = viewColumns.toString().match(/\d+/);
                    const selected = '.springoo-change-layout[data-columns|=' + viewColumns[0] + ']';
                    $(selected).addClass('active');
                }

                //change layout
                layout.on('click', function(e) {
                    //if already clicked
                    if (url.indexOf('columns') >= 0) {
                        url = url.replace(regex, 'columns=' + $(this).data('columns'));
                    } else {
                        if (url.indexOf('?') > -1) {
                            url = url + '&columns=' + $(this).data('columns');
                        } else {
                            url = url + '?columns=' + $(this).data('columns');
                        }
                    }
                    document.location = url;
                });
            },
            init: function() {

                const self = this;

                self.onResize = self.onResize.bind( self );
                self.onScroll = self.onScroll.bind( self );

                self.staticVariables();

                self.onResize();
                self.onScroll();

                self.initPreload();
                self.initTooltip();
                self.initBackToTop();
                self.initFormPlaceHolder();
                self.initSuperfish();
                self.initMobileMenu();
                self.initVerticalMenu();
                self.closeMobileMenu();
                self.initMobileSidebar()
                self.closeMobileSidebar()
                self.initStickyHeader();
                self.initFixSticky();
                self.initTransparentHeader();
                self.singleProductReviewsSort();
                self.singleProductReviewsFilter();
                self.singleProductReviewsFeedback();
                self.singleProductReviewsPopupGallery();
                self.initLightbox();
                self.headerWishlist();
                self.productQuantity();
                self.productSearch();
                self.productVariationChange();
                self.saleCountdown();
                self.blogGrid();
                self.initSlickSlider();
                self.changeShopLayout();
            },
        };

        $( document ).ready( function() {
            Springoo.init();
            $window.resize( Springoo.onResize ).resize();
            $window.scroll( Springoo.onScroll ).scroll();
            new Plyr( '.player' );
            /**
             * Destroy and Re-init superfish for windows resize
             */

            $( window ).on( 'resize', function() {
                $( '.springoo-sf-menu' ).superfish( 'destroy' );
                Springoo.initSuperfish();
            } );

            const modal = $( '.product-share-modal' );
            const modalBody = $( '.product-share-content' );
            const shareBtnTmpl = templateLoader( 'product-share' );

            $( document ).on( 'click', '.product-share', async ( e ) => {
                e.preventDefault();

                const self = $( e.currentTarget );

                if ( 'share' in navigator ) {
                    try {
                        await navigator.share( {
                            title: self.data( 'title' ) || '',
                            text: self.data( 'title' ) || '',
                            url: self.data( 'url' ),
                        } );
                    } catch ( error ) {
                    }
                } else {
                    modalBody.html( shareBtnTmpl( self.data() ) );
                    modal.modal( 'show' );
                }
            } );


            $( document ).on( 'click', '.share-btn', function( e ) {
                e.preventDefault();
                window.open( $( e.currentTarget ).data( 'url' ), '_blank', '' );
            } );


            $(document).on('click', '[data-copy]', function (e) {
                e.preventDefault();
                const target = $($(this).data('copy'));
                if (target.length) {
                    target.select();
                    document.execCommand('copy');
                }
            });

            $( '.variations_form' ).on( 'found_variation', function( e, data ) {

                if ( data.end_time_title.length ) {
                    $( '.countdown-timer' ).css( {
                        'border-top': '1px solid var(--springoo-color-border)',
                        'border-bottom': '1px solid var(--springoo-color-border)',
                        'margin-bottom': '20px',
                        'display': 'flex',
                    } ).addClass( 'has-border' );
                    $( '.countdown-timer__title' ).html( 'Limited time offer. The deal will expire on <span class="sale-date"><strong>' + data.end_time_title + '</strong></span> HURRY UP!' );
                } else {
                    $( '.countdown-timer' ).css( {
                        'border-top': 'none',
                        'border-bottom': 'none',
                        'display': 'none',
                    } ).removeClass( 'has-border' );
                    $( '.countdown-timer__title' ).html( ' ' );
                }

                if ( data.end_time_countdown.length ) {
                    $( '.springoo-sales-countdown' ).psgTimer( 'stop' );
                    $( '.countdown-timer__countdown' ).html( '<div class="springoo-sales-countdown" data-timer-end="' + data.end_time_countdown + '" data-label-placement="after" data-label-days="d" data-label-hours="h" data-label-minutes="m" data-label-seconds="s"></div>' );
                    $( '.springoo-sales-countdown' ).psgTimer( {animation: false} );
                } else {
                    $( '.countdown-timer__countdown' ).html( ' ' );
                }

                /**
                 * WooCommerce Variation Product stock Count
                 */
                if ( ! data.is_variable_manage_stock ) {
                    let stockCountWrap = $('.springoo-stock-count-wrap');
                    if ( data.is_manage_stock ) {

                        // Add Total stock content
                        $( stockCountWrap ).html('');
                        $( stockCountWrap ).append( '<div class="springoo-stock-count-content">Only <span class="stock-number">' + data.total_stock + ' ' + ( data.total_stock > 1 ? 'items' : 'item' ) + '</span> left in stock!</div>' );

                        // Check initial value exists and add progress bar
                        if ( data.initial_stock > 0 ) {
                            let percentage =  Math.round( data.total_stock / ( data.initial_stock + data.total_stock ) * 100 );
                            $( stockCountWrap ).append( '<div class="springoo-stock-count-progress"><div class="progress"><div class="progress-bar" id="springoo-progress-bar" role="progressbar" style="width: 100%" aria-valuemin="0" aria-valuemax="100"></div></div></div>' );
                            $("#springoo-progress-bar").animate({
                                width: percentage + '%',
                            }, 2000);
                        }
                    } else {
                        $( stockCountWrap ).html('')
                    }
                }

                if ( data.discount ) {
                    let discountWrap = $( '#product-'+ data.product_id + ' .product-summery-wrap .product-discount' );
                    $( discountWrap ).html('' );
                    $( discountWrap ).html( data.discount );
                }else{
                    let discountWrap = $( '#product-'+ data.product_id + ' .product-summery-wrap .product-discount' );
                    $( discountWrap ).html('' );
                }

            } );

            $( document ).on( 'click', '#respond #submit', function() {
                let $text_area = $( this ).closest( '#respond' ).find( '#comment' ),
                    text_area = $text_area.val();

                if ( $text_area.length > 0 && !text_area ) {
                    window.alert( 'Please write a review' );
                    return false;
                }
            } );

        } );

        $(".springoo-mini-search-icon").click(function () {
            // Show search box on click search icon
            $(".springoo-search-wrap").addClass('show-search');
            $('body').append('<div  class="springoo-transparent overlay"></div>');
            setTimeout(function () {
                $('.springoo-transparent').click(function () {
                    $(".springoo-search-wrap").removeClass('show-search');
                    this.remove();
                });
            }, 1000);
        });

        $('.springoo-close-search').click(function () {
            //SearchBox close
            $(".springoo-search-wrap").removeClass('show-search');
            $(".springoo-transparent.overlay").remove();
        });

        $("body").on("click", "", function () {
            let searchForm = $(this).parents("form");
            $(searchForm).length && ($(searchForm).find('[type="submit"][name="post_type"]').length ? $(searchForm).find('[type="submit"][name="post_type"]').trigger("click") : $(searchForm).trigger("submit"))
        });

        $(document).keyup(function (e) {
            // Ref https://stackoverflow.com/questions/3369593/how-to-detect-escape-key-press-with-pure-js-or-jquery
            // Close search if esc key pressed
            if (e.key === "Escape") {
                $(".springoo-search-wrap").removeClass('show-search');
                $(".springoo-transparent.bg-black").remove();
                $(".springoo-transparent.overlay").remove();
            }
        });

        //Dokan Ajax fliter
        jQuery(document).ready(function ($) {

            $('input[name="dokan_seller_search"]').on('input', function () {
                let searchTerm = $(this).val();
                let featureItem = ($('#featured').is(":checked")) ? 1 : 0;
                let oneNow = ($('#open-now').is(":checked")) ? 1 : 0;
                let rating = $('.dokan-stars i.active').data('rating');
                let loading = $('.store-wrapper-preloader').hide()
                let preloaderWrap = $('.springoo-seller-wrap-preloader');
                let preloaderWrapLi = $('.springoo-seller-wrap-preloader li');
                let content = $('.store-wrapper').show();

                $(document).ajaxStart(function () {
                    preloaderWrap.css({"display":"block","height":"auto","visibility":"visible"});
                    preloaderWrapLi.css("margin-bottom","var(--springoo-30)");
                    loading.show();
                    content.hide();
                })
                $(document).ajaxStop(function () {
                    preloaderWrap.css({"display":"block","height":"0","visibility":"hidden"});
                    preloaderWrapLi.css("margin-bottom","0");
                    loading.hide();
                    content.show();
                });

                $.ajax({
                    url: Options.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'springoo_dokan_vendor_list', // This is the PHP action hook
                        nonce: Options.nonce,
                        search: searchTerm,
                        featured: featureItem,
                        open_now: oneNow,
                        rating: rating,
                    },
                    success: function (response) {
                        $('#springoo-dokan-seller-listing').html(response); // Update a container with search results
                    }
                });
            });

            $('input[name="featured"], input[name="open_now"]').on('change', function () {
                let searchTerm = ($('input[name="dokan_seller_search"]')).val();
                let featureItem = ($('#featured').is(':checked')) ? 1 : 0;
                let oneNow = ($('#open-now').is(":checked")) ? 1 : 0;
                let rating = $('.dokan-stars.selected i.active').data('rating');
                let loading = $('.store-wrapper-preloader').hide()
                let preloaderWrap = $('.springoo-seller-wrap-preloader');
                let content = $('.store-wrapper').show();
                let preloaderWrapLi = $('.springoo-seller-wrap-preloader li');

                $(document).ajaxStart(function () {
                    preloaderWrap.css({"display":"block","height":"auto","visibility":"visible"});
                    preloaderWrapLi.css("margin-bottom","var(--springoo-30)");
                    loading.show();
                    content.hide();
                })
                $(document).ajaxStop(function () {
                    preloaderWrap.css({"display":"block","height":"0","visibility":"hidden"});
                    preloaderWrapLi.css("margin-bottom","0");
                    loading.hide();
                    content.show();
                });

                $.ajax({
                    url: Options.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'springoo_dokan_vendor_list', // This is the PHP action hook
                        nonce: Options.nonce,
                        search: searchTerm,
                        featured: featureItem,
                        open_now: oneNow,
                        rating: rating,
                    },
                    success: function (response) {
                        $('#springoo-dokan-seller-listing').html(response); // Update a container with search results
                    }
                });

            });

            $('.dokan-stars i').on('click', function () {
                let searchTerm = ($('input[name="dokan_seller_search"]')).val();
                let featureItem = ($('#featured').is(':checked')) ? 1 : 0;
                let oneNow = ($('#open-now').is(":checked")) ? 1 : 0;
                let rating = $('.dokan-stars i.active').data('rating');
                let loading = $('.store-wrapper-preloader').hide()
                let content = $('.store-wrapper').show()
                let preloaderWrap = $('.springoo-seller-wrap-preloader');
                let preloaderWrapLi = $('.springoo-seller-wrap-preloader li');

                $(document).ajaxStart(function () {
                    preloaderWrap.css({"display":"block","height":"auto","visibility":"visible"});
                    preloaderWrapLi.css("margin-bottom","var(--springoo-30)");
                    loading.show();
                    content.hide();
                })
                $(document).ajaxStop(function () {
                    preloaderWrap.css({"display":"block","height":"0","visibility":"hidden"});
                    preloaderWrapLi.css("margin-bottom","0");
                    loading.hide();
                    content.show();
                });

                $.ajax({
                    url: Options.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'springoo_dokan_vendor_list', // This is the PHP action hook
                        nonce: Options.nonce,
                        search: searchTerm,
                        featured: featureItem,
                        open_now: oneNow,
                        rating: rating,
                    },
                    success: function (response) {
                        $('#springoo-dokan-seller-listing').html(response); // Update a container with search results
                    }
                });

            });

        });


    }( jQuery, window, document, springoo_ajax, wp.template )
);
