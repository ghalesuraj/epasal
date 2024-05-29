"use strict";
/**!
 * CountDown Public Scripts
 *
 * @author Ayyash Addons
 * @version 1.0.0
 */!function(n,e,o){n(e).on("elementor/frontend/init",(function(){elementorFrontend.hooks.addAction("frontend/element_ready/ayyash-heading.default",(function(e){n(".ayyash-addons-countdown",e).psgTimer({animation:!1})}))}))}(jQuery,window,window.elementorFrontend);