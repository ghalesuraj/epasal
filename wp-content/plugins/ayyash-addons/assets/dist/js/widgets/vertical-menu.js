"use strict";!function(e,a){e(a).on("elementor/frontend/init",(function(){elementorFrontend.hooks.addAction("frontend/element_ready/ayyash-vertical-menu.default",(function(a){e("#vertical-menu-toggle",a).on("click",(function(s){s.preventDefault(),e(this).toggleClass("cs-collapse"),e("#vertical-nav",a).slideToggle(500,"easeInOutExpo")}));e(".vertical-nav",a).superfish({delay:200,speed:"fast",speedOut:"fast",cssArrows:!1,onBeforeShow:function(){var a=this,s=e(".elementor-widget-ayyash-vertical-menu").closest(".elementor-container").width(),t=e(".vertical-nav").width();a.parent().hasClass("ayyash-mega-menu")&&a.css({display:"flex"}),!a.parent().hasClass("ayyash-mega-menu")||a.parent().hasClass("ayyash-natural")||a.parent().hasClass("ayyash-custom")||a.css({width:s-t+"px"})}})}))}))}(jQuery,window);