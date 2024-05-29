"use strict";function _typeof(e){return _typeof="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},_typeof(e)}function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _defineProperties(e,t){for(var o=0;o<t.length;o++){var n=t[o];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,_toPropertyKey(n.key),n)}}function _createClass(e,t,o){return t&&_defineProperties(e.prototype,t),o&&_defineProperties(e,o),Object.defineProperty(e,"prototype",{writable:!1}),e}function _toPropertyKey(e){var t=_toPrimitive(e,"string");return"symbol"===_typeof(t)?t:String(t)}function _toPrimitive(e,t){if("object"!==_typeof(e)||null===e)return e;var o=e[Symbol.toPrimitive];if(void 0!==o){var n=o.call(e,t||"default");if("object"!==_typeof(n))return n;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===t?String:Number)(e)}function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),Object.defineProperty(e,"prototype",{writable:!1}),t&&_setPrototypeOf(e,t)}function _setPrototypeOf(e,t){return _setPrototypeOf=Object.setPrototypeOf?Object.setPrototypeOf.bind():function(e,t){return e.__proto__=t,e},_setPrototypeOf(e,t)}function _createSuper(e){var t=_isNativeReflectConstruct();return function(){var o,n=_getPrototypeOf(e);if(t){var i=_getPrototypeOf(this).constructor;o=Reflect.construct(n,arguments,i)}else o=n.apply(this,arguments);return _possibleConstructorReturn(this,o)}}function _possibleConstructorReturn(e,t){if(t&&("object"===_typeof(t)||"function"==typeof t))return t;if(void 0!==t)throw new TypeError("Derived constructors may only return object or undefined");return _assertThisInitialized(e)}function _assertThisInitialized(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}function _isNativeReflectConstruct(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){}))),!0}catch(e){return!1}}function _getPrototypeOf(e){return _getPrototypeOf=Object.setPrototypeOf?Object.getPrototypeOf.bind():function(e){return e.__proto__||Object.getPrototypeOf(e)},_getPrototypeOf(e)}!function(e,t,o,n){var i=n.has_pro,r=n.mdv,a=n.i18n,s=n.pro_widgets,l="ayyash-pro-widgets",c=function(e){var t=arguments.length>1&&void 0!==arguments[1]&&arguments[1];return elementorCommon.translate(e,null,t,a)};Object.keys(r).length&&e.each(r,(function(t,n){e(o).on("change",'[data-setting="'+t+'"]',(function(){var t=e(this).val();n.hasOwnProperty(t)&&e.each(n[t],(function(t,o){e('[data-setting="'+t+'"]').val(o).trigger("input")}))}))})),e(".elementor-control-icon-picker").on("click",(function(){setTimeout((function(){e(".elementor-icons-manager__tab-link i.ayyash-absolute").parent(".elementor-icons-manager__tab-link").addClass("ayyash-addons-icon-title")}),500)}));var u=new(function(e){_inherits(o,elementorModules.Module);var t=_createSuper(o);function o(){var e;return _classCallCheck(this,o),(e=t.call(this)).initDialog(),e}return _createClass(o,[{key:"initDialog",value:function(){var e=this;this.dialog=elementor.dialogsManager.createWidget("buttons",{id:"elementor-element--promotion__dialog",effects:{show:"show",hide:"hide"},hide:{onOutsideClick:!1},position:{my:(elementorCommon.config.isRTL?"right":"left")+"+5 top"}}),this.dialog.addButton({name:"action",text:c("See it in Action","elementor"),callback:function(){open(e.actionURL,"_blank")}}),this.dialog.getElements("action").addClass("elementor-button elementor-button-success ayyash-btn--promo__demo");var t=jQuery("<div>",{id:"elementor-element--promotion__dialog__title"}),o=jQuery("<i>",{class:"eicon-pro-icon"}),n=jQuery("<i>",{class:"eicon-close"});n.on("click",(function(){return e.dialog.hide()})),this.dialog.getElements("header").append(t,o,n),this.$promotionTitle=t}},{key:"showDialog",value:function(e){return this.dialog||this.initDialog(),this.actionURL=e.actionURL,this.$promotionTitle.text(e.headerMessage),this.dialog.setMessage(e.message).setSettings("position",{of:e.element,at:(elementorCommon.config.isRTL?"left":"right")+" top"+e.top}),elementor.promotion.dialog.hide(),this.dialog.show()}}]),o}());function d(){var e=elementor.settings.page.model.get("ayyash_custom_css");e&&(e=e.replace(/selector/g,elementor.config.settings.page.cssWrapperSelector),elementor.settings.page.getControlsCSS().elements.$stylesheetElement.append(e))}elementor.addBackgroundClickListener("ayyash_addons_promo",{ignore:".elementor-panel-category-items",callback:function(){u.dialog&&u.dialog.hide()}}),elementor.promotion.dialog.on("show",(function(){u.dialog.hide()})),elementor.promotion.dialog.on("hide",(function(){u.dialog.hide()})),elementor.hooks.addFilter("panel/elements/regionViews",(function(e){if(i||_.isEmpty(s))return e;var t={events:function(){var e,t={};return this.isEditable()||(t.mousedown=(e=this.model).get("name")&&-1<e.get("name").indexOf("absolute-")?"onMouseXDown":"onMouseDown"),t},onMouseXDown:function(){var e=this.model;u.showDialog({headerMessage:c("promo.header",[e.get("title")]),message:c("promo.body",[e.get("title")]),top:"-7",element:this.el,actionURL:"https://go.absoluteplugins.com/to/absolute-addons-pro/"+e.get("name")})}},o=e.elements.view,n=e.elements.options.collection,r=e.categories.view,a=e.categories.options.collection,d=a.findIndex({name:"ayyash-widgets"}),f=[];return _.each(s,(function(e){var t=e.name,o=e.title,i=e.icon;n.add({name:t,title:o,icon:i,categories:[l],editable:!1})})),n.each((function(e){return e.get("categories").includes(l)?f.push(e):void 0})),d&&a.add({name:l,title:c("ayyash_addons_pro"),icon:"fa fa-plug",defaultActive:!0,items:f},{at:d+1}),e.elements.view=o.extend({childView:o.prototype.childView.extend(t)}),e.categories.view=r.extend({childView:r.prototype.childView.extend({childView:r.prototype.childView.prototype.childView.extend(t)})}),e})),elementor.hooks.addFilter("editor/style/styleText",(function(e,t){if(t){var o=t.model,n=o.get("settings").get("ayyash_custom_css"),i=".elementor-element.elementor-element-"+o.get("id");return"document"===o.get("elType")&&(i=elementor.config.document.settings.cssWrapperSelector),n&&(e+=n.replace(/selector/g,i)),e}})),elementor.settings.page.model.on("change",d),elementor.on("preview:loaded",d)}(jQuery,window,document,Ayyash_Addons_Editor_Config);