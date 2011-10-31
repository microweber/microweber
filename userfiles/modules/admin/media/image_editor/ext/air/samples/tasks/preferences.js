/*
 * Ext JS Library 0.20
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.onReady(function(){
	var win = window.nativeWindow;
	var opener = Ext.air.NativeWindow.getRootHtmlWindow();
	
	var d = new Date().clearTime(true);
	d = d.add('mi', opener.Ext.state.Manager.get('defaultReminder'));
	
	var time = new Ext.get('time');
	time.dom.value = d.format('g:i A');	
	
	var ok = new Ext.Button({
		text: 'OK',
		minWidth: 80,
		renderTo: 'btns',
		handler: function(){
			var t = Date.parseDate(time.getValue(), 'g:i A');
			if(t){
				var m = t.getMinutes() + (t.getHours()*60);
				opener.Ext.state.Manager.set('defaultReminder', m);
			}
			win.close();
		}
	});
	
	var close = new Ext.Button({
		text: 'Cancel',
		minWidth: 80,
		renderTo: 'btns',
		handler: function(){
			win.close();
		}
	});
	
	win.visible = true;
	win.activate();
});

    

