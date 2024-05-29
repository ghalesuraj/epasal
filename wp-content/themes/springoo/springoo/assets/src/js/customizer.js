/* global springooCustomizerFontsL10n */

import '../scss/customizer.scss';

// noinspection JSUnusedGlobalSymbols,JSUnresolvedVariable

(function ($, document, springooCustomizerFontsL10n) {

	const wpApi = wp.customize;

	const settingsLink = 'data-customize-setting-link';

	const fontChosen = {
		cache: {},

		init: function () {
			fontChosen.buildFonts();
			fontChosen.showFonts();
		},

		buildFonts: function () {
			fontChosen.cache.fonts  = '';
			fontChosen.cache.chosen = {};

			$.each(springooCustomizerFontsL10n, function (name, options) {
				var disabled = '';
				if (options['disabled'] !== undefined) {
					disabled = ' disabled="disabled" ';
				}
				fontChosen.cache.fonts += '<option value="' + name + '"' + disabled + '>' + name + '</option>';
			});
		},

		populateVariants: function ( $el, font ) {
			if ($el.length > 0 && springooCustomizerFontsL10n[font] !== undefined) {
				$el = $el.find('select');
				$el.html(fontChosen.showVariants(springooCustomizerFontsL10n[font]['variants'])).children('option:first').attr('selected','selected');
				$el.trigger('change');
			}
		},

		showFonts: function () {
			$(".chosen-select").each(function () {
				const $el                    = $(this);
				const key                    = $el.attr( settingsLink );
				fontChosen.cache.chosen[key] = $(this);
				wpApi(key, function (setting) {
					$el.on('chosen:ready', function () {
						const v = setting.get();
						$(this).html(fontChosen.cache.fonts).val(v);
						setTimeout(function () {
							$el.trigger('chosen:updated');
						}, 200);
					});
					$el.on('change', function () {
						const $select = $(this);
						const font    = $select.val();
						const $varId  = '#' + $select.closest('li').attr( 'id' ).replace('family', '' );

						fontChosen.populateVariants( $($varId + 'variant'), font );
					});
					$el.chosen({
						search_contains: true,
						width: '100%'
					});
				});
			});
			fontChosen.showDefaultVariants();
		},

		showDefaultVariants: function () {
			$('[id$=_variant] select').each(function () {
				const $el       = $(this);
				const key       = $el.attr(settingsLink);
				const parentKey = key.replace('_variant', '_family');
				wpApi(key, function (setting) {
					if (fontChosen.cache.chosen[parentKey] !== undefined && fontChosen.cache.chosen[parentKey].length > 0) {
						const val = fontChosen.cache.chosen[parentKey].val();
						if ( val ) {
							$el.html(fontChosen.showVariants(springooCustomizerFontsL10n[val]['variants'])).val(setting.get());
						}
					}
				});

			});
		},

		showVariants: function (variants) {
			let options = '';
			options    += '<option value="default">Default</option>';
			$.each(variants, function (ind, val) {
				const name = val.replace('italic', ' Italic').replace(/\w\S*/g, txt => txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase() );
				options   += '<option value="' + val + '">' + name + '</option>';
			});
			return options;
		},

	};

	const multiCheckboxes = {
		init: function () {
			/* === Checkbox Multiple Control === */
			$( '.customize-control-checkbox-multiple input[type="checkbox"]' ).on( 'change', function () {
					const checkbox_values = $( this ).parents( '.customize-control' ).find( 'input[type="checkbox"]:checked' )
					.map( function () {
							return this.value;
					} ).get().join( ',' );
					$( this ).parents( '.customize-control' ).find( 'input[type="hidden"]' ).val( checkbox_values ).trigger( 'change' );
			}
			);
		}
	}

	$(document).ready(function () {
		multiCheckboxes.init();
		fontChosen.init();
		$('.customize-control input[type="range"]')
		.on('change input', function () {
			const name = $(this).attr('name');
			$('input[type="text"][data-name="' + name + '"]').val( $(this).val() );
		})
		.trigger('change');

        // Update the values for all our input fields and initialise the sortable repeater
        $('.social_profiles').each(function () {
            const profiles         = $(this);
            const controlId        = profiles.data('control-id');
            const add              = '.' + controlId + 'profile-add';
            const remove           = '.' + controlId + 'profile-remove';
            const move             = '.' + controlId + 'profile-move';
            const profileClassName = '.social-profile';
            const wrapper          = profiles.find('.social_profiles_wrap');
            const template         = wp.template( controlId + '-social-profile' );
            const valueEl          = profiles.find( '#' + controlId );
            let triggerTimeout;
            const updateValues = ( now ) => {
                now            = now || false;
                let data       = [];
                if ( triggerTimeout ) {
                    clearTimeout( triggerTimeout );
                    triggerTimeout = null;
                }
                profiles.find( profileClassName ).each( function () {
                    const profile = $(this);
                    data.push( {
                        label: profile.find('input.label').val(),
                        url: profile.find('input.url').val(),
                        icon: profile.find('input.icon').val(),
                    } );
                } );
                valueEl.val( JSON.stringify( data ) );
                if ( now ) {
                    valueEl.trigger( 'change' );
                    return;
                }
                triggerTimeout   = setTimeout( () => valueEl.trigger('change'), 1500 );
            };
            const addProfile     = ( idx, data ) => wrapper.append( template( { index: idx, label: '', url: '', icon: '', ...data } ) );
            const renderProfiles = () => {
                try {
                    wrapper.empty();
                    $.each( JSON.parse( valueEl.val() ), addProfile );
                } catch ( e ) {
                    console.log(e);
                }
            };

            const sortableOptions = {
                handle: move,
                cancel: 'input',
                connectWith: '.' + controlId + '_social_profiles',
                items: '> ' + profileClassName,
                update: () => {
                    wrapper.find( profileClassName ).each( function ( idx ) {
                        const title = $(this).find('.field-title h3');
                        title.text( title.text().replace( /([\d]+)/g, idx + 1 ) );
                    } );
                    updateValues( true );
                },
            };

            renderProfiles();
            wrapper.sortable( sortableOptions );
            $(document).on( 'click', add, function ( e ) {
                e.preventDefault();
                addProfile( wrapper.find( profileClassName ).length, {} );
                wrapper.sortable( 'refresh' );
            } );
            $(document).on( 'click', remove, function ( e ) {
                e.preventDefault();
                const el = $(this).closest( profileClassName );
                el.slideUp( 100, 'linear', () => {
                    el.remove();
                    wrapper.sortable( 'refresh' );
                    updateValues( true );
                    renderProfiles();
                } );
            } );
            $(document).on( 'change', '.' + controlId + '-trigger', updateValues );
        });
	});

})(jQuery, document, springooCustomizerFontsL10n);
