/*
 * Ext JS Library 0.20
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

tx.Exporter = function(){
	var lists = tx.data.conn.query('select * from list');
	
	var doc = new runtime.flash.xml.XMLDocument();
	
	var root = doc.createElement('simple-tasks');
	doc.appendChild(root);
	
	root.attributes['version'] = '2.0';
	
	for(var i = 0, len = lists.length; i < len; i++){
		var list = lists[i];
		
		var listNode = doc.createElement('list');
		root.appendChild(listNode);
		
		for(var k in list){
			if(list.hasOwnProperty(k)){
				listNode.attributes[k] = String(list[k]);
			}
		}
		
		var tasks = tx.data.conn.queryBy('select * from task where listId = ?', [list.listId]);
		for(var j = 0, jlen = tasks.length; j < jlen; j++){
			var task = tasks[j];
			
			var taskNode = doc.createElement('task');
			listNode.appendChild(taskNode);
			
			for(var t in task){
				if(task.hasOwnProperty(t)){
					taskNode.attributes[t] = String(task[t]);
				}
			}
		}
	}
	
	var file = new air.File(air.File.documentsDirectory.nativePath + air.File.separator + 'tasks.xml');
	
	file.addEventListener('select', function(e){
		var target = e.target;
		var stream = new air.FileStream();
        stream.open(target, air.FileMode.WRITE);
		stream.writeUTFBytes('<?xml version="1.0" encoding="UTF-8"?>');
        stream.writeUTFBytes(doc.toString());
        stream.close();
	});
	
	// I wonder why no filter for Save As?
	// var filter = new air.FileFilter("Tasks XML File", "*.xml");
	file.browseForSave('Save As');
};
