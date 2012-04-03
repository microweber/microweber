/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/**
 * @class Ext.layout.AnchorLayout
 * @extends Ext.layout.ContainerLayout
 * <p>This is a layout that enables anchoring of contained elements relative to the container's dimensions.  If
 * the container is resized, all anchored items are automatically rerendered according to their anchor rules.
 * This class is intended to be extended or created via the layout:'anchor' {@link Ext.Container#layout} config,
 * and should generally not need to be created directly via the new keyword.</p>
 * <p>AnchorLayout does not have any direct config options (other than inherited ones).  However, the container
 * using the AnchorLayout can supply an anchoring-specific config property of <b>anchorSize</b>.  By default,
 * AnchorLayout will calculate anchor measurements based on the size of the container itself.  However, if
 * anchorSize is specifed, the layout will use it as a virtual container for the purposes of calculating anchor
 * measurements based on it instead, allowing the container to be sized independently of the anchoring logic if necessary.</p>
 * <p>The items added to an AnchorLayout can also supply an anchoring-specific config property of <b>anchor</b> which
 * is a string containing two values: the horizontal anchor value and the vertical anchor value (for example, '100% 50%').
 * This value is what tells the layout how the item should be anchored to the container.  The following types of
 * anchor values are supported:
 * <ul>
 * <li><b>Percentage</b>: Any value between 1 and 100, expressed as a percentage.  The first anchor is the percentage
 * width that the item should take up within the container, and the second is the percentage height.  Example: '100% 50%'
 * would render an item the complete width of the container and 1/2 its height.  If only one anchor value is supplied
 * it is assumed to be the width value and the height will default to auto.</li>
 * <li><b>Offsets</b>: Any positive or negative integer value.  The first anchor is the offset from the right edge of
 * the container, and the second is the offset from the bottom edge.  Example: '-50 -100' would render an item the
 * complete width of the container minus 50 pixels and the complete height minus 100 pixels.  If only one anchor value
 * is supplied it is assumed to be the right offset value and the bottom offset will default to 0.</li>
 * <li><b>Sides</b>: Valid values are 'right' (or 'r') and 'bottom' (or 'b').  Either the container must have a fixed
 * size or an anchorSize config value defined at render time in order for these to have any effect.</li>
 * </ul>
 * <p>Anchor values can also be mixed as needed.  For example, '-50 75%' would render the width offset from the
 * container right edge by 50 pixels and 75% of the container's height.</p>
 */
Ext.layout.AnchorLayout = Ext.extend(Ext.layout.ContainerLayout, {
    // private
    monitorResize:true,

    // private
    getAnchorViewSize : function(ct, target){
        return target.dom == document.body ?
                   target.getViewSize() : target.getStyleSize();
    },

    // private
    onLayout : function(ct, target){
        Ext.layout.AnchorLayout.superclass.onLayout.call(this, ct, target);

        var size = this.getAnchorViewSize(ct, target);

        var w = size.width, h = size.height;

        if(w < 20 || h < 20){
            return;
        }

        // find the container anchoring size
        var aw, ah;
        if(ct.anchorSize){
            if(typeof ct.anchorSize == 'number'){
                aw = ct.anchorSize;
            }else{
                aw = ct.anchorSize.width;
                ah = ct.anchorSize.height;
            }
        }else{
            aw = ct.initialConfig.width;
            ah = ct.initialConfig.height;
        }

        var cs = ct.items.items, len = cs.length, i, c, a, cw, ch;
        for(i = 0; i < len; i++){
            c = cs[i];
            if(c.anchor){
                a = c.anchorSpec;
                if(!a){ // cache all anchor values
                    var vs = c.anchor.split(' ');
                    c.anchorSpec = a = {
                        right: this.parseAnchor(vs[0], c.initialConfig.width, aw),
                        bottom: this.parseAnchor(vs[1], c.initialConfig.height, ah)
                    };
                }
                cw = a.right ? this.adjustWidthAnchor(a.right(w), c) : undefined;
                ch = a.bottom ? this.adjustHeightAnchor(a.bottom(h), c) : undefined;

                if(cw || ch){
                    c.setSize(cw || undefined, ch || undefined);
                }
            }
        }
    },

    // private
    parseAnchor : function(a, start, cstart){
        if(a && a != 'none'){
            var last;
            if(/^(r|right|b|bottom)$/i.test(a)){   // standard anchor
                var diff = cstart - start;
                return function(v){
                    if(v !== last){
                        last = v;
                        return v - diff;
                    }
                }
            }else if(a.indexOf('%') != -1){
                var ratio = parseFloat(a.replace('%', ''))*.01;   // percentage
                return function(v){
                    if(v !== last){
                        last = v;
                        return Math.floor(v*ratio);
                    }
                }
            }else{
                a = parseInt(a, 10);
                if(!isNaN(a)){                            // simple offset adjustment
                    return function(v){
                        if(v !== last){
                            last = v;
                            return v + a;
                        }
                    }
                }
            }
        }
        return false;
    },

    // private
    adjustWidthAnchor : function(value, comp){
        return value;
    },

    // private
    adjustHeightAnchor : function(value, comp){
        return value;
    }
    
    /**
     * @property activeItem
     * @hide
     */
});
Ext.Container.LAYOUTS['anchor'] = Ext.layout.AnchorLayout;