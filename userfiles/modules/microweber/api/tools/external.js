    mw.tools.externalInstrument = {
        register: {},
        holder: function () {
            var div = mwd.createElement('div');
            div.className = 'mw-external-tool';
            return div;
        },
        prepare: function (name, params) {
            var frame = mwd.createElement('iframe');
            frame.name = name;
            /* for callbacks */
            var url = mw.external_tool(name);
            if (typeof params === 'object') {
                params = $.param(params);
            }
            else {
                params = "";
            }
            frame.src = url + "?" + params;
            frame.scrolling = 'no';
            frame.frameBorder = 0;
            frame.onload = function () {
                frame.contentWindow.thisframe = frame;
            };
            return frame;
        },
        init: function (name, callback, holder, params) {
            if (typeof mw.tools.externalInstrument.register[name] === 'undefined') {
                var frame = mw.tools.externalInstrument.prepare(name, params);
                frame.height = 300;
                mw.tools.externalInstrument.register[name] = frame;
                if (!holder) {
                    holder = mw.tools.externalInstrument.holder();
                    mw.$(mwd.body).append(holder);
                }
                mw.$(holder).append(frame);
            }
            else {
                mw.$(mw.tools.externalInstrument.register[name]).unbind('change');
            }
            mw.$(mw.tools.externalInstrument.register[name]).bind('change', function () {
                Array.prototype.shift.apply(arguments);
                callback.apply(this, arguments);
            });
            return mw.tools.externalInstrument.register[name];
        }
    };

    mw.tools.external = function (name, callback, holder, params) {
        return mw.tools.externalInstrument.init(name, callback, holder, params);
    };

    mw.tools._external = function (o) {
        return mw.tools.external(o.name, o.callback, o.holder, o.params);
    };

    mw.component = function (options) {
        return new mw.Component(options);
    };

    mw.Component = function (options) {

        options = options || {};
        var scope = this;

        var defaults = {
            mode: 'iframe',
            value: null,
            options: {},
            title: mw.lang('Settings')
        };

        this.settings = $.extend({}, defaults, options);

        this.value = function (data) {
          if(typeof data === 'undefined'){
              return this._value;
          }
          this._value = data;
          mw.tools[!!scope.value() ? 'removeClass' : 'addClass'](scope.btnok, 'disabled');

          if(this.container
              && this.container.contentWindow
              && this.container.contentWindow.mw
              && this.container.contentWindow.mw.ComponentInput){
              this.container.contentWindow.mw.ComponentInput(data);
          }
        };

        this.config = function (options) {
            if(this.container
                && this.container.contentWindow
                && this.container.contentWindow.mw
                && this.container.contentWindow.mw.ComponentConfig){
                this.container.contentWindow.mw.ComponentConfig(options);
            }
        };

        if(!this.settings.url) {
            return;
        }

        this.createIframe = function () {
            if(this.settings.mode === 'iframe') {
                this.container = document.createElement('iframe');
                this.container.allow = 'accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture';
                this.container.allowFullscreen = true;
                this.container.scrolling = "no";
                this.container.width = "100%";
                this.container.frameBorder = "0";
                this.container.src = mw.external_tool(this.settings.url);
                this.container.onload = function () {
                    scope.config(scope.settings.options);
                    scope.value(scope.settings.value);
                    if(this.contentWindow.mw && this.contentWindow.mw.trigger){
                        this.contentWindow.mw.ComponentOutput = function(data) {
                            scope._value = data;
                            mw.tools[!!scope.value() ? 'removeClass' : 'addClass'](scope.btnok, 'disabled');
                            $(scope).trigger('ValueChange', data);
                        };
                        this.contentWindow.mw.trigger('ComponentReady');
                    }
                };
                mw.tools.iframeAutoHeight(this.container);
            }
        };


        this.create = function () {
            if(this.settings.mode === 'iframe') {
                this.createIframe();
            }
            var footer = document.createElement('div');
            scope.btnok = document.createElement('span');
            var cancel = document.createElement('span');
            scope.btnok.className = 'mw-ui-btn mw-ui-btn-medium mw-ui-btn-info';
            scope.btnok.innerHTML = mw.lang('OK');
            mw.tools.addClass(scope.btnok, 'disabled');
            cancel.innerHTML = mw.lang('Cancel');
            cancel.className = 'mw-ui-btn mw-ui-btn-medium';
            footer.appendChild(cancel);
            footer.appendChild(scope.btnok);
            this.dialog = mw.top().dialog({ width: this.settings.width || 870, footer: footer, title: this.settings.title });
            this.dialog.dialogContainer.appendChild(this.container);
            cancel.onclick = function (e) {
                e.preventDefault();
                scope.dialog.remove();
            };
            scope.btnok.onclick = function (e) {
                e.preventDefault();
                if(scope.value()) {
                    $(scope).trigger('Result', scope.value());
                    scope.dialog.remove();
                }

            };
        };

        this.create();
    };
