/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.ux.SlidingPager = Ext.extend(Ext.util.Observable, {
    init : function(pbar){
        this.pagingBar = pbar;

        pbar.on('render', this.onRender, this);
        pbar.on('beforedestroy', this.onDestroy, this);
    },

    onRender : function(pbar){
        Ext.each(pbar.items.getRange(2,6), function(c){
            c.hide();
        });
        var td = document.createElement("td");
        pbar.tr.insertBefore(td, pbar.tr.childNodes[5]);

        td.style.padding = '0 5px';

        this.slider = new Ext.Slider({
            width: 114,
            minValue: 1,
            maxValue: 1,
            plugins:new Ext.ux.SliderTip({
                bodyStyle:'padding:5px;',
                getText : function(s){
                    return String.format('Page <b>{0}</b> of <b>{1}</b>', s.value, s.maxValue);
                }
            })
        });
        this.slider.render(td);

        this.slider.on('changecomplete', function(s, v){
            pbar.changePage(v);
        });

        pbar.on('change', function(pb, data){
            this.slider.maxValue = data.pages;
            this.slider.setValue(data.activePage);
        }, this);
    },

    onDestroy : function(){
        this.slider.destroy();
    }
});