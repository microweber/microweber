/*
 * Ext JS Library 0.20
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

// In AIR, XTemplates must be created at load time
Templates = {
	categoryCombo: new Ext.XTemplate(
		'<tpl for="."><div class="x-combo-list-item">{listName}</div></tpl>'
	),
	timeField: new Ext.XTemplate(
		'<tpl for="."><div class="x-combo-list-item">{text}</div></tpl>'
	),

	gridHeader : new Ext.Template(
        '<table border="0" cellspacing="0" cellpadding="0" style="{tstyle}">',
        '<thead><tr class="x-grid3-hd-row">{cells}</tr></thead>',
        '<tbody><tr class="new-task-row">',
            '<td><div id="new-task-icon"></div></td>',
            '<td><div class="x-small-editor" id="new-task-title"></div></td>',
            '<td><div class="x-small-editor" id="new-task-cat"></div></td>',
            '<td><div class="x-small-editor" id="new-task-due"></div></td>',
        '</tr></tbody>',
        "</table>"
    )
};
