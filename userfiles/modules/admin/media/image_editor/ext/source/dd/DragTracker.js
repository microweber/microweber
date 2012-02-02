/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.dd.DragTracker = function(config){
    Ext.apply(this, config);
    this.addEvents(
        'mousedown',
        'mouseup',
        'mousemove',
        'dragstart',
        'dragend',
        'drag'
    );

    this.dragRegion = new Ext.lib.Region(0,0,0,0);

    if(this.el){
        this.initEl(this.el);
    }
}

Ext.extend(Ext.dd.DragTracker, Ext.util.Observable,  {
    active: false,
    tolerance: 5,
    autoStart: false,

    initEl: function(el){
        this.el = Ext.get(el);
        el.on('mousedown', this.onMouseDown, this,
                this.delegate ? {delegate: this.delegate} : undefined);
    },

    destroy : function(){
        this.el.un('mousedown', this.onMouseDown, this);
    },

    onMouseDown: function(e, target){
        if(this.fireEvent('mousedown', this, e) !== false && this.onBeforeStart(e) !== false){
            this.startXY = this.lastXY = e.getXY();
            this.dragTarget = this.delegate ? target : this.el.dom;
            e.preventDefault();
            var doc = Ext.getDoc();
            doc.on('mouseup', this.onMouseUp, this);
            doc.on('mousemove', this.onMouseMove, this);
            doc.on('selectstart', this.stopSelect, this);
            if(this.autoStart){
                this.timer = this.triggerStart.defer(this.autoStart === true ? 1000 : this.autoStart, this);
            }
        }
    },

    onMouseMove: function(e, target){
        e.preventDefault();
        var xy = e.getXY(), s = this.startXY;
        this.lastXY = xy;
        if(!this.active){
            if(Math.abs(s[0]-xy[0]) > this.tolerance || Math.abs(s[1]-xy[1]) > this.tolerance){
                this.triggerStart();
            }else{
                return;
            }
        }
        this.fireEvent('mousemove', this, e);
        this.onDrag(e);
        this.fireEvent('drag', this, e);
    },

    onMouseUp: function(e){
        var doc = Ext.getDoc();
        doc.un('mousemove', this.onMouseMove, this);
        doc.un('mouseup', this.onMouseUp, this);
        doc.un('selectstart', this.stopSelect, this);
        e.preventDefault();
        this.clearStart();
        this.active = false;
        delete this.elRegion;
        this.fireEvent('mouseup', this, e);
        this.onEnd(e);
        this.fireEvent('dragend', this, e);
    },

    triggerStart: function(isTimer){
        this.clearStart();
        this.active = true;
        this.onStart(this.startXY);
        this.fireEvent('dragstart', this, this.startXY);
    },

    clearStart : function(){
        if(this.timer){
            clearTimeout(this.timer);
            delete this.timer;
        }
    },

    stopSelect : function(e){
        e.stopEvent();
        return false;
    },

    onBeforeStart : function(e){

    },

    onStart : function(xy){

    },

    onDrag : function(e){

    },

    onEnd : function(e){

    },

    getDragTarget : function(){
        return this.dragTarget;
    },

    getDragCt : function(){
        return this.el;
    },

    getXY : function(constrain){
        return constrain ?
               this.constrainModes[constrain].call(this, this.lastXY) : this.lastXY;
    },

    getOffset : function(constrain){
        var xy = this.getXY(constrain);
        var s = this.startXY;
        return [s[0]-xy[0], s[1]-xy[1]];
    },

    constrainModes: {
        'point' : function(xy){

            if(!this.elRegion){
                this.elRegion = this.getDragCt().getRegion();
            }

            var dr = this.dragRegion;

            dr.left = xy[0];
            dr.top = xy[1];
            dr.right = xy[0];
            dr.bottom = xy[1];

            dr.constrainTo(this.elRegion);

            return [dr.left, dr.top];
        }
    }
});