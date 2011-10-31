/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.onReady(function(){

    var tabs = new Ext.TabPanel({
        renderTo:'tabs',
        resizeTabs:true, // turn on tab resizing
        minTabWidth: 115,
        tabWidth:135,
        enableTabScroll:true,
        width:600,
        height:250,
        defaults: {autoScroll:true},
        plugins: new Ext.ux.TabCloseMenu()
    });

    // tab generation code
    var index = 0;
    while(index < 7){
        addTab();
    }
    function addTab(){
        tabs.add({
            title: 'New Tab ' + (++index),
            iconCls: 'tabs',
            html: 'Tab Body ' + (index) + '<br/><br/>'
                    + Ext.example.bogusMarkup,
            closable:true
        }).show();
    }

    new Ext.Button({
        text: 'Add Tab',
        handler: addTab,
        iconCls:'new-tab'
    }).render(document.body, 'tabs');
});