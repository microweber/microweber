/*
 * Ext JS Library 0.20
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

tx.ReminderManager = function(){
	var table;
	
	var run = function(){
		var rs = table.selectBy('where completed = 0 AND reminder <> \'\' AND reminder <= ?', [new Date()]);
		for(var i = 0, len = rs.length; i < len; i++){
			showReminder.defer(10, window, [rs[i]]);
		}	
	};
	
	var showReminder = function(task){
		var o;
		if (o = tx.data.tasks.getById(task.taskId)) { // if currently loaded
			o.set('reminder', '');
		}
		else {   // else update db directly
			table.update({
				taskId: task.taskId,
				reminder: ''
			});
		}
		Ext.air.NativeWindowManager.getReminderWindow(task.taskId);
	}
	
	return {
		init : function(){
			table = tx.data.conn.getTable('task', 'taskId');
			setInterval(run, 10000);
		}
	}	
}();
