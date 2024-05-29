"use strict";
/**!
 * ayyash addons woocommerce hotspots
 * @author Ayyash Addons
 *
 */!function(o,t,n){o(t).on("elementor/frontend/init",(function(){elementorFrontend.hooks.addAction("frontend/element_ready/ayyash-woocommerce-product-hotspots.default",(function(o,t){t(".ayyash-addons-product-hotspot__content",o).each((function(){var o=t(this),n=o.find(".ayyash-addons-product-hotspot-icon");o.parents(".ayyash-addons-product-hotspot-wrapper").hasClass("hotspot-action-click")&&(n.on("click",(function(){return o.hasClass("hotspot-opened")?o.removeClass("hotspot-opened"):(o.addClass("hotspot-opened"),o.siblings().removeClass("hotspot-opened")),!1})),t(document).on("click",(function(n){var s=n.target;if(o.hasClass("hotspot-opened")&&!t(s).is(".ayyash-addons-product-hotspot__content")&&!t(s).parents().is(".ayyash-addons-product-hotspot__content"))return o.removeClass("hotspot-opened"),!1})))}))}))}))}(jQuery,window,window.elementorFrontend);