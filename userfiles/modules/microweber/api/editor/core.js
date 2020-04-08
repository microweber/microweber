mw.Editor.core = {
    button: function(config) {
        config = config || {};
        var defaults = {
            tag: 'button',
            props: {
                className: 'mw-editor-controller-component mw-editor-controller-button mw-ui-btn'
            }
        };
        var settings = $.extend(true, {}, defaults, config);
        return mw.element(settings);
    },
    element: function(config) {
        config = config || {};
        var defaults = {
            props: {
                className: 'mw-editor-controller-component'
            }
        };
        var settings = $.extend(true, {}, defaults, config);
        return mw.element(settings);
    },
    dropdown: function (options) {
        this.root = mw.Editor.core.element();
        this.select = mw.select({
            element: this.root.node,
            multiple: false,
            autocomplete: false,
            tags: false,
            data: options.data,
            placeholder: options.placeholder
        });
        this.root.$select = this.select;
        this.root.$node.on('mousedown touchstart', function (e) {
            e.preventDefault();
        });
    },
    deprecated$dropdown: function (options) {
        /*
        * data: [
        *   {label: string, value: any}
        * ]
        * */

        options = options || {};
        var scope = this;

        if (!options.data) {
            options.data = [];
        }

        var defaults = {
            placeholder: mw.lang('Select')
        };

        this.settings = $.extend({}, defaults, options);

        this.createNodes = function () {
            this.rootNode = mw.element({
                tag: 'div',
                props: { className: 'mw-dropdown mw-dropdown-default' }
            });
            this.contentNode = mw.element({
                tag: 'div',
                props: { className: 'mw-dropdown-content' }
            });

            this.listNode = mw.element({
                tag: 'ul'
            });

            this.valueNode = mw.element({
                tag: 'span',
                props: { className: 'mw-dropdown-value mw-ui-btn mw-dropdown-val', innerHTML: this.settings.placeholder }
            });
            this.contentNode.node.appendChild(this.listNode.node);
            this.rootNode.node.appendChild(this.valueNode.node);
            this.rootNode.node.appendChild(this.contentNode.node);
            this.rootNode.$node.on('mousedown touchstart', function (e) {
                e.preventDefault()
            });
        };

        this.nodes = function () {
            for (var i = 0; i < this.settings.data.length; i++) {
                var item = this.settings.data[i];
                var li = mw.element({
                    tag: 'li',
                    props: { innerHTML: item.label  }
                });
                li.$node.attr('value', item.value)
                this.listNode.node.appendChild(li.node);
            }
        };

        this.getAsItem = function (item) {
            var all = this.settings.data;
            if(all.indexOf(item) !== -1) {
                return item;
            }
            for (var i = 0; i<all.length; i++){
                if( item.value && all[i].value === item.value ) {
                    return all[i];
                } else if (item === all[i].value) {
                    return all[i];
                }
            }
        };

        this.value = function (val) {
            if(typeof val === 'undefined') {
                return this._value;
            }
            var item = this.getAsItem(val);
            if(item) {
                this.valueNode.node.innerHTML = item.value;
                this._value = item;
            }

        };


        this.init = function () {
            this.createNodes();
            this.nodes();
            mw.dropdown()
        };

        this.init()

    }
}
