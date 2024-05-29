"use strict";
/**!
 * Portfolio Public Scripts
 *
 * @author Themerox
 * @package Ayyash Addons
 */!function(t){t(window).on("elementor/frontend/init",(function(){elementorFrontend.hooks.addAction("frontend/element_ready/ayyash-addons-portfolio-grid.default",(function(i){var e=i.find(".filter-container").isotope({itemSelector:".filter-item",percentPosition:!0,layoutMode:"masonry"}),o=i.find(".filters-group");o.on("click","button",(function(){var i=t(this).attr("data-filter");"all"===i?e.isotope({filter:"*"}):e.isotope({filter:"."+i})})),o.each((function(i,e){var o=t(e);o.on("click","button",(function(){o.find(".is-active").removeClass("is-active"),t(this).addClass("is-active")}))}))}))}))}(jQuery);