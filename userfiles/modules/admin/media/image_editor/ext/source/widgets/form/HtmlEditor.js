/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/**
 * @class Ext.form.HtmlEditor
 * @extends Ext.form.Field
 * Provides a lightweight HTML Editor component. Some toolbar features are not supported by Safari and will be 
 * automatically hidden when needed.  These are noted in the config options where appropriate.
 * <br><br>The editor's toolbar buttons have tooltips defined in the {@link #buttonTips} property, but they are not 
 * enabled by default unless the global {@link Ext.QuickTips} singleton is {@link Ext.QuickTips#init initialized}.
 * <br><br><b>Note: The focus/blur and validation marking functionality inherited from Ext.form.Field is NOT
 * supported by this editor.</b>
 * <br><br>An Editor is a sensitive component that can't be used in all spots standard fields can be used. Putting an Editor within
 * any element that has display set to 'none' can cause problems in Safari and Firefox due to their default iframe reloading bugs.
 * <br><br>Example usage:
 * <pre><code>
// Simple example rendered with default options:
Ext.QuickTips.init();  // enable tooltips
new Ext.form.HtmlEditor({
    renderTo: Ext.getBody(),
    width: 800,
    height: 300
});

// Passed via xtype into a container and with custom options:
Ext.QuickTips.init();  // enable tooltips
new Ext.Panel({
    title: 'HTML Editor',
    renderTo: Ext.getBody(),
    width: 600,
    height: 300,
    frame: true,
    layout: 'fit',
    items: {
        xtype: 'htmleditor',
        enableColors: false,
        enableAlignments: false
    }
});
</code></pre>
 * @constructor
 * Create a new HtmlEditor
 * @param {Object} config
 */

