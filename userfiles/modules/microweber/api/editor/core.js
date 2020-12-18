mw.Editor.core = {
    button: function(config) {
        config = config || {};
        var defaults = {
            tag: 'button',
            props: {
                className: 'mdi mw-editor-controller-component mw-editor-controller-button mw-ui-btn mw-ui-btn-medium'
            }
        };
        if (config.props && config.props.className){
            config.props.className = defaults.props.className + ' ' + config.props.className;
        }
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
            size: 'medium',
            placeholder: options.placeholder
        });
        this.root.$select = this.select;
        this.root.$node.on('mousedown touchstart', function (e) {
            e.preventDefault();
        });
    },
}
