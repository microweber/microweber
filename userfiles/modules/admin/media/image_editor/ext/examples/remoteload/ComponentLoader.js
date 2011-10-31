/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.namespace('Ext.ux');

/**
 * @class Ext.ux.ComponentLoader
 * Provides an easy way to load components dynamically. If you provide these components
 * with an id you can use Ext.ComponentMgr's onAvailable function to manipulate the components
 * as they are added.
 * @singleton
 */
Ext.ux.ComponentLoader = function() {
	var cm = Ext.ComponentMgr;			
	return {
		/*
		 *  
		 */
		root: 'components',
		/*
		 * Load components from a server resource, config options include anything available in @link Ext.data.Connect#request
		 * Note: Always uses the connection of Ext.Ajax 
		 */
		load : function(config) {
			Ext.apply(config, {
				callback: this.onLoad.createDelegate(this, [config.container], true),
				scope: this
			});	
			if (config.container) {
				Ext.apply(config.params, {
					container: config.container
				});
			}
			Ext.Ajax.request(config);
		},
		// private
		onLoad : function(opts, success, response, ct) {			
			var config = Ext.decode(response.responseText);
			if (config.success) {
				var comps = config[this.root];				
				// loop over each component returned.				
				for (var i = 0; i < comps.length; i++) {
					var c = comps[i];
					// special case of viewport, no container to add to
					if (c.xtype && c.xtype === 'viewport') {
						cm.create(c);
					// add to container
					} else {
						var ct = c.container || ct;
						Ext.getCmp(ct).add(c);
						Ext.getCmp(ct).doLayout();
					}
				}
				
			} else {
				this.onFailure();
			}
		},
		onFailure: function() {
			Ext.Msg.alert('Load failed.');
		}
	};
}();