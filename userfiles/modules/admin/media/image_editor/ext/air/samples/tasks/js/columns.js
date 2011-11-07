/*
 * Ext JS Library 0.20
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

// Grid column plugin that does the complete/active button in the left-most column
CompleteColumn = function(){
    var grid;

    function getRecord(t){
        var index = grid.getView().findRowIndex(t);
        return grid.store.getAt(index);
    }

    function onMouseDown(e, t){
        if(Ext.fly(t).hasClass('task-check')){
            e.stopEvent();
            var record = getRecord(t);
            record.set('completed', !record.data.completed);
            grid.store.applyFilter();
        }
    }

    function onMouseOver(e, t){
        if(Ext.fly(t).hasClass('task-check')){
            Ext.fly(t.parentNode).addClass('task-check-over');
        }
    }

    function onMouseOut(e, t){
        if(Ext.fly(t).hasClass('task-check')){
            Ext.fly(t.parentNode).removeClass('task-check-over');
        }
    }

    Ext.apply(this, {
        width: 22,
        header: '<div class="task-col-hd"></div>',
        fixed: true,
		menuDisabled: true,
        id: 'task-col',
        renderer: function(){
            return '<div class="task-check"></div>';
        },
        init : function(xg){
            grid = xg;
            grid.on('render', function(){
                var view = grid.getView();
                view.mainBody.on('mousedown', onMouseDown);
                view.mainBody.on('mouseover', onMouseOver);
                view.mainBody.on('mouseout', onMouseOut);
            });
        }
    });
};


ReminderColumn = function(){
    var grid, menu, record;

	function getRecord(t){
        var index = grid.getView().findRowIndex(t);
        return grid.store.getAt(index);
    }
	
	function onMenuCheck(item){
		if(item.reminder === false){
			record.set('reminder', '');
		}else{
			var s = record.data.dueDate ? record.data.dueDate.clearTime(true) : new Date().clearTime();
			s = s.add('mi', Ext.state.Manager.get('defaultReminder'));
			s = s.add('mi', item.reminder*-1);
			record.set('reminder', s);
		}
	}

	function getMenu(){
		if(!menu){
			menu = new Ext.menu.Menu({
				plain: true,
				items: [{
					text: 'No Reminder',
					reminder: false,
					handler: onMenuCheck
				},'-',{
					text: 'On the Due Date',
					reminder: 0,
					handler: onMenuCheck
				},'-',{
					text: '1 day before',
					reminder: 24*60,
					handler: onMenuCheck
				},{
					text: '2 days before',
					reminder: 48*60,
					handler: onMenuCheck
				},{
					text: '3 days before',
					reminder: 72*60,
					handler: onMenuCheck
				},{
					text: '1 week before',
					reminder: 7*24*60,
					handler: onMenuCheck
				},{
					text: '2 weeks before',
					reminder: 14*24*60,
					handler: onMenuCheck
				},'-',{
					text: 'Set Default Time...',
					handler: function(){
						Ext.air.NativeWindowManager.getPrefWindow();
					}
				}]
			});
		}
		return menu;
	}

    function onMouseDown(e, t){
        if(Ext.fly(t).hasClass('reminder')){
			e.stopEvent();
            record = getRecord(t);
			if (!record.data.completed) {
				var rmenu = getMenu();
				rmenu.show(t, 'tr-br?');
			}
        }
    }

    function onMouseOver(e, t){
        if(Ext.fly(t).hasClass('reminder')){
            Ext.fly(t.parentNode).addClass('reminder-over');
        }
    }

    function onMouseOut(e, t){
        if(Ext.fly(t).hasClass('reminder')){
            Ext.fly(t.parentNode).removeClass('reminder-over');
        }
    }

    Ext.apply(this, {
        width: 26,
        header: '<div class="reminder-col-hd"></div>',
        fixed: true,
        id: 'reminder-col',
		menuDisabled: true,
        dataIndex:'reminder',
        renderer: function(v){
			return '<div class="reminder '+(v ? 'reminder-active' : '')+'"></div>';
        },
        init : function(xg){
            grid = xg;
            grid.on('render', function(){
                var view = grid.getView();
                view.mainBody.on('contextmenu', onMouseDown);
                view.mainBody.on('mousedown', onMouseDown);
                view.mainBody.on('mouseover', onMouseOver);
                view.mainBody.on('mouseout', onMouseOut);
            });
        }
    });
};