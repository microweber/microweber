/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/**
 * @class Ext.Slider
 * @extends Ext.BoxComponent
 * Slider which supports vertical or horizontal orientation, keyboard adjustments, 
 * configurable snapping, axis clicking and animation. Can be added as an item to
 * any container. Example usage:
<pre><code>
new Ext.Slider({
    renderTo: Ext.getBody(),
    width: 200,
    value: 50,
    increment: 10,
    minValue: 0,
    maxValue: 100
});
</code></pre>
 */
Ext.Slider = Ext.extend(Ext.BoxComponent, {
	/**
	 * @cfg {Number} value The value to initialize the slider with. Defaults to minValue.
	 */
	/**
	 * @cfg {Boolean} vertical Orient the Slider vertically rather than horizontally, defaults to false.
	 */
    vertical: false,
	/**
	 * @cfg {Number} minValue The minimum value for the Slider. Defaults to 0.
	 */
    minValue: 0,
	/**
	 * @cfg {Number} maxValue The maximum value for the Slider. Defaults to 100.
	 */	
    maxValue: 100,
	/**
	 * @cfg {Number} keyIncrement How many units to change the Slider when adjusting with keyboard navigation. Defaults to 1. If the increment config is larger, it will be used instead.
	 */
    keyIncrement: 1,
	/**
	 * @cfg {Number} increment How many units to change the slider when adjusting by drag and drop. Use this option to enable 'snapping'.
	 */
    increment: 0,
	// private
    clickRange: [5,15],
	/**
	 * @cfg {Boolean} clickToChange Determines whether or not clicking on the Slider axis will change the slider. Defaults to true
	 */
    clickToChange : true,
	/**
	 * @cfg {Boolean} animate Turn on or off animation. Defaults to true
	 */
    animate: true,

    /**
     * True while the thumb is in a drag operation
     * @type boolean
     */
    dragging: false,

    // private override
    initComponent : function(){
        if(this.value === undefined){
            this.value = this.minValue;
        }
        Ext.Slider.superclass.initComponent.call(this);
        this.keyIncrement = Math.max(this.increment, this.keyIncrement); 
        this.addEvents(
            /**
             * @event beforechange
             * Fires before the slider value is changed. By returning false from an event handler, 
             * you can cancel the event and prevent the slider from changing.
			 * @param {Ext.Slider} slider The slider
			 * @param {Number} newValue The new value which the slider is being changed to.
			 * @param {Number} oldValue The old value which the slider was previously.
             */		
			'beforechange', 
			/**
			 * @event change
			 * Fires when the slider value is changed.
			 * @param {Ext.Slider} slider The slider
			 * @param {Number} newValue The new value which the slider has been changed to.
			 */
			'change',
			/**
			 * @event changecomplete
			 * Fires when the slider value is changed by the user and any drag operations have completed.
			 * @param {Ext.Slider} slider The slider
			 * @param {Number} newValue The new value which the slider has been changed to.
			 */
			'changecomplete',
			/**
			 * @event dragstart
             * Fires after a drag operation has started.
			 * @param {Ext.Slider} slider The slider
			 * @param {Ext.EventObject} e The event fired from Ext.dd.DragTracker
			 */
			'dragstart', 
			/**
			 * @event drag
             * Fires continuously during the drag operation while the mouse is moving.
			 * @param {Ext.Slider} slider The slider
			 * @param {Ext.EventObject} e The event fired from Ext.dd.DragTracker
			 */
			'drag', 
			/**
			 * @event dragend
             * Fires after the drag operation has completed.
			 * @param {Ext.Slider} slider The slider
			 * @param {Ext.EventObject} e The event fired from Ext.dd.DragTracker
			 */
			'dragend'
		);

        if(this.vertical){
            Ext.apply(this, Ext.Slider.Vertical);
        }
    },

	// private override
    onRender : function(){
        this.autoEl = {
            cls: 'x-slider ' + (this.vertical ? 'x-slider-vert' : 'x-slider-horz'),
            cn:{cls:'x-slider-end',cn:{cls:'x-slider-inner',cn:[{cls:'x-slider-thumb'},{tag:'a', cls:'x-slider-focus', href:"#", tabIndex: '-1', hidefocus:'on'}]}}
        };
        Ext.Slider.superclass.onRender.apply(this, arguments);
        this.endEl = this.el.first();
        this.innerEl = this.endEl.first();
        this.thumb = this.innerEl.first();
        this.halfThumb = (this.vertical ? this.thumb.getHeight() : this.thumb.getWidth())/2;
        this.focusEl = this.thumb.next();
        this.initEvents();
    },

	// private override
    initEvents : function(){
        this.thumb.addClassOnOver('x-slider-thumb-over');
        this.mon(this.el, 'mousedown', this.onMouseDown, this);
        this.mon(this.el, 'keydown', this.onKeyDown, this);

        this.focusEl.swallowEvent("click", true);

        this.tracker = new Ext.dd.DragTracker({
            onBeforeStart: this.onBeforeDragStart.createDelegate(this),
            onStart: this.onDragStart.createDelegate(this),
            onDrag: this.onDrag.createDelegate(this),
            onEnd: this.onDragEnd.createDelegate(this),
            tolerance: 3,
            autoStart: 300
        });
        this.tracker.initEl(this.thumb);
        this.on('beforedestroy', this.tracker.destroy, this.tracker);
    },

	// private override
    onMouseDown : function(e){
        if(this.disabled) {return;}
        if(this.clickToChange && e.target != this.thumb.dom){
            var local = this.innerEl.translatePoints(e.getXY());
            this.onClickChange(local);
        }
        this.focus();
    },

	// private
    onClickChange : function(local){
        if(local.top > this.clickRange[0] && local.top < this.clickRange[1]){
            this.setValue(Math.round(this.reverseValue(local.left)), undefined, true);
        }
    },
	
	// private
    onKeyDown : function(e){
        if(this.disabled){e.preventDefault();return;}
        var k = e.getKey();
        switch(k){
            case e.UP:
            case e.RIGHT:
                e.stopEvent();
                if(e.ctrlKey){
                    this.setValue(this.maxValue, undefined, true);
                }else{
                    this.setValue(this.value+this.keyIncrement, undefined, true);
                }
            break;
            case e.DOWN:
            case e.LEFT:
                e.stopEvent();
                if(e.ctrlKey){
                    this.setValue(this.minValue, undefined, true);
                }else{
                    this.setValue(this.value-this.keyIncrement, undefined, true);
                }
            break;
            default:
                e.preventDefault();
        }
    },
	
	// private
    doSnap : function(value){
        if(!this.increment || this.increment == 1 || !value) {
            return value;
        }
        var newValue = value, inc = this.increment;
        var m = value % inc;
        if(m > 0){
            if(m > (inc/2)){
                newValue = value + (inc-m);
            }else{
                newValue = value - m;
            }
        }
        return newValue.constrain(this.minValue,  this.maxValue);
    },
	
	// private
    afterRender : function(){
        Ext.Slider.superclass.afterRender.apply(this, arguments);
        if(this.value !== undefined){
            var v = this.normalizeValue(this.value);
            if(v !== this.value){
                delete this.value;
                this.setValue(v, false);
            }else{
                this.moveThumb(this.translateValue(v), false);
            }
        }
    },

	// private
    getRatio : function(){
        var w = this.innerEl.getWidth();
        var v = this.maxValue - this.minValue;
        return v == 0 ? w : (w/v);
    },

	// private
    normalizeValue : function(v){
       if(typeof v != 'number'){
            v = parseInt(v);
        }
        v = Math.round(v);
        v = this.doSnap(v);
        v = v.constrain(this.minValue, this.maxValue);
        return v;
    },

	/**
	 * Programmatically sets the value of the Slider. Ensures that the value is constrained within
	 * the minValue and maxValue.
	 * @param {Number} value The value to set the slider to. (This will be constrained within minValue and maxValue)
	 * @param {Boolean} animate Turn on or off animation, defaults to true
	 */
    setValue : function(v, animate, changeComplete){
        v = this.normalizeValue(v);
        if(v !== this.value && this.fireEvent('beforechange', this, v, this.value) !== false){
            this.value = v;
            this.moveThumb(this.translateValue(v), animate !== false);
            this.fireEvent('change', this, v);
            if(changeComplete){
                this.fireEvent('changecomplete', this, v);
            }
        }
    },

	// private
    translateValue : function(v){
        var ratio = this.getRatio();
        return (v * ratio)-(this.minValue * ratio)-this.halfThumb;
    },

	reverseValue : function(pos){
        var ratio = this.getRatio();
        return (pos+this.halfThumb+(this.minValue * ratio))/ratio;
    },

	// private
    moveThumb: function(v, animate){
        if(!animate || this.animate === false){
            this.thumb.setLeft(v);
        }else{
            this.thumb.shift({left: v, stopFx: true, duration:.35});
        }
    },

	// private
    focus : function(){
        this.focusEl.focus(10);
    },

	// private
    onBeforeDragStart : function(e){
        return !this.disabled;
    },

	// private
    onDragStart: function(e){
        this.thumb.addClass('x-slider-thumb-drag');
        this.dragging = true;
        this.dragStartValue = this.value;
        this.fireEvent('dragstart', this, e);
    },

	// private
    onDrag: function(e){
        var pos = this.innerEl.translatePoints(this.tracker.getXY());
        this.setValue(Math.round(this.reverseValue(pos.left)), false);
        this.fireEvent('drag', this, e);
    },
	
	// private
    onDragEnd: function(e){
        this.thumb.removeClass('x-slider-thumb-drag');
        this.dragging = false;
        this.fireEvent('dragend', this, e);
        if(this.dragStartValue != this.value){
            this.fireEvent('changecomplete', this, this.value);
        }
    },

    // private
    onResize : function(w, h){
        this.innerEl.setWidth(w - (this.el.getPadding('l') + this.endEl.getPadding('r')));
        this.syncThumb();
    },
    
    /**
     * Synchronizes the thumb position to the proper proportion of the total component width based
     * on the current slider {@link #value}.  This will be called automatically when the Slider
     * is resized by a layout, but if it is rendered auto width, this method can be called from
     * another resize handler to sync the Slider if necessary.
     */
    syncThumb : function(){
        if(this.rendered){
            this.moveThumb(this.translateValue(this.value));
        }
    },
	
	/**
	 * Returns the current value of the slider
	 * @return {Number} The current value of the slider
	 */
    getValue : function(){
        return this.value;
    }
});
Ext.reg('slider', Ext.Slider);

// private class to support vertical sliders
Ext.Slider.Vertical = {
    onResize : function(w, h){
        this.innerEl.setHeight(h - (this.el.getPadding('t') + this.endEl.getPadding('b')));
        this.syncThumb();
    },

    getRatio : function(){
        var h = this.innerEl.getHeight();
        var v = this.maxValue - this.minValue;
        return h/v;
    },

    moveThumb: function(v, animate){
        if(!animate || this.animate === false){
            this.thumb.setBottom(v);
        }else{
            this.thumb.shift({bottom: v, stopFx: true, duration:.35});
        }
    },

    onDrag: function(e){
        var pos = this.innerEl.translatePoints(this.tracker.getXY());
        var bottom = this.innerEl.getHeight()-pos.top;
        this.setValue(Math.round(bottom/this.getRatio()), false);
        this.fireEvent('drag', this, e);
    },

    onClickChange : function(local){
        if(local.left > this.clickRange[0] && local.left < this.clickRange[1]){
            var bottom = this.innerEl.getHeight()-local.top;
            this.setValue(Math.round(bottom/this.getRatio()), undefined, true);
        }
    }
};