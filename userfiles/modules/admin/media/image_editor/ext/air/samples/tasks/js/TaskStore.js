/*
 * Ext JS Library 0.20
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */


tx.data.TaskStore = Ext.extend(Ext.data.GroupingStore, {
	constructor: function(){
		tx.data.TaskStore.superclass.constructor.call(this, {
	        sortInfo:{field: 'dueDate', direction: "ASC"},
	        groupField:'dueDate',
	        taskFilter: 'all',
	        reader: new Ext.data.JsonReader({
	            id: 'taskId',
				fields: tx.data.Task
	        })
	    });
		this.conn = tx.data.conn;
	    this.proxy = new Ext.sql.Proxy(tx.data.conn, 'task', 'taskId', this);
	},
	
	applyFilter : function(filter){
    	if(filter !== undefined){
    		this.taskFilter = filter;
    	}
        var value = this.taskFilter;
        if(value == 'all'){
            return this.clearFilter();
        }
        return this.filterBy(function(item){
            return item.data.completed === value;
        });
    },

    addTask : function(data){
        this.suspendEvents();
        this.clearFilter();
        this.resumeEvents();
        this.loadData([data], true);
        this.suspendEvents();
        this.applyFilter();
        this.applyGrouping(true);
        this.resumeEvents();
        this.fireEvent('datachanged', this);
    },

	loadList: function(listId){
		var multi = Ext.isArray(listId);
		this.activeList = multi ? listId[0] : listId;
		this.suspendEvents();
        if(multi){
			var ps = [];
			for(var i = 0, len = listId.length; i < len; i++){
				ps.push('?');
			}
			this.load({
				params: {
					where: 'where listId in (' + ps.join(',') + ')',
					args: listId
				}
			});
		}else{
			this.load({params: {
				where: 'where listId = ?',
				args: [listId]
			}});
		}		
        this.applyFilter();
        this.applyGrouping(true);
        this.resumeEvents();
        this.fireEvent('datachanged', this);
	},
	
	removeList: function(listId){
		this.conn.execBy('delete from task where listId = ?', [listId]);
		this.reload();
	},
	
    prepareTable : function(){
        try{
        this.createTable({
            name: 'task',
            key: 'taskId',
            fields: tx.data.Task.prototype.fields
        });
        }catch(e){console.log(e);}
    },
		
	createTask : function(title, listText, dueDate, description, completed){
		if(!Ext.isEmpty(title)){
			var listId = '';
			if(!Ext.isEmpty(listText)){
				listId = tx.data.lists.addList(Ext.util.Format.htmlEncode(listText)).id;
			}else{
				listId = tx.data.lists.newList(false).id;
			}
            this.addTask({
                taskId: Ext.uniqueId(),
                title: Ext.util.Format.htmlEncode(title),
                dueDate: dueDate||'',
                description: description||'',
                listId: listId,
                completed: completed || false
            });
        }
	},
	
	afterEdit : function(r){
        if(r.isModified(this.getGroupState())){
			this.applyGrouping();
		}
		//workaround WebKit cross-frame date issue
		fixDateMember(r.data, 'completedDate');
		fixDateMember(r.data, 'reminder');
		fixDateMember(r.data, 'dueDate');
		if(r.isModified('completed')){
			r.editing = true;
			r.set('completedDate', r.data.completed ? new Date() : '');
			r.editing = false;
		}
		tx.data.TaskStore.superclass.afterEdit.apply(this, arguments);
    },
	
	init : function(){
		tx.data.lists.load();
		this.load({
			callback: function(){
				// first time?
				if(this.getCount() < 1){
					Ext.Msg.confirm('Create Tasks?', 'Your database is currently empty. Would you like to insert some demo data?', 
						function(btn){
							if(btn == 'yes'){
								tx.data.lists.loadDemoLists();
								this.loadDemoTasks();	
							}
						}, this);
				}
			},
			scope: this
		});
	},
	
	lookup : function(id){
		var task;
		if(task = this.getById(id)){
			return task;
		}
		var data = this.proxy.table.lookup(id);
		if (data) {
			var result = this.reader.readRecords([data]);
			return result.records[0];
		}
		return null; 
	},
	
	/* This is used to laod some demo tasks if the task database is empty */
	loadDemoTasks: function(){
		var s = new Date();
		// hardcoded demo tasks
		this.addTask({taskId: Ext.uniqueId(), title:'Update Ext 2.0 documentation', listId:'ext2', description:'', dueDate: s.add('d', 21), completed: false, reminder: ''});
		this.addTask({taskId: Ext.uniqueId(), title:'Release Ext 2.l Beta 1', listId:'ext2', description:'', dueDate:s.add('d', 2), completed: false, reminder: s.add('d', 2).clearTime(true).add('h', 9)});
		this.addTask({taskId: Ext.uniqueId(), title:'Take wife to see movie', listId:'family', description:'', dueDate:s.add('d', 2), completed: false, reminder: ''});
		this.addTask({taskId: Ext.uniqueId(), title:'Finish Simple Tasks v2 sample app', listId:'ext2', description:'', dueDate:s.add('d', 2), completed: false, reminder: ''});
		this.addTask({taskId: Ext.uniqueId(), title:'Do something other than work', listId:'fun', description:'', dueDate:s.add('d', -1), completed: false, reminder: ''});
		this.addTask({taskId: Ext.uniqueId(), title:'Go to the grocery store', listId:'family', description:'', dueDate:s.add('d', -1), completed: true, reminder: '', completedDate: new Date()});
		this.addTask({taskId: Ext.uniqueId(), title:'Reboot my computer', listId:'personal-misc', description:'', dueDate:s, completed: false, reminder: ''});
		this.addTask({taskId: Ext.uniqueId(), title:'Respond to emails', listId:'work-misc', description:'', dueDate:s, completed: true, reminder: '', completedDate: new Date()});
	}
});