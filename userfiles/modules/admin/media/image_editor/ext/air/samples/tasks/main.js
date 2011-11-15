/*
 * Ext JS Library 0.20
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

// Initialize the state provider
Ext.state.Manager.setProvider(new Ext.air.FileProvider({
	file: 'tasks.state',
	// if first time running
	defaultState : {
		mainWindow : {
			width:780,
			height:580,
			x:10,
			y:10
		},
		defaultReminder: 480
	}
}));

Ext.onReady(function(){
    Ext.QuickTips.init();

	// maintain window state automatically
	var win = new Ext.air.NativeWindow({
		id: 'mainWindow',
		instance: window.nativeWindow,
		minimizeToTray: true,
		trayIcon: 'ext-air/resources/icons/extlogo16.png',
		trayTip: 'Simple Tasks',
		trayMenu : [{
			text: 'Open Simple Tasks',
			handler: function(){
				win.activate();
			}
		}, '-', {
			text: 'Exit',
			handler: function(){
				air.NativeApplication.nativeApplication.exit();
			}
		}]
	});
	
    tx.data.conn.open('tasks.db');
    
    var grid = new TaskGrid();
	var selections = grid.getSelectionModel();
	
	
	// Shared actions used by Ext toolbars, menus, etc.
	var actions = {
		newTask: new Ext.Action({
			text: 'New Task',
			iconCls: 'icon-active',
			tooltip: 'New Task',
			handler: function(){
				taskHeader.ntTitle.focus();
			}
		}),
		
		deleteTask: new Ext.Action({
			itemText: 'Delete',
			iconCls: 'icon-delete-task',
			tooltip: 'Delete Task',
			disabled: true,
			handler: function(){
				Ext.Msg.confirm('Confirm', 'Are you sure you want to delete the selected task(s)?', function(btn){
					if (btn == 'yes') {
						selections.each(function(s){
							tx.data.tasks.remove(s);
						});
					}
				});
			}
		}),
		
		complete: new Ext.Action({
			itemText: 'Mark Complete',
			iconCls: 'icon-mark-complete',
			tooltip: 'Mark Complete',
			disabled: true,
			handler: function(){
				selections.each(function(s){
					s.set('completed', true);
				});
				tx.data.tasks.applyFilter();
			}
		}),
		
		active: new Ext.Action({
			itemText: 'Mark Active',
			tooltip: 'Mark Active',
			iconCls: 'icon-mark-active',
			disabled: true,
			handler: function(){
				selections.each(function(s){
					s.set('completed', false);
				});
				tx.data.tasks.applyFilter();
			}
		}),
		
		newList: new Ext.Action({
			itemText: 'New List',
			tooltip: 'New List',
			iconCls: 'icon-list-new',
			handler: function(){
				var id = tx.data.lists.newList(false, tree.getActiveFolderId()).id;
				tree.startEdit(id, true);
			}
		}),
		
		deleteList: new Ext.Action({
			itemText: 'Delete',
			tooltip: 'Delete List',
			iconCls: 'icon-list-delete',
			disabled: true,
			handler: function(){
				tree.removeList(tree.getSelectionModel().getSelectedNode());
			}
		}),
		
		newFolder: new Ext.Action({
			itemText: 'New Folder',
			tooltip: 'New Folder',
			iconCls: 'icon-folder-new',
			handler: function(){
				var id = tx.data.lists.newList(true, tree.getActiveFolderId()).id;
				tree.startEdit(id, true);
			}
		}),
		
		deleteFolder: new Ext.Action({
			itemText: 'Delete',
			tooltip: 'Delete Folder',
			iconCls: 'icon-folder-delete',
			disabled: true,
			handler: function(s){
				tree.removeList(tree.getSelectionModel().getSelectedNode());
			}
		}),
		
		quit : new Ext.Action({
			text: 'Exit',
			handler: function(){
				air.NativeApplication.nativeApplication.exit();
			}
		}),
		
		pasteAsTask : new Ext.Action({
			itemText: 'Paste as New Task',
			tooltip: 'Paste as New Task',
            iconCls: 'icon-paste-new',
            handler: function(){
                if(air.Clipboard.generalClipboard.hasFormat(air.ClipboardFormats.TEXT_FORMAT)){
				    var text = air.Clipboard.generalClipboard.getData(air.ClipboardFormats.TEXT_FORMAT);
					tx.data.tasks.addTask({
	                    taskId: Ext.uniqueId(),
	                    title: Ext.util.Format.htmlEncode(text.replace(/[\n\r]/g, '')),
	                    dueDate: new Date(),
	                    description: '', 
	                    listId: tx.data.getActiveListId(),
	                    completed: false, 
						reminder: ''
	                });
				}else{
                    Ext.Msg.alert('Warning', 'Could not create task. The clipboard is empty.');
                }
			}
		}) 
	};
    tx.actions = actions;

    var menus = Ext.air.SystemMenu;
	
	menus.add('File', [
		actions.newTask, 
		actions.newList, 
		actions.newFolder, 
		'-',{
			text:'Import...',
			handler: function(){
				var importer = new tx.Importer();
				importer.doImport(function(){
					tx.data.lists.load();
					root.reload();
					loadList('root');
					Ext.Msg.hide();
				});
			}
		},{
			text:'Export...',
			handler: function(){
				new tx.Exporter();
			}
		},
		'-', 
		actions.quit
	]);

	menus.add('Edit', [
		actions.pasteAsTask
	]);

        
    var viewMenu = menus.add('View', [{
        text: 'All Tasks',
        checked: true,
        handler: function(){
            Ext.getCmp('filter').setActiveItem(0);
        }
    },{
        text: 'Active Tasks',
        checked: false,
        handler: function(){
            Ext.getCmp('filter').setActiveItem(1);
        }
    },{
        text: 'Completed Tasks',
        checked: false,
        handler: function(){
            Ext.getCmp('filter').setActiveItem(2);
        }
    }]);

    menus.add('Help', [{
        text: 'About',
        handler: function(){
            Ext.air.NativeWindowManager.getAboutWindow().activate();
        }
    }]);


    var tree = new ListTree({
		actions: actions,
		store: tx.data.lists
	});

	var root = tree.getRootNode();	

	var listSm = tree.getSelectionModel();
	
    tx.data.lists.bindTree(tree);
	tx.data.lists.on('update', function(){
		tx.data.tasks.applyGrouping();
		if(grid.titleNode){
			grid.setTitle(grid.titleNode.text);
		}
	});
	
	var tb = new Ext.Toolbar({
		region:'north',
		id:'main-tb',
		height:26,
		items: [{
				xtype:'splitbutton',
				iconCls:'icon-edit',
				text:'New',
				handler: actions.newTask.initialConfig.handler,
				menu: [actions.newTask, actions.newList, actions.newFolder]
			},'-',
			actions.deleteTask,
			actions.complete,
			actions.active,
            '-',
            actions.pasteAsTask,
            '->',{
				xtype:'switch',
				id:'filter',
                activeItem:0,
				items: [{
			        tooltip:'All Tasks',
					filter: 'all',
			        iconCls:'icon-all',
			        menuIndex: 0
                },{
			        tooltip:'Active Tasks',
			        filter: false,
			        iconCls:'icon-active',
                    menuIndex: 1
			    },{
			        tooltip:'Completed Tasks',
					filter: true,
			        iconCls:'icon-complete',
                    menuIndex: 2
			    }],
				listeners: {
					change: function(btn, item){
						tx.data.tasks.applyFilter(item.filter);
						for (var i = 0; i < 3; i++) {
							viewMenu.items[i].checked = item.menuIndex === i;
						}
					},
					delay: 10 // delay gives user instant click feedback before filtering tasks
				}
			}, ' ', ' ', ' '		
		]
	});

	var viewport = new Ext.Viewport({
        layout:'border',
        items: [tb, tree, grid]
    });
	
	grid.on('keydown', function(e){
         if(e.getKey() == e.DELETE && !grid.editing){
             actions.deleteTask.execute();
         }
    });

    tree.el.on('keydown', function(e){
         if(e.getKey() == e.DELETE && !tree.editor.editing){
             actions.deleteList.execute();
         }
    });

    selections.on('selectionchange', function(sm){
    	var disabled = sm.getCount() < 1;
    	actions.complete.setDisabled(disabled);
    	actions.active.setDisabled(disabled);
    	actions.deleteTask.setDisabled(disabled);
    });

	var taskHeader = new TaskHeader(grid);

	win.show();
	win.instance.activate();
	
	tx.data.tasks.init();

	tree.root.select();

	var loadList = function(listId){
		var node = tree.getNodeById(listId);
		if(node && !node.isSelected()){
			node.select();
			return;
		}
		actions.deleteList.setDisabled(!node || !node.attributes.editable);
		actions.deleteFolder.setDisabled(!node || node.attributes.editable === false || !node.attributes.isFolder);
		if(node){
			if (node.attributes.isFolder) {
				var lists = [];
				node.cascade(function(n){
					if (!n.attributes.isFolder) {
						lists.push(n.attributes.id);
					}
				});
				tx.data.tasks.loadList(lists);
			}
			else {
				tx.data.tasks.loadList(node.id);
			}
			grid.titleNode = node;
			grid.setTitle(node.text);
			grid.setIconClass(node.attributes.iconCls);
		}
	}

	listSm.on('selectionchange', function(t, node){
		loadList(node ? node.id : null);
	});
	
	root.reload();
	
	if(Ext.state.Manager.get('defaultReminder') === undefined){
		Ext.state.Manager.set('defaultReminder', 9 * 60); // default to 9am
	}
	
	win.on('closing', function(){
		Ext.air.NativeWindowManager.closeAll();
	});
	
	tx.ReminderManager.init();
	
	grid.body.on('dragover', function(e){
		if(e.hasFormat(Ext.air.DragType.TEXT)){
			e.preventDefault();
		}
	});
	
	grid.body.on('drop', function(e){
		if(e.hasFormat(Ext.air.DragType.TEXT)){
			var text = e.getData(Ext.air.DragType.TEXT);
			try{
				// from outlook
				if(text.indexOf("Subject\t") != -1){
					var tasks = text.split("\n");
					for(var i = 1, len = tasks.length; i < len; i++){
						var data = tasks[i].split("\t");
						var list = tx.data.lists.findList(data[2]);
						tx.data.tasks.addTask({
			                taskId: Ext.uniqueId(),
			                title: Ext.util.Format.htmlEncode(data[0]),
			                dueDate: Date.parseDate(data[1], 'D n/j/Y') || '',
			                description: '', 
			                listId: list ? list.id : tx.data.getActiveListId(),
			                completed: false, 
							reminder: ''
			            });
					}
				}else{
					tx.data.tasks.addTask({
		                taskId: Ext.uniqueId(),
		                title: Ext.util.Format.htmlEncode(text),
		                dueDate: new Date(),
		                description: '', 
		                listId: tx.data.getActiveListId(),
		                completed: false, 
						reminder: ''
		            });
				}
			}catch(e){
				air.trace('An error occured trying to import drag drop tasks.');
			}
		}
	});
});

    

