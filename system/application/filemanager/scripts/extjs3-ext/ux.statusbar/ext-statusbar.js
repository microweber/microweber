Ext.namespace('Ext.ux');

Ext.ux.StatusBar = Ext.extend(Ext.Toolbar, {
    textId: '',
    defaultText: '',
    autoClear: 5000,
    task: null,
    initComponent: function() {
        this.textId = Ext.id();
        this.defaultText = this.initialConfig.defaultText || '';
        var text = this.initialConfig.text || this.defaultText;
        
        var config = {
            items: [
                '<span id="'+this.textId+'">'+text+'</span>',    // status text
                '->'                            // make it greedy
            ]
        };
        if (this.initialConfig.items) {
            config.items = config.items.concat(this.initialConfig.items);
            delete this.initialConfig.items;
        }
        Ext.apply(this, Ext.apply(this.initialConfig, config));
        Ext.ux.StatusBar.superclass.initComponent.apply(this, arguments);
        this.task = new Ext.util.DelayedTask(function() {
            var el = Ext.get(this.textId);
            var defaultText = this.defaultText;
            el.fadeOut({
                callback: function() {
                    el.update(defaultText);
                    el.show();
                },
                duration: 1
            });
        }, this);
    },
    onRender: function() {
        Ext.ux.StatusBar.superclass.onRender.apply(this, arguments);
    },
    setText: function(text) {
        var el = Ext.get(this.textId);
        el.update(text);
    },
    setStatus: function(config) {
        var defaults = {
            clear: {
                wait: this.autoClear,
                anim: true,
                useDefaults: true
            }
        };
        
        if (config.clear === true) {
            delete config.clear;
        }
        if (!Ext.isArray(config)) {
            config = {
                text: config.text || ''
            }
        }
        Ext.apply(config, defaults);
        var el = Ext.get(this.textId);
        el.update(config.text);
        var clear = config.clear;
        var defaultText = this.defaultText;
        if (clear.wait) {
            this.task.delay(clear.wait);
        }
        else {
            this.task.cancel();
        }
    },
    clearStatus: function() {
        this.setText(this.defaultText);
        this.task.cancel();
    },
    showBusy: function(msg) {
        // stub for now
    }
});