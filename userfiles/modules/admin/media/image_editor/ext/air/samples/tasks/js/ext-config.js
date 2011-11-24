/*
 * Ext JS Library 0.20
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.BLANK_IMAGE_URL = 'images/s.gif';

tx = {data:{}, ui: {}};

// work around for broken cross frame Dates in Safari
function fixDate(d){
	return d ? new Date(d.getTime()) : d;
}

function fixDateMember(o, name){
	if(o[name]){
		o[name] = new Date(o[name].getTime());
	}
}

