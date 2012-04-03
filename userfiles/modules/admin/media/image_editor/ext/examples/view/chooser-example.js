/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/*
 * Ext JS Library 2.0
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.onReady(function(){
    var chooser, btn;
    
    function insertImage(data){
    	Ext.DomHelper.append('images', {
    		tag: 'img', src: data.url, style:'margin:10px;visibility:hidden;'
    	}, true).show(true).frame();
    	btn.focus();
    };
    
    function choose(btn){
    	if(!chooser){
    		chooser = new ImageChooser({
    			url:'get-images.php',
    			width:515, 
    			height:350
    		});
    	}
    	chooser.show(btn.getEl(), insertImage);
    };
    
    btn = new Ext.Button({
	    text: "Insert Image",
		handler: choose,
        renderTo: 'buttons'
    });
});