Ext.form.HtmlEditor = Ext.extend(Ext.form.Field, {
    /**
     * @cfg {Boolean} enableFormat Enable the bold, italic and underline buttons (defaults to true)
     */
    enableFormat : true,
    /**
     * @cfg {Boolean} enableFontSize Enable the increase/decrease font size buttons (defaults to true)
     */
    enableFontSize : true,
    /**
     * @cfg {Boolean} enableColors Enable the fore/highlight color buttons (defaults to true)
     */
    enableColors : true,
    /**
     * @cfg {Boolean} enableAlignments Enable the left, center, right alignment buttons (defaults to true)
     */
    enableAlignments : true,
    /**
     * @cfg {Boolean} enableLists Enable the bullet and numbered list buttons. Not available in Safari. (defaults to true)
     */
    enableLists : true,
    /**
     * @cfg {Boolean} enableSourceEdit Enable the switch to source edit button. Not available in Safari. (defaults to true)
     */
    enableSourceEdit : true,
    /**
     * @cfg {Boolean} enableLinks Enable the create link button. Not available in Safari. (defaults to true)
     */
    enableLinks : true,
    /**
     * @cfg {Boolean} enableFont Enable font selection. Not available in Safari. (defaults to true)
     */
    enableFont : true,
    /**
     * @cfg {String} createLinkText The default text for the create link prompt
     */
    createLinkText : 'Please enter the URL for the link:',
    /**
     * @cfg {String} defaultLinkValue The default value for the create link prompt (defaults to http:/ /)
     */
    defaultLinkValue : 'http:/'+'/',
    /**
     * @cfg {Array} fontFamilies An array of available font families
     */
    fontFamilies : [
        'Arial',
        'Courier New',
        'Tahoma',
        'Times New Roman',
        'Verdana'
    ],
    defaultFont: 'tahoma',

    // private properties
    validationEvent : false,
    deferHeight: true,
    initialized : false,
    activated : false,
    sourceEditMode : false,
    onFocus : Ext.emptyFn,
    iframePad:3,
    hideMode:'offsets',
    defaultAutoCreate : {
        tag: "textarea",
        style:"width:500px;height:300px;",
        autocomplete: "off"
    },

    // private
    initComponent : function(){
        this.addEvents(
            /**
             * @event initialize
             * Fires when the editor is fully initialized (including the iframe)
             * @param {HtmlEditor} this
             */
            'initialize',
            /**
             * @event activate
             * Fires when the editor is first receives the focus. Any insertion must wait
             * until after this event.
             * @param {HtmlEditor} this
             */
            'activate',
             /**
             * @event beforesync
             * Fires before the textarea is updated with content from the editor iframe. Return false
             * to cancel the sync.
             * @param {HtmlEditor} this
             * @param {String} html
             */
            'beforesync',
             /**
             * @event beforepush
             * Fires before the iframe editor is updated with content from the textarea. Return false
             * to cancel the push.
             * @param {HtmlEditor} this
             * @param {String} html
             */
            'beforepush',
             /**
             * @event sync
             * Fires when the textarea is updated with content from the editor iframe.
             * @param {HtmlEditor} this
             * @param {String} html
             */
            'sync',
             /**
             * @event push
             * Fires when the iframe editor is updated with content from the textarea.
             * @param {HtmlEditor} this
             * @param {String} html
             */
            'push',
             /**
             * @event editmodechange
             * Fires when the editor switches edit modes
             * @param {HtmlEditor} this
             * @param {Boolean} sourceEdit True if source edit, false if standard editing.
             */
            'editmodechange'
        )
    },

    // private
    createFontOptions : function(){
        var buf = [], fs = this.fontFamilies, ff, lc;
        for(var i = 0, len = fs.length; i< len; i++){
            ff = fs[i];
            lc = ff.toLowerCase();
            buf.push(
                '<option value="',lc,'" style="font-family:',ff,';"',
                    (this.defaultFont == lc ? ' selected="true">' : '>'),
                    ff,
                '</option>'
            );
        }
        return buf.join('');
    },
    
    /*
     * Protected method that will not generally be called directly. It
     * is called when the editor creates its toolbar. Override this method if you need to
     * add custom toolbar buttons.
     * @param {HtmlEditor} editor
     */
    createToolbar : function(editor){
        
        var tipsEnabled = Ext.QuickTips && Ext.QuickTips.isEnabled();
        
        function btn(id, toggle, handler){
            return {
                itemId : id,
                cls : 'x-btn-icon x-edit-'+id,
                enableToggle:toggle !== false,
                scope: editor,
                handler:handler||editor.relayBtnCmd,
                clickEvent:'mousedown',
                tooltip: tipsEnabled ? editor.buttonTips[id] || undefined : undefined,
                tabIndex:-1
            };
        }

        // build the toolbar
        var tb = new Ext.Toolbar({
            renderTo:this.wrap.dom.firstChild
        });

        // stop form submits
        tb.el.on('click', function(e){
            e.preventDefault();
        });

        if(this.enableFont && !Ext.isSafari2){
            this.fontSelect = tb.el.createChild({
                tag:'select',
                cls:'x-font-select',
                html: this.createFontOptions()
            });
            this.fontSelect.on('change', function(){
                var font = this.fontSelect.dom.value;
                this.relayCmd('fontname', font);
                this.deferFocus();
            }, this);
            tb.add(
                this.fontSelect.dom,
                '-'
            );
        };

        if(this.enableFormat){
            tb.add(
                btn('bold'),
                btn('italic'),
                btn('underline')
            );
        };

        if(this.enableFontSize){
            tb.add(
                '-',
                btn('increasefontsize', false, this.adjustFont),
                btn('decreasefontsize', false, this.adjustFont)
            );
        };

        if(this.enableColors){
            tb.add(
                '-', {
                    itemId:'forecolor',
                    cls:'x-btn-icon x-edit-forecolor',
                    clickEvent:'mousedown',
                    tooltip: tipsEnabled ? editor.buttonTips['forecolor'] || undefined : undefined,
                    tabIndex:-1,
                    menu : new Ext.menu.ColorMenu({
                        allowReselect: true,
                        focus: Ext.emptyFn,
                        value:'000000',
                        plain:true,
                        selectHandler: function(cp, color){
                            this.execCmd('forecolor', Ext.isSafari || Ext.isIE ? '#'+color : color);
                            this.deferFocus();
                        },
                        scope: this,
                        clickEvent:'mousedown'
                    })
                }, {
                    itemId:'backcolor',
                    cls:'x-btn-icon x-edit-backcolor',
                    clickEvent:'mousedown',
                    tooltip: tipsEnabled ? editor.buttonTips['backcolor'] || undefined : undefined,
                    tabIndex:-1,
                    menu : new Ext.menu.ColorMenu({
                        focus: Ext.emptyFn,
                        value:'FFFFFF',
                        plain:true,
                        allowReselect: true,
                        selectHandler: function(cp, color){
                            if(Ext.isGecko){
                                this.execCmd('useCSS', false);
                                this.execCmd('hilitecolor', color);
                                this.execCmd('useCSS', true);
                                this.deferFocus();
                            }else{
                                this.execCmd(Ext.isOpera ? 'hilitecolor' : 'backcolor', Ext.isSafari || Ext.isIE ? '#'+color : color);
                                this.deferFocus();
                            }
                        },
                        scope:this,
                        clickEvent:'mousedown'
                    })
                }
            );
        };

        if(this.enableAlignments){
            tb.add(
                '-',
                btn('justifyleft'),
                btn('justifycenter'),
                btn('justifyright')
            );
        };

        if(!Ext.isSafari2){
            if(this.enableLinks){
                tb.add(
                    '-',
                    btn('createlink', false, this.createLink)
                );
            };

            if(this.enableLists){
                tb.add(
                    '-',
                    btn('insertorderedlist'),
                    btn('insertunorderedlist')
                );
            }
            if(this.enableSourceEdit){
                tb.add(
                    '-',
                    btn('sourceedit', true, function(btn){
                        this.toggleSourceEdit(btn.pressed);
                    })
                );
            }
        }

        this.tb = tb;
    },

    /**
     * Protected method that will not generally be called directly. It
     * is called when the editor initializes the iframe with HTML contents. Override this method if you
     * want to change the initialization markup of the iframe (e.g. to add stylesheets).
     */
    getDocMarkup : function(){
        return '<html><head><style type="text/css">body{border:0;margin:0;padding:3px;height:98%;cursor:text;}</style></head><body></body></html>';
    },

    // private
    getEditorBody : function(){
        return this.doc.body || this.doc.documentElement;
    },

    // private
    getDoc : function(){
        return Ext.isIE ? this.getWin().document : (this.iframe.contentDocument || this.getWin().document);
    },

    // private
    getWin : function(){
        return Ext.isIE ? this.iframe.contentWindow : window.frames[this.iframe.name];
    },

    // private
    onRender : function(ct, position){
        Ext.form.HtmlEditor.superclass.onRender.call(this, ct, position);
        this.el.dom.style.border = '0 none';
        this.el.dom.setAttribute('tabIndex', -1);
        this.el.addClass('x-hidden');
        if(Ext.isIE){ // fix IE 1px bogus margin
            this.el.applyStyles('margin-top:-1px;margin-bottom:-1px;')
        }
        this.wrap = this.el.wrap({
            cls:'x-html-editor-wrap', cn:{cls:'x-html-editor-tb'}
        });

        this.createToolbar(this);

        this.tb.items.each(function(item){
           if(item.itemId != 'sourceedit'){
                item.disable();
            }
        });

        var iframe = document.createElement('iframe');
        iframe.name = Ext.id();
        iframe.frameBorder = '0';

        iframe.src = Ext.isIE ? Ext.SSL_SECURE_URL : "javascript:;";

        this.wrap.dom.appendChild(iframe);

        this.iframe = iframe;

        this.initFrame();

        if(this.autoMonitorDesignMode !== false){
            this.monitorTask = Ext.TaskMgr.start({
                run: this.checkDesignMode,
                scope: this,
                interval:100
            });
        }

        if(!this.width){
            var sz = this.el.getSize();
            this.setSize(sz.width, this.height || sz.height);
        }
    },

    initFrame : function(){
        this.doc = this.getDoc();
        this.win = this.getWin();

        this.doc.open();
        this.doc.write(this.getDocMarkup());
        this.doc.close();

        var task = { // must defer to wait for browser to be ready
            run : function(){
                if(this.doc.body || this.doc.readyState == 'complete'){
                    Ext.TaskMgr.stop(task);
                    this.doc.designMode="on";
                    this.initEditor.defer(10, this);
                }
            },
            interval : 10,
            duration:10000,
            scope: this
        };
        Ext.TaskMgr.start(task);
    },


    checkDesignMode : function(){
        if(this.wrap && this.wrap.dom.offsetWidth){
            var doc = this.getDoc();
            if(!doc){
                return;
            }
            if(!doc.editorInitialized || String(doc.designMode).toLowerCase() != 'on'){
                this.initFrame();
            }
        }
    },

    // private
    onResize : function(w, h){
        Ext.form.HtmlEditor.superclass.onResize.apply(this, arguments);
        if(this.el && this.iframe){
            if(typeof w == 'number'){
                var aw = w - this.wrap.getFrameWidth('lr');
                this.el.setWidth(this.adjustWidth('textarea', aw));
                this.iframe.style.width = aw + 'px';
            }
            if(typeof h == 'number'){
                var ah = h - this.wrap.getFrameWidth('tb') - this.tb.el.getHeight();
                this.el.setHeight(this.adjustWidth('textarea', ah));
                this.iframe.style.height = ah + 'px';
                if(this.doc){
                    this.getEditorBody().style.height = (ah - (this.iframePad*2)) + 'px';
                }
            }
        }
    },

    /**
     * Toggles the editor between standard and source edit mode.
     * @param {Boolean} sourceEdit (optional) True for source edit, false for standard
     */
    toggleSourceEdit : function(sourceEditMode){
        if(sourceEditMode === undefined){
            sourceEditMode = !this.sourceEditMode;
        }
        this.sourceEditMode = sourceEditMode === true;
        var btn = this.tb.items.get('sourceedit');
        if(btn.pressed !== this.sourceEditMode){
            btn.toggle(this.sourceEditMode);
            return;
        }
        if(this.sourceEditMode){
            this.tb.items.each(function(item){
                if(item.itemId != 'sourceedit'){
                    item.disable();
                }
            });
            this.syncValue();
            this.iframe.className = 'x-hidden';
            this.el.removeClass('x-hidden');
            this.el.dom.removeAttribute('tabIndex');
            this.el.focus();
        }else{
            if(this.initialized){
                this.tb.items.each(function(item){
                    item.enable();
                });
            }
            this.pushValue();
            this.iframe.className = '';
            this.el.addClass('x-hidden');
            this.el.dom.setAttribute('tabIndex', -1);
            this.deferFocus();
        }
        var lastSize = this.lastSize;
        if(lastSize){
            delete this.lastSize;
            this.setSize(lastSize);
        }
        this.fireEvent('editmodechange', this, this.sourceEditMode);
    },

    // private used internally
    createLink : function(){
        var url = prompt(this.createLinkText, this.defaultLinkValue);
        if(url && url != 'http:/'+'/'){
            this.relayCmd('createlink', url);
        }
    },

    // private (for BoxComponent)
    adjustSize : Ext.BoxComponent.prototype.adjustSize,

    // private (for BoxComponent)
    getResizeEl : function(){
        return this.wrap;
    },

    // private (for BoxComponent)
    getPositionEl : function(){
        return this.wrap;
    },

    // private
    initEvents : function(){
        this.originalValue = this.getValue();
    },

    /**
     * Overridden and disabled. The editor element does not support standard valid/invalid marking. @hide
     * @method
     */
    markInvalid : Ext.emptyFn,
    
    /**
     * Overridden and disabled. The editor element does not support standard valid/invalid marking. @hide
     * @method
     */
    clearInvalid : Ext.emptyFn,

    // docs inherit from Field
    setValue : function(v){
        Ext.form.HtmlEditor.superclass.setValue.call(this, v);
        this.pushValue();
    },

    /**
     * Protected method that will not generally be called directly. If you need/want
     * custom HTML cleanup, this is the method you should override.
     * @param {String} html The HTML to be cleaned
     * return {String} The cleaned HTML
     */
    cleanHtml : function(html){
        html = String(html);
        if(html.length > 5){
            if(Ext.isSafari){ // strip safari nonsense
                html = html.replace(/\sclass="(?:Apple-style-span|khtml-block-placeholder)"/gi, '');
            }
        }
        if(html == '&nbsp;'){
            html = '';
        }
        return html;
    },

    /**
     * Protected method that will not generally be called directly. Syncs the contents
     * of the editor iframe with the textarea.
     */
    syncValue : function(){
        if(this.initialized){
            var bd = this.getEditorBody();
            var html = bd.innerHTML;
            if(Ext.isSafari){
                var bs = bd.getAttribute('style'); // Safari puts text-align styles on the body element!
                var m = bs.match(/text-align:(.*?);/i);
                if(m && m[1]){
                    html = '<div style="'+m[0]+'">' + html + '</div>';
                }
            }
            html = this.cleanHtml(html);
            if(this.fireEvent('beforesync', this, html) !== false){
                this.el.dom.value = html;
                this.fireEvent('sync', this, html);
            }
        }
    },

    /**
     * Protected method that will not generally be called directly. Pushes the value of the textarea
     * into the iframe editor.
     */
    pushValue : function(){
        if(this.initialized){
            var v = this.el.dom.value;
            if(!this.activated && v.length < 1){
                v = '&nbsp;';
            }
            if(this.fireEvent('beforepush', this, v) !== false){
                this.getEditorBody().innerHTML = v;
                this.fireEvent('push', this, v);
            }
        }
    },

    // private
    deferFocus : function(){
        this.focus.defer(10, this);
    },

    // docs inherit from Field
    focus : function(){
        if(this.win && !this.sourceEditMode){
            this.win.focus();
        }else{
            this.el.focus();
        }
    },

    // private
    initEditor : function(){
        var dbody = this.getEditorBody();
        var ss = this.el.getStyles('font-size', 'font-family', 'background-image', 'background-repeat');
        ss['background-attachment'] = 'fixed'; // w3c
        dbody.bgProperties = 'fixed'; // ie

        Ext.DomHelper.applyStyles(dbody, ss);

        if(this.doc){
            try{
                Ext.EventManager.removeAll(this.doc);
            }catch(e){}
        }

        this.doc = this.getDoc();

        Ext.EventManager.on(this.doc, {
            'mousedown': this.onEditorEvent,
            'dblclick': this.onEditorEvent,
            'click': this.onEditorEvent,
            'keyup': this.onEditorEvent,
            buffer:100,
            scope: this
        });

        if(Ext.isGecko){
            Ext.EventManager.on(this.doc, 'keypress', this.applyCommand, this);
        }
        if(Ext.isIE || Ext.isSafari || Ext.isOpera){
            Ext.EventManager.on(this.doc, 'keydown', this.fixKeys, this);
        }
        this.initialized = true;

        this.fireEvent('initialize', this);

        this.doc.editorInitialized = true;

        this.pushValue();
    },

    // private
    onDestroy : function(){
        if(this.monitorTask){
            Ext.TaskMgr.stop(this.monitorTask);
        }
        if(this.rendered){
            this.tb.items.each(function(item){
                if(item.menu){
                    item.menu.removeAll();
                    if(item.menu.el){
                        item.menu.el.destroy();
                    }
                }
                item.destroy();
            });
            this.wrap.dom.innerHTML = '';
            this.wrap.remove();
        }
    },

    // private
    onFirstFocus : function(){
        this.activated = true;
        this.tb.items.each(function(item){
           item.enable();
        });
        if(Ext.isGecko){ // prevent silly gecko errors
            this.win.focus();
            var s = this.win.getSelection();
            if(!s.focusNode || s.focusNode.nodeType != 3){
                var r = s.getRangeAt(0);
                r.selectNodeContents(this.getEditorBody());
                r.collapse(true);
                this.deferFocus();
            }
            try{
                this.execCmd('useCSS', true);
                this.execCmd('styleWithCSS', false);
            }catch(e){}
        }
        this.fireEvent('activate', this);
    },

    // private
    adjustFont: function(btn){
        var adjust = btn.itemId == 'increasefontsize' ? 1 : -1;

        var v = parseInt(this.doc.queryCommandValue('FontSize') || 2, 10);
        if(Ext.isSafari3 || Ext.isAir){
            // Safari 3 values
            // 1 = 10px, 2 = 13px, 3 = 16px, 4 = 18px, 5 = 24px, 6 = 32px
            if(v <= 10){
                v = 1 + adjust;
            }else if(v <= 13){
                v = 2 + adjust;
            }else if(v <= 16){
                v = 3 + adjust;
            }else if(v <= 18){
                v = 4 + adjust;
            }else if(v <= 24){
                v = 5 + adjust;
            }else {
                v = 6 + adjust;
            }
            v = v.constrain(1, 6);
        }else{
            if(Ext.isSafari){ // safari
                adjust *= 2;
            }
            v = Math.max(1, v+adjust) + (Ext.isSafari ? 'px' : 0);
        }
        this.execCmd('FontSize', v);
    },

    // private
    onEditorEvent : function(e){
        this.updateToolbar();
    },


    /**
     * Protected method that will not generally be called directly. It triggers
     * a toolbar update by reading the markup state of the current selection in the editor.
     */
    updateToolbar: function(){

        if(!this.activated){
            this.onFirstFocus();
            return;
        }

        var btns = this.tb.items.map, doc = this.doc;

        if(this.enableFont && !Ext.isSafari2){
            var name = (this.doc.queryCommandValue('FontName')||this.defaultFont).toLowerCase();
            if(name != this.fontSelect.dom.value){
                this.fontSelect.dom.value = name;
            }
        }
        if(this.enableFormat){
            btns.bold.toggle(doc.queryCommandState('bold'));
            btns.italic.toggle(doc.queryCommandState('italic'));
            btns.underline.toggle(doc.queryCommandState('underline'));
        }
        if(this.enableAlignments){
            btns.justifyleft.toggle(doc.queryCommandState('justifyleft'));
            btns.justifycenter.toggle(doc.queryCommandState('justifycenter'));
            btns.justifyright.toggle(doc.queryCommandState('justifyright'));
        }
        if(!Ext.isSafari2 && this.enableLists){
            btns.insertorderedlist.toggle(doc.queryCommandState('insertorderedlist'));
            btns.insertunorderedlist.toggle(doc.queryCommandState('insertunorderedlist'));
        }
        
        Ext.menu.MenuMgr.hideAll();

        this.syncValue();
    },

    // private
    relayBtnCmd : function(btn){
        this.relayCmd(btn.itemId);
    },

    /**
     * Executes a Midas editor command on the editor document and performs necessary focus and
     * toolbar updates. <b>This should only be called after the editor is initialized.</b>
     * @param {String} cmd The Midas command
     * @param {String/Boolean} value (optional) The value to pass to the command (defaults to null)
     */
    relayCmd : function(cmd, value){
        (function(){
            this.focus();
            this.execCmd(cmd, value);
            this.updateToolbar();
        }).defer(10, this);
    },

    /**
     * Executes a Midas editor command directly on the editor document.
     * For visual commands, you should use {@link #relayCmd} instead.
     * <b>This should only be called after the editor is initialized.</b>
     * @param {String} cmd The Midas command
     * @param {String/Boolean} value (optional) The value to pass to the command (defaults to null)
     */
    execCmd : function(cmd, value){
        this.doc.execCommand(cmd, false, value === undefined ? null : value);
        this.syncValue();
    },

    // private
    applyCommand : function(e){
        if(e.ctrlKey){
            var c = e.getCharCode(), cmd;
            if(c > 0){
                c = String.fromCharCode(c);
                switch(c){
                    case 'b':
                        cmd = 'bold';
                    break;
                    case 'i':
                        cmd = 'italic';
                    break;
                    case 'u':
                        cmd = 'underline';
                    break;
                }
                if(cmd){
                    this.win.focus();
                    this.execCmd(cmd);
                    this.deferFocus();
                    e.preventDefault();
                }
            }
        }
    },

    /**
     * Inserts the passed text at the current cursor position. Note: the editor must be initialized and activated
     * to insert text.
     * @param {String} text
     */
    insertAtCursor : function(text){
        if(!this.activated){
            return;
        }
        if(Ext.isIE){
            this.win.focus();
            var r = this.doc.selection.createRange();
            if(r){
                r.collapse(true);
                r.pasteHTML(text);
                this.syncValue();
                this.deferFocus();
            }
        }else if(Ext.isGecko || Ext.isOpera){
            this.win.focus();
            this.execCmd('InsertHTML', text);
            this.deferFocus();
        }else if(Ext.isSafari){
            this.execCmd('InsertText', text);
            this.deferFocus();
        }
    },

    // private
    fixKeys : function(){ // load time branching for fastest keydown performance
        if(Ext.isIE){
            return function(e){
                var k = e.getKey(), r;
                if(k == e.TAB){
                    e.stopEvent();
                    r = this.doc.selection.createRange();
                    if(r){
                        r.collapse(true);
                        r.pasteHTML('&nbsp;&nbsp;&nbsp;&nbsp;');
                        this.deferFocus();
                    }
                }else if(k == e.ENTER){
                    r = this.doc.selection.createRange();
                    if(r){
                        var target = r.parentElement();
                        if(!target || target.tagName.toLowerCase() != 'li'){
                            e.stopEvent();
                            r.pasteHTML('<br />');
                            r.collapse(false);
                            r.select();
                        }
                    }
                }
            };
        }else if(Ext.isOpera){
            return function(e){
                var k = e.getKey();
                if(k == e.TAB){
                    e.stopEvent();
                    this.win.focus();
                    this.execCmd('InsertHTML','&nbsp;&nbsp;&nbsp;&nbsp;');
                    this.deferFocus();
                }
            };
        }else if(Ext.isSafari){
            return function(e){
                var k = e.getKey();
                if(k == e.TAB){
                    e.stopEvent();
                    this.execCmd('InsertText','\t');
                    this.deferFocus();
                }
             };
        }
    }(),

    /**
     * Returns the editor's toolbar. <b>This is only available after the editor has been rendered.</b>
     * @return {Ext.Toolbar}
     */
    getToolbar : function(){
        return this.tb;
    },

    /**
     * Object collection of toolbar tooltips for the buttons in the editor. The key
     * is the command id associated with that button and the value is a valid QuickTips object.
     * For example:
<pre><code>
{
    bold : {
        title: 'Bold (Ctrl+B)',
        text: 'Make the selected text bold.',
        cls: 'x-html-editor-tip'
    },
    italic : {
        title: 'Italic (Ctrl+I)',
        text: 'Make the selected text italic.',
        cls: 'x-html-editor-tip'
    },
    ...
</code></pre>
    * @type Object
     */
    buttonTips : {
        bold : {
            title: 'Bold (Ctrl+B)',
            text: 'Make the selected text bold.',
            cls: 'x-html-editor-tip'
        },
        italic : {
            title: 'Italic (Ctrl+I)',
            text: 'Make the selected text italic.',
            cls: 'x-html-editor-tip'
        },
        underline : {
            title: 'Underline (Ctrl+U)',
            text: 'Underline the selected text.',
            cls: 'x-html-editor-tip'
        },
        increasefontsize : {
            title: 'Grow Text',
            text: 'Increase the font size.',
            cls: 'x-html-editor-tip'
        },
        decreasefontsize : {
            title: 'Shrink Text',
            text: 'Decrease the font size.',
            cls: 'x-html-editor-tip'
        },
        backcolor : {
            title: 'Text Highlight Color',
            text: 'Change the background color of the selected text.',
            cls: 'x-html-editor-tip'
        },
        forecolor : {
            title: 'Font Color',
            text: 'Change the color of the selected text.',
            cls: 'x-html-editor-tip'
        },
        justifyleft : {
            title: 'Align Text Left',
            text: 'Align text to the left.',
            cls: 'x-html-editor-tip'
        },
        justifycenter : {
            title: 'Center Text',
            text: 'Center text in the editor.',
            cls: 'x-html-editor-tip'
        },
        justifyright : {
            title: 'Align Text Right',
            text: 'Align text to the right.',
            cls: 'x-html-editor-tip'
        },
        insertunorderedlist : {
            title: 'Bullet List',
            text: 'Start a bulleted list.',
            cls: 'x-html-editor-tip'
        },
        insertorderedlist : {
            title: 'Numbered List',
            text: 'Start a numbered list.',
            cls: 'x-html-editor-tip'
        },
        createlink : {
            title: 'Hyperlink',
            text: 'Make the selected text a hyperlink.',
            cls: 'x-html-editor-tip'
        },
        sourceedit : {
            title: 'Source Edit',
            text: 'Switch to source editing mode.',
            cls: 'x-html-editor-tip'
        }
    }

    // hide stuff that is not compatible
    /**
     * @event blur
     * @hide
     */
    /**
     * @event change
     * @hide
     */
    /**
     * @event focus
     * @hide
     */
    /**
     * @event specialkey
     * @hide
     */
    /**
     * @cfg {String} fieldClass @hide
     */
    /**
     * @cfg {String} focusClass @hide
     */
    /**
     * @cfg {String} autoCreate @hide
     */
    /**
     * @cfg {String} inputType @hide
     */
    /**
     * @cfg {String} invalidClass @hide
     */
    /**
     * @cfg {String} invalidText @hide
     */
    /**
     * @cfg {String} msgFx @hide
     */
    /**
     * @cfg {String} validateOnBlur @hide
     */
    /**
     * @cfg {Boolean} allowDomMove  @hide
     */
    /**
     * @cfg {String} applyTo @hide
     */
    /**
     * @cfg {String} autoHeight  @hide
     */
    /**
     * @cfg {String} autoWidth  @hide
     */
    /**
     * @cfg {String} cls  @hide
     */
    /**
     * @cfg {String} disabled  @hide
     */
    /**
     * @cfg {String} disabledClass  @hide
     */
    /**
     * @cfg {String} msgTarget  @hide
     */
    /**
     * @cfg {String} readOnly  @hide
     */
    /**
     * @cfg {String} style  @hide
     */
    /**
     * @cfg {String} validationDelay  @hide
     */
    /**
     * @cfg {String} validationEvent  @hide
     */
    /**
     * @cfg {String} tabIndex  @hide
     */
    /**
     * @property disabled
     * @hide
     */
    /**
     * @method applyToMarkup
     * @hide
     */
    /**
     * @method disable
     * @hide
     */
    /**
     * @method enable
     * @hide
     */
    /**
     * @method validate
     * @hide
     */
    /**
     * @event valid
     * @hide
     */
    /**
     * @method setDisabled
     * @hide
     */
    /**
     * @cfg keys
     * @hide
     */
});
Ext.reg('htmleditor', Ext.form.HtmlEditor);