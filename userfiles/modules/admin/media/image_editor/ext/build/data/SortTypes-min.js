/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.data.SortTypes={none:function(A){return A},stripTagsRE:/<\/?[^>]+>/gi,asText:function(A){return String(A).replace(this.stripTagsRE,"")},asUCText:function(A){return String(A).toUpperCase().replace(this.stripTagsRE,"")},asUCString:function(A){return String(A).toUpperCase()},asDate:function(A){if(!A){return 0}if(Ext.isDate(A)){return A.getTime()}return Date.parse(String(A))},asFloat:function(A){var B=parseFloat(String(A).replace(/,/g,""));if(isNaN(B)){B=0}return B},asInt:function(A){var B=parseInt(String(A).replace(/,/g,""));if(isNaN(B)){B=0}return B}};