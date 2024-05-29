"use strict";
/**!
 * FAQs Public Scripts
 *
 * @author Themerox
 * @package AyyashAddons
 * @version 1.0.0
 */!function(e,n,t){e(n).on("elementor/frontend/init",(function(){elementorFrontend.hooks.addAction("frontend/element_ready/ayyash-addons-faq.default",(function(n){var t=n.find(".accordion");if(n.find(".faq-tab-item").beefup({trigger:".tab-head",content:".tab-body",animation:"fade",openSingle:!0,openSpeed:400,selfClose:!1,closeSpeed:400,onOpen:function(n){e('a[href="#'+n.attr("id")+'"]').parent().addClass(this.openClass).siblings().removeClass(this.openClass)}}),t.beefup({animation:"fade",openSingle:!0,openSpeed:400,closeSpeed:400}),t.each((function(){e(this).find(".accordion").beefup({onOpen:function(e){e.find(".faq-trim-words").slideUp()},onClose:function(e){e.find(".faq-trim-words").slideDown()}})})),n.find(".faqsearch").length){var o=n.find(".faq"),i=[];o.each((function(){i.push({el:e(this),question:e(this).find("button").text().trim(),answer:e(this).find(".collapse-body p").text().trim()})})),n.find(".faqsearch").on("keyup",(function(){var n=e(this).val().toLowerCase();n.length||o.show(),n.length<1||e(".tab-body .faq").filter((function(){e(this).toggle(e(this).text().toLowerCase().indexOf(n)>-1)}))}))}}))}))}(jQuery,window,window.elementorFrontend);