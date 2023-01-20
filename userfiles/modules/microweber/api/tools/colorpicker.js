;(function (){
    mw._colorPickerDefaults = {
        skin: 'mw-tooltip-default',
        position: 'bottom-center',
        onchange: false
    };


    var _isValidColorTest = new Option();
    var isValidColor = (color) => {
        _isValidColorTest.style.color = '';
        _isValidColorTest.style.color = color;
        return _isValidColorTest.style.color !== '';
    }

    var colorPickers = [];

    mw._colorPicker = function (options) {
        mw.lib.require('colorpicker');

        if (!mw.tools.colorPickerColors) {
            mw.tools.colorPickerColors = [];

            if (typeof mw.color !== 'undefined' && typeof mw.color.rgbToHex !== 'undefined') {
                var colorpicker_els = mw.top().$(".btn,h1,h2,h3,h4,h5");
                if (colorpicker_els.length > 0) {
                    colorpicker_els.each(function () {
                        var css = parent.getComputedStyle(this, null);
                        if (css !== null) {
                            if (mw.tools.colorPickerColors.indexOf(css.color) === -1) {
                                mw.tools.colorPickerColors.push(mw.color.rgbToHex(css.color));
                            }
                            if (mw.tools.colorPickerColors.indexOf(css.backgroundColor) === -1) {
                                mw.tools.colorPickerColors.push(mw.color.rgbToHex(css.backgroundColor));
                            }
                        }
                    });
                }
            }
        }
        var proto = this;
        if (!options) {
            return false;
        }

        var settings = $.extend({}, mw._colorPickerDefaults, options);

        if (settings.element === undefined || settings.element === null) {
            return false;
        }



        var $el = mw.$(settings.element);
        if ($el[0] === undefined) {
            return false;
        }
        if($el[0].mwcolorPicker) {
            return $el[0].mwcolorPicker;
        }


        $el[0].mwcolorPicker = this;
        this.element = $el[0];
        if ($el[0].mwToolTipBinded !== undefined) {
            return false;
        }
        if (!settings.method) {
            if (this.element.nodeName === 'DIV') {
                settings.method = 'inline';
            }
        }
        this.settings = settings;

        $el[0].mwToolTipBinded = true;
        var sett = {
            showAlpha: true,
            showHSL: false,
            showRGB: false,
            showHEX: true,
            palette: mw.tools.colorPickerColors
        };

        if(settings.value) {
            sett.color = settings.value;

        }
        if(typeof settings.showRGB !== 'undefined') {
            sett.showRGB = settings.showRGB
        }
        if(typeof settings.showHEX !== 'undefined') {
            sett.showHEX = settings.showHEX
        }

        if(typeof settings.showHSL !== 'undefined') {
            sett.showHSL = settings.showHSL
        }
        var frame;
        this.setColor = function (color) {
            frame.setColor(color)
        };
        if (settings.method === 'inline') {

            sett.attachTo = $el[0];

            frame = AColorPicker.createPicker(sett);
            frame.onchange = function (data) {

                if (proto.settings.onchange) {
                    proto.settings.onchange(data.color);
                }

                if ($el[0].nodeName === 'INPUT') {
                    var val = val === 'transparent' ? val : '#' + val;
                    $el.val(val);
                    var prev = $el[0].previousElementSibling
                    if( prev && prev.classList.contains('mw-field-color-indicator')) {
                        var dp = prev.querySelector('.mw-field-color-indicator-display')
                        if(dp) {
                            dp.style.backgroundColor = val
                        }
                    }
                }
            }

        } else {
            var tip = mw.tooltip(settings), $tip = mw.$(tip).hide();

            colorPickers.push(tip)
            this.tip = tip;

            mw.$('.mw-tooltip-content', tip).empty().css({
                padding: 0
            });
            sett.attachTo = mw.$('.mw-tooltip-content', tip)[0]

            frame = AColorPicker.createPicker(sett);

            frame.onchange = function (data) {

                if(frame.pause) {
                    return;
                }

                if (proto.settings.onchange) {
                    proto.settings.onchange(data.color);
                }

                if ($el[0].nodeName === 'INPUT') {
                    $el.val(data.color);
                    var prev = $el[0].previousElementSibling
                    if(prev && prev.classList.contains('mw-field-color-indicator')) {
                        var dp = prev.querySelector('.mw-field-color-indicator-display');
                        if(dp) {
                            dp.style.backgroundColor = data.color
                        }

                    }
                }
            };
            if ($el[0].nodeName === 'INPUT') {
                $el.on('input', function (e) {
                    var val = this.value.trim();
                    if(!val) {
                        val = 'transparent';
                    }
                    if(isValidColor(val)) {
                        var prev = $el[0].previousElementSibling
                        if( prev && prev.classList.contains('mw-field-color-indicator')) {
                            var dp = prev.querySelector('.mw-field-color-indicator-display')
                            if(dp) {
                                dp.style.backgroundColor = val
                            }
                        }
                        if (proto.settings.onchange) {
                            proto.settings.onchange(val);
                        }
                    }
                });
                    $el.on('focus', function (e) {
                        if(this.value.trim()){
                            frame.pause = true;
                            frame.color = this.value;
                            setTimeout(function () {
                                frame.pause = false;
                            });
                        }
                        colorPickers.forEach(function (clpckr){

                            if(clpckr !== tip) {
                                mw.$(clpckr).hide();
                            }
                        });
                        mw.$(tip).show();
                        mw.tools.tooltip.setPosition(tip, $el[0], settings.position)

                    });
                } else {
                $el.on('click', function (e) {
                    colorPickers.forEach(function (clpckr){

                        if(clpckr !== tip) {
                            mw.$(clpckr).hide();
                        }
                    });
                    mw.$(tip).toggle();
                    mw.tools.tooltip.setPosition(tip, $el[0], settings.position);
                });
            }
            var documents = [document];
            if (self !== mw.top().win){
                documents.push(mw.top().win.document);
            }


            $el.addClass('mw-color-picker-field')

            }
            if (this.tip) {
                this.show = function () {
                    mw.$(this.tip).show();
                    mw.tools.tooltip.setPosition(this.tip, this.settings.element, this.settings.position)
                };
                this.hide = function () {
                    mw.$(this.tip).hide();
                };
                this.toggle = function () {
                    var tip = mw.$(this.tip);
                    if (tip.is(':visible')) {
                        this.hide();
                    }
                    else {
                        $el.focus();
                        this.show();
                    }
                }
            }
            var prev = $el[0].previousElementSibling
            if( prev && prev.classList.contains('mw-field-color-indicator')) {
                var dp = prev.querySelector('.mw-field-color-indicator-display')
                if(dp) {
                    dp.style.backgroundColor = $el[0].value
                }
            }

        };
        mw.colorPicker = function (o) {

            return new mw._colorPicker(o);
        };

        $(document).ready(function (){
            $(document).on('click', function (e){
                if(!mw.tools.hasParentsWithClass(e.target, 'a-color-picker') && !mw.tools.hasClass(e.target, 'mw-color-picker-field')) {
                    colorPickers.forEach(function (clpckr){

                        mw.$(clpckr).hide();

                    });
                }

            })
        })

    })();
