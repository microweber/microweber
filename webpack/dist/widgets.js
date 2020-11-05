/******/ (() => { // webpackBootstrap
(() => {
/*!*******************************************************************!*\
  !*** ../userfiles/modules/microweber/api/widgets/autocomplete.js ***!
  \*******************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.autoComplete = function(options){
    var scope = this;
    this.prepare = function(options){
        options = options || {};
        if(!options.data && !options.ajaxConfig) return;
        var defaults = {
            size:'normal',
            multiple:false,
            map: { title:'title', value:'id' },
            titleDecorator: function (title, data) {
                return title;
            }
        };
        this.options = $.extend({}, defaults, options);
        this.options.element = mw.$(this.options.element)[0];
        if(!this.options.element){
            return;
        }
        this.element = this.options.element;
        this.data = this.options.data;
        this.searchTime = null;
        this.searchTimeout = this.options.data ? 0 : 500;
        this.results = [];
        this.map = this.options.map;
        this.selected = this.options.selected || [];
    };

    this.createValueHolder = function(){
        this.valueHolder = document.createElement('div');
        this.valueHolder.className = 'mw-autocomplete-value';
        return this.valueHolder;
    };
    this.createListHolder = function(){
        this.listHolder = document.createElement('ul');
        this.listHolder.className = 'mw-ui-box mw-ui-navigation mw-autocomplete-list';
        this.listHolder.style.display = 'none';
        return this.listHolder;
    };

    this.createWrapper = function(){
        this.wrapper = document.createElement('div');
        this.wrapper.className = 'mw-ui-field w100 mw-autocomplete mw-autocomplete-multiple-' + this.options.multiple;
        return this.wrapper;
    };

    this.createField = function(){
        this.inputField = document.createElement('input');
        this.inputField.className = 'mw-ui-invisible-field mw-autocomplete-field mw-ui-field-' + this.options.size;
        if(this.options.placeholder){
            this.inputField.placeholder = this.options.placeholder;
        }
        mw.$(this.inputField).on('input focus', function(e){
            var val = e.type === 'focus' ? '' : this.value;
            scope.search(val);
        });
        return this.inputField;
    };

    this.buildUI = function(){
        this.createWrapper();
        this.wrapper.appendChild(this.createValueHolder());
        this.wrapper.appendChild(this.createField());
        this.wrapper.appendChild(this.createListHolder());
        this.element.appendChild(this.wrapper);
    };

    this.createListItem = function(data){
        var li = document.createElement('li');
        li.value = this.dataValue(data);
        var img = this.dataImage(data);

        mw.$(li)
        .append( '<a href="javascript:;">'+this.dataTitle(data)+'</a>' )
        .on('click', function(){
            scope.select(data);
        });
        if(img){
            mw.$('a',li).prepend(img);
        }
        return li;
    };

    this.uniqueValue = function(){
        var uniqueIds = [], final = [];
        this.selected.forEach(function(item){
            var val = scope.dataValue(item);
            if(uniqueIds.indexOf(val) === -1){
                uniqueIds.push(val);
                final.push(item);
            }
        });
        this.selected = final;
    };

    this.select = function(item){
        if(this.options.multiple){
            this.selected.push(item);
        }
        else{
            this.selected = [item];
        }
        this.rendSelected();
        if(!this.options.multiple){
            this.listHolder.style.display = 'none';
        }
        mw.$(this).trigger('change', [this.selected]);
    };

    this.rendSingle = function(){
        var item = this.selected[0];
        this.inputField.value = item ? item[this.map.title] : '';
        this.valueHolder.innerHTML = '';
        var img = this.dataImage(item);
        if(img){
            this.valueHolder.appendChild(img);
        }

    };

    this.rendSelected = function(){
        if(this.options.multiple){
            this.uniqueValue();
            this.chips.setData(this.selected);
        }
        else{
            this.rendSingle();
        }
    };

    this.rendResults = function(){
        mw.$(this.listHolder).empty().show();
        $.each(this.results, function(){
            scope.listHolder.appendChild(scope.createListItem(this));
        });
    };

    this.dataValue = function(data){
        if(!data) return;
        if(typeof data === 'string'){
            return data;
        }
        else{
            return data[this.map.value];
        }
    };
    this.dataImage = function(data){
        if(!data) return;
        if(data.picture){
            data.image = data.picture;
        }
        if(data.image){
            var img = document.createElement('span');
            img.className = 'mw-autocomplete-img';
            img.style.backgroundImage = 'url(' + data.image + ')';
            return img;
        }
    };
    this.dataTitle = function(data){
        if (!data) return;
        var title;
        if (typeof data === 'string') {
            title = data;
        }
        else {
            title = data[this.map.title];
        }

        return this.options.titleDecorator(title, data);
    };

    this.searchRemote = function(val){
        var config = mw.tools.cloneObject(this.options.ajaxConfig);

        if(config.data){
            if(typeof config.data === 'string'){
                config.data = config.data.replace('${val}',val);
            }
            else{
               $.each(config.data, function(key,value){

                    if(value.indexOf && value.indexOf('${val}') !==-1 ){
                        config.data[key] = value.replace('${val}', val);
                    }
               });
            }
        }
        if(config.url){
            config.url = config.url.replace('${val}',val);
        }
        var xhr = $.ajax(config);
        xhr.done(function(data){
            if(data.data){
                scope.data = data.data;
            }
            else{
               scope.data = data;
            }
            scope.results = scope.data;
            scope.rendResults();
        })
        .always(function(){
            scope.searching = false;
        });
    };

    this.searchLocal = function(val){

        this.results = [];
        var toSearch;
        $.each(this.data, function(){
           if(typeof this === 'string'){
                toSearch = this.toLowerCase();
           }
           else{
               toSearch = this[scope.map.title].toLowerCase();
           }
           if(toSearch.indexOf(val) !== -1){
            scope.results.push(this);
           }
        });
       this.rendResults();
       scope.searching = false;
    };

    this.search = function(val){
        if(scope.searching) return;
        val = val || '';
        val = val.trim().toLowerCase();

        if(this.options.data){
            this.searchLocal(val);
        }
        else{
            clearTimeout(this.searchTime);
            setTimeout(function(){
                scope.searching = true;
                scope.searchRemote(val);
            }, this.searchTimeout);
        }
    };

    this.init = function(){
        this.prepare(options);
        this.buildUI();
        if(this.options.multiple){
            this.chips = new mw.chips({
                element:this.valueHolder,
                data:[]
            });
        }
        this.rendSelected();
        this.handleEvents();
    };

    this.handleEvents = function(){
        mw.$(document.body).on('click', function(e){
            if(!mw.tools.hasParentsWithClass(e.target, 'mw-autocomplete')){
                scope.listHolder.style.display = 'none';
            }
        });
    };


    this.init();

};

})();

(() => {
/*!*************************************************************!*\
  !*** ../userfiles/modules/microweber/api/widgets/common.js ***!
  \*************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
$(document).ready(function(){
    mw.$('.mw-lazy-load-module').reload_module();
});


$(document).ready(function(){

    mw.common['data-mw-close']();
    mw.$(mwd.body)
    .on('click', '[data-mw-dialog]', function(e){
        mw.common['data-mw-dialog'](e);
    })
    .on('click', '[data-mw-close]', function(e){
        mw.common['data-mw-close'](e);
    });
});

mw.common = {
    setOptions:function (el, options) {
        options = options || {};
        if(el.target){
            el = el.target;
        }
        var settings = el.getAttribute('data-mw-settings');
        try{
            settings = JSON.parse(settings);
        }
        catch(e){
            settings = {};
        }
        return $.extend(options, settings)

    },
    'data-mw-close':function(e){
        if(e && e.target){
            var data = e.target.getAttribute('data-mw-close');
            var cookie = JSON.parse(mw.cookie.get('data-mw-close') || '{}');
            mw.$(data).slideUp(function(){
                mw.$(this).remove();
                cookie[data] = true;
                mw.cookie.set('data-mw-close', JSON.stringify(cookie));
            })
        }
        else{
            var cookie =  JSON.parse(mw.cookie.get('data-mw-close') || '{}');
            mw.$('[data-mw-close]').each(function(){
                var data = this.getAttribute('data-mw-dialog');
                if(cookie[data]){
                    mw.$(data).remove();
                }
            })
        }
    },
    'data-mw-dialog':function(e){
        var skin = 'basic';
        var overlay = true;
        var data = e.target.getAttribute('data-mw-dialog');

        if(data){
            e.preventDefault();
            data = data.trim();
            var arr = data.split('.');
            var ext = arr[arr.length-1];
            if(data.indexOf('http') === 0){
                if(ext && /(gif|png|jpg|jpeg|bpm|tiff)$/i.test(ext)){
                    mw.image.preload(data, function(w,h){
                        var html = "<img src='"+data+"'>";
                        var conf = mw.common.setOptions(e, {
                            width:w,
                            height:h,
                            content:html,
                            template:skin,
                            overlay:overlay,
                            overlayRemovesModal:true
                        })
                        mw.dialog(conf);
                    });
                }
                else{
                    var conf = mw.common.setOptions(e, {
                        url:data,
                        width:'90%',
                        height:'auto%',
                        template:skin,
                        overlay:overlay,
                        overlayRemovesModal:true
                    })
                    mw.dialogIframe(conf)
                }
            }
            else if(data.indexOf('#') === 0 || data.indexOf('.') === 0){
                var conf = mw.common.setOptions(e, {
                    content:$(data)[0].outerHTML,
                    template:skin,
                    overlay:overlay,
                    overlayRemovesModal:true
                });
                mw.dialog(conf);
            }
        }
    }
}

})();

(() => {
/*!*************************************************************!*\
  !*** ../userfiles/modules/microweber/api/widgets/dialog.js ***!
  \*************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
(function (mw) {



    mw.dialog = function (options) {
        return new mw.Dialog(options);
    };


    mw.dialogIframe = function (options, cres) {
        options.pauseInit = true;
        var attr = 'frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen';
        if (options.autoHeight) {
            attr += ' scrolling="no"';
            options.height = 'auto';
        }
        options.content = '<iframe src="' + mw.external_tool(options.url.trim()) + '" ' + attr + '><iframe>';
        options.className = ('mw-dialog-iframe mw-dialog-iframe-loading ' + (options.className || '')).trim();
        options.className += (options.autoHeight ? ' mw-dialog-iframe-autoheight' : '');
        var dialog = new mw.Dialog(options, cres);
        dialog.iframe = dialog.dialogContainer.querySelector('iframe');
        mw.tools.loading(dialog.dialogContainer, 90);



        setTimeout(function () {
            var frame = dialog.dialogContainer.querySelector('iframe');
            frame.style.minHeight = 0; // reset in case of conflicts
            if (options.autoHeight) {
                mw.tools.iframeAutoHeight(frame, {dialog: dialog});
            } else{
                $(frame).height(options.height - 60);
                frame.style.position = 'relative';
            }
            mw.$(frame).on('load', function () {
                mw.tools.loading(dialog.dialogContainer, false);
                setTimeout(function () {
                    dialog.center();
                    mw.$(frame).on('bodyResize', function () {
                        dialog.center();
                    });
                    dialog.dialogMain.classList.remove('mw-dialog-iframe-loading');
                    frame.contentWindow.thismodal = dialog;
                    if (options.autoHeight) {
                        mw.tools.iframeAutoHeight(frame, {dialog: dialog});
                    }
                }, 78);
                if (mw.tools.canAccessIFrame(frame)) {
                    mw.$(frame.contentWindow.document).on('keydown', function (e) {
                        if (mw.event.is.escape(e) && !mw.event.targetIsField(e)) {
                            if(mw.top().__dialogs && mw.top().__dialogs.length){
                                var dlg = mw.top().__dialogs;
                                dlg[dlg.length - 1]._doCloseButton();
                                $(dlg[dlg.length - 1]).trigger('closedByUser');
                            }
                            else {
                                if (dialog.options.closeOnEscape) {
                                    dialog._doCloseButton();
                                    $(dialog).trigger('closedByUser');
                                }
                            }
                        }
                    });
                }
                if(typeof options.onload === 'function') {
                    options.onload.call(dialog);
                }
            });
        }, 12);
        return dialog;
    };

    /** @deprecated */
    mw.modal = mw.dialog;
    mw.modalFrame = mw.dialogIframe;

    mw.dialog.remove = function (selector) {
        return mw.dialog.get(selector).remove();
    };

    mw.dialog.get = function (selector) {
        var $el = mw.$(selector);
        var el = $el[0];

        if(!el) return false;

        if(el._dialog) {
            return el._dialog;
        }
        var child_cont = el.querySelector('.mw-dialog-holder');
        var parent_cont = $el.parents(".mw-dialog-holder:first");
        if (child_cont) {
            return child_cont._dialog;
        }
        else if (parent_cont.length !== 0) {
            return parent_cont[0]._dialog;
        }
        else if (window.thismodal) {
            return thismodal;
        }
        else {
             // deprecated
            child_cont = el.querySelector('.mw_modal');
            parent_cont = $el.parents(".mw_modal:first");
            if(child_cont) {
                return child_cont.modal;
            } else if (parent_cont.length !== 0) {
                return parent_cont[0].modal;
            }
            return false;
        }
    };


    mw.Dialog = function (options, cres) {

        var scope = this;

        options = options || {};
        options.content = options.content || options.html || '';

        if(!options.height && typeof options.autoHeight === 'undefined') {
            options.height = 'auto';
            options.autoHeight = true;
        }

        var defaults = {
            skin: 'default',
            overlay: true,
            overlayClose: false,
            autoCenter: true,
            root: document,
            id: mw.id('mw-dialog-'),
            content: '',
            closeOnEscape: true,
            closeButton: true,
            closeButtonAppendTo: '.mw-dialog-header',
            closeButtonAction: 'remove', // 'remove' | 'hide'
            draggable: true,
            scrollMode: 'inside', // 'inside' | 'window',
            centerMode: 'intuitive', // 'intuitive' | 'center'
            containment: 'window',
            overflowMode: 'auto', // 'auto' | 'hidden' | 'visible'
        };

        this.options = $.extend({}, defaults, options, {
            skin: 'default'
        });

        this.id = this.options.id;
        var exist = document.getElementById(this.id);
        if (exist) {
            return exist._dialog;
        }

        this.hasBeenCreated = function () {
            return this.options.root.getElementById(this.id) !== null;
        };

        if (this.hasBeenCreated()) {
            return this.options.root.getElementById(this.id)._dialog;
        }

        if(!mw.top().__dialogs ) {
            mw.top().__dialogs = [];
        }
        if (!mw.top().__dialogsData) {
            mw.top().__dialogsData = {};
        }


        if (!mw.top().__dialogsData._esc) {
            mw.top().__dialogsData._esc = true;
            mw.$(document).on('keydown', function (e) {
                if (mw.event.is.escape(e)) {
                    var dlg = mw.top().__dialogs[mw.top().__dialogs.length - 1];
                    if (dlg && dlg.options && dlg.options.closeOnEscape) {
                        dlg._doCloseButton();
                    }
                }
            });
        }

        mw.top().__dialogs.push(this);

        this.draggable = function () {
            if (this.options.draggable && $.fn.draggable) {
                var $holder = mw.$(this.dialogHolder);
                $holder.draggable({
                    handle: this.options.draggableHandle || '.mw-dialog-header',
                    start: function () {
                        $holder.addClass('mw-dialog-drag-start');
                        scope._dragged = true;
                    },
                    stop: function () {
                        $holder.removeClass('mw-dialog-drag-start');
                    },
                    containment: scope.options.containment,
                    iframeFix: true
                });
            }
        };

        this.header = function () {
            this.dialogHeader = this.options.root.createElement('div');
            this.dialogHeader.className = 'mw-dialog-header';
            if (this.options.title || this.options.header) {
                this.dialogHeader.innerHTML = '<div class="mw-dialog-title">' + (this.options.title || this.options.header) + '</div>';
            }
        };

        this.footer = function (content) {
            this.dialogFooter = this.options.root.createElement('div');
            this.dialogFooter.className = 'mw-dialog-footer';
            if (this.options.footer) {
                $(this.dialogFooter).append(this.options.footer);
            }
        };

        this.title = function (title) {
            var root = mw.$('.mw-dialog-title', this.dialogHeader);
            if (typeof title === 'undefined') {
                return root.html();
            } else {
                if (root[0]) {
                    root.html(title);
                }
                else {
                    mw.$(this.dialogHeader).prepend('<div class="mw-dialog-title">' + title + '</div>');
                }
            }
        };


        this.build = function () {
            this.dialogMain = this.options.root.createElement('div');

            this.dialogMain.id = this.id;
            var cls = 'mw-dialog mw-dialog-scroll-mode-' + this.options.scrollMode
                + ' mw-dialog-skin-' + this.options.skin
                + ' mw-dialog-overflowMode-' + this.options.overflowMode;
            cls += (!this.options.className ? '' : (' ' + this.options.className));
            this.dialogMain.className = cls;
            this.dialogMain._dialog = this;

            this.dialogHolder = this.options.root.createElement('div');
            this.dialogHolder.id = 'mw-dialog-holder-' + this.id;


            this.dialogHolder._dialog = this;

            this.header();
            this.footer();
            this.draggable();



            this.dialogContainer = this.options.root.createElement('div');
            this.dialogContainer._dialog = this;

            // TODO: obsolate
            this.container = this.dialogContainer;


            this.dialogContainer.className = 'mw-dialog-container';
            this.dialogHolder.className = 'mw-dialog-holder';

            var cont = this.options.content;
            if(this.options.shadow) {
                this.shadow = this.dialogContainer.attachShadow({
                    mode: 'open'
                });
                if(typeof cont === 'string') {
                    this.shadow.innerHTML = (cont);
                } else {
                    this.shadow.appendChild(cont);
                }
            } else {
                mw.$(this.dialogContainer).append(cont);
            }


            if (this.options.encapsulate) {
                this.iframe = cont;
                this.iframe.style.display = '';
            }

            this.dialogHolder.appendChild(this.dialogHeader);
            this.dialogHolder.appendChild(this.dialogContainer);
            this.dialogHolder.appendChild(this.dialogFooter);

            this.closeButton = this.options.root.createElement('div');
            this.closeButton.className = 'mw-dialog-close';

            this.closeButton.$scope = this;

            this.closeButton.onclick = function () {
                this.$scope[this.$scope.options.closeButtonAction]();
                $(this.$scope).trigger('closedByUser');
            };
            this.main = mw.$(this.dialogContainer); // obsolete
            this.main.width = this.width;

            this.width(this.options.width || 600);
            this.height(this.options.height || 320);

            this.options.root.body.appendChild(this.dialogMain);
            this.dialogMain.appendChild(this.dialogHolder);
            if (this.options.closeButtonAppendTo) {
                mw.$(this.options.closeButtonAppendTo, this.dialogMain).append(this.closeButton)
            }
            else {
                this.dialogHolder.appendChild(this.closeButton);

            }
            this.dialogOverlay();
            return this;
        };

        this._doCloseButton = function() {
            this[this.options.closeButtonAction]();
        };

        this.containmentManage = function () {
            if (scope.options.containment === 'window') {
                if (scope.options.scrollMode === 'inside') {
                    var rect = this.dialogHolder.getBoundingClientRect();
                    var $win = mw.$(window);
                    var sctop = $win.scrollTop();
                    var height = $win.height();
                    if (rect.top < sctop || (sctop + height) > (rect.top + rect.height)) {
                        this.center();
                    }
                }
            }
        };

        this.dialogOverlay = function () {
            this.overlay = this.options.root.createElement('div');
            this.overlay.className = 'mw-dialog-overlay';
            this.overlay.$scope = this;
            if (this.options.overlay === true) {
                this.dialogMain.appendChild(this.overlay);
            }
            mw.$(this.overlay).on('click', function () {
                if (this.$scope.options.overlayClose === true) {
                    this.$scope._doCloseButton();
                    $(this.$scope).trigger('closedByUser');
                }
            });

            return this;
        };

        this._afterSize = function() {
            if(mw._iframeDetector) {
                mw._iframeDetector.pause = true;
                var frame = window.frameElement;
                if(frame && parent !== top){
                    var height = this.dialogContainer.scrollHeight + this.dialogHeader.scrollHeight;
                    if($(frame).height() < height) {
                        frame.style.height = ((height + 100) - this.dialogHeader.offsetHeight - this.dialogFooter.offsetHeight) + 'px';
                        if(window.thismodal){
                            thismodal.height(height + 100);
                        }

                    }
                }
            }
        };

        this.show = function () {
            mw.$(this.dialogMain).find('iframe').each(function(){
                this._intPause = false;
            });
            mw.$(this.dialogMain).addClass('active');
            this.center();
            this._afterSize();
            mw.$(this).trigger('Show');
            mw.trigger('mwDialogShow', this);
            return this;
        };

        this._hideStart = false;
        this.hide = function () {
            if (!this._hideStart) {
                this._hideStart = true;
                mw.$(this.dialogMain).find('iframe').each(function(){
                    this._intPause = true;
                });
                setTimeout(function () {
                    scope._hideStart = false;
                }, 300);
                mw.$(this.dialogMain).removeClass('active');
                if(mw._iframeDetector) {
                    mw._iframeDetector.pause = false;
                }
                mw.$(this).trigger('Hide');
                mw.trigger('mwDialogHide', this);
            }
            return this;
        };

        this.remove = function () {
            this.hide();
            mw.removeInterval('iframe-' + this.id);
            mw.$(this).trigger('BeforeRemove');
            if (typeof this.options.beforeRemove === 'function') {
                this.options.beforeRemove.call(this, this)
            }
            mw.$(this.dialogMain).remove();
            if(this.options.onremove) {
                this.options.onremove()
            }
            mw.$(this).trigger('Remove');
            mw.trigger('mwDialogRemove', this);
            for (var i = 0; i < mw.top().__dialogs.length; i++) {
                if (mw.top().__dialogs[i] === this) {
                    mw.top().__dialogs.splice(i, 1);
                    break;
                }
            }
            return this;
        };

        this.destroy = this.remove;

        this._prevHeight = -1;
        this._dragged = false;

        this.center = function (width, height) {
            var $holder = mw.$(this.dialogHolder), $window = mw.$(window);
            var holderHeight = height || $holder.outerHeight();
            var holderWidth = width || $holder.outerWidth();
            var dtop, css = {};

            if (this.options.centerMode === 'intuitive' && this._prevHeight < holderHeight) {
                dtop = $window.height() / 2 - holderHeight / 2;
            } else if (this.options.centerMode === 'center') {
                dtop = $window.height() / 2 - holderHeight / 2;
            }

            if (!scope._dragged) {
                css.left = $window.outerWidth() / 2 - holderWidth / 2;
            } else {
                css.left = parseFloat($holder.css('left'));
            }

            if(css.left + holderWidth > $window.width()){
                css.left = css.left - ((css.left + holderWidth) - $window.width());
            }

            if (dtop) {
                css.top = dtop > 0 ? dtop : 0;
            }

            if(window !== mw.top().win && document.body.scrollHeight > mw.top().win.innerHeight){
                $win = $(mw.top());

                css.top = $(document).scrollTop() + 50;
                var off = $(window.frameElement).offset();
                if(off.top < 0) {
                    css.top += Math.abs(off.top);
                }
                if(window.thismodal) {
                    css.top += thismodal.dialogContainer.scrollTop;
                }

            }


            $holder.css(css);
            this._prevHeight = holderHeight;


            this._afterSize();
            mw.$(this).trigger('dialogCenter');

            return this;
        };

        this.width = function (width) {
            if(!width) {
                return mw.$(this.dialogHolder).outerWidth();
            }
            mw.$(this.dialogHolder).width(width);
            this._afterSize();
        };
        this.height = function (height) {
            if(!height) {
                return mw.$(this.dialogHolder).outerHeight();
            }
            mw.$(this.dialogHolder).height(height);
            this._afterSize();
        };
        this.resize = function (width, height) {
            if (typeof width !== 'undefined') {
                this.width(width);
            }
            if (typeof height !== 'undefined') {
                this.height(height);
            }
            this.center(width, height);
        };
        this.content = function (content) {
            this.options.content = content || '';
            $(this.dialogContainer).empty().append(this.options.content);
            return this;
        };

        this.result = function(result, doClose) {
            this.value = result;
            if(this.options.onResult){
                this.options.onResult.call( this, result );
            }
            if (cres) {
                cres.call( this, result );
            }
            $(this).trigger('Result', [result]);
            if(doClose){
                this._doCloseButton();
            }
        };


        this.contentMaxHeight = function () {
            var scope = this;
            if (this.options.scrollMode === 'inside') {
                mw.interval('iframe-' + this.id, function () {
                    var max = mw.$(window).height() - scope.dialogHeader.clientHeight - scope.dialogFooter.clientHeight - 40;
                    scope.dialogContainer.style.maxHeight = max + 'px';
                    scope.containmentManage();
                });
            }
        };
        this.init = function () {
            this.build();
            this.contentMaxHeight();
            this.center();
            this.show();
            if (this.options.autoCenter) {
                (function (scope) {
                    mw.$(window).on('resize orientationchange load', function () {
                        scope.contentMaxHeight();
                        scope.center();
                    });
                })(this);
            }
            if (!this.options.pauseInit) {
                mw.$(this).trigger('Init');
            }
            setTimeout(function(){
                scope.center();
                setTimeout(function(){
                    scope.center();
                    setTimeout(function(){
                        scope.center();
                    }, 3000);
                }, 333);
            }, 78);


            return this;
        };
        this.init();

    };

    mw.Dialog.elementIsInDialog = function (node) {
        return mw.tools.firstParentWithClass(node, 'mw-dialog');
    };




})(window.mw);


(function () {
    function scoped() {
        var all = document.querySelectorAll('style[scoped]'), i = 0;

        try {
            for( ; i < all.length; i++ ) {
                var parent = all[i].parentNode;
                parent.id = parent.id || mw.id('scoped-id-');
                var prefix = '#' + parent.id + ' ';
                var rules = all[i].sheet.rules;
                var r = 0;
                for ( ; r < rules.length; r++) {
                    var newRule = prefix + rules[r].cssText;
                    all[i].sheet.deleteRule(r);
                    all[i].sheet.insertRule(newRule, r);
                }
                all[i].removeAttribute('scoped');
            }
        }
        catch(error) {

        }


    }
    scoped();
    $(window).on('load', function () {
        scoped();
    });
})();



})();

(() => {
/*!**************************************************************!*\
  !*** ../userfiles/modules/microweber/api/widgets/gallery.js ***!
  \**************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
(function(){
    mw.require('modal.css');

    var Gallery = function (array, startFrom) {
        startFrom = startFrom || 0;

        this.currentIndex = startFrom;

        this.data = array;
        var scope = this;

        this._template = function () {
            var el = document.createElement('div');
            el.className = 'mw-gallery';
            el.innerHTML = '' +
            '<div class="">' +
                '<div class="mw-gallery-overlay"></div>' +
                '<div class="mw-gallery-content"></div>' +
                '<div class="mw-gallery-prev"></div>' +
                '<div class="mw-gallery-next"></div>' +
                '<div class="mw-gallery-controls">' +
                    '<span class="mw-gallery-control-play"></span>' +
                    /*'<span class="mw-gallery-control-fullscreen"></span>' +*/
                    '<span class="mw-gallery-control-close"></span>' +
                '</div>' +
            '</div>';
            return el;
        };

        this.createSingle = function (item, i) {
            var el = document.createElement('div');
            el.className = 'mw-gallery-item mw-gallery-item-' + i + (startFrom === i ? ' active' : '');
            var desc = !item.description ? '' : '<div class="mw-gallery-item-description">'+item.description+'</div>';
            el.innerHTML = '<div class="mw-gallery-item-image"><img src="'+(item.image || item.url || item.src)+'"></div>' + desc;
            this.container.appendChild(el);
            return el;
        };

        this.next = function () {
            this.currentIndex++;
            if(!this._items[this.currentIndex]) {
                this.currentIndex = 0;
            }
            this.goto(this.currentIndex);
        };

        this.prev = function () {
            this.currentIndex--;
            if(!this._items[this.currentIndex]) {
                this.currentIndex = this._items.length - 1;
            }
            this.goto(this.currentIndex);
        };

        this.goto = function (i) {
            if(i > -1 && i < this._items.length) {
                this.currentIndex = i;
                this._items.forEach(function (item, i){
                    item.classList.remove('active');
                    if(i === scope.currentIndex) {
                        item.classList.add('active');
                    }
                });
            }
        };

        this.paused = true;

        this.pause = function () {
            this.paused = true;
            clearTimeout(this.playInterval);
            mw.tools.loading(this.template, false, );
        };

        this.playInterval = null;
        this._play = function () {
            if(this.paused) return;
            mw.tools.loading(this.template, 100, 'slow');
            this.playInterval = setTimeout(function (){
                mw.tools.loading(scope.template, 'hide');
                scope.next();
                scope._play();
            },5000);
        };

        this.play = function () {
            this.next();
            this.paused = false;
            this._play();
        };

        this._items = [];

        this.createHandles = function () {
            this.template.querySelector('.mw-gallery-prev').onclick = function (){ scope.pause(); scope.prev(); };
            this.template.querySelector('.mw-gallery-next').onclick = function (){ scope.pause(); scope.next(); };
            this.template.querySelector('.mw-gallery-control-close').onclick = function (){ scope.remove(); };
            this.template.querySelector('.mw-gallery-control-play').onclick = function (){
                scope[scope.paused ? 'play' : 'pause']();
                this.classList[scope.paused ? 'remove' : 'add']('pause');
            };
        };

        this.createItems = function () {
            this.data.forEach(function (item, i ){
                scope._items.push(scope.createSingle(item, i));
            });
        };

        this.init = function () {
            this.template = this._template();
            document.body.appendChild(this.template);
            this.container = this.template.querySelector('.mw-gallery-content');
            this.createItems();
            this.createHandles();
        };

        this.remove = function () {
            this.template.remove();
        };

        this.init();
    };

    mw.gallery = function (array, startFrom){
        return new Gallery(array, startFrom);
    };

    // obsolate:
    mw.tools.gallery = {
        init: mw.gallery
    };
})();

})();

(() => {
/*!********************************************************************!*\
  !*** ../userfiles/modules/microweber/api/widgets/icon_selector.js ***!
  \********************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */


(function () {

    var IconLoader = function (store) {
        var scope = this;

        var defaultVersion = '-1';

        var iconsCache = {};


        var common = {
            'fontAwesome': {
                cssSelector: '.fa',
                detect: function (target) {
                    return target.classList.contains('fa');
                },
                render: function (icon, target) {
                    target.classList.add('fa');
                    target.classList.add(icon);
                },
                remove: function (target) {
                    target.classList.remove('fa');
                    var exception= ['fa-lg', 'fa-2x', 'fa-3x', 'fa-4x', 'fa-5x', 'fa-fw', 'fa-spin', 'fa-pule', 'fa-rotate-90',
                        'fa-rotate-180', 'fa-rotate-270', 'fa-flip-horizontal', 'fa-flip-vertical'];
                    mw.tools.classNamespaceDelete(target, 'fa-', undefined, undefined, exception);
                },
                icons: function () {
                     return new Promise(function (resolve) {
                         $.get(mw.settings.modules_url + 'microweber/api/fa.icons.js',function (data) {
                             resolve(JSON.parse(data));
                        });
                    });
                },
                name: 'Font Awesome',
                load:  mw.settings.libs_url + 'fontawesome-4.7.0' + '/css/font-awesome.min.css',
                unload: function () {
                    document.querySelector('link[href*="fontawesome-4.7.0"]').remove();
                },
                version: '4.7.0'
            },
            'materialIcons': {
                cssSelector: '.material-icons',
                detect: function (target) {
                    return target.classList.contains('material-icons');
                },
                render: function (icon, target) {
                    target.classList.add('material-icons');
                    target.innerHTML = (icon);
                },
                remove: function (target) {
                    mw.tools.removeClass(target, 'material-icons');
                    target.innerHTML = '';
                 },
                icons: function () {
                    return new Promise(function (resolve) {
                        $.get(mw.settings.modules_url + 'microweber/api/material.icons.js',function (data) {
                            resolve(JSON.parse(data));
                        });
                    });
                },
                name: 'Material Icons',
                load: mw.settings.libs_url + 'material_icons' + '/material_icons.css',
                unload: function () {
                    top.document.querySelector('link[href*="material_icons.css"]').remove();
                },
                version: 'mw'
            },
            'iconsMindLine': {
                cssSelector: '[class*="mw-micon-"]:not([class*="mw-micon-solid-"])',
                detect: function (target) {
                    return target.className.includes('mw-micon-') && !target.className.includes('mw-micon-solid-');
                },
                render: function (icon, target) {
                    target.classList.add(icon);
                },
                remove: function (target) {
                    mw.tools.classNamespaceDelete(target, 'mw-micon-', undefined, undefined, []);
                },
                icons: function () {
                    return new Promise(function (resolve) {
                        var icons = mw.top().win.document.querySelector('link[href*="mw-icons-mind/line"]').sheet.cssRules;
                        var l = icons.length, i = 0, mindIcons = [];
                        for (; i < l; i++) {
                            var sel = icons[i].selectorText;
                            if (!!sel && sel.indexOf('.mw-micon-') === 0) {
                                var cls = sel.replace(".", '').split(':')[0];
                                mindIcons.push(cls);
                            }
                        }
                        resolve(mindIcons)

                    });
                },
                name: 'Icons Mind Line',
                load:  mw.settings.modules_url + 'microweber/api/libs/mw-icons-mind/line/style.css',
                unload: function () {
                    document.querySelector('link[href*="mw-icons-mind/line/style"]').remove();
                },
                version: 'mw_local'
            },
            'iconsMindSolid': {
                cssSelector: '[class*="mw-micon-solid-"]',
                detect: function (target) {
                    return target.className.includes('mw-micon-solid-');
                },
                render: function (icon, target) {
                    target.classList.add(icon);
                },
                remove: function (target) {
                    mw.tools.classNamespaceDelete(target, 'mw-micon-solid-', undefined, undefined, []);
                },
                icons: function () {
                    return new Promise(function (resolve) {
                        var icons = mw.top().win.document.querySelector('link[href*="mw-icons-mind/solid"]').sheet.cssRules;
                        var l = icons.length, i = 0, mindIcons = [];
                        for (; i < l; i++) {
                            var sel = icons[i].selectorText;
                            if (!!sel && sel.indexOf('.mw-micon-solid-') === 0) {
                                var cls = sel.replace(".", '').split(':')[0];
                                mindIcons.push(cls);
                            }
                        }
                        resolve(mindIcons);

                    });
                },
                name: 'Icons Mind Solid',
                load:  mw.settings.modules_url + 'microweber/api/libs/mw-icons-mind/solid/style.css',
                unload: function () {
                    document.querySelector('link[href*="mw-icons-mind/solid/style"]').remove();
                },
                version: 'mw_local'
            },

            'materialDesignIcons': {
                cssSelector: '.mdi',
                detect: function (target) {
                    return target.classList.contains('mdi');
                },
                render: function (icon, target) {
                    target.classList.add('mdi');
                    target.classList.add(icon);
                },
                remove: function (target) {
                    mw.tools.classNamespaceDelete(target, 'mdi-', undefined, undefined, []);
                    target.classList.remove('mdi');
                },
                icons: function () {
                    return new Promise(function (resolve) {
                        var icons = mw.top().win.document.querySelector('link[href*="materialdesignicons"]').sheet.cssRules;
                        var l = icons.length, i = 0, mdiIcons = [];
                        for (; i < l; i++) {
                            var sel = icons[i].selectorText;
                            if (!!sel && sel.indexOf('.mdi-') === 0) {
                                var cls = sel.replace(".", '').split(':')[0];
                                mdiIcons.push(cls);
                            }
                        }
                        resolve(mdiIcons);

                    });
                },
                name: 'Material Design Icons',
                load:  mw.settings.modules_url + 'microweber/css/fonts/materialdesignicons/css/materialdesignicons.min.css',
                unload: function () {
                    document.querySelector('link[href*="materialdesignicons"]').remove();
                },
                version: 'mw_local'
            },
            'mwIcons': {
                cssSelector: '[class*="mw-icon-"]',
                detect: function (target) {
                    return target.className.includes('mw-icon-');
                },
                render: function (icon, target) {
                    target.classList.add(icon);
                },
                remove: function (target) {
                    mw.tools.classNamespaceDelete(target, 'mw-icon-', undefined, undefined, []);
                },
                icons: function () {
                    return new Promise(function (resolve) {
                        $.get(mw.settings.modules_url + 'microweber/api/microweber.icons.js',function (data) {
                            resolve(JSON.parse(data));
                        });
                    });
                },
                name: 'Microweber Icons',
                load:  mw.settings.modules_url + 'microweber/css/fonts/materialdesignicons/css/materialdesignicons.min.css',
                unload: function () {
                    document.querySelector('link[href*="materialdesignicons"]').remove();
                },
                version: 'mw_local'
            },
        };

        var storage = function () {
            if(!mw.top().__IconStorage) {
                mw.top().__IconStorage = [];
            }
            return mw.top().__IconStorage;
        };

        this.storage = store || storage;


        var iconSetKey = function (options) {
            return options.name + options.version;
        };

        var iconSetPush = function (options) {
            if(!storage().find(function (a) {return iconSetKey(options) === iconSetKey(a); })) {
                return storage().push(options);
            }
            return false;
        };

        var addFontIconSet = function (options) {
            options.version = options.version || defaultVersion;
            iconSetPush(options);
            if (typeof options.load === 'string') {
                mw.require(options.load);
            } else if (typeof options.load === 'function') {
                options.load();
            }
        };
        var addIconSet = function (conf) {
            if(typeof conf === 'string') {
                if (common[conf]) {
                    conf = common[conf];
                } else {
                    console.warn(conf + ' is not defined.');
                    return;
                }
            }
            if(!conf) return;
            conf.type = conf.type || 'font';
            if (conf.type === 'font') {
                return addFontIconSet(conf);
            }
        };

        this.addIconSet = function (conf) {
            addIconSet(conf);
            return this;
        };

        this.removeIconSet = function (name, version) {
            var str = storage();
            var item = str.find(function (a) { return a.name === name && (!version || a.version === version); });
            if (item) {
                if (item.unload) {
                    item.unload();
                }
                str.splice(str.indexOf(item), 1);
            }
        };


        this.init = function () {
            storage().forEach(function (iconSet){
                scope.addIconSet(iconSet);
            });
        };

    };

    mw.iconLoader = function (options) {
        return new IconLoader(options);
    };


})();


(function (){

    var IconPicker = function (options) {
        options = options || {};
        var loader = mw.iconLoader();
        var defaults = {
            iconsPerPage: 40,
            iconOptions: {
                size: true,
                color: true,
                reset: false
            }
        };


        this.settings = mw.object.extend(true, {}, defaults, options);
        var scope = this;
        var tabAccordionBuilder = function (items) {
            var res = {root: mw.element('<div class="mw-tab-accordion" data-options="tabsSize: medium" />'), items: []};
            items.forEach(function (item){
                var el = mw.element('<div class="mw-accordion-item" />');
                var content = mw.element('<div class="mw-accordion-content mw-ui-box mw-ui-box-content">' +(item.content || '') +'</div>');
                var title = mw.element('<div class="mw-ui-box-header mw-accordion-title">' + item.title +'</div>');
                el.append(title);
                el.append(content);

                res.root.append(el);
                res.items.push({
                    title: title,
                    content: content,
                    root: el,
                });
            });
            setTimeout(function (){
                if(mw.components) {
                    mw.components._init();
                }
            }, 10);
            return res;
        };

        var createUI = function () {
            var root = mw.element({
                props: { className: 'mw-icon-selector-root' }
            });
            var iconsBlockHolder, tabs, optionsHolder, iconsHolder;
            if(scope.settings.iconOptions) {
                tabs = tabAccordionBuilder([
                    {title: 'Icons'},
                    {title: 'Options'},
                ]);
                iconsBlockHolder = tabs.items[0].content;
                optionsHolder = tabs.items[1].content;
                root.append(tabs.root)
            } else {
                iconsBlockHolder = mw.element();
                root.append(iconsBlockHolder);
            }
            iconsHolder = mw.element().addClass('mw-icon-picker-icons-holder');
            iconsBlockHolder.append(iconsHolder);
            return {
                root: root,
                tabs: tabs,
                iconsBlockHolder: iconsBlockHolder,
                iconsHolder: iconsHolder,
                optionsHolder: optionsHolder
            };
        };


        var _e = {};

        this.on = function (e, f) { _e[e] ? _e[e].push(f) : (_e[e] = [f]) };
        this.dispatch = function (e, f) { _e[e] ? _e[e].forEach(function (c){ c.call(this, f); }) : ''; };

        var actionNodes = {};

        var createOptions = function (holder) {

            if(holder && scope.settings.iconOptions) {
                if(scope.settings.iconOptions.size) {
                    var sizeel = mw.element('<div class="mwiconlist-settings-section-block-item"><label class="mw-ui-label">Icon size</label></div>');
                    var sizeinput = mw.element('<input type="range" min="8" max="200">');
                    actionNodes.size = sizeinput;
                    sizeinput.on('input', function () {
                        scope.dispatch('sizeChange', sizeinput.get(0).value);
                    });
                    sizeel.append(sizeinput);
                    holder.append(sizeel);
                }
                if(scope.settings.iconOptions.color) {
                    cel = mw.element('<div class="mwiconlist-settings-section-block-item"><label class="mw-ui-label">Color</label></div>');
                    cinput = mw.element('<input type="color">');
                    actionNodes.color = cinput;
                    cinput.on('input', function () {
                        scope.dispatch('colorChange', cinput.get(0).value);
                    });
                    cel.append(cinput);
                    holder.append(cel);
                }
                if(scope.settings.iconOptions.reset) {
                    var rel = mw.element('<div class="mwiconlist-settings-section-block-item"> </div>');
                    var rinput = mw.element('<input type="button" class="mw-ui-btn mw-ui-btn-medium" value="Reset options">');
                    rinput.on('click', function () {
                        scope.dispatch('reset', rinput.get(0).value);
                    });
                    rel.append(rinput);
                    holder.append(rel);
                }
            }
        };

        var _prepareIconsLists = function (c) {
            var sets = loader.storage();
            var all = sets.length;
            var i = 0;
            sets.forEach(function (set){
                 if (!set._iconsLists) {
                     (function (aset){
                         aset.icons().then(function (data){
                             aset._iconsLists = data;
                             i++;
                             if(i === all) c.call(sets, sets);
                         });
                     })(set);
                 } else {
                     i++;
                     if(i === all) c.call(sets, sets);
                 }

            });
        };


        var createPaging = function(length, page){
            page = page || 1;
            var max = 999;
            var pages = Math.min(Math.ceil(length/scope.settings.iconsPerPage), max);
            var paging = document.createElement('div');
            paging.className = 'mw-paging mw-paging-small mw-icon-selector-paging';
            if(scope.settings.iconsPerPage >= length ) {
                return paging;
            }
            var active = false;
            for ( var i = 1; i <= pages; i++) {
                var el = document.createElement('a');
                el.innerHTML = i;
                el._value = i;
                if(page === i) {
                    el.className = 'active';
                    active = i;
                }
                el.onclick = function () {
                    comRender({page: this._value });
                };
                paging.appendChild(el);
            }
            var all = paging.querySelectorAll('a');
            for (var i = active - 3; i < active + 2; i++){
                if(all[i]) {
                    all[i].className += ' mw-paging-visible-range';
                }
            }


            if(active < pages) {
                var next = document.createElement('a');
                next.innerHTML = '&raquo;';
                next._value = active+1;
                next.className = 'mw-paging-visible-range mw-paging-next';
                next.innerHTML = '&raquo;';
                $(paging).append(next);
                next.onclick = function () {
                     comRender({page: this._value });
                }
            }
            if(active > 1) {
                var prev = document.createElement('a');
                prev.className = 'mw-paging-visible-range mw-paging-prev';
                prev.innerHTML = '&laquo;';
                prev._value = active-1;
                $(paging).prepend(prev);
                prev.onclick = function () {
                     comRender({page: this._value });
                };
            }

            return paging;
        };

        var searchField = function () {
            var time = null;
            scope.searchField =  mw.element({
                tag: 'input',
                props: {
                    className: 'mw-ui-searchfield w100',
                    oninput: function () {
                        clearTimeout(time);
                        time = setTimeout(function (){
                            comRender();
                        }, 123);
                    }
                }
            });

            return scope.searchField;
        };

        var comRender = function (options) {
            options = options || {};
            options = mw.object.extend({}, {
                set: scope.selectField.get(0).options[scope.selectField.get(0).selectedIndex]._value,
                term: scope.searchField.get(0).value
            }, options);
            scope.ui.iconsHolder.empty().append(renderSearchResults(options));
        };

        var searchSelector = function () {
            var sel = mw.element('<select class="mw-ui-field w100" />');
            scope.selectField = sel;
            loader.storage().forEach(function (item) {
                var el = document.createElement('option');
                el._value = item;
                el.innerHTML = item.name;
                sel.append(el);
            });
            sel.on('change', function (){
                comRender()
            });
            return sel;
        };

        var search = function (conf) {
            conf = conf || {};
            conf.set = conf.set ||  loader.storage()[0];
            conf.page = conf.page || 1;
            conf.term = (conf.term || '').trim().toLowerCase();

            var all = conf.set._iconsLists.filter(function (f){ return f.toLowerCase().indexOf(conf.term) !== -1; });

            var off = scope.settings.iconsPerPage * (conf.page - 1);
            var to = off + Math.min(all.length - off, scope.settings.iconsPerPage);

            return mw.object.extend({}, conf, {
                data: all.slice(off, to),
                all: all,
                off: off
            });
            /*for ( var i = off; i < to; i++ ) {

            }*/
        };

        var renderSearchResults = function (conf) {
            var res = search(conf);
            var pg = createPaging(res.all.length, res.page);
            var root = mw.element();
            res.data.forEach(function (iconItem){
                var icon = mw.element({
                    tag: 'span',
                    props: {
                        className: 'mwiconlist-icon',
                        onclick: function (e) {
                            scope.dispatch('select', {
                                icon: iconItem,
                                renderer: res.set.render,
                                render: function () {
                                    var sets = loader.storage();
                                    sets.forEach(function (set) {
                                        set.remove(scope.target)
                                    })
                                    return res.set.render(iconItem, scope.target);
                                }
                            });
                        }
                    }
                });
                root.append(icon);
                res.set.render(iconItem, icon.get(0));
            });
            root.append(pg)
            return root;
        };

        var createIconsBlock = function () {
            mw.spinner({element: scope.ui.iconsHolder.get(0), size: 30}).show();
            _prepareIconsLists(function (){
                comRender();
                mw.spinner({element: scope.ui.iconsHolder.get(0)}).hide();
            });
        };

        this.create = function () {
            this.ui = createUI();
            createOptions(this.ui.optionsHolder);
            this.ui.iconsBlockHolder.prepend(searchField());

            this.ui.iconsBlockHolder.prepend(searchSelector());
            createIconsBlock();

        };

        this.get = function () {
            return this.ui.root.get(0);
        };

        this.dialog = function (method) {
            if(method === 'hide') {
                this._dialog.hide();
                return;
            }
            if(!this._dialog) {
                this._dialog = mw.top().dialog({content: this.get(), title: 'Select icon', closeButtonAction: 'hide', width: 450});
                mw.components._init();
            }
            this._dialog.show();
            return this._dialog;
        };

        this.destroy = function () {
            this.get().remove()
            if(this._dialog) {
                this._dialog.remove();
            }
            if(this._tooltip) {
                this._tooltip.remove();
            }
        };

        this.target = null;

        this.tooltip = function (target) {
            this.target = target;
            if(target === 'hide' && this._tooltip) {
                this._tooltip.style.display = 'none';
            } else {
                if (!this._tooltip) {
                    this._tooltip = mw.tooltip({
                        content: this.get(),
                        element: target,
                        position: 'bottom-center',
                    });
                } else {
                    mw.tools.tooltip.setPosition(this._tooltip, target, 'bottom-center');
                }
                this._tooltip.style.display = 'block';
            }
            mw.components._init();
            return this._tooltip;
        };

        this.init = function () {
            this.create();
        };

        this.promise = function () {
            return new Promise(function (resolve){
               scope.on('select', function (data) {
                   resolve(data);
               });
            });
        };

        this.init();

    };


    mw.iconPicker = function (options) {
        return new IconPicker(options);
    };

})();







})();

(() => {
/*!*************************************************************!*\
  !*** ../userfiles/modules/microweber/api/widgets/select.js ***!
  \*************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
/*
    options.data = [
        {
            title: string,
            value: any,
            icon?: string,
            selected?: boolean
        }
    ]

 */


mw.Select = function(options) {
    var defaults = {
        data: [],
        skin: 'default',
        multiple: false,
        autocomplete: false,
        mobileAutocomplete: false,
        showSelected: false,
        document: document,
        size: 'normal',
        color: 'default',
        dropMode: 'over', // 'over' | 'push'
        placeholder: mw.lang('Select'),
        tags: false, // only if multiple is set to true,
        ajaxMode: {
            paginationParam: 'page',
            searchParam: 'keyword',
            endpoint: null,
            method: 'get'
        }
    };
    options  = options || {};
    this.settings = $.extend({}, defaults, options);



    if(this.settings.ajaxMode && !this.settings.ajaxMode.endpoint){
        this.settings.ajaxMode = false;
    }

    this.$element = $(this.settings.element).eq(0);
    this.element = this.$element[0];
    if(!this.element) {
        return;
    }

    this._rootInputMode = this.element.nodeName === 'INPUT';

    if(this.element._mwSelect) {
        return this.element._mwSelect;
    }

    var scope = this;
    this.document = this.settings.document;

    this._value = null;


    this.getLabel = function(item) {
        return item.title || item.name || item.label || item.value;
    };

    this._loading = false;

    this._page = 1;

    this.nextPage = function () {
        this._page++;
    };

    this.page = function (state) {
        if (typeof state === 'undefined') {
            return this._page;
        }
        this._page = state;
    };

    this.loading = function (state) {
        if (typeof state === 'undefined') {
            return this._state;
        }
        this._state = state;
        mw.tools.classNamespaceDelete(this.root, 'mw-select-loading-');
        mw.tools.addClass(this.root, 'mw-select-loading-' + state);
    };

    this.xhr = null;

    this.ajaxFilter = function (val, callback) {
        if (this.xhr) {
            this.xhr.abort();
        }
        val = (val || '').trim().toLowerCase();
        var params = { };
        params[this.settings.ajaxMode.searchParam] = val;
        params = (this.settings.ajaxMode.endpoint.indexOf('?') === -1 ? '?' : '&' ) + $.param(params);
        this.xhr = $[this.settings.ajaxMode.method](this.settings.ajaxMode.endpoint + params, function (data) {
            callback.call(this, data);
            scope.xhr = null;
        });
    };

    this.filter = function (val) {
        val = (val || '').trim().toLowerCase();
        if (this.settings.ajaxMode) {
            this.loading(true);
            this.ajaxFilter(val, function (data) {
                scope.setData(data.data);
                if(data.data && data.data.length){
                    scope.open();
                } else {
                    scope.close();
                }
                scope.loading(false);
            });
        } else {
            var all = this.root.querySelectorAll('.mw-select-option'), i = 0;
            if (!val) {
                for( ; i< all.length; i++) {
                    all[i].style.display = '';
                }
            } else {
                for( ; i< all.length; i++) {
                    all[i].style.display = this.getLabel(all[i].$value).toLowerCase().indexOf(val) !== -1 ? '' : 'none';
                }
            }
        }
    };

    this.setPlaceholder = function (plval) {
        /*plval = plval || this.settings.placeholder;
        if(scope.settings.autocomplete){
            $('.mw-ui-invisible-field', this.root).attr('placeholder', plval);
        } else {
            $('.mw-ui-btn-content', this.root).html(plval);
        }*/
        return this.displayValue(plval)
    };
    this.displayValue = function(plval){
        if(!plval && !this.settings.multiple && this.value()) {
            plval = scope.getLabel(this.value());
        }
        plval = plval || this.settings.placeholder;
        if(!scope._displayValue) {
            scope._displayValue = scope.document.createElement('span');
            scope._displayValue.className = 'mw-select-display-value mw-ui-size-' + this.settings.size;
            $('.mw-select-value', this.root).append(scope._displayValue)
        }
        if(this._rootInputMode){
            scope._displayValue.innerHTML = '&nbsp';
            $('input.mw-ui-invisible-field', this.root).val(plval);

        } else {
            scope._displayValue.innerHTML = plval + this.__indicateNumber();

        }
    };

    this.__indicateNumber = function () {
        if(this.settings.multiple && this.value() && this.value().length){
            return "<span class='mw-select-indicate-number'>" + this.value().length + "</span>";
        } else {
            return "<span class='mw-select-indicate-number mw-select-indicate-number-empty'>" + 0 + "</span>";
        }
    };

    this.rend = {

        option: function(item){
            var oh = scope.document.createElement('label');
            oh.$value = item;
            oh.className = 'mw-select-option';
            if (scope.settings.multiple) {
                oh.className = 'mw-ui-check mw-select-option';
                oh.innerHTML =  '<input type="checkbox"><span></span><span>'+scope.getLabel(item)+'</span>';

                $('input', oh).on('change', function () {
                    this.checked ? scope.valueAdd(oh.$value) : scope.valueRemove(oh.$value)
                });
            } else {
                oh.innerHTML = scope.getLabel(item);
                oh.onclick = function () {
                    scope.value(oh.$value)
                };
            }

            return oh;
        },
        value: function() {
            var tag = 'span', cls = 'mw-ui-btn';
            if(scope.settings.autocomplete){
                tag = 'span';
                cls = 'mw-ui-field'
            }
            var oh = scope.document.createElement(tag);
            oh.className = cls + ' mw-ui-size-' + scope.settings.size + ' mw-ui-bg-' + scope.settings.color + ' mw-select-value';

            if(scope.settings.autocomplete){
                oh.innerHTML = '<input class="mw-ui-invisible-field mw-ui-field-' + scope.settings.size + '">';
            } else {
                oh.innerHTML = '<span class="mw-ui-btn-content"></span>';
            }

            if(scope.settings.autocomplete){
                $('input', oh)
                    .on('input focus', function () {
                        scope.filter(this.value);
                        if(scope._rootInputMode) {
                            scope.element.value = this.value;
                            $(scope.element).trigger('input change')
                        }
                    })
                    .on('focus', function () {
                        if(scope.settings.data && scope.settings.data.length) {
                            scope.open();
                        }
                    }).on('focus blur input', function () {
                    var hasVal = !!this.value.trim();
                    mw.tools[hasVal ? 'addClass' : 'removeClass'](scope.root, 'mw-select-has-value')
                });
            } else {
                oh.onclick = function () {
                    scope.toggle();
                };
            }
            return oh;
        },
        options: function(){
            scope.optionsHolder = scope.document.createElement('div');
            scope.holder = scope.document.createElement('div');
            scope.optionsHolder.className = 'mw-select-options';
            $.each(scope.settings.data, function(){
                scope.holder.appendChild(scope.rend.option(this))
            });
            scope.optionsHolder.appendChild(scope.holder);
            return scope.optionsHolder;
        },
        root: function () {
            scope.root = scope.document.createElement('div');
            scope.root.className = 'mw-select mw-select-dropmode-' + scope.settings.dropMode + ' mw-select-multiple-' + scope.settings.multiple;

            return scope.root;
        }
    };

    this.state = 'closed';

    this.open = function () {
        this.state = 'opened';
        mw.tools.addClass(scope.root, 'active');
        mw.Select._register.forEach(function (item) {
            if(item !== scope) {
                item.close()
            }
        });
    };

    this.close = function () {
        this.state = 'closed';
        mw.tools.removeClass(scope.root, 'active');
    };

    this.tags = function () {
        if(!this._tags) {
            if(this.settings.multiple && this.settings.tags) {
                var holder = scope.document.createElement('div');
                holder.className = 'mw-select-tags';
                this._tags = new mw.tags({element:holder, data:scope._value || [], color: this.settings.tagsColor, size: this.settings.tagsSize || 'small'})
                $(this.optionsHolder).prepend(holder);
                mw.$(this._tags).on('tagRemoved', function (e, tag) {
                    scope.valueRemove(tag)
                })
            }
        } else {
            this._tags.setData(scope._value)
        }
        this.displayValue()
    };


    this.toggle = function () {
        if (this.state === 'closed') {
            this.open();
        } else {
            this.close();
        }
    };


    this._valueGet = function (val) {
        if(typeof val === 'number') {
            val = this.settings.data.find(function (item) {
                return item.id === val;
            })
        }
        return val;
    };



    this.valueAdd = function(val){
        if (!val) return;
        val = this._valueGet(val);
        if (!val) return;
        if (!this._value) {
            this._value = []
        }
        var exists = this._value.find(function (item) {
            return item.id === val.id;
        });
        if (!exists) {
            this._value.push(val);
            $(this.root.querySelectorAll('.mw-select-option')).each(function () {
                if(this.$value === val) {
                    this.querySelector('input').checked = true;
                }
            });
        }

        this.afterChange();
    };

    this.afterChange = function () {
        this.tags();
        this.displayValue();
        if($.isArray(this._value)) {
            $.each(this._value, function () {

            });
            $(this.root.querySelectorAll('.mw-select-option')).each(function () {
                if(scope._value.indexOf(this.$value) !== -1) {
                    mw.tools.addClass(this, 'active')
                }
                else {
                    mw.tools.removeClass(this, 'active')
                }
            });
        }
        $(this).trigger('change', [this._value]);
    };

    this.valueRemove = function(val) {
        if (!val) return;
        val = this._valueGet(val);
        if (!val) return;
        if (!this._value) {
            this._value = [];
        }
        var exists = this._value.find(function (item) {
            return item.id === val.id;
        });
        if (exists) {
            this._value.splice(this._value.indexOf(exists), 1);
        }
        $(this.root.querySelectorAll('.mw-select-option')).each(function () {
            if(this.$value === val) {
                this.querySelector('input').checked = false;
            }
        });
        this.afterChange()
    };

    this._valueToggle = function(val){
        if (!val) return;
        if (!this._value) {
            this._value = [];
        }
        var exists = this._value.find(function (item) {
            return item.id === val.id;
        });
        if (exists) {
            this._value.splice(this._value.indexOf(exists), 1);
        } else {
            this._value.push(val);
        }
        this.afterChange();
    };

    this.value = function(val){
        if(!val) return this._value;
        val = this._valueGet(val);
        if (!val) return;
        if(this.settings.multiple){
            this._valueToggle(val)
        }
        else {
            this._value = val;
            $('.mw-ui-invisible-field', this.root).val(this.getLabel(val))
            this.close();
        }

        this.afterChange()
    };

    this.setData = function (data) {
        $(scope.holder).empty();
        scope.settings.data = data;
        $.each(scope.settings.data, function(){
            scope.holder.appendChild(scope.rend.option(this))
        });
        return scope.holder;
    };

    this.addData = function (data) {
        $.each(data, function(){
            scope.settings.data.push(this);
            scope.holder.appendChild(scope.rend.option(this));
        });
        return scope.holder;
    };

    this.build = function () {
        this.rend.root();
        this.root.appendChild(this.rend.value());
        this.root.appendChild(this.rend.options());
        if (this._rootInputMode) {
            this.element.type = 'hidden';
            this.$element.before(this.root);
        } else {
            this.$element.html(this.root);
        }
        this.setPlaceholder();
        mw.Select._register.push(this);
    };

    this.init = function () {
        this.build();
        this.element._mwSelect = this;
    };



    this.init();


};

mw.Select._register = [];


$(document).ready(function () {
    $(document).on('mousedown touchstart', function (e) {
        if(!mw.tools.firstParentOrCurrentWithClass(e.target, 'mw-select')){
            mw.Select._register.forEach(function (item) {
                item.close();
            });
        }
    });
});


mw.select = function(options) {
    return new mw.Select(options);
};

})();

(() => {
/*!**************************************************************!*\
  !*** ../userfiles/modules/microweber/api/widgets/spinner.js ***!
  \**************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.Spinner = function(options){
    if(!options || !options.element){
        return;
    }
    this.$element = $(options.element);
    if(!this.$element.length) return;
    this.element = this.$element[0];
    if(this.element._mwSpinner){
        return this.element._mwSpinner;
    }
    this.element._mwSpinner = this;
    this.options = options;

    this.options.size = this.options.size || 20;
    this.options.color = this.options.color || '#4592ff';
    this.options.insertMode = this.options.insertMode || 'append';

    this.color = function(val){
        if(!val) {
            return this.options.color;
        }
        this.options.color = val;
        this.$spinner.find('circle').css({
            stroke: this.options.color
        });
    };

    this.size = function(val){
        if(!val) {
            return this.options.size;
        }
        this.options.size = parseFloat(val);
        this.$spinner.css({
            width: this.options.size,
            height: this.options.size
        });
    };

    this.create = function(){
        this.$spinner = $('<div class="mw-spinner mw-spinner-mode-' + this.options.insertMode + '" style="display: none;"><svg viewBox="0 0 50 50"><circle cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle></svg></div>');
        this.size(this.options.size);
        this.color(this.options.color);
        this.$element[this.options.insertMode](this.$spinner);
        this.show();
        return this;
    };

    this.show = function(){
        this.$spinner.show();
        this.$element.addClass('has-mw-spinner');
        return this;
    };

    this.hide = function(){
        this.$spinner.hide();
        this.$element.removeClass('has-mw-spinner');
        return this;
    };

    this.remove = function(){
        this.hide();
        this.$spinner.remove();
        delete this.element._mwSpinner;
    };

    this.create().show();

};

mw.spinner = function(options){
    return new mw.Spinner(options);
};

})();

(() => {
/*!***********************************************************!*\
  !*** ../userfiles/modules/microweber/api/widgets/tags.js ***!
  \***********************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */


mw.coreIcons = {
    category:'mw-icon-category',
    page:'mw-icon-page',
    home:'mw-icon-home',
    shop:'mai-market2',
    post:'mai-post'
};


mw.tags = mw.chips = function(options){

    "use strict";

    options.element = mw.$(options.element)[0];
    options.size = options.size || 'sm';

    this.options = options;
    this.options.map = this.options.map || { title: 'title', value: 'id', image: 'image', icon: 'icon' };
    this.map = this.options.map;
    var scope = this;
    /*
        data: [
            {title:'Some tag', icon:'<i class="icon"></i>'},
            {title:'Some tag', icon:'icon', image:'http://some-image/jpg.png'},
            {title:'Some tag', color:'warn'},
        ]
    */


    this.refresh = function(){
        mw.$(scope.options.element).empty();
        this.rend();
    };

    this.setData = function(data){
        this.options.data = data;
        this.refresh();
    };
    this.rend = function(){
        $.each(this.options.data, function(i){
            var data = $.extend({index:i}, this);
            scope.options.element.appendChild(scope.tag(data));
        });
        if(this.options.inputField) {
            scope.options.element.appendChild(this.addInputField());
        }
    };

    this.addInputField = function () {
        this._field = document.createElement('input');
        this._field.className = 'mw-ui-invisible-field mw-ui-field-' + this.options.size;
        this._field.onkeydown = function (e) {
            if(mw.event.is.enter(e)) {
                var val = scope._field.value.trim();
                if(val) {
                    scope.addTag({
                        title: val
                    });
                }
            }
        };
        return this._field;
    };



    this.dataValue = function(data){
        if(typeof data === 'string'){
            return data;
        }
        else{
            return data[this.map.value]
        }
    };

    this.dataImage = function(data){
        if(data[this.map.image]){
            var img = document.createElement('span');
            img.className = 'mw-ui-btn-img';
            img.style.backgroundImage = 'url('+data.image+')';
            return img;
        }
    };

    this.dataTitle = function(data){
        if(typeof data === 'string'){
            return data;
        }
        else{
            return data[this.map.title];
        }
    };

    this.dataIcon = function(data){
        if(typeof data === 'string'){
            return;
        }
        else{
            return data[this.map.icon]
        }
    };


     this.createImage = function (config) {
         var img = this.dataImage(config);
        if(img){
            return img;
        }
     };
     this.createIcon = function (config) {
        var ic = this.dataIcon(config);

        if(!ic && config.type){
            ic = mw.coreIcons[config.type];

        }
        var icon;
        if(typeof ic === 'string' && ic.indexOf('<') === -1){
            icon = mwd.createElement('i');
            icon.className = ic;
        }
        else{
            icon = ic;
        }

        return mw.$(icon)[0];
     };

     this.removeTag = function (index) {
        var item = this.options.data[index];
        this.options.data.splice(index,1);
        this.refresh();
        mw.$(scope).trigger('tagRemoved', [item, this.options.data]);
        mw.$(scope).trigger('change', [item, this.options.data]);
     };

    this.addTag = function(data, index){
        index = typeof index === 'number' ? index : this.options.data.length;
        this.options.data.splice( index, 0, data );
        this.refresh();
        mw.$(scope).trigger('tagAdded', [data, this.options.data]);
        mw.$(scope).trigger('change', [data, this.options.data]);
    };

     this.tag = function (options) {
            var config = {
                close:true,
                tagBtnClass:'btn btn-' + this.options.size
            };

            $.extend(config, options);

         config.tagBtnClass +=  ' mb-2 mr-2 btn';

         if (this.options.outline){
             config.tagBtnClass +=  '-outline';
         }

         if (this.options.color){
             config.tagBtnClass +=  '-' + this.options.color;
         }



         if(this.options.rounded){
             config.tagBtnClass +=  ' btn-rounded';
         }


            var tag_holder = mwd.createElement('span');
            var tag_close = mwd.createElement('span');

            tag_close._index = config.index;
            tag_holder._index = config.index;
            tag_holder._config = config;
            tag_holder.dataset.index = config.index;

            tag_holder.className = config.tagBtnClass;

             if(options.image){

             }

            tag_holder.innerHTML = '<span class="tag-label-content">' + this.dataTitle(config) + '</span>';

             if(typeof this.options.disableItem === 'function') {
                 if(this.options.disableItem(config)){
                     tag_holder.className += ' disabled';
                 }
             }
             if(typeof this.options.hideItem === 'function') {
                 if(this.options.hideItem(config)){
                     tag_holder.className += ' hidden';
                 }
             }

            var icon = this.createIcon(config);

            var image = this.createImage(config);

             if(image){
                 mw.$(tag_holder).prepend(image);
             }
             if(icon){
                 mw.$(tag_holder).prepend(icon);
             }


            tag_holder.onclick = function (e) {
                if(e.target !== tag_close){
                    mw.$(scope).trigger('tagClick', [this._config, this._index, this])
                }
            };

            tag_close.className = 'mw-icon-close ml-1';
            if(config.close){
                tag_close.onclick = function () {
                    scope.removeTag(this._index);
                };
            }
            tag_holder.appendChild(tag_close);
            return tag_holder;
        };

     this.init = function () {
         this.rend();
         $(this.options.element).on('click', function (e) {
             if(e.target === scope.options.element){
                 $('input', this).focus();
             }
         })
     };
    this.init();
};

mw.treeTags = mw.treeChips = function(options){
    this.options = options;
    var scope = this;

    var tagsHolder = options.tagsHolder || mw.$('<div class="mw-tree-tag-tags-holder"></div>');
    var treeHolder = options.treeHolder || mw.$('<div class="mw-tree-tag-tree-holder"></div>');

    var treeSettings = $.extend({}, this.options, {element:treeHolder});
    var tagsSettings = $.extend({}, this.options, {element:tagsHolder, data:this.options.selectedData || []});

    this.tree = new mw.tree(treeSettings);

    this.tags = new mw.tags(tagsSettings);

    mw.$( this.options.element ).append(tagsHolder);
    mw.$( this.options.element ).append(treeHolder);

     mw.$(this.tags).on('tagRemoved', function(event, item){
         scope.tree.unselect(item);
     });
     mw.$(this.tree).on('selectionChange', function(event, selectedData){
        scope.tags.setData(selectedData);
    });

};

})();

(() => {
/*!**************************************************************!*\
  !*** ../userfiles/modules/microweber/api/widgets/tooltip.js ***!
  \**************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
(function(){
    var tooltip = {
        source: function (content, skin, position, id) {
            if (skin === 'dark') {
                skin = 'mw-tooltip-dark';
            } else if (skin === 'warning') {
                skin = 'mw-tooltip-default mw-tooltip-warning';
            }
            if (typeof id === 'undefined') {
                id = 'mw-tooltip-' + mw.random();
            }
            var tooltip = mwd.createElement('div');
            var tooltipc = mwd.createElement('div');
            tooltip.className = 'mw-tooltip ' + position + ' ' + skin;
            tooltipc.className = 'mw-tooltip-content';
            tooltip.id = id;
            $(tooltipc).append(content);
            $(tooltip).append(tooltipc).append('<span class="mw-tooltip-arrow"></span>');
            mwd.body.appendChild(tooltip);
            return tooltip;
        },
        setPosition: function (tooltip, el, position) {
                el = mw.$(el);
                if (el.length === 0) {
                    return false;
                }
                tooltip.tooltipData.element = el[0];
                var w = el.outerWidth(),
                    tipwidth = mw.$(tooltip).outerWidth(),
                    h = el.outerHeight(),
                    tipheight = mw.$(tooltip).outerHeight(),
                    off = el.offset(),
                    arrheight = mw.$('.mw-tooltip-arrow', tooltip).height();
                if (off.top === 0 && off.left === 0) {
                    off = mw.$(el).parent().offset();
                }
                mw.tools.removeClass(tooltip, tooltip.tooltipData.position);
                mw.tools.addClass(tooltip, position);
                tooltip.tooltipData.position = position;

                off.left = off.left > 0 ? off.left : 0;
                off.top = off.top > 0 ? off.top : 0;

                var leftCenter = off.left - tipwidth / 2 + w / 2;
                leftCenter = leftCenter > 0 ? leftCenter : 0;

                if (position === 'auto') {
                    var $win = mw.$(window);
                    var wxCenter =  $win.width()/2;
                    var wyCenter =  ($win.height() + $win.scrollTop())/2;
                    var elXCenter =  off.left +(w/2);
                    var elYCenter =  off.top +(h/2);
                    var xPos, yPost;
                    var space = 100;

                    if(elXCenter > wxCenter) {
                        xPos = 'left'
                    } else {
                        xPos = 'right'
                    }

                    yPos = 'top'


                    return this.setPosition (tooltip, el, (xPos+'-'+yPos));
                }

                if (position === 'bottom-left') {
                    mw.$(tooltip).css({
                        top: off.top + h + arrheight,
                        left: off.left
                    });
                }
                else if (position === 'bottom-center') {
                    mw.$(tooltip).css({
                        top: off.top + h + arrheight,
                        left: leftCenter
                    });
                }
                else if (position === 'bottom-right') {
                    mw.$(tooltip).css({
                        top: off.top + h + arrheight,
                        left: off.left - tipwidth + w
                    });
                }
                else if (position === 'top-right') {
                    mw.$(tooltip).css({
                        top: off.top - tipheight - arrheight,
                        left: off.left - tipwidth + w
                    });
                }
                else if (position === 'top-left') {
                    mw.$(tooltip).css({
                        top: off.top - tipheight - arrheight,
                        left: off.left
                    });
                }
                else if (position === 'top-center') {

                    mw.$(tooltip).css({
                        top: off.top - tipheight - arrheight,
                        left: leftCenter
                    });
                }
                else if (position === 'left-top') {
                    mw.$(tooltip).css({
                        top: off.top,
                        left: off.left - tipwidth - arrheight
                    });
                }
                else if (position === 'left-bottom') {
                    mw.$(tooltip).css({
                        top: (off.top + h) - tipheight,
                        left: off.left - tipwidth - arrheight
                    });
                }
                else if (position === 'left-center') {
                    mw.$(tooltip).css({
                        top: off.top - tipheight / 2 + h / 2,
                        left: off.left - tipwidth - arrheight
                    });
                }
                else if (position === 'right-top') {
                    mw.$(tooltip).css({
                        top: off.top,
                        left: off.left + w + arrheight
                    });
                }
                else if (position === 'right-bottom') {
                    mw.$(tooltip).css({
                        top: (off.top + h) - tipheight,
                        left: off.left + w + arrheight
                    });
                }
                else if (position === 'right-center') {
                    mw.$(tooltip).css({
                        top: off.top - tipheight / 2 + h / 2,
                        left: off.left + w + arrheight
                    });
                }
                if (parseFloat($(tooltip).css('top')) < 0) {
                    mw.$(tooltip).css('top', 0);
                }
            },
            fixPosition: function (tooltip) {
                /* mw_todo */
                var max = 5;
                var arr = mw.$('.mw-tooltip-arrow', tooltip);
                arr.css('left', '');
                var arr_left = parseFloat(arr.css('left'));
                var tt = mw.$(tooltip);
                if (tt.length === 0) {
                    return false;
                }
                var w = tt.width(),
                    off = tt.offset(),
                    ww = mw.$(window).width();
                if ((off.left + w) > (ww - max)) {
                    var diff = off.left - (ww - w - max);
                    tt.css('left', ww - w - max);
                    arr.css('left', arr_left + diff);
                }
                if (parseFloat(tt.css('top')) < 0) {
                    tt.css('top', 0);
                }
            },
            prepare: function (o) {
                if (typeof o.element === 'undefined') return false;
                if (o.element === null) return false;
                if (typeof o.element === 'string') {
                    o.element = mw.$(o.element)
                }

                if (o.element.constructor === [].constructor && o.element.length === 0) return false;
                if (typeof o.position === 'undefined') {
                    o.position = 'auto';
                }
                if (typeof o.skin === 'undefined') {
                    o.skin = 'mw-tooltip-default';
                }
                if (typeof o.id === 'undefined') {
                    o.id = 'mw-tooltip-' + mw.random();
                }
                if (typeof o.group === 'undefined') {
                    o.group = null;
                }
                return {
                    id: o.id,
                    element: o.element,
                    skin: o.template || o.skin,
                    position: o.position,
                    content: o.content,
                    group: o.group
                }
            },
            init: function (o, wl) {

                var orig_options = o;
                o = mw.tools.tooltip.prepare(o);
                if (o === false) return false;
                if (o.id && mw.$('#' + o.id).length > 0) {
                    var tip = mw.$('#' + o.id)[0];
                } else {
                    var tip = mw.tools.tooltip.source(o.content, o.skin, o.position, o.id);
                }
                tip.tooltipData = o;
                var wl = wl || true;
                if (o.group) {
                    var tip_group_class = 'mw-tooltip-group-' + o.group;
                    var cur_tip = mw.$(tip)
                    if (!cur_tip.hasClass(tip_group_class)) {
                        cur_tip.addClass(tip_group_class);
                    }
                    var cur_tip_id = cur_tip.attr('id');
                    if (cur_tip_id) {
                        mw.$("." + tip_group_class).not("#" + cur_tip_id).hide();
                        if (o.group && typeof orig_options.close_on_click_outside !== 'undefined' && orig_options.close_on_click_outside) {
                            setTimeout(function () {
                                mw.$("#" + cur_tip_id).show();
                            }, 100);
                        } else {
                            mw.$("#" + cur_tip_id).show();
                        }
                    }
                }
                if (wl && $.contains(self.document, tip)) {
                    /*
                     //position bug: resize fires in modal frame
                     mw.$(self).bind('resize scroll', function (e) {
                     if (self.document.contains(tip)) {
                     self.mw.tools.tooltip.setPosition(tip, tip.tooltipData.element, o.position);
                     }
                     });*/
                    if (o.group && typeof orig_options.close_on_click_outside !== 'undefined' && orig_options.close_on_click_outside) {
                        mw.$(self).bind('click', function (e, target) {
                            mw.$("." + tip_group_class).hide();
                        });
                    }
                }
                mw.tools.tooltip.setPosition(tip, o.element, o.position);
                return tip;
            }

    };

    mw.tools.tooltip = tooltip;
    mw.tools.titleTip = function (el, _titleTip) {
        _titleTip = _titleTip || '_titleTip';
        if (mw.tools.hasClass(el, 'tip-disabled')) {
            mw.$(mw.tools[_titleTip]).hide();
            return false;
        }
        var skin = mw.$(el).attr('data-tipskin');
        skin = (skin) ? skin : 'mw-tooltip-dark';
        var pos = mw.$(el).attr('data-tipposition');
        var iscircle = mw.$(el).attr('data-tipcircle') === 'true';
        if (!pos) {
            pos = 'top-center';
        }
        var text = mw.$(el).attr('data-tip');
        if (!text) {
            text = mw.$(el).attr('title');
        }
        if (!text) {
            text = mw.$(el).attr('tip');
        }
        if (typeof text === 'undefined' || !text) {
            return;
        }
        if (text.indexOf('.') === 0 || text.indexOf('#') === 0) {
            var xitem = mw.$(text);
            if (xitem.length === 0) {
                return false;
            }
            text = xitem.html();
        }
        else {
            text = text.replace(/\n/g, '<br>');
        }
        var showon = mw.$(el).attr('data-showon');
        if (showon) {
            el = mw.$(showon)[0];
        }
        if (!mw.tools[_titleTip]) {
            mw.tools[_titleTip] = mw.tooltip({skin: skin, element: el, position: pos, content: text});
            mw.$(mw.tools[_titleTip]).addClass('mw-universal-tooltip');
        }
        else {
            mw.tools[_titleTip].className = 'mw-tooltip ' + pos + ' ' + skin + ' mw-universal-tooltip';
            mw.$('.mw-tooltip-content', mw.tools[_titleTip]).html(text);
            mw.tools.tooltip.setPosition(mw.tools[_titleTip], el, pos);
        }
        if (iscircle) {
            mw.$(mw.tools[_titleTip]).addClass('mw-tooltip-circle');
        }
        else {
            mw.$(mw.tools[_titleTip]).removeClass('mw-tooltip-circle');
        }
        mw.$(mw.tools[_titleTip]).show();
    }

})();

})();

(() => {
/*!***********************************************************!*\
  !*** ../userfiles/modules/microweber/api/widgets/tree.js ***!
  \***********************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */

/********************************************************


 var myTree = new mw.tree({

});


 ********************************************************/




(function(){
    mw.lib.require('jqueryui');

    mw.lib.require('nestedsortable');



    var mwtree = function(config){

        var scope = this;

        this.config = function(config){

            window.mwtree = (typeof window.mwtree === 'undefined' ? 0 : window.mwtree)+1;

            if(!config.id && typeof config.saveState === undefined){
                config.saveState = false;
            }

            var defaults = {
                data:[],
                openedClass:'opened',
                selectedClass:'selected',
                skin:'default',
                multiPageSelect:true,
                saveState:true,
                sortable:false,
                nestedSortable:false,
                singleSelect:false,
                selectedData:[],
                skip:[],
                contextMenu:false,
                append:false,
                prepend:false,
                selectable:false,
                filter:false,
                cantSelectTypes: [],
                document: document,
                _tempRender: true,
                filterRemoteURL: null,
                filterRemoteKey: 'keyword',
            };

            var options = $.extend({}, defaults, config);



            options.element = mw.$(options.element)[0];
            options.data = options.data || [];

            this.options = options;
            this.document = options.document;
            this._selectionChangeDisable = false;

            if(this.options.selectedData){
                this.selectedData = this.options.selectedData;
            }
            else{
                this.selectedData = [];
            }
        };
        this.filterLocal = function(val, key){
            key = key || 'title';
            val = (val || '').toLowerCase().trim();
            if(!val){
                scope.showAll();
            }
            else{
                scope.options.data.forEach(function(item){
                    if(item[key].toLowerCase().indexOf(val) === -1){
                        scope.hide(item);
                    }
                    else{
                        scope.show(item);
                    }
                });
            }
        };

        this._filterRemoteTime = null;
        this.filterRemote = function(val, key){
            clearTimeout(this._filterRemoteTime);
            this._filterRemoteTime = setTimeout(function () {
                key = key || 'title';
                val = (val || '').toLowerCase().trim();
                var ts = {};
                ts[scope.options.filterRemoteKey] = val;
                $.get(scope.options.filterRemoteURL, ts, function (data) {
                    scope.setData(data);
                });
            }, 777);
        };

        this.filter = function(val, key){
            if (!!this.options.filterRemoteURL && !!this.options.filterRemoteKey) {
                this.filterRemote(val, key);
            } else {
                this.filterLocal(val, key);
            }
        };

        var _e = {};

        this.on = function (e, f) { _e[e] ? _e[e].push(f) : (_e[e] = [f]) };
        this.dispatch = function (e, f) { _e[e] ? _e[e].forEach(function (c){ c.call(this, f); }) : ''; };

        this.search = function(){
            this._seachInput = mw.$(this.options.searchInput);
            if(!this._seachInput[0] || this._seachInput[0]._tree) return;
            this._seachInput[0]._tree = this;
            var scope = this;
            this._seachInput.on('input', function(){
                scope.filter(this.value);
            });
        };
        this.skip = function(itemData){
            if(this.options.skip && this.options.skip.length>0){
                for( var n=0; n<scope.options.skip.length; n++ ){
                    var item = scope.options.skip[n];
                    var case1 = (item.id == itemData.id && item.type == itemData.type);
                    var case2 = (itemData.parent_id == item.id && item.type == itemData.type);
                    if(case1 ||case2){
                        return true;
                    }
                }
                return false;
            }
        };
        this.prepareData = function(){
            if(typeof this.options.filter === 'object'){
                var final = [], scope = this;
                for( var i in this.options.filter){
                    $.each(this.options.data, function(){
                        if(this[i] && this[i] == scope.options.filter[i]){
                            final.push(this);
                        }
                    });
                }
                this.options.data = final;
            }
        };


        this._postCreated = [];

        this.json2ul = function(){
            this.list = scope.document.createElement( 'ul' );
            this.list._tree = this;
            this.options.id = this.options.id || ( 'mw-tree-' + window.mwtree );
            this.list.id = this.options.id;
            this.list.className = 'mw-defaults mw-tree-nav mw-tree-nav-skin-' + this.options.skin;
            this.list._id = 0;
            this.options.data.forEach(function(item){
                var list = scope.getParent(item);
                if(list){
                    var li = scope.createItem(item);
                    if(li){
                        list.appendChild(li);
                    }
                }
                else if(typeof list === 'undefined'){
                    scope._postCreated.push(item);
                }
            });
            if(this.options._tempRender) {
                this.tempRend();
            }
        };



        this._tempPrepare = function () {
            for (var i=0; i<this._postCreated.length; i++) {
                var it = this._postCreated[i];
                if(it.parent_id !== 0) {
                    var has = this.options.data.find(function (a) {
                        return a.id ==  it.parent_id; // 1 == '1'
                    });
                    if(!has) {
                        it.parent_id = 0;
                        it.parent_type = "page";
                    }
                }
            }
        };

        this.tempRend = function () {
            this._tempPrepare()
            var curr = scope._postCreated[0];
            var max = 10000, itr = 0;

            while(scope._postCreated.length && itr<max){
                itr++;
                var index = scope._postCreated.indexOf(curr);
                var selector = '#' + scope.options.id + '-' + curr.type + '-'  + curr.id;
                var lastc = selector.charAt(selector.length - 1);
                if( lastc === '.' || lastc === '#') {
                    selector = selector.substring(0, selector.length - 1);
                }
                var it = mw.$(selector)[0];
                if(it){
                    scope._postCreated.splice(index, 1);
                    curr = scope._postCreated[0];
                    continue;
                }
                var list = scope.getParent(curr);

                if(list && !$(selector)[0]){
                    var li = scope.createItem(curr);
                    if(li){
                        list.appendChild(li);
                    }
                    scope._postCreated.splice(index, 1);
                    curr = scope._postCreated[0];
                }
                else if(typeof list === 'undefined'){
                    curr = scope._postCreated[index+1] || scope._postCreated[0];
                }
            }

        };

        function triggerChange() {
            if(!this._selectionChangeDisable) {
                mw.$(scope).trigger('selectionChange', [scope.selectedData]);
                scope.dispatch('selectionChange', scope.selectedData)
            }
        }

        this.setData = function(newData){
            this.options.data = newData;
            this._postCreated = [];
            this._ids = [];
            this.init();
        };

        this.saveState = function(){
            if(!this.options.saveState) return;
            var data = [];
            mw.$( 'li.' + this.options.openedClass, this.list  ).each(function(){
                if(this._data) {
                    data.push({type:this._data.type, id:this._data.id})
                }
            });

            mw.storage.set(this.options.id, data);
        };

        this.restoreState = function(){
            if(!this.options.saveState) return;
            var data = mw.storage.get(this.options.id);
            if(!data) return;
            try{
                $.each(data, function(){
                    if(typeof this.id === 'string'){
                        this.id = parseInt(this.id, 10);
                    }
                    scope.open(this.id, this.type);
                });
            }
            catch(e){ }
        };

        this.manageUnselected = function(){
            mw.$('input:not(:checked)', this.list).each(function(){
                var li = scope.parentLi(this);
                mw.$(li).removeClass(scope.options.selectedClass)
            });
        };

        this.analizeLi = function(li){
            if(typeof li === 'string'){
                li = decodeURIComponent(li).trim();
                if(/^\d+$/.test(li)){
                    li = parseInt(li, 10);
                }
                else{
                    return mw.$(li)[0];
                }
            }
            return li;
        };

        this.select = function(li, type){
            if(Array.isArray(li)){
                $.each(li, function(){
                    scope.select(this);
                });
                return;
            }
            li = this.get(li, type);
            if(li && this.options.cantSelectTypes.indexOf(li.dataset.type) === -1){
                li.classList.add(this.options.selectedClass);
                var input = li.querySelector('input');
                if(input) input.checked = true;
            }

            this.manageUnselected();
            this.getSelected();
            triggerChange();
        };



        this.unselect = function(li, type){
            if(Array.isArray(li)){
                $.each(li, function(){
                    scope.unselect(this);
                });
                return;
            }
            li = this.get(li, type);
            if(li){
                li.classList.remove(this.options.selectedClass);
                var input = li.querySelector('input');
                if(input) input.checked = false;
            }
            this.manageUnselected();
            this.getSelected();
            triggerChange();
        };

        this.get = function(li, type){
            if(typeof li === 'undefined') return false;
            if(li === null) return false;
            if(li.nodeType) return li;
            li = this.analizeLi(li);
            if(typeof li === 'object' && typeof li.id !== 'undefined'){
                return this.get(li.id, li.type);
            }
            if(typeof li === 'object' && li.constructor === Number){
                li = parseInt(li);
            }
            if(typeof li === 'number'){
                if(!type) return;
                return this.list.querySelector('li[data-type="'+type+'"][data-id="'+li+'"]');
            }
            if(typeof li === 'string' && /^\d+$/.test(li)){
                if(!type) return;
                return this.list.querySelector('li[data-type="'+type+'"][data-id="'+li+'"]');
            }
            //if(!li) {console.warn('List item not defined:', li, type)}
            return li;
        };

        this.isSelected = function(li, type){
            li = this.get(li, type);
            if(!li) return;
            var input = li.querySelector('input');
            if(!input) return false;
            return input.checked === true;
        };
        this.toggleSelect = function(li, type){
            if(this.isSelected(li, type)){
                this.unselect(li, type)
            }
            else{
                this.select(li, type)
            }
        };

        this.selectAll = function(){
            this._selectionChangeDisable = true;
            this.select(this.options.data);
            this._selectionChangeDisable = false;
            triggerChange()
        };

        this.unselectAll = function(){
            this._selectionChangeDisable = true;
            this.unselect(this.selectedData);
            this._selectionChangeDisable = false;
            triggerChange()
        };

        this.open = function(li, type, _skipsave){
            if(Array.isArray(li)){
                $.each(li, function(){
                    scope.open(this);
                });
                return;
            }
            li = this.get(li, type);
            if(!li) return;
            li.classList.add(this.options.openedClass);
            if(!_skipsave) this.saveState();
        };
        this.show = function(li, type){
            if(Array.isArray(li)){
                $.each(li, function(){
                    scope.show(this);
                });
                return;
            }
            li = this.get(li, type);
            if(!li) return;
            li.classList.remove('mw-tree-item-hidden');
            mw.$(li).parents(".mw-tree-item-hidden").removeClass('mw-tree-item-hidden').each(function(){
                scope.open(this);
            });
        };

        this.showAll = function(){
            mw.$(this.list.querySelectorAll('li')).removeClass('mw-tree-item-hidden');
        };

        this.hide = function(li, type){
            if(Array.isArray(li)){
                $.each(li, function(){
                    scope.hide(this);
                });
                return;
            }
            li = this.get(li, type);
            if(!li) return;
            li.classList.add('mw-tree-item-hidden');
        };

        this.hideAll = function(){
            mw.$(this.list.querySelectorAll('li')).addClass('mw-tree-item-hidden');
        };

        this.close = function(li,type, _skipsave){
            if(Array.isArray(li)){
                $.each(li, function(){
                    scope.close(this);
                });
                return;
            }
            li = this.get(li, type);
            if(!li) return;
            li.classList.remove(this.options.openedClass);
            if(!_skipsave) this.saveState();
        };

        this.toggle = function(li, type){
            li = this.get(li, type);
            if(!li) return;
            li.classList.toggle(this.options.openedClass);
            this.saveState();
        };

        this.openAll = function(){
            var all = this.list.querySelectorAll('li');
            mw.$(all).each(function(){
                scope.open(this, undefined, true);
            });
            this.saveState();
        };

        this.closeAll = function(){
            var all = this.list.querySelectorAll('li.'+this.options.openedClass);
            mw.$(all).each(function(){
                scope.close(this, undefined, true);
            });
            this.saveState();
        };

        this.button = function(){
            var btn = scope.document.createElement('mwbutton');
            btn.className = 'mw-tree-toggler';
            btn.onclick = function(){
                scope.toggle(mw.tools.firstParentWithTag(this, 'li'));
            };
            return btn;
        };

        this.addButtons = function(){
            var all = this.list.querySelectorAll('li ul.pre-init'), i=0;
            for( ; i<all.length; i++ ){
                var ul = all[i];
                ul.classList.remove('pre-init');
                mw.$(ul).parent().children('.mw-tree-item-content-root').prepend(this.button());
            }
        };

        this.checkBox = function(element){
            if(this.options.cantSelectTypes.indexOf(element.dataset.type) !== -1){
                return scope.document.createTextNode('');
            }
            var itype = 'radio';
            if(this.options.singleSelect){

            }
            else if(this.options.multiPageSelect || element._data.type !== 'page'){
                itype = 'checkbox';
            }
            var label = scope.document.createElement('tree-label');
            var input = scope.document.createElement('input');
            var span = scope.document.createElement('span');
            input.type = itype;
            input._data = element._data;
            input.value = element._data.id;
            input.name = this.list.id;
            label.className = 'mw-ui-check';

            label.appendChild(input);


            label.appendChild(span);

            /*input.onchange = function(){
                var li = scope.parentLi(this);
                mw.$(li)[this.checked?'addClass':'removeClass'](scope.options.selectedClass)
                var data = scope.getSelected();
                scope.manageUnselected()
                mw.$(scope).trigger('change', [data]);
            }*/
            return label;
        };

        this.parentLi = function(scope){
            if(!scope) return;
            if(scope.nodeName === 'LI'){
                return scope;
            }
            while(scope.parentNode){
                scope = scope.parentNode;
                if(scope.nodeName === 'LI'){
                    return scope;
                }
            }
        };

        this.getSelected = function(){
            var selected = [];
            var all = this.list.querySelectorAll('li.selected');
            mw.$(all).each(function(){
                if(this._data) selected.push(this._data);
            });
            this.selectedData = selected;
            this.options.selectedData = selected;
            return selected;
        };

        this.decorate = function(element){
            if(this.options.selectable){
                mw.$(element.querySelector('.mw-tree-item-content')).prepend(this.checkBox(element))
            }

            element.querySelector('.mw-tree-item-content').appendChild(this.contextMenu(element));

            if(this.options.sortable){
                this.sortable();
            }
            if(this.options.nestedSortable){
                this.nestedSortable();
            }

        };

        this.sortable = function(element){
            var items = mw.$(this.list);
            mw.$('ul', this.list).each(function () {
                items.push(this);
            });
            items.sortable({
                items: ".type-category, .type-page",
                axis:'y',
                listType:'ul',
                handle:'.mw-tree-item-title',
                update:function(e, ui){
                    setTimeout(function(){
                        var old = $.extend({},ui.item[0]._data);
                        var obj = ui.item[0]._data;
                        var objParent = ui.item[0].parentNode.parentNode._data;
                        ui.item[0].dataset.parent_id = objParent ? objParent.id : 0;

                        obj.parent_id = objParent ? objParent.id : 0;
                        obj.parent_type = objParent ? objParent.id : 'page';
                        var newdata = [];
                        mw.$('li', scope.list).each(function(){
                            if(this._data) newdata.push(this._data)
                        });
                        scope.options.data = newdata;
                        var local = [];
                        mw.$(ui.item[0].parentNode).children('li').each(function(){
                            if(this._data) {
                                local.push(this._data.id);
                            }
                        });
                        //$(scope.list).remove();
                        //scope.init();
                        mw.$(scope).trigger('orderChange', [obj, scope.options.data, old, local])
                    }, 110);

                }
            });
        };
        this.nestedSortable = function(element){
            mw.$('ul', this.list).nestedSortable({
                items: ".type-category",
                listType:'ul',
                handle:'.mw-tree-item-title',
                update:function(e, ui){

                }
            })
        };

        this.contextMenu = function(element){
            var menu = scope.document.createElement('span');
            menu.className = 'mw-tree-context-menu';
            if(this.options.contextMenu){
                $.each(this.options.contextMenu, function(){
                    var menuitem = scope.document.createElement('span');
                    var icon = scope.document.createElement('span');
                    menuitem.title = this.title;
                    menuitem.className = 'mw-tree-context-menu-item';
                    icon.className = this.icon;
                    menuitem.appendChild(icon);
                    menu.appendChild(menuitem);
                    (function(menuitem, element, obj){
                        menuitem.onclick = function(){
                            if(obj.action){
                                obj.action.call(element, element, element._data, menuitem)
                            }
                        }
                    })(menuitem, element, this);
                });
            }
            return menu

        };

        this.rend = function(){
            if(this.options.element){
                var el = mw.$(this.options.element);
                if(el.length!==0){
                    el.empty().append(this.list);
                }
            }
        };

        this._ids = [];

        this._createSingle = function (item) {

        }

        this.createItem = function(item){
            var selector = '#'+scope.options.id + '-' + item.type+'-'+item.id;
            if(this._ids.indexOf(selector) !== -1) return false;
            this._ids.push(selector);
            var li = scope.document.createElement('li');
            li.dataset.type = item.type;
            li.dataset.id = item.id;
            li.dataset.parent_id = item.parent_id;
            var skip = this.skip(item);
            li.className = 'type-' + item.type + ' subtype-'+ item.subtype + ' skip-' + (skip || 'none');
            var container = scope.document.createElement('span');
            container.className = "mw-tree-item-content";
            container.innerHTML = '<span class="mw-tree-item-title">'+item.title+'</span>';

            li._data = item;
            li.id = scope.options.id + '-' + item.type+'-'+item.id;
            li.appendChild(container);
            $(container).wrap('<span class="mw-tree-item-content-root"></span>')
            if(!skip){
                container.onclick = function(){
                    if(scope.options.selectable) scope.toggleSelect(li)
                };
                this.decorate(li);
            }


            return li;
        };



        this.additional = function (obj) {
            var li = scope.document.createElement('li');
            li.className = 'mw-tree-additional-item';
            var container = scope.document.createElement('span');
            var containerTitle = scope.document.createElement('span');
            container.className = "mw-tree-item-content";
            containerTitle.className = "mw-tree-item-title";
            container.appendChild(containerTitle);

            li.appendChild(container);
            $(container).wrap('<span class="mw-tree-item-content-root"></span>')
            if(obj.icon){
                if(obj.icon.indexOf('</') === -1){
                    var icon = scope.document.createElement('span');
                    icon.className = 'mw-tree-aditional-item-icon ' + obj.icon;
                    containerTitle.appendChild(icon);
                }
                else{
                    mw.$(containerTitle).append(obj.icon)
                }

            }
            var title = scope.document.createElement('span');
            title.innerHTML = obj.title;
            containerTitle.appendChild(title);
            li.onclick = function (ev) {
                if(obj.action){
                    obj.action.call(this, obj)
                }
            };
            return li;
        }

        this.createList = function(item){
            var nlist = scope.document.createElement('ul');
            nlist.dataset.type = item.parent_type;
            nlist.dataset.id = item.parent_id;
            nlist.className = 'pre-init';
            return nlist;
        };

        this.getParent = function(item, isTemp){
            if(!item.parent_id) return this.list;
            var findul = this.list.querySelector('ul[data-type="'+item.parent_type+'"][data-id="'+item.parent_id+'"]');
            var findli = this.list.querySelector('li[data-type="'+item.parent_type+'"][data-id="'+item.parent_id+'"]');
            if(findul){
                return findul;
            }
            else if(findli){
                var nlistwrap = this.createItem(item);
                if(!nlistwrap) return false;
                var nlist = this.createList(item);
                nlist.appendChild(nlistwrap);
                findli.appendChild(nlist);
                return false;
            }
        };

        this.append = function(){
            if(this.options.append){
                $.each(this.options.append, function(){
                    scope.list.appendChild(scope.additional(this))
                })
            }
        };

        this.prepend = function(){
            if(this.options.prepend){
                $.each(this.options.append, function(){
                    mw.$(scope.list).prepend(scope.additional(this))
                })
            }
        };

        this.addHelperClasses = function(root, level){
            level = (level || 0) + 1;
            root = root || this.list;
            mw.$( root.children ).addClass('level-'+level).each(function(){
                var ch = this.querySelector('ul');
                if(ch){
                    mw.$(this).addClass('has-children')
                    scope.addHelperClasses(ch, level);
                }
            })
        };

        this.loadSelected = function(){
            if(this.selectedData){
                scope.select(this.selectedData);
            }
        };
        this.init = function(){

            this.prepareData();
            this.json2ul();
            this.addButtons();
            this.rend();
            this.append();
            this.prepend();
            this.addHelperClasses();
            this.restoreState();
            this.loadSelected();
            this.search();
            setTimeout(function(){
                mw.$(scope).trigger('ready');
            }, 78)
        };

        this.config(config);
        this.init();
    };
    mw.tree = mwtree;
    mw.tree.get = function (a) {
        a = mw.$(a)[0];
        if(!a) return;
        if(mw.tools.hasClass(a, 'mw-tree-nav')){
            return a._tree;
        }
        var child = a.querySelector('.mw-tree-nav');
        if(child) return child._tree;
        var parent = mw.tools.firstParentWithClass(a, 'mw-tree-nav');

        if(parent) {
            return parent._tree;
        }
    }


})();

})();

(() => {
/*!******************************************************************!*\
  !*** ../userfiles/modules/microweber/api/widgets/uiaccordion.js ***!
  \******************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.uiAccordion = function (options) {
    if (!options) return;
    options.element = options.element || '.mw-accordion';

    var scope = this;



    this.getContents = function () {
        this.contents = this.root.children(this.options.contentSelector);
        if (!this.contents.length) {
            this.contents = mw.$();
            this.root.children(this.options.itemSelector).each(function () {
                var title = mw.$(this).children(scope.options.contentSelector)[0];
                if (title) {
                    scope.contents.push(title)
                }
            });
        }
    }
    this.getTitles = function () {
        this.titles = this.root.children(this.options.titleSelector);
        if (!this.titles.length) {
            this.titles = mw.$();
            this.root.children(this.options.itemSelector).each(function () {
                var title = mw.$(this).children(scope.options.titleSelector)[0];
                if (title) {
                    scope.titles.push(title)
                }
            });
        }
    };

    this.prepare = function (options) {
        var defaults = {
            multiple: false,
            itemSelector: ".mw-accordion-item,mw-accordion-item",
            titleSelector: ".mw-accordion-title,mw-accordion-title",
            contentSelector: ".mw-accordion-content,mw-accordion-content",
            openFirst: true,
            toggle: true
        };
        this.options = $.extend({}, defaults, options);

        this.root = mw.$(this.options.element).not('.mw-accordion-ready').eq(0);
        if (!this.root.length) return;
        this.root.addClass('mw-accordion-ready');
        this.root[0].uiAccordion = this;
        this.getTitles();
        this.getContents();

    };

    this.getItem = function (q) {
        var item;
        if (typeof q === 'number') {
            item = this.contents.eq(q)
        }
        else {
            item = mw.$(q);
        }
        return item;
    };

    this.set = function (index) {
        var item = this.getItem(index);
        if (!this.options.multiple) {
            this.contents.not(item)
                .slideUp()
                .removeClass('active')
                .prev()
                .removeClass('active')
                .parents('.mw-accordion-item').eq(0)
                .removeClass('active');
        }
        item.stop()
            .slideDown()
            .addClass('active')
            .prev()
            .addClass('active')
            .parents('.mw-accordion-item').eq(0)
            .addClass('active');
        mw.$(this).trigger('accordionSet', [item]);
    };

    this.unset = function (index) {
        if (typeof index === 'undefined') return;
        var item = this.getItem(index);
        item.stop()
            .slideUp()
            .removeClass('active')
            .prev()
            .removeClass('active')
            .parents('.mw-accordion-item').eq(0)
            .removeClass('active');
        ;
        mw.$(this).trigger('accordionUnset', [item]);
    }

    this.toggle = function (index) {
        var item = this.getItem(index);
        if (item.hasClass('active')) {
            if (this.options.toggle) {
                this.unset(item)
            }
        }
        else {
            this.set(item)
        }
    }

    this.init = function (options) {
        this.prepare(options);
        if(typeof(this.contents) !== 'undefined'){
            this.contents.hide()
        }

        if (this.options.openFirst) {
            if(typeof(this.contents) !== 'undefined'){
                this.contents.eq(0).show().addClass('active')
            }
            if(typeof(this.titles) !== 'undefined'){
                this.titles.eq(0).addClass('active').parent('.mw-accordion-item').addClass('active');
            }
        }
        if(typeof(this.titles) !== 'undefined') {
            this.titles.on('click', function () {
                scope.toggle(scope.titles.index(this));
            });
        }
    }

    this.init(options);

};


mw.tabAccordion = function (options, accordion) {
    if (!options) return;
    var scope = this;
    this.options = options;

    this.options.breakPoint = this.options.breakPoint || 800;
    this.options.activeClass = this.options.activeClass || 'active-info';


    this.buildAccordion = function () {
        this.accordion = accordion || new mw.uiAccordion(this.options);
    }

    this.breakPoint = function () {
        if (matchMedia("(max-width: " + this.options.breakPoint + "px)").matches) {
            mw.$(this.nav).hide();
            mw.$(this.accordion.titles).show();
        }
        else {
            mw.$(this.nav).show();
            mw.$(this.accordion.titles).hide();
        }
    }

    this.createTabButton = function (content, index) {
        this.buttons = this.buttons || mw.$();
        var btn = document.createElement('span');
        this.buttons.push(btn)
        var size = (this.options.tabsSize ? ' mw-ui-btn-' + this.options.tabsSize : '');
        var color = (this.options.tabsColor ? ' mw-ui-btn-' + this.options.tabsColor : '');
        var active = (index === 0 ? (' ' + this.options.activeClass) : '');
        btn.className = 'mw-ui-btn' + size + color + active;
        btn.innerHTML = content;
        btn.onclick = function () {
            scope.buttons.removeClass(scope.options.activeClass).eq(index).addClass(scope.options.activeClass);
            scope.accordion.set(index);
        }
        return btn;
    }

    this.createTabs = function () {
        this.nav = document.createElement('div');
        this.nav.className = 'mw-ui-btn-nav mw-ui-btn-nav-tabs';
        mw.$(this.accordion.titles)
            .each(function (i) {
                scope.nav.appendChild(scope.createTabButton($(this).html(), i))
            })
            .hide();
        mw.$(this.accordion.root).before(this.nav)
    }

    this.init = function () {
        this.buildAccordion();
        this.createTabs();
        this.breakPoint();
        mw.$(window).on('load resize orientationchange', function () {
            scope.breakPoint();
        });
    };

    this.init();
};

})();

(() => {
/*!***************************************************************!*\
  !*** ../userfiles/modules/microweber/api/widgets/uploader.js ***!
  \***************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
;(function (){

    var Uploader = function( options ) {
        //var upload = function( url, data, callback, type ) {
        options = options || {};
        options.accept = options.accept || options.filetypes;
        var defaults = {
            multiple: false,
            progress: null,
            element: null,
            url: options.url || (mw.settings.site_url + 'plupload'),
            urlParams: {},
            on: {},
            autostart: true,
            async: true,
            accept: '*',
            chunkSize: 1500000,
        };

        var normalizeAccept = function (type) {
            type = (type || '').trim().toLowerCase();
            if(!type) return '*';
            if (type === 'image' || type === 'images') return '.png,.gif,.jpg,.jpeg,.tiff,.bmp,.svg';
            if (type === 'video' || type === 'videos') return '.mp4,.webm,.ogg,.wma,.mov,.wmv';
            if (type === 'document' || type === 'documents') return '.doc,.docx,.log,.pdf,.msg,.odt,.pages,' +
                '.rtf,.tex,.txt,.wpd,.wps,.pps,.ppt,.pptx,.xml,.htm,.html,.xlr,.xls,.xlsx';

            return '*';
        };

        var scope = this;
        this.settings = $.extend({}, defaults, options);
        this.settings.accept = normalizeAccept(this.settings.accept);

        this.getUrl = function () {
            var params = this.urlParams();
            var empty = mw.tools.isEmptyObject(params);
            return this.url() + (empty ? '' : ('?' + $.param(params)));
        };

        this.urlParam = function (param, value) {
            if(typeof value === 'undefined') {
                return this.settings.urlParams[param];
            }
            this.settings.urlParams[param] = value;
        };

        this.urlParams = function (params) {
            if(!params) {
                return this.settings.urlParams;
            }
            this.settings.urlParams = params;
        };

        this.url = function (url) {
            if(!url) {
                return this.settings.url;
            }
            this.settings.url = url;
        };

        this.create = function () {
            this.input = document.createElement('input');
            this.input.multiple = this.settings.multiple;
            this.input.accept = this.settings.accept;
            this.input.type = 'file';
            this.input.className = 'mw-uploader-input';
            this.input.oninput = function () {
                scope.addFiles(this.files);
            };
        };

        this.files = [];
        this._uploading = false;
        this.uploading = function (state) {
            if(typeof state === 'undefined') {
                return this._uploading;
            }
            this._uploading = state;
        };

        this._validateAccept = this.settings.accept
            .toLowerCase()
            .replace(/\*/g, '')
            .replace(/ /g, '')
            .split(',')
            .filter(function (item) {
                return !!item;
            });
        this.validate = function (file) {
            if(!file) return false;
            var ext = '.' + file.name.split('.').pop().toLowerCase();
            if (this._validateAccept.length === 0) {
                return true;
            }
            for (var i = 0; i < this._validateAccept.length; i++) {
                var item =  this._validateAccept[i];
                if(item === ext) {
                    return true;
                }
                else if(file.type.indexOf(item) === 0) {
                    return true;
                }
            }
            return false;

        };

        this.addFile = function (file) {
            if(this.validate(file)) {
                if(!this.files.length || this.settings.multiple){
                    this.files.push(file);
                    if(this.settings.on.fileAdded) {
                        this.settings.on.fileAdded(file);
                    }
                    $(scope).trigger('FileAdded', file);
                } else {
                    this.files = [file];
                    $(scope).trigger('FileAdded', file);
                    if(this.settings.on.fileAdded) {
                        this.settings.on.fileAdded(file);
                    }
                }
            }
        };

        this.addFiles = function (files) {
            if(!files || !files.length) return;

            if(!this.settings.multiple) {
                files = [files[0]];
            }
            if (files && files.length) {
                for (var i = 0; i < files.length; i++) {
                    scope.addFile(files[i]);
                }
                if(this.settings.on.filesAdded) {
                    if(this.settings.on.filesAdded(files) === false) {
                        return;
                    }
                }
                $(scope).trigger('FilesAdded', files);
                if(this.settings.autostart) {
                    this.uploadFiles();
                }
            }
        };

        this.build = function () {
            if(this.settings.element) {
                this.$element = $(this.settings.element);
                this.element = this.$element[0];
                if(this.element) {
                    this.$element/*.empty()*/.append(this.input);
                }
            }
        };

        this.show = function () {
            this.$element.show();
        };

        this.hide = function () {
            this.$element.hide();
        };

        this.initDropZone = function () {
            if (!!this.settings.dropZone) {
                mw.$(this.settings.dropZone).each(function () {
                    $(this).on('dragover', function (e) {
                        e.preventDefault();
                    }).on('drop', function (e) {
                        var dt = e.dataTransfer || e.originalEvent.dataTransfer;
                        e.preventDefault();
                        if (dt && dt.items) {
                            var files = [];
                            for (var i = 0; i < dt.items.length; i++) {
                                if (dt.items[i].kind === 'file') {
                                    var file = dt.items[i].getAsFile();
                                    files.push(file);
                                }
                            }
                            scope.addFiles(files);
                        } else  if (dt && dt.files)  {
                            scope.addFiles(dt.files);
                        }
                    })
                });
            }
        };


        this.init = function() {
            this.create();
            this.build();
            this.initDropZone();
        };

        this.init();

        this.removeFile = function (file) {
            var i = this.files.indexOf(file);
            if (i > -1) {
                this.files.splice(i, 1);
            }
        };

        this.uploadFile = function (file, done, chunks, _all, _i) {
            chunks = chunks || this.sliceFile(file);
            _all = _all || chunks.length;
            _i = _i || 0;
            var chunk = chunks.shift();
            var data = {
                name: file.name,
                chunk: _i,
                chunks: _all,
                file: chunk,
            };
            _i++;
            $(scope).trigger('uploadStart', [data]);

            this.upload(data, function (res) {
                var dataProgress;
                if(chunks.length) {
                    scope.uploadFile(file, done, chunks, _all, _i);
                    dataProgress = {
                        percent: ((100 * _i) / _all).toFixed()
                    };
                    $(scope).trigger('progress', [dataProgress, res]);
                    if(scope.settings.on.progress) {
                        scope.settings.on.progress(dataProgress, res);
                    }

                } else {
                    dataProgress = {
                        percent: '100'
                    };
                    $(scope).trigger('progress', [dataProgress, res]);
                    if(scope.settings.on.progress) {
                        scope.settings.on.progress(dataProgress, res);
                    }
                    $(scope).trigger('FileUploaded', [res]);
                    if(scope.settings.on.fileUploaded) {
                        scope.settings.on.fileUploaded(res);
                    }
                    done.call(file);
                }
            });

        };

        this.sliceFile = function(file) {
            var byteIndex = 0;
            var chunks = [];
            var chunksAmount = file.size <= this.settings.chunkSize ? 1 : ((file.size / this.settings.chunkSize) >> 0) + 1;

            for (var i = 0; i < chunksAmount; i ++) {
                var byteEnd = Math.ceil((file.size / chunksAmount) * (i + 1));
                chunks.push(file.slice(byteIndex, byteEnd));
                byteIndex += (byteEnd - byteIndex);
            }

            return chunks;
        };

        this.uploadFiles = function () {
            if (this.settings.async) {
                if (this.files.length) {
                    this.uploading(true);
                    this.uploadFile(this.files[0], function () {
                        scope.files.shift();
                        scope.uploadFiles();
                    });
                } else {
                    this.uploading(false);
                    scope.input.value = '';
                    if(scope.settings.on.filesUploaded) {
                        scope.settings.on.filesUploaded();
                    }
                    $(scope).trigger('FilesUploaded');

                }
            } else {
                var count = 0;
                var all = this.files.length;
                this.uploading(true);
                this.files.forEach(function (file) {
                    scope.uploadFile(file, function () {
                        count++;
                        scope.uploading(false);
                        if(all === count) {
                            scope.input.value = '';
                            if(scope.settings.on.filesUploaded) {
                                scope.settings.on.filesUploaded();
                            }
                            $(scope).trigger('FilesUploaded');
                        }
                    });
                });
            }
        };


        this.upload = function (data, done) {
            if (!this.settings.url) {
                return;
            }
            var pdata = new FormData();
            $.each(data, function (key, val) {
                pdata.append(key, val);
            });
            if(scope.settings.on.uploadStart) {
                if (scope.settings.on.uploadStart(pdata) === false) {
                    return;
                }
            }

            return $.ajax({
                url: this.getUrl(),
                type: 'post',
                processData: false,
                contentType: false,
                data: pdata,
                success: function (res) {
                    scope.removeFile(data.file);
                    if(done) {
                        done.call(res, res);
                    }
                },
                dataType: 'json',
                xhr: function () {
                    var xhr = new XMLHttpRequest();
                    xhr.upload.addEventListener('progress', function (event) {
                        if (event.lengthComputable) {
                            var percent = (event.loaded / event.total) * 100;
                            if(scope.settings.on.progressNative) {
                                scope.settings.on.progressNative(percent, event);
                            }
                            $(scope).trigger('progressNative', [percent, event]);
                        }
                    });
                    return xhr;
                }
            });
        };
    };

    mw.upload = function (options) {
        return new Uploader(options);
    };


})();

})();

/******/ })()
;
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly9taWNyb3dlYmVyLXdlYnBhY2svLi4vdXNlcmZpbGVzL21vZHVsZXMvbWljcm93ZWJlci9hcGkvd2lkZ2V0cy9hdXRvY29tcGxldGUuanMiLCJ3ZWJwYWNrOi8vbWljcm93ZWJlci13ZWJwYWNrLy4uL3VzZXJmaWxlcy9tb2R1bGVzL21pY3Jvd2ViZXIvYXBpL3dpZGdldHMvY29tbW9uLmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS93aWRnZXRzL2RpYWxvZy5qcyIsIndlYnBhY2s6Ly9taWNyb3dlYmVyLXdlYnBhY2svLi4vdXNlcmZpbGVzL21vZHVsZXMvbWljcm93ZWJlci9hcGkvd2lkZ2V0cy9nYWxsZXJ5LmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS93aWRnZXRzL2ljb25fc2VsZWN0b3IuanMiLCJ3ZWJwYWNrOi8vbWljcm93ZWJlci13ZWJwYWNrLy4uL3VzZXJmaWxlcy9tb2R1bGVzL21pY3Jvd2ViZXIvYXBpL3dpZGdldHMvc2VsZWN0LmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS93aWRnZXRzL3NwaW5uZXIuanMiLCJ3ZWJwYWNrOi8vbWljcm93ZWJlci13ZWJwYWNrLy4uL3VzZXJmaWxlcy9tb2R1bGVzL21pY3Jvd2ViZXIvYXBpL3dpZGdldHMvdGFncy5qcyIsIndlYnBhY2s6Ly9taWNyb3dlYmVyLXdlYnBhY2svLi4vdXNlcmZpbGVzL21vZHVsZXMvbWljcm93ZWJlci9hcGkvd2lkZ2V0cy90b29sdGlwLmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS93aWRnZXRzL3RyZWUuanMiLCJ3ZWJwYWNrOi8vbWljcm93ZWJlci13ZWJwYWNrLy4uL3VzZXJmaWxlcy9tb2R1bGVzL21pY3Jvd2ViZXIvYXBpL3dpZGdldHMvdWlhY2NvcmRpb24uanMiLCJ3ZWJwYWNrOi8vbWljcm93ZWJlci13ZWJwYWNrLy4uL3VzZXJmaWxlcy9tb2R1bGVzL21pY3Jvd2ViZXIvYXBpL3dpZGdldHMvdXBsb2FkZXIuanMiXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6Ijs7Ozs7OztBQUFBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxrQkFBa0IsNEJBQTRCO0FBQzlDO0FBQ0E7QUFDQTtBQUNBO0FBQ0Esa0NBQWtDO0FBQ2xDO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQSx1Q0FBdUM7QUFDdkM7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FB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QTtBQUNBO0FBQ0EscUJBQXFCO0FBQ3JCO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakIsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsMENBQTBDLGlIQUFpSDtBQUMzSjtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTs7OztBQUlBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOzs7O0FBSUE7OztBQUdBOztBQUVBOzs7QUFHQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0EsS0FBSztBQUNMLENBQUM7OztBQUdEO0FBQ0E7QUFDQTs7Ozs7Ozs7OztBQ3hjQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7O0FBRUE7QUFDQSx3SEFBd0g7QUFDeEg7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTs7Ozs7Ozs7Ozs7O0FDckVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7QUFHQTs7QUFFQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0EsNENBQTRDO0FBQzVDO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYSw4Q0FBOEM7QUFDM0QsYUFBYSxpRUFBaUU7QUFDOUUsYUFBYSwrQkFBK0I7QUFDNUM7QUFDQTs7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUNBQWlDLFFBQVE7QUFDekM7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EscUJBQXFCO0FBQ3JCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7Ozs7QUFJQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7QUFHQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOzs7O0FBSUE7QUFDQTtBQUNBOzs7QUFHQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBOztBQUVBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsVUFBVTtBQUNWO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQSxrQ0FBa0MsaUJBQWlCLG1CQUFtQjtBQUN0RSxrQ0FBa0MsaUJBQWlCLHlEQUF5RDs7QUFFNUc7O0FBRUE7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0EsTUFBTTtBQUNOO0FBQ0E7QUFDQSxLQUFLOztBQUVMOzs7Ozs7Ozs7O0FDclFBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLHFCQUFxQjtBQUNyQjtBQUNBOztBQUVBOzs7QUFHQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EscUJBQXFCO0FBQ3JCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxxQkFBcUI7QUFDckI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHFCQUFxQjtBQUNyQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EscUJBQXFCO0FBQ3JCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxxQkFBcUI7QUFDckI7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxxQkFBcUI7QUFDckI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHFCQUFxQjtBQUNyQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EscUJBQXFCO0FBQ3JCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxxQkFBcUI7QUFDckI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHFCQUFxQjtBQUNyQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EscUJBQXFCO0FBQ3JCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxxQkFBcUI7QUFDckI7QUFDQTtBQUNBO0FBQ0E7QUFD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QUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0Esa0JBQWtCLGNBQWM7QUFDaEM7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOzs7QUFHQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSw2Q0FBNkM7QUFDN0M7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSx5QkFBeUI7QUFDekI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EseUJBQXlCO0FBQ3pCO0FBQ0E7QUFDQTtBQUNBLHFCQUFxQjs7QUFFckI7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQSxhQUFhO0FBQ2I7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EscUJBQXFCO0FBQ3JCLGlCQUFpQjtBQUNqQjtBQUNBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7Ozs7QUFJQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7OztBQUdBLENBQUM7Ozs7Ozs7Ozs7QUM3eUJEO0FBQ0E7QUFDQTs7QUFFQTs7OztBQUlBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0Esa0NBQWtDOztBQUVsQztBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBOztBQUVBOztBQUVBOzs7QUFHQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOzs7QUFHQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUOztBQUVBO0FBQ0E7Ozs7Ozs7Ozs7QUN0TUEsQ0FBQzs7QUFFRDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSx5QkFBeUI7QUFDekIsa0JBQWtCO0FBQ2xCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBLG1DQUFtQztBQUNuQzs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsMkJBQTJCLGlDQUFpQztBQUM1RDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQSwrQkFBK0Isa0JBQWtCO0FBQ2pEO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxxQkFBcUI7QUFDckI7QUFDQTtBQUNBO0FBQ0E7QUFDQSwyQ0FBMkMscUJBQXFCO0FBQ2hFO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHlCQUF5QjtBQUN6QjtBQUNBO0FBQ0EscUJBQXFCO0FBQ3JCLGlCQUFpQjtBQUNqQjtBQUNBOzs7QUFHQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7O0FBRWI7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUEsMkJBQTJCLGtCQUFrQjtBQUM3QztBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EscUJBQXFCO0FBQ3JCLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHFCQUFxQjtBQUNyQixpQkFBaUI7QUFDakI7QUFDQTs7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxxQkFBcUI7QUFDckI7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7O0FBR0EsQ0FBQyIsImZpbGUiOiJ3aWRnZXRzLmpzIiwic291cmNlc0NvbnRlbnQiOlsibXcuYXV0b0NvbXBsZXRlID0gZnVuY3Rpb24ob3B0aW9ucyl7XG4gICAgdmFyIHNjb3BlID0gdGhpcztcbiAgICB0aGlzLnByZXBhcmUgPSBmdW5jdGlvbihvcHRpb25zKXtcbiAgICAgICAgb3B0aW9ucyA9IG9wdGlvbnMgfHwge307XG4gICAgICAgIGlmKCFvcHRpb25zLmRhdGEgJiYgIW9wdGlvbnMuYWpheENvbmZpZykgcmV0dXJuO1xuICAgICAgICB2YXIgZGVmYXVsdHMgPSB7XG4gICAgICAgICAgICBzaXplOidub3JtYWwnLFxuICAgICAgICAgICAgbXVsdGlwbGU6ZmFsc2UsXG4gICAgICAgICAgICBtYXA6IHsgdGl0bGU6J3RpdGxlJywgdmFsdWU6J2lkJyB9LFxuICAgICAgICAgICAgdGl0bGVEZWNvcmF0b3I6IGZ1bmN0aW9uICh0aXRsZSwgZGF0YSkge1xuICAgICAgICAgICAgICAgIHJldHVybiB0aXRsZTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfTtcbiAgICAgICAgdGhpcy5vcHRpb25zID0gJC5leHRlbmQoe30sIGRlZmF1bHRzLCBvcHRpb25zKTtcbiAgICAgICAgdGhpcy5vcHRpb25zLmVsZW1lbnQgPSBtdy4kKHRoaXMub3B0aW9ucy5lbGVtZW50KVswXTtcbiAgICAgICAgaWYoIXRoaXMub3B0aW9ucy5lbGVtZW50KXtcbiAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgfVxuICAgICAgICB0aGlzLmVsZW1lbnQgPSB0aGlzLm9wdGlvbnMuZWxlbWVudDtcbiAgICAgICAgdGhpcy5kYXRhID0gdGhpcy5vcHRpb25zLmRhdGE7XG4gICAgICAgIHRoaXMuc2VhcmNoVGltZSA9IG51bGw7XG4gICAgICAgIHRoaXMuc2VhcmNoVGltZW91dCA9IHRoaXMub3B0aW9ucy5kYXRhID8gMCA6IDUwMDtcbiAgICAgICAgdGhpcy5yZXN1bHRzID0gW107XG4gICAgICAgIHRoaXMubWFwID0gdGhpcy5vcHRpb25zLm1hcDtcbiAgICAgICAgdGhpcy5zZWxlY3RlZCA9IHRoaXMub3B0aW9ucy5zZWxlY3RlZCB8fCBbXTtcbiAgICB9O1xuXG4gICAgdGhpcy5jcmVhdGVWYWx1ZUhvbGRlciA9IGZ1bmN0aW9uKCl7XG4gICAgICAgIHRoaXMudmFsdWVIb2xkZXIgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgdGhpcy52YWx1ZUhvbGRlci5jbGFzc05hbWUgPSAnbXctYXV0b2NvbXBsZXRlLXZhbHVlJztcbiAgICAgICAgcmV0dXJuIHRoaXMudmFsdWVIb2xkZXI7XG4gICAgfTtcbiAgICB0aGlzLmNyZWF0ZUxpc3RIb2xkZXIgPSBmdW5jdGlvbigpe1xuICAgICAgICB0aGlzLmxpc3RIb2xkZXIgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCd1bCcpO1xuICAgICAgICB0aGlzLmxpc3RIb2xkZXIuY2xhc3NOYW1lID0gJ213LXVpLWJveCBtdy11aS1uYXZpZ2F0aW9uIG13LWF1dG9jb21wbGV0ZS1saXN0JztcbiAgICAgICAgdGhpcy5saXN0SG9sZGVyLnN0eWxlLmRpc3BsYXkgPSAnbm9uZSc7XG4gICAgICAgIHJldHVybiB0aGlzLmxpc3RIb2xkZXI7XG4gICAgfTtcblxuICAgIHRoaXMuY3JlYXRlV3JhcHBlciA9IGZ1bmN0aW9uKCl7XG4gICAgICAgIHRoaXMud3JhcHBlciA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgICAgICB0aGlzLndyYXBwZXIuY2xhc3NOYW1lID0gJ213LXVpLWZpZWxkIHcxMDAgbXctYXV0b2NvbXBsZXRlIG13LWF1dG9jb21wbGV0ZS1tdWx0aXBsZS0nICsgdGhpcy5vcHRpb25zLm11bHRpcGxlO1xuICAgICAgICByZXR1cm4gdGhpcy53cmFwcGVyO1xuICAgIH07XG5cbiAgICB0aGlzLmNyZWF0ZUZpZWxkID0gZnVuY3Rpb24oKXtcbiAgICAgICAgdGhpcy5pbnB1dEZpZWxkID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnaW5wdXQnKTtcbiAgICAgICAgdGhpcy5pbnB1dEZpZWxkLmNsYXNzTmFtZSA9ICdtdy11aS1pbnZpc2libGUtZmllbGQgbXctYXV0b2NvbXBsZXRlLWZpZWxkIG13LXVpLWZpZWxkLScgKyB0aGlzLm9wdGlvbnMuc2l6ZTtcbiAgICAgICAgaWYodGhpcy5vcHRpb25zLnBsYWNlaG9sZGVyKXtcbiAgICAgICAgICAgIHRoaXMuaW5wdXRGaWVsZC5wbGFjZWhvbGRlciA9IHRoaXMub3B0aW9ucy5wbGFjZWhvbGRlcjtcbiAgICAgICAgfVxuICAgICAgICBtdy4kKHRoaXMuaW5wdXRGaWVsZCkub24oJ2lucHV0IGZvY3VzJywgZnVuY3Rpb24oZSl7XG4gICAgICAgICAgICB2YXIgdmFsID0gZS50eXBlID09PSAnZm9jdXMnID8gJycgOiB0aGlzLnZhbHVlO1xuICAgICAgICAgICAgc2NvcGUuc2VhcmNoKHZhbCk7XG4gICAgICAgIH0pO1xuICAgICAgICByZXR1cm4gdGhpcy5pbnB1dEZpZWxkO1xuICAgIH07XG5cbiAgICB0aGlzLmJ1aWxkVUkgPSBmdW5jdGlvbigpe1xuICAgICAgICB0aGlzLmNyZWF0ZVdyYXBwZXIoKTtcbiAgICAgICAgdGhpcy53cmFwcGVyLmFwcGVuZENoaWxkKHRoaXMuY3JlYXRlVmFsdWVIb2xkZXIoKSk7XG4gICAgICAgIHRoaXMud3JhcHBlci5hcHBlbmRDaGlsZCh0aGlzLmNyZWF0ZUZpZWxkKCkpO1xuICAgICAgICB0aGlzLndyYXBwZXIuYXBwZW5kQ2hpbGQodGhpcy5jcmVhdGVMaXN0SG9sZGVyKCkpO1xuICAgICAgICB0aGlzLmVsZW1lbnQuYXBwZW5kQ2hpbGQodGhpcy53cmFwcGVyKTtcbiAgICB9O1xuXG4gICAgdGhpcy5jcmVhdGVMaXN0SXRlbSA9IGZ1bmN0aW9uKGRhdGEpe1xuICAgICAgICB2YXIgbGkgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdsaScpO1xuICAgICAgICBsaS52YWx1ZSA9IHRoaXMuZGF0YVZhbHVlKGRhdGEpO1xuICAgICAgICB2YXIgaW1nID0gdGhpcy5kYXRhSW1hZ2UoZGF0YSk7XG5cbiAgICAgICAgbXcuJChsaSlcbiAgICAgICAgLmFwcGVuZCggJzxhIGhyZWY9XCJqYXZhc2NyaXB0OjtcIj4nK3RoaXMuZGF0YVRpdGxlKGRhdGEpKyc8L2E+JyApXG4gICAgICAgIC5vbignY2xpY2snLCBmdW5jdGlvbigpe1xuICAgICAgICAgICAgc2NvcGUuc2VsZWN0KGRhdGEpO1xuICAgICAgICB9KTtcbiAgICAgICAgaWYoaW1nKXtcbiAgICAgICAgICAgIG13LiQoJ2EnLGxpKS5wcmVwZW5kKGltZyk7XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIGxpO1xuICAgIH07XG5cbiAgICB0aGlzLnVuaXF1ZVZhbHVlID0gZnVuY3Rpb24oKXtcbiAgICAgICAgdmFyIHVuaXF1ZUlkcyA9IFtdLCBmaW5hbCA9IFtdO1xuICAgICAgICB0aGlzLnNlbGVjdGVkLmZvckVhY2goZnVuY3Rpb24oaXRlbSl7XG4gICAgICAgICAgICB2YXIgdmFsID0gc2NvcGUuZGF0YVZhbHVlKGl0ZW0pO1xuICAgICAgICAgICAgaWYodW5pcXVlSWRzLmluZGV4T2YodmFsKSA9PT0gLTEpe1xuICAgICAgICAgICAgICAgIHVuaXF1ZUlkcy5wdXNoKHZhbCk7XG4gICAgICAgICAgICAgICAgZmluYWwucHVzaChpdGVtKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgICAgIHRoaXMuc2VsZWN0ZWQgPSBmaW5hbDtcbiAgICB9O1xuXG4gICAgdGhpcy5zZWxlY3QgPSBmdW5jdGlvbihpdGVtKXtcbiAgICAgICAgaWYodGhpcy5vcHRpb25zLm11bHRpcGxlKXtcbiAgICAgICAgICAgIHRoaXMuc2VsZWN0ZWQucHVzaChpdGVtKTtcbiAgICAgICAgfVxuICAgICAgICBlbHNle1xuICAgICAgICAgICAgdGhpcy5zZWxlY3RlZCA9IFtpdGVtXTtcbiAgICAgICAgfVxuICAgICAgICB0aGlzLnJlbmRTZWxlY3RlZCgpO1xuICAgICAgICBpZighdGhpcy5vcHRpb25zLm11bHRpcGxlKXtcbiAgICAgICAgICAgIHRoaXMubGlzdEhvbGRlci5zdHlsZS5kaXNwbGF5ID0gJ25vbmUnO1xuICAgICAgICB9XG4gICAgICAgIG13LiQodGhpcykudHJpZ2dlcignY2hhbmdlJywgW3RoaXMuc2VsZWN0ZWRdKTtcbiAgICB9O1xuXG4gICAgdGhpcy5yZW5kU2luZ2xlID0gZnVuY3Rpb24oKXtcbiAgICAgICAgdmFyIGl0ZW0gPSB0aGlzLnNlbGVjdGVkWzBdO1xuICAgICAgICB0aGlzLmlucHV0RmllbGQudmFsdWUgPSBpdGVtID8gaXRlbVt0aGlzLm1hcC50aXRsZV0gOiAnJztcbiAgICAgICAgdGhpcy52YWx1ZUhvbGRlci5pbm5lckhUTUwgPSAnJztcbiAgICAgICAgdmFyIGltZyA9IHRoaXMuZGF0YUltYWdlKGl0ZW0pO1xuICAgICAgICBpZihpbWcpe1xuICAgICAgICAgICAgdGhpcy52YWx1ZUhvbGRlci5hcHBlbmRDaGlsZChpbWcpO1xuICAgICAgICB9XG5cbiAgICB9O1xuXG4gICAgdGhpcy5yZW5kU2VsZWN0ZWQgPSBmdW5jdGlvbigpe1xuICAgICAgICBpZih0aGlzLm9wdGlvbnMubXVsdGlwbGUpe1xuICAgICAgICAgICAgdGhpcy51bmlxdWVWYWx1ZSgpO1xuICAgICAgICAgICAgdGhpcy5jaGlwcy5zZXREYXRhKHRoaXMuc2VsZWN0ZWQpO1xuICAgICAgICB9XG4gICAgICAgIGVsc2V7XG4gICAgICAgICAgICB0aGlzLnJlbmRTaW5nbGUoKTtcbiAgICAgICAgfVxuICAgIH07XG5cbiAgICB0aGlzLnJlbmRSZXN1bHRzID0gZnVuY3Rpb24oKXtcbiAgICAgICAgbXcuJCh0aGlzLmxpc3RIb2xkZXIpLmVtcHR5KCkuc2hvdygpO1xuICAgICAgICAkLmVhY2godGhpcy5yZXN1bHRzLCBmdW5jdGlvbigpe1xuICAgICAgICAgICAgc2NvcGUubGlzdEhvbGRlci5hcHBlbmRDaGlsZChzY29wZS5jcmVhdGVMaXN0SXRlbSh0aGlzKSk7XG4gICAgICAgIH0pO1xuICAgIH07XG5cbiAgICB0aGlzLmRhdGFWYWx1ZSA9IGZ1bmN0aW9uKGRhdGEpe1xuICAgICAgICBpZighZGF0YSkgcmV0dXJuO1xuICAgICAgICBpZih0eXBlb2YgZGF0YSA9PT0gJ3N0cmluZycpe1xuICAgICAgICAgICAgcmV0dXJuIGRhdGE7XG4gICAgICAgIH1cbiAgICAgICAgZWxzZXtcbiAgICAgICAgICAgIHJldHVybiBkYXRhW3RoaXMubWFwLnZhbHVlXTtcbiAgICAgICAgfVxuICAgIH07XG4gICAgdGhpcy5kYXRhSW1hZ2UgPSBmdW5jdGlvbihkYXRhKXtcbiAgICAgICAgaWYoIWRhdGEpIHJldHVybjtcbiAgICAgICAgaWYoZGF0YS5waWN0dXJlKXtcbiAgICAgICAgICAgIGRhdGEuaW1hZ2UgPSBkYXRhLnBpY3R1cmU7XG4gICAgICAgIH1cbiAgICAgICAgaWYoZGF0YS5pbWFnZSl7XG4gICAgICAgICAgICB2YXIgaW1nID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnc3BhbicpO1xuICAgICAgICAgICAgaW1nLmNsYXNzTmFtZSA9ICdtdy1hdXRvY29tcGxldGUtaW1nJztcbiAgICAgICAgICAgIGltZy5zdHlsZS5iYWNrZ3JvdW5kSW1hZ2UgPSAndXJsKCcgKyBkYXRhLmltYWdlICsgJyknO1xuICAgICAgICAgICAgcmV0dXJuIGltZztcbiAgICAgICAgfVxuICAgIH07XG4gICAgdGhpcy5kYXRhVGl0bGUgPSBmdW5jdGlvbihkYXRhKXtcbiAgICAgICAgaWYgKCFkYXRhKSByZXR1cm47XG4gICAgICAgIHZhciB0aXRsZTtcbiAgICAgICAgaWYgKHR5cGVvZiBkYXRhID09PSAnc3RyaW5nJykge1xuICAgICAgICAgICAgdGl0bGUgPSBkYXRhO1xuICAgICAgICB9XG4gICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgdGl0bGUgPSBkYXRhW3RoaXMubWFwLnRpdGxlXTtcbiAgICAgICAgfVxuXG4gICAgICAgIHJldHVybiB0aGlzLm9wdGlvbnMudGl0bGVEZWNvcmF0b3IodGl0bGUsIGRhdGEpO1xuICAgIH07XG5cbiAgICB0aGlzLnNlYXJjaFJlbW90ZSA9IGZ1bmN0aW9uKHZhbCl7XG4gICAgICAgIHZhciBjb25maWcgPSBtdy50b29scy5jbG9uZU9iamVjdCh0aGlzLm9wdGlvbnMuYWpheENvbmZpZyk7XG5cbiAgICAgICAgaWYoY29uZmlnLmRhdGEpe1xuICAgICAgICAgICAgaWYodHlwZW9mIGNvbmZpZy5kYXRhID09PSAnc3RyaW5nJyl7XG4gICAgICAgICAgICAgICAgY29uZmlnLmRhdGEgPSBjb25maWcuZGF0YS5yZXBsYWNlKCcke3ZhbH0nLHZhbCk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBlbHNle1xuICAgICAgICAgICAgICAgJC5lYWNoKGNvbmZpZy5kYXRhLCBmdW5jdGlvbihrZXksdmFsdWUpe1xuXG4gICAgICAgICAgICAgICAgICAgIGlmKHZhbHVlLmluZGV4T2YgJiYgdmFsdWUuaW5kZXhPZignJHt2YWx9JykgIT09LTEgKXtcbiAgICAgICAgICAgICAgICAgICAgICAgIGNvbmZpZy5kYXRhW2tleV0gPSB2YWx1ZS5yZXBsYWNlKCcke3ZhbH0nLCB2YWwpO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgICBpZihjb25maWcudXJsKXtcbiAgICAgICAgICAgIGNvbmZpZy51cmwgPSBjb25maWcudXJsLnJlcGxhY2UoJyR7dmFsfScsdmFsKTtcbiAgICAgICAgfVxuICAgICAgICB2YXIgeGhyID0gJC5hamF4KGNvbmZpZyk7XG4gICAgICAgIHhoci5kb25lKGZ1bmN0aW9uKGRhdGEpe1xuICAgICAgICAgICAgaWYoZGF0YS5kYXRhKXtcbiAgICAgICAgICAgICAgICBzY29wZS5kYXRhID0gZGF0YS5kYXRhO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgZWxzZXtcbiAgICAgICAgICAgICAgIHNjb3BlLmRhdGEgPSBkYXRhO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgc2NvcGUucmVzdWx0cyA9IHNjb3BlLmRhdGE7XG4gICAgICAgICAgICBzY29wZS5yZW5kUmVzdWx0cygpO1xuICAgICAgICB9KVxuICAgICAgICAuYWx3YXlzKGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICBzY29wZS5zZWFyY2hpbmcgPSBmYWxzZTtcbiAgICAgICAgfSk7XG4gICAgfTtcblxuICAgIHRoaXMuc2VhcmNoTG9jYWwgPSBmdW5jdGlvbih2YWwpe1xuXG4gICAgICAgIHRoaXMucmVzdWx0cyA9IFtdO1xuICAgICAgICB2YXIgdG9TZWFyY2g7XG4gICAgICAgICQuZWFjaCh0aGlzLmRhdGEsIGZ1bmN0aW9uKCl7XG4gICAgICAgICAgIGlmKHR5cGVvZiB0aGlzID09PSAnc3RyaW5nJyl7XG4gICAgICAgICAgICAgICAgdG9TZWFyY2ggPSB0aGlzLnRvTG93ZXJDYXNlKCk7XG4gICAgICAgICAgIH1cbiAgICAgICAgICAgZWxzZXtcbiAgICAgICAgICAgICAgIHRvU2VhcmNoID0gdGhpc1tzY29wZS5tYXAudGl0bGVdLnRvTG93ZXJDYXNlKCk7XG4gICAgICAgICAgIH1cbiAgICAgICAgICAgaWYodG9TZWFyY2guaW5kZXhPZih2YWwpICE9PSAtMSl7XG4gICAgICAgICAgICBzY29wZS5yZXN1bHRzLnB1c2godGhpcyk7XG4gICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgICAgdGhpcy5yZW5kUmVzdWx0cygpO1xuICAgICAgIHNjb3BlLnNlYXJjaGluZyA9IGZhbHNlO1xuICAgIH07XG5cbiAgICB0aGlzLnNlYXJjaCA9IGZ1bmN0aW9uKHZhbCl7XG4gICAgICAgIGlmKHNjb3BlLnNlYXJjaGluZykgcmV0dXJuO1xuICAgICAgICB2YWwgPSB2YWwgfHwgJyc7XG4gICAgICAgIHZhbCA9IHZhbC50cmltKCkudG9Mb3dlckNhc2UoKTtcblxuICAgICAgICBpZih0aGlzLm9wdGlvbnMuZGF0YSl7XG4gICAgICAgICAgICB0aGlzLnNlYXJjaExvY2FsKHZhbCk7XG4gICAgICAgIH1cbiAgICAgICAgZWxzZXtcbiAgICAgICAgICAgIGNsZWFyVGltZW91dCh0aGlzLnNlYXJjaFRpbWUpO1xuICAgICAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgIHNjb3BlLnNlYXJjaGluZyA9IHRydWU7XG4gICAgICAgICAgICAgICAgc2NvcGUuc2VhcmNoUmVtb3RlKHZhbCk7XG4gICAgICAgICAgICB9LCB0aGlzLnNlYXJjaFRpbWVvdXQpO1xuICAgICAgICB9XG4gICAgfTtcblxuICAgIHRoaXMuaW5pdCA9IGZ1bmN0aW9uKCl7XG4gICAgICAgIHRoaXMucHJlcGFyZShvcHRpb25zKTtcbiAgICAgICAgdGhpcy5idWlsZFVJKCk7XG4gICAgICAgIGlmKHRoaXMub3B0aW9ucy5tdWx0aXBsZSl7XG4gICAgICAgICAgICB0aGlzLmNoaXBzID0gbmV3IG13LmNoaXBzKHtcbiAgICAgICAgICAgICAgICBlbGVtZW50OnRoaXMudmFsdWVIb2xkZXIsXG4gICAgICAgICAgICAgICAgZGF0YTpbXVxuICAgICAgICAgICAgfSk7XG4gICAgICAgIH1cbiAgICAgICAgdGhpcy5yZW5kU2VsZWN0ZWQoKTtcbiAgICAgICAgdGhpcy5oYW5kbGVFdmVudHMoKTtcbiAgICB9O1xuXG4gICAgdGhpcy5oYW5kbGVFdmVudHMgPSBmdW5jdGlvbigpe1xuICAgICAgICBtdy4kKGRvY3VtZW50LmJvZHkpLm9uKCdjbGljaycsIGZ1bmN0aW9uKGUpe1xuICAgICAgICAgICAgaWYoIW13LnRvb2xzLmhhc1BhcmVudHNXaXRoQ2xhc3MoZS50YXJnZXQsICdtdy1hdXRvY29tcGxldGUnKSl7XG4gICAgICAgICAgICAgICAgc2NvcGUubGlzdEhvbGRlci5zdHlsZS5kaXNwbGF5ID0gJ25vbmUnO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICB9O1xuXG5cbiAgICB0aGlzLmluaXQoKTtcblxufTtcbiIsIiQoZG9jdW1lbnQpLnJlYWR5KGZ1bmN0aW9uKCl7XG4gICAgbXcuJCgnLm13LWxhenktbG9hZC1tb2R1bGUnKS5yZWxvYWRfbW9kdWxlKCk7XG59KTtcblxuXG4kKGRvY3VtZW50KS5yZWFkeShmdW5jdGlvbigpe1xuXG4gICAgbXcuY29tbW9uWydkYXRhLW13LWNsb3NlJ10oKTtcbiAgICBtdy4kKG13ZC5ib2R5KVxuICAgIC5vbignY2xpY2snLCAnW2RhdGEtbXctZGlhbG9nXScsIGZ1bmN0aW9uKGUpe1xuICAgICAgICBtdy5jb21tb25bJ2RhdGEtbXctZGlhbG9nJ10oZSk7XG4gICAgfSlcbiAgICAub24oJ2NsaWNrJywgJ1tkYXRhLW13LWNsb3NlXScsIGZ1bmN0aW9uKGUpe1xuICAgICAgICBtdy5jb21tb25bJ2RhdGEtbXctY2xvc2UnXShlKTtcbiAgICB9KTtcbn0pO1xuXG5tdy5jb21tb24gPSB7XG4gICAgc2V0T3B0aW9uczpmdW5jdGlvbiAoZWwsIG9wdGlvbnMpIHtcbiAgICAgICAgb3B0aW9ucyA9IG9wdGlvbnMgfHwge307XG4gICAgICAgIGlmKGVsLnRhcmdldCl7XG4gICAgICAgICAgICBlbCA9IGVsLnRhcmdldDtcbiAgICAgICAgfVxuICAgICAgICB2YXIgc2V0dGluZ3MgPSBlbC5nZXRBdHRyaWJ1dGUoJ2RhdGEtbXctc2V0dGluZ3MnKTtcbiAgICAgICAgdHJ5e1xuICAgICAgICAgICAgc2V0dGluZ3MgPSBKU09OLnBhcnNlKHNldHRpbmdzKTtcbiAgICAgICAgfVxuICAgICAgICBjYXRjaChlKXtcbiAgICAgICAgICAgIHNldHRpbmdzID0ge307XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuICQuZXh0ZW5kKG9wdGlvbnMsIHNldHRpbmdzKVxuXG4gICAgfSxcbiAgICAnZGF0YS1tdy1jbG9zZSc6ZnVuY3Rpb24oZSl7XG4gICAgICAgIGlmKGUgJiYgZS50YXJnZXQpe1xuICAgICAgICAgICAgdmFyIGRhdGEgPSBlLnRhcmdldC5nZXRBdHRyaWJ1dGUoJ2RhdGEtbXctY2xvc2UnKTtcbiAgICAgICAgICAgIHZhciBjb29raWUgPSBKU09OLnBhcnNlKG13LmNvb2tpZS5nZXQoJ2RhdGEtbXctY2xvc2UnKSB8fCAne30nKTtcbiAgICAgICAgICAgIG13LiQoZGF0YSkuc2xpZGVVcChmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgIG13LiQodGhpcykucmVtb3ZlKCk7XG4gICAgICAgICAgICAgICAgY29va2llW2RhdGFdID0gdHJ1ZTtcbiAgICAgICAgICAgICAgICBtdy5jb29raWUuc2V0KCdkYXRhLW13LWNsb3NlJywgSlNPTi5zdHJpbmdpZnkoY29va2llKSk7XG4gICAgICAgICAgICB9KVxuICAgICAgICB9XG4gICAgICAgIGVsc2V7XG4gICAgICAgICAgICB2YXIgY29va2llID0gIEpTT04ucGFyc2UobXcuY29va2llLmdldCgnZGF0YS1tdy1jbG9zZScpIHx8ICd7fScpO1xuICAgICAgICAgICAgbXcuJCgnW2RhdGEtbXctY2xvc2VdJykuZWFjaChmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgIHZhciBkYXRhID0gdGhpcy5nZXRBdHRyaWJ1dGUoJ2RhdGEtbXctZGlhbG9nJyk7XG4gICAgICAgICAgICAgICAgaWYoY29va2llW2RhdGFdKXtcbiAgICAgICAgICAgICAgICAgICAgbXcuJChkYXRhKS5yZW1vdmUoKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KVxuICAgICAgICB9XG4gICAgfSxcbiAgICAnZGF0YS1tdy1kaWFsb2cnOmZ1bmN0aW9uKGUpe1xuICAgICAgICB2YXIgc2tpbiA9ICdiYXNpYyc7XG4gICAgICAgIHZhciBvdmVybGF5ID0gdHJ1ZTtcbiAgICAgICAgdmFyIGRhdGEgPSBlLnRhcmdldC5nZXRBdHRyaWJ1dGUoJ2RhdGEtbXctZGlhbG9nJyk7XG5cbiAgICAgICAgaWYoZGF0YSl7XG4gICAgICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgICAgICBkYXRhID0gZGF0YS50cmltKCk7XG4gICAgICAgICAgICB2YXIgYXJyID0gZGF0YS5zcGxpdCgnLicpO1xuICAgICAgICAgICAgdmFyIGV4dCA9IGFyclthcnIubGVuZ3RoLTFdO1xuICAgICAgICAgICAgaWYoZGF0YS5pbmRleE9mKCdodHRwJykgPT09IDApe1xuICAgICAgICAgICAgICAgIGlmKGV4dCAmJiAvKGdpZnxwbmd8anBnfGpwZWd8YnBtfHRpZmYpJC9pLnRlc3QoZXh0KSl7XG4gICAgICAgICAgICAgICAgICAgIG13LmltYWdlLnByZWxvYWQoZGF0YSwgZnVuY3Rpb24odyxoKXtcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciBodG1sID0gXCI8aW1nIHNyYz0nXCIrZGF0YStcIic+XCI7XG4gICAgICAgICAgICAgICAgICAgICAgICB2YXIgY29uZiA9IG13LmNvbW1vbi5zZXRPcHRpb25zKGUsIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB3aWR0aDp3LFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGhlaWdodDpoLFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNvbnRlbnQ6aHRtbCxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB0ZW1wbGF0ZTpza2luLFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG92ZXJsYXk6b3ZlcmxheSxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBvdmVybGF5UmVtb3Zlc01vZGFsOnRydWVcbiAgICAgICAgICAgICAgICAgICAgICAgIH0pXG4gICAgICAgICAgICAgICAgICAgICAgICBtdy5kaWFsb2coY29uZik7XG4gICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBlbHNle1xuICAgICAgICAgICAgICAgICAgICB2YXIgY29uZiA9IG13LmNvbW1vbi5zZXRPcHRpb25zKGUsIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHVybDpkYXRhLFxuICAgICAgICAgICAgICAgICAgICAgICAgd2lkdGg6JzkwJScsXG4gICAgICAgICAgICAgICAgICAgICAgICBoZWlnaHQ6J2F1dG8lJyxcbiAgICAgICAgICAgICAgICAgICAgICAgIHRlbXBsYXRlOnNraW4sXG4gICAgICAgICAgICAgICAgICAgICAgICBvdmVybGF5Om92ZXJsYXksXG4gICAgICAgICAgICAgICAgICAgICAgICBvdmVybGF5UmVtb3Zlc01vZGFsOnRydWVcbiAgICAgICAgICAgICAgICAgICAgfSlcbiAgICAgICAgICAgICAgICAgICAgbXcuZGlhbG9nSWZyYW1lKGNvbmYpXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgZWxzZSBpZihkYXRhLmluZGV4T2YoJyMnKSA9PT0gMCB8fCBkYXRhLmluZGV4T2YoJy4nKSA9PT0gMCl7XG4gICAgICAgICAgICAgICAgdmFyIGNvbmYgPSBtdy5jb21tb24uc2V0T3B0aW9ucyhlLCB7XG4gICAgICAgICAgICAgICAgICAgIGNvbnRlbnQ6JChkYXRhKVswXS5vdXRlckhUTUwsXG4gICAgICAgICAgICAgICAgICAgIHRlbXBsYXRlOnNraW4sXG4gICAgICAgICAgICAgICAgICAgIG92ZXJsYXk6b3ZlcmxheSxcbiAgICAgICAgICAgICAgICAgICAgb3ZlcmxheVJlbW92ZXNNb2RhbDp0cnVlXG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgbXcuZGlhbG9nKGNvbmYpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgfVxufVxuIiwiKGZ1bmN0aW9uIChtdykge1xuXG5cblxuICAgIG13LmRpYWxvZyA9IGZ1bmN0aW9uIChvcHRpb25zKSB7XG4gICAgICAgIHJldHVybiBuZXcgbXcuRGlhbG9nKG9wdGlvbnMpO1xuICAgIH07XG5cblxuICAgIG13LmRpYWxvZ0lmcmFtZSA9IGZ1bmN0aW9uIChvcHRpb25zLCBjcmVzKSB7XG4gICAgICAgIG9wdGlvbnMucGF1c2VJbml0ID0gdHJ1ZTtcbiAgICAgICAgdmFyIGF0dHIgPSAnZnJhbWVib3JkZXI9XCIwXCIgYWxsb3c9XCJhY2NlbGVyb21ldGVyOyBhdXRvcGxheTsgZW5jcnlwdGVkLW1lZGlhOyBneXJvc2NvcGU7IHBpY3R1cmUtaW4tcGljdHVyZVwiIGFsbG93ZnVsbHNjcmVlbic7XG4gICAgICAgIGlmIChvcHRpb25zLmF1dG9IZWlnaHQpIHtcbiAgICAgICAgICAgIGF0dHIgKz0gJyBzY3JvbGxpbmc9XCJub1wiJztcbiAgICAgICAgICAgIG9wdGlvbnMuaGVpZ2h0ID0gJ2F1dG8nO1xuICAgICAgICB9XG4gICAgICAgIG9wdGlvbnMuY29udGVudCA9ICc8aWZyYW1lIHNyYz1cIicgKyBtdy5leHRlcm5hbF90b29sKG9wdGlvbnMudXJsLnRyaW0oKSkgKyAnXCIgJyArIGF0dHIgKyAnPjxpZnJhbWU+JztcbiAgICAgICAgb3B0aW9ucy5jbGFzc05hbWUgPSAoJ213LWRpYWxvZy1pZnJhbWUgbXctZGlhbG9nLWlmcmFtZS1sb2FkaW5nICcgKyAob3B0aW9ucy5jbGFzc05hbWUgfHwgJycpKS50cmltKCk7XG4gICAgICAgIG9wdGlvbnMuY2xhc3NOYW1lICs9IChvcHRpb25zLmF1dG9IZWlnaHQgPyAnIG13LWRpYWxvZy1pZnJhbWUtYXV0b2hlaWdodCcgOiAnJyk7XG4gICAgICAgIHZhciBkaWFsb2cgPSBuZXcgbXcuRGlhbG9nKG9wdGlvbnMsIGNyZXMpO1xuICAgICAgICBkaWFsb2cuaWZyYW1lID0gZGlhbG9nLmRpYWxvZ0NvbnRhaW5lci5xdWVyeVNlbGVjdG9yKCdpZnJhbWUnKTtcbiAgICAgICAgbXcudG9vbHMubG9hZGluZyhkaWFsb2cuZGlhbG9nQ29udGFpbmVyLCA5MCk7XG5cblxuXG4gICAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdmFyIGZyYW1lID0gZGlhbG9nLmRpYWxvZ0NvbnRhaW5lci5xdWVyeVNlbGVjdG9yKCdpZnJhbWUnKTtcbiAgICAgICAgICAgIGZyYW1lLnN0eWxlLm1pbkhlaWdodCA9IDA7IC8vIHJlc2V0IGluIGNhc2Ugb2YgY29uZmxpY3RzXG4gICAgICAgICAgICBpZiAob3B0aW9ucy5hdXRvSGVpZ2h0KSB7XG4gICAgICAgICAgICAgICAgbXcudG9vbHMuaWZyYW1lQXV0b0hlaWdodChmcmFtZSwge2RpYWxvZzogZGlhbG9nfSk7XG4gICAgICAgICAgICB9IGVsc2V7XG4gICAgICAgICAgICAgICAgJChmcmFtZSkuaGVpZ2h0KG9wdGlvbnMuaGVpZ2h0IC0gNjApO1xuICAgICAgICAgICAgICAgIGZyYW1lLnN0eWxlLnBvc2l0aW9uID0gJ3JlbGF0aXZlJztcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIG13LiQoZnJhbWUpLm9uKCdsb2FkJywgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIG13LnRvb2xzLmxvYWRpbmcoZGlhbG9nLmRpYWxvZ0NvbnRhaW5lciwgZmFsc2UpO1xuICAgICAgICAgICAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICBkaWFsb2cuY2VudGVyKCk7XG4gICAgICAgICAgICAgICAgICAgIG13LiQoZnJhbWUpLm9uKCdib2R5UmVzaXplJywgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgZGlhbG9nLmNlbnRlcigpO1xuICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICAgICAgZGlhbG9nLmRpYWxvZ01haW4uY2xhc3NMaXN0LnJlbW92ZSgnbXctZGlhbG9nLWlmcmFtZS1sb2FkaW5nJyk7XG4gICAgICAgICAgICAgICAgICAgIGZyYW1lLmNvbnRlbnRXaW5kb3cudGhpc21vZGFsID0gZGlhbG9nO1xuICAgICAgICAgICAgICAgICAgICBpZiAob3B0aW9ucy5hdXRvSGVpZ2h0KSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBtdy50b29scy5pZnJhbWVBdXRvSGVpZ2h0KGZyYW1lLCB7ZGlhbG9nOiBkaWFsb2d9KTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH0sIDc4KTtcbiAgICAgICAgICAgICAgICBpZiAobXcudG9vbHMuY2FuQWNjZXNzSUZyYW1lKGZyYW1lKSkge1xuICAgICAgICAgICAgICAgICAgICBtdy4kKGZyYW1lLmNvbnRlbnRXaW5kb3cuZG9jdW1lbnQpLm9uKCdrZXlkb3duJywgZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmIChtdy5ldmVudC5pcy5lc2NhcGUoZSkgJiYgIW13LmV2ZW50LnRhcmdldElzRmllbGQoZSkpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZihtdy50b3AoKS5fX2RpYWxvZ3MgJiYgbXcudG9wKCkuX19kaWFsb2dzLmxlbmd0aCl7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHZhciBkbGcgPSBtdy50b3AoKS5fX2RpYWxvZ3M7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGRsZ1tkbGcubGVuZ3RoIC0gMV0uX2RvQ2xvc2VCdXR0b24oKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgJChkbGdbZGxnLmxlbmd0aCAtIDFdKS50cmlnZ2VyKCdjbG9zZWRCeVVzZXInKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGlmIChkaWFsb2cub3B0aW9ucy5jbG9zZU9uRXNjYXBlKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBkaWFsb2cuX2RvQ2xvc2VCdXR0b24oKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICQoZGlhbG9nKS50cmlnZ2VyKCdjbG9zZWRCeVVzZXInKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGlmKHR5cGVvZiBvcHRpb25zLm9ubG9hZCA9PT0gJ2Z1bmN0aW9uJykge1xuICAgICAgICAgICAgICAgICAgICBvcHRpb25zLm9ubG9hZC5jYWxsKGRpYWxvZyk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG4gICAgICAgIH0sIDEyKTtcbiAgICAgICAgcmV0dXJuIGRpYWxvZztcbiAgICB9O1xuXG4gICAgLyoqIEBkZXByZWNhdGVkICovXG4gICAgbXcubW9kYWwgPSBtdy5kaWFsb2c7XG4gICAgbXcubW9kYWxGcmFtZSA9IG13LmRpYWxvZ0lmcmFtZTtcblxuICAgIG13LmRpYWxvZy5yZW1vdmUgPSBmdW5jdGlvbiAoc2VsZWN0b3IpIHtcbiAgICAgICAgcmV0dXJuIG13LmRpYWxvZy5nZXQoc2VsZWN0b3IpLnJlbW92ZSgpO1xuICAgIH07XG5cbiAgICBtdy5kaWFsb2cuZ2V0ID0gZnVuY3Rpb24gKHNlbGVjdG9yKSB7XG4gICAgICAgIHZhciAkZWwgPSBtdy4kKHNlbGVjdG9yKTtcbiAgICAgICAgdmFyIGVsID0gJGVsWzBdO1xuXG4gICAgICAgIGlmKCFlbCkgcmV0dXJuIGZhbHNlO1xuXG4gICAgICAgIGlmKGVsLl9kaWFsb2cpIHtcbiAgICAgICAgICAgIHJldHVybiBlbC5fZGlhbG9nO1xuICAgICAgICB9XG4gICAgICAgIHZhciBjaGlsZF9jb250ID0gZWwucXVlcnlTZWxlY3RvcignLm13LWRpYWxvZy1ob2xkZXInKTtcbiAgICAgICAgdmFyIHBhcmVudF9jb250ID0gJGVsLnBhcmVudHMoXCIubXctZGlhbG9nLWhvbGRlcjpmaXJzdFwiKTtcbiAgICAgICAgaWYgKGNoaWxkX2NvbnQpIHtcbiAgICAgICAgICAgIHJldHVybiBjaGlsZF9jb250Ll9kaWFsb2c7XG4gICAgICAgIH1cbiAgICAgICAgZWxzZSBpZiAocGFyZW50X2NvbnQubGVuZ3RoICE9PSAwKSB7XG4gICAgICAgICAgICByZXR1cm4gcGFyZW50X2NvbnRbMF0uX2RpYWxvZztcbiAgICAgICAgfVxuICAgICAgICBlbHNlIGlmICh3aW5kb3cudGhpc21vZGFsKSB7XG4gICAgICAgICAgICByZXR1cm4gdGhpc21vZGFsO1xuICAgICAgICB9XG4gICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgIC8vIGRlcHJlY2F0ZWRcbiAgICAgICAgICAgIGNoaWxkX2NvbnQgPSBlbC5xdWVyeVNlbGVjdG9yKCcubXdfbW9kYWwnKTtcbiAgICAgICAgICAgIHBhcmVudF9jb250ID0gJGVsLnBhcmVudHMoXCIubXdfbW9kYWw6Zmlyc3RcIik7XG4gICAgICAgICAgICBpZihjaGlsZF9jb250KSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIGNoaWxkX2NvbnQubW9kYWw7XG4gICAgICAgICAgICB9IGVsc2UgaWYgKHBhcmVudF9jb250Lmxlbmd0aCAhPT0gMCkge1xuICAgICAgICAgICAgICAgIHJldHVybiBwYXJlbnRfY29udFswXS5tb2RhbDtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgfVxuICAgIH07XG5cblxuICAgIG13LkRpYWxvZyA9IGZ1bmN0aW9uIChvcHRpb25zLCBjcmVzKSB7XG5cbiAgICAgICAgdmFyIHNjb3BlID0gdGhpcztcblxuICAgICAgICBvcHRpb25zID0gb3B0aW9ucyB8fCB7fTtcbiAgICAgICAgb3B0aW9ucy5jb250ZW50ID0gb3B0aW9ucy5jb250ZW50IHx8IG9wdGlvbnMuaHRtbCB8fCAnJztcblxuICAgICAgICBpZighb3B0aW9ucy5oZWlnaHQgJiYgdHlwZW9mIG9wdGlvbnMuYXV0b0hlaWdodCA9PT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAgICAgICAgIG9wdGlvbnMuaGVpZ2h0ID0gJ2F1dG8nO1xuICAgICAgICAgICAgb3B0aW9ucy5hdXRvSGVpZ2h0ID0gdHJ1ZTtcbiAgICAgICAgfVxuXG4gICAgICAgIHZhciBkZWZhdWx0cyA9IHtcbiAgICAgICAgICAgIHNraW46ICdkZWZhdWx0JyxcbiAgICAgICAgICAgIG92ZXJsYXk6IHRydWUsXG4gICAgICAgICAgICBvdmVybGF5Q2xvc2U6IGZhbHNlLFxuICAgICAgICAgICAgYXV0b0NlbnRlcjogdHJ1ZSxcbiAgICAgICAgICAgIHJvb3Q6IGRvY3VtZW50LFxuICAgICAgICAgICAgaWQ6IG13LmlkKCdtdy1kaWFsb2ctJyksXG4gICAgICAgICAgICBjb250ZW50OiAnJyxcbiAgICAgICAgICAgIGNsb3NlT25Fc2NhcGU6IHRydWUsXG4gICAgICAgICAgICBjbG9zZUJ1dHRvbjogdHJ1ZSxcbiAgICAgICAgICAgIGNsb3NlQnV0dG9uQXBwZW5kVG86ICcubXctZGlhbG9nLWhlYWRlcicsXG4gICAgICAgICAgICBjbG9zZUJ1dHRvbkFjdGlvbjogJ3JlbW92ZScsIC8vICdyZW1vdmUnIHwgJ2hpZGUnXG4gICAgICAgICAgICBkcmFnZ2FibGU6IHRydWUsXG4gICAgICAgICAgICBzY3JvbGxNb2RlOiAnaW5zaWRlJywgLy8gJ2luc2lkZScgfCAnd2luZG93JyxcbiAgICAgICAgICAgIGNlbnRlck1vZGU6ICdpbnR1aXRpdmUnLCAvLyAnaW50dWl0aXZlJyB8ICdjZW50ZXInXG4gICAgICAgICAgICBjb250YWlubWVudDogJ3dpbmRvdycsXG4gICAgICAgICAgICBvdmVyZmxvd01vZGU6ICdhdXRvJywgLy8gJ2F1dG8nIHwgJ2hpZGRlbicgfCAndmlzaWJsZSdcbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLm9wdGlvbnMgPSAkLmV4dGVuZCh7fSwgZGVmYXVsdHMsIG9wdGlvbnMsIHtcbiAgICAgICAgICAgIHNraW46ICdkZWZhdWx0J1xuICAgICAgICB9KTtcblxuICAgICAgICB0aGlzLmlkID0gdGhpcy5vcHRpb25zLmlkO1xuICAgICAgICB2YXIgZXhpc3QgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCh0aGlzLmlkKTtcbiAgICAgICAgaWYgKGV4aXN0KSB7XG4gICAgICAgICAgICByZXR1cm4gZXhpc3QuX2RpYWxvZztcbiAgICAgICAgfVxuXG4gICAgICAgIHRoaXMuaGFzQmVlbkNyZWF0ZWQgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICByZXR1cm4gdGhpcy5vcHRpb25zLnJvb3QuZ2V0RWxlbWVudEJ5SWQodGhpcy5pZCkgIT09IG51bGw7XG4gICAgICAgIH07XG5cbiAgICAgICAgaWYgKHRoaXMuaGFzQmVlbkNyZWF0ZWQoKSkge1xuICAgICAgICAgICAgcmV0dXJuIHRoaXMub3B0aW9ucy5yb290LmdldEVsZW1lbnRCeUlkKHRoaXMuaWQpLl9kaWFsb2c7XG4gICAgICAgIH1cblxuICAgICAgICBpZighbXcudG9wKCkuX19kaWFsb2dzICkge1xuICAgICAgICAgICAgbXcudG9wKCkuX19kaWFsb2dzID0gW107XG4gICAgICAgIH1cbiAgICAgICAgaWYgKCFtdy50b3AoKS5fX2RpYWxvZ3NEYXRhKSB7XG4gICAgICAgICAgICBtdy50b3AoKS5fX2RpYWxvZ3NEYXRhID0ge307XG4gICAgICAgIH1cblxuXG4gICAgICAgIGlmICghbXcudG9wKCkuX19kaWFsb2dzRGF0YS5fZXNjKSB7XG4gICAgICAgICAgICBtdy50b3AoKS5fX2RpYWxvZ3NEYXRhLl9lc2MgPSB0cnVlO1xuICAgICAgICAgICAgbXcuJChkb2N1bWVudCkub24oJ2tleWRvd24nLCBmdW5jdGlvbiAoZSkge1xuICAgICAgICAgICAgICAgIGlmIChtdy5ldmVudC5pcy5lc2NhcGUoZSkpIHtcbiAgICAgICAgICAgICAgICAgICAgdmFyIGRsZyA9IG13LnRvcCgpLl9fZGlhbG9nc1ttdy50b3AoKS5fX2RpYWxvZ3MubGVuZ3RoIC0gMV07XG4gICAgICAgICAgICAgICAgICAgIGlmIChkbGcgJiYgZGxnLm9wdGlvbnMgJiYgZGxnLm9wdGlvbnMuY2xvc2VPbkVzY2FwZSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgZGxnLl9kb0Nsb3NlQnV0dG9uKCk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfVxuXG4gICAgICAgIG13LnRvcCgpLl9fZGlhbG9ncy5wdXNoKHRoaXMpO1xuXG4gICAgICAgIHRoaXMuZHJhZ2dhYmxlID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgaWYgKHRoaXMub3B0aW9ucy5kcmFnZ2FibGUgJiYgJC5mbi5kcmFnZ2FibGUpIHtcbiAgICAgICAgICAgICAgICB2YXIgJGhvbGRlciA9IG13LiQodGhpcy5kaWFsb2dIb2xkZXIpO1xuICAgICAgICAgICAgICAgICRob2xkZXIuZHJhZ2dhYmxlKHtcbiAgICAgICAgICAgICAgICAgICAgaGFuZGxlOiB0aGlzLm9wdGlvbnMuZHJhZ2dhYmxlSGFuZGxlIHx8ICcubXctZGlhbG9nLWhlYWRlcicsXG4gICAgICAgICAgICAgICAgICAgIHN0YXJ0OiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAkaG9sZGVyLmFkZENsYXNzKCdtdy1kaWFsb2ctZHJhZy1zdGFydCcpO1xuICAgICAgICAgICAgICAgICAgICAgICAgc2NvcGUuX2RyYWdnZWQgPSB0cnVlO1xuICAgICAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgICAgICBzdG9wOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAkaG9sZGVyLnJlbW92ZUNsYXNzKCdtdy1kaWFsb2ctZHJhZy1zdGFydCcpO1xuICAgICAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgICAgICBjb250YWlubWVudDogc2NvcGUub3B0aW9ucy5jb250YWlubWVudCxcbiAgICAgICAgICAgICAgICAgICAgaWZyYW1lRml4OiB0cnVlXG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5oZWFkZXIgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB0aGlzLmRpYWxvZ0hlYWRlciA9IHRoaXMub3B0aW9ucy5yb290LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgICAgICAgICAgdGhpcy5kaWFsb2dIZWFkZXIuY2xhc3NOYW1lID0gJ213LWRpYWxvZy1oZWFkZXInO1xuICAgICAgICAgICAgaWYgKHRoaXMub3B0aW9ucy50aXRsZSB8fCB0aGlzLm9wdGlvbnMuaGVhZGVyKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5kaWFsb2dIZWFkZXIuaW5uZXJIVE1MID0gJzxkaXYgY2xhc3M9XCJtdy1kaWFsb2ctdGl0bGVcIj4nICsgKHRoaXMub3B0aW9ucy50aXRsZSB8fCB0aGlzLm9wdGlvbnMuaGVhZGVyKSArICc8L2Rpdj4nO1xuICAgICAgICAgICAgfVxuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuZm9vdGVyID0gZnVuY3Rpb24gKGNvbnRlbnQpIHtcbiAgICAgICAgICAgIHRoaXMuZGlhbG9nRm9vdGVyID0gdGhpcy5vcHRpb25zLnJvb3QuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XG4gICAgICAgICAgICB0aGlzLmRpYWxvZ0Zvb3Rlci5jbGFzc05hbWUgPSAnbXctZGlhbG9nLWZvb3Rlcic7XG4gICAgICAgICAgICBpZiAodGhpcy5vcHRpb25zLmZvb3Rlcikge1xuICAgICAgICAgICAgICAgICQodGhpcy5kaWFsb2dGb290ZXIpLmFwcGVuZCh0aGlzLm9wdGlvbnMuZm9vdGVyKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLnRpdGxlID0gZnVuY3Rpb24gKHRpdGxlKSB7XG4gICAgICAgICAgICB2YXIgcm9vdCA9IG13LiQoJy5tdy1kaWFsb2ctdGl0bGUnLCB0aGlzLmRpYWxvZ0hlYWRlcik7XG4gICAgICAgICAgICBpZiAodHlwZW9mIHRpdGxlID09PSAndW5kZWZpbmVkJykge1xuICAgICAgICAgICAgICAgIHJldHVybiByb290Lmh0bWwoKTtcbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgaWYgKHJvb3RbMF0pIHtcbiAgICAgICAgICAgICAgICAgICAgcm9vdC5odG1sKHRpdGxlKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgIG13LiQodGhpcy5kaWFsb2dIZWFkZXIpLnByZXBlbmQoJzxkaXYgY2xhc3M9XCJtdy1kaWFsb2ctdGl0bGVcIj4nICsgdGl0bGUgKyAnPC9kaXY+Jyk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICB9O1xuXG5cbiAgICAgICAgdGhpcy5idWlsZCA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHRoaXMuZGlhbG9nTWFpbiA9IHRoaXMub3B0aW9ucy5yb290LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuXG4gICAgICAgICAgICB0aGlzLmRpYWxvZ01haW4uaWQgPSB0aGlzLmlkO1xuICAgICAgICAgICAgdmFyIGNscyA9ICdtdy1kaWFsb2cgbXctZGlhbG9nLXNjcm9sbC1tb2RlLScgKyB0aGlzLm9wdGlvbnMuc2Nyb2xsTW9kZVxuICAgICAgICAgICAgICAgICsgJyBtdy1kaWFsb2ctc2tpbi0nICsgdGhpcy5vcHRpb25zLnNraW5cbiAgICAgICAgICAgICAgICArICcgbXctZGlhbG9nLW92ZXJmbG93TW9kZS0nICsgdGhpcy5vcHRpb25zLm92ZXJmbG93TW9kZTtcbiAgICAgICAgICAgIGNscyArPSAoIXRoaXMub3B0aW9ucy5jbGFzc05hbWUgPyAnJyA6ICgnICcgKyB0aGlzLm9wdGlvbnMuY2xhc3NOYW1lKSk7XG4gICAgICAgICAgICB0aGlzLmRpYWxvZ01haW4uY2xhc3NOYW1lID0gY2xzO1xuICAgICAgICAgICAgdGhpcy5kaWFsb2dNYWluLl9kaWFsb2cgPSB0aGlzO1xuXG4gICAgICAgICAgICB0aGlzLmRpYWxvZ0hvbGRlciA9IHRoaXMub3B0aW9ucy5yb290LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgICAgICAgICAgdGhpcy5kaWFsb2dIb2xkZXIuaWQgPSAnbXctZGlhbG9nLWhvbGRlci0nICsgdGhpcy5pZDtcblxuXG4gICAgICAgICAgICB0aGlzLmRpYWxvZ0hvbGRlci5fZGlhbG9nID0gdGhpcztcblxuICAgICAgICAgICAgdGhpcy5oZWFkZXIoKTtcbiAgICAgICAgICAgIHRoaXMuZm9vdGVyKCk7XG4gICAgICAgICAgICB0aGlzLmRyYWdnYWJsZSgpO1xuXG5cblxuICAgICAgICAgICAgdGhpcy5kaWFsb2dDb250YWluZXIgPSB0aGlzLm9wdGlvbnMucm9vdC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgICAgIHRoaXMuZGlhbG9nQ29udGFpbmVyLl9kaWFsb2cgPSB0aGlzO1xuXG4gICAgICAgICAgICAvLyBUT0RPOiBvYnNvbGF0ZVxuICAgICAgICAgICAgdGhpcy5jb250YWluZXIgPSB0aGlzLmRpYWxvZ0NvbnRhaW5lcjtcblxuXG4gICAgICAgICAgICB0aGlzLmRpYWxvZ0NvbnRhaW5lci5jbGFzc05hbWUgPSAnbXctZGlhbG9nLWNvbnRhaW5lcic7XG4gICAgICAgICAgICB0aGlzLmRpYWxvZ0hvbGRlci5jbGFzc05hbWUgPSAnbXctZGlhbG9nLWhvbGRlcic7XG5cbiAgICAgICAgICAgIHZhciBjb250ID0gdGhpcy5vcHRpb25zLmNvbnRlbnQ7XG4gICAgICAgICAgICBpZih0aGlzLm9wdGlvbnMuc2hhZG93KSB7XG4gICAgICAgICAgICAgICAgdGhpcy5zaGFkb3cgPSB0aGlzLmRpYWxvZ0NvbnRhaW5lci5hdHRhY2hTaGFkb3coe1xuICAgICAgICAgICAgICAgICAgICBtb2RlOiAnb3BlbidcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICBpZih0eXBlb2YgY29udCA9PT0gJ3N0cmluZycpIHtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5zaGFkb3cuaW5uZXJIVE1MID0gKGNvbnQpO1xuICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuc2hhZG93LmFwcGVuZENoaWxkKGNvbnQpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgbXcuJCh0aGlzLmRpYWxvZ0NvbnRhaW5lcikuYXBwZW5kKGNvbnQpO1xuICAgICAgICAgICAgfVxuXG5cbiAgICAgICAgICAgIGlmICh0aGlzLm9wdGlvbnMuZW5jYXBzdWxhdGUpIHtcbiAgICAgICAgICAgICAgICB0aGlzLmlmcmFtZSA9IGNvbnQ7XG4gICAgICAgICAgICAgICAgdGhpcy5pZnJhbWUuc3R5bGUuZGlzcGxheSA9ICcnO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICB0aGlzLmRpYWxvZ0hvbGRlci5hcHBlbmRDaGlsZCh0aGlzLmRpYWxvZ0hlYWRlcik7XG4gICAgICAgICAgICB0aGlzLmRpYWxvZ0hvbGRlci5hcHBlbmRDaGlsZCh0aGlzLmRpYWxvZ0NvbnRhaW5lcik7XG4gICAgICAgICAgICB0aGlzLmRpYWxvZ0hvbGRlci5hcHBlbmRDaGlsZCh0aGlzLmRpYWxvZ0Zvb3Rlcik7XG5cbiAgICAgICAgICAgIHRoaXMuY2xvc2VCdXR0b24gPSB0aGlzLm9wdGlvbnMucm9vdC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgICAgIHRoaXMuY2xvc2VCdXR0b24uY2xhc3NOYW1lID0gJ213LWRpYWxvZy1jbG9zZSc7XG5cbiAgICAgICAgICAgIHRoaXMuY2xvc2VCdXR0b24uJHNjb3BlID0gdGhpcztcblxuICAgICAgICAgICAgdGhpcy5jbG9zZUJ1dHRvbi5vbmNsaWNrID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIHRoaXMuJHNjb3BlW3RoaXMuJHNjb3BlLm9wdGlvbnMuY2xvc2VCdXR0b25BY3Rpb25dKCk7XG4gICAgICAgICAgICAgICAgJCh0aGlzLiRzY29wZSkudHJpZ2dlcignY2xvc2VkQnlVc2VyJyk7XG4gICAgICAgICAgICB9O1xuICAgICAgICAgICAgdGhpcy5tYWluID0gbXcuJCh0aGlzLmRpYWxvZ0NvbnRhaW5lcik7IC8vIG9ic29sZXRlXG4gICAgICAgICAgICB0aGlzLm1haW4ud2lkdGggPSB0aGlzLndpZHRoO1xuXG4gICAgICAgICAgICB0aGlzLndpZHRoKHRoaXMub3B0aW9ucy53aWR0aCB8fCA2MDApO1xuICAgICAgICAgICAgdGhpcy5oZWlnaHQodGhpcy5vcHRpb25zLmhlaWdodCB8fCAzMjApO1xuXG4gICAgICAgICAgICB0aGlzLm9wdGlvbnMucm9vdC5ib2R5LmFwcGVuZENoaWxkKHRoaXMuZGlhbG9nTWFpbik7XG4gICAgICAgICAgICB0aGlzLmRpYWxvZ01haW4uYXBwZW5kQ2hpbGQodGhpcy5kaWFsb2dIb2xkZXIpO1xuICAgICAgICAgICAgaWYgKHRoaXMub3B0aW9ucy5jbG9zZUJ1dHRvbkFwcGVuZFRvKSB7XG4gICAgICAgICAgICAgICAgbXcuJCh0aGlzLm9wdGlvbnMuY2xvc2VCdXR0b25BcHBlbmRUbywgdGhpcy5kaWFsb2dNYWluKS5hcHBlbmQodGhpcy5jbG9zZUJ1dHRvbilcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgIHRoaXMuZGlhbG9nSG9sZGVyLmFwcGVuZENoaWxkKHRoaXMuY2xvc2VCdXR0b24pO1xuXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICB0aGlzLmRpYWxvZ092ZXJsYXkoKTtcbiAgICAgICAgICAgIHJldHVybiB0aGlzO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuX2RvQ2xvc2VCdXR0b24gPSBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIHRoaXNbdGhpcy5vcHRpb25zLmNsb3NlQnV0dG9uQWN0aW9uXSgpO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuY29udGFpbm1lbnRNYW5hZ2UgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBpZiAoc2NvcGUub3B0aW9ucy5jb250YWlubWVudCA9PT0gJ3dpbmRvdycpIHtcbiAgICAgICAgICAgICAgICBpZiAoc2NvcGUub3B0aW9ucy5zY3JvbGxNb2RlID09PSAnaW5zaWRlJykge1xuICAgICAgICAgICAgICAgICAgICB2YXIgcmVjdCA9IHRoaXMuZGlhbG9nSG9sZGVyLmdldEJvdW5kaW5nQ2xpZW50UmVjdCgpO1xuICAgICAgICAgICAgICAgICAgICB2YXIgJHdpbiA9IG13LiQod2luZG93KTtcbiAgICAgICAgICAgICAgICAgICAgdmFyIHNjdG9wID0gJHdpbi5zY3JvbGxUb3AoKTtcbiAgICAgICAgICAgICAgICAgICAgdmFyIGhlaWdodCA9ICR3aW4uaGVpZ2h0KCk7XG4gICAgICAgICAgICAgICAgICAgIGlmIChyZWN0LnRvcCA8IHNjdG9wIHx8IChzY3RvcCArIGhlaWdodCkgPiAocmVjdC50b3AgKyByZWN0LmhlaWdodCkpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMuY2VudGVyKCk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5kaWFsb2dPdmVybGF5ID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdGhpcy5vdmVybGF5ID0gdGhpcy5vcHRpb25zLnJvb3QuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XG4gICAgICAgICAgICB0aGlzLm92ZXJsYXkuY2xhc3NOYW1lID0gJ213LWRpYWxvZy1vdmVybGF5JztcbiAgICAgICAgICAgIHRoaXMub3ZlcmxheS4kc2NvcGUgPSB0aGlzO1xuICAgICAgICAgICAgaWYgKHRoaXMub3B0aW9ucy5vdmVybGF5ID09PSB0cnVlKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5kaWFsb2dNYWluLmFwcGVuZENoaWxkKHRoaXMub3ZlcmxheSk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBtdy4kKHRoaXMub3ZlcmxheSkub24oJ2NsaWNrJywgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIGlmICh0aGlzLiRzY29wZS5vcHRpb25zLm92ZXJsYXlDbG9zZSA9PT0gdHJ1ZSkge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLiRzY29wZS5fZG9DbG9zZUJ1dHRvbigpO1xuICAgICAgICAgICAgICAgICAgICAkKHRoaXMuJHNjb3BlKS50cmlnZ2VyKCdjbG9zZWRCeVVzZXInKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgcmV0dXJuIHRoaXM7XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5fYWZ0ZXJTaXplID0gZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBpZihtdy5faWZyYW1lRGV0ZWN0b3IpIHtcbiAgICAgICAgICAgICAgICBtdy5faWZyYW1lRGV0ZWN0b3IucGF1c2UgPSB0cnVlO1xuICAgICAgICAgICAgICAgIHZhciBmcmFtZSA9IHdpbmRvdy5mcmFtZUVsZW1lbnQ7XG4gICAgICAgICAgICAgICAgaWYoZnJhbWUgJiYgcGFyZW50ICE9PSB0b3Ape1xuICAgICAgICAgICAgICAgICAgICB2YXIgaGVpZ2h0ID0gdGhpcy5kaWFsb2dDb250YWluZXIuc2Nyb2xsSGVpZ2h0ICsgdGhpcy5kaWFsb2dIZWFkZXIuc2Nyb2xsSGVpZ2h0O1xuICAgICAgICAgICAgICAgICAgICBpZigkKGZyYW1lKS5oZWlnaHQoKSA8IGhlaWdodCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgZnJhbWUuc3R5bGUuaGVpZ2h0ID0gKChoZWlnaHQgKyAxMDApIC0gdGhpcy5kaWFsb2dIZWFkZXIub2Zmc2V0SGVpZ2h0IC0gdGhpcy5kaWFsb2dGb290ZXIub2Zmc2V0SGVpZ2h0KSArICdweCc7XG4gICAgICAgICAgICAgICAgICAgICAgICBpZih3aW5kb3cudGhpc21vZGFsKXtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB0aGlzbW9kYWwuaGVpZ2h0KGhlaWdodCArIDEwMCk7XG4gICAgICAgICAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLnNob3cgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBtdy4kKHRoaXMuZGlhbG9nTWFpbikuZmluZCgnaWZyYW1lJykuZWFjaChmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgIHRoaXMuX2ludFBhdXNlID0gZmFsc2U7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIG13LiQodGhpcy5kaWFsb2dNYWluKS5hZGRDbGFzcygnYWN0aXZlJyk7XG4gICAgICAgICAgICB0aGlzLmNlbnRlcigpO1xuICAgICAgICAgICAgdGhpcy5fYWZ0ZXJTaXplKCk7XG4gICAgICAgICAgICBtdy4kKHRoaXMpLnRyaWdnZXIoJ1Nob3cnKTtcbiAgICAgICAgICAgIG13LnRyaWdnZXIoJ213RGlhbG9nU2hvdycsIHRoaXMpO1xuICAgICAgICAgICAgcmV0dXJuIHRoaXM7XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5faGlkZVN0YXJ0ID0gZmFsc2U7XG4gICAgICAgIHRoaXMuaGlkZSA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIGlmICghdGhpcy5faGlkZVN0YXJ0KSB7XG4gICAgICAgICAgICAgICAgdGhpcy5faGlkZVN0YXJ0ID0gdHJ1ZTtcbiAgICAgICAgICAgICAgICBtdy4kKHRoaXMuZGlhbG9nTWFpbikuZmluZCgnaWZyYW1lJykuZWFjaChmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICAgICB0aGlzLl9pbnRQYXVzZSA9IHRydWU7XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgIHNjb3BlLl9oaWRlU3RhcnQgPSBmYWxzZTtcbiAgICAgICAgICAgICAgICB9LCAzMDApO1xuICAgICAgICAgICAgICAgIG13LiQodGhpcy5kaWFsb2dNYWluKS5yZW1vdmVDbGFzcygnYWN0aXZlJyk7XG4gICAgICAgICAgICAgICAgaWYobXcuX2lmcmFtZURldGVjdG9yKSB7XG4gICAgICAgICAgICAgICAgICAgIG13Ll9pZnJhbWVEZXRlY3Rvci5wYXVzZSA9IGZhbHNlO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBtdy4kKHRoaXMpLnRyaWdnZXIoJ0hpZGUnKTtcbiAgICAgICAgICAgICAgICBtdy50cmlnZ2VyKCdtd0RpYWxvZ0hpZGUnLCB0aGlzKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHJldHVybiB0aGlzO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMucmVtb3ZlID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdGhpcy5oaWRlKCk7XG4gICAgICAgICAgICBtdy5yZW1vdmVJbnRlcnZhbCgnaWZyYW1lLScgKyB0aGlzLmlkKTtcbiAgICAgICAgICAgIG13LiQodGhpcykudHJpZ2dlcignQmVmb3JlUmVtb3ZlJyk7XG4gICAgICAgICAgICBpZiAodHlwZW9mIHRoaXMub3B0aW9ucy5iZWZvcmVSZW1vdmUgPT09ICdmdW5jdGlvbicpIHtcbiAgICAgICAgICAgICAgICB0aGlzLm9wdGlvbnMuYmVmb3JlUmVtb3ZlLmNhbGwodGhpcywgdGhpcylcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIG13LiQodGhpcy5kaWFsb2dNYWluKS5yZW1vdmUoKTtcbiAgICAgICAgICAgIGlmKHRoaXMub3B0aW9ucy5vbnJlbW92ZSkge1xuICAgICAgICAgICAgICAgIHRoaXMub3B0aW9ucy5vbnJlbW92ZSgpXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBtdy4kKHRoaXMpLnRyaWdnZXIoJ1JlbW92ZScpO1xuICAgICAgICAgICAgbXcudHJpZ2dlcignbXdEaWFsb2dSZW1vdmUnLCB0aGlzKTtcbiAgICAgICAgICAgIGZvciAodmFyIGkgPSAwOyBpIDwgbXcudG9wKCkuX19kaWFsb2dzLmxlbmd0aDsgaSsrKSB7XG4gICAgICAgICAgICAgICAgaWYgKG13LnRvcCgpLl9fZGlhbG9nc1tpXSA9PT0gdGhpcykge1xuICAgICAgICAgICAgICAgICAgICBtdy50b3AoKS5fX2RpYWxvZ3Muc3BsaWNlKGksIDEpO1xuICAgICAgICAgICAgICAgICAgICBicmVhaztcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICByZXR1cm4gdGhpcztcbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLmRlc3Ryb3kgPSB0aGlzLnJlbW92ZTtcblxuICAgICAgICB0aGlzLl9wcmV2SGVpZ2h0ID0gLTE7XG4gICAgICAgIHRoaXMuX2RyYWdnZWQgPSBmYWxzZTtcblxuICAgICAgICB0aGlzLmNlbnRlciA9IGZ1bmN0aW9uICh3aWR0aCwgaGVpZ2h0KSB7XG4gICAgICAgICAgICB2YXIgJGhvbGRlciA9IG13LiQodGhpcy5kaWFsb2dIb2xkZXIpLCAkd2luZG93ID0gbXcuJCh3aW5kb3cpO1xuICAgICAgICAgICAgdmFyIGhvbGRlckhlaWdodCA9IGhlaWdodCB8fCAkaG9sZGVyLm91dGVySGVpZ2h0KCk7XG4gICAgICAgICAgICB2YXIgaG9sZGVyV2lkdGggPSB3aWR0aCB8fCAkaG9sZGVyLm91dGVyV2lkdGgoKTtcbiAgICAgICAgICAgIHZhciBkdG9wLCBjc3MgPSB7fTtcblxuICAgICAgICAgICAgaWYgKHRoaXMub3B0aW9ucy5jZW50ZXJNb2RlID09PSAnaW50dWl0aXZlJyAmJiB0aGlzLl9wcmV2SGVpZ2h0IDwgaG9sZGVySGVpZ2h0KSB7XG4gICAgICAgICAgICAgICAgZHRvcCA9ICR3aW5kb3cuaGVpZ2h0KCkgLyAyIC0gaG9sZGVySGVpZ2h0IC8gMjtcbiAgICAgICAgICAgIH0gZWxzZSBpZiAodGhpcy5vcHRpb25zLmNlbnRlck1vZGUgPT09ICdjZW50ZXInKSB7XG4gICAgICAgICAgICAgICAgZHRvcCA9ICR3aW5kb3cuaGVpZ2h0KCkgLyAyIC0gaG9sZGVySGVpZ2h0IC8gMjtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgaWYgKCFzY29wZS5fZHJhZ2dlZCkge1xuICAgICAgICAgICAgICAgIGNzcy5sZWZ0ID0gJHdpbmRvdy5vdXRlcldpZHRoKCkgLyAyIC0gaG9sZGVyV2lkdGggLyAyO1xuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICBjc3MubGVmdCA9IHBhcnNlRmxvYXQoJGhvbGRlci5jc3MoJ2xlZnQnKSk7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIGlmKGNzcy5sZWZ0ICsgaG9sZGVyV2lkdGggPiAkd2luZG93LndpZHRoKCkpe1xuICAgICAgICAgICAgICAgIGNzcy5sZWZ0ID0gY3NzLmxlZnQgLSAoKGNzcy5sZWZ0ICsgaG9sZGVyV2lkdGgpIC0gJHdpbmRvdy53aWR0aCgpKTtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgaWYgKGR0b3ApIHtcbiAgICAgICAgICAgICAgICBjc3MudG9wID0gZHRvcCA+IDAgPyBkdG9wIDogMDtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgaWYod2luZG93ICE9PSBtdy50b3AoKS53aW4gJiYgZG9jdW1lbnQuYm9keS5zY3JvbGxIZWlnaHQgPiBtdy50b3AoKS53aW4uaW5uZXJIZWlnaHQpe1xuICAgICAgICAgICAgICAgICR3aW4gPSAkKG13LnRvcCgpKTtcblxuICAgICAgICAgICAgICAgIGNzcy50b3AgPSAkKGRvY3VtZW50KS5zY3JvbGxUb3AoKSArIDUwO1xuICAgICAgICAgICAgICAgIHZhciBvZmYgPSAkKHdpbmRvdy5mcmFtZUVsZW1lbnQpLm9mZnNldCgpO1xuICAgICAgICAgICAgICAgIGlmKG9mZi50b3AgPCAwKSB7XG4gICAgICAgICAgICAgICAgICAgIGNzcy50b3AgKz0gTWF0aC5hYnMob2ZmLnRvcCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGlmKHdpbmRvdy50aGlzbW9kYWwpIHtcbiAgICAgICAgICAgICAgICAgICAgY3NzLnRvcCArPSB0aGlzbW9kYWwuZGlhbG9nQ29udGFpbmVyLnNjcm9sbFRvcDtcbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIH1cblxuXG4gICAgICAgICAgICAkaG9sZGVyLmNzcyhjc3MpO1xuICAgICAgICAgICAgdGhpcy5fcHJldkhlaWdodCA9IGhvbGRlckhlaWdodDtcblxuXG4gICAgICAgICAgICB0aGlzLl9hZnRlclNpemUoKTtcbiAgICAgICAgICAgIG13LiQodGhpcykudHJpZ2dlcignZGlhbG9nQ2VudGVyJyk7XG5cbiAgICAgICAgICAgIHJldHVybiB0aGlzO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMud2lkdGggPSBmdW5jdGlvbiAod2lkdGgpIHtcbiAgICAgICAgICAgIGlmKCF3aWR0aCkge1xuICAgICAgICAgICAgICAgIHJldHVybiBtdy4kKHRoaXMuZGlhbG9nSG9sZGVyKS5vdXRlcldpZHRoKCk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBtdy4kKHRoaXMuZGlhbG9nSG9sZGVyKS53aWR0aCh3aWR0aCk7XG4gICAgICAgICAgICB0aGlzLl9hZnRlclNpemUoKTtcbiAgICAgICAgfTtcbiAgICAgICAgdGhpcy5oZWlnaHQgPSBmdW5jdGlvbiAoaGVpZ2h0KSB7XG4gICAgICAgICAgICBpZighaGVpZ2h0KSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIG13LiQodGhpcy5kaWFsb2dIb2xkZXIpLm91dGVySGVpZ2h0KCk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBtdy4kKHRoaXMuZGlhbG9nSG9sZGVyKS5oZWlnaHQoaGVpZ2h0KTtcbiAgICAgICAgICAgIHRoaXMuX2FmdGVyU2l6ZSgpO1xuICAgICAgICB9O1xuICAgICAgICB0aGlzLnJlc2l6ZSA9IGZ1bmN0aW9uICh3aWR0aCwgaGVpZ2h0KSB7XG4gICAgICAgICAgICBpZiAodHlwZW9mIHdpZHRoICE9PSAndW5kZWZpbmVkJykge1xuICAgICAgICAgICAgICAgIHRoaXMud2lkdGgod2lkdGgpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgaWYgKHR5cGVvZiBoZWlnaHQgIT09ICd1bmRlZmluZWQnKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5oZWlnaHQoaGVpZ2h0KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHRoaXMuY2VudGVyKHdpZHRoLCBoZWlnaHQpO1xuICAgICAgICB9O1xuICAgICAgICB0aGlzLmNvbnRlbnQgPSBmdW5jdGlvbiAoY29udGVudCkge1xuICAgICAgICAgICAgdGhpcy5vcHRpb25zLmNvbnRlbnQgPSBjb250ZW50IHx8ICcnO1xuICAgICAgICAgICAgJCh0aGlzLmRpYWxvZ0NvbnRhaW5lcikuZW1wdHkoKS5hcHBlbmQodGhpcy5vcHRpb25zLmNvbnRlbnQpO1xuICAgICAgICAgICAgcmV0dXJuIHRoaXM7XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5yZXN1bHQgPSBmdW5jdGlvbihyZXN1bHQsIGRvQ2xvc2UpIHtcbiAgICAgICAgICAgIHRoaXMudmFsdWUgPSByZXN1bHQ7XG4gICAgICAgICAgICBpZih0aGlzLm9wdGlvbnMub25SZXN1bHQpe1xuICAgICAgICAgICAgICAgIHRoaXMub3B0aW9ucy5vblJlc3VsdC5jYWxsKCB0aGlzLCByZXN1bHQgKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGlmIChjcmVzKSB7XG4gICAgICAgICAgICAgICAgY3Jlcy5jYWxsKCB0aGlzLCByZXN1bHQgKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgICQodGhpcykudHJpZ2dlcignUmVzdWx0JywgW3Jlc3VsdF0pO1xuICAgICAgICAgICAgaWYoZG9DbG9zZSl7XG4gICAgICAgICAgICAgICAgdGhpcy5fZG9DbG9zZUJ1dHRvbigpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9O1xuXG5cbiAgICAgICAgdGhpcy5jb250ZW50TWF4SGVpZ2h0ID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdmFyIHNjb3BlID0gdGhpcztcbiAgICAgICAgICAgIGlmICh0aGlzLm9wdGlvbnMuc2Nyb2xsTW9kZSA9PT0gJ2luc2lkZScpIHtcbiAgICAgICAgICAgICAgICBtdy5pbnRlcnZhbCgnaWZyYW1lLScgKyB0aGlzLmlkLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgIHZhciBtYXggPSBtdy4kKHdpbmRvdykuaGVpZ2h0KCkgLSBzY29wZS5kaWFsb2dIZWFkZXIuY2xpZW50SGVpZ2h0IC0gc2NvcGUuZGlhbG9nRm9vdGVyLmNsaWVudEhlaWdodCAtIDQwO1xuICAgICAgICAgICAgICAgICAgICBzY29wZS5kaWFsb2dDb250YWluZXIuc3R5bGUubWF4SGVpZ2h0ID0gbWF4ICsgJ3B4JztcbiAgICAgICAgICAgICAgICAgICAgc2NvcGUuY29udGFpbm1lbnRNYW5hZ2UoKTtcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfTtcbiAgICAgICAgdGhpcy5pbml0ID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdGhpcy5idWlsZCgpO1xuICAgICAgICAgICAgdGhpcy5jb250ZW50TWF4SGVpZ2h0KCk7XG4gICAgICAgICAgICB0aGlzLmNlbnRlcigpO1xuICAgICAgICAgICAgdGhpcy5zaG93KCk7XG4gICAgICAgICAgICBpZiAodGhpcy5vcHRpb25zLmF1dG9DZW50ZXIpIHtcbiAgICAgICAgICAgICAgICAoZnVuY3Rpb24gKHNjb3BlKSB7XG4gICAgICAgICAgICAgICAgICAgIG13LiQod2luZG93KS5vbigncmVzaXplIG9yaWVudGF0aW9uY2hhbmdlIGxvYWQnLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBzY29wZS5jb250ZW50TWF4SGVpZ2h0KCk7XG4gICAgICAgICAgICAgICAgICAgICAgICBzY29wZS5jZW50ZXIoKTtcbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgfSkodGhpcyk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBpZiAoIXRoaXMub3B0aW9ucy5wYXVzZUluaXQpIHtcbiAgICAgICAgICAgICAgICBtdy4kKHRoaXMpLnRyaWdnZXIoJ0luaXQnKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICBzY29wZS5jZW50ZXIoKTtcbiAgICAgICAgICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgICAgIHNjb3BlLmNlbnRlcigpO1xuICAgICAgICAgICAgICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgICAgICAgICBzY29wZS5jZW50ZXIoKTtcbiAgICAgICAgICAgICAgICAgICAgfSwgMzAwMCk7XG4gICAgICAgICAgICAgICAgfSwgMzMzKTtcbiAgICAgICAgICAgIH0sIDc4KTtcblxuXG4gICAgICAgICAgICByZXR1cm4gdGhpcztcbiAgICAgICAgfTtcbiAgICAgICAgdGhpcy5pbml0KCk7XG5cbiAgICB9O1xuXG4gICAgbXcuRGlhbG9nLmVsZW1lbnRJc0luRGlhbG9nID0gZnVuY3Rpb24gKG5vZGUpIHtcbiAgICAgICAgcmV0dXJuIG13LnRvb2xzLmZpcnN0UGFyZW50V2l0aENsYXNzKG5vZGUsICdtdy1kaWFsb2cnKTtcbiAgICB9O1xuXG5cblxuXG59KSh3aW5kb3cubXcpO1xuXG5cbihmdW5jdGlvbiAoKSB7XG4gICAgZnVuY3Rpb24gc2NvcGVkKCkge1xuICAgICAgICB2YXIgYWxsID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCgnc3R5bGVbc2NvcGVkXScpLCBpID0gMDtcblxuICAgICAgICB0cnkge1xuICAgICAgICAgICAgZm9yKCA7IGkgPCBhbGwubGVuZ3RoOyBpKysgKSB7XG4gICAgICAgICAgICAgICAgdmFyIHBhcmVudCA9IGFsbFtpXS5wYXJlbnROb2RlO1xuICAgICAgICAgICAgICAgIHBhcmVudC5pZCA9IHBhcmVudC5pZCB8fCBtdy5pZCgnc2NvcGVkLWlkLScpO1xuICAgICAgICAgICAgICAgIHZhciBwcmVmaXggPSAnIycgKyBwYXJlbnQuaWQgKyAnICc7XG4gICAgICAgICAgICAgICAgdmFyIHJ1bGVzID0gYWxsW2ldLnNoZWV0LnJ1bGVzO1xuICAgICAgICAgICAgICAgIHZhciByID0gMDtcbiAgICAgICAgICAgICAgICBmb3IgKCA7IHIgPCBydWxlcy5sZW5ndGg7IHIrKykge1xuICAgICAgICAgICAgICAgICAgICB2YXIgbmV3UnVsZSA9IHByZWZpeCArIHJ1bGVzW3JdLmNzc1RleHQ7XG4gICAgICAgICAgICAgICAgICAgIGFsbFtpXS5zaGVldC5kZWxldGVSdWxlKHIpO1xuICAgICAgICAgICAgICAgICAgICBhbGxbaV0uc2hlZXQuaW5zZXJ0UnVsZShuZXdSdWxlLCByKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgYWxsW2ldLnJlbW92ZUF0dHJpYnV0ZSgnc2NvcGVkJyk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgY2F0Y2goZXJyb3IpIHtcblxuICAgICAgICB9XG5cblxuICAgIH1cbiAgICBzY29wZWQoKTtcbiAgICAkKHdpbmRvdykub24oJ2xvYWQnLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHNjb3BlZCgpO1xuICAgIH0pO1xufSkoKTtcblxuXG4iLCIoZnVuY3Rpb24oKXtcbiAgICBtdy5yZXF1aXJlKCdtb2RhbC5jc3MnKTtcblxuICAgIHZhciBHYWxsZXJ5ID0gZnVuY3Rpb24gKGFycmF5LCBzdGFydEZyb20pIHtcbiAgICAgICAgc3RhcnRGcm9tID0gc3RhcnRGcm9tIHx8IDA7XG5cbiAgICAgICAgdGhpcy5jdXJyZW50SW5kZXggPSBzdGFydEZyb207XG5cbiAgICAgICAgdGhpcy5kYXRhID0gYXJyYXk7XG4gICAgICAgIHZhciBzY29wZSA9IHRoaXM7XG5cbiAgICAgICAgdGhpcy5fdGVtcGxhdGUgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB2YXIgZWwgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgICAgIGVsLmNsYXNzTmFtZSA9ICdtdy1nYWxsZXJ5JztcbiAgICAgICAgICAgIGVsLmlubmVySFRNTCA9ICcnICtcbiAgICAgICAgICAgICc8ZGl2IGNsYXNzPVwiXCI+JyArXG4gICAgICAgICAgICAgICAgJzxkaXYgY2xhc3M9XCJtdy1nYWxsZXJ5LW92ZXJsYXlcIj48L2Rpdj4nICtcbiAgICAgICAgICAgICAgICAnPGRpdiBjbGFzcz1cIm13LWdhbGxlcnktY29udGVudFwiPjwvZGl2PicgK1xuICAgICAgICAgICAgICAgICc8ZGl2IGNsYXNzPVwibXctZ2FsbGVyeS1wcmV2XCI+PC9kaXY+JyArXG4gICAgICAgICAgICAgICAgJzxkaXYgY2xhc3M9XCJtdy1nYWxsZXJ5LW5leHRcIj48L2Rpdj4nICtcbiAgICAgICAgICAgICAgICAnPGRpdiBjbGFzcz1cIm13LWdhbGxlcnktY29udHJvbHNcIj4nICtcbiAgICAgICAgICAgICAgICAgICAgJzxzcGFuIGNsYXNzPVwibXctZ2FsbGVyeS1jb250cm9sLXBsYXlcIj48L3NwYW4+JyArXG4gICAgICAgICAgICAgICAgICAgIC8qJzxzcGFuIGNsYXNzPVwibXctZ2FsbGVyeS1jb250cm9sLWZ1bGxzY3JlZW5cIj48L3NwYW4+JyArKi9cbiAgICAgICAgICAgICAgICAgICAgJzxzcGFuIGNsYXNzPVwibXctZ2FsbGVyeS1jb250cm9sLWNsb3NlXCI+PC9zcGFuPicgK1xuICAgICAgICAgICAgICAgICc8L2Rpdj4nICtcbiAgICAgICAgICAgICc8L2Rpdj4nO1xuICAgICAgICAgICAgcmV0dXJuIGVsO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuY3JlYXRlU2luZ2xlID0gZnVuY3Rpb24gKGl0ZW0sIGkpIHtcbiAgICAgICAgICAgIHZhciBlbCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgICAgICAgICAgZWwuY2xhc3NOYW1lID0gJ213LWdhbGxlcnktaXRlbSBtdy1nYWxsZXJ5LWl0ZW0tJyArIGkgKyAoc3RhcnRGcm9tID09PSBpID8gJyBhY3RpdmUnIDogJycpO1xuICAgICAgICAgICAgdmFyIGRlc2MgPSAhaXRlbS5kZXNjcmlwdGlvbiA/ICcnIDogJzxkaXYgY2xhc3M9XCJtdy1nYWxsZXJ5LWl0ZW0tZGVzY3JpcHRpb25cIj4nK2l0ZW0uZGVzY3JpcHRpb24rJzwvZGl2Pic7XG4gICAgICAgICAgICBlbC5pbm5lckhUTUwgPSAnPGRpdiBjbGFzcz1cIm13LWdhbGxlcnktaXRlbS1pbWFnZVwiPjxpbWcgc3JjPVwiJysoaXRlbS5pbWFnZSB8fCBpdGVtLnVybCB8fCBpdGVtLnNyYykrJ1wiPjwvZGl2PicgKyBkZXNjO1xuICAgICAgICAgICAgdGhpcy5jb250YWluZXIuYXBwZW5kQ2hpbGQoZWwpO1xuICAgICAgICAgICAgcmV0dXJuIGVsO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMubmV4dCA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHRoaXMuY3VycmVudEluZGV4Kys7XG4gICAgICAgICAgICBpZighdGhpcy5faXRlbXNbdGhpcy5jdXJyZW50SW5kZXhdKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5jdXJyZW50SW5kZXggPSAwO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgdGhpcy5nb3RvKHRoaXMuY3VycmVudEluZGV4KTtcbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLnByZXYgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB0aGlzLmN1cnJlbnRJbmRleC0tO1xuICAgICAgICAgICAgaWYoIXRoaXMuX2l0ZW1zW3RoaXMuY3VycmVudEluZGV4XSkge1xuICAgICAgICAgICAgICAgIHRoaXMuY3VycmVudEluZGV4ID0gdGhpcy5faXRlbXMubGVuZ3RoIC0gMTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHRoaXMuZ290byh0aGlzLmN1cnJlbnRJbmRleCk7XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5nb3RvID0gZnVuY3Rpb24gKGkpIHtcbiAgICAgICAgICAgIGlmKGkgPiAtMSAmJiBpIDwgdGhpcy5faXRlbXMubGVuZ3RoKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5jdXJyZW50SW5kZXggPSBpO1xuICAgICAgICAgICAgICAgIHRoaXMuX2l0ZW1zLmZvckVhY2goZnVuY3Rpb24gKGl0ZW0sIGkpe1xuICAgICAgICAgICAgICAgICAgICBpdGVtLmNsYXNzTGlzdC5yZW1vdmUoJ2FjdGl2ZScpO1xuICAgICAgICAgICAgICAgICAgICBpZihpID09PSBzY29wZS5jdXJyZW50SW5kZXgpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGl0ZW0uY2xhc3NMaXN0LmFkZCgnYWN0aXZlJyk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLnBhdXNlZCA9IHRydWU7XG5cbiAgICAgICAgdGhpcy5wYXVzZSA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHRoaXMucGF1c2VkID0gdHJ1ZTtcbiAgICAgICAgICAgIGNsZWFyVGltZW91dCh0aGlzLnBsYXlJbnRlcnZhbCk7XG4gICAgICAgICAgICBtdy50b29scy5sb2FkaW5nKHRoaXMudGVtcGxhdGUsIGZhbHNlLCApO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMucGxheUludGVydmFsID0gbnVsbDtcbiAgICAgICAgdGhpcy5fcGxheSA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIGlmKHRoaXMucGF1c2VkKSByZXR1cm47XG4gICAgICAgICAgICBtdy50b29scy5sb2FkaW5nKHRoaXMudGVtcGxhdGUsIDEwMCwgJ3Nsb3cnKTtcbiAgICAgICAgICAgIHRoaXMucGxheUludGVydmFsID0gc2V0VGltZW91dChmdW5jdGlvbiAoKXtcbiAgICAgICAgICAgICAgICBtdy50b29scy5sb2FkaW5nKHNjb3BlLnRlbXBsYXRlLCAnaGlkZScpO1xuICAgICAgICAgICAgICAgIHNjb3BlLm5leHQoKTtcbiAgICAgICAgICAgICAgICBzY29wZS5fcGxheSgpO1xuICAgICAgICAgICAgfSw1MDAwKTtcbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLnBsYXkgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB0aGlzLm5leHQoKTtcbiAgICAgICAgICAgIHRoaXMucGF1c2VkID0gZmFsc2U7XG4gICAgICAgICAgICB0aGlzLl9wbGF5KCk7XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5faXRlbXMgPSBbXTtcblxuICAgICAgICB0aGlzLmNyZWF0ZUhhbmRsZXMgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB0aGlzLnRlbXBsYXRlLnF1ZXJ5U2VsZWN0b3IoJy5tdy1nYWxsZXJ5LXByZXYnKS5vbmNsaWNrID0gZnVuY3Rpb24gKCl7IHNjb3BlLnBhdXNlKCk7IHNjb3BlLnByZXYoKTsgfTtcbiAgICAgICAgICAgIHRoaXMudGVtcGxhdGUucXVlcnlTZWxlY3RvcignLm13LWdhbGxlcnktbmV4dCcpLm9uY2xpY2sgPSBmdW5jdGlvbiAoKXsgc2NvcGUucGF1c2UoKTsgc2NvcGUubmV4dCgpOyB9O1xuICAgICAgICAgICAgdGhpcy50ZW1wbGF0ZS5xdWVyeVNlbGVjdG9yKCcubXctZ2FsbGVyeS1jb250cm9sLWNsb3NlJykub25jbGljayA9IGZ1bmN0aW9uICgpeyBzY29wZS5yZW1vdmUoKTsgfTtcbiAgICAgICAgICAgIHRoaXMudGVtcGxhdGUucXVlcnlTZWxlY3RvcignLm13LWdhbGxlcnktY29udHJvbC1wbGF5Jykub25jbGljayA9IGZ1bmN0aW9uICgpe1xuICAgICAgICAgICAgICAgIHNjb3BlW3Njb3BlLnBhdXNlZCA/ICdwbGF5JyA6ICdwYXVzZSddKCk7XG4gICAgICAgICAgICAgICAgdGhpcy5jbGFzc0xpc3Rbc2NvcGUucGF1c2VkID8gJ3JlbW92ZScgOiAnYWRkJ10oJ3BhdXNlJyk7XG4gICAgICAgICAgICB9O1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuY3JlYXRlSXRlbXMgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB0aGlzLmRhdGEuZm9yRWFjaChmdW5jdGlvbiAoaXRlbSwgaSApe1xuICAgICAgICAgICAgICAgIHNjb3BlLl9pdGVtcy5wdXNoKHNjb3BlLmNyZWF0ZVNpbmdsZShpdGVtLCBpKSk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLmluaXQgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB0aGlzLnRlbXBsYXRlID0gdGhpcy5fdGVtcGxhdGUoKTtcbiAgICAgICAgICAgIGRvY3VtZW50LmJvZHkuYXBwZW5kQ2hpbGQodGhpcy50ZW1wbGF0ZSk7XG4gICAgICAgICAgICB0aGlzLmNvbnRhaW5lciA9IHRoaXMudGVtcGxhdGUucXVlcnlTZWxlY3RvcignLm13LWdhbGxlcnktY29udGVudCcpO1xuICAgICAgICAgICAgdGhpcy5jcmVhdGVJdGVtcygpO1xuICAgICAgICAgICAgdGhpcy5jcmVhdGVIYW5kbGVzKCk7XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5yZW1vdmUgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB0aGlzLnRlbXBsYXRlLnJlbW92ZSgpO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuaW5pdCgpO1xuICAgIH07XG5cbiAgICBtdy5nYWxsZXJ5ID0gZnVuY3Rpb24gKGFycmF5LCBzdGFydEZyb20pe1xuICAgICAgICByZXR1cm4gbmV3IEdhbGxlcnkoYXJyYXksIHN0YXJ0RnJvbSk7XG4gICAgfTtcblxuICAgIC8vIG9ic29sYXRlOlxuICAgIG13LnRvb2xzLmdhbGxlcnkgPSB7XG4gICAgICAgIGluaXQ6IG13LmdhbGxlcnlcbiAgICB9O1xufSkoKTtcbiIsIlxuXG4oZnVuY3Rpb24gKCkge1xuXG4gICAgdmFyIEljb25Mb2FkZXIgPSBmdW5jdGlvbiAoc3RvcmUpIHtcbiAgICAgICAgdmFyIHNjb3BlID0gdGhpcztcblxuICAgICAgICB2YXIgZGVmYXVsdFZlcnNpb24gPSAnLTEnO1xuXG4gICAgICAgIHZhciBpY29uc0NhY2hlID0ge307XG5cblxuICAgICAgICB2YXIgY29tbW9uID0ge1xuICAgICAgICAgICAgJ2ZvbnRBd2Vzb21lJzoge1xuICAgICAgICAgICAgICAgIGNzc1NlbGVjdG9yOiAnLmZhJyxcbiAgICAgICAgICAgICAgICBkZXRlY3Q6IGZ1bmN0aW9uICh0YXJnZXQpIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHRhcmdldC5jbGFzc0xpc3QuY29udGFpbnMoJ2ZhJyk7XG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICByZW5kZXI6IGZ1bmN0aW9uIChpY29uLCB0YXJnZXQpIHtcbiAgICAgICAgICAgICAgICAgICAgdGFyZ2V0LmNsYXNzTGlzdC5hZGQoJ2ZhJyk7XG4gICAgICAgICAgICAgICAgICAgIHRhcmdldC5jbGFzc0xpc3QuYWRkKGljb24pO1xuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgcmVtb3ZlOiBmdW5jdGlvbiAodGFyZ2V0KSB7XG4gICAgICAgICAgICAgICAgICAgIHRhcmdldC5jbGFzc0xpc3QucmVtb3ZlKCdmYScpO1xuICAgICAgICAgICAgICAgICAgICB2YXIgZXhjZXB0aW9uPSBbJ2ZhLWxnJywgJ2ZhLTJ4JywgJ2ZhLTN4JywgJ2ZhLTR4JywgJ2ZhLTV4JywgJ2ZhLWZ3JywgJ2ZhLXNwaW4nLCAnZmEtcHVsZScsICdmYS1yb3RhdGUtOTAnLFxuICAgICAgICAgICAgICAgICAgICAgICAgJ2ZhLXJvdGF0ZS0xODAnLCAnZmEtcm90YXRlLTI3MCcsICdmYS1mbGlwLWhvcml6b250YWwnLCAnZmEtZmxpcC12ZXJ0aWNhbCddO1xuICAgICAgICAgICAgICAgICAgICBtdy50b29scy5jbGFzc05hbWVzcGFjZURlbGV0ZSh0YXJnZXQsICdmYS0nLCB1bmRlZmluZWQsIHVuZGVmaW5lZCwgZXhjZXB0aW9uKTtcbiAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgIGljb25zOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgICByZXR1cm4gbmV3IFByb21pc2UoZnVuY3Rpb24gKHJlc29sdmUpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAkLmdldChtdy5zZXR0aW5ncy5tb2R1bGVzX3VybCArICdtaWNyb3dlYmVyL2FwaS9mYS5pY29ucy5qcycsZnVuY3Rpb24gKGRhdGEpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgcmVzb2x2ZShKU09OLnBhcnNlKGRhdGEpKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgIG5hbWU6ICdGb250IEF3ZXNvbWUnLFxuICAgICAgICAgICAgICAgIGxvYWQ6ICBtdy5zZXR0aW5ncy5saWJzX3VybCArICdmb250YXdlc29tZS00LjcuMCcgKyAnL2Nzcy9mb250LWF3ZXNvbWUubWluLmNzcycsXG4gICAgICAgICAgICAgICAgdW5sb2FkOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgIGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJ2xpbmtbaHJlZio9XCJmb250YXdlc29tZS00LjcuMFwiXScpLnJlbW92ZSgpO1xuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgdmVyc2lvbjogJzQuNy4wJ1xuICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICdtYXRlcmlhbEljb25zJzoge1xuICAgICAgICAgICAgICAgIGNzc1NlbGVjdG9yOiAnLm1hdGVyaWFsLWljb25zJyxcbiAgICAgICAgICAgICAgICBkZXRlY3Q6IGZ1bmN0aW9uICh0YXJnZXQpIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHRhcmdldC5jbGFzc0xpc3QuY29udGFpbnMoJ21hdGVyaWFsLWljb25zJyk7XG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICByZW5kZXI6IGZ1bmN0aW9uIChpY29uLCB0YXJnZXQpIHtcbiAgICAgICAgICAgICAgICAgICAgdGFyZ2V0LmNsYXNzTGlzdC5hZGQoJ21hdGVyaWFsLWljb25zJyk7XG4gICAgICAgICAgICAgICAgICAgIHRhcmdldC5pbm5lckhUTUwgPSAoaWNvbik7XG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICByZW1vdmU6IGZ1bmN0aW9uICh0YXJnZXQpIHtcbiAgICAgICAgICAgICAgICAgICAgbXcudG9vbHMucmVtb3ZlQ2xhc3ModGFyZ2V0LCAnbWF0ZXJpYWwtaWNvbnMnKTtcbiAgICAgICAgICAgICAgICAgICAgdGFyZ2V0LmlubmVySFRNTCA9ICcnO1xuICAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgIGljb25zOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBuZXcgUHJvbWlzZShmdW5jdGlvbiAocmVzb2x2ZSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgJC5nZXQobXcuc2V0dGluZ3MubW9kdWxlc191cmwgKyAnbWljcm93ZWJlci9hcGkvbWF0ZXJpYWwuaWNvbnMuanMnLGZ1bmN0aW9uIChkYXRhKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgcmVzb2x2ZShKU09OLnBhcnNlKGRhdGEpKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgIG5hbWU6ICdNYXRlcmlhbCBJY29ucycsXG4gICAgICAgICAgICAgICAgbG9hZDogbXcuc2V0dGluZ3MubGlic191cmwgKyAnbWF0ZXJpYWxfaWNvbnMnICsgJy9tYXRlcmlhbF9pY29ucy5jc3MnLFxuICAgICAgICAgICAgICAgIHVubG9hZDogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICB0b3AuZG9jdW1lbnQucXVlcnlTZWxlY3RvcignbGlua1tocmVmKj1cIm1hdGVyaWFsX2ljb25zLmNzc1wiXScpLnJlbW92ZSgpO1xuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgdmVyc2lvbjogJ213J1xuICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICdpY29uc01pbmRMaW5lJzoge1xuICAgICAgICAgICAgICAgIGNzc1NlbGVjdG9yOiAnW2NsYXNzKj1cIm13LW1pY29uLVwiXTpub3QoW2NsYXNzKj1cIm13LW1pY29uLXNvbGlkLVwiXSknLFxuICAgICAgICAgICAgICAgIGRldGVjdDogZnVuY3Rpb24gKHRhcmdldCkge1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gdGFyZ2V0LmNsYXNzTmFtZS5pbmNsdWRlcygnbXctbWljb24tJykgJiYgIXRhcmdldC5jbGFzc05hbWUuaW5jbHVkZXMoJ213LW1pY29uLXNvbGlkLScpO1xuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgcmVuZGVyOiBmdW5jdGlvbiAoaWNvbiwgdGFyZ2V0KSB7XG4gICAgICAgICAgICAgICAgICAgIHRhcmdldC5jbGFzc0xpc3QuYWRkKGljb24pO1xuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgcmVtb3ZlOiBmdW5jdGlvbiAodGFyZ2V0KSB7XG4gICAgICAgICAgICAgICAgICAgIG13LnRvb2xzLmNsYXNzTmFtZXNwYWNlRGVsZXRlKHRhcmdldCwgJ213LW1pY29uLScsIHVuZGVmaW5lZCwgdW5kZWZpbmVkLCBbXSk7XG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICBpY29uczogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gbmV3IFByb21pc2UoZnVuY3Rpb24gKHJlc29sdmUpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciBpY29ucyA9IG13LnRvcCgpLndpbi5kb2N1bWVudC5xdWVyeVNlbGVjdG9yKCdsaW5rW2hyZWYqPVwibXctaWNvbnMtbWluZC9saW5lXCJdJykuc2hlZXQuY3NzUnVsZXM7XG4gICAgICAgICAgICAgICAgICAgICAgICB2YXIgbCA9IGljb25zLmxlbmd0aCwgaSA9IDAsIG1pbmRJY29ucyA9IFtdO1xuICAgICAgICAgICAgICAgICAgICAgICAgZm9yICg7IGkgPCBsOyBpKyspIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB2YXIgc2VsID0gaWNvbnNbaV0uc2VsZWN0b3JUZXh0O1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGlmICghIXNlbCAmJiBzZWwuaW5kZXhPZignLm13LW1pY29uLScpID09PSAwKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHZhciBjbHMgPSBzZWwucmVwbGFjZShcIi5cIiwgJycpLnNwbGl0KCc6JylbMF07XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIG1pbmRJY29ucy5wdXNoKGNscyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgcmVzb2x2ZShtaW5kSWNvbnMpXG5cbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICBuYW1lOiAnSWNvbnMgTWluZCBMaW5lJyxcbiAgICAgICAgICAgICAgICBsb2FkOiAgbXcuc2V0dGluZ3MubW9kdWxlc191cmwgKyAnbWljcm93ZWJlci9hcGkvbGlicy9tdy1pY29ucy1taW5kL2xpbmUvc3R5bGUuY3NzJyxcbiAgICAgICAgICAgICAgICB1bmxvYWQ6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgZG9jdW1lbnQucXVlcnlTZWxlY3RvcignbGlua1tocmVmKj1cIm13LWljb25zLW1pbmQvbGluZS9zdHlsZVwiXScpLnJlbW92ZSgpO1xuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgdmVyc2lvbjogJ213X2xvY2FsJ1xuICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICdpY29uc01pbmRTb2xpZCc6IHtcbiAgICAgICAgICAgICAgICBjc3NTZWxlY3RvcjogJ1tjbGFzcyo9XCJtdy1taWNvbi1zb2xpZC1cIl0nLFxuICAgICAgICAgICAgICAgIGRldGVjdDogZnVuY3Rpb24gKHRhcmdldCkge1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gdGFyZ2V0LmNsYXNzTmFtZS5pbmNsdWRlcygnbXctbWljb24tc29saWQtJyk7XG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICByZW5kZXI6IGZ1bmN0aW9uIChpY29uLCB0YXJnZXQpIHtcbiAgICAgICAgICAgICAgICAgICAgdGFyZ2V0LmNsYXNzTGlzdC5hZGQoaWNvbik7XG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICByZW1vdmU6IGZ1bmN0aW9uICh0YXJnZXQpIHtcbiAgICAgICAgICAgICAgICAgICAgbXcudG9vbHMuY2xhc3NOYW1lc3BhY2VEZWxldGUodGFyZ2V0LCAnbXctbWljb24tc29saWQtJywgdW5kZWZpbmVkLCB1bmRlZmluZWQsIFtdKTtcbiAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgIGljb25zOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBuZXcgUHJvbWlzZShmdW5jdGlvbiAocmVzb2x2ZSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgdmFyIGljb25zID0gbXcudG9wKCkud2luLmRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJ2xpbmtbaHJlZio9XCJtdy1pY29ucy1taW5kL3NvbGlkXCJdJykuc2hlZXQuY3NzUnVsZXM7XG4gICAgICAgICAgICAgICAgICAgICAgICB2YXIgbCA9IGljb25zLmxlbmd0aCwgaSA9IDAsIG1pbmRJY29ucyA9IFtdO1xuICAgICAgICAgICAgICAgICAgICAgICAgZm9yICg7IGkgPCBsOyBpKyspIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB2YXIgc2VsID0gaWNvbnNbaV0uc2VsZWN0b3JUZXh0O1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGlmICghIXNlbCAmJiBzZWwuaW5kZXhPZignLm13LW1pY29uLXNvbGlkLScpID09PSAwKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHZhciBjbHMgPSBzZWwucmVwbGFjZShcIi5cIiwgJycpLnNwbGl0KCc6JylbMF07XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIG1pbmRJY29ucy5wdXNoKGNscyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgcmVzb2x2ZShtaW5kSWNvbnMpO1xuXG4gICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgbmFtZTogJ0ljb25zIE1pbmQgU29saWQnLFxuICAgICAgICAgICAgICAgIGxvYWQ6ICBtdy5zZXR0aW5ncy5tb2R1bGVzX3VybCArICdtaWNyb3dlYmVyL2FwaS9saWJzL213LWljb25zLW1pbmQvc29saWQvc3R5bGUuY3NzJyxcbiAgICAgICAgICAgICAgICB1bmxvYWQ6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgZG9jdW1lbnQucXVlcnlTZWxlY3RvcignbGlua1tocmVmKj1cIm13LWljb25zLW1pbmQvc29saWQvc3R5bGVcIl0nKS5yZW1vdmUoKTtcbiAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgIHZlcnNpb246ICdtd19sb2NhbCdcbiAgICAgICAgICAgIH0sXG5cbiAgICAgICAgICAgICdtYXRlcmlhbERlc2lnbkljb25zJzoge1xuICAgICAgICAgICAgICAgIGNzc1NlbGVjdG9yOiAnLm1kaScsXG4gICAgICAgICAgICAgICAgZGV0ZWN0OiBmdW5jdGlvbiAodGFyZ2V0KSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiB0YXJnZXQuY2xhc3NMaXN0LmNvbnRhaW5zKCdtZGknKTtcbiAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgIHJlbmRlcjogZnVuY3Rpb24gKGljb24sIHRhcmdldCkge1xuICAgICAgICAgICAgICAgICAgICB0YXJnZXQuY2xhc3NMaXN0LmFkZCgnbWRpJyk7XG4gICAgICAgICAgICAgICAgICAgIHRhcmdldC5jbGFzc0xpc3QuYWRkKGljb24pO1xuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgcmVtb3ZlOiBmdW5jdGlvbiAodGFyZ2V0KSB7XG4gICAgICAgICAgICAgICAgICAgIG13LnRvb2xzLmNsYXNzTmFtZXNwYWNlRGVsZXRlKHRhcmdldCwgJ21kaS0nLCB1bmRlZmluZWQsIHVuZGVmaW5lZCwgW10pO1xuICAgICAgICAgICAgICAgICAgICB0YXJnZXQuY2xhc3NMaXN0LnJlbW92ZSgnbWRpJyk7XG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICBpY29uczogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gbmV3IFByb21pc2UoZnVuY3Rpb24gKHJlc29sdmUpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciBpY29ucyA9IG13LnRvcCgpLndpbi5kb2N1bWVudC5xdWVyeVNlbGVjdG9yKCdsaW5rW2hyZWYqPVwibWF0ZXJpYWxkZXNpZ25pY29uc1wiXScpLnNoZWV0LmNzc1J1bGVzO1xuICAgICAgICAgICAgICAgICAgICAgICAgdmFyIGwgPSBpY29ucy5sZW5ndGgsIGkgPSAwLCBtZGlJY29ucyA9IFtdO1xuICAgICAgICAgICAgICAgICAgICAgICAgZm9yICg7IGkgPCBsOyBpKyspIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB2YXIgc2VsID0gaWNvbnNbaV0uc2VsZWN0b3JUZXh0O1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGlmICghIXNlbCAmJiBzZWwuaW5kZXhPZignLm1kaS0nKSA9PT0gMCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB2YXIgY2xzID0gc2VsLnJlcGxhY2UoXCIuXCIsICcnKS5zcGxpdCgnOicpWzBdO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBtZGlJY29ucy5wdXNoKGNscyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgcmVzb2x2ZShtZGlJY29ucyk7XG5cbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICBuYW1lOiAnTWF0ZXJpYWwgRGVzaWduIEljb25zJyxcbiAgICAgICAgICAgICAgICBsb2FkOiAgbXcuc2V0dGluZ3MubW9kdWxlc191cmwgKyAnbWljcm93ZWJlci9jc3MvZm9udHMvbWF0ZXJpYWxkZXNpZ25pY29ucy9jc3MvbWF0ZXJpYWxkZXNpZ25pY29ucy5taW4uY3NzJyxcbiAgICAgICAgICAgICAgICB1bmxvYWQ6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgZG9jdW1lbnQucXVlcnlTZWxlY3RvcignbGlua1tocmVmKj1cIm1hdGVyaWFsZGVzaWduaWNvbnNcIl0nKS5yZW1vdmUoKTtcbiAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgIHZlcnNpb246ICdtd19sb2NhbCdcbiAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAnbXdJY29ucyc6IHtcbiAgICAgICAgICAgICAgICBjc3NTZWxlY3RvcjogJ1tjbGFzcyo9XCJtdy1pY29uLVwiXScsXG4gICAgICAgICAgICAgICAgZGV0ZWN0OiBmdW5jdGlvbiAodGFyZ2V0KSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiB0YXJnZXQuY2xhc3NOYW1lLmluY2x1ZGVzKCdtdy1pY29uLScpO1xuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgcmVuZGVyOiBmdW5jdGlvbiAoaWNvbiwgdGFyZ2V0KSB7XG4gICAgICAgICAgICAgICAgICAgIHRhcmdldC5jbGFzc0xpc3QuYWRkKGljb24pO1xuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgcmVtb3ZlOiBmdW5jdGlvbiAodGFyZ2V0KSB7XG4gICAgICAgICAgICAgICAgICAgIG13LnRvb2xzLmNsYXNzTmFtZXNwYWNlRGVsZXRlKHRhcmdldCwgJ213LWljb24tJywgdW5kZWZpbmVkLCB1bmRlZmluZWQsIFtdKTtcbiAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgIGljb25zOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBuZXcgUHJvbWlzZShmdW5jdGlvbiAocmVzb2x2ZSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgJC5nZXQobXcuc2V0dGluZ3MubW9kdWxlc191cmwgKyAnbWljcm93ZWJlci9hcGkvbWljcm93ZWJlci5pY29ucy5qcycsZnVuY3Rpb24gKGRhdGEpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICByZXNvbHZlKEpTT04ucGFyc2UoZGF0YSkpO1xuICAgICAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgbmFtZTogJ01pY3Jvd2ViZXIgSWNvbnMnLFxuICAgICAgICAgICAgICAgIGxvYWQ6ICBtdy5zZXR0aW5ncy5tb2R1bGVzX3VybCArICdtaWNyb3dlYmVyL2Nzcy9mb250cy9tYXRlcmlhbGRlc2lnbmljb25zL2Nzcy9tYXRlcmlhbGRlc2lnbmljb25zLm1pbi5jc3MnLFxuICAgICAgICAgICAgICAgIHVubG9hZDogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCdsaW5rW2hyZWYqPVwibWF0ZXJpYWxkZXNpZ25pY29uc1wiXScpLnJlbW92ZSgpO1xuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgdmVyc2lvbjogJ213X2xvY2FsJ1xuICAgICAgICAgICAgfSxcbiAgICAgICAgfTtcblxuICAgICAgICB2YXIgc3RvcmFnZSA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIGlmKCFtdy50b3AoKS5fX0ljb25TdG9yYWdlKSB7XG4gICAgICAgICAgICAgICAgbXcudG9wKCkuX19JY29uU3RvcmFnZSA9IFtdO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgcmV0dXJuIG13LnRvcCgpLl9fSWNvblN0b3JhZ2U7XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5zdG9yYWdlID0gc3RvcmUgfHwgc3RvcmFnZTtcblxuXG4gICAgICAgIHZhciBpY29uU2V0S2V5ID0gZnVuY3Rpb24gKG9wdGlvbnMpIHtcbiAgICAgICAgICAgIHJldHVybiBvcHRpb25zLm5hbWUgKyBvcHRpb25zLnZlcnNpb247XG4gICAgICAgIH07XG5cbiAgICAgICAgdmFyIGljb25TZXRQdXNoID0gZnVuY3Rpb24gKG9wdGlvbnMpIHtcbiAgICAgICAgICAgIGlmKCFzdG9yYWdlKCkuZmluZChmdW5jdGlvbiAoYSkge3JldHVybiBpY29uU2V0S2V5KG9wdGlvbnMpID09PSBpY29uU2V0S2V5KGEpOyB9KSkge1xuICAgICAgICAgICAgICAgIHJldHVybiBzdG9yYWdlKCkucHVzaChvcHRpb25zKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgfTtcblxuICAgICAgICB2YXIgYWRkRm9udEljb25TZXQgPSBmdW5jdGlvbiAob3B0aW9ucykge1xuICAgICAgICAgICAgb3B0aW9ucy52ZXJzaW9uID0gb3B0aW9ucy52ZXJzaW9uIHx8IGRlZmF1bHRWZXJzaW9uO1xuICAgICAgICAgICAgaWNvblNldFB1c2gob3B0aW9ucyk7XG4gICAgICAgICAgICBpZiAodHlwZW9mIG9wdGlvbnMubG9hZCA9PT0gJ3N0cmluZycpIHtcbiAgICAgICAgICAgICAgICBtdy5yZXF1aXJlKG9wdGlvbnMubG9hZCk7XG4gICAgICAgICAgICB9IGVsc2UgaWYgKHR5cGVvZiBvcHRpb25zLmxvYWQgPT09ICdmdW5jdGlvbicpIHtcbiAgICAgICAgICAgICAgICBvcHRpb25zLmxvYWQoKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfTtcbiAgICAgICAgdmFyIGFkZEljb25TZXQgPSBmdW5jdGlvbiAoY29uZikge1xuICAgICAgICAgICAgaWYodHlwZW9mIGNvbmYgPT09ICdzdHJpbmcnKSB7XG4gICAgICAgICAgICAgICAgaWYgKGNvbW1vbltjb25mXSkge1xuICAgICAgICAgICAgICAgICAgICBjb25mID0gY29tbW9uW2NvbmZdO1xuICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgIGNvbnNvbGUud2Fybihjb25mICsgJyBpcyBub3QgZGVmaW5lZC4nKTtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGlmKCFjb25mKSByZXR1cm47XG4gICAgICAgICAgICBjb25mLnR5cGUgPSBjb25mLnR5cGUgfHwgJ2ZvbnQnO1xuICAgICAgICAgICAgaWYgKGNvbmYudHlwZSA9PT0gJ2ZvbnQnKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIGFkZEZvbnRJY29uU2V0KGNvbmYpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuYWRkSWNvblNldCA9IGZ1bmN0aW9uIChjb25mKSB7XG4gICAgICAgICAgICBhZGRJY29uU2V0KGNvbmYpO1xuICAgICAgICAgICAgcmV0dXJuIHRoaXM7XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5yZW1vdmVJY29uU2V0ID0gZnVuY3Rpb24gKG5hbWUsIHZlcnNpb24pIHtcbiAgICAgICAgICAgIHZhciBzdHIgPSBzdG9yYWdlKCk7XG4gICAgICAgICAgICB2YXIgaXRlbSA9IHN0ci5maW5kKGZ1bmN0aW9uIChhKSB7IHJldHVybiBhLm5hbWUgPT09IG5hbWUgJiYgKCF2ZXJzaW9uIHx8IGEudmVyc2lvbiA9PT0gdmVyc2lvbik7IH0pO1xuICAgICAgICAgICAgaWYgKGl0ZW0pIHtcbiAgICAgICAgICAgICAgICBpZiAoaXRlbS51bmxvYWQpIHtcbiAgICAgICAgICAgICAgICAgICAgaXRlbS51bmxvYWQoKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgc3RyLnNwbGljZShzdHIuaW5kZXhPZihpdGVtKSwgMSk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH07XG5cblxuICAgICAgICB0aGlzLmluaXQgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBzdG9yYWdlKCkuZm9yRWFjaChmdW5jdGlvbiAoaWNvblNldCl7XG4gICAgICAgICAgICAgICAgc2NvcGUuYWRkSWNvblNldChpY29uU2V0KTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9O1xuXG4gICAgfTtcblxuICAgIG13Lmljb25Mb2FkZXIgPSBmdW5jdGlvbiAob3B0aW9ucykge1xuICAgICAgICByZXR1cm4gbmV3IEljb25Mb2FkZXIob3B0aW9ucyk7XG4gICAgfTtcblxuXG59KSgpO1xuXG5cbihmdW5jdGlvbiAoKXtcblxuICAgIHZhciBJY29uUGlja2VyID0gZnVuY3Rpb24gKG9wdGlvbnMpIHtcbiAgICAgICAgb3B0aW9ucyA9IG9wdGlvbnMgfHwge307XG4gICAgICAgIHZhciBsb2FkZXIgPSBtdy5pY29uTG9hZGVyKCk7XG4gICAgICAgIHZhciBkZWZhdWx0cyA9IHtcbiAgICAgICAgICAgIGljb25zUGVyUGFnZTogNDAsXG4gICAgICAgICAgICBpY29uT3B0aW9uczoge1xuICAgICAgICAgICAgICAgIHNpemU6IHRydWUsXG4gICAgICAgICAgICAgICAgY29sb3I6IHRydWUsXG4gICAgICAgICAgICAgICAgcmVzZXQ6IGZhbHNlXG4gICAgICAgICAgICB9XG4gICAgICAgIH07XG5cblxuICAgICAgICB0aGlzLnNldHRpbmdzID0gbXcub2JqZWN0LmV4dGVuZCh0cnVlLCB7fSwgZGVmYXVsdHMsIG9wdGlvbnMpO1xuICAgICAgICB2YXIgc2NvcGUgPSB0aGlzO1xuICAgICAgICB2YXIgdGFiQWNjb3JkaW9uQnVpbGRlciA9IGZ1bmN0aW9uIChpdGVtcykge1xuICAgICAgICAgICAgdmFyIHJlcyA9IHtyb290OiBtdy5lbGVtZW50KCc8ZGl2IGNsYXNzPVwibXctdGFiLWFjY29yZGlvblwiIGRhdGEtb3B0aW9ucz1cInRhYnNTaXplOiBtZWRpdW1cIiAvPicpLCBpdGVtczogW119O1xuICAgICAgICAgICAgaXRlbXMuZm9yRWFjaChmdW5jdGlvbiAoaXRlbSl7XG4gICAgICAgICAgICAgICAgdmFyIGVsID0gbXcuZWxlbWVudCgnPGRpdiBjbGFzcz1cIm13LWFjY29yZGlvbi1pdGVtXCIgLz4nKTtcbiAgICAgICAgICAgICAgICB2YXIgY29udGVudCA9IG13LmVsZW1lbnQoJzxkaXYgY2xhc3M9XCJtdy1hY2NvcmRpb24tY29udGVudCBtdy11aS1ib3ggbXctdWktYm94LWNvbnRlbnRcIj4nICsoaXRlbS5jb250ZW50IHx8ICcnKSArJzwvZGl2PicpO1xuICAgICAgICAgICAgICAgIHZhciB0aXRsZSA9IG13LmVsZW1lbnQoJzxkaXYgY2xhc3M9XCJtdy11aS1ib3gtaGVhZGVyIG13LWFjY29yZGlvbi10aXRsZVwiPicgKyBpdGVtLnRpdGxlICsnPC9kaXY+Jyk7XG4gICAgICAgICAgICAgICAgZWwuYXBwZW5kKHRpdGxlKTtcbiAgICAgICAgICAgICAgICBlbC5hcHBlbmQoY29udGVudCk7XG5cbiAgICAgICAgICAgICAgICByZXMucm9vdC5hcHBlbmQoZWwpO1xuICAgICAgICAgICAgICAgIHJlcy5pdGVtcy5wdXNoKHtcbiAgICAgICAgICAgICAgICAgICAgdGl0bGU6IHRpdGxlLFxuICAgICAgICAgICAgICAgICAgICBjb250ZW50OiBjb250ZW50LFxuICAgICAgICAgICAgICAgICAgICByb290OiBlbCxcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbiAoKXtcbiAgICAgICAgICAgICAgICBpZihtdy5jb21wb25lbnRzKSB7XG4gICAgICAgICAgICAgICAgICAgIG13LmNvbXBvbmVudHMuX2luaXQoKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9LCAxMCk7XG4gICAgICAgICAgICByZXR1cm4gcmVzO1xuICAgICAgICB9O1xuXG4gICAgICAgIHZhciBjcmVhdGVVSSA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHZhciByb290ID0gbXcuZWxlbWVudCh7XG4gICAgICAgICAgICAgICAgcHJvcHM6IHsgY2xhc3NOYW1lOiAnbXctaWNvbi1zZWxlY3Rvci1yb290JyB9XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIHZhciBpY29uc0Jsb2NrSG9sZGVyLCB0YWJzLCBvcHRpb25zSG9sZGVyLCBpY29uc0hvbGRlcjtcbiAgICAgICAgICAgIGlmKHNjb3BlLnNldHRpbmdzLmljb25PcHRpb25zKSB7XG4gICAgICAgICAgICAgICAgdGFicyA9IHRhYkFjY29yZGlvbkJ1aWxkZXIoW1xuICAgICAgICAgICAgICAgICAgICB7dGl0bGU6ICdJY29ucyd9LFxuICAgICAgICAgICAgICAgICAgICB7dGl0bGU6ICdPcHRpb25zJ30sXG4gICAgICAgICAgICAgICAgXSk7XG4gICAgICAgICAgICAgICAgaWNvbnNCbG9ja0hvbGRlciA9IHRhYnMuaXRlbXNbMF0uY29udGVudDtcbiAgICAgICAgICAgICAgICBvcHRpb25zSG9sZGVyID0gdGFicy5pdGVtc1sxXS5jb250ZW50O1xuICAgICAgICAgICAgICAgIHJvb3QuYXBwZW5kKHRhYnMucm9vdClcbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgaWNvbnNCbG9ja0hvbGRlciA9IG13LmVsZW1lbnQoKTtcbiAgICAgICAgICAgICAgICByb290LmFwcGVuZChpY29uc0Jsb2NrSG9sZGVyKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGljb25zSG9sZGVyID0gbXcuZWxlbWVudCgpLmFkZENsYXNzKCdtdy1pY29uLXBpY2tlci1pY29ucy1ob2xkZXInKTtcbiAgICAgICAgICAgIGljb25zQmxvY2tIb2xkZXIuYXBwZW5kKGljb25zSG9sZGVyKTtcbiAgICAgICAgICAgIHJldHVybiB7XG4gICAgICAgICAgICAgICAgcm9vdDogcm9vdCxcbiAgICAgICAgICAgICAgICB0YWJzOiB0YWJzLFxuICAgICAgICAgICAgICAgIGljb25zQmxvY2tIb2xkZXI6IGljb25zQmxvY2tIb2xkZXIsXG4gICAgICAgICAgICAgICAgaWNvbnNIb2xkZXI6IGljb25zSG9sZGVyLFxuICAgICAgICAgICAgICAgIG9wdGlvbnNIb2xkZXI6IG9wdGlvbnNIb2xkZXJcbiAgICAgICAgICAgIH07XG4gICAgICAgIH07XG5cblxuICAgICAgICB2YXIgX2UgPSB7fTtcblxuICAgICAgICB0aGlzLm9uID0gZnVuY3Rpb24gKGUsIGYpIHsgX2VbZV0gPyBfZVtlXS5wdXNoKGYpIDogKF9lW2VdID0gW2ZdKSB9O1xuICAgICAgICB0aGlzLmRpc3BhdGNoID0gZnVuY3Rpb24gKGUsIGYpIHsgX2VbZV0gPyBfZVtlXS5mb3JFYWNoKGZ1bmN0aW9uIChjKXsgYy5jYWxsKHRoaXMsIGYpOyB9KSA6ICcnOyB9O1xuXG4gICAgICAgIHZhciBhY3Rpb25Ob2RlcyA9IHt9O1xuXG4gICAgICAgIHZhciBjcmVhdGVPcHRpb25zID0gZnVuY3Rpb24gKGhvbGRlcikge1xuXG4gICAgICAgICAgICBpZihob2xkZXIgJiYgc2NvcGUuc2V0dGluZ3MuaWNvbk9wdGlvbnMpIHtcbiAgICAgICAgICAgICAgICBpZihzY29wZS5zZXR0aW5ncy5pY29uT3B0aW9ucy5zaXplKSB7XG4gICAgICAgICAgICAgICAgICAgIHZhciBzaXplZWwgPSBtdy5lbGVtZW50KCc8ZGl2IGNsYXNzPVwibXdpY29ubGlzdC1zZXR0aW5ncy1zZWN0aW9uLWJsb2NrLWl0ZW1cIj48bGFiZWwgY2xhc3M9XCJtdy11aS1sYWJlbFwiPkljb24gc2l6ZTwvbGFiZWw+PC9kaXY+Jyk7XG4gICAgICAgICAgICAgICAgICAgIHZhciBzaXplaW5wdXQgPSBtdy5lbGVtZW50KCc8aW5wdXQgdHlwZT1cInJhbmdlXCIgbWluPVwiOFwiIG1heD1cIjIwMFwiPicpO1xuICAgICAgICAgICAgICAgICAgICBhY3Rpb25Ob2Rlcy5zaXplID0gc2l6ZWlucHV0O1xuICAgICAgICAgICAgICAgICAgICBzaXplaW5wdXQub24oJ2lucHV0JywgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgc2NvcGUuZGlzcGF0Y2goJ3NpemVDaGFuZ2UnLCBzaXplaW5wdXQuZ2V0KDApLnZhbHVlKTtcbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgICAgIHNpemVlbC5hcHBlbmQoc2l6ZWlucHV0KTtcbiAgICAgICAgICAgICAgICAgICAgaG9sZGVyLmFwcGVuZChzaXplZWwpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBpZihzY29wZS5zZXR0aW5ncy5pY29uT3B0aW9ucy5jb2xvcikge1xuICAgICAgICAgICAgICAgICAgICBjZWwgPSBtdy5lbGVtZW50KCc8ZGl2IGNsYXNzPVwibXdpY29ubGlzdC1zZXR0aW5ncy1zZWN0aW9uLWJsb2NrLWl0ZW1cIj48bGFiZWwgY2xhc3M9XCJtdy11aS1sYWJlbFwiPkNvbG9yPC9sYWJlbD48L2Rpdj4nKTtcbiAgICAgICAgICAgICAgICAgICAgY2lucHV0ID0gbXcuZWxlbWVudCgnPGlucHV0IHR5cGU9XCJjb2xvclwiPicpO1xuICAgICAgICAgICAgICAgICAgICBhY3Rpb25Ob2Rlcy5jb2xvciA9IGNpbnB1dDtcbiAgICAgICAgICAgICAgICAgICAgY2lucHV0Lm9uKCdpbnB1dCcsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHNjb3BlLmRpc3BhdGNoKCdjb2xvckNoYW5nZScsIGNpbnB1dC5nZXQoMCkudmFsdWUpO1xuICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICAgICAgY2VsLmFwcGVuZChjaW5wdXQpO1xuICAgICAgICAgICAgICAgICAgICBob2xkZXIuYXBwZW5kKGNlbCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGlmKHNjb3BlLnNldHRpbmdzLmljb25PcHRpb25zLnJlc2V0KSB7XG4gICAgICAgICAgICAgICAgICAgIHZhciByZWwgPSBtdy5lbGVtZW50KCc8ZGl2IGNsYXNzPVwibXdpY29ubGlzdC1zZXR0aW5ncy1zZWN0aW9uLWJsb2NrLWl0ZW1cIj4gPC9kaXY+Jyk7XG4gICAgICAgICAgICAgICAgICAgIHZhciByaW5wdXQgPSBtdy5lbGVtZW50KCc8aW5wdXQgdHlwZT1cImJ1dHRvblwiIGNsYXNzPVwibXctdWktYnRuIG13LXVpLWJ0bi1tZWRpdW1cIiB2YWx1ZT1cIlJlc2V0IG9wdGlvbnNcIj4nKTtcbiAgICAgICAgICAgICAgICAgICAgcmlucHV0Lm9uKCdjbGljaycsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHNjb3BlLmRpc3BhdGNoKCdyZXNldCcsIHJpbnB1dC5nZXQoMCkudmFsdWUpO1xuICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICAgICAgcmVsLmFwcGVuZChyaW5wdXQpO1xuICAgICAgICAgICAgICAgICAgICBob2xkZXIuYXBwZW5kKHJlbCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICB9O1xuXG4gICAgICAgIHZhciBfcHJlcGFyZUljb25zTGlzdHMgPSBmdW5jdGlvbiAoYykge1xuICAgICAgICAgICAgdmFyIHNldHMgPSBsb2FkZXIuc3RvcmFnZSgpO1xuICAgICAgICAgICAgdmFyIGFsbCA9IHNldHMubGVuZ3RoO1xuICAgICAgICAgICAgdmFyIGkgPSAwO1xuICAgICAgICAgICAgc2V0cy5mb3JFYWNoKGZ1bmN0aW9uIChzZXQpe1xuICAgICAgICAgICAgICAgICBpZiAoIXNldC5faWNvbnNMaXN0cykge1xuICAgICAgICAgICAgICAgICAgICAgKGZ1bmN0aW9uIChhc2V0KXtcbiAgICAgICAgICAgICAgICAgICAgICAgICBhc2V0Lmljb25zKCkudGhlbihmdW5jdGlvbiAoZGF0YSl7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgIGFzZXQuX2ljb25zTGlzdHMgPSBkYXRhO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICBpKys7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgIGlmKGkgPT09IGFsbCkgYy5jYWxsKHNldHMsIHNldHMpO1xuICAgICAgICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgICAgICAgfSkoc2V0KTtcbiAgICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgIGkrKztcbiAgICAgICAgICAgICAgICAgICAgIGlmKGkgPT09IGFsbCkgYy5jYWxsKHNldHMsIHNldHMpO1xuICAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9O1xuXG5cbiAgICAgICAgdmFyIGNyZWF0ZVBhZ2luZyA9IGZ1bmN0aW9uKGxlbmd0aCwgcGFnZSl7XG4gICAgICAgICAgICBwYWdlID0gcGFnZSB8fCAxO1xuICAgICAgICAgICAgdmFyIG1heCA9IDk5OTtcbiAgICAgICAgICAgIHZhciBwYWdlcyA9IE1hdGgubWluKE1hdGguY2VpbChsZW5ndGgvc2NvcGUuc2V0dGluZ3MuaWNvbnNQZXJQYWdlKSwgbWF4KTtcbiAgICAgICAgICAgIHZhciBwYWdpbmcgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgICAgIHBhZ2luZy5jbGFzc05hbWUgPSAnbXctcGFnaW5nIG13LXBhZ2luZy1zbWFsbCBtdy1pY29uLXNlbGVjdG9yLXBhZ2luZyc7XG4gICAgICAgICAgICBpZihzY29wZS5zZXR0aW5ncy5pY29uc1BlclBhZ2UgPj0gbGVuZ3RoICkge1xuICAgICAgICAgICAgICAgIHJldHVybiBwYWdpbmc7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICB2YXIgYWN0aXZlID0gZmFsc2U7XG4gICAgICAgICAgICBmb3IgKCB2YXIgaSA9IDE7IGkgPD0gcGFnZXM7IGkrKykge1xuICAgICAgICAgICAgICAgIHZhciBlbCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2EnKTtcbiAgICAgICAgICAgICAgICBlbC5pbm5lckhUTUwgPSBpO1xuICAgICAgICAgICAgICAgIGVsLl92YWx1ZSA9IGk7XG4gICAgICAgICAgICAgICAgaWYocGFnZSA9PT0gaSkge1xuICAgICAgICAgICAgICAgICAgICBlbC5jbGFzc05hbWUgPSAnYWN0aXZlJztcbiAgICAgICAgICAgICAgICAgICAgYWN0aXZlID0gaTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgZWwub25jbGljayA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgY29tUmVuZGVyKHtwYWdlOiB0aGlzLl92YWx1ZSB9KTtcbiAgICAgICAgICAgICAgICB9O1xuICAgICAgICAgICAgICAgIHBhZ2luZy5hcHBlbmRDaGlsZChlbCk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICB2YXIgYWxsID0gcGFnaW5nLnF1ZXJ5U2VsZWN0b3JBbGwoJ2EnKTtcbiAgICAgICAgICAgIGZvciAodmFyIGkgPSBhY3RpdmUgLSAzOyBpIDwgYWN0aXZlICsgMjsgaSsrKXtcbiAgICAgICAgICAgICAgICBpZihhbGxbaV0pIHtcbiAgICAgICAgICAgICAgICAgICAgYWxsW2ldLmNsYXNzTmFtZSArPSAnIG13LXBhZ2luZy12aXNpYmxlLXJhbmdlJztcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG5cblxuICAgICAgICAgICAgaWYoYWN0aXZlIDwgcGFnZXMpIHtcbiAgICAgICAgICAgICAgICB2YXIgbmV4dCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2EnKTtcbiAgICAgICAgICAgICAgICBuZXh0LmlubmVySFRNTCA9ICcmcmFxdW87JztcbiAgICAgICAgICAgICAgICBuZXh0Ll92YWx1ZSA9IGFjdGl2ZSsxO1xuICAgICAgICAgICAgICAgIG5leHQuY2xhc3NOYW1lID0gJ213LXBhZ2luZy12aXNpYmxlLXJhbmdlIG13LXBhZ2luZy1uZXh0JztcbiAgICAgICAgICAgICAgICBuZXh0LmlubmVySFRNTCA9ICcmcmFxdW87JztcbiAgICAgICAgICAgICAgICAkKHBhZ2luZykuYXBwZW5kKG5leHQpO1xuICAgICAgICAgICAgICAgIG5leHQub25jbGljayA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgIGNvbVJlbmRlcih7cGFnZTogdGhpcy5fdmFsdWUgfSk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgaWYoYWN0aXZlID4gMSkge1xuICAgICAgICAgICAgICAgIHZhciBwcmV2ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnYScpO1xuICAgICAgICAgICAgICAgIHByZXYuY2xhc3NOYW1lID0gJ213LXBhZ2luZy12aXNpYmxlLXJhbmdlIG13LXBhZ2luZy1wcmV2JztcbiAgICAgICAgICAgICAgICBwcmV2LmlubmVySFRNTCA9ICcmbGFxdW87JztcbiAgICAgICAgICAgICAgICBwcmV2Ll92YWx1ZSA9IGFjdGl2ZS0xO1xuICAgICAgICAgICAgICAgICQocGFnaW5nKS5wcmVwZW5kKHByZXYpO1xuICAgICAgICAgICAgICAgIHByZXYub25jbGljayA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgIGNvbVJlbmRlcih7cGFnZTogdGhpcy5fdmFsdWUgfSk7XG4gICAgICAgICAgICAgICAgfTtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgcmV0dXJuIHBhZ2luZztcbiAgICAgICAgfTtcblxuICAgICAgICB2YXIgc2VhcmNoRmllbGQgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB2YXIgdGltZSA9IG51bGw7XG4gICAgICAgICAgICBzY29wZS5zZWFyY2hGaWVsZCA9ICBtdy5lbGVtZW50KHtcbiAgICAgICAgICAgICAgICB0YWc6ICdpbnB1dCcsXG4gICAgICAgICAgICAgICAgcHJvcHM6IHtcbiAgICAgICAgICAgICAgICAgICAgY2xhc3NOYW1lOiAnbXctdWktc2VhcmNoZmllbGQgdzEwMCcsXG4gICAgICAgICAgICAgICAgICAgIG9uaW5wdXQ6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGNsZWFyVGltZW91dCh0aW1lKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIHRpbWUgPSBzZXRUaW1lb3V0KGZ1bmN0aW9uICgpe1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNvbVJlbmRlcigpO1xuICAgICAgICAgICAgICAgICAgICAgICAgfSwgMTIzKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICByZXR1cm4gc2NvcGUuc2VhcmNoRmllbGQ7XG4gICAgICAgIH07XG5cbiAgICAgICAgdmFyIGNvbVJlbmRlciA9IGZ1bmN0aW9uIChvcHRpb25zKSB7XG4gICAgICAgICAgICBvcHRpb25zID0gb3B0aW9ucyB8fCB7fTtcbiAgICAgICAgICAgIG9wdGlvbnMgPSBtdy5vYmplY3QuZXh0ZW5kKHt9LCB7XG4gICAgICAgICAgICAgICAgc2V0OiBzY29wZS5zZWxlY3RGaWVsZC5nZXQoMCkub3B0aW9uc1tzY29wZS5zZWxlY3RGaWVsZC5nZXQoMCkuc2VsZWN0ZWRJbmRleF0uX3ZhbHVlLFxuICAgICAgICAgICAgICAgIHRlcm06IHNjb3BlLnNlYXJjaEZpZWxkLmdldCgwKS52YWx1ZVxuICAgICAgICAgICAgfSwgb3B0aW9ucyk7XG4gICAgICAgICAgICBzY29wZS51aS5pY29uc0hvbGRlci5lbXB0eSgpLmFwcGVuZChyZW5kZXJTZWFyY2hSZXN1bHRzKG9wdGlvbnMpKTtcbiAgICAgICAgfTtcblxuICAgICAgICB2YXIgc2VhcmNoU2VsZWN0b3IgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB2YXIgc2VsID0gbXcuZWxlbWVudCgnPHNlbGVjdCBjbGFzcz1cIm13LXVpLWZpZWxkIHcxMDBcIiAvPicpO1xuICAgICAgICAgICAgc2NvcGUuc2VsZWN0RmllbGQgPSBzZWw7XG4gICAgICAgICAgICBsb2FkZXIuc3RvcmFnZSgpLmZvckVhY2goZnVuY3Rpb24gKGl0ZW0pIHtcbiAgICAgICAgICAgICAgICB2YXIgZWwgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdvcHRpb24nKTtcbiAgICAgICAgICAgICAgICBlbC5fdmFsdWUgPSBpdGVtO1xuICAgICAgICAgICAgICAgIGVsLmlubmVySFRNTCA9IGl0ZW0ubmFtZTtcbiAgICAgICAgICAgICAgICBzZWwuYXBwZW5kKGVsKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgc2VsLm9uKCdjaGFuZ2UnLCBmdW5jdGlvbiAoKXtcbiAgICAgICAgICAgICAgICBjb21SZW5kZXIoKVxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICByZXR1cm4gc2VsO1xuICAgICAgICB9O1xuXG4gICAgICAgIHZhciBzZWFyY2ggPSBmdW5jdGlvbiAoY29uZikge1xuICAgICAgICAgICAgY29uZiA9IGNvbmYgfHwge307XG4gICAgICAgICAgICBjb25mLnNldCA9IGNvbmYuc2V0IHx8ICBsb2FkZXIuc3RvcmFnZSgpWzBdO1xuICAgICAgICAgICAgY29uZi5wYWdlID0gY29uZi5wYWdlIHx8IDE7XG4gICAgICAgICAgICBjb25mLnRlcm0gPSAoY29uZi50ZXJtIHx8ICcnKS50cmltKCkudG9Mb3dlckNhc2UoKTtcblxuICAgICAgICAgICAgdmFyIGFsbCA9IGNvbmYuc2V0Ll9pY29uc0xpc3RzLmZpbHRlcihmdW5jdGlvbiAoZil7IHJldHVybiBmLnRvTG93ZXJDYXNlKCkuaW5kZXhPZihjb25mLnRlcm0pICE9PSAtMTsgfSk7XG5cbiAgICAgICAgICAgIHZhciBvZmYgPSBzY29wZS5zZXR0aW5ncy5pY29uc1BlclBhZ2UgKiAoY29uZi5wYWdlIC0gMSk7XG4gICAgICAgICAgICB2YXIgdG8gPSBvZmYgKyBNYXRoLm1pbihhbGwubGVuZ3RoIC0gb2ZmLCBzY29wZS5zZXR0aW5ncy5pY29uc1BlclBhZ2UpO1xuXG4gICAgICAgICAgICByZXR1cm4gbXcub2JqZWN0LmV4dGVuZCh7fSwgY29uZiwge1xuICAgICAgICAgICAgICAgIGRhdGE6IGFsbC5zbGljZShvZmYsIHRvKSxcbiAgICAgICAgICAgICAgICBhbGw6IGFsbCxcbiAgICAgICAgICAgICAgICBvZmY6IG9mZlxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAvKmZvciAoIHZhciBpID0gb2ZmOyBpIDwgdG87IGkrKyApIHtcblxuICAgICAgICAgICAgfSovXG4gICAgICAgIH07XG5cbiAgICAgICAgdmFyIHJlbmRlclNlYXJjaFJlc3VsdHMgPSBmdW5jdGlvbiAoY29uZikge1xuICAgICAgICAgICAgdmFyIHJlcyA9IHNlYXJjaChjb25mKTtcbiAgICAgICAgICAgIHZhciBwZyA9IGNyZWF0ZVBhZ2luZyhyZXMuYWxsLmxlbmd0aCwgcmVzLnBhZ2UpO1xuICAgICAgICAgICAgdmFyIHJvb3QgPSBtdy5lbGVtZW50KCk7XG4gICAgICAgICAgICByZXMuZGF0YS5mb3JFYWNoKGZ1bmN0aW9uIChpY29uSXRlbSl7XG4gICAgICAgICAgICAgICAgdmFyIGljb24gPSBtdy5lbGVtZW50KHtcbiAgICAgICAgICAgICAgICAgICAgdGFnOiAnc3BhbicsXG4gICAgICAgICAgICAgICAgICAgIHByb3BzOiB7XG4gICAgICAgICAgICAgICAgICAgICAgICBjbGFzc05hbWU6ICdtd2ljb25saXN0LWljb24nLFxuICAgICAgICAgICAgICAgICAgICAgICAgb25jbGljazogZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBzY29wZS5kaXNwYXRjaCgnc2VsZWN0Jywge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBpY29uOiBpY29uSXRlbSxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgcmVuZGVyZXI6IHJlcy5zZXQucmVuZGVyLFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICByZW5kZXI6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHZhciBzZXRzID0gbG9hZGVyLnN0b3JhZ2UoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHNldHMuZm9yRWFjaChmdW5jdGlvbiAoc2V0KSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgc2V0LnJlbW92ZShzY29wZS50YXJnZXQpXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB9KVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHJlcy5zZXQucmVuZGVyKGljb25JdGVtLCBzY29wZS50YXJnZXQpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICByb290LmFwcGVuZChpY29uKTtcbiAgICAgICAgICAgICAgICByZXMuc2V0LnJlbmRlcihpY29uSXRlbSwgaWNvbi5nZXQoMCkpO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICByb290LmFwcGVuZChwZylcbiAgICAgICAgICAgIHJldHVybiByb290O1xuICAgICAgICB9O1xuXG4gICAgICAgIHZhciBjcmVhdGVJY29uc0Jsb2NrID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgbXcuc3Bpbm5lcih7ZWxlbWVudDogc2NvcGUudWkuaWNvbnNIb2xkZXIuZ2V0KDApLCBzaXplOiAzMH0pLnNob3coKTtcbiAgICAgICAgICAgIF9wcmVwYXJlSWNvbnNMaXN0cyhmdW5jdGlvbiAoKXtcbiAgICAgICAgICAgICAgICBjb21SZW5kZXIoKTtcbiAgICAgICAgICAgICAgICBtdy5zcGlubmVyKHtlbGVtZW50OiBzY29wZS51aS5pY29uc0hvbGRlci5nZXQoMCl9KS5oaWRlKCk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLmNyZWF0ZSA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHRoaXMudWkgPSBjcmVhdGVVSSgpO1xuICAgICAgICAgICAgY3JlYXRlT3B0aW9ucyh0aGlzLnVpLm9wdGlvbnNIb2xkZXIpO1xuICAgICAgICAgICAgdGhpcy51aS5pY29uc0Jsb2NrSG9sZGVyLnByZXBlbmQoc2VhcmNoRmllbGQoKSk7XG5cbiAgICAgICAgICAgIHRoaXMudWkuaWNvbnNCbG9ja0hvbGRlci5wcmVwZW5kKHNlYXJjaFNlbGVjdG9yKCkpO1xuICAgICAgICAgICAgY3JlYXRlSWNvbnNCbG9jaygpO1xuXG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5nZXQgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICByZXR1cm4gdGhpcy51aS5yb290LmdldCgwKTtcbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLmRpYWxvZyA9IGZ1bmN0aW9uIChtZXRob2QpIHtcbiAgICAgICAgICAgIGlmKG1ldGhvZCA9PT0gJ2hpZGUnKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5fZGlhbG9nLmhpZGUoKTtcbiAgICAgICAgICAgICAgICByZXR1cm47XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBpZighdGhpcy5fZGlhbG9nKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5fZGlhbG9nID0gbXcudG9wKCkuZGlhbG9nKHtjb250ZW50OiB0aGlzLmdldCgpLCB0aXRsZTogJ1NlbGVjdCBpY29uJywgY2xvc2VCdXR0b25BY3Rpb246ICdoaWRlJywgd2lkdGg6IDQ1MH0pO1xuICAgICAgICAgICAgICAgIG13LmNvbXBvbmVudHMuX2luaXQoKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHRoaXMuX2RpYWxvZy5zaG93KCk7XG4gICAgICAgICAgICByZXR1cm4gdGhpcy5fZGlhbG9nO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuZGVzdHJveSA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHRoaXMuZ2V0KCkucmVtb3ZlKClcbiAgICAgICAgICAgIGlmKHRoaXMuX2RpYWxvZykge1xuICAgICAgICAgICAgICAgIHRoaXMuX2RpYWxvZy5yZW1vdmUoKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGlmKHRoaXMuX3Rvb2x0aXApIHtcbiAgICAgICAgICAgICAgICB0aGlzLl90b29sdGlwLnJlbW92ZSgpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMudGFyZ2V0ID0gbnVsbDtcblxuICAgICAgICB0aGlzLnRvb2x0aXAgPSBmdW5jdGlvbiAodGFyZ2V0KSB7XG4gICAgICAgICAgICB0aGlzLnRhcmdldCA9IHRhcmdldDtcbiAgICAgICAgICAgIGlmKHRhcmdldCA9PT0gJ2hpZGUnICYmIHRoaXMuX3Rvb2x0aXApIHtcbiAgICAgICAgICAgICAgICB0aGlzLl90b29sdGlwLnN0eWxlLmRpc3BsYXkgPSAnbm9uZSc7XG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgIGlmICghdGhpcy5fdG9vbHRpcCkge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLl90b29sdGlwID0gbXcudG9vbHRpcCh7XG4gICAgICAgICAgICAgICAgICAgICAgICBjb250ZW50OiB0aGlzLmdldCgpLFxuICAgICAgICAgICAgICAgICAgICAgICAgZWxlbWVudDogdGFyZ2V0LFxuICAgICAgICAgICAgICAgICAgICAgICAgcG9zaXRpb246ICdib3R0b20tY2VudGVyJyxcbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgbXcudG9vbHMudG9vbHRpcC5zZXRQb3NpdGlvbih0aGlzLl90b29sdGlwLCB0YXJnZXQsICdib3R0b20tY2VudGVyJyk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIHRoaXMuX3Rvb2x0aXAuc3R5bGUuZGlzcGxheSA9ICdibG9jayc7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBtdy5jb21wb25lbnRzLl9pbml0KCk7XG4gICAgICAgICAgICByZXR1cm4gdGhpcy5fdG9vbHRpcDtcbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLmluaXQgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB0aGlzLmNyZWF0ZSgpO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMucHJvbWlzZSA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHJldHVybiBuZXcgUHJvbWlzZShmdW5jdGlvbiAocmVzb2x2ZSl7XG4gICAgICAgICAgICAgICBzY29wZS5vbignc2VsZWN0JywgZnVuY3Rpb24gKGRhdGEpIHtcbiAgICAgICAgICAgICAgICAgICByZXNvbHZlKGRhdGEpO1xuICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLmluaXQoKTtcblxuICAgIH07XG5cblxuICAgIG13Lmljb25QaWNrZXIgPSBmdW5jdGlvbiAob3B0aW9ucykge1xuICAgICAgICByZXR1cm4gbmV3IEljb25QaWNrZXIob3B0aW9ucyk7XG4gICAgfTtcblxufSkoKTtcblxuXG5cblxuXG5cbiIsIi8qXHJcbiAgICBvcHRpb25zLmRhdGEgPSBbXHJcbiAgICAgICAge1xyXG4gICAgICAgICAgICB0aXRsZTogc3RyaW5nLFxyXG4gICAgICAgICAgICB2YWx1ZTogYW55LFxyXG4gICAgICAgICAgICBpY29uPzogc3RyaW5nLFxyXG4gICAgICAgICAgICBzZWxlY3RlZD86IGJvb2xlYW5cclxuICAgICAgICB9XHJcbiAgICBdXHJcblxyXG4gKi9cclxuXHJcblxyXG5tdy5TZWxlY3QgPSBmdW5jdGlvbihvcHRpb25zKSB7XHJcbiAgICB2YXIgZGVmYXVsdHMgPSB7XHJcbiAgICAgICAgZGF0YTogW10sXHJcbiAgICAgICAgc2tpbjogJ2RlZmF1bHQnLFxyXG4gICAgICAgIG11bHRpcGxlOiBmYWxzZSxcclxuICAgICAgICBhdXRvY29tcGxldGU6IGZhbHNlLFxyXG4gICAgICAgIG1vYmlsZUF1dG9jb21wbGV0ZTogZmFsc2UsXHJcbiAgICAgICAgc2hvd1NlbGVjdGVkOiBmYWxzZSxcclxuICAgICAgICBkb2N1bWVudDogZG9jdW1lbnQsXHJcbiAgICAgICAgc2l6ZTogJ25vcm1hbCcsXHJcbiAgICAgICAgY29sb3I6ICdkZWZhdWx0JyxcclxuICAgICAgICBkcm9wTW9kZTogJ292ZXInLCAvLyAnb3ZlcicgfCAncHVzaCdcclxuICAgICAgICBwbGFjZWhvbGRlcjogbXcubGFuZygnU2VsZWN0JyksXHJcbiAgICAgICAgdGFnczogZmFsc2UsIC8vIG9ubHkgaWYgbXVsdGlwbGUgaXMgc2V0IHRvIHRydWUsXHJcbiAgICAgICAgYWpheE1vZGU6IHtcclxuICAgICAgICAgICAgcGFnaW5hdGlvblBhcmFtOiAncGFnZScsXHJcbiAgICAgICAgICAgIHNlYXJjaFBhcmFtOiAna2V5d29yZCcsXHJcbiAgICAgICAgICAgIGVuZHBvaW50OiBudWxsLFxyXG4gICAgICAgICAgICBtZXRob2Q6ICdnZXQnXHJcbiAgICAgICAgfVxyXG4gICAgfTtcclxuICAgIG9wdGlvbnMgID0gb3B0aW9ucyB8fCB7fTtcclxuICAgIHRoaXMuc2V0dGluZ3MgPSAkLmV4dGVuZCh7fSwgZGVmYXVsdHMsIG9wdGlvbnMpO1xyXG5cclxuXHJcblxyXG4gICAgaWYodGhpcy5zZXR0aW5ncy5hamF4TW9kZSAmJiAhdGhpcy5zZXR0aW5ncy5hamF4TW9kZS5lbmRwb2ludCl7XHJcbiAgICAgICAgdGhpcy5zZXR0aW5ncy5hamF4TW9kZSA9IGZhbHNlO1xyXG4gICAgfVxyXG5cclxuICAgIHRoaXMuJGVsZW1lbnQgPSAkKHRoaXMuc2V0dGluZ3MuZWxlbWVudCkuZXEoMCk7XHJcbiAgICB0aGlzLmVsZW1lbnQgPSB0aGlzLiRlbGVtZW50WzBdO1xyXG4gICAgaWYoIXRoaXMuZWxlbWVudCkge1xyXG4gICAgICAgIHJldHVybjtcclxuICAgIH1cclxuXHJcbiAgICB0aGlzLl9yb290SW5wdXRNb2RlID0gdGhpcy5lbGVtZW50Lm5vZGVOYW1lID09PSAnSU5QVVQnO1xyXG5cclxuICAgIGlmKHRoaXMuZWxlbWVudC5fbXdTZWxlY3QpIHtcclxuICAgICAgICByZXR1cm4gdGhpcy5lbGVtZW50Ll9td1NlbGVjdDtcclxuICAgIH1cclxuXHJcbiAgICB2YXIgc2NvcGUgPSB0aGlzO1xyXG4gICAgdGhpcy5kb2N1bWVudCA9IHRoaXMuc2V0dGluZ3MuZG9jdW1lbnQ7XHJcblxyXG4gICAgdGhpcy5fdmFsdWUgPSBudWxsO1xyXG5cclxuXHJcbiAgICB0aGlzLmdldExhYmVsID0gZnVuY3Rpb24oaXRlbSkge1xyXG4gICAgICAgIHJldHVybiBpdGVtLnRpdGxlIHx8IGl0ZW0ubmFtZSB8fCBpdGVtLmxhYmVsIHx8IGl0ZW0udmFsdWU7XHJcbiAgICB9O1xyXG5cclxuICAgIHRoaXMuX2xvYWRpbmcgPSBmYWxzZTtcclxuXHJcbiAgICB0aGlzLl9wYWdlID0gMTtcclxuXHJcbiAgICB0aGlzLm5leHRQYWdlID0gZnVuY3Rpb24gKCkge1xyXG4gICAgICAgIHRoaXMuX3BhZ2UrKztcclxuICAgIH07XHJcblxyXG4gICAgdGhpcy5wYWdlID0gZnVuY3Rpb24gKHN0YXRlKSB7XHJcbiAgICAgICAgaWYgKHR5cGVvZiBzdGF0ZSA9PT0gJ3VuZGVmaW5lZCcpIHtcclxuICAgICAgICAgICAgcmV0dXJuIHRoaXMuX3BhZ2U7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIHRoaXMuX3BhZ2UgPSBzdGF0ZTtcclxuICAgIH07XHJcblxyXG4gICAgdGhpcy5sb2FkaW5nID0gZnVuY3Rpb24gKHN0YXRlKSB7XHJcbiAgICAgICAgaWYgKHR5cGVvZiBzdGF0ZSA9PT0gJ3VuZGVmaW5lZCcpIHtcclxuICAgICAgICAgICAgcmV0dXJuIHRoaXMuX3N0YXRlO1xyXG4gICAgICAgIH1cclxuICAgICAgICB0aGlzLl9zdGF0ZSA9IHN0YXRlO1xyXG4gICAgICAgIG13LnRvb2xzLmNsYXNzTmFtZXNwYWNlRGVsZXRlKHRoaXMucm9vdCwgJ213LXNlbGVjdC1sb2FkaW5nLScpO1xyXG4gICAgICAgIG13LnRvb2xzLmFkZENsYXNzKHRoaXMucm9vdCwgJ213LXNlbGVjdC1sb2FkaW5nLScgKyBzdGF0ZSk7XHJcbiAgICB9O1xyXG5cclxuICAgIHRoaXMueGhyID0gbnVsbDtcclxuXHJcbiAgICB0aGlzLmFqYXhGaWx0ZXIgPSBmdW5jdGlvbiAodmFsLCBjYWxsYmFjaykge1xyXG4gICAgICAgIGlmICh0aGlzLnhocikge1xyXG4gICAgICAgICAgICB0aGlzLnhoci5hYm9ydCgpO1xyXG4gICAgICAgIH1cclxuICAgICAgICB2YWwgPSAodmFsIHx8ICcnKS50cmltKCkudG9Mb3dlckNhc2UoKTtcclxuICAgICAgICB2YXIgcGFyYW1zID0geyB9O1xyXG4gICAgICAgIHBhcmFtc1t0aGlzLnNldHRpbmdzLmFqYXhNb2RlLnNlYXJjaFBhcmFtXSA9IHZhbDtcclxuICAgICAgICBwYXJhbXMgPSAodGhpcy5zZXR0aW5ncy5hamF4TW9kZS5lbmRwb2ludC5pbmRleE9mKCc/JykgPT09IC0xID8gJz8nIDogJyYnICkgKyAkLnBhcmFtKHBhcmFtcyk7XHJcbiAgICAgICAgdGhpcy54aHIgPSAkW3RoaXMuc2V0dGluZ3MuYWpheE1vZGUubWV0aG9kXSh0aGlzLnNldHRpbmdzLmFqYXhNb2RlLmVuZHBvaW50ICsgcGFyYW1zLCBmdW5jdGlvbiAoZGF0YSkge1xyXG4gICAgICAgICAgICBjYWxsYmFjay5jYWxsKHRoaXMsIGRhdGEpO1xyXG4gICAgICAgICAgICBzY29wZS54aHIgPSBudWxsO1xyXG4gICAgICAgIH0pO1xyXG4gICAgfTtcclxuXHJcbiAgICB0aGlzLmZpbHRlciA9IGZ1bmN0aW9uICh2YWwpIHtcclxuICAgICAgICB2YWwgPSAodmFsIHx8ICcnKS50cmltKCkudG9Mb3dlckNhc2UoKTtcclxuICAgICAgICBpZiAodGhpcy5zZXR0aW5ncy5hamF4TW9kZSkge1xyXG4gICAgICAgICAgICB0aGlzLmxvYWRpbmcodHJ1ZSk7XHJcbiAgICAgICAgICAgIHRoaXMuYWpheEZpbHRlcih2YWwsIGZ1bmN0aW9uIChkYXRhKSB7XHJcbiAgICAgICAgICAgICAgICBzY29wZS5zZXREYXRhKGRhdGEuZGF0YSk7XHJcbiAgICAgICAgICAgICAgICBpZihkYXRhLmRhdGEgJiYgZGF0YS5kYXRhLmxlbmd0aCl7XHJcbiAgICAgICAgICAgICAgICAgICAgc2NvcGUub3BlbigpO1xyXG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICBzY29wZS5jbG9zZSgpO1xyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgc2NvcGUubG9hZGluZyhmYWxzZSk7XHJcbiAgICAgICAgICAgIH0pO1xyXG4gICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgIHZhciBhbGwgPSB0aGlzLnJvb3QucXVlcnlTZWxlY3RvckFsbCgnLm13LXNlbGVjdC1vcHRpb24nKSwgaSA9IDA7XHJcbiAgICAgICAgICAgIGlmICghdmFsKSB7XHJcbiAgICAgICAgICAgICAgICBmb3IoIDsgaTwgYWxsLmxlbmd0aDsgaSsrKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgYWxsW2ldLnN0eWxlLmRpc3BsYXkgPSAnJztcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgIGZvciggOyBpPCBhbGwubGVuZ3RoOyBpKyspIHtcclxuICAgICAgICAgICAgICAgICAgICBhbGxbaV0uc3R5bGUuZGlzcGxheSA9IHRoaXMuZ2V0TGFiZWwoYWxsW2ldLiR2YWx1ZSkudG9Mb3dlckNhc2UoKS5pbmRleE9mKHZhbCkgIT09IC0xID8gJycgOiAnbm9uZSc7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XHJcbiAgICB9O1xyXG5cclxuICAgIHRoaXMuc2V0UGxhY2Vob2xkZXIgPSBmdW5jdGlvbiAocGx2YWwpIHtcclxuICAgICAgICAvKnBsdmFsID0gcGx2YWwgfHwgdGhpcy5zZXR0aW5ncy5wbGFjZWhvbGRlcjtcclxuICAgICAgICBpZihzY29wZS5zZXR0aW5ncy5hdXRvY29tcGxldGUpe1xyXG4gICAgICAgICAgICAkKCcubXctdWktaW52aXNpYmxlLWZpZWxkJywgdGhpcy5yb290KS5hdHRyKCdwbGFjZWhvbGRlcicsIHBsdmFsKTtcclxuICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAkKCcubXctdWktYnRuLWNvbnRlbnQnLCB0aGlzLnJvb3QpLmh0bWwocGx2YWwpO1xyXG4gICAgICAgIH0qL1xyXG4gICAgICAgIHJldHVybiB0aGlzLmRpc3BsYXlWYWx1ZShwbHZhbClcclxuICAgIH07XHJcbiAgICB0aGlzLmRpc3BsYXlWYWx1ZSA9IGZ1bmN0aW9uKHBsdmFsKXtcclxuICAgICAgICBpZighcGx2YWwgJiYgIXRoaXMuc2V0dGluZ3MubXVsdGlwbGUgJiYgdGhpcy52YWx1ZSgpKSB7XHJcbiAgICAgICAgICAgIHBsdmFsID0gc2NvcGUuZ2V0TGFiZWwodGhpcy52YWx1ZSgpKTtcclxuICAgICAgICB9XHJcbiAgICAgICAgcGx2YWwgPSBwbHZhbCB8fCB0aGlzLnNldHRpbmdzLnBsYWNlaG9sZGVyO1xyXG4gICAgICAgIGlmKCFzY29wZS5fZGlzcGxheVZhbHVlKSB7XHJcbiAgICAgICAgICAgIHNjb3BlLl9kaXNwbGF5VmFsdWUgPSBzY29wZS5kb2N1bWVudC5jcmVhdGVFbGVtZW50KCdzcGFuJyk7XHJcbiAgICAgICAgICAgIHNjb3BlLl9kaXNwbGF5VmFsdWUuY2xhc3NOYW1lID0gJ213LXNlbGVjdC1kaXNwbGF5LXZhbHVlIG13LXVpLXNpemUtJyArIHRoaXMuc2V0dGluZ3Muc2l6ZTtcclxuICAgICAgICAgICAgJCgnLm13LXNlbGVjdC12YWx1ZScsIHRoaXMucm9vdCkuYXBwZW5kKHNjb3BlLl9kaXNwbGF5VmFsdWUpXHJcbiAgICAgICAgfVxyXG4gICAgICAgIGlmKHRoaXMuX3Jvb3RJbnB1dE1vZGUpe1xyXG4gICAgICAgICAgICBzY29wZS5fZGlzcGxheVZhbHVlLmlubmVySFRNTCA9ICcmbmJzcCc7XHJcbiAgICAgICAgICAgICQoJ2lucHV0Lm13LXVpLWludmlzaWJsZS1maWVsZCcsIHRoaXMucm9vdCkudmFsKHBsdmFsKTtcclxuXHJcbiAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgc2NvcGUuX2Rpc3BsYXlWYWx1ZS5pbm5lckhUTUwgPSBwbHZhbCArIHRoaXMuX19pbmRpY2F0ZU51bWJlcigpO1xyXG5cclxuICAgICAgICB9XHJcbiAgICB9O1xyXG5cclxuICAgIHRoaXMuX19pbmRpY2F0ZU51bWJlciA9IGZ1bmN0aW9uICgpIHtcclxuICAgICAgICBpZih0aGlzLnNldHRpbmdzLm11bHRpcGxlICYmIHRoaXMudmFsdWUoKSAmJiB0aGlzLnZhbHVlKCkubGVuZ3RoKXtcclxuICAgICAgICAgICAgcmV0dXJuIFwiPHNwYW4gY2xhc3M9J213LXNlbGVjdC1pbmRpY2F0ZS1udW1iZXInPlwiICsgdGhpcy52YWx1ZSgpLmxlbmd0aCArIFwiPC9zcGFuPlwiO1xyXG4gICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgIHJldHVybiBcIjxzcGFuIGNsYXNzPSdtdy1zZWxlY3QtaW5kaWNhdGUtbnVtYmVyIG13LXNlbGVjdC1pbmRpY2F0ZS1udW1iZXItZW1wdHknPlwiICsgMCArIFwiPC9zcGFuPlwiO1xyXG4gICAgICAgIH1cclxuICAgIH07XHJcblxyXG4gICAgdGhpcy5yZW5kID0ge1xyXG5cclxuICAgICAgICBvcHRpb246IGZ1bmN0aW9uKGl0ZW0pe1xyXG4gICAgICAgICAgICB2YXIgb2ggPSBzY29wZS5kb2N1bWVudC5jcmVhdGVFbGVtZW50KCdsYWJlbCcpO1xyXG4gICAgICAgICAgICBvaC4kdmFsdWUgPSBpdGVtO1xyXG4gICAgICAgICAgICBvaC5jbGFzc05hbWUgPSAnbXctc2VsZWN0LW9wdGlvbic7XHJcbiAgICAgICAgICAgIGlmIChzY29wZS5zZXR0aW5ncy5tdWx0aXBsZSkge1xyXG4gICAgICAgICAgICAgICAgb2guY2xhc3NOYW1lID0gJ213LXVpLWNoZWNrIG13LXNlbGVjdC1vcHRpb24nO1xyXG4gICAgICAgICAgICAgICAgb2guaW5uZXJIVE1MID0gICc8aW5wdXQgdHlwZT1cImNoZWNrYm94XCI+PHNwYW4+PC9zcGFuPjxzcGFuPicrc2NvcGUuZ2V0TGFiZWwoaXRlbSkrJzwvc3Bhbj4nO1xyXG5cclxuICAgICAgICAgICAgICAgICQoJ2lucHV0Jywgb2gpLm9uKCdjaGFuZ2UnLCBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5jaGVja2VkID8gc2NvcGUudmFsdWVBZGQob2guJHZhbHVlKSA6IHNjb3BlLnZhbHVlUmVtb3ZlKG9oLiR2YWx1ZSlcclxuICAgICAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgb2guaW5uZXJIVE1MID0gc2NvcGUuZ2V0TGFiZWwoaXRlbSk7XHJcbiAgICAgICAgICAgICAgICBvaC5vbmNsaWNrID0gZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICAgICAgICAgIHNjb3BlLnZhbHVlKG9oLiR2YWx1ZSlcclxuICAgICAgICAgICAgICAgIH07XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIHJldHVybiBvaDtcclxuICAgICAgICB9LFxyXG4gICAgICAgIHZhbHVlOiBmdW5jdGlvbigpIHtcclxuICAgICAgICAgICAgdmFyIHRhZyA9ICdzcGFuJywgY2xzID0gJ213LXVpLWJ0bic7XHJcbiAgICAgICAgICAgIGlmKHNjb3BlLnNldHRpbmdzLmF1dG9jb21wbGV0ZSl7XHJcbiAgICAgICAgICAgICAgICB0YWcgPSAnc3Bhbic7XHJcbiAgICAgICAgICAgICAgICBjbHMgPSAnbXctdWktZmllbGQnXHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgdmFyIG9oID0gc2NvcGUuZG9jdW1lbnQuY3JlYXRlRWxlbWVudCh0YWcpO1xyXG4gICAgICAgICAgICBvaC5jbGFzc05hbWUgPSBjbHMgKyAnIG13LXVpLXNpemUtJyArIHNjb3BlLnNldHRpbmdzLnNpemUgKyAnIG13LXVpLWJnLScgKyBzY29wZS5zZXR0aW5ncy5jb2xvciArICcgbXctc2VsZWN0LXZhbHVlJztcclxuXHJcbiAgICAgICAgICAgIGlmKHNjb3BlLnNldHRpbmdzLmF1dG9jb21wbGV0ZSl7XHJcbiAgICAgICAgICAgICAgICBvaC5pbm5lckhUTUwgPSAnPGlucHV0IGNsYXNzPVwibXctdWktaW52aXNpYmxlLWZpZWxkIG13LXVpLWZpZWxkLScgKyBzY29wZS5zZXR0aW5ncy5zaXplICsgJ1wiPic7XHJcbiAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICBvaC5pbm5lckhUTUwgPSAnPHNwYW4gY2xhc3M9XCJtdy11aS1idG4tY29udGVudFwiPjwvc3Bhbj4nO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBpZihzY29wZS5zZXR0aW5ncy5hdXRvY29tcGxldGUpe1xyXG4gICAgICAgICAgICAgICAgJCgnaW5wdXQnLCBvaClcclxuICAgICAgICAgICAgICAgICAgICAub24oJ2lucHV0IGZvY3VzJywgZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBzY29wZS5maWx0ZXIodGhpcy52YWx1ZSk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmKHNjb3BlLl9yb290SW5wdXRNb2RlKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBzY29wZS5lbGVtZW50LnZhbHVlID0gdGhpcy52YWx1ZTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICQoc2NvcGUuZWxlbWVudCkudHJpZ2dlcignaW5wdXQgY2hhbmdlJylcclxuICAgICAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgICAgIH0pXHJcbiAgICAgICAgICAgICAgICAgICAgLm9uKCdmb2N1cycsIGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgaWYoc2NvcGUuc2V0dGluZ3MuZGF0YSAmJiBzY29wZS5zZXR0aW5ncy5kYXRhLmxlbmd0aCkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgc2NvcGUub3BlbigpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICAgICAgfSkub24oJ2ZvY3VzIGJsdXIgaW5wdXQnLCBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgdmFyIGhhc1ZhbCA9ICEhdGhpcy52YWx1ZS50cmltKCk7XHJcbiAgICAgICAgICAgICAgICAgICAgbXcudG9vbHNbaGFzVmFsID8gJ2FkZENsYXNzJyA6ICdyZW1vdmVDbGFzcyddKHNjb3BlLnJvb3QsICdtdy1zZWxlY3QtaGFzLXZhbHVlJylcclxuICAgICAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgb2gub25jbGljayA9IGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgICAgICAgICBzY29wZS50b2dnbGUoKTtcclxuICAgICAgICAgICAgICAgIH07XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgcmV0dXJuIG9oO1xyXG4gICAgICAgIH0sXHJcbiAgICAgICAgb3B0aW9uczogZnVuY3Rpb24oKXtcclxuICAgICAgICAgICAgc2NvcGUub3B0aW9uc0hvbGRlciA9IHNjb3BlLmRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xyXG4gICAgICAgICAgICBzY29wZS5ob2xkZXIgPSBzY29wZS5kb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcclxuICAgICAgICAgICAgc2NvcGUub3B0aW9uc0hvbGRlci5jbGFzc05hbWUgPSAnbXctc2VsZWN0LW9wdGlvbnMnO1xyXG4gICAgICAgICAgICAkLmVhY2goc2NvcGUuc2V0dGluZ3MuZGF0YSwgZnVuY3Rpb24oKXtcclxuICAgICAgICAgICAgICAgIHNjb3BlLmhvbGRlci5hcHBlbmRDaGlsZChzY29wZS5yZW5kLm9wdGlvbih0aGlzKSlcclxuICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgICAgIHNjb3BlLm9wdGlvbnNIb2xkZXIuYXBwZW5kQ2hpbGQoc2NvcGUuaG9sZGVyKTtcclxuICAgICAgICAgICAgcmV0dXJuIHNjb3BlLm9wdGlvbnNIb2xkZXI7XHJcbiAgICAgICAgfSxcclxuICAgICAgICByb290OiBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgIHNjb3BlLnJvb3QgPSBzY29wZS5kb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcclxuICAgICAgICAgICAgc2NvcGUucm9vdC5jbGFzc05hbWUgPSAnbXctc2VsZWN0IG13LXNlbGVjdC1kcm9wbW9kZS0nICsgc2NvcGUuc2V0dGluZ3MuZHJvcE1vZGUgKyAnIG13LXNlbGVjdC1tdWx0aXBsZS0nICsgc2NvcGUuc2V0dGluZ3MubXVsdGlwbGU7XHJcblxyXG4gICAgICAgICAgICByZXR1cm4gc2NvcGUucm9vdDtcclxuICAgICAgICB9XHJcbiAgICB9O1xyXG5cclxuICAgIHRoaXMuc3RhdGUgPSAnY2xvc2VkJztcclxuXHJcbiAgICB0aGlzLm9wZW4gPSBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgdGhpcy5zdGF0ZSA9ICdvcGVuZWQnO1xyXG4gICAgICAgIG13LnRvb2xzLmFkZENsYXNzKHNjb3BlLnJvb3QsICdhY3RpdmUnKTtcclxuICAgICAgICBtdy5TZWxlY3QuX3JlZ2lzdGVyLmZvckVhY2goZnVuY3Rpb24gKGl0ZW0pIHtcclxuICAgICAgICAgICAgaWYoaXRlbSAhPT0gc2NvcGUpIHtcclxuICAgICAgICAgICAgICAgIGl0ZW0uY2xvc2UoKVxyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfSk7XHJcbiAgICB9O1xyXG5cclxuICAgIHRoaXMuY2xvc2UgPSBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgdGhpcy5zdGF0ZSA9ICdjbG9zZWQnO1xyXG4gICAgICAgIG13LnRvb2xzLnJlbW92ZUNsYXNzKHNjb3BlLnJvb3QsICdhY3RpdmUnKTtcclxuICAgIH07XHJcblxyXG4gICAgdGhpcy50YWdzID0gZnVuY3Rpb24gKCkge1xyXG4gICAgICAgIGlmKCF0aGlzLl90YWdzKSB7XHJcbiAgICAgICAgICAgIGlmKHRoaXMuc2V0dGluZ3MubXVsdGlwbGUgJiYgdGhpcy5zZXR0aW5ncy50YWdzKSB7XHJcbiAgICAgICAgICAgICAgICB2YXIgaG9sZGVyID0gc2NvcGUuZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XHJcbiAgICAgICAgICAgICAgICBob2xkZXIuY2xhc3NOYW1lID0gJ213LXNlbGVjdC10YWdzJztcclxuICAgICAgICAgICAgICAgIHRoaXMuX3RhZ3MgPSBuZXcgbXcudGFncyh7ZWxlbWVudDpob2xkZXIsIGRhdGE6c2NvcGUuX3ZhbHVlIHx8IFtdLCBjb2xvcjogdGhpcy5zZXR0aW5ncy50YWdzQ29sb3IsIHNpemU6IHRoaXMuc2V0dGluZ3MudGFnc1NpemUgfHwgJ3NtYWxsJ30pXHJcbiAgICAgICAgICAgICAgICAkKHRoaXMub3B0aW9uc0hvbGRlcikucHJlcGVuZChob2xkZXIpO1xyXG4gICAgICAgICAgICAgICAgbXcuJCh0aGlzLl90YWdzKS5vbigndGFnUmVtb3ZlZCcsIGZ1bmN0aW9uIChlLCB0YWcpIHtcclxuICAgICAgICAgICAgICAgICAgICBzY29wZS52YWx1ZVJlbW92ZSh0YWcpXHJcbiAgICAgICAgICAgICAgICB9KVxyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgdGhpcy5fdGFncy5zZXREYXRhKHNjb3BlLl92YWx1ZSlcclxuICAgICAgICB9XHJcbiAgICAgICAgdGhpcy5kaXNwbGF5VmFsdWUoKVxyXG4gICAgfTtcclxuXHJcblxyXG4gICAgdGhpcy50b2dnbGUgPSBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgaWYgKHRoaXMuc3RhdGUgPT09ICdjbG9zZWQnKSB7XHJcbiAgICAgICAgICAgIHRoaXMub3BlbigpO1xyXG4gICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgIHRoaXMuY2xvc2UoKTtcclxuICAgICAgICB9XHJcbiAgICB9O1xyXG5cclxuXHJcbiAgICB0aGlzLl92YWx1ZUdldCA9IGZ1bmN0aW9uICh2YWwpIHtcclxuICAgICAgICBpZih0eXBlb2YgdmFsID09PSAnbnVtYmVyJykge1xyXG4gICAgICAgICAgICB2YWwgPSB0aGlzLnNldHRpbmdzLmRhdGEuZmluZChmdW5jdGlvbiAoaXRlbSkge1xyXG4gICAgICAgICAgICAgICAgcmV0dXJuIGl0ZW0uaWQgPT09IHZhbDtcclxuICAgICAgICAgICAgfSlcclxuICAgICAgICB9XHJcbiAgICAgICAgcmV0dXJuIHZhbDtcclxuICAgIH07XHJcblxyXG5cclxuXHJcbiAgICB0aGlzLnZhbHVlQWRkID0gZnVuY3Rpb24odmFsKXtcclxuICAgICAgICBpZiAoIXZhbCkgcmV0dXJuO1xyXG4gICAgICAgIHZhbCA9IHRoaXMuX3ZhbHVlR2V0KHZhbCk7XHJcbiAgICAgICAgaWYgKCF2YWwpIHJldHVybjtcclxuICAgICAgICBpZiAoIXRoaXMuX3ZhbHVlKSB7XHJcbiAgICAgICAgICAgIHRoaXMuX3ZhbHVlID0gW11cclxuICAgICAgICB9XHJcbiAgICAgICAgdmFyIGV4aXN0cyA9IHRoaXMuX3ZhbHVlLmZpbmQoZnVuY3Rpb24gKGl0ZW0pIHtcclxuICAgICAgICAgICAgcmV0dXJuIGl0ZW0uaWQgPT09IHZhbC5pZDtcclxuICAgICAgICB9KTtcclxuICAgICAgICBpZiAoIWV4aXN0cykge1xyXG4gICAgICAgICAgICB0aGlzLl92YWx1ZS5wdXNoKHZhbCk7XHJcbiAgICAgICAgICAgICQodGhpcy5yb290LnF1ZXJ5U2VsZWN0b3JBbGwoJy5tdy1zZWxlY3Qtb3B0aW9uJykpLmVhY2goZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICAgICAgaWYodGhpcy4kdmFsdWUgPT09IHZhbCkge1xyXG4gICAgICAgICAgICAgICAgICAgIHRoaXMucXVlcnlTZWxlY3RvcignaW5wdXQnKS5jaGVja2VkID0gdHJ1ZTtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICB0aGlzLmFmdGVyQ2hhbmdlKCk7XHJcbiAgICB9O1xyXG5cclxuICAgIHRoaXMuYWZ0ZXJDaGFuZ2UgPSBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgdGhpcy50YWdzKCk7XHJcbiAgICAgICAgdGhpcy5kaXNwbGF5VmFsdWUoKTtcclxuICAgICAgICBpZigkLmlzQXJyYXkodGhpcy5fdmFsdWUpKSB7XHJcbiAgICAgICAgICAgICQuZWFjaCh0aGlzLl92YWx1ZSwgZnVuY3Rpb24gKCkge1xyXG5cclxuICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgICAgICQodGhpcy5yb290LnF1ZXJ5U2VsZWN0b3JBbGwoJy5tdy1zZWxlY3Qtb3B0aW9uJykpLmVhY2goZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICAgICAgaWYoc2NvcGUuX3ZhbHVlLmluZGV4T2YodGhpcy4kdmFsdWUpICE9PSAtMSkge1xyXG4gICAgICAgICAgICAgICAgICAgIG13LnRvb2xzLmFkZENsYXNzKHRoaXMsICdhY3RpdmUnKVxyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAgICAgbXcudG9vbHMucmVtb3ZlQ2xhc3ModGhpcywgJ2FjdGl2ZScpXHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH0pO1xyXG4gICAgICAgIH1cclxuICAgICAgICAkKHRoaXMpLnRyaWdnZXIoJ2NoYW5nZScsIFt0aGlzLl92YWx1ZV0pO1xyXG4gICAgfTtcclxuXHJcbiAgICB0aGlzLnZhbHVlUmVtb3ZlID0gZnVuY3Rpb24odmFsKSB7XHJcbiAgICAgICAgaWYgKCF2YWwpIHJldHVybjtcclxuICAgICAgICB2YWwgPSB0aGlzLl92YWx1ZUdldCh2YWwpO1xyXG4gICAgICAgIGlmICghdmFsKSByZXR1cm47XHJcbiAgICAgICAgaWYgKCF0aGlzLl92YWx1ZSkge1xyXG4gICAgICAgICAgICB0aGlzLl92YWx1ZSA9IFtdO1xyXG4gICAgICAgIH1cclxuICAgICAgICB2YXIgZXhpc3RzID0gdGhpcy5fdmFsdWUuZmluZChmdW5jdGlvbiAoaXRlbSkge1xyXG4gICAgICAgICAgICByZXR1cm4gaXRlbS5pZCA9PT0gdmFsLmlkO1xyXG4gICAgICAgIH0pO1xyXG4gICAgICAgIGlmIChleGlzdHMpIHtcclxuICAgICAgICAgICAgdGhpcy5fdmFsdWUuc3BsaWNlKHRoaXMuX3ZhbHVlLmluZGV4T2YoZXhpc3RzKSwgMSk7XHJcbiAgICAgICAgfVxyXG4gICAgICAgICQodGhpcy5yb290LnF1ZXJ5U2VsZWN0b3JBbGwoJy5tdy1zZWxlY3Qtb3B0aW9uJykpLmVhY2goZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICBpZih0aGlzLiR2YWx1ZSA9PT0gdmFsKSB7XHJcbiAgICAgICAgICAgICAgICB0aGlzLnF1ZXJ5U2VsZWN0b3IoJ2lucHV0JykuY2hlY2tlZCA9IGZhbHNlO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfSk7XHJcbiAgICAgICAgdGhpcy5hZnRlckNoYW5nZSgpXHJcbiAgICB9O1xyXG5cclxuICAgIHRoaXMuX3ZhbHVlVG9nZ2xlID0gZnVuY3Rpb24odmFsKXtcclxuICAgICAgICBpZiAoIXZhbCkgcmV0dXJuO1xyXG4gICAgICAgIGlmICghdGhpcy5fdmFsdWUpIHtcclxuICAgICAgICAgICAgdGhpcy5fdmFsdWUgPSBbXTtcclxuICAgICAgICB9XHJcbiAgICAgICAgdmFyIGV4aXN0cyA9IHRoaXMuX3ZhbHVlLmZpbmQoZnVuY3Rpb24gKGl0ZW0pIHtcclxuICAgICAgICAgICAgcmV0dXJuIGl0ZW0uaWQgPT09IHZhbC5pZDtcclxuICAgICAgICB9KTtcclxuICAgICAgICBpZiAoZXhpc3RzKSB7XHJcbiAgICAgICAgICAgIHRoaXMuX3ZhbHVlLnNwbGljZSh0aGlzLl92YWx1ZS5pbmRleE9mKGV4aXN0cyksIDEpO1xyXG4gICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgIHRoaXMuX3ZhbHVlLnB1c2godmFsKTtcclxuICAgICAgICB9XHJcbiAgICAgICAgdGhpcy5hZnRlckNoYW5nZSgpO1xyXG4gICAgfTtcclxuXHJcbiAgICB0aGlzLnZhbHVlID0gZnVuY3Rpb24odmFsKXtcclxuICAgICAgICBpZighdmFsKSByZXR1cm4gdGhpcy5fdmFsdWU7XHJcbiAgICAgICAgdmFsID0gdGhpcy5fdmFsdWVHZXQodmFsKTtcclxuICAgICAgICBpZiAoIXZhbCkgcmV0dXJuO1xyXG4gICAgICAgIGlmKHRoaXMuc2V0dGluZ3MubXVsdGlwbGUpe1xyXG4gICAgICAgICAgICB0aGlzLl92YWx1ZVRvZ2dsZSh2YWwpXHJcbiAgICAgICAgfVxyXG4gICAgICAgIGVsc2Uge1xyXG4gICAgICAgICAgICB0aGlzLl92YWx1ZSA9IHZhbDtcclxuICAgICAgICAgICAgJCgnLm13LXVpLWludmlzaWJsZS1maWVsZCcsIHRoaXMucm9vdCkudmFsKHRoaXMuZ2V0TGFiZWwodmFsKSlcclxuICAgICAgICAgICAgdGhpcy5jbG9zZSgpO1xyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgdGhpcy5hZnRlckNoYW5nZSgpXHJcbiAgICB9O1xyXG5cclxuICAgIHRoaXMuc2V0RGF0YSA9IGZ1bmN0aW9uIChkYXRhKSB7XHJcbiAgICAgICAgJChzY29wZS5ob2xkZXIpLmVtcHR5KCk7XHJcbiAgICAgICAgc2NvcGUuc2V0dGluZ3MuZGF0YSA9IGRhdGE7XHJcbiAgICAgICAgJC5lYWNoKHNjb3BlLnNldHRpbmdzLmRhdGEsIGZ1bmN0aW9uKCl7XHJcbiAgICAgICAgICAgIHNjb3BlLmhvbGRlci5hcHBlbmRDaGlsZChzY29wZS5yZW5kLm9wdGlvbih0aGlzKSlcclxuICAgICAgICB9KTtcclxuICAgICAgICByZXR1cm4gc2NvcGUuaG9sZGVyO1xyXG4gICAgfTtcclxuXHJcbiAgICB0aGlzLmFkZERhdGEgPSBmdW5jdGlvbiAoZGF0YSkge1xyXG4gICAgICAgICQuZWFjaChkYXRhLCBmdW5jdGlvbigpe1xyXG4gICAgICAgICAgICBzY29wZS5zZXR0aW5ncy5kYXRhLnB1c2godGhpcyk7XHJcbiAgICAgICAgICAgIHNjb3BlLmhvbGRlci5hcHBlbmRDaGlsZChzY29wZS5yZW5kLm9wdGlvbih0aGlzKSk7XHJcbiAgICAgICAgfSk7XHJcbiAgICAgICAgcmV0dXJuIHNjb3BlLmhvbGRlcjtcclxuICAgIH07XHJcblxyXG4gICAgdGhpcy5idWlsZCA9IGZ1bmN0aW9uICgpIHtcclxuICAgICAgICB0aGlzLnJlbmQucm9vdCgpO1xyXG4gICAgICAgIHRoaXMucm9vdC5hcHBlbmRDaGlsZCh0aGlzLnJlbmQudmFsdWUoKSk7XHJcbiAgICAgICAgdGhpcy5yb290LmFwcGVuZENoaWxkKHRoaXMucmVuZC5vcHRpb25zKCkpO1xyXG4gICAgICAgIGlmICh0aGlzLl9yb290SW5wdXRNb2RlKSB7XHJcbiAgICAgICAgICAgIHRoaXMuZWxlbWVudC50eXBlID0gJ2hpZGRlbic7XHJcbiAgICAgICAgICAgIHRoaXMuJGVsZW1lbnQuYmVmb3JlKHRoaXMucm9vdCk7XHJcbiAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgdGhpcy4kZWxlbWVudC5odG1sKHRoaXMucm9vdCk7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIHRoaXMuc2V0UGxhY2Vob2xkZXIoKTtcclxuICAgICAgICBtdy5TZWxlY3QuX3JlZ2lzdGVyLnB1c2godGhpcyk7XHJcbiAgICB9O1xyXG5cclxuICAgIHRoaXMuaW5pdCA9IGZ1bmN0aW9uICgpIHtcclxuICAgICAgICB0aGlzLmJ1aWxkKCk7XHJcbiAgICAgICAgdGhpcy5lbGVtZW50Ll9td1NlbGVjdCA9IHRoaXM7XHJcbiAgICB9O1xyXG5cclxuXHJcblxyXG4gICAgdGhpcy5pbml0KCk7XHJcblxyXG5cclxufTtcclxuXHJcbm13LlNlbGVjdC5fcmVnaXN0ZXIgPSBbXTtcclxuXHJcblxyXG4kKGRvY3VtZW50KS5yZWFkeShmdW5jdGlvbiAoKSB7XHJcbiAgICAkKGRvY3VtZW50KS5vbignbW91c2Vkb3duIHRvdWNoc3RhcnQnLCBmdW5jdGlvbiAoZSkge1xyXG4gICAgICAgIGlmKCFtdy50b29scy5maXJzdFBhcmVudE9yQ3VycmVudFdpdGhDbGFzcyhlLnRhcmdldCwgJ213LXNlbGVjdCcpKXtcclxuICAgICAgICAgICAgbXcuU2VsZWN0Ll9yZWdpc3Rlci5mb3JFYWNoKGZ1bmN0aW9uIChpdGVtKSB7XHJcbiAgICAgICAgICAgICAgICBpdGVtLmNsb3NlKCk7XHJcbiAgICAgICAgICAgIH0pO1xyXG4gICAgICAgIH1cclxuICAgIH0pO1xyXG59KTtcclxuXHJcblxyXG5tdy5zZWxlY3QgPSBmdW5jdGlvbihvcHRpb25zKSB7XHJcbiAgICByZXR1cm4gbmV3IG13LlNlbGVjdChvcHRpb25zKTtcclxufTtcclxuIiwibXcuU3Bpbm5lciA9IGZ1bmN0aW9uKG9wdGlvbnMpe1xuICAgIGlmKCFvcHRpb25zIHx8ICFvcHRpb25zLmVsZW1lbnQpe1xuICAgICAgICByZXR1cm47XG4gICAgfVxuICAgIHRoaXMuJGVsZW1lbnQgPSAkKG9wdGlvbnMuZWxlbWVudCk7XG4gICAgaWYoIXRoaXMuJGVsZW1lbnQubGVuZ3RoKSByZXR1cm47XG4gICAgdGhpcy5lbGVtZW50ID0gdGhpcy4kZWxlbWVudFswXTtcbiAgICBpZih0aGlzLmVsZW1lbnQuX213U3Bpbm5lcil7XG4gICAgICAgIHJldHVybiB0aGlzLmVsZW1lbnQuX213U3Bpbm5lcjtcbiAgICB9XG4gICAgdGhpcy5lbGVtZW50Ll9td1NwaW5uZXIgPSB0aGlzO1xuICAgIHRoaXMub3B0aW9ucyA9IG9wdGlvbnM7XG5cbiAgICB0aGlzLm9wdGlvbnMuc2l6ZSA9IHRoaXMub3B0aW9ucy5zaXplIHx8IDIwO1xuICAgIHRoaXMub3B0aW9ucy5jb2xvciA9IHRoaXMub3B0aW9ucy5jb2xvciB8fCAnIzQ1OTJmZic7XG4gICAgdGhpcy5vcHRpb25zLmluc2VydE1vZGUgPSB0aGlzLm9wdGlvbnMuaW5zZXJ0TW9kZSB8fCAnYXBwZW5kJztcblxuICAgIHRoaXMuY29sb3IgPSBmdW5jdGlvbih2YWwpe1xuICAgICAgICBpZighdmFsKSB7XG4gICAgICAgICAgICByZXR1cm4gdGhpcy5vcHRpb25zLmNvbG9yO1xuICAgICAgICB9XG4gICAgICAgIHRoaXMub3B0aW9ucy5jb2xvciA9IHZhbDtcbiAgICAgICAgdGhpcy4kc3Bpbm5lci5maW5kKCdjaXJjbGUnKS5jc3Moe1xuICAgICAgICAgICAgc3Ryb2tlOiB0aGlzLm9wdGlvbnMuY29sb3JcbiAgICAgICAgfSk7XG4gICAgfTtcblxuICAgIHRoaXMuc2l6ZSA9IGZ1bmN0aW9uKHZhbCl7XG4gICAgICAgIGlmKCF2YWwpIHtcbiAgICAgICAgICAgIHJldHVybiB0aGlzLm9wdGlvbnMuc2l6ZTtcbiAgICAgICAgfVxuICAgICAgICB0aGlzLm9wdGlvbnMuc2l6ZSA9IHBhcnNlRmxvYXQodmFsKTtcbiAgICAgICAgdGhpcy4kc3Bpbm5lci5jc3Moe1xuICAgICAgICAgICAgd2lkdGg6IHRoaXMub3B0aW9ucy5zaXplLFxuICAgICAgICAgICAgaGVpZ2h0OiB0aGlzLm9wdGlvbnMuc2l6ZVxuICAgICAgICB9KTtcbiAgICB9O1xuXG4gICAgdGhpcy5jcmVhdGUgPSBmdW5jdGlvbigpe1xuICAgICAgICB0aGlzLiRzcGlubmVyID0gJCgnPGRpdiBjbGFzcz1cIm13LXNwaW5uZXIgbXctc3Bpbm5lci1tb2RlLScgKyB0aGlzLm9wdGlvbnMuaW5zZXJ0TW9kZSArICdcIiBzdHlsZT1cImRpc3BsYXk6IG5vbmU7XCI+PHN2ZyB2aWV3Qm94PVwiMCAwIDUwIDUwXCI+PGNpcmNsZSBjeD1cIjI1XCIgY3k9XCIyNVwiIHI9XCIyMFwiIGZpbGw9XCJub25lXCIgc3Ryb2tlLXdpZHRoPVwiNVwiPjwvY2lyY2xlPjwvc3ZnPjwvZGl2PicpO1xuICAgICAgICB0aGlzLnNpemUodGhpcy5vcHRpb25zLnNpemUpO1xuICAgICAgICB0aGlzLmNvbG9yKHRoaXMub3B0aW9ucy5jb2xvcik7XG4gICAgICAgIHRoaXMuJGVsZW1lbnRbdGhpcy5vcHRpb25zLmluc2VydE1vZGVdKHRoaXMuJHNwaW5uZXIpO1xuICAgICAgICB0aGlzLnNob3coKTtcbiAgICAgICAgcmV0dXJuIHRoaXM7XG4gICAgfTtcblxuICAgIHRoaXMuc2hvdyA9IGZ1bmN0aW9uKCl7XG4gICAgICAgIHRoaXMuJHNwaW5uZXIuc2hvdygpO1xuICAgICAgICB0aGlzLiRlbGVtZW50LmFkZENsYXNzKCdoYXMtbXctc3Bpbm5lcicpO1xuICAgICAgICByZXR1cm4gdGhpcztcbiAgICB9O1xuXG4gICAgdGhpcy5oaWRlID0gZnVuY3Rpb24oKXtcbiAgICAgICAgdGhpcy4kc3Bpbm5lci5oaWRlKCk7XG4gICAgICAgIHRoaXMuJGVsZW1lbnQucmVtb3ZlQ2xhc3MoJ2hhcy1tdy1zcGlubmVyJyk7XG4gICAgICAgIHJldHVybiB0aGlzO1xuICAgIH07XG5cbiAgICB0aGlzLnJlbW92ZSA9IGZ1bmN0aW9uKCl7XG4gICAgICAgIHRoaXMuaGlkZSgpO1xuICAgICAgICB0aGlzLiRzcGlubmVyLnJlbW92ZSgpO1xuICAgICAgICBkZWxldGUgdGhpcy5lbGVtZW50Ll9td1NwaW5uZXI7XG4gICAgfTtcblxuICAgIHRoaXMuY3JlYXRlKCkuc2hvdygpO1xuXG59O1xuXG5tdy5zcGlubmVyID0gZnVuY3Rpb24ob3B0aW9ucyl7XG4gICAgcmV0dXJuIG5ldyBtdy5TcGlubmVyKG9wdGlvbnMpO1xufTtcbiIsIlxyXG5cclxubXcuY29yZUljb25zID0ge1xyXG4gICAgY2F0ZWdvcnk6J213LWljb24tY2F0ZWdvcnknLFxyXG4gICAgcGFnZTonbXctaWNvbi1wYWdlJyxcclxuICAgIGhvbWU6J213LWljb24taG9tZScsXHJcbiAgICBzaG9wOidtYWktbWFya2V0MicsXHJcbiAgICBwb3N0OidtYWktcG9zdCdcclxufTtcclxuXHJcblxyXG5tdy50YWdzID0gbXcuY2hpcHMgPSBmdW5jdGlvbihvcHRpb25zKXtcclxuXHJcbiAgICBcInVzZSBzdHJpY3RcIjtcclxuXHJcbiAgICBvcHRpb25zLmVsZW1lbnQgPSBtdy4kKG9wdGlvbnMuZWxlbWVudClbMF07XHJcbiAgICBvcHRpb25zLnNpemUgPSBvcHRpb25zLnNpemUgfHwgJ3NtJztcclxuXHJcbiAgICB0aGlzLm9wdGlvbnMgPSBvcHRpb25zO1xyXG4gICAgdGhpcy5vcHRpb25zLm1hcCA9IHRoaXMub3B0aW9ucy5tYXAgfHwgeyB0aXRsZTogJ3RpdGxlJywgdmFsdWU6ICdpZCcsIGltYWdlOiAnaW1hZ2UnLCBpY29uOiAnaWNvbicgfTtcclxuICAgIHRoaXMubWFwID0gdGhpcy5vcHRpb25zLm1hcDtcclxuICAgIHZhciBzY29wZSA9IHRoaXM7XHJcbiAgICAvKlxyXG4gICAgICAgIGRhdGE6IFtcclxuICAgICAgICAgICAge3RpdGxlOidTb21lIHRhZycsIGljb246JzxpIGNsYXNzPVwiaWNvblwiPjwvaT4nfSxcclxuICAgICAgICAgICAge3RpdGxlOidTb21lIHRhZycsIGljb246J2ljb24nLCBpbWFnZTonaHR0cDovL3NvbWUtaW1hZ2UvanBnLnBuZyd9LFxyXG4gICAgICAgICAgICB7dGl0bGU6J1NvbWUgdGFnJywgY29sb3I6J3dhcm4nfSxcclxuICAgICAgICBdXHJcbiAgICAqL1xyXG5cclxuXHJcbiAgICB0aGlzLnJlZnJlc2ggPSBmdW5jdGlvbigpe1xyXG4gICAgICAgIG13LiQoc2NvcGUub3B0aW9ucy5lbGVtZW50KS5lbXB0eSgpO1xyXG4gICAgICAgIHRoaXMucmVuZCgpO1xyXG4gICAgfTtcclxuXHJcbiAgICB0aGlzLnNldERhdGEgPSBmdW5jdGlvbihkYXRhKXtcclxuICAgICAgICB0aGlzLm9wdGlvbnMuZGF0YSA9IGRhdGE7XHJcbiAgICAgICAgdGhpcy5yZWZyZXNoKCk7XHJcbiAgICB9O1xyXG4gICAgdGhpcy5yZW5kID0gZnVuY3Rpb24oKXtcclxuICAgICAgICAkLmVhY2godGhpcy5vcHRpb25zLmRhdGEsIGZ1bmN0aW9uKGkpe1xyXG4gICAgICAgICAgICB2YXIgZGF0YSA9ICQuZXh0ZW5kKHtpbmRleDppfSwgdGhpcyk7XHJcbiAgICAgICAgICAgIHNjb3BlLm9wdGlvbnMuZWxlbWVudC5hcHBlbmRDaGlsZChzY29wZS50YWcoZGF0YSkpO1xyXG4gICAgICAgIH0pO1xyXG4gICAgICAgIGlmKHRoaXMub3B0aW9ucy5pbnB1dEZpZWxkKSB7XHJcbiAgICAgICAgICAgIHNjb3BlLm9wdGlvbnMuZWxlbWVudC5hcHBlbmRDaGlsZCh0aGlzLmFkZElucHV0RmllbGQoKSk7XHJcbiAgICAgICAgfVxyXG4gICAgfTtcclxuXHJcbiAgICB0aGlzLmFkZElucHV0RmllbGQgPSBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgdGhpcy5fZmllbGQgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdpbnB1dCcpO1xyXG4gICAgICAgIHRoaXMuX2ZpZWxkLmNsYXNzTmFtZSA9ICdtdy11aS1pbnZpc2libGUtZmllbGQgbXctdWktZmllbGQtJyArIHRoaXMub3B0aW9ucy5zaXplO1xyXG4gICAgICAgIHRoaXMuX2ZpZWxkLm9ua2V5ZG93biA9IGZ1bmN0aW9uIChlKSB7XHJcbiAgICAgICAgICAgIGlmKG13LmV2ZW50LmlzLmVudGVyKGUpKSB7XHJcbiAgICAgICAgICAgICAgICB2YXIgdmFsID0gc2NvcGUuX2ZpZWxkLnZhbHVlLnRyaW0oKTtcclxuICAgICAgICAgICAgICAgIGlmKHZhbCkge1xyXG4gICAgICAgICAgICAgICAgICAgIHNjb3BlLmFkZFRhZyh7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHRpdGxlOiB2YWxcclxuICAgICAgICAgICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH07XHJcbiAgICAgICAgcmV0dXJuIHRoaXMuX2ZpZWxkO1xyXG4gICAgfTtcclxuXHJcblxyXG5cclxuICAgIHRoaXMuZGF0YVZhbHVlID0gZnVuY3Rpb24oZGF0YSl7XHJcbiAgICAgICAgaWYodHlwZW9mIGRhdGEgPT09ICdzdHJpbmcnKXtcclxuICAgICAgICAgICAgcmV0dXJuIGRhdGE7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIGVsc2V7XHJcbiAgICAgICAgICAgIHJldHVybiBkYXRhW3RoaXMubWFwLnZhbHVlXVxyXG4gICAgICAgIH1cclxuICAgIH07XHJcblxyXG4gICAgdGhpcy5kYXRhSW1hZ2UgPSBmdW5jdGlvbihkYXRhKXtcclxuICAgICAgICBpZihkYXRhW3RoaXMubWFwLmltYWdlXSl7XHJcbiAgICAgICAgICAgIHZhciBpbWcgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdzcGFuJyk7XHJcbiAgICAgICAgICAgIGltZy5jbGFzc05hbWUgPSAnbXctdWktYnRuLWltZyc7XHJcbiAgICAgICAgICAgIGltZy5zdHlsZS5iYWNrZ3JvdW5kSW1hZ2UgPSAndXJsKCcrZGF0YS5pbWFnZSsnKSc7XHJcbiAgICAgICAgICAgIHJldHVybiBpbWc7XHJcbiAgICAgICAgfVxyXG4gICAgfTtcclxuXHJcbiAgICB0aGlzLmRhdGFUaXRsZSA9IGZ1bmN0aW9uKGRhdGEpe1xyXG4gICAgICAgIGlmKHR5cGVvZiBkYXRhID09PSAnc3RyaW5nJyl7XHJcbiAgICAgICAgICAgIHJldHVybiBkYXRhO1xyXG4gICAgICAgIH1cclxuICAgICAgICBlbHNle1xyXG4gICAgICAgICAgICByZXR1cm4gZGF0YVt0aGlzLm1hcC50aXRsZV07XHJcbiAgICAgICAgfVxyXG4gICAgfTtcclxuXHJcbiAgICB0aGlzLmRhdGFJY29uID0gZnVuY3Rpb24oZGF0YSl7XHJcbiAgICAgICAgaWYodHlwZW9mIGRhdGEgPT09ICdzdHJpbmcnKXtcclxuICAgICAgICAgICAgcmV0dXJuO1xyXG4gICAgICAgIH1cclxuICAgICAgICBlbHNle1xyXG4gICAgICAgICAgICByZXR1cm4gZGF0YVt0aGlzLm1hcC5pY29uXVxyXG4gICAgICAgIH1cclxuICAgIH07XHJcblxyXG5cclxuICAgICB0aGlzLmNyZWF0ZUltYWdlID0gZnVuY3Rpb24gKGNvbmZpZykge1xyXG4gICAgICAgICB2YXIgaW1nID0gdGhpcy5kYXRhSW1hZ2UoY29uZmlnKTtcclxuICAgICAgICBpZihpbWcpe1xyXG4gICAgICAgICAgICByZXR1cm4gaW1nO1xyXG4gICAgICAgIH1cclxuICAgICB9O1xyXG4gICAgIHRoaXMuY3JlYXRlSWNvbiA9IGZ1bmN0aW9uIChjb25maWcpIHtcclxuICAgICAgICB2YXIgaWMgPSB0aGlzLmRhdGFJY29uKGNvbmZpZyk7XHJcblxyXG4gICAgICAgIGlmKCFpYyAmJiBjb25maWcudHlwZSl7XHJcbiAgICAgICAgICAgIGljID0gbXcuY29yZUljb25zW2NvbmZpZy50eXBlXTtcclxuXHJcbiAgICAgICAgfVxyXG4gICAgICAgIHZhciBpY29uO1xyXG4gICAgICAgIGlmKHR5cGVvZiBpYyA9PT0gJ3N0cmluZycgJiYgaWMuaW5kZXhPZignPCcpID09PSAtMSl7XHJcbiAgICAgICAgICAgIGljb24gPSBtd2QuY3JlYXRlRWxlbWVudCgnaScpO1xyXG4gICAgICAgICAgICBpY29uLmNsYXNzTmFtZSA9IGljO1xyXG4gICAgICAgIH1cclxuICAgICAgICBlbHNle1xyXG4gICAgICAgICAgICBpY29uID0gaWM7XHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICByZXR1cm4gbXcuJChpY29uKVswXTtcclxuICAgICB9O1xyXG5cclxuICAgICB0aGlzLnJlbW92ZVRhZyA9IGZ1bmN0aW9uIChpbmRleCkge1xyXG4gICAgICAgIHZhciBpdGVtID0gdGhpcy5vcHRpb25zLmRhdGFbaW5kZXhdO1xyXG4gICAgICAgIHRoaXMub3B0aW9ucy5kYXRhLnNwbGljZShpbmRleCwxKTtcclxuICAgICAgICB0aGlzLnJlZnJlc2goKTtcclxuICAgICAgICBtdy4kKHNjb3BlKS50cmlnZ2VyKCd0YWdSZW1vdmVkJywgW2l0ZW0sIHRoaXMub3B0aW9ucy5kYXRhXSk7XHJcbiAgICAgICAgbXcuJChzY29wZSkudHJpZ2dlcignY2hhbmdlJywgW2l0ZW0sIHRoaXMub3B0aW9ucy5kYXRhXSk7XHJcbiAgICAgfTtcclxuXHJcbiAgICB0aGlzLmFkZFRhZyA9IGZ1bmN0aW9uKGRhdGEsIGluZGV4KXtcclxuICAgICAgICBpbmRleCA9IHR5cGVvZiBpbmRleCA9PT0gJ251bWJlcicgPyBpbmRleCA6IHRoaXMub3B0aW9ucy5kYXRhLmxlbmd0aDtcclxuICAgICAgICB0aGlzLm9wdGlvbnMuZGF0YS5zcGxpY2UoIGluZGV4LCAwLCBkYXRhICk7XHJcbiAgICAgICAgdGhpcy5yZWZyZXNoKCk7XHJcbiAgICAgICAgbXcuJChzY29wZSkudHJpZ2dlcigndGFnQWRkZWQnLCBbZGF0YSwgdGhpcy5vcHRpb25zLmRhdGFdKTtcclxuICAgICAgICBtdy4kKHNjb3BlKS50cmlnZ2VyKCdjaGFuZ2UnLCBbZGF0YSwgdGhpcy5vcHRpb25zLmRhdGFdKTtcclxuICAgIH07XHJcblxyXG4gICAgIHRoaXMudGFnID0gZnVuY3Rpb24gKG9wdGlvbnMpIHtcclxuICAgICAgICAgICAgdmFyIGNvbmZpZyA9IHtcclxuICAgICAgICAgICAgICAgIGNsb3NlOnRydWUsXHJcbiAgICAgICAgICAgICAgICB0YWdCdG5DbGFzczonYnRuIGJ0bi0nICsgdGhpcy5vcHRpb25zLnNpemVcclxuICAgICAgICAgICAgfTtcclxuXHJcbiAgICAgICAgICAgICQuZXh0ZW5kKGNvbmZpZywgb3B0aW9ucyk7XHJcblxyXG4gICAgICAgICBjb25maWcudGFnQnRuQ2xhc3MgKz0gICcgbWItMiBtci0yIGJ0bic7XHJcblxyXG4gICAgICAgICBpZiAodGhpcy5vcHRpb25zLm91dGxpbmUpe1xyXG4gICAgICAgICAgICAgY29uZmlnLnRhZ0J0bkNsYXNzICs9ICAnLW91dGxpbmUnO1xyXG4gICAgICAgICB9XHJcblxyXG4gICAgICAgICBpZiAodGhpcy5vcHRpb25zLmNvbG9yKXtcclxuICAgICAgICAgICAgIGNvbmZpZy50YWdCdG5DbGFzcyArPSAgJy0nICsgdGhpcy5vcHRpb25zLmNvbG9yO1xyXG4gICAgICAgICB9XHJcblxyXG5cclxuXHJcbiAgICAgICAgIGlmKHRoaXMub3B0aW9ucy5yb3VuZGVkKXtcclxuICAgICAgICAgICAgIGNvbmZpZy50YWdCdG5DbGFzcyArPSAgJyBidG4tcm91bmRlZCc7XHJcbiAgICAgICAgIH1cclxuXHJcblxyXG4gICAgICAgICAgICB2YXIgdGFnX2hvbGRlciA9IG13ZC5jcmVhdGVFbGVtZW50KCdzcGFuJyk7XHJcbiAgICAgICAgICAgIHZhciB0YWdfY2xvc2UgPSBtd2QuY3JlYXRlRWxlbWVudCgnc3BhbicpO1xyXG5cclxuICAgICAgICAgICAgdGFnX2Nsb3NlLl9pbmRleCA9IGNvbmZpZy5pbmRleDtcclxuICAgICAgICAgICAgdGFnX2hvbGRlci5faW5kZXggPSBjb25maWcuaW5kZXg7XHJcbiAgICAgICAgICAgIHRhZ19ob2xkZXIuX2NvbmZpZyA9IGNvbmZpZztcclxuICAgICAgICAgICAgdGFnX2hvbGRlci5kYXRhc2V0LmluZGV4ID0gY29uZmlnLmluZGV4O1xyXG5cclxuICAgICAgICAgICAgdGFnX2hvbGRlci5jbGFzc05hbWUgPSBjb25maWcudGFnQnRuQ2xhc3M7XHJcblxyXG4gICAgICAgICAgICAgaWYob3B0aW9ucy5pbWFnZSl7XHJcblxyXG4gICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgdGFnX2hvbGRlci5pbm5lckhUTUwgPSAnPHNwYW4gY2xhc3M9XCJ0YWctbGFiZWwtY29udGVudFwiPicgKyB0aGlzLmRhdGFUaXRsZShjb25maWcpICsgJzwvc3Bhbj4nO1xyXG5cclxuICAgICAgICAgICAgIGlmKHR5cGVvZiB0aGlzLm9wdGlvbnMuZGlzYWJsZUl0ZW0gPT09ICdmdW5jdGlvbicpIHtcclxuICAgICAgICAgICAgICAgICBpZih0aGlzLm9wdGlvbnMuZGlzYWJsZUl0ZW0oY29uZmlnKSl7XHJcbiAgICAgICAgICAgICAgICAgICAgIHRhZ19ob2xkZXIuY2xhc3NOYW1lICs9ICcgZGlzYWJsZWQnO1xyXG4gICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgIGlmKHR5cGVvZiB0aGlzLm9wdGlvbnMuaGlkZUl0ZW0gPT09ICdmdW5jdGlvbicpIHtcclxuICAgICAgICAgICAgICAgICBpZih0aGlzLm9wdGlvbnMuaGlkZUl0ZW0oY29uZmlnKSl7XHJcbiAgICAgICAgICAgICAgICAgICAgIHRhZ19ob2xkZXIuY2xhc3NOYW1lICs9ICcgaGlkZGVuJztcclxuICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICB2YXIgaWNvbiA9IHRoaXMuY3JlYXRlSWNvbihjb25maWcpO1xyXG5cclxuICAgICAgICAgICAgdmFyIGltYWdlID0gdGhpcy5jcmVhdGVJbWFnZShjb25maWcpO1xyXG5cclxuICAgICAgICAgICAgIGlmKGltYWdlKXtcclxuICAgICAgICAgICAgICAgICBtdy4kKHRhZ19ob2xkZXIpLnByZXBlbmQoaW1hZ2UpO1xyXG4gICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgaWYoaWNvbil7XHJcbiAgICAgICAgICAgICAgICAgbXcuJCh0YWdfaG9sZGVyKS5wcmVwZW5kKGljb24pO1xyXG4gICAgICAgICAgICAgfVxyXG5cclxuXHJcbiAgICAgICAgICAgIHRhZ19ob2xkZXIub25jbGljayA9IGZ1bmN0aW9uIChlKSB7XHJcbiAgICAgICAgICAgICAgICBpZihlLnRhcmdldCAhPT0gdGFnX2Nsb3NlKXtcclxuICAgICAgICAgICAgICAgICAgICBtdy4kKHNjb3BlKS50cmlnZ2VyKCd0YWdDbGljaycsIFt0aGlzLl9jb25maWcsIHRoaXMuX2luZGV4LCB0aGlzXSlcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfTtcclxuXHJcbiAgICAgICAgICAgIHRhZ19jbG9zZS5jbGFzc05hbWUgPSAnbXctaWNvbi1jbG9zZSBtbC0xJztcclxuICAgICAgICAgICAgaWYoY29uZmlnLmNsb3NlKXtcclxuICAgICAgICAgICAgICAgIHRhZ19jbG9zZS5vbmNsaWNrID0gZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICAgICAgICAgIHNjb3BlLnJlbW92ZVRhZyh0aGlzLl9pbmRleCk7XHJcbiAgICAgICAgICAgICAgICB9O1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIHRhZ19ob2xkZXIuYXBwZW5kQ2hpbGQodGFnX2Nsb3NlKTtcclxuICAgICAgICAgICAgcmV0dXJuIHRhZ19ob2xkZXI7XHJcbiAgICAgICAgfTtcclxuXHJcbiAgICAgdGhpcy5pbml0ID0gZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICB0aGlzLnJlbmQoKTtcclxuICAgICAgICAgJCh0aGlzLm9wdGlvbnMuZWxlbWVudCkub24oJ2NsaWNrJywgZnVuY3Rpb24gKGUpIHtcclxuICAgICAgICAgICAgIGlmKGUudGFyZ2V0ID09PSBzY29wZS5vcHRpb25zLmVsZW1lbnQpe1xyXG4gICAgICAgICAgICAgICAgICQoJ2lucHV0JywgdGhpcykuZm9jdXMoKTtcclxuICAgICAgICAgICAgIH1cclxuICAgICAgICAgfSlcclxuICAgICB9O1xyXG4gICAgdGhpcy5pbml0KCk7XHJcbn07XHJcblxyXG5tdy50cmVlVGFncyA9IG13LnRyZWVDaGlwcyA9IGZ1bmN0aW9uKG9wdGlvbnMpe1xyXG4gICAgdGhpcy5vcHRpb25zID0gb3B0aW9ucztcclxuICAgIHZhciBzY29wZSA9IHRoaXM7XHJcblxyXG4gICAgdmFyIHRhZ3NIb2xkZXIgPSBvcHRpb25zLnRhZ3NIb2xkZXIgfHwgbXcuJCgnPGRpdiBjbGFzcz1cIm13LXRyZWUtdGFnLXRhZ3MtaG9sZGVyXCI+PC9kaXY+Jyk7XHJcbiAgICB2YXIgdHJlZUhvbGRlciA9IG9wdGlvbnMudHJlZUhvbGRlciB8fCBtdy4kKCc8ZGl2IGNsYXNzPVwibXctdHJlZS10YWctdHJlZS1ob2xkZXJcIj48L2Rpdj4nKTtcclxuXHJcbiAgICB2YXIgdHJlZVNldHRpbmdzID0gJC5leHRlbmQoe30sIHRoaXMub3B0aW9ucywge2VsZW1lbnQ6dHJlZUhvbGRlcn0pO1xyXG4gICAgdmFyIHRhZ3NTZXR0aW5ncyA9ICQuZXh0ZW5kKHt9LCB0aGlzLm9wdGlvbnMsIHtlbGVtZW50OnRhZ3NIb2xkZXIsIGRhdGE6dGhpcy5vcHRpb25zLnNlbGVjdGVkRGF0YSB8fCBbXX0pO1xyXG5cclxuICAgIHRoaXMudHJlZSA9IG5ldyBtdy50cmVlKHRyZWVTZXR0aW5ncyk7XHJcblxyXG4gICAgdGhpcy50YWdzID0gbmV3IG13LnRhZ3ModGFnc1NldHRpbmdzKTtcclxuXHJcbiAgICBtdy4kKCB0aGlzLm9wdGlvbnMuZWxlbWVudCApLmFwcGVuZCh0YWdzSG9sZGVyKTtcclxuICAgIG13LiQoIHRoaXMub3B0aW9ucy5lbGVtZW50ICkuYXBwZW5kKHRyZWVIb2xkZXIpO1xyXG5cclxuICAgICBtdy4kKHRoaXMudGFncykub24oJ3RhZ1JlbW92ZWQnLCBmdW5jdGlvbihldmVudCwgaXRlbSl7XHJcbiAgICAgICAgIHNjb3BlLnRyZWUudW5zZWxlY3QoaXRlbSk7XHJcbiAgICAgfSk7XHJcbiAgICAgbXcuJCh0aGlzLnRyZWUpLm9uKCdzZWxlY3Rpb25DaGFuZ2UnLCBmdW5jdGlvbihldmVudCwgc2VsZWN0ZWREYXRhKXtcclxuICAgICAgICBzY29wZS50YWdzLnNldERhdGEoc2VsZWN0ZWREYXRhKTtcclxuICAgIH0pO1xyXG5cclxufTtcclxuIiwiKGZ1bmN0aW9uKCl7XG4gICAgdmFyIHRvb2x0aXAgPSB7XG4gICAgICAgIHNvdXJjZTogZnVuY3Rpb24gKGNvbnRlbnQsIHNraW4sIHBvc2l0aW9uLCBpZCkge1xuICAgICAgICAgICAgaWYgKHNraW4gPT09ICdkYXJrJykge1xuICAgICAgICAgICAgICAgIHNraW4gPSAnbXctdG9vbHRpcC1kYXJrJztcbiAgICAgICAgICAgIH0gZWxzZSBpZiAoc2tpbiA9PT0gJ3dhcm5pbmcnKSB7XG4gICAgICAgICAgICAgICAgc2tpbiA9ICdtdy10b29sdGlwLWRlZmF1bHQgbXctdG9vbHRpcC13YXJuaW5nJztcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGlmICh0eXBlb2YgaWQgPT09ICd1bmRlZmluZWQnKSB7XG4gICAgICAgICAgICAgICAgaWQgPSAnbXctdG9vbHRpcC0nICsgbXcucmFuZG9tKCk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICB2YXIgdG9vbHRpcCA9IG13ZC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgICAgIHZhciB0b29sdGlwYyA9IG13ZC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgICAgIHRvb2x0aXAuY2xhc3NOYW1lID0gJ213LXRvb2x0aXAgJyArIHBvc2l0aW9uICsgJyAnICsgc2tpbjtcbiAgICAgICAgICAgIHRvb2x0aXBjLmNsYXNzTmFtZSA9ICdtdy10b29sdGlwLWNvbnRlbnQnO1xuICAgICAgICAgICAgdG9vbHRpcC5pZCA9IGlkO1xuICAgICAgICAgICAgJCh0b29sdGlwYykuYXBwZW5kKGNvbnRlbnQpO1xuICAgICAgICAgICAgJCh0b29sdGlwKS5hcHBlbmQodG9vbHRpcGMpLmFwcGVuZCgnPHNwYW4gY2xhc3M9XCJtdy10b29sdGlwLWFycm93XCI+PC9zcGFuPicpO1xuICAgICAgICAgICAgbXdkLmJvZHkuYXBwZW5kQ2hpbGQodG9vbHRpcCk7XG4gICAgICAgICAgICByZXR1cm4gdG9vbHRpcDtcbiAgICAgICAgfSxcbiAgICAgICAgc2V0UG9zaXRpb246IGZ1bmN0aW9uICh0b29sdGlwLCBlbCwgcG9zaXRpb24pIHtcbiAgICAgICAgICAgICAgICBlbCA9IG13LiQoZWwpO1xuICAgICAgICAgICAgICAgIGlmIChlbC5sZW5ndGggPT09IDApIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB0b29sdGlwLnRvb2x0aXBEYXRhLmVsZW1lbnQgPSBlbFswXTtcbiAgICAgICAgICAgICAgICB2YXIgdyA9IGVsLm91dGVyV2lkdGgoKSxcbiAgICAgICAgICAgICAgICAgICAgdGlwd2lkdGggPSBtdy4kKHRvb2x0aXApLm91dGVyV2lkdGgoKSxcbiAgICAgICAgICAgICAgICAgICAgaCA9IGVsLm91dGVySGVpZ2h0KCksXG4gICAgICAgICAgICAgICAgICAgIHRpcGhlaWdodCA9IG13LiQodG9vbHRpcCkub3V0ZXJIZWlnaHQoKSxcbiAgICAgICAgICAgICAgICAgICAgb2ZmID0gZWwub2Zmc2V0KCksXG4gICAgICAgICAgICAgICAgICAgIGFycmhlaWdodCA9IG13LiQoJy5tdy10b29sdGlwLWFycm93JywgdG9vbHRpcCkuaGVpZ2h0KCk7XG4gICAgICAgICAgICAgICAgaWYgKG9mZi50b3AgPT09IDAgJiYgb2ZmLmxlZnQgPT09IDApIHtcbiAgICAgICAgICAgICAgICAgICAgb2ZmID0gbXcuJChlbCkucGFyZW50KCkub2Zmc2V0KCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIG13LnRvb2xzLnJlbW92ZUNsYXNzKHRvb2x0aXAsIHRvb2x0aXAudG9vbHRpcERhdGEucG9zaXRpb24pO1xuICAgICAgICAgICAgICAgIG13LnRvb2xzLmFkZENsYXNzKHRvb2x0aXAsIHBvc2l0aW9uKTtcbiAgICAgICAgICAgICAgICB0b29sdGlwLnRvb2x0aXBEYXRhLnBvc2l0aW9uID0gcG9zaXRpb247XG5cbiAgICAgICAgICAgICAgICBvZmYubGVmdCA9IG9mZi5sZWZ0ID4gMCA/IG9mZi5sZWZ0IDogMDtcbiAgICAgICAgICAgICAgICBvZmYudG9wID0gb2ZmLnRvcCA+IDAgPyBvZmYudG9wIDogMDtcblxuICAgICAgICAgICAgICAgIHZhciBsZWZ0Q2VudGVyID0gb2ZmLmxlZnQgLSB0aXB3aWR0aCAvIDIgKyB3IC8gMjtcbiAgICAgICAgICAgICAgICBsZWZ0Q2VudGVyID0gbGVmdENlbnRlciA+IDAgPyBsZWZ0Q2VudGVyIDogMDtcblxuICAgICAgICAgICAgICAgIGlmIChwb3NpdGlvbiA9PT0gJ2F1dG8nKSB7XG4gICAgICAgICAgICAgICAgICAgIHZhciAkd2luID0gbXcuJCh3aW5kb3cpO1xuICAgICAgICAgICAgICAgICAgICB2YXIgd3hDZW50ZXIgPSAgJHdpbi53aWR0aCgpLzI7XG4gICAgICAgICAgICAgICAgICAgIHZhciB3eUNlbnRlciA9ICAoJHdpbi5oZWlnaHQoKSArICR3aW4uc2Nyb2xsVG9wKCkpLzI7XG4gICAgICAgICAgICAgICAgICAgIHZhciBlbFhDZW50ZXIgPSAgb2ZmLmxlZnQgKyh3LzIpO1xuICAgICAgICAgICAgICAgICAgICB2YXIgZWxZQ2VudGVyID0gIG9mZi50b3AgKyhoLzIpO1xuICAgICAgICAgICAgICAgICAgICB2YXIgeFBvcywgeVBvc3Q7XG4gICAgICAgICAgICAgICAgICAgIHZhciBzcGFjZSA9IDEwMDtcblxuICAgICAgICAgICAgICAgICAgICBpZihlbFhDZW50ZXIgPiB3eENlbnRlcikge1xuICAgICAgICAgICAgICAgICAgICAgICAgeFBvcyA9ICdsZWZ0J1xuICAgICAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICAgICAgeFBvcyA9ICdyaWdodCdcbiAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgICAgIHlQb3MgPSAndG9wJ1xuXG5cbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHRoaXMuc2V0UG9zaXRpb24gKHRvb2x0aXAsIGVsLCAoeFBvcysnLScreVBvcykpO1xuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgIGlmIChwb3NpdGlvbiA9PT0gJ2JvdHRvbS1sZWZ0Jykge1xuICAgICAgICAgICAgICAgICAgICBtdy4kKHRvb2x0aXApLmNzcyh7XG4gICAgICAgICAgICAgICAgICAgICAgICB0b3A6IG9mZi50b3AgKyBoICsgYXJyaGVpZ2h0LFxuICAgICAgICAgICAgICAgICAgICAgICAgbGVmdDogb2ZmLmxlZnRcbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGVsc2UgaWYgKHBvc2l0aW9uID09PSAnYm90dG9tLWNlbnRlcicpIHtcbiAgICAgICAgICAgICAgICAgICAgbXcuJCh0b29sdGlwKS5jc3Moe1xuICAgICAgICAgICAgICAgICAgICAgICAgdG9wOiBvZmYudG9wICsgaCArIGFycmhlaWdodCxcbiAgICAgICAgICAgICAgICAgICAgICAgIGxlZnQ6IGxlZnRDZW50ZXJcbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGVsc2UgaWYgKHBvc2l0aW9uID09PSAnYm90dG9tLXJpZ2h0Jykge1xuICAgICAgICAgICAgICAgICAgICBtdy4kKHRvb2x0aXApLmNzcyh7XG4gICAgICAgICAgICAgICAgICAgICAgICB0b3A6IG9mZi50b3AgKyBoICsgYXJyaGVpZ2h0LFxuICAgICAgICAgICAgICAgICAgICAgICAgbGVmdDogb2ZmLmxlZnQgLSB0aXB3aWR0aCArIHdcbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGVsc2UgaWYgKHBvc2l0aW9uID09PSAndG9wLXJpZ2h0Jykge1xuICAgICAgICAgICAgICAgICAgICBtdy4kKHRvb2x0aXApLmNzcyh7XG4gICAgICAgICAgICAgICAgICAgICAgICB0b3A6IG9mZi50b3AgLSB0aXBoZWlnaHQgLSBhcnJoZWlnaHQsXG4gICAgICAgICAgICAgICAgICAgICAgICBsZWZ0OiBvZmYubGVmdCAtIHRpcHdpZHRoICsgd1xuICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgZWxzZSBpZiAocG9zaXRpb24gPT09ICd0b3AtbGVmdCcpIHtcbiAgICAgICAgICAgICAgICAgICAgbXcuJCh0b29sdGlwKS5jc3Moe1xuICAgICAgICAgICAgICAgICAgICAgICAgdG9wOiBvZmYudG9wIC0gdGlwaGVpZ2h0IC0gYXJyaGVpZ2h0LFxuICAgICAgICAgICAgICAgICAgICAgICAgbGVmdDogb2ZmLmxlZnRcbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGVsc2UgaWYgKHBvc2l0aW9uID09PSAndG9wLWNlbnRlcicpIHtcblxuICAgICAgICAgICAgICAgICAgICBtdy4kKHRvb2x0aXApLmNzcyh7XG4gICAgICAgICAgICAgICAgICAgICAgICB0b3A6IG9mZi50b3AgLSB0aXBoZWlnaHQgLSBhcnJoZWlnaHQsXG4gICAgICAgICAgICAgICAgICAgICAgICBsZWZ0OiBsZWZ0Q2VudGVyXG4gICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBlbHNlIGlmIChwb3NpdGlvbiA9PT0gJ2xlZnQtdG9wJykge1xuICAgICAgICAgICAgICAgICAgICBtdy4kKHRvb2x0aXApLmNzcyh7XG4gICAgICAgICAgICAgICAgICAgICAgICB0b3A6IG9mZi50b3AsXG4gICAgICAgICAgICAgICAgICAgICAgICBsZWZ0OiBvZmYubGVmdCAtIHRpcHdpZHRoIC0gYXJyaGVpZ2h0XG4gICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBlbHNlIGlmIChwb3NpdGlvbiA9PT0gJ2xlZnQtYm90dG9tJykge1xuICAgICAgICAgICAgICAgICAgICBtdy4kKHRvb2x0aXApLmNzcyh7XG4gICAgICAgICAgICAgICAgICAgICAgICB0b3A6IChvZmYudG9wICsgaCkgLSB0aXBoZWlnaHQsXG4gICAgICAgICAgICAgICAgICAgICAgICBsZWZ0OiBvZmYubGVmdCAtIHRpcHdpZHRoIC0gYXJyaGVpZ2h0XG4gICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBlbHNlIGlmIChwb3NpdGlvbiA9PT0gJ2xlZnQtY2VudGVyJykge1xuICAgICAgICAgICAgICAgICAgICBtdy4kKHRvb2x0aXApLmNzcyh7XG4gICAgICAgICAgICAgICAgICAgICAgICB0b3A6IG9mZi50b3AgLSB0aXBoZWlnaHQgLyAyICsgaCAvIDIsXG4gICAgICAgICAgICAgICAgICAgICAgICBsZWZ0OiBvZmYubGVmdCAtIHRpcHdpZHRoIC0gYXJyaGVpZ2h0XG4gICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBlbHNlIGlmIChwb3NpdGlvbiA9PT0gJ3JpZ2h0LXRvcCcpIHtcbiAgICAgICAgICAgICAgICAgICAgbXcuJCh0b29sdGlwKS5jc3Moe1xuICAgICAgICAgICAgICAgICAgICAgICAgdG9wOiBvZmYudG9wLFxuICAgICAgICAgICAgICAgICAgICAgICAgbGVmdDogb2ZmLmxlZnQgKyB3ICsgYXJyaGVpZ2h0XG4gICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBlbHNlIGlmIChwb3NpdGlvbiA9PT0gJ3JpZ2h0LWJvdHRvbScpIHtcbiAgICAgICAgICAgICAgICAgICAgbXcuJCh0b29sdGlwKS5jc3Moe1xuICAgICAgICAgICAgICAgICAgICAgICAgdG9wOiAob2ZmLnRvcCArIGgpIC0gdGlwaGVpZ2h0LFxuICAgICAgICAgICAgICAgICAgICAgICAgbGVmdDogb2ZmLmxlZnQgKyB3ICsgYXJyaGVpZ2h0XG4gICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBlbHNlIGlmIChwb3NpdGlvbiA9PT0gJ3JpZ2h0LWNlbnRlcicpIHtcbiAgICAgICAgICAgICAgICAgICAgbXcuJCh0b29sdGlwKS5jc3Moe1xuICAgICAgICAgICAgICAgICAgICAgICAgdG9wOiBvZmYudG9wIC0gdGlwaGVpZ2h0IC8gMiArIGggLyAyLFxuICAgICAgICAgICAgICAgICAgICAgICAgbGVmdDogb2ZmLmxlZnQgKyB3ICsgYXJyaGVpZ2h0XG4gICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBpZiAocGFyc2VGbG9hdCgkKHRvb2x0aXApLmNzcygndG9wJykpIDwgMCkge1xuICAgICAgICAgICAgICAgICAgICBtdy4kKHRvb2x0aXApLmNzcygndG9wJywgMCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIGZpeFBvc2l0aW9uOiBmdW5jdGlvbiAodG9vbHRpcCkge1xuICAgICAgICAgICAgICAgIC8qIG13X3RvZG8gKi9cbiAgICAgICAgICAgICAgICB2YXIgbWF4ID0gNTtcbiAgICAgICAgICAgICAgICB2YXIgYXJyID0gbXcuJCgnLm13LXRvb2x0aXAtYXJyb3cnLCB0b29sdGlwKTtcbiAgICAgICAgICAgICAgICBhcnIuY3NzKCdsZWZ0JywgJycpO1xuICAgICAgICAgICAgICAgIHZhciBhcnJfbGVmdCA9IHBhcnNlRmxvYXQoYXJyLmNzcygnbGVmdCcpKTtcbiAgICAgICAgICAgICAgICB2YXIgdHQgPSBtdy4kKHRvb2x0aXApO1xuICAgICAgICAgICAgICAgIGlmICh0dC5sZW5ndGggPT09IDApIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB2YXIgdyA9IHR0LndpZHRoKCksXG4gICAgICAgICAgICAgICAgICAgIG9mZiA9IHR0Lm9mZnNldCgpLFxuICAgICAgICAgICAgICAgICAgICB3dyA9IG13LiQod2luZG93KS53aWR0aCgpO1xuICAgICAgICAgICAgICAgIGlmICgob2ZmLmxlZnQgKyB3KSA+ICh3dyAtIG1heCkpIHtcbiAgICAgICAgICAgICAgICAgICAgdmFyIGRpZmYgPSBvZmYubGVmdCAtICh3dyAtIHcgLSBtYXgpO1xuICAgICAgICAgICAgICAgICAgICB0dC5jc3MoJ2xlZnQnLCB3dyAtIHcgLSBtYXgpO1xuICAgICAgICAgICAgICAgICAgICBhcnIuY3NzKCdsZWZ0JywgYXJyX2xlZnQgKyBkaWZmKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgaWYgKHBhcnNlRmxvYXQodHQuY3NzKCd0b3AnKSkgPCAwKSB7XG4gICAgICAgICAgICAgICAgICAgIHR0LmNzcygndG9wJywgMCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIHByZXBhcmU6IGZ1bmN0aW9uIChvKSB7XG4gICAgICAgICAgICAgICAgaWYgKHR5cGVvZiBvLmVsZW1lbnQgPT09ICd1bmRlZmluZWQnKSByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICAgICAgaWYgKG8uZWxlbWVudCA9PT0gbnVsbCkgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgICAgIGlmICh0eXBlb2Ygby5lbGVtZW50ID09PSAnc3RyaW5nJykge1xuICAgICAgICAgICAgICAgICAgICBvLmVsZW1lbnQgPSBtdy4kKG8uZWxlbWVudClcbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICBpZiAoby5lbGVtZW50LmNvbnN0cnVjdG9yID09PSBbXS5jb25zdHJ1Y3RvciAmJiBvLmVsZW1lbnQubGVuZ3RoID09PSAwKSByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICAgICAgaWYgKHR5cGVvZiBvLnBvc2l0aW9uID09PSAndW5kZWZpbmVkJykge1xuICAgICAgICAgICAgICAgICAgICBvLnBvc2l0aW9uID0gJ2F1dG8nO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBpZiAodHlwZW9mIG8uc2tpbiA9PT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAgICAgICAgICAgICAgICAgby5za2luID0gJ213LXRvb2x0aXAtZGVmYXVsdCc7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGlmICh0eXBlb2Ygby5pZCA9PT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAgICAgICAgICAgICAgICAgby5pZCA9ICdtdy10b29sdGlwLScgKyBtdy5yYW5kb20oKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgaWYgKHR5cGVvZiBvLmdyb3VwID09PSAndW5kZWZpbmVkJykge1xuICAgICAgICAgICAgICAgICAgICBvLmdyb3VwID0gbnVsbDtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgcmV0dXJuIHtcbiAgICAgICAgICAgICAgICAgICAgaWQ6IG8uaWQsXG4gICAgICAgICAgICAgICAgICAgIGVsZW1lbnQ6IG8uZWxlbWVudCxcbiAgICAgICAgICAgICAgICAgICAgc2tpbjogby50ZW1wbGF0ZSB8fCBvLnNraW4sXG4gICAgICAgICAgICAgICAgICAgIHBvc2l0aW9uOiBvLnBvc2l0aW9uLFxuICAgICAgICAgICAgICAgICAgICBjb250ZW50OiBvLmNvbnRlbnQsXG4gICAgICAgICAgICAgICAgICAgIGdyb3VwOiBvLmdyb3VwXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIGluaXQ6IGZ1bmN0aW9uIChvLCB3bCkge1xuXG4gICAgICAgICAgICAgICAgdmFyIG9yaWdfb3B0aW9ucyA9IG87XG4gICAgICAgICAgICAgICAgbyA9IG13LnRvb2xzLnRvb2x0aXAucHJlcGFyZShvKTtcbiAgICAgICAgICAgICAgICBpZiAobyA9PT0gZmFsc2UpIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgICAgICBpZiAoby5pZCAmJiBtdy4kKCcjJyArIG8uaWQpLmxlbmd0aCA+IDApIHtcbiAgICAgICAgICAgICAgICAgICAgdmFyIHRpcCA9IG13LiQoJyMnICsgby5pZClbMF07XG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgdmFyIHRpcCA9IG13LnRvb2xzLnRvb2x0aXAuc291cmNlKG8uY29udGVudCwgby5za2luLCBvLnBvc2l0aW9uLCBvLmlkKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgdGlwLnRvb2x0aXBEYXRhID0gbztcbiAgICAgICAgICAgICAgICB2YXIgd2wgPSB3bCB8fCB0cnVlO1xuICAgICAgICAgICAgICAgIGlmIChvLmdyb3VwKSB7XG4gICAgICAgICAgICAgICAgICAgIHZhciB0aXBfZ3JvdXBfY2xhc3MgPSAnbXctdG9vbHRpcC1ncm91cC0nICsgby5ncm91cDtcbiAgICAgICAgICAgICAgICAgICAgdmFyIGN1cl90aXAgPSBtdy4kKHRpcClcbiAgICAgICAgICAgICAgICAgICAgaWYgKCFjdXJfdGlwLmhhc0NsYXNzKHRpcF9ncm91cF9jbGFzcykpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGN1cl90aXAuYWRkQ2xhc3ModGlwX2dyb3VwX2NsYXNzKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICB2YXIgY3VyX3RpcF9pZCA9IGN1cl90aXAuYXR0cignaWQnKTtcbiAgICAgICAgICAgICAgICAgICAgaWYgKGN1cl90aXBfaWQpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13LiQoXCIuXCIgKyB0aXBfZ3JvdXBfY2xhc3MpLm5vdChcIiNcIiArIGN1cl90aXBfaWQpLmhpZGUoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmIChvLmdyb3VwICYmIHR5cGVvZiBvcmlnX29wdGlvbnMuY2xvc2Vfb25fY2xpY2tfb3V0c2lkZSAhPT0gJ3VuZGVmaW5lZCcgJiYgb3JpZ19vcHRpb25zLmNsb3NlX29uX2NsaWNrX291dHNpZGUpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgbXcuJChcIiNcIiArIGN1cl90aXBfaWQpLnNob3coKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9LCAxMDApO1xuICAgICAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBtdy4kKFwiI1wiICsgY3VyX3RpcF9pZCkuc2hvdygpO1xuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGlmICh3bCAmJiAkLmNvbnRhaW5zKHNlbGYuZG9jdW1lbnQsIHRpcCkpIHtcbiAgICAgICAgICAgICAgICAgICAgLypcbiAgICAgICAgICAgICAgICAgICAgIC8vcG9zaXRpb24gYnVnOiByZXNpemUgZmlyZXMgaW4gbW9kYWwgZnJhbWVcbiAgICAgICAgICAgICAgICAgICAgIG13LiQoc2VsZikuYmluZCgncmVzaXplIHNjcm9sbCcsIGZ1bmN0aW9uIChlKSB7XG4gICAgICAgICAgICAgICAgICAgICBpZiAoc2VsZi5kb2N1bWVudC5jb250YWlucyh0aXApKSB7XG4gICAgICAgICAgICAgICAgICAgICBzZWxmLm13LnRvb2xzLnRvb2x0aXAuc2V0UG9zaXRpb24odGlwLCB0aXAudG9vbHRpcERhdGEuZWxlbWVudCwgby5wb3NpdGlvbik7XG4gICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICB9KTsqL1xuICAgICAgICAgICAgICAgICAgICBpZiAoby5ncm91cCAmJiB0eXBlb2Ygb3JpZ19vcHRpb25zLmNsb3NlX29uX2NsaWNrX291dHNpZGUgIT09ICd1bmRlZmluZWQnICYmIG9yaWdfb3B0aW9ucy5jbG9zZV9vbl9jbGlja19vdXRzaWRlKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBtdy4kKHNlbGYpLmJpbmQoJ2NsaWNrJywgZnVuY3Rpb24gKGUsIHRhcmdldCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG13LiQoXCIuXCIgKyB0aXBfZ3JvdXBfY2xhc3MpLmhpZGUoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIG13LnRvb2xzLnRvb2x0aXAuc2V0UG9zaXRpb24odGlwLCBvLmVsZW1lbnQsIG8ucG9zaXRpb24pO1xuICAgICAgICAgICAgICAgIHJldHVybiB0aXA7XG4gICAgICAgICAgICB9XG5cbiAgICB9O1xuXG4gICAgbXcudG9vbHMudG9vbHRpcCA9IHRvb2x0aXA7XG4gICAgbXcudG9vbHMudGl0bGVUaXAgPSBmdW5jdGlvbiAoZWwsIF90aXRsZVRpcCkge1xuICAgICAgICBfdGl0bGVUaXAgPSBfdGl0bGVUaXAgfHwgJ190aXRsZVRpcCc7XG4gICAgICAgIGlmIChtdy50b29scy5oYXNDbGFzcyhlbCwgJ3RpcC1kaXNhYmxlZCcpKSB7XG4gICAgICAgICAgICBtdy4kKG13LnRvb2xzW190aXRsZVRpcF0pLmhpZGUoKTtcbiAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgfVxuICAgICAgICB2YXIgc2tpbiA9IG13LiQoZWwpLmF0dHIoJ2RhdGEtdGlwc2tpbicpO1xuICAgICAgICBza2luID0gKHNraW4pID8gc2tpbiA6ICdtdy10b29sdGlwLWRhcmsnO1xuICAgICAgICB2YXIgcG9zID0gbXcuJChlbCkuYXR0cignZGF0YS10aXBwb3NpdGlvbicpO1xuICAgICAgICB2YXIgaXNjaXJjbGUgPSBtdy4kKGVsKS5hdHRyKCdkYXRhLXRpcGNpcmNsZScpID09PSAndHJ1ZSc7XG4gICAgICAgIGlmICghcG9zKSB7XG4gICAgICAgICAgICBwb3MgPSAndG9wLWNlbnRlcic7XG4gICAgICAgIH1cbiAgICAgICAgdmFyIHRleHQgPSBtdy4kKGVsKS5hdHRyKCdkYXRhLXRpcCcpO1xuICAgICAgICBpZiAoIXRleHQpIHtcbiAgICAgICAgICAgIHRleHQgPSBtdy4kKGVsKS5hdHRyKCd0aXRsZScpO1xuICAgICAgICB9XG4gICAgICAgIGlmICghdGV4dCkge1xuICAgICAgICAgICAgdGV4dCA9IG13LiQoZWwpLmF0dHIoJ3RpcCcpO1xuICAgICAgICB9XG4gICAgICAgIGlmICh0eXBlb2YgdGV4dCA9PT0gJ3VuZGVmaW5lZCcgfHwgIXRleHQpIHtcbiAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgfVxuICAgICAgICBpZiAodGV4dC5pbmRleE9mKCcuJykgPT09IDAgfHwgdGV4dC5pbmRleE9mKCcjJykgPT09IDApIHtcbiAgICAgICAgICAgIHZhciB4aXRlbSA9IG13LiQodGV4dCk7XG4gICAgICAgICAgICBpZiAoeGl0ZW0ubGVuZ3RoID09PSAwKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgdGV4dCA9IHhpdGVtLmh0bWwoKTtcbiAgICAgICAgfVxuICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgIHRleHQgPSB0ZXh0LnJlcGxhY2UoL1xcbi9nLCAnPGJyPicpO1xuICAgICAgICB9XG4gICAgICAgIHZhciBzaG93b24gPSBtdy4kKGVsKS5hdHRyKCdkYXRhLXNob3dvbicpO1xuICAgICAgICBpZiAoc2hvd29uKSB7XG4gICAgICAgICAgICBlbCA9IG13LiQoc2hvd29uKVswXTtcbiAgICAgICAgfVxuICAgICAgICBpZiAoIW13LnRvb2xzW190aXRsZVRpcF0pIHtcbiAgICAgICAgICAgIG13LnRvb2xzW190aXRsZVRpcF0gPSBtdy50b29sdGlwKHtza2luOiBza2luLCBlbGVtZW50OiBlbCwgcG9zaXRpb246IHBvcywgY29udGVudDogdGV4dH0pO1xuICAgICAgICAgICAgbXcuJChtdy50b29sc1tfdGl0bGVUaXBdKS5hZGRDbGFzcygnbXctdW5pdmVyc2FsLXRvb2x0aXAnKTtcbiAgICAgICAgfVxuICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgIG13LnRvb2xzW190aXRsZVRpcF0uY2xhc3NOYW1lID0gJ213LXRvb2x0aXAgJyArIHBvcyArICcgJyArIHNraW4gKyAnIG13LXVuaXZlcnNhbC10b29sdGlwJztcbiAgICAgICAgICAgIG13LiQoJy5tdy10b29sdGlwLWNvbnRlbnQnLCBtdy50b29sc1tfdGl0bGVUaXBdKS5odG1sKHRleHQpO1xuICAgICAgICAgICAgbXcudG9vbHMudG9vbHRpcC5zZXRQb3NpdGlvbihtdy50b29sc1tfdGl0bGVUaXBdLCBlbCwgcG9zKTtcbiAgICAgICAgfVxuICAgICAgICBpZiAoaXNjaXJjbGUpIHtcbiAgICAgICAgICAgIG13LiQobXcudG9vbHNbX3RpdGxlVGlwXSkuYWRkQ2xhc3MoJ213LXRvb2x0aXAtY2lyY2xlJyk7XG4gICAgICAgIH1cbiAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICBtdy4kKG13LnRvb2xzW190aXRsZVRpcF0pLnJlbW92ZUNsYXNzKCdtdy10b29sdGlwLWNpcmNsZScpO1xuICAgICAgICB9XG4gICAgICAgIG13LiQobXcudG9vbHNbX3RpdGxlVGlwXSkuc2hvdygpO1xuICAgIH1cblxufSkoKTtcbiIsIlxuLyoqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqXG5cblxuIHZhciBteVRyZWUgPSBuZXcgbXcudHJlZSh7XG5cbn0pO1xuXG5cbiAqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKi9cblxuXG5cblxuKGZ1bmN0aW9uKCl7XG4gICAgbXcubGliLnJlcXVpcmUoJ2pxdWVyeXVpJyk7XG5cbiAgICBtdy5saWIucmVxdWlyZSgnbmVzdGVkc29ydGFibGUnKTtcblxuXG5cbiAgICB2YXIgbXd0cmVlID0gZnVuY3Rpb24oY29uZmlnKXtcblxuICAgICAgICB2YXIgc2NvcGUgPSB0aGlzO1xuXG4gICAgICAgIHRoaXMuY29uZmlnID0gZnVuY3Rpb24oY29uZmlnKXtcblxuICAgICAgICAgICAgd2luZG93Lm13dHJlZSA9ICh0eXBlb2Ygd2luZG93Lm13dHJlZSA9PT0gJ3VuZGVmaW5lZCcgPyAwIDogd2luZG93Lm13dHJlZSkrMTtcblxuICAgICAgICAgICAgaWYoIWNvbmZpZy5pZCAmJiB0eXBlb2YgY29uZmlnLnNhdmVTdGF0ZSA9PT0gdW5kZWZpbmVkKXtcbiAgICAgICAgICAgICAgICBjb25maWcuc2F2ZVN0YXRlID0gZmFsc2U7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIHZhciBkZWZhdWx0cyA9IHtcbiAgICAgICAgICAgICAgICBkYXRhOltdLFxuICAgICAgICAgICAgICAgIG9wZW5lZENsYXNzOidvcGVuZWQnLFxuICAgICAgICAgICAgICAgIHNlbGVjdGVkQ2xhc3M6J3NlbGVjdGVkJyxcbiAgICAgICAgICAgICAgICBza2luOidkZWZhdWx0JyxcbiAgICAgICAgICAgICAgICBtdWx0aVBhZ2VTZWxlY3Q6dHJ1ZSxcbiAgICAgICAgICAgICAgICBzYXZlU3RhdGU6dHJ1ZSxcbiAgICAgICAgICAgICAgICBzb3J0YWJsZTpmYWxzZSxcbiAgICAgICAgICAgICAgICBuZXN0ZWRTb3J0YWJsZTpmYWxzZSxcbiAgICAgICAgICAgICAgICBzaW5nbGVTZWxlY3Q6ZmFsc2UsXG4gICAgICAgICAgICAgICAgc2VsZWN0ZWREYXRhOltdLFxuICAgICAgICAgICAgICAgIHNraXA6W10sXG4gICAgICAgICAgICAgICAgY29udGV4dE1lbnU6ZmFsc2UsXG4gICAgICAgICAgICAgICAgYXBwZW5kOmZhbHNlLFxuICAgICAgICAgICAgICAgIHByZXBlbmQ6ZmFsc2UsXG4gICAgICAgICAgICAgICAgc2VsZWN0YWJsZTpmYWxzZSxcbiAgICAgICAgICAgICAgICBmaWx0ZXI6ZmFsc2UsXG4gICAgICAgICAgICAgICAgY2FudFNlbGVjdFR5cGVzOiBbXSxcbiAgICAgICAgICAgICAgICBkb2N1bWVudDogZG9jdW1lbnQsXG4gICAgICAgICAgICAgICAgX3RlbXBSZW5kZXI6IHRydWUsXG4gICAgICAgICAgICAgICAgZmlsdGVyUmVtb3RlVVJMOiBudWxsLFxuICAgICAgICAgICAgICAgIGZpbHRlclJlbW90ZUtleTogJ2tleXdvcmQnLFxuICAgICAgICAgICAgfTtcblxuICAgICAgICAgICAgdmFyIG9wdGlvbnMgPSAkLmV4dGVuZCh7fSwgZGVmYXVsdHMsIGNvbmZpZyk7XG5cblxuXG4gICAgICAgICAgICBvcHRpb25zLmVsZW1lbnQgPSBtdy4kKG9wdGlvbnMuZWxlbWVudClbMF07XG4gICAgICAgICAgICBvcHRpb25zLmRhdGEgPSBvcHRpb25zLmRhdGEgfHwgW107XG5cbiAgICAgICAgICAgIHRoaXMub3B0aW9ucyA9IG9wdGlvbnM7XG4gICAgICAgICAgICB0aGlzLmRvY3VtZW50ID0gb3B0aW9ucy5kb2N1bWVudDtcbiAgICAgICAgICAgIHRoaXMuX3NlbGVjdGlvbkNoYW5nZURpc2FibGUgPSBmYWxzZTtcblxuICAgICAgICAgICAgaWYodGhpcy5vcHRpb25zLnNlbGVjdGVkRGF0YSl7XG4gICAgICAgICAgICAgICAgdGhpcy5zZWxlY3RlZERhdGEgPSB0aGlzLm9wdGlvbnMuc2VsZWN0ZWREYXRhO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgZWxzZXtcbiAgICAgICAgICAgICAgICB0aGlzLnNlbGVjdGVkRGF0YSA9IFtdO1xuICAgICAgICAgICAgfVxuICAgICAgICB9O1xuICAgICAgICB0aGlzLmZpbHRlckxvY2FsID0gZnVuY3Rpb24odmFsLCBrZXkpe1xuICAgICAgICAgICAga2V5ID0ga2V5IHx8ICd0aXRsZSc7XG4gICAgICAgICAgICB2YWwgPSAodmFsIHx8ICcnKS50b0xvd2VyQ2FzZSgpLnRyaW0oKTtcbiAgICAgICAgICAgIGlmKCF2YWwpe1xuICAgICAgICAgICAgICAgIHNjb3BlLnNob3dBbGwoKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsc2V7XG4gICAgICAgICAgICAgICAgc2NvcGUub3B0aW9ucy5kYXRhLmZvckVhY2goZnVuY3Rpb24oaXRlbSl7XG4gICAgICAgICAgICAgICAgICAgIGlmKGl0ZW1ba2V5XS50b0xvd2VyQ2FzZSgpLmluZGV4T2YodmFsKSA9PT0gLTEpe1xuICAgICAgICAgICAgICAgICAgICAgICAgc2NvcGUuaGlkZShpdGVtKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICBlbHNle1xuICAgICAgICAgICAgICAgICAgICAgICAgc2NvcGUuc2hvdyhpdGVtKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfVxuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuX2ZpbHRlclJlbW90ZVRpbWUgPSBudWxsO1xuICAgICAgICB0aGlzLmZpbHRlclJlbW90ZSA9IGZ1bmN0aW9uKHZhbCwga2V5KXtcbiAgICAgICAgICAgIGNsZWFyVGltZW91dCh0aGlzLl9maWx0ZXJSZW1vdGVUaW1lKTtcbiAgICAgICAgICAgIHRoaXMuX2ZpbHRlclJlbW90ZVRpbWUgPSBzZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICBrZXkgPSBrZXkgfHwgJ3RpdGxlJztcbiAgICAgICAgICAgICAgICB2YWwgPSAodmFsIHx8ICcnKS50b0xvd2VyQ2FzZSgpLnRyaW0oKTtcbiAgICAgICAgICAgICAgICB2YXIgdHMgPSB7fTtcbiAgICAgICAgICAgICAgICB0c1tzY29wZS5vcHRpb25zLmZpbHRlclJlbW90ZUtleV0gPSB2YWw7XG4gICAgICAgICAgICAgICAgJC5nZXQoc2NvcGUub3B0aW9ucy5maWx0ZXJSZW1vdGVVUkwsIHRzLCBmdW5jdGlvbiAoZGF0YSkge1xuICAgICAgICAgICAgICAgICAgICBzY29wZS5zZXREYXRhKGRhdGEpO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfSwgNzc3KTtcbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLmZpbHRlciA9IGZ1bmN0aW9uKHZhbCwga2V5KXtcbiAgICAgICAgICAgIGlmICghIXRoaXMub3B0aW9ucy5maWx0ZXJSZW1vdGVVUkwgJiYgISF0aGlzLm9wdGlvbnMuZmlsdGVyUmVtb3RlS2V5KSB7XG4gICAgICAgICAgICAgICAgdGhpcy5maWx0ZXJSZW1vdGUodmFsLCBrZXkpO1xuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICB0aGlzLmZpbHRlckxvY2FsKHZhbCwga2V5KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfTtcblxuICAgICAgICB2YXIgX2UgPSB7fTtcblxuICAgICAgICB0aGlzLm9uID0gZnVuY3Rpb24gKGUsIGYpIHsgX2VbZV0gPyBfZVtlXS5wdXNoKGYpIDogKF9lW2VdID0gW2ZdKSB9O1xuICAgICAgICB0aGlzLmRpc3BhdGNoID0gZnVuY3Rpb24gKGUsIGYpIHsgX2VbZV0gPyBfZVtlXS5mb3JFYWNoKGZ1bmN0aW9uIChjKXsgYy5jYWxsKHRoaXMsIGYpOyB9KSA6ICcnOyB9O1xuXG4gICAgICAgIHRoaXMuc2VhcmNoID0gZnVuY3Rpb24oKXtcbiAgICAgICAgICAgIHRoaXMuX3NlYWNoSW5wdXQgPSBtdy4kKHRoaXMub3B0aW9ucy5zZWFyY2hJbnB1dCk7XG4gICAgICAgICAgICBpZighdGhpcy5fc2VhY2hJbnB1dFswXSB8fCB0aGlzLl9zZWFjaElucHV0WzBdLl90cmVlKSByZXR1cm47XG4gICAgICAgICAgICB0aGlzLl9zZWFjaElucHV0WzBdLl90cmVlID0gdGhpcztcbiAgICAgICAgICAgIHZhciBzY29wZSA9IHRoaXM7XG4gICAgICAgICAgICB0aGlzLl9zZWFjaElucHV0Lm9uKCdpbnB1dCcsIGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgc2NvcGUuZmlsdGVyKHRoaXMudmFsdWUpO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgIH07XG4gICAgICAgIHRoaXMuc2tpcCA9IGZ1bmN0aW9uKGl0ZW1EYXRhKXtcbiAgICAgICAgICAgIGlmKHRoaXMub3B0aW9ucy5za2lwICYmIHRoaXMub3B0aW9ucy5za2lwLmxlbmd0aD4wKXtcbiAgICAgICAgICAgICAgICBmb3IoIHZhciBuPTA7IG48c2NvcGUub3B0aW9ucy5za2lwLmxlbmd0aDsgbisrICl7XG4gICAgICAgICAgICAgICAgICAgIHZhciBpdGVtID0gc2NvcGUub3B0aW9ucy5za2lwW25dO1xuICAgICAgICAgICAgICAgICAgICB2YXIgY2FzZTEgPSAoaXRlbS5pZCA9PSBpdGVtRGF0YS5pZCAmJiBpdGVtLnR5cGUgPT0gaXRlbURhdGEudHlwZSk7XG4gICAgICAgICAgICAgICAgICAgIHZhciBjYXNlMiA9IChpdGVtRGF0YS5wYXJlbnRfaWQgPT0gaXRlbS5pZCAmJiBpdGVtLnR5cGUgPT0gaXRlbURhdGEudHlwZSk7XG4gICAgICAgICAgICAgICAgICAgIGlmKGNhc2UxIHx8Y2FzZTIpe1xuICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHRydWU7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgfVxuICAgICAgICB9O1xuICAgICAgICB0aGlzLnByZXBhcmVEYXRhID0gZnVuY3Rpb24oKXtcbiAgICAgICAgICAgIGlmKHR5cGVvZiB0aGlzLm9wdGlvbnMuZmlsdGVyID09PSAnb2JqZWN0Jyl7XG4gICAgICAgICAgICAgICAgdmFyIGZpbmFsID0gW10sIHNjb3BlID0gdGhpcztcbiAgICAgICAgICAgICAgICBmb3IoIHZhciBpIGluIHRoaXMub3B0aW9ucy5maWx0ZXIpe1xuICAgICAgICAgICAgICAgICAgICAkLmVhY2godGhpcy5vcHRpb25zLmRhdGEsIGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgICAgICAgICBpZih0aGlzW2ldICYmIHRoaXNbaV0gPT0gc2NvcGUub3B0aW9ucy5maWx0ZXJbaV0pe1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGZpbmFsLnB1c2godGhpcyk7XG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB0aGlzLm9wdGlvbnMuZGF0YSA9IGZpbmFsO1xuICAgICAgICAgICAgfVxuICAgICAgICB9O1xuXG5cbiAgICAgICAgdGhpcy5fcG9zdENyZWF0ZWQgPSBbXTtcblxuICAgICAgICB0aGlzLmpzb24ydWwgPSBmdW5jdGlvbigpe1xuICAgICAgICAgICAgdGhpcy5saXN0ID0gc2NvcGUuZG9jdW1lbnQuY3JlYXRlRWxlbWVudCggJ3VsJyApO1xuICAgICAgICAgICAgdGhpcy5saXN0Ll90cmVlID0gdGhpcztcbiAgICAgICAgICAgIHRoaXMub3B0aW9ucy5pZCA9IHRoaXMub3B0aW9ucy5pZCB8fCAoICdtdy10cmVlLScgKyB3aW5kb3cubXd0cmVlICk7XG4gICAgICAgICAgICB0aGlzLmxpc3QuaWQgPSB0aGlzLm9wdGlvbnMuaWQ7XG4gICAgICAgICAgICB0aGlzLmxpc3QuY2xhc3NOYW1lID0gJ213LWRlZmF1bHRzIG13LXRyZWUtbmF2IG13LXRyZWUtbmF2LXNraW4tJyArIHRoaXMub3B0aW9ucy5za2luO1xuICAgICAgICAgICAgdGhpcy5saXN0Ll9pZCA9IDA7XG4gICAgICAgICAgICB0aGlzLm9wdGlvbnMuZGF0YS5mb3JFYWNoKGZ1bmN0aW9uKGl0ZW0pe1xuICAgICAgICAgICAgICAgIHZhciBsaXN0ID0gc2NvcGUuZ2V0UGFyZW50KGl0ZW0pO1xuICAgICAgICAgICAgICAgIGlmKGxpc3Qpe1xuICAgICAgICAgICAgICAgICAgICB2YXIgbGkgPSBzY29wZS5jcmVhdGVJdGVtKGl0ZW0pO1xuICAgICAgICAgICAgICAgICAgICBpZihsaSl7XG4gICAgICAgICAgICAgICAgICAgICAgICBsaXN0LmFwcGVuZENoaWxkKGxpKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBlbHNlIGlmKHR5cGVvZiBsaXN0ID09PSAndW5kZWZpbmVkJyl7XG4gICAgICAgICAgICAgICAgICAgIHNjb3BlLl9wb3N0Q3JlYXRlZC5wdXNoKGl0ZW0pO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgaWYodGhpcy5vcHRpb25zLl90ZW1wUmVuZGVyKSB7XG4gICAgICAgICAgICAgICAgdGhpcy50ZW1wUmVuZCgpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9O1xuXG5cblxuICAgICAgICB0aGlzLl90ZW1wUHJlcGFyZSA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIGZvciAodmFyIGk9MDsgaTx0aGlzLl9wb3N0Q3JlYXRlZC5sZW5ndGg7IGkrKykge1xuICAgICAgICAgICAgICAgIHZhciBpdCA9IHRoaXMuX3Bvc3RDcmVhdGVkW2ldO1xuICAgICAgICAgICAgICAgIGlmKGl0LnBhcmVudF9pZCAhPT0gMCkge1xuICAgICAgICAgICAgICAgICAgICB2YXIgaGFzID0gdGhpcy5vcHRpb25zLmRhdGEuZmluZChmdW5jdGlvbiAoYSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIGEuaWQgPT0gIGl0LnBhcmVudF9pZDsgLy8gMSA9PSAnMSdcbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgICAgIGlmKCFoYXMpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGl0LnBhcmVudF9pZCA9IDA7XG4gICAgICAgICAgICAgICAgICAgICAgICBpdC5wYXJlbnRfdHlwZSA9IFwicGFnZVwiO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMudGVtcFJlbmQgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB0aGlzLl90ZW1wUHJlcGFyZSgpXG4gICAgICAgICAgICB2YXIgY3VyciA9IHNjb3BlLl9wb3N0Q3JlYXRlZFswXTtcbiAgICAgICAgICAgIHZhciBtYXggPSAxMDAwMCwgaXRyID0gMDtcblxuICAgICAgICAgICAgd2hpbGUoc2NvcGUuX3Bvc3RDcmVhdGVkLmxlbmd0aCAmJiBpdHI8bWF4KXtcbiAgICAgICAgICAgICAgICBpdHIrKztcbiAgICAgICAgICAgICAgICB2YXIgaW5kZXggPSBzY29wZS5fcG9zdENyZWF0ZWQuaW5kZXhPZihjdXJyKTtcbiAgICAgICAgICAgICAgICB2YXIgc2VsZWN0b3IgPSAnIycgKyBzY29wZS5vcHRpb25zLmlkICsgJy0nICsgY3Vyci50eXBlICsgJy0nICArIGN1cnIuaWQ7XG4gICAgICAgICAgICAgICAgdmFyIGxhc3RjID0gc2VsZWN0b3IuY2hhckF0KHNlbGVjdG9yLmxlbmd0aCAtIDEpO1xuICAgICAgICAgICAgICAgIGlmKCBsYXN0YyA9PT0gJy4nIHx8IGxhc3RjID09PSAnIycpIHtcbiAgICAgICAgICAgICAgICAgICAgc2VsZWN0b3IgPSBzZWxlY3Rvci5zdWJzdHJpbmcoMCwgc2VsZWN0b3IubGVuZ3RoIC0gMSk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIHZhciBpdCA9IG13LiQoc2VsZWN0b3IpWzBdO1xuICAgICAgICAgICAgICAgIGlmKGl0KXtcbiAgICAgICAgICAgICAgICAgICAgc2NvcGUuX3Bvc3RDcmVhdGVkLnNwbGljZShpbmRleCwgMSk7XG4gICAgICAgICAgICAgICAgICAgIGN1cnIgPSBzY29wZS5fcG9zdENyZWF0ZWRbMF07XG4gICAgICAgICAgICAgICAgICAgIGNvbnRpbnVlO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB2YXIgbGlzdCA9IHNjb3BlLmdldFBhcmVudChjdXJyKTtcblxuICAgICAgICAgICAgICAgIGlmKGxpc3QgJiYgISQoc2VsZWN0b3IpWzBdKXtcbiAgICAgICAgICAgICAgICAgICAgdmFyIGxpID0gc2NvcGUuY3JlYXRlSXRlbShjdXJyKTtcbiAgICAgICAgICAgICAgICAgICAgaWYobGkpe1xuICAgICAgICAgICAgICAgICAgICAgICAgbGlzdC5hcHBlbmRDaGlsZChsaSk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgc2NvcGUuX3Bvc3RDcmVhdGVkLnNwbGljZShpbmRleCwgMSk7XG4gICAgICAgICAgICAgICAgICAgIGN1cnIgPSBzY29wZS5fcG9zdENyZWF0ZWRbMF07XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGVsc2UgaWYodHlwZW9mIGxpc3QgPT09ICd1bmRlZmluZWQnKXtcbiAgICAgICAgICAgICAgICAgICAgY3VyciA9IHNjb3BlLl9wb3N0Q3JlYXRlZFtpbmRleCsxXSB8fCBzY29wZS5fcG9zdENyZWF0ZWRbMF07XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuXG4gICAgICAgIH07XG5cbiAgICAgICAgZnVuY3Rpb24gdHJpZ2dlckNoYW5nZSgpIHtcbiAgICAgICAgICAgIGlmKCF0aGlzLl9zZWxlY3Rpb25DaGFuZ2VEaXNhYmxlKSB7XG4gICAgICAgICAgICAgICAgbXcuJChzY29wZSkudHJpZ2dlcignc2VsZWN0aW9uQ2hhbmdlJywgW3Njb3BlLnNlbGVjdGVkRGF0YV0pO1xuICAgICAgICAgICAgICAgIHNjb3BlLmRpc3BhdGNoKCdzZWxlY3Rpb25DaGFuZ2UnLCBzY29wZS5zZWxlY3RlZERhdGEpXG4gICAgICAgICAgICB9XG4gICAgICAgIH1cblxuICAgICAgICB0aGlzLnNldERhdGEgPSBmdW5jdGlvbihuZXdEYXRhKXtcbiAgICAgICAgICAgIHRoaXMub3B0aW9ucy5kYXRhID0gbmV3RGF0YTtcbiAgICAgICAgICAgIHRoaXMuX3Bvc3RDcmVhdGVkID0gW107XG4gICAgICAgICAgICB0aGlzLl9pZHMgPSBbXTtcbiAgICAgICAgICAgIHRoaXMuaW5pdCgpO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuc2F2ZVN0YXRlID0gZnVuY3Rpb24oKXtcbiAgICAgICAgICAgIGlmKCF0aGlzLm9wdGlvbnMuc2F2ZVN0YXRlKSByZXR1cm47XG4gICAgICAgICAgICB2YXIgZGF0YSA9IFtdO1xuICAgICAgICAgICAgbXcuJCggJ2xpLicgKyB0aGlzLm9wdGlvbnMub3BlbmVkQ2xhc3MsIHRoaXMubGlzdCAgKS5lYWNoKGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgaWYodGhpcy5fZGF0YSkge1xuICAgICAgICAgICAgICAgICAgICBkYXRhLnB1c2goe3R5cGU6dGhpcy5fZGF0YS50eXBlLCBpZDp0aGlzLl9kYXRhLmlkfSlcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgbXcuc3RvcmFnZS5zZXQodGhpcy5vcHRpb25zLmlkLCBkYXRhKTtcbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLnJlc3RvcmVTdGF0ZSA9IGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICBpZighdGhpcy5vcHRpb25zLnNhdmVTdGF0ZSkgcmV0dXJuO1xuICAgICAgICAgICAgdmFyIGRhdGEgPSBtdy5zdG9yYWdlLmdldCh0aGlzLm9wdGlvbnMuaWQpO1xuICAgICAgICAgICAgaWYoIWRhdGEpIHJldHVybjtcbiAgICAgICAgICAgIHRyeXtcbiAgICAgICAgICAgICAgICAkLmVhY2goZGF0YSwgZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAgICAgaWYodHlwZW9mIHRoaXMuaWQgPT09ICdzdHJpbmcnKXtcbiAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMuaWQgPSBwYXJzZUludCh0aGlzLmlkLCAxMCk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgc2NvcGUub3Blbih0aGlzLmlkLCB0aGlzLnR5cGUpO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgY2F0Y2goZSl7IH1cbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLm1hbmFnZVVuc2VsZWN0ZWQgPSBmdW5jdGlvbigpe1xuICAgICAgICAgICAgbXcuJCgnaW5wdXQ6bm90KDpjaGVja2VkKScsIHRoaXMubGlzdCkuZWFjaChmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgIHZhciBsaSA9IHNjb3BlLnBhcmVudExpKHRoaXMpO1xuICAgICAgICAgICAgICAgIG13LiQobGkpLnJlbW92ZUNsYXNzKHNjb3BlLm9wdGlvbnMuc2VsZWN0ZWRDbGFzcylcbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuYW5hbGl6ZUxpID0gZnVuY3Rpb24obGkpe1xuICAgICAgICAgICAgaWYodHlwZW9mIGxpID09PSAnc3RyaW5nJyl7XG4gICAgICAgICAgICAgICAgbGkgPSBkZWNvZGVVUklDb21wb25lbnQobGkpLnRyaW0oKTtcbiAgICAgICAgICAgICAgICBpZigvXlxcZCskLy50ZXN0KGxpKSl7XG4gICAgICAgICAgICAgICAgICAgIGxpID0gcGFyc2VJbnQobGksIDEwKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgZWxzZXtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIG13LiQobGkpWzBdO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHJldHVybiBsaTtcbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLnNlbGVjdCA9IGZ1bmN0aW9uKGxpLCB0eXBlKXtcbiAgICAgICAgICAgIGlmKEFycmF5LmlzQXJyYXkobGkpKXtcbiAgICAgICAgICAgICAgICAkLmVhY2gobGksIGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgICAgIHNjb3BlLnNlbGVjdCh0aGlzKTtcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICByZXR1cm47XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBsaSA9IHRoaXMuZ2V0KGxpLCB0eXBlKTtcbiAgICAgICAgICAgIGlmKGxpICYmIHRoaXMub3B0aW9ucy5jYW50U2VsZWN0VHlwZXMuaW5kZXhPZihsaS5kYXRhc2V0LnR5cGUpID09PSAtMSl7XG4gICAgICAgICAgICAgICAgbGkuY2xhc3NMaXN0LmFkZCh0aGlzLm9wdGlvbnMuc2VsZWN0ZWRDbGFzcyk7XG4gICAgICAgICAgICAgICAgdmFyIGlucHV0ID0gbGkucXVlcnlTZWxlY3RvcignaW5wdXQnKTtcbiAgICAgICAgICAgICAgICBpZihpbnB1dCkgaW5wdXQuY2hlY2tlZCA9IHRydWU7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIHRoaXMubWFuYWdlVW5zZWxlY3RlZCgpO1xuICAgICAgICAgICAgdGhpcy5nZXRTZWxlY3RlZCgpO1xuICAgICAgICAgICAgdHJpZ2dlckNoYW5nZSgpO1xuICAgICAgICB9O1xuXG5cblxuICAgICAgICB0aGlzLnVuc2VsZWN0ID0gZnVuY3Rpb24obGksIHR5cGUpe1xuICAgICAgICAgICAgaWYoQXJyYXkuaXNBcnJheShsaSkpe1xuICAgICAgICAgICAgICAgICQuZWFjaChsaSwgZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAgICAgc2NvcGUudW5zZWxlY3QodGhpcyk7XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgbGkgPSB0aGlzLmdldChsaSwgdHlwZSk7XG4gICAgICAgICAgICBpZihsaSl7XG4gICAgICAgICAgICAgICAgbGkuY2xhc3NMaXN0LnJlbW92ZSh0aGlzLm9wdGlvbnMuc2VsZWN0ZWRDbGFzcyk7XG4gICAgICAgICAgICAgICAgdmFyIGlucHV0ID0gbGkucXVlcnlTZWxlY3RvcignaW5wdXQnKTtcbiAgICAgICAgICAgICAgICBpZihpbnB1dCkgaW5wdXQuY2hlY2tlZCA9IGZhbHNlO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgdGhpcy5tYW5hZ2VVbnNlbGVjdGVkKCk7XG4gICAgICAgICAgICB0aGlzLmdldFNlbGVjdGVkKCk7XG4gICAgICAgICAgICB0cmlnZ2VyQ2hhbmdlKCk7XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5nZXQgPSBmdW5jdGlvbihsaSwgdHlwZSl7XG4gICAgICAgICAgICBpZih0eXBlb2YgbGkgPT09ICd1bmRlZmluZWQnKSByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICBpZihsaSA9PT0gbnVsbCkgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgaWYobGkubm9kZVR5cGUpIHJldHVybiBsaTtcbiAgICAgICAgICAgIGxpID0gdGhpcy5hbmFsaXplTGkobGkpO1xuICAgICAgICAgICAgaWYodHlwZW9mIGxpID09PSAnb2JqZWN0JyAmJiB0eXBlb2YgbGkuaWQgIT09ICd1bmRlZmluZWQnKXtcbiAgICAgICAgICAgICAgICByZXR1cm4gdGhpcy5nZXQobGkuaWQsIGxpLnR5cGUpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgaWYodHlwZW9mIGxpID09PSAnb2JqZWN0JyAmJiBsaS5jb25zdHJ1Y3RvciA9PT0gTnVtYmVyKXtcbiAgICAgICAgICAgICAgICBsaSA9IHBhcnNlSW50KGxpKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGlmKHR5cGVvZiBsaSA9PT0gJ251bWJlcicpe1xuICAgICAgICAgICAgICAgIGlmKCF0eXBlKSByZXR1cm47XG4gICAgICAgICAgICAgICAgcmV0dXJuIHRoaXMubGlzdC5xdWVyeVNlbGVjdG9yKCdsaVtkYXRhLXR5cGU9XCInK3R5cGUrJ1wiXVtkYXRhLWlkPVwiJytsaSsnXCJdJyk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBpZih0eXBlb2YgbGkgPT09ICdzdHJpbmcnICYmIC9eXFxkKyQvLnRlc3QobGkpKXtcbiAgICAgICAgICAgICAgICBpZighdHlwZSkgcmV0dXJuO1xuICAgICAgICAgICAgICAgIHJldHVybiB0aGlzLmxpc3QucXVlcnlTZWxlY3RvcignbGlbZGF0YS10eXBlPVwiJyt0eXBlKydcIl1bZGF0YS1pZD1cIicrbGkrJ1wiXScpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgLy9pZighbGkpIHtjb25zb2xlLndhcm4oJ0xpc3QgaXRlbSBub3QgZGVmaW5lZDonLCBsaSwgdHlwZSl9XG4gICAgICAgICAgICByZXR1cm4gbGk7XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5pc1NlbGVjdGVkID0gZnVuY3Rpb24obGksIHR5cGUpe1xuICAgICAgICAgICAgbGkgPSB0aGlzLmdldChsaSwgdHlwZSk7XG4gICAgICAgICAgICBpZighbGkpIHJldHVybjtcbiAgICAgICAgICAgIHZhciBpbnB1dCA9IGxpLnF1ZXJ5U2VsZWN0b3IoJ2lucHV0Jyk7XG4gICAgICAgICAgICBpZighaW5wdXQpIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgIHJldHVybiBpbnB1dC5jaGVja2VkID09PSB0cnVlO1xuICAgICAgICB9O1xuICAgICAgICB0aGlzLnRvZ2dsZVNlbGVjdCA9IGZ1bmN0aW9uKGxpLCB0eXBlKXtcbiAgICAgICAgICAgIGlmKHRoaXMuaXNTZWxlY3RlZChsaSwgdHlwZSkpe1xuICAgICAgICAgICAgICAgIHRoaXMudW5zZWxlY3QobGksIHR5cGUpXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBlbHNle1xuICAgICAgICAgICAgICAgIHRoaXMuc2VsZWN0KGxpLCB0eXBlKVxuICAgICAgICAgICAgfVxuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuc2VsZWN0QWxsID0gZnVuY3Rpb24oKXtcbiAgICAgICAgICAgIHRoaXMuX3NlbGVjdGlvbkNoYW5nZURpc2FibGUgPSB0cnVlO1xuICAgICAgICAgICAgdGhpcy5zZWxlY3QodGhpcy5vcHRpb25zLmRhdGEpO1xuICAgICAgICAgICAgdGhpcy5fc2VsZWN0aW9uQ2hhbmdlRGlzYWJsZSA9IGZhbHNlO1xuICAgICAgICAgICAgdHJpZ2dlckNoYW5nZSgpXG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy51bnNlbGVjdEFsbCA9IGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICB0aGlzLl9zZWxlY3Rpb25DaGFuZ2VEaXNhYmxlID0gdHJ1ZTtcbiAgICAgICAgICAgIHRoaXMudW5zZWxlY3QodGhpcy5zZWxlY3RlZERhdGEpO1xuICAgICAgICAgICAgdGhpcy5fc2VsZWN0aW9uQ2hhbmdlRGlzYWJsZSA9IGZhbHNlO1xuICAgICAgICAgICAgdHJpZ2dlckNoYW5nZSgpXG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5vcGVuID0gZnVuY3Rpb24obGksIHR5cGUsIF9za2lwc2F2ZSl7XG4gICAgICAgICAgICBpZihBcnJheS5pc0FycmF5KGxpKSl7XG4gICAgICAgICAgICAgICAgJC5lYWNoKGxpLCBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICAgICBzY29wZS5vcGVuKHRoaXMpO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGxpID0gdGhpcy5nZXQobGksIHR5cGUpO1xuICAgICAgICAgICAgaWYoIWxpKSByZXR1cm47XG4gICAgICAgICAgICBsaS5jbGFzc0xpc3QuYWRkKHRoaXMub3B0aW9ucy5vcGVuZWRDbGFzcyk7XG4gICAgICAgICAgICBpZighX3NraXBzYXZlKSB0aGlzLnNhdmVTdGF0ZSgpO1xuICAgICAgICB9O1xuICAgICAgICB0aGlzLnNob3cgPSBmdW5jdGlvbihsaSwgdHlwZSl7XG4gICAgICAgICAgICBpZihBcnJheS5pc0FycmF5KGxpKSl7XG4gICAgICAgICAgICAgICAgJC5lYWNoKGxpLCBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICAgICBzY29wZS5zaG93KHRoaXMpO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGxpID0gdGhpcy5nZXQobGksIHR5cGUpO1xuICAgICAgICAgICAgaWYoIWxpKSByZXR1cm47XG4gICAgICAgICAgICBsaS5jbGFzc0xpc3QucmVtb3ZlKCdtdy10cmVlLWl0ZW0taGlkZGVuJyk7XG4gICAgICAgICAgICBtdy4kKGxpKS5wYXJlbnRzKFwiLm13LXRyZWUtaXRlbS1oaWRkZW5cIikucmVtb3ZlQ2xhc3MoJ213LXRyZWUtaXRlbS1oaWRkZW4nKS5lYWNoKGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgc2NvcGUub3Blbih0aGlzKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuc2hvd0FsbCA9IGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICBtdy4kKHRoaXMubGlzdC5xdWVyeVNlbGVjdG9yQWxsKCdsaScpKS5yZW1vdmVDbGFzcygnbXctdHJlZS1pdGVtLWhpZGRlbicpO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuaGlkZSA9IGZ1bmN0aW9uKGxpLCB0eXBlKXtcbiAgICAgICAgICAgIGlmKEFycmF5LmlzQXJyYXkobGkpKXtcbiAgICAgICAgICAgICAgICAkLmVhY2gobGksIGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgICAgIHNjb3BlLmhpZGUodGhpcyk7XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgbGkgPSB0aGlzLmdldChsaSwgdHlwZSk7XG4gICAgICAgICAgICBpZighbGkpIHJldHVybjtcbiAgICAgICAgICAgIGxpLmNsYXNzTGlzdC5hZGQoJ213LXRyZWUtaXRlbS1oaWRkZW4nKTtcbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLmhpZGVBbGwgPSBmdW5jdGlvbigpe1xuICAgICAgICAgICAgbXcuJCh0aGlzLmxpc3QucXVlcnlTZWxlY3RvckFsbCgnbGknKSkuYWRkQ2xhc3MoJ213LXRyZWUtaXRlbS1oaWRkZW4nKTtcbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLmNsb3NlID0gZnVuY3Rpb24obGksdHlwZSwgX3NraXBzYXZlKXtcbiAgICAgICAgICAgIGlmKEFycmF5LmlzQXJyYXkobGkpKXtcbiAgICAgICAgICAgICAgICAkLmVhY2gobGksIGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgICAgIHNjb3BlLmNsb3NlKHRoaXMpO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGxpID0gdGhpcy5nZXQobGksIHR5cGUpO1xuICAgICAgICAgICAgaWYoIWxpKSByZXR1cm47XG4gICAgICAgICAgICBsaS5jbGFzc0xpc3QucmVtb3ZlKHRoaXMub3B0aW9ucy5vcGVuZWRDbGFzcyk7XG4gICAgICAgICAgICBpZighX3NraXBzYXZlKSB0aGlzLnNhdmVTdGF0ZSgpO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMudG9nZ2xlID0gZnVuY3Rpb24obGksIHR5cGUpe1xuICAgICAgICAgICAgbGkgPSB0aGlzLmdldChsaSwgdHlwZSk7XG4gICAgICAgICAgICBpZighbGkpIHJldHVybjtcbiAgICAgICAgICAgIGxpLmNsYXNzTGlzdC50b2dnbGUodGhpcy5vcHRpb25zLm9wZW5lZENsYXNzKTtcbiAgICAgICAgICAgIHRoaXMuc2F2ZVN0YXRlKCk7XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5vcGVuQWxsID0gZnVuY3Rpb24oKXtcbiAgICAgICAgICAgIHZhciBhbGwgPSB0aGlzLmxpc3QucXVlcnlTZWxlY3RvckFsbCgnbGknKTtcbiAgICAgICAgICAgIG13LiQoYWxsKS5lYWNoKGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgc2NvcGUub3Blbih0aGlzLCB1bmRlZmluZWQsIHRydWUpO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB0aGlzLnNhdmVTdGF0ZSgpO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuY2xvc2VBbGwgPSBmdW5jdGlvbigpe1xuICAgICAgICAgICAgdmFyIGFsbCA9IHRoaXMubGlzdC5xdWVyeVNlbGVjdG9yQWxsKCdsaS4nK3RoaXMub3B0aW9ucy5vcGVuZWRDbGFzcyk7XG4gICAgICAgICAgICBtdy4kKGFsbCkuZWFjaChmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgIHNjb3BlLmNsb3NlKHRoaXMsIHVuZGVmaW5lZCwgdHJ1ZSk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIHRoaXMuc2F2ZVN0YXRlKCk7XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5idXR0b24gPSBmdW5jdGlvbigpe1xuICAgICAgICAgICAgdmFyIGJ0biA9IHNjb3BlLmRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ213YnV0dG9uJyk7XG4gICAgICAgICAgICBidG4uY2xhc3NOYW1lID0gJ213LXRyZWUtdG9nZ2xlcic7XG4gICAgICAgICAgICBidG4ub25jbGljayA9IGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgc2NvcGUudG9nZ2xlKG13LnRvb2xzLmZpcnN0UGFyZW50V2l0aFRhZyh0aGlzLCAnbGknKSk7XG4gICAgICAgICAgICB9O1xuICAgICAgICAgICAgcmV0dXJuIGJ0bjtcbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLmFkZEJ1dHRvbnMgPSBmdW5jdGlvbigpe1xuICAgICAgICAgICAgdmFyIGFsbCA9IHRoaXMubGlzdC5xdWVyeVNlbGVjdG9yQWxsKCdsaSB1bC5wcmUtaW5pdCcpLCBpPTA7XG4gICAgICAgICAgICBmb3IoIDsgaTxhbGwubGVuZ3RoOyBpKysgKXtcbiAgICAgICAgICAgICAgICB2YXIgdWwgPSBhbGxbaV07XG4gICAgICAgICAgICAgICAgdWwuY2xhc3NMaXN0LnJlbW92ZSgncHJlLWluaXQnKTtcbiAgICAgICAgICAgICAgICBtdy4kKHVsKS5wYXJlbnQoKS5jaGlsZHJlbignLm13LXRyZWUtaXRlbS1jb250ZW50LXJvb3QnKS5wcmVwZW5kKHRoaXMuYnV0dG9uKCkpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuY2hlY2tCb3ggPSBmdW5jdGlvbihlbGVtZW50KXtcbiAgICAgICAgICAgIGlmKHRoaXMub3B0aW9ucy5jYW50U2VsZWN0VHlwZXMuaW5kZXhPZihlbGVtZW50LmRhdGFzZXQudHlwZSkgIT09IC0xKXtcbiAgICAgICAgICAgICAgICByZXR1cm4gc2NvcGUuZG9jdW1lbnQuY3JlYXRlVGV4dE5vZGUoJycpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgdmFyIGl0eXBlID0gJ3JhZGlvJztcbiAgICAgICAgICAgIGlmKHRoaXMub3B0aW9ucy5zaW5nbGVTZWxlY3Qpe1xuXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBlbHNlIGlmKHRoaXMub3B0aW9ucy5tdWx0aVBhZ2VTZWxlY3QgfHwgZWxlbWVudC5fZGF0YS50eXBlICE9PSAncGFnZScpe1xuICAgICAgICAgICAgICAgIGl0eXBlID0gJ2NoZWNrYm94JztcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHZhciBsYWJlbCA9IHNjb3BlLmRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ3RyZWUtbGFiZWwnKTtcbiAgICAgICAgICAgIHZhciBpbnB1dCA9IHNjb3BlLmRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2lucHV0Jyk7XG4gICAgICAgICAgICB2YXIgc3BhbiA9IHNjb3BlLmRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ3NwYW4nKTtcbiAgICAgICAgICAgIGlucHV0LnR5cGUgPSBpdHlwZTtcbiAgICAgICAgICAgIGlucHV0Ll9kYXRhID0gZWxlbWVudC5fZGF0YTtcbiAgICAgICAgICAgIGlucHV0LnZhbHVlID0gZWxlbWVudC5fZGF0YS5pZDtcbiAgICAgICAgICAgIGlucHV0Lm5hbWUgPSB0aGlzLmxpc3QuaWQ7XG4gICAgICAgICAgICBsYWJlbC5jbGFzc05hbWUgPSAnbXctdWktY2hlY2snO1xuXG4gICAgICAgICAgICBsYWJlbC5hcHBlbmRDaGlsZChpbnB1dCk7XG5cblxuICAgICAgICAgICAgbGFiZWwuYXBwZW5kQ2hpbGQoc3Bhbik7XG5cbiAgICAgICAgICAgIC8qaW5wdXQub25jaGFuZ2UgPSBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgIHZhciBsaSA9IHNjb3BlLnBhcmVudExpKHRoaXMpO1xuICAgICAgICAgICAgICAgIG13LiQobGkpW3RoaXMuY2hlY2tlZD8nYWRkQ2xhc3MnOidyZW1vdmVDbGFzcyddKHNjb3BlLm9wdGlvbnMuc2VsZWN0ZWRDbGFzcylcbiAgICAgICAgICAgICAgICB2YXIgZGF0YSA9IHNjb3BlLmdldFNlbGVjdGVkKCk7XG4gICAgICAgICAgICAgICAgc2NvcGUubWFuYWdlVW5zZWxlY3RlZCgpXG4gICAgICAgICAgICAgICAgbXcuJChzY29wZSkudHJpZ2dlcignY2hhbmdlJywgW2RhdGFdKTtcbiAgICAgICAgICAgIH0qL1xuICAgICAgICAgICAgcmV0dXJuIGxhYmVsO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMucGFyZW50TGkgPSBmdW5jdGlvbihzY29wZSl7XG4gICAgICAgICAgICBpZighc2NvcGUpIHJldHVybjtcbiAgICAgICAgICAgIGlmKHNjb3BlLm5vZGVOYW1lID09PSAnTEknKXtcbiAgICAgICAgICAgICAgICByZXR1cm4gc2NvcGU7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICB3aGlsZShzY29wZS5wYXJlbnROb2RlKXtcbiAgICAgICAgICAgICAgICBzY29wZSA9IHNjb3BlLnBhcmVudE5vZGU7XG4gICAgICAgICAgICAgICAgaWYoc2NvcGUubm9kZU5hbWUgPT09ICdMSScpe1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gc2NvcGU7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuZ2V0U2VsZWN0ZWQgPSBmdW5jdGlvbigpe1xuICAgICAgICAgICAgdmFyIHNlbGVjdGVkID0gW107XG4gICAgICAgICAgICB2YXIgYWxsID0gdGhpcy5saXN0LnF1ZXJ5U2VsZWN0b3JBbGwoJ2xpLnNlbGVjdGVkJyk7XG4gICAgICAgICAgICBtdy4kKGFsbCkuZWFjaChmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgIGlmKHRoaXMuX2RhdGEpIHNlbGVjdGVkLnB1c2godGhpcy5fZGF0YSk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIHRoaXMuc2VsZWN0ZWREYXRhID0gc2VsZWN0ZWQ7XG4gICAgICAgICAgICB0aGlzLm9wdGlvbnMuc2VsZWN0ZWREYXRhID0gc2VsZWN0ZWQ7XG4gICAgICAgICAgICByZXR1cm4gc2VsZWN0ZWQ7XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5kZWNvcmF0ZSA9IGZ1bmN0aW9uKGVsZW1lbnQpe1xuICAgICAgICAgICAgaWYodGhpcy5vcHRpb25zLnNlbGVjdGFibGUpe1xuICAgICAgICAgICAgICAgIG13LiQoZWxlbWVudC5xdWVyeVNlbGVjdG9yKCcubXctdHJlZS1pdGVtLWNvbnRlbnQnKSkucHJlcGVuZCh0aGlzLmNoZWNrQm94KGVsZW1lbnQpKVxuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBlbGVtZW50LnF1ZXJ5U2VsZWN0b3IoJy5tdy10cmVlLWl0ZW0tY29udGVudCcpLmFwcGVuZENoaWxkKHRoaXMuY29udGV4dE1lbnUoZWxlbWVudCkpO1xuXG4gICAgICAgICAgICBpZih0aGlzLm9wdGlvbnMuc29ydGFibGUpe1xuICAgICAgICAgICAgICAgIHRoaXMuc29ydGFibGUoKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGlmKHRoaXMub3B0aW9ucy5uZXN0ZWRTb3J0YWJsZSl7XG4gICAgICAgICAgICAgICAgdGhpcy5uZXN0ZWRTb3J0YWJsZSgpO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5zb3J0YWJsZSA9IGZ1bmN0aW9uKGVsZW1lbnQpe1xuICAgICAgICAgICAgdmFyIGl0ZW1zID0gbXcuJCh0aGlzLmxpc3QpO1xuICAgICAgICAgICAgbXcuJCgndWwnLCB0aGlzLmxpc3QpLmVhY2goZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIGl0ZW1zLnB1c2godGhpcyk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIGl0ZW1zLnNvcnRhYmxlKHtcbiAgICAgICAgICAgICAgICBpdGVtczogXCIudHlwZS1jYXRlZ29yeSwgLnR5cGUtcGFnZVwiLFxuICAgICAgICAgICAgICAgIGF4aXM6J3knLFxuICAgICAgICAgICAgICAgIGxpc3RUeXBlOid1bCcsXG4gICAgICAgICAgICAgICAgaGFuZGxlOicubXctdHJlZS1pdGVtLXRpdGxlJyxcbiAgICAgICAgICAgICAgICB1cGRhdGU6ZnVuY3Rpb24oZSwgdWkpe1xuICAgICAgICAgICAgICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgICAgICAgICB2YXIgb2xkID0gJC5leHRlbmQoe30sdWkuaXRlbVswXS5fZGF0YSk7XG4gICAgICAgICAgICAgICAgICAgICAgICB2YXIgb2JqID0gdWkuaXRlbVswXS5fZGF0YTtcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciBvYmpQYXJlbnQgPSB1aS5pdGVtWzBdLnBhcmVudE5vZGUucGFyZW50Tm9kZS5fZGF0YTtcbiAgICAgICAgICAgICAgICAgICAgICAgIHVpLml0ZW1bMF0uZGF0YXNldC5wYXJlbnRfaWQgPSBvYmpQYXJlbnQgPyBvYmpQYXJlbnQuaWQgOiAwO1xuXG4gICAgICAgICAgICAgICAgICAgICAgICBvYmoucGFyZW50X2lkID0gb2JqUGFyZW50ID8gb2JqUGFyZW50LmlkIDogMDtcbiAgICAgICAgICAgICAgICAgICAgICAgIG9iai5wYXJlbnRfdHlwZSA9IG9ialBhcmVudCA/IG9ialBhcmVudC5pZCA6ICdwYWdlJztcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciBuZXdkYXRhID0gW107XG4gICAgICAgICAgICAgICAgICAgICAgICBtdy4kKCdsaScsIHNjb3BlLmxpc3QpLmVhY2goZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZih0aGlzLl9kYXRhKSBuZXdkYXRhLnB1c2godGhpcy5fZGF0YSlcbiAgICAgICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgICAgICAgICAgc2NvcGUub3B0aW9ucy5kYXRhID0gbmV3ZGF0YTtcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciBsb2NhbCA9IFtdO1xuICAgICAgICAgICAgICAgICAgICAgICAgbXcuJCh1aS5pdGVtWzBdLnBhcmVudE5vZGUpLmNoaWxkcmVuKCdsaScpLmVhY2goZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZih0aGlzLl9kYXRhKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGxvY2FsLnB1c2godGhpcy5fZGF0YS5pZCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgICAgICAgICAvLyQoc2NvcGUubGlzdCkucmVtb3ZlKCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAvL3Njb3BlLmluaXQoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13LiQoc2NvcGUpLnRyaWdnZXIoJ29yZGVyQ2hhbmdlJywgW29iaiwgc2NvcGUub3B0aW9ucy5kYXRhLCBvbGQsIGxvY2FsXSlcbiAgICAgICAgICAgICAgICAgICAgfSwgMTEwKTtcblxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9O1xuICAgICAgICB0aGlzLm5lc3RlZFNvcnRhYmxlID0gZnVuY3Rpb24oZWxlbWVudCl7XG4gICAgICAgICAgICBtdy4kKCd1bCcsIHRoaXMubGlzdCkubmVzdGVkU29ydGFibGUoe1xuICAgICAgICAgICAgICAgIGl0ZW1zOiBcIi50eXBlLWNhdGVnb3J5XCIsXG4gICAgICAgICAgICAgICAgbGlzdFR5cGU6J3VsJyxcbiAgICAgICAgICAgICAgICBoYW5kbGU6Jy5tdy10cmVlLWl0ZW0tdGl0bGUnLFxuICAgICAgICAgICAgICAgIHVwZGF0ZTpmdW5jdGlvbihlLCB1aSl7XG5cbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KVxuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuY29udGV4dE1lbnUgPSBmdW5jdGlvbihlbGVtZW50KXtcbiAgICAgICAgICAgIHZhciBtZW51ID0gc2NvcGUuZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnc3BhbicpO1xuICAgICAgICAgICAgbWVudS5jbGFzc05hbWUgPSAnbXctdHJlZS1jb250ZXh0LW1lbnUnO1xuICAgICAgICAgICAgaWYodGhpcy5vcHRpb25zLmNvbnRleHRNZW51KXtcbiAgICAgICAgICAgICAgICAkLmVhY2godGhpcy5vcHRpb25zLmNvbnRleHRNZW51LCBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICAgICB2YXIgbWVudWl0ZW0gPSBzY29wZS5kb2N1bWVudC5jcmVhdGVFbGVtZW50KCdzcGFuJyk7XG4gICAgICAgICAgICAgICAgICAgIHZhciBpY29uID0gc2NvcGUuZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnc3BhbicpO1xuICAgICAgICAgICAgICAgICAgICBtZW51aXRlbS50aXRsZSA9IHRoaXMudGl0bGU7XG4gICAgICAgICAgICAgICAgICAgIG1lbnVpdGVtLmNsYXNzTmFtZSA9ICdtdy10cmVlLWNvbnRleHQtbWVudS1pdGVtJztcbiAgICAgICAgICAgICAgICAgICAgaWNvbi5jbGFzc05hbWUgPSB0aGlzLmljb247XG4gICAgICAgICAgICAgICAgICAgIG1lbnVpdGVtLmFwcGVuZENoaWxkKGljb24pO1xuICAgICAgICAgICAgICAgICAgICBtZW51LmFwcGVuZENoaWxkKG1lbnVpdGVtKTtcbiAgICAgICAgICAgICAgICAgICAgKGZ1bmN0aW9uKG1lbnVpdGVtLCBlbGVtZW50LCBvYmope1xuICAgICAgICAgICAgICAgICAgICAgICAgbWVudWl0ZW0ub25jbGljayA9IGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgaWYob2JqLmFjdGlvbil7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIG9iai5hY3Rpb24uY2FsbChlbGVtZW50LCBlbGVtZW50LCBlbGVtZW50Ll9kYXRhLCBtZW51aXRlbSlcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIH0pKG1lbnVpdGVtLCBlbGVtZW50LCB0aGlzKTtcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHJldHVybiBtZW51XG5cbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLnJlbmQgPSBmdW5jdGlvbigpe1xuICAgICAgICAgICAgaWYodGhpcy5vcHRpb25zLmVsZW1lbnQpe1xuICAgICAgICAgICAgICAgIHZhciBlbCA9IG13LiQodGhpcy5vcHRpb25zLmVsZW1lbnQpO1xuICAgICAgICAgICAgICAgIGlmKGVsLmxlbmd0aCE9PTApe1xuICAgICAgICAgICAgICAgICAgICBlbC5lbXB0eSgpLmFwcGVuZCh0aGlzLmxpc3QpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLl9pZHMgPSBbXTtcblxuICAgICAgICB0aGlzLl9jcmVhdGVTaW5nbGUgPSBmdW5jdGlvbiAoaXRlbSkge1xuXG4gICAgICAgIH1cblxuICAgICAgICB0aGlzLmNyZWF0ZUl0ZW0gPSBmdW5jdGlvbihpdGVtKXtcbiAgICAgICAgICAgIHZhciBzZWxlY3RvciA9ICcjJytzY29wZS5vcHRpb25zLmlkICsgJy0nICsgaXRlbS50eXBlKyctJytpdGVtLmlkO1xuICAgICAgICAgICAgaWYodGhpcy5faWRzLmluZGV4T2Yoc2VsZWN0b3IpICE9PSAtMSkgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgdGhpcy5faWRzLnB1c2goc2VsZWN0b3IpO1xuICAgICAgICAgICAgdmFyIGxpID0gc2NvcGUuZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnbGknKTtcbiAgICAgICAgICAgIGxpLmRhdGFzZXQudHlwZSA9IGl0ZW0udHlwZTtcbiAgICAgICAgICAgIGxpLmRhdGFzZXQuaWQgPSBpdGVtLmlkO1xuICAgICAgICAgICAgbGkuZGF0YXNldC5wYXJlbnRfaWQgPSBpdGVtLnBhcmVudF9pZDtcbiAgICAgICAgICAgIHZhciBza2lwID0gdGhpcy5za2lwKGl0ZW0pO1xuICAgICAgICAgICAgbGkuY2xhc3NOYW1lID0gJ3R5cGUtJyArIGl0ZW0udHlwZSArICcgc3VidHlwZS0nKyBpdGVtLnN1YnR5cGUgKyAnIHNraXAtJyArIChza2lwIHx8ICdub25lJyk7XG4gICAgICAgICAgICB2YXIgY29udGFpbmVyID0gc2NvcGUuZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnc3BhbicpO1xuICAgICAgICAgICAgY29udGFpbmVyLmNsYXNzTmFtZSA9IFwibXctdHJlZS1pdGVtLWNvbnRlbnRcIjtcbiAgICAgICAgICAgIGNvbnRhaW5lci5pbm5lckhUTUwgPSAnPHNwYW4gY2xhc3M9XCJtdy10cmVlLWl0ZW0tdGl0bGVcIj4nK2l0ZW0udGl0bGUrJzwvc3Bhbj4nO1xuXG4gICAgICAgICAgICBsaS5fZGF0YSA9IGl0ZW07XG4gICAgICAgICAgICBsaS5pZCA9IHNjb3BlLm9wdGlvbnMuaWQgKyAnLScgKyBpdGVtLnR5cGUrJy0nK2l0ZW0uaWQ7XG4gICAgICAgICAgICBsaS5hcHBlbmRDaGlsZChjb250YWluZXIpO1xuICAgICAgICAgICAgJChjb250YWluZXIpLndyYXAoJzxzcGFuIGNsYXNzPVwibXctdHJlZS1pdGVtLWNvbnRlbnQtcm9vdFwiPjwvc3Bhbj4nKVxuICAgICAgICAgICAgaWYoIXNraXApe1xuICAgICAgICAgICAgICAgIGNvbnRhaW5lci5vbmNsaWNrID0gZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAgICAgaWYoc2NvcGUub3B0aW9ucy5zZWxlY3RhYmxlKSBzY29wZS50b2dnbGVTZWxlY3QobGkpXG4gICAgICAgICAgICAgICAgfTtcbiAgICAgICAgICAgICAgICB0aGlzLmRlY29yYXRlKGxpKTtcbiAgICAgICAgICAgIH1cblxuXG4gICAgICAgICAgICByZXR1cm4gbGk7XG4gICAgICAgIH07XG5cblxuXG4gICAgICAgIHRoaXMuYWRkaXRpb25hbCA9IGZ1bmN0aW9uIChvYmopIHtcbiAgICAgICAgICAgIHZhciBsaSA9IHNjb3BlLmRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2xpJyk7XG4gICAgICAgICAgICBsaS5jbGFzc05hbWUgPSAnbXctdHJlZS1hZGRpdGlvbmFsLWl0ZW0nO1xuICAgICAgICAgICAgdmFyIGNvbnRhaW5lciA9IHNjb3BlLmRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ3NwYW4nKTtcbiAgICAgICAgICAgIHZhciBjb250YWluZXJUaXRsZSA9IHNjb3BlLmRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ3NwYW4nKTtcbiAgICAgICAgICAgIGNvbnRhaW5lci5jbGFzc05hbWUgPSBcIm13LXRyZWUtaXRlbS1jb250ZW50XCI7XG4gICAgICAgICAgICBjb250YWluZXJUaXRsZS5jbGFzc05hbWUgPSBcIm13LXRyZWUtaXRlbS10aXRsZVwiO1xuICAgICAgICAgICAgY29udGFpbmVyLmFwcGVuZENoaWxkKGNvbnRhaW5lclRpdGxlKTtcblxuICAgICAgICAgICAgbGkuYXBwZW5kQ2hpbGQoY29udGFpbmVyKTtcbiAgICAgICAgICAgICQoY29udGFpbmVyKS53cmFwKCc8c3BhbiBjbGFzcz1cIm13LXRyZWUtaXRlbS1jb250ZW50LXJvb3RcIj48L3NwYW4+JylcbiAgICAgICAgICAgIGlmKG9iai5pY29uKXtcbiAgICAgICAgICAgICAgICBpZihvYmouaWNvbi5pbmRleE9mKCc8LycpID09PSAtMSl7XG4gICAgICAgICAgICAgICAgICAgIHZhciBpY29uID0gc2NvcGUuZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnc3BhbicpO1xuICAgICAgICAgICAgICAgICAgICBpY29uLmNsYXNzTmFtZSA9ICdtdy10cmVlLWFkaXRpb25hbC1pdGVtLWljb24gJyArIG9iai5pY29uO1xuICAgICAgICAgICAgICAgICAgICBjb250YWluZXJUaXRsZS5hcHBlbmRDaGlsZChpY29uKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgZWxzZXtcbiAgICAgICAgICAgICAgICAgICAgbXcuJChjb250YWluZXJUaXRsZSkuYXBwZW5kKG9iai5pY29uKVxuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgdmFyIHRpdGxlID0gc2NvcGUuZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnc3BhbicpO1xuICAgICAgICAgICAgdGl0bGUuaW5uZXJIVE1MID0gb2JqLnRpdGxlO1xuICAgICAgICAgICAgY29udGFpbmVyVGl0bGUuYXBwZW5kQ2hpbGQodGl0bGUpO1xuICAgICAgICAgICAgbGkub25jbGljayA9IGZ1bmN0aW9uIChldikge1xuICAgICAgICAgICAgICAgIGlmKG9iai5hY3Rpb24pe1xuICAgICAgICAgICAgICAgICAgICBvYmouYWN0aW9uLmNhbGwodGhpcywgb2JqKVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH07XG4gICAgICAgICAgICByZXR1cm4gbGk7XG4gICAgICAgIH1cblxuICAgICAgICB0aGlzLmNyZWF0ZUxpc3QgPSBmdW5jdGlvbihpdGVtKXtcbiAgICAgICAgICAgIHZhciBubGlzdCA9IHNjb3BlLmRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ3VsJyk7XG4gICAgICAgICAgICBubGlzdC5kYXRhc2V0LnR5cGUgPSBpdGVtLnBhcmVudF90eXBlO1xuICAgICAgICAgICAgbmxpc3QuZGF0YXNldC5pZCA9IGl0ZW0ucGFyZW50X2lkO1xuICAgICAgICAgICAgbmxpc3QuY2xhc3NOYW1lID0gJ3ByZS1pbml0JztcbiAgICAgICAgICAgIHJldHVybiBubGlzdDtcbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLmdldFBhcmVudCA9IGZ1bmN0aW9uKGl0ZW0sIGlzVGVtcCl7XG4gICAgICAgICAgICBpZighaXRlbS5wYXJlbnRfaWQpIHJldHVybiB0aGlzLmxpc3Q7XG4gICAgICAgICAgICB2YXIgZmluZHVsID0gdGhpcy5saXN0LnF1ZXJ5U2VsZWN0b3IoJ3VsW2RhdGEtdHlwZT1cIicraXRlbS5wYXJlbnRfdHlwZSsnXCJdW2RhdGEtaWQ9XCInK2l0ZW0ucGFyZW50X2lkKydcIl0nKTtcbiAgICAgICAgICAgIHZhciBmaW5kbGkgPSB0aGlzLmxpc3QucXVlcnlTZWxlY3RvcignbGlbZGF0YS10eXBlPVwiJytpdGVtLnBhcmVudF90eXBlKydcIl1bZGF0YS1pZD1cIicraXRlbS5wYXJlbnRfaWQrJ1wiXScpO1xuICAgICAgICAgICAgaWYoZmluZHVsKXtcbiAgICAgICAgICAgICAgICByZXR1cm4gZmluZHVsO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgZWxzZSBpZihmaW5kbGkpe1xuICAgICAgICAgICAgICAgIHZhciBubGlzdHdyYXAgPSB0aGlzLmNyZWF0ZUl0ZW0oaXRlbSk7XG4gICAgICAgICAgICAgICAgaWYoIW5saXN0d3JhcCkgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgICAgIHZhciBubGlzdCA9IHRoaXMuY3JlYXRlTGlzdChpdGVtKTtcbiAgICAgICAgICAgICAgICBubGlzdC5hcHBlbmRDaGlsZChubGlzdHdyYXApO1xuICAgICAgICAgICAgICAgIGZpbmRsaS5hcHBlbmRDaGlsZChubGlzdCk7XG4gICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgfVxuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuYXBwZW5kID0gZnVuY3Rpb24oKXtcbiAgICAgICAgICAgIGlmKHRoaXMub3B0aW9ucy5hcHBlbmQpe1xuICAgICAgICAgICAgICAgICQuZWFjaCh0aGlzLm9wdGlvbnMuYXBwZW5kLCBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICAgICBzY29wZS5saXN0LmFwcGVuZENoaWxkKHNjb3BlLmFkZGl0aW9uYWwodGhpcykpXG4gICAgICAgICAgICAgICAgfSlcbiAgICAgICAgICAgIH1cbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLnByZXBlbmQgPSBmdW5jdGlvbigpe1xuICAgICAgICAgICAgaWYodGhpcy5vcHRpb25zLnByZXBlbmQpe1xuICAgICAgICAgICAgICAgICQuZWFjaCh0aGlzLm9wdGlvbnMuYXBwZW5kLCBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICAgICBtdy4kKHNjb3BlLmxpc3QpLnByZXBlbmQoc2NvcGUuYWRkaXRpb25hbCh0aGlzKSlcbiAgICAgICAgICAgICAgICB9KVxuICAgICAgICAgICAgfVxuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuYWRkSGVscGVyQ2xhc3NlcyA9IGZ1bmN0aW9uKHJvb3QsIGxldmVsKXtcbiAgICAgICAgICAgIGxldmVsID0gKGxldmVsIHx8IDApICsgMTtcbiAgICAgICAgICAgIHJvb3QgPSByb290IHx8IHRoaXMubGlzdDtcbiAgICAgICAgICAgIG13LiQoIHJvb3QuY2hpbGRyZW4gKS5hZGRDbGFzcygnbGV2ZWwtJytsZXZlbCkuZWFjaChmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgIHZhciBjaCA9IHRoaXMucXVlcnlTZWxlY3RvcigndWwnKTtcbiAgICAgICAgICAgICAgICBpZihjaCl7XG4gICAgICAgICAgICAgICAgICAgIG13LiQodGhpcykuYWRkQ2xhc3MoJ2hhcy1jaGlsZHJlbicpXG4gICAgICAgICAgICAgICAgICAgIHNjb3BlLmFkZEhlbHBlckNsYXNzZXMoY2gsIGxldmVsKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KVxuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMubG9hZFNlbGVjdGVkID0gZnVuY3Rpb24oKXtcbiAgICAgICAgICAgIGlmKHRoaXMuc2VsZWN0ZWREYXRhKXtcbiAgICAgICAgICAgICAgICBzY29wZS5zZWxlY3QodGhpcy5zZWxlY3RlZERhdGEpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9O1xuICAgICAgICB0aGlzLmluaXQgPSBmdW5jdGlvbigpe1xuXG4gICAgICAgICAgICB0aGlzLnByZXBhcmVEYXRhKCk7XG4gICAgICAgICAgICB0aGlzLmpzb24ydWwoKTtcbiAgICAgICAgICAgIHRoaXMuYWRkQnV0dG9ucygpO1xuICAgICAgICAgICAgdGhpcy5yZW5kKCk7XG4gICAgICAgICAgICB0aGlzLmFwcGVuZCgpO1xuICAgICAgICAgICAgdGhpcy5wcmVwZW5kKCk7XG4gICAgICAgICAgICB0aGlzLmFkZEhlbHBlckNsYXNzZXMoKTtcbiAgICAgICAgICAgIHRoaXMucmVzdG9yZVN0YXRlKCk7XG4gICAgICAgICAgICB0aGlzLmxvYWRTZWxlY3RlZCgpO1xuICAgICAgICAgICAgdGhpcy5zZWFyY2goKTtcbiAgICAgICAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICBtdy4kKHNjb3BlKS50cmlnZ2VyKCdyZWFkeScpO1xuICAgICAgICAgICAgfSwgNzgpXG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5jb25maWcoY29uZmlnKTtcbiAgICAgICAgdGhpcy5pbml0KCk7XG4gICAgfTtcbiAgICBtdy50cmVlID0gbXd0cmVlO1xuICAgIG13LnRyZWUuZ2V0ID0gZnVuY3Rpb24gKGEpIHtcbiAgICAgICAgYSA9IG13LiQoYSlbMF07XG4gICAgICAgIGlmKCFhKSByZXR1cm47XG4gICAgICAgIGlmKG13LnRvb2xzLmhhc0NsYXNzKGEsICdtdy10cmVlLW5hdicpKXtcbiAgICAgICAgICAgIHJldHVybiBhLl90cmVlO1xuICAgICAgICB9XG4gICAgICAgIHZhciBjaGlsZCA9IGEucXVlcnlTZWxlY3RvcignLm13LXRyZWUtbmF2Jyk7XG4gICAgICAgIGlmKGNoaWxkKSByZXR1cm4gY2hpbGQuX3RyZWU7XG4gICAgICAgIHZhciBwYXJlbnQgPSBtdy50b29scy5maXJzdFBhcmVudFdpdGhDbGFzcyhhLCAnbXctdHJlZS1uYXYnKTtcblxuICAgICAgICBpZihwYXJlbnQpIHtcbiAgICAgICAgICAgIHJldHVybiBwYXJlbnQuX3RyZWU7XG4gICAgICAgIH1cbiAgICB9XG5cblxufSkoKTtcbiIsIm13LnVpQWNjb3JkaW9uID0gZnVuY3Rpb24gKG9wdGlvbnMpIHtcbiAgICBpZiAoIW9wdGlvbnMpIHJldHVybjtcbiAgICBvcHRpb25zLmVsZW1lbnQgPSBvcHRpb25zLmVsZW1lbnQgfHwgJy5tdy1hY2NvcmRpb24nO1xuXG4gICAgdmFyIHNjb3BlID0gdGhpcztcblxuXG5cbiAgICB0aGlzLmdldENvbnRlbnRzID0gZnVuY3Rpb24gKCkge1xuICAgICAgICB0aGlzLmNvbnRlbnRzID0gdGhpcy5yb290LmNoaWxkcmVuKHRoaXMub3B0aW9ucy5jb250ZW50U2VsZWN0b3IpO1xuICAgICAgICBpZiAoIXRoaXMuY29udGVudHMubGVuZ3RoKSB7XG4gICAgICAgICAgICB0aGlzLmNvbnRlbnRzID0gbXcuJCgpO1xuICAgICAgICAgICAgdGhpcy5yb290LmNoaWxkcmVuKHRoaXMub3B0aW9ucy5pdGVtU2VsZWN0b3IpLmVhY2goZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIHZhciB0aXRsZSA9IG13LiQodGhpcykuY2hpbGRyZW4oc2NvcGUub3B0aW9ucy5jb250ZW50U2VsZWN0b3IpWzBdO1xuICAgICAgICAgICAgICAgIGlmICh0aXRsZSkge1xuICAgICAgICAgICAgICAgICAgICBzY29wZS5jb250ZW50cy5wdXNoKHRpdGxlKVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9XG4gICAgfVxuICAgIHRoaXMuZ2V0VGl0bGVzID0gZnVuY3Rpb24gKCkge1xuICAgICAgICB0aGlzLnRpdGxlcyA9IHRoaXMucm9vdC5jaGlsZHJlbih0aGlzLm9wdGlvbnMudGl0bGVTZWxlY3Rvcik7XG4gICAgICAgIGlmICghdGhpcy50aXRsZXMubGVuZ3RoKSB7XG4gICAgICAgICAgICB0aGlzLnRpdGxlcyA9IG13LiQoKTtcbiAgICAgICAgICAgIHRoaXMucm9vdC5jaGlsZHJlbih0aGlzLm9wdGlvbnMuaXRlbVNlbGVjdG9yKS5lYWNoKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICB2YXIgdGl0bGUgPSBtdy4kKHRoaXMpLmNoaWxkcmVuKHNjb3BlLm9wdGlvbnMudGl0bGVTZWxlY3RvcilbMF07XG4gICAgICAgICAgICAgICAgaWYgKHRpdGxlKSB7XG4gICAgICAgICAgICAgICAgICAgIHNjb3BlLnRpdGxlcy5wdXNoKHRpdGxlKVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9XG4gICAgfTtcblxuICAgIHRoaXMucHJlcGFyZSA9IGZ1bmN0aW9uIChvcHRpb25zKSB7XG4gICAgICAgIHZhciBkZWZhdWx0cyA9IHtcbiAgICAgICAgICAgIG11bHRpcGxlOiBmYWxzZSxcbiAgICAgICAgICAgIGl0ZW1TZWxlY3RvcjogXCIubXctYWNjb3JkaW9uLWl0ZW0sbXctYWNjb3JkaW9uLWl0ZW1cIixcbiAgICAgICAgICAgIHRpdGxlU2VsZWN0b3I6IFwiLm13LWFjY29yZGlvbi10aXRsZSxtdy1hY2NvcmRpb24tdGl0bGVcIixcbiAgICAgICAgICAgIGNvbnRlbnRTZWxlY3RvcjogXCIubXctYWNjb3JkaW9uLWNvbnRlbnQsbXctYWNjb3JkaW9uLWNvbnRlbnRcIixcbiAgICAgICAgICAgIG9wZW5GaXJzdDogdHJ1ZSxcbiAgICAgICAgICAgIHRvZ2dsZTogdHJ1ZVxuICAgICAgICB9O1xuICAgICAgICB0aGlzLm9wdGlvbnMgPSAkLmV4dGVuZCh7fSwgZGVmYXVsdHMsIG9wdGlvbnMpO1xuXG4gICAgICAgIHRoaXMucm9vdCA9IG13LiQodGhpcy5vcHRpb25zLmVsZW1lbnQpLm5vdCgnLm13LWFjY29yZGlvbi1yZWFkeScpLmVxKDApO1xuICAgICAgICBpZiAoIXRoaXMucm9vdC5sZW5ndGgpIHJldHVybjtcbiAgICAgICAgdGhpcy5yb290LmFkZENsYXNzKCdtdy1hY2NvcmRpb24tcmVhZHknKTtcbiAgICAgICAgdGhpcy5yb290WzBdLnVpQWNjb3JkaW9uID0gdGhpcztcbiAgICAgICAgdGhpcy5nZXRUaXRsZXMoKTtcbiAgICAgICAgdGhpcy5nZXRDb250ZW50cygpO1xuXG4gICAgfTtcblxuICAgIHRoaXMuZ2V0SXRlbSA9IGZ1bmN0aW9uIChxKSB7XG4gICAgICAgIHZhciBpdGVtO1xuICAgICAgICBpZiAodHlwZW9mIHEgPT09ICdudW1iZXInKSB7XG4gICAgICAgICAgICBpdGVtID0gdGhpcy5jb250ZW50cy5lcShxKVxuICAgICAgICB9XG4gICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgaXRlbSA9IG13LiQocSk7XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIGl0ZW07XG4gICAgfTtcblxuICAgIHRoaXMuc2V0ID0gZnVuY3Rpb24gKGluZGV4KSB7XG4gICAgICAgIHZhciBpdGVtID0gdGhpcy5nZXRJdGVtKGluZGV4KTtcbiAgICAgICAgaWYgKCF0aGlzLm9wdGlvbnMubXVsdGlwbGUpIHtcbiAgICAgICAgICAgIHRoaXMuY29udGVudHMubm90KGl0ZW0pXG4gICAgICAgICAgICAgICAgLnNsaWRlVXAoKVxuICAgICAgICAgICAgICAgIC5yZW1vdmVDbGFzcygnYWN0aXZlJylcbiAgICAgICAgICAgICAgICAucHJldigpXG4gICAgICAgICAgICAgICAgLnJlbW92ZUNsYXNzKCdhY3RpdmUnKVxuICAgICAgICAgICAgICAgIC5wYXJlbnRzKCcubXctYWNjb3JkaW9uLWl0ZW0nKS5lcSgwKVxuICAgICAgICAgICAgICAgIC5yZW1vdmVDbGFzcygnYWN0aXZlJyk7XG4gICAgICAgIH1cbiAgICAgICAgaXRlbS5zdG9wKClcbiAgICAgICAgICAgIC5zbGlkZURvd24oKVxuICAgICAgICAgICAgLmFkZENsYXNzKCdhY3RpdmUnKVxuICAgICAgICAgICAgLnByZXYoKVxuICAgICAgICAgICAgLmFkZENsYXNzKCdhY3RpdmUnKVxuICAgICAgICAgICAgLnBhcmVudHMoJy5tdy1hY2NvcmRpb24taXRlbScpLmVxKDApXG4gICAgICAgICAgICAuYWRkQ2xhc3MoJ2FjdGl2ZScpO1xuICAgICAgICBtdy4kKHRoaXMpLnRyaWdnZXIoJ2FjY29yZGlvblNldCcsIFtpdGVtXSk7XG4gICAgfTtcblxuICAgIHRoaXMudW5zZXQgPSBmdW5jdGlvbiAoaW5kZXgpIHtcbiAgICAgICAgaWYgKHR5cGVvZiBpbmRleCA9PT0gJ3VuZGVmaW5lZCcpIHJldHVybjtcbiAgICAgICAgdmFyIGl0ZW0gPSB0aGlzLmdldEl0ZW0oaW5kZXgpO1xuICAgICAgICBpdGVtLnN0b3AoKVxuICAgICAgICAgICAgLnNsaWRlVXAoKVxuICAgICAgICAgICAgLnJlbW92ZUNsYXNzKCdhY3RpdmUnKVxuICAgICAgICAgICAgLnByZXYoKVxuICAgICAgICAgICAgLnJlbW92ZUNsYXNzKCdhY3RpdmUnKVxuICAgICAgICAgICAgLnBhcmVudHMoJy5tdy1hY2NvcmRpb24taXRlbScpLmVxKDApXG4gICAgICAgICAgICAucmVtb3ZlQ2xhc3MoJ2FjdGl2ZScpO1xuICAgICAgICA7XG4gICAgICAgIG13LiQodGhpcykudHJpZ2dlcignYWNjb3JkaW9uVW5zZXQnLCBbaXRlbV0pO1xuICAgIH1cblxuICAgIHRoaXMudG9nZ2xlID0gZnVuY3Rpb24gKGluZGV4KSB7XG4gICAgICAgIHZhciBpdGVtID0gdGhpcy5nZXRJdGVtKGluZGV4KTtcbiAgICAgICAgaWYgKGl0ZW0uaGFzQ2xhc3MoJ2FjdGl2ZScpKSB7XG4gICAgICAgICAgICBpZiAodGhpcy5vcHRpb25zLnRvZ2dsZSkge1xuICAgICAgICAgICAgICAgIHRoaXMudW5zZXQoaXRlbSlcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgIHRoaXMuc2V0KGl0ZW0pXG4gICAgICAgIH1cbiAgICB9XG5cbiAgICB0aGlzLmluaXQgPSBmdW5jdGlvbiAob3B0aW9ucykge1xuICAgICAgICB0aGlzLnByZXBhcmUob3B0aW9ucyk7XG4gICAgICAgIGlmKHR5cGVvZih0aGlzLmNvbnRlbnRzKSAhPT0gJ3VuZGVmaW5lZCcpe1xuICAgICAgICAgICAgdGhpcy5jb250ZW50cy5oaWRlKClcbiAgICAgICAgfVxuXG4gICAgICAgIGlmICh0aGlzLm9wdGlvbnMub3BlbkZpcnN0KSB7XG4gICAgICAgICAgICBpZih0eXBlb2YodGhpcy5jb250ZW50cykgIT09ICd1bmRlZmluZWQnKXtcbiAgICAgICAgICAgICAgICB0aGlzLmNvbnRlbnRzLmVxKDApLnNob3coKS5hZGRDbGFzcygnYWN0aXZlJylcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGlmKHR5cGVvZih0aGlzLnRpdGxlcykgIT09ICd1bmRlZmluZWQnKXtcbiAgICAgICAgICAgICAgICB0aGlzLnRpdGxlcy5lcSgwKS5hZGRDbGFzcygnYWN0aXZlJykucGFyZW50KCcubXctYWNjb3JkaW9uLWl0ZW0nKS5hZGRDbGFzcygnYWN0aXZlJyk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgaWYodHlwZW9mKHRoaXMudGl0bGVzKSAhPT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAgICAgICAgIHRoaXMudGl0bGVzLm9uKCdjbGljaycsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICBzY29wZS50b2dnbGUoc2NvcGUudGl0bGVzLmluZGV4KHRoaXMpKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9XG4gICAgfVxuXG4gICAgdGhpcy5pbml0KG9wdGlvbnMpO1xuXG59O1xuXG5cbm13LnRhYkFjY29yZGlvbiA9IGZ1bmN0aW9uIChvcHRpb25zLCBhY2NvcmRpb24pIHtcbiAgICBpZiAoIW9wdGlvbnMpIHJldHVybjtcbiAgICB2YXIgc2NvcGUgPSB0aGlzO1xuICAgIHRoaXMub3B0aW9ucyA9IG9wdGlvbnM7XG5cbiAgICB0aGlzLm9wdGlvbnMuYnJlYWtQb2ludCA9IHRoaXMub3B0aW9ucy5icmVha1BvaW50IHx8IDgwMDtcbiAgICB0aGlzLm9wdGlvbnMuYWN0aXZlQ2xhc3MgPSB0aGlzLm9wdGlvbnMuYWN0aXZlQ2xhc3MgfHwgJ2FjdGl2ZS1pbmZvJztcblxuXG4gICAgdGhpcy5idWlsZEFjY29yZGlvbiA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgdGhpcy5hY2NvcmRpb24gPSBhY2NvcmRpb24gfHwgbmV3IG13LnVpQWNjb3JkaW9uKHRoaXMub3B0aW9ucyk7XG4gICAgfVxuXG4gICAgdGhpcy5icmVha1BvaW50ID0gZnVuY3Rpb24gKCkge1xuICAgICAgICBpZiAobWF0Y2hNZWRpYShcIihtYXgtd2lkdGg6IFwiICsgdGhpcy5vcHRpb25zLmJyZWFrUG9pbnQgKyBcInB4KVwiKS5tYXRjaGVzKSB7XG4gICAgICAgICAgICBtdy4kKHRoaXMubmF2KS5oaWRlKCk7XG4gICAgICAgICAgICBtdy4kKHRoaXMuYWNjb3JkaW9uLnRpdGxlcykuc2hvdygpO1xuICAgICAgICB9XG4gICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgbXcuJCh0aGlzLm5hdikuc2hvdygpO1xuICAgICAgICAgICAgbXcuJCh0aGlzLmFjY29yZGlvbi50aXRsZXMpLmhpZGUoKTtcbiAgICAgICAgfVxuICAgIH1cblxuICAgIHRoaXMuY3JlYXRlVGFiQnV0dG9uID0gZnVuY3Rpb24gKGNvbnRlbnQsIGluZGV4KSB7XG4gICAgICAgIHRoaXMuYnV0dG9ucyA9IHRoaXMuYnV0dG9ucyB8fCBtdy4kKCk7XG4gICAgICAgIHZhciBidG4gPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdzcGFuJyk7XG4gICAgICAgIHRoaXMuYnV0dG9ucy5wdXNoKGJ0bilcbiAgICAgICAgdmFyIHNpemUgPSAodGhpcy5vcHRpb25zLnRhYnNTaXplID8gJyBtdy11aS1idG4tJyArIHRoaXMub3B0aW9ucy50YWJzU2l6ZSA6ICcnKTtcbiAgICAgICAgdmFyIGNvbG9yID0gKHRoaXMub3B0aW9ucy50YWJzQ29sb3IgPyAnIG13LXVpLWJ0bi0nICsgdGhpcy5vcHRpb25zLnRhYnNDb2xvciA6ICcnKTtcbiAgICAgICAgdmFyIGFjdGl2ZSA9IChpbmRleCA9PT0gMCA/ICgnICcgKyB0aGlzLm9wdGlvbnMuYWN0aXZlQ2xhc3MpIDogJycpO1xuICAgICAgICBidG4uY2xhc3NOYW1lID0gJ213LXVpLWJ0bicgKyBzaXplICsgY29sb3IgKyBhY3RpdmU7XG4gICAgICAgIGJ0bi5pbm5lckhUTUwgPSBjb250ZW50O1xuICAgICAgICBidG4ub25jbGljayA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHNjb3BlLmJ1dHRvbnMucmVtb3ZlQ2xhc3Moc2NvcGUub3B0aW9ucy5hY3RpdmVDbGFzcykuZXEoaW5kZXgpLmFkZENsYXNzKHNjb3BlLm9wdGlvbnMuYWN0aXZlQ2xhc3MpO1xuICAgICAgICAgICAgc2NvcGUuYWNjb3JkaW9uLnNldChpbmRleCk7XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIGJ0bjtcbiAgICB9XG5cbiAgICB0aGlzLmNyZWF0ZVRhYnMgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHRoaXMubmF2ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XG4gICAgICAgIHRoaXMubmF2LmNsYXNzTmFtZSA9ICdtdy11aS1idG4tbmF2IG13LXVpLWJ0bi1uYXYtdGFicyc7XG4gICAgICAgIG13LiQodGhpcy5hY2NvcmRpb24udGl0bGVzKVxuICAgICAgICAgICAgLmVhY2goZnVuY3Rpb24gKGkpIHtcbiAgICAgICAgICAgICAgICBzY29wZS5uYXYuYXBwZW5kQ2hpbGQoc2NvcGUuY3JlYXRlVGFiQnV0dG9uKCQodGhpcykuaHRtbCgpLCBpKSlcbiAgICAgICAgICAgIH0pXG4gICAgICAgICAgICAuaGlkZSgpO1xuICAgICAgICBtdy4kKHRoaXMuYWNjb3JkaW9uLnJvb3QpLmJlZm9yZSh0aGlzLm5hdilcbiAgICB9XG5cbiAgICB0aGlzLmluaXQgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHRoaXMuYnVpbGRBY2NvcmRpb24oKTtcbiAgICAgICAgdGhpcy5jcmVhdGVUYWJzKCk7XG4gICAgICAgIHRoaXMuYnJlYWtQb2ludCgpO1xuICAgICAgICBtdy4kKHdpbmRvdykub24oJ2xvYWQgcmVzaXplIG9yaWVudGF0aW9uY2hhbmdlJywgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgc2NvcGUuYnJlYWtQb2ludCgpO1xuICAgICAgICB9KTtcbiAgICB9O1xuXG4gICAgdGhpcy5pbml0KCk7XG59O1xuIiwiOyhmdW5jdGlvbiAoKXtcblxuICAgIHZhciBVcGxvYWRlciA9IGZ1bmN0aW9uKCBvcHRpb25zICkge1xuICAgICAgICAvL3ZhciB1cGxvYWQgPSBmdW5jdGlvbiggdXJsLCBkYXRhLCBjYWxsYmFjaywgdHlwZSApIHtcbiAgICAgICAgb3B0aW9ucyA9IG9wdGlvbnMgfHwge307XG4gICAgICAgIG9wdGlvbnMuYWNjZXB0ID0gb3B0aW9ucy5hY2NlcHQgfHwgb3B0aW9ucy5maWxldHlwZXM7XG4gICAgICAgIHZhciBkZWZhdWx0cyA9IHtcbiAgICAgICAgICAgIG11bHRpcGxlOiBmYWxzZSxcbiAgICAgICAgICAgIHByb2dyZXNzOiBudWxsLFxuICAgICAgICAgICAgZWxlbWVudDogbnVsbCxcbiAgICAgICAgICAgIHVybDogb3B0aW9ucy51cmwgfHwgKG13LnNldHRpbmdzLnNpdGVfdXJsICsgJ3BsdXBsb2FkJyksXG4gICAgICAgICAgICB1cmxQYXJhbXM6IHt9LFxuICAgICAgICAgICAgb246IHt9LFxuICAgICAgICAgICAgYXV0b3N0YXJ0OiB0cnVlLFxuICAgICAgICAgICAgYXN5bmM6IHRydWUsXG4gICAgICAgICAgICBhY2NlcHQ6ICcqJyxcbiAgICAgICAgICAgIGNodW5rU2l6ZTogMTUwMDAwMCxcbiAgICAgICAgfTtcblxuICAgICAgICB2YXIgbm9ybWFsaXplQWNjZXB0ID0gZnVuY3Rpb24gKHR5cGUpIHtcbiAgICAgICAgICAgIHR5cGUgPSAodHlwZSB8fCAnJykudHJpbSgpLnRvTG93ZXJDYXNlKCk7XG4gICAgICAgICAgICBpZighdHlwZSkgcmV0dXJuICcqJztcbiAgICAgICAgICAgIGlmICh0eXBlID09PSAnaW1hZ2UnIHx8IHR5cGUgPT09ICdpbWFnZXMnKSByZXR1cm4gJy5wbmcsLmdpZiwuanBnLC5qcGVnLC50aWZmLC5ibXAsLnN2Zyc7XG4gICAgICAgICAgICBpZiAodHlwZSA9PT0gJ3ZpZGVvJyB8fCB0eXBlID09PSAndmlkZW9zJykgcmV0dXJuICcubXA0LC53ZWJtLC5vZ2csLndtYSwubW92LC53bXYnO1xuICAgICAgICAgICAgaWYgKHR5cGUgPT09ICdkb2N1bWVudCcgfHwgdHlwZSA9PT0gJ2RvY3VtZW50cycpIHJldHVybiAnLmRvYywuZG9jeCwubG9nLC5wZGYsLm1zZywub2R0LC5wYWdlcywnICtcbiAgICAgICAgICAgICAgICAnLnJ0ZiwudGV4LC50eHQsLndwZCwud3BzLC5wcHMsLnBwdCwucHB0eCwueG1sLC5odG0sLmh0bWwsLnhsciwueGxzLC54bHN4JztcblxuICAgICAgICAgICAgcmV0dXJuICcqJztcbiAgICAgICAgfTtcblxuICAgICAgICB2YXIgc2NvcGUgPSB0aGlzO1xuICAgICAgICB0aGlzLnNldHRpbmdzID0gJC5leHRlbmQoe30sIGRlZmF1bHRzLCBvcHRpb25zKTtcbiAgICAgICAgdGhpcy5zZXR0aW5ncy5hY2NlcHQgPSBub3JtYWxpemVBY2NlcHQodGhpcy5zZXR0aW5ncy5hY2NlcHQpO1xuXG4gICAgICAgIHRoaXMuZ2V0VXJsID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdmFyIHBhcmFtcyA9IHRoaXMudXJsUGFyYW1zKCk7XG4gICAgICAgICAgICB2YXIgZW1wdHkgPSBtdy50b29scy5pc0VtcHR5T2JqZWN0KHBhcmFtcyk7XG4gICAgICAgICAgICByZXR1cm4gdGhpcy51cmwoKSArIChlbXB0eSA/ICcnIDogKCc/JyArICQucGFyYW0ocGFyYW1zKSkpO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMudXJsUGFyYW0gPSBmdW5jdGlvbiAocGFyYW0sIHZhbHVlKSB7XG4gICAgICAgICAgICBpZih0eXBlb2YgdmFsdWUgPT09ICd1bmRlZmluZWQnKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIHRoaXMuc2V0dGluZ3MudXJsUGFyYW1zW3BhcmFtXTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHRoaXMuc2V0dGluZ3MudXJsUGFyYW1zW3BhcmFtXSA9IHZhbHVlO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMudXJsUGFyYW1zID0gZnVuY3Rpb24gKHBhcmFtcykge1xuICAgICAgICAgICAgaWYoIXBhcmFtcykge1xuICAgICAgICAgICAgICAgIHJldHVybiB0aGlzLnNldHRpbmdzLnVybFBhcmFtcztcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHRoaXMuc2V0dGluZ3MudXJsUGFyYW1zID0gcGFyYW1zO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMudXJsID0gZnVuY3Rpb24gKHVybCkge1xuICAgICAgICAgICAgaWYoIXVybCkge1xuICAgICAgICAgICAgICAgIHJldHVybiB0aGlzLnNldHRpbmdzLnVybDtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHRoaXMuc2V0dGluZ3MudXJsID0gdXJsO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuY3JlYXRlID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdGhpcy5pbnB1dCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2lucHV0Jyk7XG4gICAgICAgICAgICB0aGlzLmlucHV0Lm11bHRpcGxlID0gdGhpcy5zZXR0aW5ncy5tdWx0aXBsZTtcbiAgICAgICAgICAgIHRoaXMuaW5wdXQuYWNjZXB0ID0gdGhpcy5zZXR0aW5ncy5hY2NlcHQ7XG4gICAgICAgICAgICB0aGlzLmlucHV0LnR5cGUgPSAnZmlsZSc7XG4gICAgICAgICAgICB0aGlzLmlucHV0LmNsYXNzTmFtZSA9ICdtdy11cGxvYWRlci1pbnB1dCc7XG4gICAgICAgICAgICB0aGlzLmlucHV0Lm9uaW5wdXQgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgc2NvcGUuYWRkRmlsZXModGhpcy5maWxlcyk7XG4gICAgICAgICAgICB9O1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuZmlsZXMgPSBbXTtcbiAgICAgICAgdGhpcy5fdXBsb2FkaW5nID0gZmFsc2U7XG4gICAgICAgIHRoaXMudXBsb2FkaW5nID0gZnVuY3Rpb24gKHN0YXRlKSB7XG4gICAgICAgICAgICBpZih0eXBlb2Ygc3RhdGUgPT09ICd1bmRlZmluZWQnKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIHRoaXMuX3VwbG9hZGluZztcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHRoaXMuX3VwbG9hZGluZyA9IHN0YXRlO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuX3ZhbGlkYXRlQWNjZXB0ID0gdGhpcy5zZXR0aW5ncy5hY2NlcHRcbiAgICAgICAgICAgIC50b0xvd2VyQ2FzZSgpXG4gICAgICAgICAgICAucmVwbGFjZSgvXFwqL2csICcnKVxuICAgICAgICAgICAgLnJlcGxhY2UoLyAvZywgJycpXG4gICAgICAgICAgICAuc3BsaXQoJywnKVxuICAgICAgICAgICAgLmZpbHRlcihmdW5jdGlvbiAoaXRlbSkge1xuICAgICAgICAgICAgICAgIHJldHVybiAhIWl0ZW07XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgdGhpcy52YWxpZGF0ZSA9IGZ1bmN0aW9uIChmaWxlKSB7XG4gICAgICAgICAgICBpZighZmlsZSkgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgdmFyIGV4dCA9ICcuJyArIGZpbGUubmFtZS5zcGxpdCgnLicpLnBvcCgpLnRvTG93ZXJDYXNlKCk7XG4gICAgICAgICAgICBpZiAodGhpcy5fdmFsaWRhdGVBY2NlcHQubGVuZ3RoID09PSAwKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIHRydWU7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBmb3IgKHZhciBpID0gMDsgaSA8IHRoaXMuX3ZhbGlkYXRlQWNjZXB0Lmxlbmd0aDsgaSsrKSB7XG4gICAgICAgICAgICAgICAgdmFyIGl0ZW0gPSAgdGhpcy5fdmFsaWRhdGVBY2NlcHRbaV07XG4gICAgICAgICAgICAgICAgaWYoaXRlbSA9PT0gZXh0KSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiB0cnVlO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBlbHNlIGlmKGZpbGUudHlwZS5pbmRleE9mKGl0ZW0pID09PSAwKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiB0cnVlO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHJldHVybiBmYWxzZTtcblxuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuYWRkRmlsZSA9IGZ1bmN0aW9uIChmaWxlKSB7XG4gICAgICAgICAgICBpZih0aGlzLnZhbGlkYXRlKGZpbGUpKSB7XG4gICAgICAgICAgICAgICAgaWYoIXRoaXMuZmlsZXMubGVuZ3RoIHx8IHRoaXMuc2V0dGluZ3MubXVsdGlwbGUpe1xuICAgICAgICAgICAgICAgICAgICB0aGlzLmZpbGVzLnB1c2goZmlsZSk7XG4gICAgICAgICAgICAgICAgICAgIGlmKHRoaXMuc2V0dGluZ3Mub24uZmlsZUFkZGVkKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICB0aGlzLnNldHRpbmdzLm9uLmZpbGVBZGRlZChmaWxlKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAkKHNjb3BlKS50cmlnZ2VyKCdGaWxlQWRkZWQnLCBmaWxlKTtcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLmZpbGVzID0gW2ZpbGVdO1xuICAgICAgICAgICAgICAgICAgICAkKHNjb3BlKS50cmlnZ2VyKCdGaWxlQWRkZWQnLCBmaWxlKTtcbiAgICAgICAgICAgICAgICAgICAgaWYodGhpcy5zZXR0aW5ncy5vbi5maWxlQWRkZWQpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMuc2V0dGluZ3Mub24uZmlsZUFkZGVkKGZpbGUpO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuYWRkRmlsZXMgPSBmdW5jdGlvbiAoZmlsZXMpIHtcbiAgICAgICAgICAgIGlmKCFmaWxlcyB8fCAhZmlsZXMubGVuZ3RoKSByZXR1cm47XG5cbiAgICAgICAgICAgIGlmKCF0aGlzLnNldHRpbmdzLm11bHRpcGxlKSB7XG4gICAgICAgICAgICAgICAgZmlsZXMgPSBbZmlsZXNbMF1dO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgaWYgKGZpbGVzICYmIGZpbGVzLmxlbmd0aCkge1xuICAgICAgICAgICAgICAgIGZvciAodmFyIGkgPSAwOyBpIDwgZmlsZXMubGVuZ3RoOyBpKyspIHtcbiAgICAgICAgICAgICAgICAgICAgc2NvcGUuYWRkRmlsZShmaWxlc1tpXSk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGlmKHRoaXMuc2V0dGluZ3Mub24uZmlsZXNBZGRlZCkge1xuICAgICAgICAgICAgICAgICAgICBpZih0aGlzLnNldHRpbmdzLm9uLmZpbGVzQWRkZWQoZmlsZXMpID09PSBmYWxzZSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICQoc2NvcGUpLnRyaWdnZXIoJ0ZpbGVzQWRkZWQnLCBmaWxlcyk7XG4gICAgICAgICAgICAgICAgaWYodGhpcy5zZXR0aW5ncy5hdXRvc3RhcnQpIHtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy51cGxvYWRGaWxlcygpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLmJ1aWxkID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgaWYodGhpcy5zZXR0aW5ncy5lbGVtZW50KSB7XG4gICAgICAgICAgICAgICAgdGhpcy4kZWxlbWVudCA9ICQodGhpcy5zZXR0aW5ncy5lbGVtZW50KTtcbiAgICAgICAgICAgICAgICB0aGlzLmVsZW1lbnQgPSB0aGlzLiRlbGVtZW50WzBdO1xuICAgICAgICAgICAgICAgIGlmKHRoaXMuZWxlbWVudCkge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLiRlbGVtZW50LyouZW1wdHkoKSovLmFwcGVuZCh0aGlzLmlucHV0KTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5zaG93ID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdGhpcy4kZWxlbWVudC5zaG93KCk7XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5oaWRlID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdGhpcy4kZWxlbWVudC5oaWRlKCk7XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5pbml0RHJvcFpvbmUgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBpZiAoISF0aGlzLnNldHRpbmdzLmRyb3Bab25lKSB7XG4gICAgICAgICAgICAgICAgbXcuJCh0aGlzLnNldHRpbmdzLmRyb3Bab25lKS5lYWNoKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgJCh0aGlzKS5vbignZHJhZ292ZXInLCBmdW5jdGlvbiAoZSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICAgICAgICAgICAgICB9KS5vbignZHJvcCcsIGZ1bmN0aW9uIChlKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICB2YXIgZHQgPSBlLmRhdGFUcmFuc2ZlciB8fCBlLm9yaWdpbmFsRXZlbnQuZGF0YVRyYW5zZmVyO1xuICAgICAgICAgICAgICAgICAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgaWYgKGR0ICYmIGR0Lml0ZW1zKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdmFyIGZpbGVzID0gW107XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgZm9yICh2YXIgaSA9IDA7IGkgPCBkdC5pdGVtcy5sZW5ndGg7IGkrKykge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiAoZHQuaXRlbXNbaV0ua2luZCA9PT0gJ2ZpbGUnKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB2YXIgZmlsZSA9IGR0Lml0ZW1zW2ldLmdldEFzRmlsZSgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgZmlsZXMucHVzaChmaWxlKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBzY29wZS5hZGRGaWxlcyhmaWxlcyk7XG4gICAgICAgICAgICAgICAgICAgICAgICB9IGVsc2UgIGlmIChkdCAmJiBkdC5maWxlcykgIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBzY29wZS5hZGRGaWxlcyhkdC5maWxlcyk7XG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIH0pXG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH07XG5cblxuICAgICAgICB0aGlzLmluaXQgPSBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIHRoaXMuY3JlYXRlKCk7XG4gICAgICAgICAgICB0aGlzLmJ1aWxkKCk7XG4gICAgICAgICAgICB0aGlzLmluaXREcm9wWm9uZSgpO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuaW5pdCgpO1xuXG4gICAgICAgIHRoaXMucmVtb3ZlRmlsZSA9IGZ1bmN0aW9uIChmaWxlKSB7XG4gICAgICAgICAgICB2YXIgaSA9IHRoaXMuZmlsZXMuaW5kZXhPZihmaWxlKTtcbiAgICAgICAgICAgIGlmIChpID4gLTEpIHtcbiAgICAgICAgICAgICAgICB0aGlzLmZpbGVzLnNwbGljZShpLCAxKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLnVwbG9hZEZpbGUgPSBmdW5jdGlvbiAoZmlsZSwgZG9uZSwgY2h1bmtzLCBfYWxsLCBfaSkge1xuICAgICAgICAgICAgY2h1bmtzID0gY2h1bmtzIHx8IHRoaXMuc2xpY2VGaWxlKGZpbGUpO1xuICAgICAgICAgICAgX2FsbCA9IF9hbGwgfHwgY2h1bmtzLmxlbmd0aDtcbiAgICAgICAgICAgIF9pID0gX2kgfHwgMDtcbiAgICAgICAgICAgIHZhciBjaHVuayA9IGNodW5rcy5zaGlmdCgpO1xuICAgICAgICAgICAgdmFyIGRhdGEgPSB7XG4gICAgICAgICAgICAgICAgbmFtZTogZmlsZS5uYW1lLFxuICAgICAgICAgICAgICAgIGNodW5rOiBfaSxcbiAgICAgICAgICAgICAgICBjaHVua3M6IF9hbGwsXG4gICAgICAgICAgICAgICAgZmlsZTogY2h1bmssXG4gICAgICAgICAgICB9O1xuICAgICAgICAgICAgX2krKztcbiAgICAgICAgICAgICQoc2NvcGUpLnRyaWdnZXIoJ3VwbG9hZFN0YXJ0JywgW2RhdGFdKTtcblxuICAgICAgICAgICAgdGhpcy51cGxvYWQoZGF0YSwgZnVuY3Rpb24gKHJlcykge1xuICAgICAgICAgICAgICAgIHZhciBkYXRhUHJvZ3Jlc3M7XG4gICAgICAgICAgICAgICAgaWYoY2h1bmtzLmxlbmd0aCkge1xuICAgICAgICAgICAgICAgICAgICBzY29wZS51cGxvYWRGaWxlKGZpbGUsIGRvbmUsIGNodW5rcywgX2FsbCwgX2kpO1xuICAgICAgICAgICAgICAgICAgICBkYXRhUHJvZ3Jlc3MgPSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBwZXJjZW50OiAoKDEwMCAqIF9pKSAvIF9hbGwpLnRvRml4ZWQoKVxuICAgICAgICAgICAgICAgICAgICB9O1xuICAgICAgICAgICAgICAgICAgICAkKHNjb3BlKS50cmlnZ2VyKCdwcm9ncmVzcycsIFtkYXRhUHJvZ3Jlc3MsIHJlc10pO1xuICAgICAgICAgICAgICAgICAgICBpZihzY29wZS5zZXR0aW5ncy5vbi5wcm9ncmVzcykge1xuICAgICAgICAgICAgICAgICAgICAgICAgc2NvcGUuc2V0dGluZ3Mub24ucHJvZ3Jlc3MoZGF0YVByb2dyZXNzLCByZXMpO1xuICAgICAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICBkYXRhUHJvZ3Jlc3MgPSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBwZXJjZW50OiAnMTAwJ1xuICAgICAgICAgICAgICAgICAgICB9O1xuICAgICAgICAgICAgICAgICAgICAkKHNjb3BlKS50cmlnZ2VyKCdwcm9ncmVzcycsIFtkYXRhUHJvZ3Jlc3MsIHJlc10pO1xuICAgICAgICAgICAgICAgICAgICBpZihzY29wZS5zZXR0aW5ncy5vbi5wcm9ncmVzcykge1xuICAgICAgICAgICAgICAgICAgICAgICAgc2NvcGUuc2V0dGluZ3Mub24ucHJvZ3Jlc3MoZGF0YVByb2dyZXNzLCByZXMpO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICQoc2NvcGUpLnRyaWdnZXIoJ0ZpbGVVcGxvYWRlZCcsIFtyZXNdKTtcbiAgICAgICAgICAgICAgICAgICAgaWYoc2NvcGUuc2V0dGluZ3Mub24uZmlsZVVwbG9hZGVkKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBzY29wZS5zZXR0aW5ncy5vbi5maWxlVXBsb2FkZWQocmVzKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICBkb25lLmNhbGwoZmlsZSk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLnNsaWNlRmlsZSA9IGZ1bmN0aW9uKGZpbGUpIHtcbiAgICAgICAgICAgIHZhciBieXRlSW5kZXggPSAwO1xuICAgICAgICAgICAgdmFyIGNodW5rcyA9IFtdO1xuICAgICAgICAgICAgdmFyIGNodW5rc0Ftb3VudCA9IGZpbGUuc2l6ZSA8PSB0aGlzLnNldHRpbmdzLmNodW5rU2l6ZSA/IDEgOiAoKGZpbGUuc2l6ZSAvIHRoaXMuc2V0dGluZ3MuY2h1bmtTaXplKSA+PiAwKSArIDE7XG5cbiAgICAgICAgICAgIGZvciAodmFyIGkgPSAwOyBpIDwgY2h1bmtzQW1vdW50OyBpICsrKSB7XG4gICAgICAgICAgICAgICAgdmFyIGJ5dGVFbmQgPSBNYXRoLmNlaWwoKGZpbGUuc2l6ZSAvIGNodW5rc0Ftb3VudCkgKiAoaSArIDEpKTtcbiAgICAgICAgICAgICAgICBjaHVua3MucHVzaChmaWxlLnNsaWNlKGJ5dGVJbmRleCwgYnl0ZUVuZCkpO1xuICAgICAgICAgICAgICAgIGJ5dGVJbmRleCArPSAoYnl0ZUVuZCAtIGJ5dGVJbmRleCk7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIHJldHVybiBjaHVua3M7XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy51cGxvYWRGaWxlcyA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIGlmICh0aGlzLnNldHRpbmdzLmFzeW5jKSB7XG4gICAgICAgICAgICAgICAgaWYgKHRoaXMuZmlsZXMubGVuZ3RoKSB7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMudXBsb2FkaW5nKHRydWUpO1xuICAgICAgICAgICAgICAgICAgICB0aGlzLnVwbG9hZEZpbGUodGhpcy5maWxlc1swXSwgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgc2NvcGUuZmlsZXMuc2hpZnQoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIHNjb3BlLnVwbG9hZEZpbGVzKCk7XG4gICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMudXBsb2FkaW5nKGZhbHNlKTtcbiAgICAgICAgICAgICAgICAgICAgc2NvcGUuaW5wdXQudmFsdWUgPSAnJztcbiAgICAgICAgICAgICAgICAgICAgaWYoc2NvcGUuc2V0dGluZ3Mub24uZmlsZXNVcGxvYWRlZCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgc2NvcGUuc2V0dGluZ3Mub24uZmlsZXNVcGxvYWRlZCgpO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICQoc2NvcGUpLnRyaWdnZXIoJ0ZpbGVzVXBsb2FkZWQnKTtcblxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgdmFyIGNvdW50ID0gMDtcbiAgICAgICAgICAgICAgICB2YXIgYWxsID0gdGhpcy5maWxlcy5sZW5ndGg7XG4gICAgICAgICAgICAgICAgdGhpcy51cGxvYWRpbmcodHJ1ZSk7XG4gICAgICAgICAgICAgICAgdGhpcy5maWxlcy5mb3JFYWNoKGZ1bmN0aW9uIChmaWxlKSB7XG4gICAgICAgICAgICAgICAgICAgIHNjb3BlLnVwbG9hZEZpbGUoZmlsZSwgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgY291bnQrKztcbiAgICAgICAgICAgICAgICAgICAgICAgIHNjb3BlLnVwbG9hZGluZyhmYWxzZSk7XG4gICAgICAgICAgICAgICAgICAgICAgICBpZihhbGwgPT09IGNvdW50KSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgc2NvcGUuaW5wdXQudmFsdWUgPSAnJztcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZihzY29wZS5zZXR0aW5ncy5vbi5maWxlc1VwbG9hZGVkKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHNjb3BlLnNldHRpbmdzLm9uLmZpbGVzVXBsb2FkZWQoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgJChzY29wZSkudHJpZ2dlcignRmlsZXNVcGxvYWRlZCcpO1xuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfTtcblxuXG4gICAgICAgIHRoaXMudXBsb2FkID0gZnVuY3Rpb24gKGRhdGEsIGRvbmUpIHtcbiAgICAgICAgICAgIGlmICghdGhpcy5zZXR0aW5ncy51cmwpIHtcbiAgICAgICAgICAgICAgICByZXR1cm47XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICB2YXIgcGRhdGEgPSBuZXcgRm9ybURhdGEoKTtcbiAgICAgICAgICAgICQuZWFjaChkYXRhLCBmdW5jdGlvbiAoa2V5LCB2YWwpIHtcbiAgICAgICAgICAgICAgICBwZGF0YS5hcHBlbmQoa2V5LCB2YWwpO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBpZihzY29wZS5zZXR0aW5ncy5vbi51cGxvYWRTdGFydCkge1xuICAgICAgICAgICAgICAgIGlmIChzY29wZS5zZXR0aW5ncy5vbi51cGxvYWRTdGFydChwZGF0YSkgPT09IGZhbHNlKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIHJldHVybiAkLmFqYXgoe1xuICAgICAgICAgICAgICAgIHVybDogdGhpcy5nZXRVcmwoKSxcbiAgICAgICAgICAgICAgICB0eXBlOiAncG9zdCcsXG4gICAgICAgICAgICAgICAgcHJvY2Vzc0RhdGE6IGZhbHNlLFxuICAgICAgICAgICAgICAgIGNvbnRlbnRUeXBlOiBmYWxzZSxcbiAgICAgICAgICAgICAgICBkYXRhOiBwZGF0YSxcbiAgICAgICAgICAgICAgICBzdWNjZXNzOiBmdW5jdGlvbiAocmVzKSB7XG4gICAgICAgICAgICAgICAgICAgIHNjb3BlLnJlbW92ZUZpbGUoZGF0YS5maWxlKTtcbiAgICAgICAgICAgICAgICAgICAgaWYoZG9uZSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgZG9uZS5jYWxsKHJlcywgcmVzKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgZGF0YVR5cGU6ICdqc29uJyxcbiAgICAgICAgICAgICAgICB4aHI6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgdmFyIHhociA9IG5ldyBYTUxIdHRwUmVxdWVzdCgpO1xuICAgICAgICAgICAgICAgICAgICB4aHIudXBsb2FkLmFkZEV2ZW50TGlzdGVuZXIoJ3Byb2dyZXNzJywgZnVuY3Rpb24gKGV2ZW50KSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBpZiAoZXZlbnQubGVuZ3RoQ29tcHV0YWJsZSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHZhciBwZXJjZW50ID0gKGV2ZW50LmxvYWRlZCAvIGV2ZW50LnRvdGFsKSAqIDEwMDtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZihzY29wZS5zZXR0aW5ncy5vbi5wcm9ncmVzc05hdGl2ZSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBzY29wZS5zZXR0aW5ncy5vbi5wcm9ncmVzc05hdGl2ZShwZXJjZW50LCBldmVudCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICQoc2NvcGUpLnRyaWdnZXIoJ3Byb2dyZXNzTmF0aXZlJywgW3BlcmNlbnQsIGV2ZW50XSk7XG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4geGhyO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9O1xuICAgIH07XG5cbiAgICBtdy51cGxvYWQgPSBmdW5jdGlvbiAob3B0aW9ucykge1xuICAgICAgICByZXR1cm4gbmV3IFVwbG9hZGVyKG9wdGlvbnMpO1xuICAgIH07XG5cblxufSkoKTtcbiJdLCJzb3VyY2VSb290IjoiIn0=