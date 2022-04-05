(function(){

    var ToolTip = function (options) {
        options = options || {};

        var defaults = {
            template: 'default',
            overlay: false,
            position: 'bottom-center'
        };

        var scope = this;

        this.settings = mw.object.extend({}, defaults, options);
        if (this.settings.skin ) {
            this.settings.template = this.settings.skin;
        }

        var create = function () {
            var tpl = scope.settings.template.indexOf('mw-tooltip') === 0 ? scope.settings.template : 'mw-tooltip  mw-tooltip-' + scope.settings.template;
            tpl += ' mw-tooltip-widget';
            scope.tooltip = mw.element({
                tag: 'div',
                props: {
                    className: tpl,
                    id: scope.settings.id || mw.id('mw-tooltip-')
                }
            });
            scope.tooltip.get(0)._mwtooltip = scope;
            if ( scope.settings.overlay) {
                scope.overlay = mw.element({
                    tag: 'div',
                    props: {
                        className: 'mw-tooltip-overlay',
                    }
                });
                scope.overlay.on('mousedown touchstart', function (){
                    scope.remove()
                });
            }
            mw.element('body')
                .append(scope.overlay)
                .append(scope.tooltip);
            scope.content(scope.settings.content);
        };

        var _e = {};

        this.on = function (e, f) { _e[e] ? _e[e].push(f) : (_e[e] = [f]) };

        this.dispatch = function (e, f) { _e[e] ? _e[e].forEach(function (c){ c.call(this, f); }) : ''; };

        this.content = function (content) {
          if (typeof content === 'undefined') {
              return scope.tooltip.innerHTML;
          }
          scope.tooltip.html(content || '') ;
        };

        this.remove = function () {
            this.tooltip.remove();
            if (this.overlay) {
                this.overlay.remove();
            }
             this.dispatch('removed');
        };
        this.show = function () {
            this.tooltip.show();
            if (this.overlay) {
                this.overlay.show();
            }
            this.dispatch('show');
        };
        this.hide = function () {
            this.tooltip.hide();
            if (this.overlay) {
                this.overlay.hide();
            }
            this.dispatch('hide');
        };

        this._position = null;
        this.position = function (position, target) {
            position = position || this._position || this.settings.position;
            if (target) {
                scope.settings.element = target;
            }
            var el = mw.$(scope.settings.element);
            if (el.length === 0) {
                return false;
            }
            var tooltip = this.tooltip.get(0);
            var w = el.outerWidth(),
                tipwidth = mw.$(tooltip).outerWidth(),
                h = el.outerHeight(),
                tipheight = mw.$(tooltip).outerHeight(),
                off = el.offset();
            if (off.top === 0 && off.left === 0) {
                off = el.parent().offset();
            }
            mw.tools.removeClass(tooltip, this._position);
            mw.tools.addClass(tooltip, position);
            this._position = position


            off.left = off.left > 0 ? off.left : 0;
            off.top = off.top > 0 ? off.top : 0;

            var leftCenter = off.left - tipwidth / 2 + w / 2;
            leftCenter = leftCenter > 0 ? leftCenter : 0;

            if (position === 'bottom-left') {
                mw.$(tooltip).css({
                    top: off.top + h,
                    left: off.left
                });
            }
            else if (position === 'bottom-center') {
                mw.$(tooltip).css({
                    top: off.top + h,
                    left: leftCenter
                });
            }
            else if (position === 'bottom-right') {
                mw.$(tooltip).css({
                    top: off.top + h,
                    left: off.left - tipwidth + w
                });
            }
            else if (position === 'top-right') {
                mw.$(tooltip).css({
                    top: off.top - tipheight - arrheight,
                    left: off.left - tipwidth + w
                });
            }
            else if (position === 'top-left') {
                mw.$(tooltip).css({
                    top: off.top - tipheight - arrheight,
                    left: off.left
                });
            }
            else if (position === 'top-center') {

                mw.$(tooltip).css({
                    top: off.top - tipheight - arrheight,
                    left: leftCenter
                });
            }
            else if (position === 'left-top') {
                mw.$(tooltip).css({
                    top: off.top,
                    left: off.left - tipwidth - arrheight
                });
            }
            else if (position === 'left-bottom') {
                mw.$(tooltip).css({
                    top: (off.top + h) - tipheight,
                    left: off.left - tipwidth - arrheight
                });
            }
            else if (position === 'left-center') {
                mw.$(tooltip).css({
                    top: off.top - tipheight / 2 + h / 2,
                    left: off.left - tipwidth - arrheight
                });
            }
            else if (position === 'right-top') {
                mw.$(tooltip).css({
                    top: off.top,
                    left: off.left + w
                });
            }
            else if (position === 'right-bottom') {
                mw.$(tooltip).css({
                    top: (off.top + h) - tipheight,
                    left: off.left + w
                });
            }
            else if (position === 'right-center') {
                mw.$(tooltip).css({
                    top: off.top - tipheight / 2 + h / 2,
                    left: off.left + w
                });
            }
            if (parseFloat($(tooltip).css('top')) < 0) {
                mw.$(tooltip).css('top', 0);
            }
            this.show();
        };

        var init = function () {
            create();
            scope.position();
            scope.show();
        };

        init();

    };

    mw.ToolTip = ToolTip;


    var tooltip = {
        source: function (content, skin, position, id) {
            if (skin === 'dark') {
                skin = 'mw-tooltip-dark';
            } else if (skin === 'warning') {
                skin = 'mw-tooltip-default mw-tooltip-warning';
            }
            if (typeof id === 'undefined') {
                id = 'mw-tooltip-' + mw.random();
            }
            var tooltip = document.createElement('div');
            var tooltipc = document.createElement('div');
            tooltip.className = 'mw-tooltip ' + position + ' ' + skin;
            tooltipc.className = 'mw-tooltip-content';
            tooltip.id = id;
            $(tooltipc).append(content);
            $(tooltip).append(tooltipc).append('<span class="mw-tooltip-arrow"></span>');
            document.body.appendChild(tooltip);
            return tooltip;
        },
        setPosition: function (tooltip, el, position) {
                if(window.top.document.documentElement.dir === 'rtl') {
                    if (position.indexOf('right') !== -1) {
                        position = position.replace('right', 'left');
                    } else if (position.indexOf('left') !== -1) {
                        position = position.replace('left', 'right');
                    }
                }
                el = mw.$(el);
                if (el.length === 0) {
                    return false;
                }
                tooltip.tooltipData.element = el[0];
                var w = el.outerWidth(),
                    tipwidth = mw.$(tooltip).outerWidth(),
                    h = el.outerHeight(),
                    tipheight = mw.$(tooltip).outerHeight(),
                    off = el.offset(),
                    arrheight = mw.$('.mw-tooltip-arrow', tooltip).height();
                if (off.top === 0 && off.left === 0) {
                    off = mw.$(el).parent().offset();
                }
                mw.tools.removeClass(tooltip, tooltip.tooltipData.position);
                mw.tools.addClass(tooltip, position);
                tooltip.tooltipData.position = position;

                off.left = off.left > 0 ? off.left : 0;
                off.top = off.top > 0 ? off.top : 0;

                var leftCenter = off.left - tipwidth / 2 + w / 2;
                leftCenter = leftCenter > 0 ? leftCenter : 0;

                if (position === 'auto') {
                    var $win = mw.$(window);
                    var wxCenter =  $win.width()/2;
                    var wyCenter =  ($win.height() + $win.scrollTop())/2;
                    var elXCenter =  off.left +(w/2);
                    var elYCenter =  off.top +(h/2);
                    var xPos, yPost;
                    var space = 100;

                    if(elXCenter > wxCenter) {
                        xPos = 'left'
                    } else {
                        xPos = 'right'
                    }

                    yPos = 'top'


                    return this.setPosition (tooltip, el, (xPos+'-'+yPos));
                }

                if (position === 'bottom-left') {
                    mw.$(tooltip).css({
                        top: off.top + h + arrheight,
                        left: off.left
                    });
                }
                else if (position === 'bottom-center') {
                    mw.$(tooltip).css({
                        top: off.top + h + arrheight,
                        left: leftCenter
                    });
                }
                else if (position === 'bottom-right') {
                    mw.$(tooltip).css({
                        top: off.top + h + arrheight,
                        left: off.left - tipwidth + w
                    });
                }
                else if (position === 'top-right') {
                    mw.$(tooltip).css({
                        top: off.top - tipheight - arrheight,
                        left: off.left - tipwidth + w
                    });
                }
                else if (position === 'top-left') {
                    mw.$(tooltip).css({
                        top: off.top - tipheight - arrheight,
                        left: off.left
                    });
                }
                else if (position === 'top-center') {

                    mw.$(tooltip).css({
                        top: off.top - tipheight - arrheight,
                        left: leftCenter
                    });
                }
                else if (position === 'left-top') {
                    mw.$(tooltip).css({
                        top: off.top,
                        left: off.left - tipwidth - arrheight
                    });
                }
                else if (position === 'left-bottom') {
                    mw.$(tooltip).css({
                        top: (off.top + h) - tipheight,
                        left: off.left - tipwidth - arrheight
                    });
                }
                else if (position === 'left-center') {
                    mw.$(tooltip).css({
                        top: off.top - tipheight / 2 + h / 2,
                        left: off.left - tipwidth - arrheight
                    });
                }
                else if (position === 'right-top') {
                    mw.$(tooltip).css({
                        top: off.top,
                        left: off.left + w + arrheight
                    });
                }
                else if (position === 'right-bottom') {
                    mw.$(tooltip).css({
                        top: (off.top + h) - tipheight,
                        left: off.left + w + arrheight
                    });
                }
                else if (position === 'right-center') {
                    mw.$(tooltip).css({
                        top: off.top - tipheight / 2 + h / 2,
                        left: off.left + w + arrheight
                    });
                }
                if (parseFloat($(tooltip).css('top')) < 0) {
                    mw.$(tooltip).css('top', 0);
                }
            },
            fixPosition: function (tooltip) {
                /* mw_todo */
                var max = 5;
                var arr = mw.$('.mw-tooltip-arrow', tooltip);
                arr.css('left', '');
                var arr_left = parseFloat(arr.css('left'));
                var tt = mw.$(tooltip);
                if (tt.length === 0) {
                    return false;
                }
                var w = tt.width(),
                    off = tt.offset(),
                    ww = mw.$(window).width();
                if ((off.left + w) > (ww - max)) {
                    var diff = off.left - (ww - w - max);
                    tt.css('left', ww - w - max);
                    arr.css('left', arr_left + diff);
                }
                if (parseFloat(tt.css('top')) < 0) {
                    tt.css('top', 0);
                }
            },
            prepare: function (o) {
                if (typeof o.element === 'undefined') return false;
                if (o.element === null) return false;
                if (typeof o.element === 'string') {
                    o.element = mw.$(o.element)
                }

                if (o.element.constructor === [].constructor && o.element.length === 0) return false;
                if (typeof o.position === 'undefined') {
                    o.position = 'auto';
                }
                if (typeof o.skin === 'undefined') {
                    o.skin = 'mw-tooltip-default';
                }
                if (typeof o.id === 'undefined') {
                    o.id = 'mw-tooltip-' + mw.random();
                }
                if (typeof o.group === 'undefined') {
                    o.group = null;
                }
                return {
                    id: o.id,
                    element: o.element,
                    skin: o.template || o.skin,
                    position: o.position,
                    overlay: o.overlay,
                    content: o.content,
                    group: o.group
                }
            },
            init: function (o, wl) {

                var orig_options = o;
                o = mw.tools.tooltip.prepare(o);
                if (o === false) return false;
                var tip;
                if (o.id && mw.$('#' + o.id).length > 0) {
                    tip = mw.$('#' + o.id)[0];
                } else {
                    tip = mw.tools.tooltip.source(o.content, o.skin, o.position, o.id);
                }

                if(o.overlay) {
                    var overlay = $('<div class="mw-tooltip-overlay"></div>');
                    tip.tremove = function () {
                        overlay.remove();
                        tip.remove();
                    };

                    overlay.on('click', function () {
                        tip.tremove()
                    });

                    $('body').append(overlay)
                }
                tip.tooltipData = o;
                wl = wl || true;
                if (o.group) {
                    var tip_group_class = 'mw-tooltip-group-' + o.group;
                    var cur_tip = mw.$(tip)
                    if (!cur_tip.hasClass(tip_group_class)) {
                        cur_tip.addClass(tip_group_class);
                    }
                    var cur_tip_id = cur_tip.attr('id');
                    if (cur_tip_id) {
                        mw.$("." + tip_group_class).not("#" + cur_tip_id).hide();
                        if (o.group && typeof orig_options.close_on_click_outside !== 'undefined' && orig_options.close_on_click_outside) {
                            setTimeout(function () {
                                mw.$("#" + cur_tip_id).show();
                            }, 100);
                        } else {
                            mw.$("#" + cur_tip_id).show();
                        }
                    }
                }
                if (wl && $.contains(self.document, tip)) {

                    if (o.group && typeof orig_options.close_on_click_outside !== 'undefined' && orig_options.close_on_click_outside) {
                        mw.$(self).bind('click', function (e, target) {
                            mw.$("." + tip_group_class).hide();
                        });
                    }
                }
                mw.tools.tooltip.setPosition(tip, o.element, o.position);
                return tip;
            }

    };

    mw.tools.tooltip = tooltip;
    var TTTime = null;
    mw.tools.titleTip = function (el, _titleTip) {
        clearTimeout(TTTime);
        mw.$(mw.tools[_titleTip]).hide();
        TTTime = setTimeout(function (){
        _titleTip = _titleTip || '_titleTip';
        if (mw.tools.hasClass(el, 'tip-disabled')) {
            mw.$(mw.tools[_titleTip]).hide();
            return false;
        }
        var skin = mw.$(el).attr('data-tipskin');
        skin = (skin) ? skin : 'mw-tooltip-dark';
        var pos = mw.$(el).attr('data-tipposition');
        var iscircle = mw.$(el).attr('data-tipcircle') === 'true';
        if (!pos) {
            pos = 'top-center';
        }
        var text = mw.$(el).attr('data-tip');
        if (!text) {
            text = mw.$(el).attr('title');
        }
        if (!text) {
            text = mw.$(el).attr('tip');
        }
        if (typeof text === 'undefined' || !text) {
            return;
        }
        if (text.indexOf('.') === 0 || text.indexOf('#') === 0) {
            var xitem = mw.$(text);
            if (xitem.length === 0) {
                return false;
            }
            text = xitem.html();
        }
        else {
            text = text.replace(/\n/g, '<br>');
        }
        var showon = mw.$(el).attr('data-showon');
        if (showon) {
            el = mw.$(showon)[0];
        }
        if (!mw.tools[_titleTip]) {
            mw.tools[_titleTip] = mw.tooltip({skin: skin, element: el, position: pos, content: text});
            mw.$(mw.tools[_titleTip]).addClass('mw-universal-tooltip');
        }
        else {
            mw.tools[_titleTip].className = 'mw-tooltip ' + pos + ' ' + skin + ' mw-universal-tooltip';
            mw.$('.mw-tooltip-content', mw.tools[_titleTip]).html(text);
            mw.tools.tooltip.setPosition(mw.tools[_titleTip], el, pos);
        }
        if (iscircle) {
            mw.$(mw.tools[_titleTip]).addClass('mw-tooltip-circle');
        }
        else {
            mw.$(mw.tools[_titleTip]).removeClass('mw-tooltip-circle');
        }
        mw.$(mw.tools[_titleTip]).show();
        }, 500)
    };

})();
