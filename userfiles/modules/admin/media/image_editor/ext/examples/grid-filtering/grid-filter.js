/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.onReady(function(){
	Ext.menu.RangeMenu.prototype.icons = {
	  gt: 'img/greater_then.png', 
	  lt: 'img/less_then.png',
	  eq: 'img/equals.png'
	};
	Ext.grid.filter.StringFilter.prototype.icon = 'img/find.png';
    
    // NOTE: This is an example showing simple state management. During development,
    // it is generally best to disable state management as dynamically-generated ids
    // can change across page loads, leading to unpredictable results.  The developer
    // should ensure that stable state ids are set for stateful components in real apps.
	Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
	  
	var ds = new Ext.data.JsonStore({
	  url:'grid-filter.php',
    id: 'id',
    totalProperty: 'total',
    root: 'data',
    fields: [
      {name:'id'}, 
      {name:'company'}, 
      {name:'price'}, 
      {name:'date',type: 'date', dateFormat: 'Y-m-d H:i:s'}, 
      {name:'visible'}, 
      {name:'size'}
    ],
	  sortInfo: {field: 'company', direction: 'ASC'},
	  remoteSort: true
	});
  
	var filters = new Ext.grid.GridFilters({
	  filters:[
	    {type: 'numeric',  dataIndex: 'id'},
	    {type: 'string',  dataIndex: 'company'},
	    {type: 'numeric', dataIndex: 'price'},
	    {type: 'date',  dataIndex: 'date'},
	    {
	      type: 'list',  
	      dataIndex: 'size', 
	      options: ['small', 'medium', 'large', 'extra large'],
	      phpMode: true
	    },
	    {type: 'boolean', dataIndex: 'visible'}
	]});
	
	var cm = new Ext.grid.ColumnModel([
	  {dataIndex: 'id', header: 'Id'},
	  {dataIndex: 'company', header: 'Company', id: 'company'},
	  {dataIndex: 'price', header: 'Price'},
	  {dataIndex: 'date',header: 'Date', renderer: Ext.util.Format.dateRenderer('m/d/Y')}, 
	  {dataIndex: 'size', header: 'Size'}, 
	  {dataIndex: 'visible',header: 'Visible'}
	]);
	cm.defaultSortable = true;
	
	var grid = new Ext.grid.GridPanel({
	  id: 'example',
	  title: 'Grid Filters Example',
	  ds: ds,
	  cm: cm,
	  enableColLock: false,
	  loadMask: true,
	  plugins: filters,
	  height:400,
	  width:700,        
	  el: 'grid-example',
    autoExpandColumn: 'company',
	  bbar: new Ext.PagingToolbar({
	    store: ds,
	    pageSize: 15,
	    plugins: filters
	  })
	});
	grid.render();
	
	ds.load({params:{start: 0, limit: 15}});
});