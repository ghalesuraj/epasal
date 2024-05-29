(()=>{function t(e){return t="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},t(e)}!function(e,s){"use strict";var o=e(s).width();e(s).resize((function(){o=e(s).width()}));var i,n,r,a,l,h,f,c,p,u,d,v,m,y,w,C=(n="sf-breadcrumb",r="sf-js-enabled","sf-with-ul",a=function(){var t=/^(?![\w\W]*Windows Phone)[\w\W]*(iPhone|iPad|iPod)/i.test(navigator.userAgent);return t&&e("html").css("cursor","pointer").on("click",e.noop),t}(),l="behavior"in(i=document.documentElement.style)&&"fill"in i&&/iemobile/i.test(navigator.userAgent),h=!!s.PointerEvent,f=function(t,e,s){t[s?"addClass":"removeClass"]("sf-js-enabled")},c=function(t,e){var s=e?"addClass":"removeClass";t.children("a")[s]("sf-with-ul")},p=function(t){var e=t.css("ms-touch-action"),s=t.css("touch-action");s="pan-y"===(s=s||e)?"auto":"pan-y",t.css({"ms-touch-action":s,"touch-action":s})},u=function(t){return t.closest("."+r)},d=function(t){return u(t).data("sfOptions")},v=function(){var t=e(this),s=d(t);clearTimeout(s.sfTimer),t.siblings().superfish("hide").end().superfish("show")},m=function(t){t.retainPath=e.inArray(this[0],t.$path)>-1,this.superfish("hide"),this.parents("."+t.hoverClass).length||(t.onIdle.call(u(this)),t.$path.length&&e.proxy(v,t.$path)())},y=function(){var t=e(this),s=d(t);a?e.proxy(m,t,s)():(clearTimeout(s.sfTimer),s.sfTimer=setTimeout(e.proxy(m,t,s),s.delay))},w=function(t){var s=e(this),o=d(s),i=s.siblings(t.data.popUpSelector);if(!1===o.onHandleTouch.call(i))return this;i.length>0&&i.is(":hidden")&&(s.one("click.superfish",!1),"MSPointerDown"===t.type||"pointerdown"===t.type?s.trigger("focus"):e.proxy(v,s.parent("li"))())},{hide:function(t){if(this.length){var s=d(this);if(!s)return this;var o=!0===s.retainPath?s.$path:"",i=this.find("li."+s.hoverClass).add(this).not(o).removeClass(s.hoverClass).children(s.popUpSelector),n=s.speedOut;if(t&&(i.show(),n=0),s.retainPath=!1,!1===s.onBeforeHide.call(i))return this;i.stop(!0,!0).animate(s.animationOut,n,(function(){var t=e(this);s.onHide.call(t)}))}return this},show:function(){var t=d(this);if(!t)return this;var s=this.addClass(t.hoverClass).children(t.popUpSelector);s.css({opacity:0,visibility:"hidden",display:"block"});var i=s.closest("li");if(i.length){var n=i.hasClass("depth-0");if(n&&parseInt(s.css("width"))>o&&(s.css("width",""),i.removeClass("width-custom")),n&&parseInt(s.css("width"))>o-i.offset().left&&parseInt(s.css("width"))>i.offset().left+i.width()&&(s.css("width",""),i.removeClass("width-custom")),i.hasClass("mega-menu"))if(s.closest(".vertical-navigation").length){var r=s,a=e(".vertical-navigation-wrap").closest(".container").width(),l=e(".vertical-nav").width();i.hasClass("mega-menu")&&i.hasClass("width-full")&&r.css({width:a-l+"px"})}else s.offset().left+s.outerWidth()>o&&s.css({left:"auto",right:"0"}),s.offset().left<0&&s.css({left:"0",right:"auto"});else{var h=s.offset().left;h+s.outerWidth()>o&&(n?s.css({left:"auto",right:"0"}):s.css({left:"auto",right:"100%"})),h<0&&(n?s.css({left:"0",right:"auto"}):s.css({left:"100%",right:"auto"}))}return s.css("opacity",""),s.css("visibility",""),s.css("display","none"),!1===t.onBeforeShow.call(s)||s.stop(!0,!0).animate(t.animation,t.speed,(function(){t.onShow.call(s)})),this}},destroy:function(){return this.each((function(){var t,s=e(this),o=s.data("sfOptions");if(!o)return!1;t=s.find(o.popUpSelector).parent("li"),clearTimeout(o.sfTimer),f(s),c(t),p(s),s.off(".superfish").off(".hoverIntent"),t.children(o.popUpSelector).attr("style",(function(t,e){if(void 0!==e)return e.replace(/display[^;]+;?/g,"")})),o.$path.removeClass(o.hoverClass+" "+n).addClass(o.pathClass),s.find("."+o.hoverClass).removeClass(o.hoverClass),o.onDestroy.call(s),s.removeData("sfOptions")}))},init:function(t){return this.each((function(){var s=e(this);if(s.data("sfOptions"))return!1;var o=e.extend({},e.fn.superfish.defaults,t),i=s.find(o.popUpSelector).parent("li");o.$path=function(t,s){return t.find("li."+s.pathClass).slice(0,s.pathLevels).addClass(s.hoverClass+" "+n).filter((function(){return e(this).children(s.popUpSelector).hide().show().length})).removeClass(s.pathClass)}(s,o),s.data("sfOptions",o),f(s,0,!0),c(i,!0),p(s),function(t,s){var o="li:has("+s.popUpSelector+")";e.fn.hoverIntent&&!s.disableHI?t.hoverIntent(v,y,o):t.on("mouseenter.superfish",o,v).on("mouseleave.superfish",o,y);var i="MSPointerDown.superfish";h&&(i="pointerdown.superfish"),a||(i+=" touchend.superfish"),l&&(i+=" mousedown.superfish"),t.on("focusin.superfish","li",v).on("focusout.superfish","li",y).on(i,"a",s,w)}(s,o),i.not("."+n).superfish("hide",!0),o.onInit.call(this)}))}});e.fn.superfish=function(s,o){return C[s]?C[s].apply(this,Array.prototype.slice.call(arguments,1)):"object"!==t(s)&&s?e.error("Method "+s+" does not exist on jQuery.fn.superfish"):C.init.apply(this,arguments)},e.fn.superfish.defaults={popUpSelector:"ul,.sf-mega",hoverClass:"sfHover",pathClass:"overrideThisToUse",pathLevels:1,delay:200,animation:{opacity:"show"},animationOut:{opacity:"hide"},speed:"fast",speedOut:"fast",disableHI:!1,onInit:e.noop,onBeforeShow:e.noop,onShow:e.noop,onBeforeHide:e.noop,onHide:e.noop,onIdle:e.noop,onDestroy:e.noop,onHandleTouch:e.noop}}(jQuery,window)})();