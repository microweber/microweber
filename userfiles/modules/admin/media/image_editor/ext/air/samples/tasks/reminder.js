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
	var taskId = String(window.location).split('=')[1];
	
	var store = opener.tx.data.tasks;
	var task = store.lookup(taskId);
	
	win.title = 'Reminder - ' + Ext.util.Format.ellipsis(task.data.title, 40);
	
	bulkUpdate({
		'task-title' : Ext.util.Format.ellipsis(task.data.title, 80),
		'task-due' : task.data.dueDate ? task.data.dueDate.format('F d, Y') : 'None'
	});
	
	function bulkUpdate(o){
		for(var id in o){
			Ext.fly(id).update(o[id]);
		}
	}
		
	var dismiss = new Ext.Button({
		text: 'Dismiss',
		minWidth: 80,
		renderTo: 'btns',
		handler: function(){
			win.close();
		}
	});
	
	var snooze = new Ext.Button({
		text: 'Snooze',
		minWidth: 80,
		renderTo: 'btns',
		handler: function(){
			var min = parseInt(Ext.get('snooze-time').getValue(), 10);
			var reminder = new Date().add('mi', min);
			var o = store.getById(taskId);
			if(o){
				o.set('reminder', reminder);
			}else{
				store.proxy.table.updateBy({reminder: reminder}, 'where taskId = ?', [taskId]);
			}
			win.close();
		}
	});
	
	win.visible = true;
	win.activate();
	win.notifyUser('informational');
	
	Ext.air.Sound.play('beep.mp3', 10500);
});

    

