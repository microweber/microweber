/*
 * Ext JS Library 0.20
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.SwitchButton = Ext.extend(Ext.Component, {
	initComponent : function(){
		Ext.SwitchButton.superclass.initComponent.call(this);
		
		var mc = new Ext.util.MixedCollection();
		mc.addAll(this.items);
		this.items = mc;
		
		this.addEvents('change');
		
		if(this.handler){
			this.on('change', this.handler, this.scope || this);
		}
	},
	
	onRender : function(ct, position){
		
		var el = document.createElement('table');
		el.cellSpacing = 0;
		el.className = 'x-rbtn';
		el.id = this.id;
		
		var row = document.createElement('tr');
		el.appendChild(row);
		
		var count = this.items.length;
		var last = count - 1;
		this.activeItem = this.items.get(this.activeItem);
		
		for(var i = 0; i < count; i++){
			var item = this.items.itemAt(i);
			
			var cell = row.appendChild(document.createElement('td'));
			cell.id = this.id + '-rbi-' + i;
			
			var cls = i == 0 ? 'x-rbtn-first' : (i == last ? 'x-rbtn-last' : 'x-rbtn-item');
			item.baseCls = cls;
			
			if(this.activeItem == item){
				cls += '-active';
			}
			cell.className = cls;
			
			var button = document.createElement('button');
			button.innerHTML = '&#160;';
			button.className = item.iconCls;
			button.qtip = item.tooltip;
			
			cell.appendChild(button);
			
			item.cell = cell;
		}
		
		this.el = Ext.get(ct.dom.appendChild(el));
		
		this.el.on('click', this.onClick, this);
	},
	
	getActiveItem : function(){
		return this.activeItem;
	},
	
	setActiveItem : function(item){
		if(typeof item != 'object' && item !== null){
			item = this.items.get(item);
		}
		var current = this.getActiveItem();
		if(item != current){
			if(current){
				Ext.fly(current.cell).removeClass(current.baseCls + '-active');
			}
			if(item) {
				Ext.fly(item.cell).addClass(item.baseCls + '-active');
			}
			this.activeItem = item;
			this.fireEvent('change', this, item);
		}
		return item;
	},
	
	onClick : function(e){
		var target = e.getTarget('td', 2);
		if(!this.disabled && target){
			this.setActiveItem(parseInt(target.id.split('-rbi-')[1], 10));
		}
	}
});

Ext.reg('switch', Ext.SwitchButton);
