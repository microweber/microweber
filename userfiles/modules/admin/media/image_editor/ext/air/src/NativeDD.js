/*
 * Ext JS Library 0.20
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */


/**
 * @class Ext.air.DragType
 * 
 * Drag drop type constants
 * 
 * @singleton
 */
Ext.air.DragType = {
	/**
	 * Constant for text data
	 */
	TEXT : 'text/plain',
	/**
	 * Constant for html data
	 */
	HTML : 'text/html',
	/**
	 * Constant for url data
	 */
	URL : 'text/uri-list',
	/**
	 * Constant for bitmap data
	 */
	BITMAP : 'image/x-vnd.adobe.air.bitmap',
	/**
	 * Constant for file list data
	 */
	FILES : 'application/x-vnd.adobe.air.file-list'
};


// workaround for DD dataTransfer Clipboard not having hasFormat

Ext.apply(Ext.EventObjectImpl.prototype, {
	hasFormat : function(format){
		if (this.browserEvent.dataTransfer) {
			for (var i = 0, len = this.browserEvent.dataTransfer.types.length; i < len; i++) {
				if(this.browserEvent.dataTransfer.types[i] == format) {
					return true;
				}
			}
		}
		return false;
	},
	
	getData : function(type){
		return this.browserEvent.dataTransfer.getData(type);
	}
});


