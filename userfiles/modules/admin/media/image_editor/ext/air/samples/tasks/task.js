/*
 * Ext JS Library 0.20
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */


Ext.onReady(function(){
    Ext.QuickTips.init();
	
	var win = window.nativeWindow;
	
	var opener = Ext.air.NativeWindow.getRootHtmlWindow();
	var taskId = String(window.location).split('=')[1];
	var isNew = !taskId;
	var completed = false;
	
	win.title = 'Task - ' + Ext.util.Format.ellipsis(getTask().data.title, 40);
	
	var tb = new Ext.Toolbar({
		region: 'north',
		height:26,
		id:'main-tb',
		items:[{
			id:'cpl-btn', 
			iconCls: 'icon-mark-complete', 
			text: 'Mark Complete',
			handler: function(){
				setCompleted(!completed);
				if(completed) {
					setMsg('This task was completed on ' + new Date().format('l, F d, Y'));
				}
				if(validate()) {
					(function(){
						saveData();
						if (completed) {
							win.close();
						}
					}).defer(250);
				}
			}
		},'-',
			{iconCls: 'icon-delete-task', text: 'Delete', handler: function(){
				Ext.Msg.confirm('Confirm Delete', 'Are you sure you want to delete this task?', function(btn){
					if(btn == 'yes'){
						opener.tx.data.tasks.remove(getTask());
						win.close();
					}
				});
			}}
		]
	});
	
	var title = new Ext.form.TextField({
		fieldLabel: 'Task Subject',
        name: 'title',
        anchor: '100%'
    });
		
	var dueDate = new Ext.form.DateField({
		fieldLabel: 'Due Date',
		name: 'dueDate',
		width: 135,
		format: 'm/d/Y'
	});
	
	var list = new ListSelector({
        fieldLabel: 'Task List',
		name: 'listId',
		store: opener.tx.data.lists,
		anchor: '100%'
    });
	
	list.on('render', function(){
		this.menu.on('beforeshow', function(m){
			list.tree.setWidth(Math.max(180, list.getSize().width));
		});
	});
	win.addEventListener('closing', function(){
		opener.tx.data.lists.unbindTree(list.tree);
	});
	
	
	var hasReminder = new Ext.form.Checkbox({
		boxLabel: 'Reminder:',
		checked: false
	});
	
	var reminder = new Ext.ux.form.DateTime({
		name: 'reminder',
		disabled: true,
		timeFormat: 'g:i A',
		dateFormat: 'm/d/Y',
		timeConfig: {
			tpl: opener.Templates.timeField,
			listClass:'x-combo-list-small',
			maxHeight:100
		}
	});
	
	var description = new Ext.form.HtmlEditor({
        hideLabel: true,
        name: 'description',
        anchor: '100% -95',  // anchor width by percentage and height by raw adjustment
        onEditorEvent : function(e){
	        var t;
	        if(e.browserEvent.type == 'mousedown' && (t = e.getTarget('a', 3))){
	            t.target = '_blank';
	        }
	        this.updateToolbar();
	    }
    });
	
	var form = new Ext.form.FormPanel({
		region:'center',
        baseCls: 'x-plain',
        labelWidth: 75,
        margins:'10 10 5 10',
		
		buttonAlign: 'right',
		minButtonWidth: 80,
		buttons:[{
			text: 'OK',
			handler: function(){
				if(validate()) {
					saveData();
					window.nativeWindow.close();
				}
			}
		},{
			text: 'Cancel',
			handler: function(){ window.nativeWindow.close(); }
		}],
				
		
        items: [{
			xtype:'box',
			autoEl: {id:'msg'}
		},
		title,{
			layout: 'column',
			anchor: '100%',
			baseCls: 'x-plain',
			items: [{
				width: 250,
				layout: 'form',
				baseCls: 'x-plain',
				items: dueDate
			}, {
				columnWidth: 1,
				layout: 'form',
				baseCls: 'x-plain',
				labelWidth:55,
				items: list
			}]
		},{
			xtype:'box',
			autoEl: {cls:'divider'}
		},{
			layout: 'column',
			anchor: '100%',
			baseCls: 'x-plain',
			items: [{
				width: 80,
				layout: 'form',
				baseCls: 'x-plain',
				hideLabels: true,
				items: hasReminder
			}, {
				columnWidth: 1,
				layout: 'form',
				baseCls: 'x-plain',
				hideLabels: true,
				items: reminder
			}]
		}, 
		description]
    });
	
	var viewport = new Ext.Viewport({
		layout:'border',
		items:[tb, form]
	});
	
	var msg = Ext.get('msg');
	var task = getTask();
	
	if(task && task.data.completedDate){
		setMsg('This task was completed on ' + task.data.completedDate.format('l, F d, Y'));
	}	
	
	hasReminder.on('check', function(cb, checked){
		reminder.setDisabled(!checked);
		if(checked && !reminder.getValue()){
			reminder.setValue(opener.tx.data.getDefaultReminder(getTask()));
		}
	});
	
	refreshData.defer(10);

	win.visible = true;
	win.activate();
	
	title.focus();
		
	function refreshData(){
		if(!isNew){
			var task = getTask();
			hasReminder.setValue(!!task.data.reminder);
			form.getForm().loadRecord(task);
			setCompleted(task.data.completed);
		}
	}
	
	function saveData(){
		var task;
		if(isNew){
			task = opener.tx.data.tasks.createTask(
				title.getValue(), 
				list.getRawValue(), 
				dueDate.getValue(), 
				description.getValue(),
				completed
			);
		}else{
			task = getTask();
			task.set('completed', completed);
		}
		if(!hasReminder.getValue()){
			reminder.setValue('');
		}
		form.getForm().updateRecord(task);
	}
	
	function setCompleted(value){
		completed = value;
		var cplBtn = Ext.getCmp('cpl-btn');
		if (completed) {
			cplBtn.setText('Mark Active');
			cplBtn.setIconClass('icon-mark-active');
			hasReminder.disable();
			reminder.disable();
		}
		else {
			cplBtn.setText('Mark Complete');
			cplBtn.setIconClass('icon-mark-complete');
			setMsg(null);
			hasReminder.enable();
			reminder.setDisabled(!hasReminder.checked);
		}
	}
	
	function setMsg(msgText){
		var last;
		if(!msgText) {
			msg.setDisplayed(false);
		} else {
			msg.setDisplayed('');
			msg.update(msgText);
		}
		description.anchorSpec.bottom = function(v){
            if(v !== last){
                last = v;
				var h = msg.getHeight();
                return v - 95 - (h ? h + 8 : 0);
            }
        };
		form.doLayout();
	}
	
	function validate(){
		if(Ext.isEmpty(title.getValue(), false)){
			Ext.Msg.alert('Warning', 'Unable to save changes. A subject is required.', function(){
				title.focus();
			});
			return false;
		}
		return true;
	}
	
	function getTask(){
		var t = opener.tx.data.tasks.lookup(taskId);
		if(t){
			//workaround WebKit cross-frame date issue
			fixDateMember(t.data, 'completedDate');
			fixDateMember(t.data, 'reminder');
			fixDateMember(t.data, 'dueDate');
		}
		return t;
	}
	
});

    

