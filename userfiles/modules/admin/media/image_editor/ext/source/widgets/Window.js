/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/**
 * @class Ext.Window
 * @extends Ext.Panel
 * A specialized panel intended for use as an application window.  Windows are floated and draggable by default, and
 * also provide specific behavior like the ability to maximize and restore (with an event for minimizing, since the
 * minimize behavior is application-specific).  Windows can also be linked to a {@link Ext.WindowGroup} or managed
 * by the {@link Ext.WindowManager} to provide grouping, activation, to front/back and other application-specific behavior.
 * @constructor
 * @param {Object} config The config object
 */
Ext.Window = Ext.extend(Ext.Panel, {
    /**
     * @cfg {Number} x
     * The X position of the left edge of the Window on initial showing. Defaults to centering the Window within
     * the width of the Window's container {@link Ext.Element Element) (The Element that the Window is rendered to).
     */
    /**
     * @cfg {Number} y
     * The Y position of the top edge of the Window on initial showing. Defaults to centering the Window within
     * the height of the Window's container {@link Ext.Element Element) (The Element that the Window is rendered to).
     */
    /**
     * @cfg {Boolean} modal
     * True to make the window modal and mask everything behind it when displayed, false to display it without
     * restricting access to other UI elements (defaults to false).
     */
    /**
     * @cfg {String/Element} animateTarget
     * Id or element from which the window should animate while opening (defaults to null with no animation).
     */
    /**
     * @cfg {String} resizeHandles
     * A valid {@link Ext.Resizable} handles config string (defaults to 'all').  Only applies when resizable = true.
     */
    /**
     * @cfg {Ext.WindowGroup} manager
     * A reference to the WindowGroup that should manage this window (defaults to {@link Ext.WindowMgr}).
     */
    /**
    * @cfg {String/Number/Button} defaultButton
    * The id / index of a button or a button instance to focus when this window received the focus.
    */
    /**
    * @cfg {Function} onEsc
    * Allows override of the built-in processing for the escape key. Default action
    * is to close the Window (performing whatever action is specified in {@link #closeAction}.
    * To prevent the Window closing when the escape key is pressed, specify this as
    * Ext.emptyFn (See {@link Ext#emptyFn}).
    */
    /**
    * @cfg {String} baseCls
    * The base CSS class to apply to this panel's element (defaults to 'x-window').
    */
    baseCls : 'x-window',
    /**
     * @cfg {Boolean} resizable
     * True to allow user resizing at each edge and corner of the window, false to disable resizing (defaults to true).
     */
    resizable:true,
    /**
     * @cfg {Boolean} draggable
     * True to allow the window to be dragged by the header bar, false to disable dragging (defaults to true).  Note
     * that by default the window will be centered in the viewport, so if dragging is disabled the window may need
     * to be positioned programmatically after render (e.g., myWindow.setPosition(100, 100);).
     */
    draggable:true,
    /**
     * @cfg {Boolean} closable
     * <p>True to display the 'close' tool button and allow the user to close the window, false to
     * hide the button and disallow closing the window (default to true).</p>
     * <p>By default, when close is requested by either clicking the close button in the header
     * or pressing ESC when the Window has focus, the {@link #close} method will be called. This
     * will <i>destroy</i> the Window and its content meaning that it may not be reused.</p>
     * <p>To make closing a Window <i>hide</i> the Window so that it may be reused, set
     * {@link #closeAction} to 'hide'.
     */
    closable : true,
    /**
     * @cfg {Boolean} constrain
     * True to constrain the window to the viewport, false to allow it to fall outside of the viewport
     * (defaults to false).  Optionally the header only can be constrained using {@link #constrainHeader}.
     */
    constrain:false,
    /**
     * @cfg {Boolean} constrainHeader
     * True to constrain the window header to the viewport, allowing the window body to fall outside of the viewport,
     * false to allow the header to fall outside the viewport (defaults to false).  Optionally the entire window
     * can be constrained using {@link #constrain}.
     */
    constrainHeader:false,
    /**
     * @cfg {Boolean} plain
     * True to render the window body with a transparent background so that it will blend into the framing
     * elements, false to add a lighter background color to visually highlight the body element and separate it
     * more distinctly from the surrounding frame (defaults to false).
     */
    plain:false,
    /**
     * @cfg {Boolean} minimizable
     * True to display the 'minimize' tool button and allow the user to minimize the window, false to hide the button
     * and disallow minimizing the window (defaults to false).  Note that this button provides no implementation --
     * the behavior of minimizing a window is implementation-specific, so the minimize event must be handled and a
     * custom minimize behavior implemented for this option to be useful.
     */
    minimizable : false,
    /**
     * @cfg {Boolean} maximizable
     * True to display the 'maximize' tool button and allow the user to maximize the window, false to hide the button
     * and disallow maximizing the window (defaults to false).  Note that when a window is maximized, the tool button
     * will automatically change to a 'restore' button with the appropriate behavior already built-in that will
     * restore the window to its previous size.
     */
    maximizable : false,
    /**
     * @cfg {Number} minHeight
     * The minimum height in pixels allowed for this window (defaults to 100).  Only applies when resizable = true.
     */
    minHeight: 100,
    /**
     * @cfg {Number} minWidth
     * The minimum width in pixels allowed for this window (defaults to 200).  Only applies when resizable = true.
     */
    minWidth: 200,
    /**
     * @cfg {Boolean} expandOnShow
     * True to always expand the window when it is displayed, false to keep it in its current state (which may be
     * collapsed) when displayed (defaults to true).
     */
    expandOnShow: true,
    /**
     * @cfg {String} closeAction
     * The action to take when the close button is clicked.  The default action is 'close' which will actually remove
     * the window from the DOM and destroy it.  The other valid option is 'hide' which will simply hide the window
     * by setting visibility to hidden and applying negative offsets, keeping the window available to be redisplayed
     * via the {@link #show} method.
     */
    closeAction: 'close',
    /**
     * @cfg {String} elements
     * A comma-delimited list of panel elements to initialize when the window is rendered.  Normally, this list will be
     * generated automatically based on the items added to the window at config time, but sometimes it might be useful to
     * make sure a structural element is rendered even if not specified at config time (for example, you may want
     * to add a button or toolbar dynamically after the window has been rendered).  Adding those elements to this
     * list will allocate the required placeholders in the window when it is rendered.  Valid values are<ul>
     * <li><b>header</b> (required)</li>
     * <li><b>tbar</b> (top bar)</li>
     * <li><b>body</b> (required)</li>
     * <li><b>bbar</b> (bottom bar)</li>
     * <li><b>footer</b><li>
     * </ul>
     * Defaults to 'header,body'.
     */
    elements: 'header,body',

    // inherited docs, same default
    collapsible:false,

    // private
    initHidden : true,
    /**
    * @cfg {Boolean} monitorResize @hide
    * This is automatically managed based on the value of constrain and constrainToHeader
    */
    monitorResize : true,
    /** @cfg {Boolean} frame @hide */
    frame:true,
    /** @cfg {Boolean} floating @hide */
    floating:true,

    // private
    initComponent : function(){
        Ext.Window.superclass.initComponent.call(this);
        this.addEvents(
            /**
             * @event activate
             * Fires after the window has been visually activated via {@link setActive}.
             * @param {Ext.Window} this
             */
            /**
             * @event deactivate
             * Fires after the window has been visually deactivated via {@link setActive}.
             * @param {Ext.Window} this
             */
            /**
             * @event resize
             * Fires after the window has been resized.
             * @param {Ext.Window} this
             * @param {Number} width The window's new width
             * @param {Number} height The window's new height
             */
            'resize',
            /**
             * @event maximize
             * Fires after the window has been maximized.
             * @param {Ext.Window} this
             */
            'maximize',
            /**
             * @event minimize
             * Fires after the window has been minimized.
             * @param {Ext.Window} this
             */
            'minimize',
            /**
             * @event restore
             * Fires after the window has been restored to its original size after being maximized.
             * @param {Ext.Window} this
             */
            'restore'
        );
    },

    // private
    getState : function(){
        return Ext.apply(Ext.Window.superclass.getState.call(this) || {}, this.getBox());
    },

    // private
    onRender : function(ct, position){
        Ext.Window.superclass.onRender.call(this, ct, position);

        if(this.plain){
            this.el.addClass('x-window-plain');
        }

        // this element allows the Window to be focused for keyboard events
        this.focusEl = this.el.createChild({
                    tag: "a", href:"#", cls:"x-dlg-focus",
                    tabIndex:"-1", html: "&#160;"});
        this.focusEl.swallowEvent('click', true);

        this.proxy = this.el.createProxy("x-window-proxy");
        this.proxy.enableDisplayMode('block');

        if(this.modal){
            this.mask = this.container.createChild({cls:"ext-el-mask"}, this.el.dom);
            this.mask.enableDisplayMode("block");
            this.mask.hide();
        }
    },

    // private
    initEvents : function(){
        Ext.Window.superclass.initEvents.call(this);
        if(this.animateTarget){
            this.setAnimateTarget(this.animateTarget);
        }

        if(this.resizable){
            this.resizer = new Ext.Resizable(this.el, {
                minWidth: this.minWidth,
                minHeight:this.minHeight,
                handles: this.resizeHandles || "all",
                pinned: true,
                resizeElement : this.resizerAction
            });
            this.resizer.window = this;
            this.resizer.on("beforeresize", this.beforeResize, this);
        }

        if(this.draggable){
            this.header.addClass("x-window-draggable");
        }
        this.initTools();

        this.el.on("mousedown", this.toFront, this);
        this.manager = this.manager || Ext.WindowMgr;
        this.manager.register(this);
        this.hidden = true;
        if(this.maximized){
            this.maximized = false;
            this.maximize();
        }
        if(this.closable){
            var km = this.getKeyMap();
            km.on(27, this.onEsc, this);
            km.disable();
        }
    },

    initDraggable : function(){
        /**
         * If this Window is configured {@link #draggable}, this property will contain
         * an instance of {@link Ext.dd.DD} which handles dragging the Window's DOM Element.
         * @type Ext.dd.DD
         * @property dd
         */
        this.dd = new Ext.Window.DD(this);
    },

   // private
    onEsc : function(){
        this[this.closeAction]();
    },

    // private
    beforeDestroy : function(){
        Ext.destroy(
            this.resizer,
            this.dd,
            this.proxy,
            this.mask
        );
        Ext.Window.superclass.beforeDestroy.call(this);
    },

    // private
    onDestroy : function(){
        if(this.manager){
            this.manager.unregister(this);
        }
        Ext.Window.superclass.onDestroy.call(this);
    },

    // private
    initTools : function(){
        if(this.minimizable){
            this.addTool({
                id: 'minimize',
                handler: this.minimize.createDelegate(this, [])
            });
        }
        if(this.maximizable){
            this.addTool({
                id: 'maximize',
                handler: this.maximize.createDelegate(this, [])
            });
            this.addTool({
                id: 'restore',
                handler: this.restore.createDelegate(this, []),
                hidden:true
            });
            this.header.on('dblclick', this.toggleMaximize, this);
        }
        if(this.closable){
            this.addTool({
                id: 'close',
                handler: this[this.closeAction].createDelegate(this, [])
            });
        }
    },

    // private
    resizerAction : function(){
        var box = this.proxy.getBox();
        this.proxy.hide();
        this.window.handleResize(box);
        return box;
    },

    // private
    beforeResize : function(){
        this.resizer.minHeight = Math.max(this.minHeight, this.getFrameHeight() + 40); // 40 is a magic minimum content size?
        this.resizer.minWidth = Math.max(this.minWidth, this.getFrameWidth() + 40);
        this.resizeBox = this.el.getBox();
    },

    // private
    updateHandles : function(){
        if(Ext.isIE && this.resizer){
            this.resizer.syncHandleHeight();
            this.el.repaint();
        }
    },

    // private
    handleResize : function(box){
        var rz = this.resizeBox;
        if(rz.x != box.x || rz.y != box.y){
            this.updateBox(box);
        }else{
            this.setSize(box);
        }
        this.focus();
        this.updateHandles();
        this.saveState();
        if(this.layout){
            this.doLayout();
        }
        this.fireEvent("resize", this, box.width, box.height);
    },

    /**
     * Focuses the window.  If a defaultButton is set, it will receive focus, otherwise the
     * window itself will receive focus.
     */
    focus : function(){
        var f = this.focusEl, db = this.defaultButton, t = typeof db;
        if(t != 'undefined'){
            if(t == 'number'){
                f = this.buttons[db];
            }else if(t == 'string'){
                f = Ext.getCmp(db);
            }else{
                f = db;
            }
        }
        f.focus.defer(10, f);
    },

    /**
     * Sets the target element from which the window should animate while opening.
     * @param {String/Element} el The target element or id
     */
    setAnimateTarget : function(el){
        el = Ext.get(el);
        this.animateTarget = el;
    },

    // private
    beforeShow : function(){
        delete this.el.lastXY;
        delete this.el.lastLT;
        if(this.x === undefined || this.y === undefined){
            var xy = this.el.getAlignToXY(this.container, 'c-c');
            var pos = this.el.translatePoints(xy[0], xy[1]);
            this.x = this.x === undefined? pos.left : this.x;
            this.y = this.y === undefined? pos.top : this.y;
        }
        this.el.setLeftTop(this.x, this.y);

        if(this.expandOnShow){
            this.expand(false);
        }

        if(this.modal){
            Ext.getBody().addClass("x-body-masked");
            this.mask.setSize(Ext.lib.Dom.getViewWidth(true), Ext.lib.Dom.getViewHeight(true));
            this.mask.show();
        }
    },

    /**
     * Shows the window, rendering it first if necessary, or activates it and brings it to front if hidden.
     * @param {String/Element} animateTarget (optional) The target element or id from which the window should
     * animate while opening (defaults to undefined with no animation)
     * @param {Function} callback (optional) A callback function to call after the window is displayed
     * @param {Object} scope (optional) The scope in which to execute the callback
     */
    show : function(animateTarget, cb, scope){
        if(!this.rendered){
            this.render(Ext.getBody());
        }
        if(this.hidden === false){
            this.toFront();
            return;
        }
        if(this.fireEvent("beforeshow", this) === false){
            return;
        }
        if(cb){
            this.on('show', cb, scope, {single:true});
        }
        this.hidden = false;
        if(animateTarget !== undefined){
            this.setAnimateTarget(animateTarget);
        }
        this.beforeShow();
        if(this.animateTarget){
            this.animShow();
        }else{
            this.afterShow();
        }
    },

    // private
    afterShow : function(){
        this.proxy.hide();
        this.el.setStyle('display', 'block');
        this.el.show();
        if(this.maximized){
            this.fitContainer();
        }
        if(Ext.isMac && Ext.isGecko){ // work around stupid FF 2.0/Mac scroll bar bug
        	this.cascade(this.setAutoScroll);
        }

        if(this.monitorResize || this.modal || this.constrain || this.constrainHeader){
            Ext.EventManager.onWindowResize(this.onWindowResize, this);
        }
        this.doConstrain();
        if(this.layout){
            this.doLayout();
        }
        if(this.keyMap){
            this.keyMap.enable();
        }
        this.toFront();
        this.updateHandles();
        this.fireEvent("show", this);
    },

    // private
    animShow : function(){
        this.proxy.show();
        this.proxy.setBox(this.animateTarget.getBox());
        this.proxy.setOpacity(0);
        var b = this.getBox(false);
        b.callback = this.afterShow;
        b.scope = this;
        b.duration = .25;
        b.easing = 'easeNone';
        b.opacity = .5;
        b.block = true;
        this.el.setStyle('display', 'none');
        this.proxy.shift(b);
    },

    /**
     * Hides the window, setting it to invisible and applying negative offsets.
     * @param {String/Element} animateTarget (optional) The target element or id to which the window should
     * animate while hiding (defaults to null with no animation)
     * @param {Function} callback (optional) A callback function to call after the window is hidden
     * @param {Object} scope (optional) The scope in which to execute the callback
     */
    hide : function(animateTarget, cb, scope){
        if(this.activeGhost){ // drag active?
            this.hide.defer(100, this, [animateTarget, cb, scope]);
            return;
        }
        if(this.hidden || this.fireEvent("beforehide", this) === false){
            return;
        }
        if(cb){
            this.on('hide', cb, scope, {single:true});
        }
        this.hidden = true;
        if(animateTarget !== undefined){
            this.setAnimateTarget(animateTarget);
        }
        if(this.animateTarget){
            this.animHide();
        }else{
            this.el.hide();
            this.afterHide();
        }
    },

    // private
    afterHide : function(){
        this.proxy.hide();
        if(this.monitorResize || this.modal || this.constrain || this.constrainHeader){
            Ext.EventManager.removeResizeListener(this.onWindowResize, this);
        }
        if(this.modal){
            this.mask.hide();
            Ext.getBody().removeClass("x-body-masked");
        }
        if(this.keyMap){
            this.keyMap.disable();
        }
        this.fireEvent("hide", this);
    },

    // private
    animHide : function(){
        this.proxy.setOpacity(.5);
        this.proxy.show();
        var tb = this.getBox(false);
        this.proxy.setBox(tb);
        this.el.hide();
        var b = this.animateTarget.getBox();
        b.callback = this.afterHide;
        b.scope = this;
        b.duration = .25;
        b.easing = 'easeNone';
        b.block = true;
        b.opacity = 0;
        this.proxy.shift(b);
    },

    // private
    onWindowResize : function(){
        if(this.maximized){
            this.fitContainer();
        }
        if(this.modal){
            this.mask.setSize('100%', '100%');
            var force = this.mask.dom.offsetHeight;
            this.mask.setSize(Ext.lib.Dom.getViewWidth(true), Ext.lib.Dom.getViewHeight(true));
        }
        this.doConstrain();
    },

    // private
    doConstrain : function(){
        if(this.constrain || this.constrainHeader){
            var offsets;
            if(this.constrain){
                offsets = {
                    right:this.el.shadowOffset,
                    left:this.el.shadowOffset,
                    bottom:this.el.shadowOffset
                };
            }else {
                var s = this.getSize();
                offsets = {
                    right:-(s.width - 100),
                    bottom:-(s.height - 25)
                };
            }

            var xy = this.el.getConstrainToXY(this.container, true, offsets);
            if(xy){
                this.setPosition(xy[0], xy[1]);
            }
        }
    },

    // private - used for dragging
    ghost : function(cls){
        var ghost = this.createGhost(cls);
        var box = this.getBox(true);
        ghost.setLeftTop(box.x, box.y);
        ghost.setWidth(box.width);
        this.el.hide();
        this.activeGhost = ghost;
        return ghost;
    },

    // private
    unghost : function(show, matchPosition){
        if(show !== false){
            this.el.show();
            this.focus();
	        if(Ext.isMac && Ext.isGecko){ // work around stupid FF 2.0/Mac scroll bar bug
	        	this.cascade(this.setAutoScroll);
	        }
        }
        if(matchPosition !== false){
            this.setPosition(this.activeGhost.getLeft(true), this.activeGhost.getTop(true));
        }
        this.activeGhost.hide();
        this.activeGhost.remove();
        delete this.activeGhost;
    },

    /**
     * Placeholder method for minimizing the window.  By default, this method simply fires the {@link #minimize} event
     * since the behavior of minimizing a window is application-specific.  To implement custom minimize behavior,
     * either the minimize event can be handled or this method can be overridden.
     */
    minimize : function(){
        this.fireEvent('minimize', this);
    },

    /**
     * Closes the window, removes it from the DOM and destroys the window object.  The beforeclose event is fired
     * before the close happens and will cancel the close action if it returns false.
     */
    close : function(){
        if(this.fireEvent("beforeclose", this) !== false){
            this.hide(null, function(){
                this.fireEvent('close', this);
                this.destroy();
            }, this);
        }
    },

    /**
     * Fits the window within its current container and automatically replaces the 'maximize' tool button with
     * the 'restore' tool button.
     */
    maximize : function(){
        if(!this.maximized){
            this.expand(false);
            this.restoreSize = this.getSize();
            this.restorePos = this.getPosition(true);
            if (this.maximizable){
                this.tools.maximize.hide();
                this.tools.restore.show();
            }
            this.maximized = true;
            this.el.disableShadow();

            if(this.dd){
                this.dd.lock();
            }
            if(this.collapsible){
                this.tools.toggle.hide();
            }
            this.el.addClass('x-window-maximized');
            this.container.addClass('x-window-maximized-ct');

            this.setPosition(0, 0);
            this.fitContainer();
            this.fireEvent('maximize', this);
        }
    },

    /**
     * Restores a maximized window back to its original size and position prior to being maximized and also replaces
     * the 'restore' tool button with the 'maximize' tool button.
     */
    restore : function(){
        if(this.maximized){
            this.el.removeClass('x-window-maximized');
            this.tools.restore.hide();
            this.tools.maximize.show();
            this.setPosition(this.restorePos[0], this.restorePos[1]);
            this.setSize(this.restoreSize.width, this.restoreSize.height);
            delete this.restorePos;
            delete this.restoreSize;
            this.maximized = false;
            this.el.enableShadow(true);

            if(this.dd){
                this.dd.unlock();
            }
            if(this.collapsible){
                this.tools.toggle.show();
            }
            this.container.removeClass('x-window-maximized-ct');

            this.doConstrain();
            this.fireEvent('restore', this);
        }
    },

    /**
     * A shortcut method for toggling between {@link #maximize} and {@link #restore} based on the current maximized
     * state of the window.
     */
    toggleMaximize : function(){
        this[this.maximized ? 'restore' : 'maximize']();
    },

    // private
    fitContainer : function(){
        var vs = this.container.getViewSize();
        this.setSize(vs.width, vs.height);
    },

    // private
    // z-index is managed by the WindowManager and may be overwritten at any time
    setZIndex : function(index){
        if(this.modal){
            this.mask.setStyle("z-index", index);
        }
        this.el.setZIndex(++index);
        index += 5;

        if(this.resizer){
            this.resizer.proxy.setStyle("z-index", ++index);
        }

        this.lastZIndex = index;
    },

    /**
     * Aligns the window to the specified element
     * @param {Mixed} element The element to align to.
     * @param {String} position The position to align to (see {@link Ext.Element#alignTo} for more details).
     * @param {Array} offsets (optional) Offset the positioning by [x, y]
     * @return {Ext.Window} this
     */
    alignTo : function(element, position, offsets){
        var xy = this.el.getAlignToXY(element, position, offsets);
        this.setPagePosition(xy[0], xy[1]);
        return this;
    },

    /**
     * Anchors this window to another element and realigns it when the window is resized or scrolled.
     * @param {Mixed} element The element to align to.
     * @param {String} position The position to align to (see {@link Ext.Element#alignTo} for more details)
     * @param {Array} offsets (optional) Offset the positioning by [x, y]
     * @param {Boolean/Number} monitorScroll (optional) true to monitor body scroll and reposition. If this parameter
     * is a number, it is used as the buffer delay (defaults to 50ms).
     * @return {Ext.Window} this
     */
    anchorTo : function(el, alignment, offsets, monitorScroll, _pname){
        var action = function(){
            this.alignTo(el, alignment, offsets);
        };
        Ext.EventManager.onWindowResize(action, this);
        var tm = typeof monitorScroll;
        if(tm != 'undefined'){
            Ext.EventManager.on(window, 'scroll', action, this,
                {buffer: tm == 'number' ? monitorScroll : 50});
        }
        action.call(this);
        this[_pname] = action;
        return this;
    },

    /**
     * Brings this window to the front of any other visible windows
     * @return {Ext.Window} this
     */
    toFront : function(){
        if(this.manager.bringToFront(this)){
            this.focus();
        }
        return this;
    },

    /**
     * Makes this the active window by showing its shadow, or deactivates it by hiding its shadow.  This method also
     * fires the {@link #activate} or {@link #deactivate} event depending on which action occurred.
     * @param {Boolean} active True to activate the window, false to deactivate it (defaults to false)
     */
    setActive : function(active){
        if(active){
            if(!this.maximized){
                this.el.enableShadow(true);
            }
            this.fireEvent('activate', this);
        }else{
            this.el.disableShadow();
            this.fireEvent('deactivate', this);
        }
    },

    /**
     * Sends this window to the back of (lower z-index than) any other visible windows
     * @return {Ext.Window} this
     */
    toBack : function(){
        this.manager.sendToBack(this);
        return this;
    },

    /**
     * Centers this window in the viewport
     * @return {Ext.Window} this
     */
    center : function(){
        var xy = this.el.getAlignToXY(this.container, 'c-c');
        this.setPagePosition(xy[0], xy[1]);
        return this;
    }

    /**
     * @cfg {Boolean} autoWidth @hide
     **/
});
Ext.reg('window', Ext.Window);

// private - custom Window DD implementation
Ext.Window.DD = function(win){
    this.win = win;
    Ext.Window.DD.superclass.constructor.call(this, win.el.id, 'WindowDD-'+win.id);
    this.setHandleElId(win.header.id);
    this.scroll = false;
};

Ext.extend(Ext.Window.DD, Ext.dd.DD, {
    moveOnly:true,
    headerOffsets:[100, 25],
    startDrag : function(){
        var w = this.win;
        this.proxy = w.ghost();
        if(w.constrain !== false){
            var so = w.el.shadowOffset;
            this.constrainTo(w.container, {right: so, left: so, bottom: so});
        }else if(w.constrainHeader !== false){
            var s = this.proxy.getSize();
            this.constrainTo(w.container, {right: -(s.width-this.headerOffsets[0]), bottom: -(s.height-this.headerOffsets[1])});
        }
    },
    b4Drag : Ext.emptyFn,

    onDrag : function(e){
        this.alignElWithMouse(this.proxy, e.getPageX(), e.getPageY());
    },

    endDrag : function(e){
        this.win.unghost();
        this.win.saveState();
    }
});
