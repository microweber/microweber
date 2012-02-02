/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.onReady(function(){
    var propsGrid = new Ext.grid.PropertyGrid({
        el:'props-grid',
        nameText: 'Properties Grid',
        width:300,
        autoHeight:true,
        viewConfig : {
            forceFit:true,
            scrollOffset:2 // the grid will never have scrollbars
        }
    });

    propsGrid.render();

    propsGrid.setSource({
        "(name)": "Properties Grid",
        "grouping": false,
        "autoFitColumns": true,
        "productionQuality": false,
        "created": new Date(Date.parse('10/15/2006')),
        "tested": false,
        "version": .01,
        "borderWidth": 1
    });
});