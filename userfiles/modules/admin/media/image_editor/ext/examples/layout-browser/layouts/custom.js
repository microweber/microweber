/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */


// We are adding these custom layouts to a namespace that does not
// exist by default in Ext, so we have to add the namespace first:
Ext.ns('Ext.ux.layout');

/*
 * ================  CenterLayout  =======================
 */
/**
 * @class Ext.ux.layout.CenterLayout
 * @extends Ext.layout.FitLayout
 * <p>This is a very simple layout style used to center contents within a container.  This layout works within
 * nested containers and can also be used as expected as a Viewport layout to center the page layout.</p>
 * <p>As a subclass of FitLayout, CenterLayout expects to have a single child panel of the container that uses 
 * the layout.  The layout does not require any config options, although the child panel contained within the
 * layout must provide a fixed or percentage width.  The child panel's height will fit to the container by
 * default, but you can specify <tt>autoHeight:true</tt> to allow it to autosize based on its content height.  
 * Example usage:</p> 
 * <pre><code>
// The content panel is centered in the container
var p = new Ext.Panel({
    title: 'Center Layout',
    layout: 'ux.center',
    items: [{
        title: 'Centered Content',
        width: '75%',
        html: 'Some content'
    }]
});

// If you leave the title blank and specify no border
// you'll create a non-visual, structural panel just
// for centering the contents in the main container.
var p = new Ext.Panel({
    layout: 'ux.center',
    border: false,
    items: [{
        title: 'Centered Content',
        width: 300,
        autoHeight: true,
        html: 'Some content'
    }]
});
</code></pre>
 */
Ext.ux.layout.CenterLayout = Ext.extend(Ext.layout.FitLayout, {
	// private
    setItemSize : function(item, size){
        this.container.addClass('ux-layout-center');
        item.addClass('ux-layout-center-item');
        if(item && size.height > 0){
            if(item.width){
                size.width = item.width;
            }
            item.setSize(size);
        }
    }
});
Ext.Container.LAYOUTS['ux.center'] = Ext.ux.layout.CenterLayout;

/*
 * CenterLayout demo panel
 */
var centerLayout = {
	id: 'center-panel',
    layout:'ux.center',
    items: {
        title: 'Centered Panel: 75% of container width and fit height',
        layout: 'ux.center',
        autoScroll: true,
        width: '75%',
        bodyStyle: 'padding:20px 0;',
        items: [{
        	title: 'Inner Centered Panel',
        	html: 'Fixed 300px wide and auto height. The container panel will also autoscroll if narrower than 300px.',
        	width: 300,
        	frame: true,
        	autoHeight: true,
        	bodyStyle: 'padding:10px 20px;'
        }]
    }
};

/*
 * ================  RowLayout  =======================
 */
/**
 * @class Ext.ux.layout.RowLayout
 * @extends Ext.layout.ContainerLayout
 * <p>This is the layout style of choice for creating structural layouts in a multi-row format where the height of
 * each row can be specified as a percentage or fixed height.  Row widths can also be fixed, percentage or auto.
 * This class is intended to be extended or created via the layout:'ux.row' {@link Ext.Container#layout} config,
 * and should generally not need to be created directly via the new keyword.</p>
 * <p>RowLayout does not have any direct config options (other than inherited ones), but it does support a
 * specific config property of <b><tt>rowHeight</tt></b> that can be included in the config of any panel added to it.  The
 * layout will use the rowHeight (if present) or height of each panel during layout to determine how to size each panel.
 * If height or rowHeight is not specified for a given panel, its height will default to the panel's height (or auto).</p>
 * <p>The height property is always evaluated as pixels, and must be a number greater than or equal to 1.
 * The rowHeight property is always evaluated as a percentage, and must be a decimal value greater than 0 and
 * less than 1 (e.g., .25).</p>
 * <p>The basic rules for specifying row heights are pretty simple.  The logic makes two passes through the
 * set of contained panels.  During the first layout pass, all panels that either have a fixed height or none
 * specified (auto) are skipped, but their heights are subtracted from the overall container height.  During the second
 * pass, all panels with rowHeights are assigned pixel heights in proportion to their percentages based on
 * the total <b>remaining</b> container height.  In other words, percentage height panels are designed to fill the space
 * left over by all the fixed-height and/or auto-height panels.  Because of this, while you can specify any number of rows
 * with different percentages, the rowHeights must always add up to 1 (or 100%) when added together, otherwise your
 * layout may not render as expected.  Example usage:</p>
 * <pre><code>
// All rows are percentages -- they must add up to 1
var p = new Ext.Panel({
    title: 'Row Layout - Percentage Only',
    layout:'ux.row',
    items: [{
        title: 'Row 1',
        rowHeight: .25 
    },{
        title: 'Row 2',
        rowHeight: .6
    },{
        title: 'Row 3',
        rowHeight: .15
    }]
});

// Mix of height and rowHeight -- all rowHeight values must add
// up to 1. The first row will take up exactly 120px, and the last two
// rows will fill the remaining container height.
var p = new Ext.Panel({
    title: 'Row Layout - Mixed',
    layout:'ux.row',
    items: [{
        title: 'Row 1',
        height: 120,
        // standard panel widths are still supported too:
        width: '50%' // or 200
    },{
        title: 'Row 2',
        rowHeight: .8,
        width: 300
    },{
        title: 'Row 3',
        rowHeight: .2
    }]
});
</code></pre>
 */
Ext.ux.layout.RowLayout = Ext.extend(Ext.layout.ContainerLayout, {
    // private
    monitorResize:true,

    // private
    isValidParent : function(c, target){
        return c.getEl().dom.parentNode == this.innerCt.dom;
    },

    // private
    onLayout : function(ct, target){
        var rs = ct.items.items, len = rs.length, r, i;

        if(!this.innerCt){
            target.addClass('ux-row-layout-ct');
            this.innerCt = target.createChild({cls:'x-row-inner'});
        }
        this.renderAll(ct, this.innerCt);

        var size = target.getViewSize();

        if(size.width < 1 && size.height < 1){ // display none?
            return;
        }

        var h = size.height - target.getPadding('tb'),
            ph = h;

        this.innerCt.setSize({height:h});
        
        // some rows can be percentages while others are fixed
        // so we need to make 2 passes
        
        for(i = 0; i < len; i++){
            r = rs[i];
            if(!r.rowHeight){
                ph -= (r.getSize().height + r.getEl().getMargins('tb'));
            }
        }

        ph = ph < 0 ? 0 : ph;

        for(i = 0; i < len; i++){
            r = rs[i];
            if(r.rowHeight){
                r.setSize({height: Math.floor(r.rowHeight*ph) - r.getEl().getMargins('tb')});
            }
        }
    }
    
    /**
     * @property activeItem
     * @hide
     */
});
Ext.Container.LAYOUTS['ux.row'] = Ext.ux.layout.RowLayout;

/*
 * RowLayout demo panel
 */
var rowLayout = {
	id: 'row-panel',
	bodyStyle: 'padding:5px',
	layout: 'ux.row',
    title: 'Row Layout',
    items: [{
        title: 'Height = 25%, Width = 50%',
        rowHeight: .25,
        width: '50%'
    },{
        title: 'Height = 100px, Width = 300px',
        height: 100,
        width: 300
    },{
    	title: 'Height = 75%, Width = fit',
    	rowHeight: .75
    }]
};

