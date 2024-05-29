/* global springooOptions */

( function ($, document, opts ) {
	$(document).ready(function () {

		const $document = $(document);
		var __          = wp.i18n.__,
			_x          = wp.i18n._x,
			sprintf     = wp.i18n.sprintf;

		$document.on('click', '.install-now', function (event) {
			event.preventDefault();
			const $button = $( this );
			if ( $button.hasClass( 'updating-message' ) || $button.hasClass( 'button-disabled' ) ) {
				return;
			}
			const slug = $button.data('slug');

			if ( wp.updates.shouldRequestFilesystemCredentials && ! wp.updates.ajaxLocked ) {
				wp.updates.requestFilesystemCredentials( event );
			}

			wp.updates.installPlugin(
				{
					slug,
					clear_destination: true ,
				}
			);
		} );

		$document.on('click', '.activate-now', function (event) {
			event.preventDefault();
			const $button 	= $( this );
			let $parentWrap = $button.closest('.springoo-options-plugin-card__wrap');

			if ( $button.hasClass( 'updating-message' ) || $button.hasClass( 'button-disabled' ) ) {
				return;
			}

			$button
				.addClass( 'updating-message' )
				.attr(
					'aria-label',
					sprintf(
						/* translators: %s: Plugin name and version. */
						_x( 'Activating %s...', 'plugin' ),
						$button.data( 'name' )
					)
				)
				.text( __( 'Activating...' ) );

			wp.a11y.speak( __( 'Activating... please wait.' ) );

			const mainfile = $button.data('mainfile');

			wp.ajax.post( 'springoo_activate_plugin', { nonce: opts.nonce, mainfile }).then( () => {
				$button.removeClass( 'updating-message' );
				$button.removeClass('activate-now')
				$button.removeClass('primary-button')
				$button.addClass('deactivate-now')
				$button
					.attr(
						'aria-label',
						sprintf(
							/* translators: %s: Plugin name and version. */
							_x( 'Deactivate %s...', 'plugin' ),
							$button.data( 'name' )
						)
					)
					.text( __( 'Deactivate' ) );
				$parentWrap.addClass('plugin-activated');

			}).fail( e => {
				$button.addClass( 'button-disabled' );
				console.log( { e } );
			});
		});

		$document.on('click', '.deactivate-now', function (event) {
			event.preventDefault();
			const $button 	= $( this );
			let $parentWrap = $button.closest('.springoo-options-plugin-card__wrap');

			if ( $button.hasClass( 'updating-message' ) || $button.hasClass( 'button-disabled' ) ) {
				return;
			}

			$button
				.addClass('updating-message')
				.attr(
					'aria-label',
					sprintf(
						/* translators: %s: Plugin name and version. */
						_x( 'Deactivating %s...', 'plugin' ),
						$button.data( 'name' )
					)
				)
				.text( __( 'Deactivating...' ) );

			wp.a11y.speak( __( 'Deactivating... please wait.' ) );

			const mainfile = $button.data('mainfile');

			wp.ajax.post( 'springoo_deactivate_plugin', { nonce: opts.nonce, mainfile }).then( () => {
				$button
					.removeClass('updating-message')
					.removeClass('deactivate-now')
					.addClass('activate-now')
					.attr(
						'aria-label',
						sprintf(
							/* translators: %s: Plugin name and version. */
							_x( 'Activate %s...', 'plugin' ),
							$button.data( 'name' )
						)
					)
					.text( __( 'Activate' ) );
				$parentWrap.removeClass('plugin-activated');
			}).fail( e => {
				$button.addClass( 'button-disabled' );
				console.log( { e } );
			});
		});

		/**
		 * Nav tab
		 */
		const dashboard_prefix = '.springoo-options';
		const hash             = window.location.hash || '#recommended-plugins';
		const setHash          = hash => window.location.hash = hash;
		const Tabs             = $( dashboard_prefix + '-tabs');
		const TabNavs          = Tabs.find( dashboard_prefix + '-tabs__nav');
		const TabContents      = Tabs.find( dashboard_prefix + '-tabs__content');

		const viewTab            = hash => {
			const currentTabLink = $('a[href="' + hash + '"]');
			const currentTabId   = '#' + hash.substring(1);
			const currentTab     = TabContents.find( currentTabId );

			currentTabLink.addClass('tab--is-active').siblings().removeClass('tab--is-active').blur();

			currentTab.addClass('tab--is-active').siblings().removeClass('tab--is-active');
		};

		TabNavs.on('click', dashboard_prefix + '-tabs__nav-item', function (e) {

			const currentTabLink = $(e.currentTarget);
			const currentHash    = e.currentTarget.hash;

			if ( currentTabLink.is( '.nav-item-is--link' ) ) {
				return true;
			}

			e.preventDefault();

			setHash( currentHash );
		});

		if ( hash ) {
			if ( ! window.location.hash ) {
				setHash( hash );
			} else {
				// if url already had a hash then the event doesn't fire.
				TabNavs.find('a[href="' + hash + '"]').click();
				viewTab( hash );
			}
		}

		$( window ).on( 'hashchange', function ( e ) {
			e.preventDefault();
			viewTab( window.location.hash );
		} );

	});
} )(jQuery, document, springooOptions || { admin_ajax: '', nonce: '' } );
