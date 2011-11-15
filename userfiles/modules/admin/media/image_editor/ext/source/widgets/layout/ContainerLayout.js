/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/**
 * @class Ext.layout.ContainerLayout
 * Every layout is composed of one or more {@link Ext.Container} elements internally, and ContainerLayout provides
 * the basic foundation for all other layout classes in Ext.  It is a non-visual class that simply provides the
 * base logic required for a Container to function as a layout.  This class is intended to be extended and should
 * generally not need to be created directly via the new keyword.
 */
Ext.layout.ContainerLayout = function(config){
    Ext.apply(this, config);
};

Ext.layout.ContainerLayout.prototype = {
    /**
     * @cfg {String} extraCls
     * An optional extra CSS class that will be added to the container (defaults to '').  This can be useful for
     * adding customized styles to the container or any of its children using standard CSS rules.
     */
    /**
     * @cfg {Boolean} renderHidden
     * True to hide each contained item on render (defaults to false).
     */

    /**
     * A reference to the {@link Ext.Component} that is active.  For example,
     * if(myPanel.layout.activeItem.id == 'item-1') { ... }.  activeItem only applies to layout styles that can
     * display items one at a time (like {@link Ext.layout.Accordion}, {@link Ext.layout.CardLayout}
     * and {@link Ext.layout.FitLayout}).  Read-only.  Related to {@link Ext.Container#activeItem}.
     * @type {Ext.Component}
     * @property activeItem
     */

    // private
    monitorResize:false,
    // private
    activeItem : null,

    // private
    layout : function(){
        var target = this.container.getLayoutTarget();
        this.onLayout(this.container, target);
        this.container.fireEvent('afterlayout', this.container, this);
    },

    // private
    onLayout : function(ct, target){
        this.renderAll(ct, target);
    },

    // private
    isValidParent : function(c, target){
		var el = c.getPositionEl ? c.getPositionEl() : c.getEl();
		return el.dom.parentNode == target.dom;
    },

    // private
    renderAll : function(ct, target){
        var items = ct.items.items;
        for(var i = 0, len = items.length; i < len; i++) {
            var c = items[i];
            if(c && (!c.rendered || !this.isValidParent(c, target))){
                this.renderItem(c, i, target);
            }
        }
    },

    // private
    renderItem : function(c, position, target){
        if(c && !c.rendered){
            c.render(target, position);
            if(this.extraCls){
            	var t = c.getPositionEl ? c.getPositionEl() : c;
            	t.addClass(this.extraCls);
            }
            if (this.renderHidden && c != this.activeItem) {
                c.hide();
            }
        }else if(c && !this.isValidParent(c, target)){
            if(this.extraCls){
                c.addClass(this.extraCls);
            }
            if(typeof position == 'number'){
                position = target.dom.childNodes[position];
            }
            target.dom.insertBefore(c.getEl().dom, position || null);
            if (this.renderHidden && c != this.activeItem) {
                c.hide();
            }
        }
    },

    // private
    onResize: function(){
        if(this.container.collapsed){
            return;
        }
        var b = this.container.bufferResize;
        if(b){
            if(!this.resizeTask){
                this.resizeTask = new Ext.util.DelayedTask(this.layout, this);
                this.resizeBuffer = typeof b == 'number' ? b : 100;
            }
            this.resizeTask.delay(this.resizeBuffer);
        }else{
            this.layout();
        }
    },

    // private
    setContainer : function(ct){
        if(this.monitorResize && ct != this.container){
            if(this.container){
                this.container.un('resize', this.onResize, this);
            }
            if(ct){
                ct.on('resize', this.onResize, this);
            }
        }
        this.container = ct;
    },

    // private
    parseMargins : function(v){
        var ms = v.split(' ');
        var len = ms.length;
        if(len == 1){
            ms[1] = ms[0];
            ms[2] = ms[0];
            ms[3] = ms[0];
        }
        if(len == 2){
            ms[2] = ms[0];
            ms[3] = ms[1];
        }
        return {
            top:parseInt(ms[0], 10) || 0,
            right:parseInt(ms[1], 10) || 0,
            bottom:parseInt(ms[2], 10) || 0,
            left:parseInt(ms[3], 10) || 0
        };
    },

    destroy : Ext.emptyFn
};
Ext.Container.LAYOUTS['auto'] = Ext.layout.ContainerLayout;