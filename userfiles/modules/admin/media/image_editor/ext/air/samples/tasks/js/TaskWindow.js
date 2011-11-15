/*
 * Ext JS Library 0.20
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.air.NativeWindowManager.getTaskWindow = function(taskId){
	var win, winId = 'task' + taskId;
	if(win = this.get(winId)) {
		win.instance.orderToFront();
	} else {
		win = new Ext.air.NativeWindow({
			id: winId,
			file: 'task.html?taskId=' + taskId,
			width: 500,
			height:350
		});
	}
	return win;
}

Ext.air.NativeWindowManager.getReminderWindow = function(taskId){
	var win, winId = 'reminder' + taskId;
	if(win = this.get(winId)) {
		win.instance.orderToFront();
	} else {
		win = new Ext.air.NativeWindow({
			id: winId,
			file: 'reminder.html?taskId=' + taskId,
			width:400,
			height:140,
			maximizable: false,
			resizable: false
		});
	}
	return win;
}

Ext.air.NativeWindowManager.getAboutWindow = function(){
	var win, winId = 'about';
	if(win = this.get(winId)) {
		win.instance.orderToFront();
	} else {
		win = new Ext.air.NativeWindow({
			id: winId,
			file: 'about.html',
			width:350,
			height:300,
			resizable: false,
            type:'utility'
        });
	}
	return win;
}

Ext.air.NativeWindowManager.getPrefWindow = function(){
	var win, winId = 'prefs';
	if(win = this.get(winId)) {
		win.instance.orderToFront();
	} else {
		win = new Ext.air.NativeWindow({
			id: winId,
			file: 'preferences.html',
			width:240,
			height:150,
			resizable: false,
            type:'utility'
        });
	}
	return win;
}
