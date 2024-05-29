"use strict";
/**!
 * Attribute Filter
 * @author Ayyash Addons
 *
 */!function(n,s,a){n(s).on("elementor/frontend/init",(function(){elementorFrontend.hooks.addAction("frontend/element_ready/ayyash-woocommerce-sales-products.default",(function(s){n(".sales-product-countdown",s).each((function(){var s=n(this),a=s.data("end-date");s.countdown(a,(function(n){s.html(n.strftime('<span class="countdown-days">%D </span> <span class="separator"> : </span><span class="countdown-hours">%H </span> <span class="separator"> : </span><span class="countdown-min">%M </span> <span class="separator"> : </span><span class="countdown-sec">%S </span>'))}))}))}))}))}(jQuery,window,window.elementorFrontend);