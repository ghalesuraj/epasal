"use strict";
/**!
 * Wishlist Public Scripts
 *
 * @author Ayyash Addons
 * @version 1.0.0
 */!function(t,n,e){t(n).on("elementor/frontend/init",(function(){elementorFrontend.hooks.addAction("frontend/element_ready/ayyash-wishlist.default",(function(n){var e=n.find(".ayyash-wishlist-icon-wrapper .items-count");t("body").on("added_to_wishlist",(function(n,o){if(t(e).length>0){var i=parseInt(t(e).text());i+=1,t(e).text(i)}})),t("body").on("removed_from_wishlist",(function(n,o){if(t(e).length>0){var i=parseInt(t(e).text());(i-=1)<0&&(i=0),t(e).text(i)}}))}))}))}(jQuery,window,window.elementorFrontend);