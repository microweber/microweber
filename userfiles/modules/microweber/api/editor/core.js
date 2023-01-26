

;(function (){

    MWEditor.core = {
        capsulatedField: function (options) {

            var defaults = {
                width: '55px',
                height: '35px',
                placeholder: '',
                background: 'transparent',
                color: '#333',
                css: ''
            };

            if(!options) {
                options = {};
            }


            this.settings = Object.assign({}, defaults, options);
            var css
            if(this.settings.css) {
                css = document.createElement('style');
                css.type = 'text/css';
                css.innerHTML = 'input{' + this.settings.css + '}';
            }





            var scope = this;
            this.frame = document.createElement('iframe');
            this.field = document.createElement('input');
            this.field.placeholder = this.settings.placeholder;


            this.frame.style.width = this.settings.width;
            this.frame.style.height = this.settings.height;
            this.frame.style.overflow = 'hidden';
            this.frame.style.background = 'transparent';

            this.field.style.width = this.settings.width;
            this.field.style.height = this.settings.height;

            this.field.style.outline = this.settings.outline || 'none';
            this.field.style.border = this.settings.border || 'none';
            this.field.style.background = this.settings.background;
            this.field.style.color = this.settings.color;

            scope.frame.tabIndex = -1;
            scope.frame.frameborder = 0;
            this.setValue = function (val) {
                scope.field.value = val || '';
            };

            this.getValue = function () {
                return scope.field.value;
            };

            scope.frame.addEventListener('load', function (){
                scope.frame.contentDocument.body.appendChild(scope.field);
                if(css) {
                    scope.frame.contentDocument.body.appendChild(css);
                }
                scope.frame.contentDocument.body.style.margin = 0;
                scope.frame.contentDocument.body.style.padding = 0;
                scope.frame.contentDocument.body.style.overflow = 'hidden';
                scope.frame.contentDocument.body.style.background = 'transparent';
                scope.frame.contentDocument.documentElement.style.margin = 0;
                scope.frame.contentDocument.documentElement.style.padding = 0;
                scope.frame.contentDocument.documentElement.style.overflow = 'hidden';
                scope.frame.contentDocument.documentElement.style.background = 'transparent';
            });

        },
        button: function(config) {
            config = config || {};
            var defaults = {
                tag: 'mw-editor-button',
                props: {
                    className: 'mdi mw-editor-controller-component mw-editor-controller-button',
                    type: 'button',

                },
            };
            if (config.props && config.props.className){
                config.props.className = defaults.props.className + ' ' + config.props.className;
            }
            var settings = $.extend( true, {}, defaults, config);
            return mw.element(settings);
        },
        colorPicker: function(config) {

            config = config || {};
            var defaults = {
                props: {
                    className: 'mw-editor-controller-component'
                }
            };
            var settings = $.extend(true, {}, defaults, config);
            var _opt = new Option();
            var isColor = function(strColor)  {
                _opt.style.color = strColor;
                return _opt.style.color !== '';
            };

            var el = MWEditor.core.button(settings);


            el.addClass('mw-editor-color-picker');


            var tip = mw.element({
                props: {
                    className: 'mw-editor-color-picker-dialog'
                }
            });
            mw.element(document.body).append(tip).on('mousedown touchstart', function (e) {
                if(!el.get(0).contains(e.target) && !tip.get(0).contains(e.target)){
                    tip.hide()
                }
            });

            el.on('click', function (e){
                var off = el.offset();
                tip.css({
                    top: off.offsetTop + off.height,
                    left: off.offsetLeft,

                }).toggle();
            });


            var cf = new MWEditor.core.capsulatedField({
                placeholder: '#efecec',
                width: '100%',
                color: 'white',
                css: 'text-align: center;'
            });

            var _pauseSetValue = false;

           var picker = mw.colorPicker({
                element: tip.get(0),
                // position: 'bottom-center',
                method: 'inline',
                showHEX: false,
                onchange: function (color) {
                    el.trigger('change', color);
                    if(!_pauseSetValue) {
                        cf.field.value = color;
                    }

                },

            });





           mw.element('.a-color-picker-row.a-color-picker-palette', tip.get(0)).before(cf.frame);

            cf.field.addEventListener('input', function (e){
                e.stopPropagation();
                if(isColor(this.value)) {
                    _pauseSetValue = true;
                    picker.setColor(this.value);
                    _pauseSetValue = false;
                }
            });

            cf.field.addEventListener('mousedown', function (e){
                e.stopPropagation();
            })




            return el;
        },
        element: function(config) {
            config = config || {};
            var defaults = {
                props: {
                    className: 'mw-editor-controller-component'
                }
            };
            var settings = $.extend(true, {}, defaults, config);
            var el = mw.element(settings);
            el.on('mousedown touchstart', function (e) {
                e.preventDefault();
            });
            return el;
        },

        _dropdownOption: function (data, eachOption) {
            // data: { label: string, value: any },
            var option = MWEditor.core.element({
                props: {
                    className: 'mw-editor-dropdown-option',
                    innerHTML: data.label,
                    dataset: {
                        value: data.value,
                        label: encodeURIComponent(data.label),
                    }
                }
            });
            option.on('mousedown touchstart', function (e) {
                e.preventDefault();
            });
            option.value = data.value;

            if(eachOption) {
                eachOption.call(option, data, option.get(0))
            }
            return option;
        },
        dropdown: function (options) {
            var lscope = this;
            this.root = MWEditor.core.element();
            this.select = MWEditor.core.element({
                props: {
                    className: 'mw-editor-controller-component mw-editor-controller-component-select',
                    tooltip: options.placeholder || null
                }
            });


            setTimeout(function () {
                var doc = lscope.select.get(0).ownerDocument;
                if(doc && !doc.body.__mwEditorDropwdownRegister) {
                    doc.body.__mwEditorDropwdownRegister = true;
                    doc.body.addEventListener('click', function (e){
                        if (!mw.tools.hasParentsWithClass(e.target, 'mw-editor-controller-component-select')) {
                            mw.element('.mw-editor-controller-component-select.active').each(function (){
                                this.classList.remove('active');
                            });
                        }
                    });
                }
            }, 500);

            var displayValNode, displayValObj;

            if(options.customValue) {
                displayValObj = new MWEditor.core.capsulatedField({
                    width: '40px',
                    props: {
                        className: (options.icon ? 'mdi-' + options.icon + ' ' : '') + 'mw-editor-select-display-value',
                        innerHTML: options.placeholder || ''
                    }
                });




                displayValObj.field.addEventListener('input', function (){
                    lscope._pauseDisplayValue = true;
                    lscope.select.trigger('change', { label: this.value, value: this.value });
                    setTimeout(function (){
                        lscope._pauseDisplayValue = false;
                    }, 78);
                });
                displayValNode = MWEditor.core.button({
                    props: {
                        className: (options.icon ? 'mdi-' + options.icon + ' ' : '') + 'mw-editor-select-display-value',
                        // innerHTML: options.placeholder || ''
                    }
                });
                displayValNode.append(displayValObj.frame);

            } else {
                displayValNode = MWEditor.core.button({
                    props: {
                        className: (options.icon ? 'mdi-' + options.icon + ' ' : '') + 'mw-editor-select-display-value',
                        innerHTML: '<span class="mw-editor-select-display-value-content">' + (options.placeholder || '') + '</span>'
                    }
                });
            }

            var valueHolder = MWEditor.core.element({
                props: {
                    className: 'mw-editor-controller-component-select-values-holder',

                }
            });
            this.root.value = function (val){
                this.displayValue(val.label);
                this.value(val.value);
                lscope.optionsNodes.forEach(function (opt){
                    if(opt.value === val.value){
                        opt.addClass('active')
                    } else {
                        opt.removeClass('active')
                    }
                });
            };

            this._pauseDisplayValue = false;
            this.root.displayValue = function (val) {

                if( !lscope._pauseDisplayValue) {
                    if(options.customValue) {
                        displayValObj.field.value = (val || options.placeholder || '');
                    } else {

                        displayValNode.get(0).firstElementChild.textContent = (val || options.placeholder || '');
                    }
                }
                var num = parseFloat(val);
                var isNumberLike = !isNaN(num);
                lscope.optionsNodes.forEach(function (opt){

                    var label = decodeURIComponent(opt.get(0).dataset.label);



                    if(label === val){
                        opt.addClass('mw-editor-dropdown-option-active');
                    } else {
                        if(isNumberLike && label == num) {
                            opt.addClass('mw-editor-dropdown-option-active');

                        } else {

                            opt.removeClass('mw-editor-dropdown-option-active');

                        }

                    }

                });

            };

            this.optionsNodes = [];

            this.setData = function (data){
                this.select.valueHolder.empty();
                this.optionsNodes = [];
                for (var i = 0; i < data.length; i++) {
                    (function (dt){
                        var opt = MWEditor.core._dropdownOption(dt, options.eachOption);
                        opt.on('click', function (){
                            lscope.select.trigger('change', dt);
                        });
                        valueHolder.append(opt);
                        lscope.optionsNodes.push(opt);
                    })(data[i]);
                }
            };

            this.select.append(displayValNode);
            this.select.append(valueHolder);
            this.select.valueHolder = valueHolder;

            this.setData(options.data);

            var curr = lscope.select.get(0);
            var _handleClick = function (_this){

                var wrapper = mw.tools.firstParentWithClass(_this, 'mw-editor-wrapper');
                if (wrapper) {
                    var edOff = wrapper.getBoundingClientRect();
                    var selOff = _this.getBoundingClientRect();
                    lscope.select.valueHolder.css({
                        maxHeight: edOff.height - (selOff.top - edOff.top)
                    });
                }

                mw.element('.mw-editor-controller-component-select').each(function (){
                    if (this !== curr ) {
                        this.classList.remove('active');
                    }
                });
                mw.element(_this).toggleClass('active');
            };
            this.select.on('click', function (e) {
                e.stopPropagation();
                _handleClick(this);
            });
            if(options.customValue) {
                displayValObj.field.addEventListener('focus', function (){
                    _handleClick(displayValObj.frame.parentNode);
                });
            }
            this.root.append(this.select);
        },
        _preSelect: function (node) {
            var all = document.querySelectorAll('.mw-editor-controller-component-select.active, .mw-bar-control-item-group.active');
            var parent = mw.tools.firstParentOrCurrentWithAnyOfClasses(node ? node.parentNode : null, ['mw-editor-controller-component-select','mw-bar-control-item-group']);
            var i = 0, l = all.length;
            for ( ; i < l; i++) {
                if(!node || (all[i] !== node && all[i] !== parent)) {
                    all[i].classList.remove('active');
                }
            }
        }
    }

})();
