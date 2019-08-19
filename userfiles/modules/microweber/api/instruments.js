mw.instruments = {
    _create:function (config) {
        config = config || {};
        var settings = $.extend({}, config);
        var frame = document.createElement('iframe');
        frame.src = mw.external_tool(settings.url);
        frame.className = 'mw-instrument-frame';
        frame.frameBorder = '0';
        $(frame).css(settings.css || {width: '100%'}).on('load', function () {
            this.contentWindow.thisframe= this;
            if(!settings.css || !settings.css.height) {
                mw.tools.iframeAutoHeight(this);
            }
        });
        return frame;
    },
    _run: {},
    run: function(config){
        if(!config || !config.instrument || !config.id) {
            return;
        }
        config.show = typeof config.show === 'undefined' ? true : config.show;
        config.width = config.width || 600;
        if(!this._run[config.id]){
            var inst = this[config.instrument]({
                mode: 'inline'
            });
            var div = $('<div id="'+config.id+'" />');
            div.hide();
            $(document.body).append(div);
            div.append(inst.frame);
            this._run[config.id] = inst;
        }
        if (config.show) {
            mw.$('#' + config.id).mwDialog({
                //id: 'mw-instrument-dialog-' + config.id,
                width: config.width
            });
        }
        return this._run[config.id];
    },
    createMode: function(mode, url, id){
        var frame, dialog;
        if(mode === 'inline'){
            frame = this._create({
                url: 'link_editor'
            });
        } else if(mode === 'dialog') {
            var exists = mw.dialog.get('#' + id.replace('#', ''));
            if(exists){
                exists.show();
                return {
                    dialog: exists,
                    frame: exists.iframe
                };
            }
            dialog = mw.dialogIframe({
                url: url,
                height: 'auto',
                autoHeight: true
            });
            frame = dialog.iframe;
        } else {
            return {};
        }

        return {
            frame: frame,
            dialog: dialog
        };
    },
    _handlers: {},
    handler: function(id, val){
        if(!val) {
            return this._handlers[id];
        }
        this._handlers[id] = val;
    },
    link: function(config){
        config = config || {};
        var defaults = {
            mode: 'dialog' // 'dialog', 'inline'
        };
        var settings = $.extend({}, defaults, config);
        var frame, dialog;
        if(settings.mode === 'inline'){
            frame = this._create({
                url: 'link_editor'
            });
        } else if(settings.mode === 'dialog') {
          dialog = mw.dialogIframe({
              url:' rte_link_editor',
              height: 'auto',
              autoHeight: true
          });
          frame = dialog.iframe;
        } else {
            return {};
        }
        var id = mw.id();
        var final = {
            frame: frame,
            dialog: dialog,
            handler: $({}),
            id: id,
            window: window
        };
        this.handler(id, final);
        $(frame).on('load', function(url, target, text){
            this.contentWindow.mw.instrumentData = final;
        });
        return final;
    },
    file: function(config){
        config = config || {};
        var defaults = {
            mode: 'dialog' // 'dialog', 'inline'
        };
        var settings = $.extend({}, defaults, config);
        var frame, dialog;
        if(settings.mode === 'inline'){
            frame = this._create({
                url: 'file_picker'
            });
        } else if(settings.mode === 'dialog') {
            dialog = mw.dialogIframe({
                url:' file_picker',
                height: 'auto',
                autoHeight: true
            });
            frame = dialog.iframe;
        } else {
            return {};
        }
        var id = mw.id();
        var final = {
            frame: frame,
            dialog: dialog,
            handler: $({}),
            id: id,
            window: window
        };
        this.handler(id, final);
        $(frame).on('load', function(url, target, text){
            this.contentWindow.mw.instrumentData = final;
        });
        return final;
    }
};
