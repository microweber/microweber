/*!
* Aloha Editor
* Author & Copyright (c) 2010 Gentics Software GmbH
* aloha-sales@gentics.com
* Licensed unter the terms of http://www.aloha-editor.com/license.html
*/

define([
	'aloha/plugin',
	'block/blockmanager',
	'blockdemo/block',
	'css!blockdemo/css/block.css'
], function(Plugin, BlockManager, block) {
	
	return Plugin.create('blockdemo', {
		init: function() {
			BlockManager.registerBlockType('ProductTeaserBlock', block.ProductTeaserBlock);
			BlockManager.registerBlockType('CompanyBlock', block.CompanyBlock);
			BlockManager.registerBlockType('EditableProductTeaserBlock', block.EditableProductTeaserBlock);
			BlockManager.registerBlockType('VCardBlock', block.VCardBlock);
			BlockManager.registerBlockType('CustomHandleBlock', block.CustomHandleBlock);
			BlockManager.registerBlockType('TwoColumnBlock', block.TwoColumnBlock);
		}
	});
});