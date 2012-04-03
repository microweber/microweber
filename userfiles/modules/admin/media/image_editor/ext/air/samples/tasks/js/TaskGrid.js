/*
 * Ext JS Library 0.20
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

TaskGrid = function(){
	
	// custom columns
	var completeColumn = new CompleteColumn();
	var reminderColumn = new ReminderColumn();
	
	TaskGrid.superclass.constructor.call(this, {
		id:'tasks-grid',
        store: tx.data.tasks,
        sm: new Ext.grid.RowSelectionModel({moveEditorOnEnter: false}),
        clicksToEdit: 'auto',
        enableColumnHide:false,
        enableColumnMove:false,
		autoEncode: true,
        title:'All Tasks',
        iconCls:'icon-folder',
        region:'center',
        plugins: [completeColumn, reminderColumn],
		margins:'3 3 3 0',
        columns: [
            completeColumn,
            {
                header: "Task",
                width:400,
                sortable: true,
                dataIndex: 'title',
                id:'task-title',
                editor: new Ext.form.TextField({
                    allowBlank: false
                })
            },
            {
                header: "List",
                width:150,
                sortable: true,
                dataIndex: 'listId',
                editor: new ListSelector({
			        store:tx.data.lists
			    }),
				renderer : function(v){
					return tx.data.lists.getName(v);
				}
            },
            {
				id:'dueDate',
                header: "Due Date",
                width: 150,
                sortable: true,
                renderer: Ext.util.Format.dateRenderer('D m/d/Y'),
                dataIndex: 'dueDate',
                groupRenderer: Ext.util.Format.createTextDateRenderer(),
                groupName: 'Due',
                editor: new Ext.form.DateField({
                    format : "m/d/Y"
                })
            },
			reminderColumn
        ],

        view: new TaskView()
	});
	
	// Keep the visible date groups in the grid accurate
	Ext.TaskMgr.start({
		run: function(){
			var col = this.getColumnModel().getColumnById('dueDate');
			if(col.groupRenderer.date.getTime() != new Date().clearTime().getTime()){
				col.groupRenderer = Ext.util.Format.createTextDateRenderer();
				tx.data.tasks.applyGrouping();
			}
		},
		interval: 60000,
		scope: this
	});
	
	this.on('rowcontextmenu', this.onRowContext, this);
};

Ext.extend(TaskGrid, Ext.grid.EditorGridPanel, {
	onCellDblClick: function(g, row){
		clearTimeout(this.autoEditTimer); // allow dbl click without starting edit
		var id = this.store.getAt(row).id;
		
		Ext.air.NativeWindowManager.getTaskWindow(id);
	},

    // private
    onAutoEditClick : function(e, t){
		clearTimeout(this.autoEditTimer);
        if(e.button !== 0){
            return;
        }
        var row = this.view.findRowIndex(t);
        var col = this.view.findCellIndex(t);
        if(row !== false && col !== false){
        	if(this.selModel.isSelected(row) && this.selModel.getCount() === 1){
				this.autoEditTimer = this.startEditing.defer(300, this, [row, col]);
            }
        }
    },
	
	onRowContext : function(grid, row, e){
        if(!this.menu){ // create context menu on first right click
            this.menu = new Ext.menu.Menu({
                id:'tasks-ctx',
				listWidth: 200,
                items: [{
                    text:'Open',
                    scope: this,
                    handler:function(){
						Ext.each(this.selModel.getSelections(), function(r){
							Ext.air.NativeWindowManager.getTaskWindow(r.id);
						});
                    }
                },'-',
					tx.actions.complete,
					tx.actions.deleteTask
                ]
            });
        }
		if(!this.selModel.isSelected(row)){
			this.selModel.selectRow(row);
		}
		
		this.menu.showAt(e.getXY());
    }
})


TaskView = Ext.extend(Ext.grid.GroupingView, {
	forceFit:true,
    ignoreAdd: true,
    emptyText: 'There are no tasks to show in this list.',

    templates: {
        header: Templates.gridHeader
    },
    getRowClass : function(r){
        var d = r.data;
        if(d.completed){
            return 'task-completed';
        }
        if(d.dueDate && d.dueDate.getTime() < new Date().clearTime().getTime()){
            return 'task-overdue';
        }
        return '';
    }
});


TaskHeader = function(grid){
	grid.on('resize', syncFields);
	grid.on('columnresize', syncFields);
		
    // The fields in the grid's header
    var ntTitle = this.ntTitle = new Ext.form.TextField({
        renderTo: 'new-task-title',
        emptyText: 'Add a task...'
    });

    var ntCat = new ListSelector({
        renderTo: 'new-task-cat',
        disabled:true,
        store:tx.data.lists,
		listenForLoad: true
    });

    var ntDue = new Ext.form.DateField({
        renderTo: 'new-task-due',
        value: new Date(),
        disabled:true,
        format : "m/d/Y"
    });

    // syncs the header fields' widths with the grid column widths
    function syncFields(){
        var cm = grid.getColumnModel();
        ntTitle.setSize(cm.getColumnWidth(1)-2);
        ntCat.setSize(cm.getColumnWidth(2)-4);
        ntDue.setSize(cm.getColumnWidth(3)-4);
    }
    syncFields();

    var editing = false, focused = false, userTriggered = false;
    var handlers = {
        focus: function(){
            setFocus.defer(20, window, [true]);
        },
        blur: function(){
            focused = false;
            doBlur.defer(250);
        },
        specialkey: function(f, e){
            if(e.getKey()==e.ENTER && (!f.isExpanded || !f.isExpanded())){
                userTriggered = true;
                e.stopEvent();
                f.el.blur();
                if(f.triggerBlur){
                    f.triggerBlur();
                }
            }
        }
    }
    ntTitle.on(handlers);
    ntCat.on(handlers);
    ntDue.on(handlers);

    ntTitle.on('focus', function(){
        focused = true;
        if(!editing){
            ntCat.enable();
            ntDue.enable();
            syncFields();
            editing = true;
			
			ntCat.setValue(tx.data.getActiveListId());
        }
    });

    function setFocus(v){
		focused = v;
	}
    // when a field in the add bar is blurred, this determines
    // whether a new task should be created
    function doBlur(){
        if(editing && !focused){
			var title = ntTitle.getValue();
            if(!Ext.isEmpty(title)){
				tx.data.tasks.createTask(title, ntCat.getRawValue(), ntDue.getValue());
            	ntTitle.setValue('');
                if(userTriggered){ // if they entered to add the task, then go to a new add automatically
                    userTriggered = false;
                    ntTitle.focus.defer(100, ntTitle);
                }
            }
            ntCat.disable();
            ntDue.disable();
            editing = false;
        }
    }
};
