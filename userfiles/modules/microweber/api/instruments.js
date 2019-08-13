mw.instruments = {
    _create:function (config) {
        config = config || {};
        var settings = $.extend({}, config);
        var frame = document.createElement('iframe');
        frame.src = mw.external_tool(settings.url);
        frame.className = 'mw-instrument-frame';
        frame.frameBorder = '0';
        $(frame).css(settings.css || {width: '100%'}).on('load', function () {
            this.contentWindow.thismodal = this;
            if(!settings.css || !settings.css.height) {
                mw.tools.iframeAutoHeight(this);
            }
        });
        return frame;
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
            window: window,
        };
        this.handler(id, final);
        $(frame).on('load', function(url, target, text){
            this.contentWindow.mw.instrumentData = final;
        });
        return final;
    }
};
