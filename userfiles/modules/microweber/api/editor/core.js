import {ObjectService} from "../classes/object.service";
import {ElementManager} from "../classes/element";
import {DomService} from "../classes/dom";
MWEditor.core = {
    button: function(config) {
        config = config || {};
        var defaults = {
            tag: 'button',
            props: {
                className: 'mdi mw-editor-controller-component mw-editor-controller-button',
                type: 'button'
            }
        };
        if (config.props && config.props.className){
            config.props.className = defaults.props.className + ' ' + config.props.className;
        }
        var settings = ObjectService.extend(true, {}, defaults, config);
        return ElementManager(settings);
    },
    field: function(config) {
        config = config || {};
        var defaults = {
            tag: 'input',
            props: {
                className: 'mdi mw-editor-controller-component mw-editor-controller-field',
                type: config.type || 'text'
            }
        };
        if (config.props && config.props.className){
            config.props.className = defaults.props.className + ' ' + config.props.className;
        }
        var settings = ObjectService.extend(true, {}, defaults, config);
        return ElementManager(settings);
    },
    colorPicker: function(config) {
        config = config || {};
        var defaults = {
            props: {
                className: 'mw-editor-controller-component'
            }
        };
        var settings = ObjectService.extend(true, {}, defaults, config);

        var el = MWEditor.core.button(settings);
        el.addClass('mw-editor-color-picker')
        var input = ElementManager({
            tag: 'input',
            props: {
                type: 'color',
                className: 'mw-editor-color-picker-node'
            }
        });
        var time = null;
        input.on('input', function (){
            clearTimeout(time);
            time = setTimeout(function (el, node){
                console.log(node.value)
                el.trigger('change', node.value);
            }, 210, el, this);
        });
        el.append(input);
        return el;
    },
    element: function(config) {
        config = config || {};
        var defaults = {
            props: {
                className: 'mw-editor-controller-component'
            }
        };
        var settings = ObjectService.extend(true, {}, defaults, config);
        var el = ElementManager(settings);
        el.on('mousedown touchstart', function (e) {
            var tg = e.target.nodeName;

            var shouldPrevent = tg !== 'INPUT';
            if( shouldPrevent ) {
                e.preventDefault();
            }
        });
        return el;
    },

    _dropdownOption: function (data) {
        // data: { label: string, value: any },
        var option = MWEditor.core.element({
            props: {
                className: 'mw-editor-dropdown-option',
                innerHTML: data.label
            }
        });
        option.on('mousedown touchstart', function (e) {
            e.preventDefault();
        });
        option.value = data.value;
        return option;
    },
    number: function (options) {
        this.root = MWEditor.core.element({
            props: {
                className: 'mw-editor-controller-component mw-editor-controller-component-number',
                tooltip: options.placeholder || null
            }
        });
        var scope = this;


        var _e = {};

        this.on = function (e, f) { _e[e] ? _e[e].push(f) : (_e[e] = [f]) };
        this.dispatch = function (e, f) { _e[e] ? _e[e].forEach(function (c){ c.call(this, f); }) : ''; };


        var valueNode = MWEditor.core.element({
            tag: 'input',
            props: {
                type: 'number',
                className: 'mw-editor-controller-component-number-value',
                min: options.min || 1,
                max: options.max || 1000,

            }
        });
        valueNode.on('input', function (){
            scope.dispatch('change', scope.value())
        })
        this.value = function (val) {
            if(typeof val === 'undefined' || val === null) {
                return valueNode.get(0).value;
            }
            valueNode.get(0).value = val;
        }
        var minus = MWEditor.core.element({
            props: {
                className: 'mw-editor-controller-component-number-minus',

            }
        }).on('mousedown touchstart', function (){
            valueNode.get(0).value = parseFloat(valueNode.get(0).value) - 1
            scope.dispatch('change', scope.value())
        });
        var plus = MWEditor.core.element({
            props: {
                className: 'mw-editor-controller-component-number-plus',

            }
        }).on('mousedown touchstart', function (){

            valueNode.get(0).value =  parseFloat(valueNode.get(0).value) + 1
            scope.dispatch('change', scope.value())
        });
        this.root.append(minus)
        this.root.append(valueNode)
        this.root.append(plus)
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
        if(!options.displayMode) {
            options.displayMode = 'button';
        }
        var displayValNode
        if(options.displayMode === "button") {
            displayValNode = MWEditor.core.button({
                props: {
                    className: (options.icon ? 'mdi-' + options.icon + ' ' : '') + 'mw-editor-select-display-value',
                    innerHTML: options.placeholder || ''
                }
            });
        } else if(options.displayMode === "field") {
            displayValNode = MWEditor.core.field({
                props: {
                    className: (options.icon ? 'mdi-' + options.icon + ' ' : '') + 'mw-editor-select-display-value-field',
                    placeholder: options.placeholder || ''
                }
            });
            setTimeout(function (){
                displayValNode.wrap({
                    props: {
                        className: 'mw-editor-select-display-value'
                    }
                })
            }, 10)
        }


        var valueHolder = MWEditor.core.element({
            props: {
                className: 'mw-editor-controller-component-select-values-holder',

            }
        });
        this.root.value = function (val){
            this.displayValue(val.label);
            this.value(val.value);
        };

        this.root.displayValue = function (val) {
            var md;
            if(options.displayMode === "button") {
                md = 'html';

            } else if(options.displayMode === "field") {
                md = 'val';
            }
            displayValNode[md](val || options.placeholder || '');
        };

        this.select.append(displayValNode);
        this.select.append(valueHolder);
        this.select.valueHolder = valueHolder;
        this.options = [];
        for (var i = 0; i < options.data.length; i++) {
            var dt = options.data[i];
            (function (dt){
                var opt = MWEditor.core._dropdownOption(dt);
                opt.on('click', function (){
                    lscope.select.trigger('change', dt);
                });
                lscope.options.push({
                    element: opt,
                    data: dt
                })
                valueHolder.append(opt);
            })(dt);

        }
        var curr = lscope.select.get(0);
        this.select.on('click', function (e) {
            e.stopPropagation();
            var wrapper = DomService.firstParentOrCurrentWithClass(this, 'mw-editor-wrapper');
            if (wrapper) {
                var edOff = wrapper.getBoundingClientRect();
                var selOff = this.getBoundingClientRect();
                lscope.select.valueHolder.css({
                    maxHeight: edOff.height - (selOff.top - edOff.top)
                });
            }

            ElementManager('.mw-editor-controller-component-select').each(function (){
                if (this !== curr ) {
                    this.classList.remove('active');
                }
            });
            ElementManager(this).toggleClass('active');
        });
        this.root.append(this.select);
    },
    _preSelect: function (node) {
        var all = document.querySelectorAll('.mw-editor-controller-component-select.active, .mw-bar-control-item-group.active');
        var parent = DomService.firstParentOrCurrentWithAnyOfClasses(node ? node.parentNode : null, ['mw-editor-controller-component-select','mw-bar-control-item-group']);
        var i = 0, l = all.length;
        for ( ; i < l; i++) {
            if(!node || (all[i] !== node && all[i] !== parent)) {
                all[i].classList.remove('active');
            }
        }
    }
};
