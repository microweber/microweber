class MWEditorButton extends HTMLButtonElement {

    #isDisabled = false;
    constructor() {
        console.log(this);
        super();
    }

    get disabled() {
        console.log(this);
        return this.#isDisabled;
    }
    set disabled(state) {
        console.log(this);
        this.#isDisabled = state;
        this.setAttribute('disabled', state);
    }
}


customElements.define("mw-editor-button", MWEditorButton, { extends: "button" });



MWEditor.core = {
    button: function(config) {
        config = config || {};
        var defaults = {
            tag: 'mw-editor-button',
            props: {
                className: 'mdi mw-editor-controller-component mw-editor-controller-button',
                type: 'button',
                is: "mw-editor-button"
            }
        };
        if (config.props && config.props.className){
            config.props.className = defaults.props.className + ' ' + config.props.className;
        }
        var settings = $.extend(true, {}, defaults, config);
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

        var el = MWEditor.core.button(settings);
        el.addClass('mw-editor-color-picker');
        mw.colorPicker({
            element: el.get(0),
            position: 'bottom-center',
            onchange: function (color) {

                el.trigger('change', color);
            }
        });

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
    dropdown: function (options) {
        var lscope = this;
        this.root = MWEditor.core.element();
        this.select = MWEditor.core.element({
            props: {
                className: 'mw-editor-controller-component mw-editor-controller-component-select',
                tooltip: options.placeholder || null
            }
        });
        var displayValNode = MWEditor.core.button({
            props: {
                className: (options.icon ? 'mdi-' + options.icon + ' ' : '') + 'mw-editor-select-display-value',
                innerHTML: options.placeholder || ''
            }
        });

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
            displayValNode.text(val || options.placeholder || '');
        };

        this.select.append(displayValNode);
        this.select.append(valueHolder);
        this.select.valueHolder = valueHolder;
        for (var i = 0; i < options.data.length; i++) {
            var dt = options.data[i];
            (function (dt){
                var opt = MWEditor.core._dropdownOption(dt);
                opt.on('click', function (){
                    lscope.select.trigger('change', dt);
                });
                valueHolder.append(opt);
            })(dt);

        }
        var curr = lscope.select.get(0);
        this.select.on('click', function (e) {
            e.stopPropagation();
            var wrapper = mw.tools.firstParentWithClass(this, 'mw-editor-wrapper');
            if (wrapper) {
                var edOff = wrapper.getBoundingClientRect();
                var selOff = this.getBoundingClientRect();
                lscope.select.valueHolder.css({
                    maxHeight: edOff.height - (selOff.top - edOff.top)
                });
            }

            mw.element('.mw-editor-controller-component-select').each(function (){
                if (this !== curr ) {
                    this.classList.remove('active');
                }
            });
            mw.element(this).toggleClass('active');
        });
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
};
