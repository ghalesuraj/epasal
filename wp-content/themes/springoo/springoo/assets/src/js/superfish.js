/*
 * jQuery Superfish Menu Plugin - v1.7.10
 * Copyright (c) 2018 Joel Birch
 *
 * Dual licensed under the MIT and GPL licenses:
 *	http://www.opensource.org/licenses/mit-license.php
 *	http://www.gnu.org/licenses/gpl.html
 */

;(function($, window) {
    'use strict';

    let windowWidth = $(window).width();
    $(window).resize(function() {
        windowWidth = $(window).width();
    });

    const methods = (function() {
        // private properties and methods go here
        const c = {
                    bcClass: 'sf-breadcrumb',
                    menuClass: 'sf-js-enabled',
                    anchorClass: 'sf-with-ul',
                    // menuArrowClass: 'sf-arrows'
                },
                ios = (function() {
                    var ios = /^(?![\w\W]*Windows Phone)[\w\W]*(iPhone|iPad|iPod)/i.test(navigator.userAgent);
                    if (ios) {
                        // tap anywhere on iOS to unfocus a submenu
                        $('html').css('cursor', 'pointer').on('click', $.noop);
                    }
                    return ios;
                })(),
                // windows phone 7
                wp7 = (function() {
                    var style = document.documentElement.style;
                    return ('behavior' in style && 'fill' in style && /iemobile/i.test(navigator.userAgent));
                })(),
                unprefixedPointerEvents = (function() {
                    return (!!window.PointerEvent);
                })(),
                toggleMenuClasses = function($menu, o, add) {
                    var classes = c.menuClass,
                            method;
                    // if (o.cssArrows) {
                    // 	classes += ' ' + c.menuArrowClass;
                    // }
                    method = (add) ? 'addClass' : 'removeClass';
                    $menu[method](classes);
                },
                setPathToCurrent = function($menu, o) {
                    return $menu.find('li.' + o.pathClass).slice(0, o.pathLevels).addClass(o.hoverClass + ' ' + c.bcClass).filter(function() {
                        return ($(this).children(o.popUpSelector).hide().show().length);
                    }).removeClass(o.pathClass);
                },
                toggleAnchorClass = function($li, add) {
                    var method = (add) ? 'addClass' : 'removeClass';
                    $li.children('a')[method](c.anchorClass);
                },
                toggleTouchAction = function($menu) {
                    var msTouchAction = $menu.css('ms-touch-action');
                    var touchAction = $menu.css('touch-action');
                    touchAction = touchAction || msTouchAction;
                    touchAction = (touchAction === 'pan-y') ? 'auto' : 'pan-y';
                    $menu.css({
                        'ms-touch-action': touchAction,
                        'touch-action': touchAction,
                    });
                },
                getMenu = function($el) {
                    return $el.closest('.' + c.menuClass);
                },
                getOptions = function($el) {
                    return getMenu($el).data('sfOptions');
                },
                over = function() {
                    var $this = $(this),
                            o = getOptions($this);
                    clearTimeout(o.sfTimer);
                    $this.siblings().superfish('hide').end().superfish('show');
                },
                close = function(o) {
                    o.retainPath = ($.inArray(this[0], o.$path) > -1);
                    this.superfish('hide');

                    if (!this.parents('.' + o.hoverClass).length) {
                        o.onIdle.call(getMenu(this));
                        if (o.$path.length) {
                            $.proxy(over, o.$path)();
                        }
                    }
                },
                out = function() {
                    var $this = $(this),
                            o = getOptions($this);
                    if (ios) {
                        $.proxy(close, $this, o)();
                    } else {
                        clearTimeout(o.sfTimer);
                        o.sfTimer = setTimeout($.proxy(close, $this, o), o.delay);
                    }
                },
                touchHandler = function(e) {
                    var $this = $(this),
                            o = getOptions($this),
                            $ul = $this.siblings(e.data.popUpSelector);

                    if (o.onHandleTouch.call($ul) === false) {
                        return this;
                    }

                    if ($ul.length > 0 && $ul.is(':hidden')) {
                        $this.one('click.superfish', false);
                        if (e.type === 'MSPointerDown' || e.type === 'pointerdown') {
                            $this.trigger('focus');
                        } else {
                            $.proxy(over, $this.parent('li'))();
                        }
                    }
                },
                applyHandlers = function($menu, o) {
                    var targets = 'li:has(' + o.popUpSelector + ')';
                    if ($.fn.hoverIntent && !o.disableHI) {
                        $menu.hoverIntent(over, out, targets);
                    } else {
                        $menu.on('mouseenter.superfish', targets, over).on('mouseleave.superfish', targets, out);
                    }
                    var touchevent = 'MSPointerDown.superfish';
                    if (unprefixedPointerEvents) {
                        touchevent = 'pointerdown.superfish';
                    }
                    if (!ios) {
                        touchevent += ' touchend.superfish';
                    }
                    if (wp7) {
                        touchevent += ' mousedown.superfish';
                    }
                    $menu.on('focusin.superfish', 'li', over).on('focusout.superfish', 'li', out).on(touchevent, 'a', o, touchHandler);
                };


        return {
            // public methods
            hide: function(instant) {
                if (this.length) {
                    var $this = this,
                            o = getOptions($this);
                    if (!o) {
                        return this;
                    }
                    var not = (o.retainPath === true) ? o.$path : '',
                            $ul = $this.find('li.' + o.hoverClass).add(this).not(not).removeClass(o.hoverClass).children(o.popUpSelector),
                            speed = o.speedOut;

                    if (instant) {
                        $ul.show();
                        speed = 0;
                    }
                    o.retainPath = false;

                    if (o.onBeforeHide.call($ul) === false) {
                        return this;
                    }

                    $ul.stop(true, true).animate(o.animationOut, speed, function() {
                        var $this = $(this);
                        o.onHide.call($this);
                    });
                }
                return this;
            },
            show: function() {
                var o = getOptions(this);
                if (!o) {
                    return this;
                }
                var $this = this.addClass(o.hoverClass),
                        $ul = $this.children(o.popUpSelector);

                $ul.css({opacity: 0, visibility: 'hidden', display: 'block'});

                //menu style for left right and mega menu
                const li = $ul.closest('li');

                if ( ! li.length ) {
                    return;
                }

                const isFirst = li.hasClass('depth-0');

                if (isFirst && parseInt($ul.css('width')) > windowWidth) {
                    $ul.css('width', '');
                    li.removeClass('width-custom');
                    // make this generic
                    // classname.
                    // li.css('position', 'static');
                }

                if ( isFirst && parseInt($ul.css('width')) > ( windowWidth - li.offset().left ) && parseInt($ul.css('width')) > li.offset().left + li.width() ){
                    $ul.css('width', '');
                    li.removeClass('width-custom');
                }

                if (!li.hasClass('mega-menu')) {
                    const offsetLeft = $ul.offset().left;
                    if (offsetLeft + $ul.outerWidth() > windowWidth) {
                        if (isFirst) {
                            $ul.css({
                                'left': 'auto',
                                'right': '0',
                            });
                        } else {
                            $ul.css({
                                'left': 'auto',
                                'right': '100%',
                            });
                        }
                    }
                    if (offsetLeft < 0) {
                        if (isFirst) {
                            $ul.css({
                                'left': '0',
                                'right': 'auto',
                            });
                        } else {
                            $ul.css({
                                'left': '100%',
                                'right': 'auto',
                            });
                        }
                    }
                } else {
                    //if vertical menu
                    if ($ul.closest('.vertical-navigation').length) {
                        const self = $ul;
                        //if it's mega menu
                        let parentContainerWidth = $('.vertical-navigation-wrap').closest('.container').width();

                        let parentWidth = $('.vertical-nav').width();
                        if (li.hasClass('mega-menu') && li.hasClass('width-full')) {
                            self.css({
                                'width': parentContainerWidth - parentWidth + 'px',
                            });
                        }
                    } else {
                        if ($ul.offset().left + $ul.outerWidth() > windowWidth) {
                            $ul.css({
                                'left': 'auto',
                                'right': '0',
                            });
                        }
                        if ($ul.offset().left < 0) {
                            $ul.css({
                                'left': '0',
                                'right': 'auto',
                            });
                        }
                    }
                }

                $ul.css('opacity', '');
                $ul.css('visibility', '');
                $ul.css('display', 'none');

                if (o.onBeforeShow.call($ul) === false) {
                    return this;
                }

                $ul.stop(true, true).animate(o.animation, o.speed, function() {
                    o.onShow.call($ul);
                });
                return this;
            },
            destroy: function() {
                return this.each(function() {
                    var $this = $(this),
                            o = $this.data('sfOptions'),
                            $hasPopUp;
                    if (!o) {
                        return false;
                    }
                    $hasPopUp = $this.find(o.popUpSelector).parent('li');
                    clearTimeout(o.sfTimer);
                    toggleMenuClasses($this, o);
                    toggleAnchorClass($hasPopUp);
                    toggleTouchAction($this);
                    // remove event handlers
                    $this.off('.superfish').off('.hoverIntent');
                    // clear animation's inline display style
                    $hasPopUp.children(o.popUpSelector).attr('style', function(i, style) {
                        if (typeof style !== 'undefined') {
                            return style.replace(/display[^;]+;?/g, '');
                        }
                    });
                    // reset 'current' path classes
                    o.$path.removeClass(o.hoverClass + ' ' + c.bcClass).addClass(o.pathClass);
                    $this.find('.' + o.hoverClass).removeClass(o.hoverClass);
                    o.onDestroy.call($this);
                    $this.removeData('sfOptions');
                });
            },
            init: function(op) {
                return this.each(function() {
                    const $this = $(this);
                    if ($this.data('sfOptions')) {
                        return false;
                    }
                    const o = $.extend({}, $.fn.superfish.defaults, op),
                            $hasPopUp = $this.find(o.popUpSelector).parent('li');
                    o.$path = setPathToCurrent($this, o);

                    $this.data('sfOptions', o);

                    toggleMenuClasses($this, o, true);
                    toggleAnchorClass($hasPopUp, true);
                    toggleTouchAction($this);
                    applyHandlers($this, o);

                    $hasPopUp.not('.' + c.bcClass).superfish('hide', true);

                    o.onInit.call(this);
                });
            },
        };
    })();

    $.fn.superfish = function(method, args) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            return $.error('Method ' + method + ' does not exist on jQuery.fn.superfish');
        }
    };

    $.fn.superfish.defaults = {
        popUpSelector: 'ul,.sf-mega', // within menu context
        hoverClass: 'sfHover',
        pathClass: 'overrideThisToUse',
        pathLevels: 1,
        delay: 200, // was 800
        animation: {opacity: 'show'},
        animationOut: {opacity: 'hide'},
        speed: 'fast', // was normal
        speedOut: 'fast',
        //cssArrows: false, // was true
        disableHI: false,
        onInit: $.noop,
        onBeforeShow: $.noop,
        onShow: $.noop,
        onBeforeHide: $.noop,
        onHide: $.noop,
        onIdle: $.noop,
        onDestroy: $.noop,
        onHandleTouch: $.noop,
    };

})(jQuery, window);
