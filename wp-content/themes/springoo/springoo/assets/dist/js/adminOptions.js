!function(a,t,i){a(t).ready((function(){var n=a(t),e=wp.i18n.__,s=wp.i18n._x,o=wp.i18n.sprintf;n.on("click",".install-now",(function(t){t.preventDefault();var i=a(this);if(!i.hasClass("updating-message")&&!i.hasClass("button-disabled")){var n=i.data("slug");wp.updates.shouldRequestFilesystemCredentials&&!wp.updates.ajaxLocked&&wp.updates.requestFilesystemCredentials(t),wp.updates.installPlugin({slug:n,clear_destination:!0})}})),n.on("click",".activate-now",(function(t){t.preventDefault();var n=a(this),l=n.closest(".springoo-options-plugin-card__wrap");if(!n.hasClass("updating-message")&&!n.hasClass("button-disabled")){n.addClass("updating-message").attr("aria-label",o(s("Activating %s...","plugin"),n.data("name"))).text(e("Activating...")),wp.a11y.speak(e("Activating... please wait."));var d=n.data("mainfile");wp.ajax.post("springoo_activate_plugin",{nonce:i.nonce,mainfile:d}).then((function(){n.removeClass("updating-message"),n.removeClass("activate-now"),n.removeClass("primary-button"),n.addClass("deactivate-now"),n.attr("aria-label",o(s("Deactivate %s...","plugin"),n.data("name"))).text(e("Deactivate")),l.addClass("plugin-activated")})).fail((function(a){n.addClass("button-disabled"),console.log({e:a})}))}})),n.on("click",".deactivate-now",(function(t){t.preventDefault();var n=a(this),l=n.closest(".springoo-options-plugin-card__wrap");if(!n.hasClass("updating-message")&&!n.hasClass("button-disabled")){n.addClass("updating-message").attr("aria-label",o(s("Deactivating %s...","plugin"),n.data("name"))).text(e("Deactivating...")),wp.a11y.speak(e("Deactivating... please wait."));var d=n.data("mainfile");wp.ajax.post("springoo_deactivate_plugin",{nonce:i.nonce,mainfile:d}).then((function(){n.removeClass("updating-message").removeClass("deactivate-now").addClass("activate-now").attr("aria-label",o(s("Activate %s...","plugin"),n.data("name"))).text(e("Activate")),l.removeClass("plugin-activated")})).fail((function(a){n.addClass("button-disabled"),console.log({e:a})}))}}));var l=window.location.hash||"#recommended-plugins",d=function(a){return window.location.hash=a},c=a(".springoo-options-tabs"),r=c.find(".springoo-options-tabs__nav"),p=c.find(".springoo-options-tabs__content"),u=function(t){var i=a('a[href="'+t+'"]'),n="#"+t.substring(1),e=p.find(n);i.addClass("tab--is-active").siblings().removeClass("tab--is-active").blur(),e.addClass("tab--is-active").siblings().removeClass("tab--is-active")};r.on("click",".springoo-options-tabs__nav-item",(function(t){var i=a(t.currentTarget),n=t.currentTarget.hash;if(i.is(".nav-item-is--link"))return!0;t.preventDefault(),d(n)})),l&&(window.location.hash?(r.find('a[href="'+l+'"]').click(),u(l)):d(l)),a(window).on("hashchange",(function(a){a.preventDefault(),u(window.location.hash)}))}))}(jQuery,document,springooOptions||{admin_ajax:"",nonce:""});