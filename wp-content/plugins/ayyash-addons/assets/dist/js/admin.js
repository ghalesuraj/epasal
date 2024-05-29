"use strict";!function(e,t,a,n,s){var i=s.nonce,r=s.i18n,o=r.okay,c=r.cancel,d=r.submit,l=r.success,u=r.warning,f=r.error,h=r.e404,g=r.something_wrong,v=r.are_you_sure,m=function(e,t){var a=arguments.length>2&&void 0!==arguments[2]?arguments[2]:"success",n=arguments.length>3&&void 0!==arguments[3]&&arguments[3];return Swal.fire({title:e,html:t,icon:a,confirmButtonText:o,timer:n?6e4:2e3,width:"450px"})},p=function(t){return e(".ayyash-addons-admin-options--error").remove(),!!Object.keys(t.errors).length&&(e.each(t.errors,(function(t,a){e('[data-depend-id="'+t+'"]').after('<p class="ayyash-addons-admin-options--error ayyash-addons-admin-options--error-text">'+a+"</p>")})),!0)},w=function(e){e.hasOwnProperty("redirect")&&("reload"===e.redirect?t.location.reload():t.location.href=e.redirect)},b=function(e){var t="";e.hasOwnProperty("message")&&e.message?t=e.message:e.hasOwnProperty("statusText")&&(t="404"===e.status?h:g),m(f,t,"error")},y="toplevel_page_ayyash_addons",_=".ayyash-addons-dashboard";if(n===y){var C=t.location.hash||"#dashboard",k=function(e){return t.location.hash=e},D=e(_+"-tabs"),x=D.find(_+"-tabs__nav"),O=D.find(_+"-tabs__content"),S=e("#"+y).find(".wp-submenu"),T=e(".ayyash-addons-dashboard-btn--save"),A=function(t){var a=e('a[href="'+t+'"]'),n="#tab-content-"+t.substring(1),s=O.find(n);T.toggle(s.find("form").length>0&&"#license"!==t),a.addClass("tab--is-active").siblings().removeClass("tab--is-active").blur(),s.addClass("tab--is-active").siblings().removeClass("tab--is-active"),S.find("a").filter((function(e,a){return t===a.hash})).parent().addClass("current").siblings().removeClass("current")};x.on("click",_+"-tabs__nav-item",(function(t){var a=e(t.currentTarget),n=t.currentTarget.hash;if(a.is(".nav-item-is--link"))return!0;t.preventDefault(),k(n)})),S.on("click","a",(function(t){var a=t.currentTarget.hash;if(!a)return!0;t.preventDefault(),k(a),e(t.currentTarget).blur().parent().addClass("current").siblings().removeClass("current"),x.find('a[href="'+a+'"]').click(),e("body").focus()})),C&&(t.location.hash?(x.find('a[href="'+C+'"]').click(),A(C)):k(C)),e(t).on("hashchange",(function(e){e.preventDefault(),A(t.location.hash)})),e(".accordion").beefup({openSingle:!0,openSpeed:400,closeSpeed:400}),T.on("click",(function(t){t.preventDefault(),e(this).closest(".ayyash-addons-dashboard-tabs").find(".tab--is-active form").submit()}));var B=e(".widget-item");e(a).on("click",".widget-filter a",(function(t){t.preventDefault();var a=e(this),n=a.data("filter");a.closest(".widget-filter").find("a").removeClass("active"),a.addClass("active"),"all"===n?B.show():B.show().not(".is-"+n).hide()})).on("click",".toggle-widget",(function(t){t.preventDefault();var a=s.i18n,n=a.confirm_disable_all,i=a.confirm_enable_all,r=e(this).hasClass("all-on");Swal.fire({title:v,html:r?i:n,showCancelButton:!0,allowOutsideClick:!1,showLoaderOnConfirm:!0,cancelButtonText:c,confirmButtonText:d,preConfirm:function(e){return new Promise((function(t,a){e?(B.find(".widget-switch-control").not("[disabled]").prop("checked",r),t()):a()}))}})})),e("#ayyash-addons-widgets-settings").submit((function(t){t.preventDefault();var a={_wpnonce:i,widgets:{}};e(".widget-switch-control").each((function(){var t=e(this),n=(t.closest(".widget-item"),t.attr("name").replace(/^widgets\[(.*)\]$/g,"$1"));a.widgets["".concat(n)]=t.is(":checked")?"on":"off"})),wp.ajax.post("ayyash_addons_save_widgets",a).then((function(e){e.message&&m(l,e.message)})).fail(b)})),e("#ayyash-addons-integration-settings").submit((function(t){t.preventDefault();var a={};e.extend(a,{_wpnonce:i},e(this).serializeJSON()),wp.ajax.post("ayyash_addons_save_integrations",a).then((function(e){var t=p(e);e.message&&m(t?u:l,e.message,t?"warning":"success"),w(e)})).fail((function(e){b(e),p(e),w(e)}))}))}}(jQuery,window,document,pagenow,AYYASH_ADDONS_ADMIN_DASHBOARD);