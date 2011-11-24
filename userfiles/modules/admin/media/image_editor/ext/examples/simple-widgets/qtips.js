/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.onReady(function(){
    new Ext.ToolTip({
        target: 'tip1',
        html: 'A very simple tooltip'
    });

    new Ext.ToolTip({
        target: 'ajax-tip',
        width: 200,
        autoLoad: {url: 'ajax-tip.html'},
        dismissDelay: 15000 // auto hide after 15 seconds
    });

    new Ext.ToolTip({
        target: 'tip2',
        html: 'Click the X to close me',
        title: 'My Tip Title',
        autoHide: false,
        closable: true,
        draggable:true
    });

    new Ext.ToolTip({
        target: 'track-tip',
        title: 'Mouse Track',
        width:200,
        html: 'This tip will follow the mouse while it is over the element',
        trackMouse:true
    });


    Ext.QuickTips.init();

});