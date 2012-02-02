/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.grid.GridFilters = function(config){		
	this.filters = new Ext.util.MixedCollection();
	this.filters.getKey = function(o) {return o ? o.dataIndex : null};
	
	for(var i=0, len=config.filters.length; i<len; i++) {
		this.addFilter(config.filters[i]);
  }
  
	this.deferredUpdate = new Ext.util.DelayedTask(this.reload, this);
	
	delete config.filters;
	Ext.apply(this, config);
};
Ext.extend(Ext.grid.GridFilters, Ext.util.Observable, {
	/**
	 * @cfg {Integer} updateBuffer
	 * Number of milisecond to defer store updates since the last filter change.
	 */
	updateBuffer: 500,
	/**
	 * @cfg {String} paramPrefix
	 * The url parameter prefix for the filters.
	 */
	paramPrefix: 'filter',
	/**
	 * @cfg {String} fitlerCls
	 * The css class to be applied to column headers that active filters. Defaults to 'ux-filterd-column'
	 */
	filterCls: 'ux-filtered-column',
	/**
	 * @cfg {Boolean} local
	 * True to use Ext.data.Store filter functions instead of server side filtering.
	 */
	local: false,
	/**
	 * @cfg {Boolean} autoReload
	 * True to automagicly reload the datasource when a filter change happens.
	 */
	autoReload: true,
	/**
	 * @cfg {String} stateId
	 * Name of the Ext.data.Store value to be used to store state information.
	 */
	stateId: undefined,
	/**
	 * @cfg {Boolean} showMenu
	 * True to show the filter menus
	 */
	showMenu: true,
    /**
     * @cfg {String} filtersText
     * The text displayed for the "Filters" menu item
     */
    filtersText: 'Filters',

	init: function(grid){
    if(grid instanceof Ext.grid.GridPanel){
      this.grid  = grid;
      
      this.store = this.grid.getStore();
      if(this.local){
        this.store.on('load', function(store) {
          store.filterBy(this.getRecordFilter());
        }, this);
      } else {
        this.store.on('beforeload', this.onBeforeLoad, this);
      }
      
      this.grid.filters = this;
      
      this.grid.addEvents('filterupdate');
      
      grid.on("render", this.onRender, this);
      grid.on("beforestaterestore", this.applyState, this);
      grid.on("beforestatesave", this.saveState, this);
      
    } else if(grid instanceof Ext.PagingToolbar) {
      this.toolbar = grid;
    }
	},
		
	/** private **/
	applyState: function(grid, state) {
		this.suspendStateStore = true;
		this.clearFilters();
		if(state.filters) {
			for(var key in state.filters) {
				var filter = this.filters.get(key);
				if(filter) {
					filter.setValue(state.filters[key]);
					filter.setActive(true);
				}
			}
    }
    
		this.deferredUpdate.cancel();
		if(this.local) {
			this.reload();
    }
    
		this.suspendStateStore = false;
	},
	
	/** private **/
	saveState: function(grid, state){
		var filters = {};
		this.filters.each(function(filter) {
			if(filter.active) {
				filters[filter.dataIndex] = filter.getValue();
      }
		});
		return state.filters = filters;
	},
	
	/** private **/
	onRender: function(){
		var hmenu;
		
		if(this.showMenu) {
			hmenu = this.grid.getView().hmenu;
			
			this.sep  = hmenu.addSeparator();
			this.menu = hmenu.add(new Ext.menu.CheckItem({
					text: this.filtersText,
					menu: new Ext.menu.Menu()
				}));
			this.menu.on('checkchange', this.onCheckChange, this);
			this.menu.on('beforecheckchange', this.onBeforeCheck, this);
				
			hmenu.on('beforeshow', this.onMenu, this);
		}
		
		this.grid.getView().on("refresh", this.onRefresh, this);
		this.updateColumnHeadings(this.grid.getView());
	},
	
	/** private **/
	onMenu: function(filterMenu) {
		var filter = this.getMenuFilter();
		if(filter) {
			this.menu.menu = filter.menu;
			this.menu.setChecked(filter.active, false);
		}
		
		this.menu.setVisible(filter !== undefined);
		this.sep.setVisible(filter !== undefined);
	},
	
	/** private **/
	onCheckChange: function(item, value) {
		this.getMenuFilter().setActive(value);
	},
	
	/** private **/
	onBeforeCheck: function(check, value) {
		return !value || this.getMenuFilter().isActivatable();
	},
	
	/** private **/
	onStateChange: function(event, filter) {
    if(event == "serialize") {
      return;
    }
    
		if(filter == this.getMenuFilter()) {
			this.menu.setChecked(filter.active, false);
    }
			
		if(this.autoReload || this.local) {
			this.deferredUpdate.delay(this.updateBuffer);
    }
		
		var view = this.grid.getView();
		this.updateColumnHeadings(view);
			
		this.grid.saveState();
			
		this.grid.fireEvent('filterupdate', this, filter);
	},
	
	/** private **/
	onBeforeLoad: function(store, options) {
    options.params = options.params || {};
		this.cleanParams(options.params);		
		var params = this.buildQuery(this.getFilterData());
		Ext.apply(options.params, params);
	},
	
	/** private **/
	onRefresh: function(view) {
		this.updateColumnHeadings(view);
	},
	
	/** private **/
	getMenuFilter: function() {
		var view = this.grid.getView();
		if(!view || view.hdCtxIndex === undefined) {
			return null;
    }
		
		return this.filters.get(view.cm.config[view.hdCtxIndex].dataIndex);
	},
	
	/** private **/
	updateColumnHeadings: function(view) {
		if(!view || !view.mainHd) {
      return;
    }
		
		var hds = view.mainHd.select('td').removeClass(this.filterCls);
		for(var i=0, len=view.cm.config.length; i<len; i++) {
			var filter = this.getFilter(view.cm.config[i].dataIndex);
			if(filter && filter.active) {
				hds.item(i).addClass(this.filterCls);
      }
		}
	},
	
	/** private **/
	reload: function() {
		if(this.local){
			this.grid.store.clearFilter(true);
			this.grid.store.filterBy(this.getRecordFilter());
		} else {
			this.deferredUpdate.cancel();
			var store = this.grid.store;
			if(this.toolbar) {
				var start = this.toolbar.paramNames.start;
				if(store.lastOptions && store.lastOptions.params && store.lastOptions.params[start]) {
					store.lastOptions.params[start] = 0;
        }
			}
			store.reload();
		}
	},
	
	/**
	 * Method factory that generates a record validator for the filters active at the time
	 * of invokation.
	 * 
	 * @private
	 */
	getRecordFilter: function() {
		var f = [];
		this.filters.each(function(filter) {
			if(filter.active) {
        f.push(filter);
      }
		});
		
		var len = f.length;
		return function(record) {
			for(var i=0; i<len; i++) {
				if(!f[i].validateRecord(record)) {
					return false;
        }
      }
			return true;
		};
	},
	
	/**
	 * Adds a filter to the collection.
	 * 
	 * @param {Object/Ext.grid.filter.Filter} config A filter configuration or a filter object.
	 * 
	 * @return {Ext.grid.filter.Filter} The existing or newly created filter object.
	 */
	addFilter: function(config) {
		var filter = config.menu ? config : new (this.getFilterClass(config.type))(config);
		this.filters.add(filter);
		
		Ext.util.Observable.capture(filter, this.onStateChange, this);
		return filter;
	},
	
	/**
	 * Returns a filter for the given dataIndex, if on exists.
	 * 
	 * @param {String} dataIndex The dataIndex of the desired filter object.
	 * 
	 * @return {Ext.grid.filter.Filter}
	 */
	getFilter: function(dataIndex){
		return this.filters.get(dataIndex);
	},

	/**
	 * Turns all filters off. This does not clear the configuration information.
	 */
	clearFilters: function() {
		this.filters.each(function(filter) {
			filter.setActive(false);
		});
	},

	/** private **/
	getFilterData: function() {
		var filters = [];
		
		this.filters.each(function(f) {
			if(f.active) {
				var d = [].concat(f.serialize());
				for(var i=0, len=d.length; i<len; i++) {
					filters.push({field: f.dataIndex, data: d[i]});
        }
			}
		});
		
		return filters;
	},
	
	/**
	 * Function to take structured filter data and 'flatten' it into query parameteres. The default function
	 * will produce a query string of the form:
	 * 		filters[0][field]=dataIndex&filters[0][data][param1]=param&filters[0][data][param2]=param...
	 * 
	 * @param {Array} filters A collection of objects representing active filters and their configuration.
	 * 	  Each element will take the form of {field: dataIndex, data: filterConf}. dataIndex is not assured
	 *    to be unique as any one filter may be a composite of more basic filters for the same dataIndex.
	 * 
	 * @return {Object} Query keys and values
	 */
	buildQuery: function(filters) {
		var p = {};
		for(var i=0, len=filters.length; i<len; i++) {
			var f = filters[i];
			var root = [this.paramPrefix, '[', i, ']'].join('');
			p[root + '[field]'] = f.field;
			
			var dataPrefix = root + '[data]';
			for(var key in f.data) {
				p[[dataPrefix, '[', key, ']'].join('')] = f.data[key];
      }
		}
		
		return p;
	},
	
	/**
	 * Removes filter related query parameters from the provided object.
	 * 
	 * @param {Object} p Query parameters that may contain filter related fields.
	 */
	cleanParams: function(p) {
		var regex = new RegExp("^" + this.paramPrefix + "\[[0-9]+\]");
		for(var key in p) {
			if(regex.test(key)) {
				delete p[key];
      }
    }
	},
	
	/**
	 * Function for locating filter classes, overwrite this with your favorite
	 * loader to provide dynamic filter loading.
	 * 
	 * @param {String} type The type of filter to load.
	 * 
	 * @return {Class}
	 */
	getFilterClass: function(type){
		return Ext.grid.filter[type.substr(0, 1).toUpperCase() + type.substr(1) + 'Filter'];
	}
});