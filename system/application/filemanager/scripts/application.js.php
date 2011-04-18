<?php
// ensure this file is being included by a parent file
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/**
 * @package eXtplorer
 * @copyright soeren 2007-2009
 * @author The eXtplorer project (http://sourceforge.net/projects/extplorer)
 * @license
 * @version $Id: application.js.php 168 2010-06-07 15:08:18Z soeren $
 * The contents of this file are subject to the Mozilla Public License
 * Version 1.1 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 * 
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
 * 
 * Alternatively, the contents of this file may be used under the terms
 * of the GNU General Public License Version 2 or later (the "GPL"), in
 * which case the provisions of the GPL are applicable instead of
 * those above. If you wish to allow use of your version of this file only
 * under the terms of the GPL and not to allow others to use
 * your version of this file under the MPL, indicate your decision by
 * deleting  the provisions above and replace  them with the notice and
 * other provisions required by the GPL.  If you do not delete
 * the provisions above, a recipient may use your version of this file
 * under either the MPL or the GPL."
 * 
*/

/**
 * Layout and Application Logic Functions based on ExtJS
 */

?>
<script type="text/javascript">

function ext_init(){
	Ext.BLANK_IMAGE_URL = "<?php echo _EXT_URL ?>/scripts/extjs3/resources/images/default/s.gif";
    // create the Data Store
    datastore = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: "<?php echo $GLOBALS['script_name'] ?>",
            directory: "/",
            params:{start:0, limit:150, dir: this.directory, option:"com_extplorer", action:"getdircontents" }
        }),
		directory: "/",
		sendWhat: "both",
        // create reader that reads the File records
        reader: new Ext.data.JsonReader({
            root: "items",
            totalProperty: "totalCount"
        }, Ext.data.Record.create([
            {name: "name"},
            {name: "size"},
            {name: "type"},
            {name: "modified"},
            {name: "perms"},
            {name: "icon"},
            {name: "owner"},
            {name: "is_deletable"},
            {name: "is_file"},
            {name: "is_archive"},
            {name: "is_writable"},
            {name: "is_chmodable"},
            {name: "is_readable"},
            {name: "is_deletable"},
            {name: "is_editable"}
        ])),

        // turn on remote sorting
        remoteSort: true
    });
    datastore.paramNames["dir"] = "direction";
    datastore.paramNames["sort"] = "order";
    
    datastore.on("beforeload", function(ds, options) {
    								options.params.dir = options.params.dir ? options.params.dir : ds.directory;
    								options.params.option = "com_extplorer";
    								options.params.action = "getdircontents";
    								options.params.sendWhat = datastore.sendWhat;    								
    								}
    							 );

    // pluggable renders
    function renderFileName(value,p, record){
        return String.format('<img src="{0}" alt="* " align="absmiddle" />&nbsp;<b>{1}</b>', record.get('icon'), value );
    }
    function renderType(value){
        return String.format('<i>{0}</i>', value);
    }
    var gridtb = new Ext.Toolbar([
                         	{
                             	xtype: "tbbutton",
                         		id: 'tb_home',
                         		icon: '<?php echo _EXT_URL ?>/images/_home.png',
                         		text: '<?php echo ext_Lang::msg('homelink', true ) ?>',
                         		tooltip: '<?php echo ext_Lang::msg('homelink', true ) ?>',
                         		cls:'x-btn-text-icon',
                         		handler: function() { chDir('') }
                         	},
                            {
                         		xtype: "tbbutton",
                         		id: 'tb_reload',
                              	icon: '<?php echo _EXT_URL ?>/images/_reload.png',
                              	text: '<?php echo ext_Lang::msg('reloadlink', true ) ?>',
                            	tooltip: '<?php echo ext_Lang::msg('reloadlink', true ) ?>',
                              	cls:'x-btn-text-icon',
                              	handler: loadDir
                            },
                            <?php if( !ext_isFTPMode() ) { ?>
                              	{
                              		xtype: "tbbutton",
                             		id: 'tb_search',
                              		icon: '<?php echo _EXT_URL ?>/images/_filefind.png',
                              		text: '<?php echo ext_Lang::msg('searchlink', true ) ?>',
                              		tooltip: '<?php echo ext_Lang::msg('searchlink', true ) ?>',
                              		cls:'x-btn-text-icon',
                              		handler: function() { openActionDialog(this, 'search'); }
                              	},
                            <?php } ?>
                            '-',
                            {
                            	xtype: "tbbutton",
                         		id: 'tb_new',
                              		icon: '<?php echo _EXT_URL ?>/images/_filenew.png',
                              		tooltip: '<?php echo ext_Lang::msg('newlink', true ) ?>',
                              		cls:'x-btn-icon',
                              		disabled: <?php echo $allow ? 'false' : 'true' ?>,
                              		handler: function() { openActionDialog(this, 'mkitem'); }
                              	},
                              	{
                              		xtype: "tbbutton",
                             		id: 'tb_edit',
                              		icon: '<?php echo _EXT_URL ?>/images/_edit.png',
                              		tooltip: '<?php echo ext_Lang::msg('editlink', true ) ?>',
                              		cls:'x-btn-icon',
                              		disabled: <?php echo $allow ? 'false' : 'true' ?>,
                              		handler: function() { openActionDialog(this, 'edit'); }
                              	},
                              	{
                              		xtype: "tbbutton",
                             		id: 'tb_copy',
                              		icon: '<?php echo _EXT_URL ?>/images/_editcopy.png',
                              		tooltip: '<?php echo ext_Lang::msg('copylink', true ) ?>',
                              		cls:'x-btn-icon',
                              		disabled: <?php echo $allow ? 'false' : 'true' ?>,
                              		handler: function() { openActionDialog(this, 'copy'); }
                              	},
                              	{
                              		xtype: "tbbutton",
                             		id: 'tb_move',
                              		icon: '<?php echo _EXT_URL ?>/images/_move.png',
                              		tooltip: '<?php echo ext_Lang::msg('movelink', true ) ?>',
                              		cls:'x-btn-icon',
                              		disabled: <?php echo $allow ? 'false' : 'true' ?>,
                              		handler: function() { openActionDialog(this, 'move'); }
                              	},
                              	{
                              		xtype: "tbbutton",
                             		id: 'tb_delete',
                              		icon: '<?php echo _EXT_URL ?>/images/_editdelete.png',
                              		tooltip: '<?php echo ext_Lang::msg('dellink', true ) ?>',
                              		cls:'x-btn-icon',
                              		disabled: <?php echo $allow ? 'false' : 'true' ?>,
                              		handler: function() { openActionDialog(this, 'delete'); }
                              	},
                              	{
                              		xtype: "tbbutton",
                             		id: 'tb_rename',
                              		icon: '<?php echo _EXT_URL ?>/images/_fonts.png',
                              		tooltip: '<?php echo ext_Lang::msg('renamelink', true ) ?>',
                              		cls:'x-btn-icon',
                              		disabled: <?php echo $allow ? 'false' : 'true' ?>,
                              		handler: function() { openActionDialog(this, 'rename'); }
                              	},
                              	{
                              		xtype: "tbbutton",
                             		id: 'tb_chmod',
                              		icon: '<?php echo _EXT_URL ?>/images/_chmod.png',
                              		tooltip: '<?php echo ext_Lang::msg('chmodlink', true ) ?>',
                              		cls:'x-btn-icon',
                              		disabled: <?php echo $allow ? 'false' : 'true' ?>,
                              		handler: function() { openActionDialog(this, 'chmod'); }
                              	},
                              	'-',
                              	{
                              		xtype: "tbbutton",
                             		id: 'tb_view',
                              		icon: '<?php echo _EXT_URL ?>/images/_view.png',
                              		tooltip: '<?php echo ext_Lang::msg('viewlink', true ) ?>',
                              		cls:'x-btn-icon',
                              		handler: function() { openActionDialog(this, 'view'); }
                              	},
                              	{
                              		xtype: "tbbutton",
                             		id: 'tb_diff',
                              		icon: '<?php echo _EXT_URL ?>/images/extension/document.png',
                              		tooltip: '<?php echo ext_Lang::msg('difflink', true ) ?>',
                              		cls:'x-btn-icon',
                              		disabled: <?php echo $allow ? 'false' : 'true' ?>,
                              		handler: function() { openActionDialog(this, 'diff'); }
                              	},
                              	{
                              		xtype: "tbbutton",
                             		id: 'tb_download',
                              		icon: '<?php echo _EXT_URL ?>/images/_down.png',
                              		tooltip: '<?php echo ext_Lang::msg('downlink', true ) ?>',
                              		cls:'x-btn-icon',
                              		disabled: <?php echo $allow ? 'false' : 'true' ?>,
                              		handler: function() { openActionDialog(this,'download'); }
                              	},
                              	'-',
                              	{
                              		xtype: "tbbutton",
                             		id: 'tb_upload',
                              		icon: '<?php echo _EXT_URL ?>/images/_up.png',
                              		tooltip: '<?php echo ext_Lang::msg('uploadlink', true ) ?>',
                              		cls:'x-btn-icon',
                              		disabled: <?php echo $allow && ini_get('file_uploads') ? 'false' : 'true' ?>,
                              		handler: function() { 
                                  		Ext.ux.OnDemandLoad.load("<?php echo _EXT_URL ?>/scripts/extjs3-ext/ux.swfupload/SwfUploadPanel.css");
                              			Ext.ux.OnDemandLoad.load("<?php echo _EXT_URL ?>/scripts/extjs3-ext/ux.swfupload/SwfUpload.js" );
                              			Ext.ux.OnDemandLoad.load("<?php echo _EXT_URL ?>/scripts/extjs3-ext/ux.swfupload/SwfUploadPanel.js",
                              		    	function(options) { openActionDialog(this, 'upload'); }); 
                          		    }
                              	},
                              	{
                              		xtype: "tbbutton",
                             		id: 'tb_archive',
                              		icon: '<?php echo _EXT_URL ?>/images/_archive.png',
                              		tooltip: '<?php echo ext_Lang::msg('comprlink', true ) ?>',
                          			cls:'x-btn-icon',
                          			<?php if( ($GLOBALS["zip"] || $GLOBALS["tar"] || $GLOBALS["tgz"]) && !ext_isFTPMode() ) { ?>
                              		handler: function() { openActionDialog(this, 'archive'); }
                          			<?php } else { ?>
                          			disabled: true
                              		<?php }  ?>
                              	},{
                              		xtype: "tbbutton",
                             		id: 'tb_extract',
                              		icon: '<?php echo _EXT_URL ?>/images/_extract.gif',
                              		tooltip: '<?php echo ext_Lang::msg('extractlink', true ) ?>',
                              		cls:'x-btn-icon',
                          			<?php if( ($GLOBALS["zip"] || $GLOBALS["tar"] || $GLOBALS["tgz"]) && !ext_isFTPMode() ) { ?>
                              		handler: function() { openActionDialog(this, 'extract'); }
                          			<?php } else { ?>
                          			disabled: true
                              		<?php }  ?>
                          		},
                              	'-',
                              	{
                          			xtype: "tbbutton",
                             		id: 'tb_info',
                              		icon: '<?php echo _EXT_URL ?>/images/_help.png',
                              		tooltip: '<?php echo ext_Lang::msg('aboutlink', true ) ?>',
                              		cls:'x-btn-icon',
                              		handler: function() { openActionDialog(this, 'get_about'); }
                              	},
                              	'-',
                              	<?php
                          		// ADMIN & LOGOUT
                          		if(!empty($GLOBALS["require_login"])) {
                          			if($admin) {
                          		
                          			?>
                          	    	{	// ADMIN
                          	    		xtype: "tbbutton",
                                 		id: 'tb_admin',
                          	    		icon: '<?php echo _EXT_URL ?>/images/_admin.gif',
                          	    		tooltip: '<?php echo ext_Lang::msg('adminlink', true ) ?>',
                          	    		cls:'x-btn-icon',
                          	    		handler: function() { openActionDialog(this, 'admin'); }
                          	    	},
                          	    	<?php
                          			}
                          			?>
                          	    	{	// LOGOUT
                          	    		xtype: "tbbutton",
                                 		id: 'tb_logout',
                          	    		icon: '<?php echo _EXT_URL ?>/images/_logout.png',
                          	    		tooltip: '<?php echo ext_Lang::msg('logoutlink', true ) ?>',
                          	    		cls:'x-btn-icon',
                          	    		handler: function() { document.location.href='<?php echo make_link('logout', null ) ?>'; }
                          	    	},		
                          	    	'-',
                          			<?php
                          		}
                          		?>		
                            	new Ext.Toolbar.Button( {
                            		text: '<?php echo ext_Lang::msg('show_directories', true ) ?>',
                            		enableToggle: true,
                            		pressed: true,
                            		handler: function(btn,e) { 
                            					if( btn.pressed ) {
                            						datastore.sendWhat= 'both';
                            						loadDir();
                            					} else {
                            						datastore.sendWhat= 'files';
                            						loadDir();
                            					}
                            			}
                            	}), '-',
                            	new Ext.form.TextField( { 
                                	name: "filterValue", 
                                	id: "filterField",
                                	enableKeyEvents: true,
                                	title: "<?php echo ext_Lang::msg('filter_grid', true ) ?>",
                            		listeners: { 
                            			"keypress": { fn: 	function(textfield, e ) {
					                            		    	if( e.getKey() == Ext.EventObject.ENTER ) {
					                            		    		filterDataStore();
					                            		    	}
	                            							}
                            						}
                            		}
                                }),
                            	new Ext.Toolbar.Button( {
                            		text: '&nbsp;X&nbsp;',
                            	handler: function() { 
                                	datastore.clearFilter();
                                	Ext.getCmp("filterField").setValue(""); 
                                	}
                            	})

                            ]);
    function filterDataStore(btn,e) { 
		var filterVal = Ext.getCmp("filterField").getValue();
		if( filterVal.length > 1 ) {
			datastore.filter( 'name', eval('/'+filterVal+'/gi') );
		} else {
			datastore.clearFilter();
		}
	}
    // add a paging toolbar to the grid's footer
    var gridbb = new Ext.PagingToolbar({
        store: datastore,
        pageSize: 150,
        displayInfo: true,
        displayMsg: '<?php echo ext_Lang::msg( 'paging_info', true ) ?>',
        emptyMsg: '<?php echo ext_Lang::msg( 'paging_noitems', true ) ?>',
        beforePageText: '<?php echo ext_Lang::msg('paging_page', true ) ?>',
		afterPageText: '<?php echo ext_Lang::msg('paging_of_X', true ) ?>',
		firstText: '<?php echo ext_Lang::msg('paging_firstpage', true ) ?>',
		lastText: '<?php echo ext_Lang::msg('paging_lastpage', true ) ?>',
		nextText: '<?php echo ext_Lang::msg('paging_nextpage', true ) ?>',
		prevText: '<?php echo ext_Lang::msg('paging_prevpage', true ) ?>',
		refreshText: '<?php echo ext_Lang::msg('reloadlink', true ) ?>',
		items: ['-',' ',' ',' ',' ',' ',
			new Ext.ux.StatusBar({
			    defaultText: '<?php echo ext_Lang::msg('done', true ) ?>',
			    id: 'statusPanel'
			})]
    });
    
    // the column model has information about grid columns
    // dataIndex maps the column to the specific data field in
    // the data store
    var cm = new Ext.grid.ColumnModel([{
           id: 'gridcm', // id assigned so we can apply custom css (e.g. .x-grid-col-topic b { color:#333 })
           header: "<?php echo ext_Lang::msg('nameheader', true ) ?>",
           dataIndex: 'name',
           width: 250,
           renderer: renderFileName,
           editor: new Ext.form.TextField({
					allowBlank: false
				}),
           css: 'white-space:normal;'
        },{
           header: "<?php echo ext_Lang::msg('sizeheader', true ) ?>",
           dataIndex: 'size',
           width: 50
        },{
           header: "<?php echo ext_Lang::msg('typeheader', true ) ?>",
           dataIndex: 'type',
           width: 70,
           align: 'right',
           renderer: renderType
        },{
           header: "<?php echo ext_Lang::msg('modifheader', true ) ?>",
           dataIndex: 'modified',
           width: 150
        },{
           header: "<?php echo ext_Lang::msg('permheader', true ) ?>",
           dataIndex: 'perms',
           width: 100
        },{
           header: "<?php echo ext_Lang::msg('miscowner', true ) ?>",
           dataIndex: 'owner',
           width: 100,
           sortable: false
        }, 
        { dataIndex: 'is_deletable', header: "is_deletable", hidden: true, hideable: false },
        {dataIndex: 'is_file', hidden: true, hideable: false },
        {dataIndex: 'is_archive', hidden: true, hideable: false },
        {dataIndex: 'is_writable', hidden: true, hideable: false },
        {dataIndex: 'is_chmodable', hidden: true, hideable: false },
        {dataIndex: 'is_readable', hidden: true, hideable: false },
        {dataIndex: 'is_deletable', hidden: true, hideable: false },
        {dataIndex: 'is_editable', hidden: true, hideable: false }
        ]);

    // by default columns are sortable
    cm.defaultSortable = true;


    // Unregister the default double click action (which makes the name field editable - we want this when the user clicks "Rename" in the menu)
    //ext_itemgrid.un('celldblclick', ext_itemgrid.onCellDblClick);
    
    function handleRowClick(sm, rowIndex) {
    	var selections = sm.getSelections();
    	tb = ext_itemgrid.getTopToolbar();
    	if( selections.length > 1 ) {
    		tb.items.get('tb_edit').disable();
    		tb.items.get('tb_delete').enable();
    		tb.items.get('tb_rename').disable();
    		tb.items.get('tb_chmod').enable();
    		tb.items.get('tb_download').disable();
    		tb.items.get('tb_extract').disable();
    		tb.items.get('tb_archive').enable();
    		tb.items.get('tb_view').enable();
    	} else if(selections.length == 1) {
    		tb.items.get('tb_edit')[selections[0].get('is_editable')&&selections[0].get('is_readable') ? 'enable' : 'disable']();
    		tb.items.get('tb_delete')[selections[0].get('is_deletable') ? 'enable' : 'disable']();
    		tb.items.get('tb_rename')[selections[0].get('is_deletable') ? 'enable' : 'disable']();
    		tb.items.get('tb_chmod')[selections[0].get('is_chmodable') ? 'enable' : 'disable']();
    		tb.items.get('tb_download')[selections[0].get('is_readable')&&selections[0].get('is_file') ? 'enable' : 'disable']();
    		tb.items.get('tb_extract')[selections[0].get('is_archive') ? 'enable' : 'disable']();
    		tb.items.get('tb_archive').enable();
    		tb.items.get('tb_view').enable();
    	} else {
			tb.items.get('tb_edit').disable();
    		tb.items.get('tb_delete').disable();
    		tb.items.get('tb_rename').disable();
    		tb.items.get('tb_chmod').disable();
    		tb.items.get('tb_download').disable();
    		tb.items.get('tb_extract').disable();
    		tb.items.get('tb_view').disable();
    		tb.items.get('tb_archive').disable();
    	}
    	return true;
    }
    
    // The Quicktips are used for the toolbar and Tree mouseover tooltips!
	Ext.QuickTips.init();
	
    
    // trigger the data store load
    function loadDir() {
    	datastore.load({params:{start:0, limit:150, dir: datastore.directory, option:'com_extplorer', action:'getdircontents', sendWhat: datastore.sendWhat }});
    }
   
    
    function rowContextMenu(grid, rowIndex, e, f) {
    	if( typeof e == 'object') {
    		e.preventDefault();
    	} else {
    		e = f;
    	}
    	gsm = ext_itemgrid.getSelectionModel();
    	gsm.clickedRow = rowIndex;
    	var selections = gsm.getSelections();
    	if( selections.length > 1 ) {
    		gridCtxMenu.items.get('gc_edit').disable();
    		gridCtxMenu.items.get('gc_delete').enable();
    		gridCtxMenu.items.get('gc_rename').disable();
    		gridCtxMenu.items.get('gc_chmod').enable();
    		gridCtxMenu.items.get('gc_download').disable();
    		gridCtxMenu.items.get('gc_extract').disable();
    		gridCtxMenu.items.get('gc_archive').enable();
    		gridCtxMenu.items.get('gc_view').enable();
    	} else if(selections.length == 1) {
    		gridCtxMenu.items.get('gc_edit')[selections[0].get('is_editable')&&selections[0].get('is_readable') ? 'enable' : 'disable']();
    		gridCtxMenu.items.get('gc_delete')[selections[0].get('is_deletable') ? 'enable' : 'disable']();
    		gridCtxMenu.items.get('gc_rename')[selections[0].get('is_deletable') ? 'enable' : 'disable']();
    		gridCtxMenu.items.get('gc_chmod')[selections[0].get('is_chmodable') ? 'enable' : 'disable']();
    		gridCtxMenu.items.get('gc_download')[selections[0].get('is_readable')&&selections[0].get('is_file') ? 'enable' : 'disable']();
    		gridCtxMenu.items.get('gc_extract')[selections[0].get('is_archive') ? 'enable' : 'disable']();
    		gridCtxMenu.items.get('gc_archive').enable();
    		gridCtxMenu.items.get('gc_view').enable();
    	}
		gridCtxMenu.show(e.getTarget(), 'tr-br?' );

    }
    gridCtxMenu = new Ext.menu.Menu({
    	id:'gridCtxMenu',
    
        items: [{
    		id: 'gc_edit',
    		icon: '<?php echo _EXT_URL ?>/images/_edit.png',
    		text: '<?php echo ext_Lang::msg('editlink', true ) ?>',
    		handler: function() { openActionDialog(this, 'edit'); }
    	},
    	{
    		id: 'gc_diff',
    		icon: '<?php echo _EXT_URL ?>/images/extension/document.png',
    		text: '<?php echo ext_Lang::msg('difflink', true ) ?>',
    		handler: function() { openActionDialog(this, 'diff'); }
    	},
    	{
    		id: 'gc_rename',
    		icon: '<?php echo _EXT_URL ?>/images/_fonts.png',
    		text: '<?php echo ext_Lang::msg('renamelink', true ) ?>',
    		handler: function() { ext_itemgrid.onCellDblClick( ext_itemgrid, gsm.clickedRow, 0 ); gsm.clickedRow = null; }
    	},
    	{
        	id: 'gc_copy',
    		icon: '<?php echo _EXT_URL ?>/images/_editcopy.png',
    		text: '<?php echo ext_Lang::msg('copylink', true ) ?>',
    		handler: function() { openActionDialog(this, 'copy'); }
    	},
    	{
    		id: 'gc_move',
    		icon: '<?php echo _EXT_URL ?>/images/_move.png',
    		text: '<?php echo ext_Lang::msg('movelink', true ) ?>',
    		handler: function() { openActionDialog(this, 'move'); }
    	},
    	{
    		id: 'gc_chmod',
    		icon: '<?php echo _EXT_URL ?>/images/_chmod.png',
    		text: '<?php echo ext_Lang::msg('chmodlink', true ) ?>',
    		handler: function() { openActionDialog(this, 'chmod'); }
    	},
    	{
    		id: 'gc_delete',
    		icon: '<?php echo _EXT_URL ?>/images/_editdelete.png',
    		text: '<?php echo ext_Lang::msg('dellink', true ) ?>',
    		handler: function() { openActionDialog(this, 'delete'); }
    	},
    	'-',
    	{
    		id: 'gc_view',
    		icon: '<?php echo _EXT_URL ?>/images/_view.png',
    		text: '<?php echo ext_Lang::msg('viewlink', true ) ?>',
    		handler: function() { openActionDialog(this, 'view'); }
    	},
    	{
    		id: 'gc_download',
    		icon: '<?php echo _EXT_URL ?>/images/_down.png',
    		text: '<?php echo ext_Lang::msg('downlink', true ) ?>',
    		handler: function() { openActionDialog(this,'download'); }
    	},
    	'-',
    	<?php if( ($GLOBALS["zip"] || $GLOBALS["tar"] || $GLOBALS["tgz"]) ) { ?>
	    	{
    			id: 'gc_archive',
	    		icon: '<?php echo _EXT_URL ?>/images/_archive.png',
	    		text: '<?php echo ext_Lang::msg('comprlink', true ) ?>',
	    		handler: function() { openActionDialog(this, 'archive'); }
	    	},
	    	{
	    		id: 'gc_extract',
	    		icon: '<?php echo _EXT_URL ?>/images/_extract.gif',
	    		text: '<?php echo ext_Lang::msg('extractlink', true ) ?>',
	    		handler: function() { openActionDialog(this, 'extract'); }
	    	},
    	<?php } ?>
    	'-',
		{
			id: 'cancel',
    		icon: '<?php echo _EXT_URL ?>/images/_cancel.png',
    		text: '<?php echo ext_Lang::msg('btncancel', true ) ?>',
    		handler: function() { gridCtxMenu.hide(); }
    	}
    	]
    });
    	
	function dirContext(node, e ) {
		// Select the node that was right clicked
		node.select();
		// Unselect all files in the grid
		ext_itemgrid.getSelectionModel().clearSelections();
		
		dirCtxMenu.items.get('dirCtxMenu_rename')[node.attributes.is_deletable ? 'enable' : 'disable']();
		dirCtxMenu.items.get('dirCtxMenu_remove')[node.attributes.is_deletable ? 'enable' : 'disable']();
		dirCtxMenu.items.get('dirCtxMenu_chmod')[node.attributes.is_chmodable ? 'enable' : 'disable']();
		
		dirCtxMenu.node = node;
		dirCtxMenu.show(e.getTarget(), 't-b?' );
		
	}
	
    function copymove( action ) {
	    var s = dropEvent.data.selections, r = [];
		if( s ) {
			// Dragged from the Grid
			requestParams = getRequestParams();
			requestParams.new_dir = dropEvent.target.id.replace( /_RRR_/g, '/' );
			requestParams.new_dir = requestParams.new_dir.replace( /ext_root/g, '' );
			requestParams.confirm = 'true';
			requestParams.action = action;
			handleCallback(requestParams);
		} else {
			// Dragged from inside the tree
			//alert('Move ' + dropEvent.data.node.id.replace( /_RRR_/g, '/' )+' to '+ dropEvent.target.id.replace( /_RRR_/g, '/' ));
			requestParams = getRequestParams();
			requestParams.dir = datastore.directory.substring( 0, datastore.directory.lastIndexOf('/'));
			requestParams.new_dir = dropEvent.target.id.replace( /_RRR_/g, '/' );
			requestParams.new_dir = requestParams.new_dir.replace( /ext_root/g, '' );
			requestParams.selitems = Array( dropEvent.data.node.id.replace( /_RRR_/g, '/' ) );
			requestParams.confirm = 'true';
			requestParams.action = action;
			handleCallback(requestParams);
		}
	}
    // context menus
    var dirCtxMenu = new Ext.menu.Menu({
        id:'dirCtxMenu',
        items: [    	{
        	id: 'dirCtxMenu_new',
    		icon: '<?php echo _EXT_URL ?>/images/_folder_new.png',
    		text: '<?php echo ext_Lang::msg('newlink', true ) ?>',
    		handler: function() {dirCtxMenu.hide();openActionDialog(this, 'mkitem');}
    	},
    	{
    		id: 'dirCtxMenu_rename',
    		icon: '<?php echo _EXT_URL ?>/images/_fonts.png',
    		text: '<?php echo ext_Lang::msg('renamelink', true ) ?>',
    		handler: function() { dirCtxMenu.hide();openActionDialog(this, 'rename'); }
    	},
    	{
        	id: 'dirCtxMenu_copy',
    		icon: '<?php echo _EXT_URL ?>/images/_editcopy.png',
    		text: '<?php echo ext_Lang::msg('copylink', true ) ?>',
    		handler: function() { dirCtxMenu.hide();openActionDialog(this, 'copy'); }
    	},
    	{
    		id: 'dirCtxMenu_move',
    		icon: '<?php echo _EXT_URL ?>/images/_move.png',
    		text: '<?php echo ext_Lang::msg('movelink', true ) ?>',
    		handler: function() { dirCtxMenu.hide();openActionDialog(this, 'move'); }
    	},
    	{
    		id: 'dirCtxMenu_chmod',
    		icon: '<?php echo _EXT_URL ?>/images/_chmod.png',
    		text: '<?php echo ext_Lang::msg('chmodlink', true ) ?>',
    		handler: function() { dirCtxMenu.hide();openActionDialog(this, 'chmod'); }
    	},
    	{
    		id: 'dirCtxMenu_remove',
    		icon: '<?php echo _EXT_URL ?>/images/_editdelete.png',
    		text: '<?php echo ext_Lang::msg('btnremove', true ) ?>',
    		handler: function() { dirCtxMenu.hide();var num = 1; Ext.Msg.confirm('Confirm', String.format("<?php echo $GLOBALS['error_msg']['miscdelitems'] ?>", num ), function(btn) { deleteDir( btn, dirCtxMenu.node ) }); }
    	},'-',
    	<?php if( ($GLOBALS["zip"] || $GLOBALS["tar"] || $GLOBALS["tgz"]) && !ext_isFTPMode() ) { ?>
	    	{
    			id: 'dirCtxMenu_archive',
	    		icon: '<?php echo _EXT_URL ?>/images/_archive.png',
	    		text: '<?php echo ext_Lang::msg('comprlink', true ) ?>',
	    		handler: function() { openActionDialog(this, 'archive'); }
	    	},
    	<?php } ?>
    	{
    		id: 'dirCtxMenu_reload',
    		icon: '<?php echo _EXT_URL ?>/images/_reload.png',
    		text: '<?php echo ext_Lang::msg('reloadlink', true ) ?>',
    		handler: function() { dirCtxMenu.hide();dirCtxMenu.node.reload(); }
    	},
    	'-', 
		{
			id: 'dirCtxMenu_cancel',
    		icon: '<?php echo _EXT_URL ?>/images/_cancel.png',
    		text: '<?php echo ext_Lang::msg('btncancel', true ) ?>',
    		handler: function() { dirCtxMenu.hide(); }
    	}
	]
    });
    var copymoveCtxMenu = new Ext.menu.Menu({
        id:'copyCtx',
        items: [    	{
        	id: 'copymoveCtxMenu_copy',
    		icon: '<?php echo _EXT_URL ?>/images/_editcopy.png',
    		text: '<?php echo ext_Lang::msg('copylink', true ) ?>',
    		handler: function() {copymoveCtxMenu.hide();copymove('copy');}
    	},
    	{
    		id: 'copymoveCtxMenu_move',
    		icon: '<?php echo _EXT_URL ?>/images/_move.png',
    		text: '<?php echo ext_Lang::msg('movelink', true ) ?>',
    		handler: function() { copymoveCtxMenu.hide();copymove('move'); }
    	},'-', 
		{
			id: 'copymoveCtxMenu_cancel',
    		icon: '<?php echo _EXT_URL ?>/images/_cancel.png',
    		text: '<?php echo ext_Lang::msg('btncancel', true ) ?>',
    		handler: function() { copymoveCtxMenu.hide(); }
    	}
	]
    });

    function copymoveCtx(e){
        //ctxMenu.items.get('remove')[node.attributes.allowDelete ? 'enable' : 'disable']();
        copymoveCtxMenu.showAt(e.rawEvent.getXY());
    }
    
	// Hide the Admin Menu under Joomla! 1.5
	try{ 
    		Ext.fly('header-box').hide();Ext.fly('border-top').hide();
	} catch(e) {}
	// Hide the Admin Menu under Joomla! 1.0
	try{
		Ext.fly('header').hide();Ext.select(".menubar").hide();
	} catch(e) {}
	
	var viewport = new Ext.Viewport({
	    layout:'border',
	    defaults: {
	        split: true
	    },
	    items:[{
	        region:"north",
            initialSize: 50,
            titlebar: false,
            closable: false,
            contentEl: "ext_header"
        },{
            xtype: "treepanel",
            region: "west",
        	id: "dirTree",
        	title: '<?php echo ext_Lang::msg('directory_tree', true ) ?> <img src="<?php echo _EXT_URL ?>/images/_reload.png" hspace="20" style="cursor:pointer;" title="reload" onclick="Ext.getCmp(\'dirTree\').getRootNode().reload();" alt="Reload" align="middle" />', 
        	closable: false,
            width: 230,
            titlebar: true,
            autoScroll:true,
    	    animate:true, 
    	    //rootVisible: false,
    	    loader: new Ext.tree.TreeLoader({
    	    	preloadChildren: true,
    	        dataUrl:'<?php echo basename( $GLOBALS['script_name']) ?>',
    	        baseParams: {option:'com_extplorer', action:'getdircontents', dir: '',sendWhat: 'dirs'} // custom http params
    	    }),
    	    containerScroll: true,
    	    enableDD:true,
    	    ddGroup : 'TreeDD',
        	listeners: {
            	//"load": { fn: function(node) { chDir( node.id.replace( /_RRR_/g, '/' ), true ); } }, 
        		'contextmenu': { fn: dirContext },
    			'textchange': { fn: function(node, text, oldText) {
    						if( text == oldText ) return true;
    						var requestParams = getRequestParams();
    						var dir = node.parentNode.id.replace( /_RRR_/g, '/' );
    						if( dir == 'ext_root' ) dir = '';
    						requestParams.dir = dir;
    						requestParams.newitemname = text;
    						requestParams.item = oldText;
    						
    						requestParams.confirm = 'true';
    						requestParams.action = 'rename';
    						handleCallback(requestParams);
    						ext_itemgrid.stopEditing();
    						return true;
    					}	
        		},
        		'beforenodedrop': { fn: function(e){
    						    	    	dropEvent = e;
    						    	    	copymoveCtx(e);
    						    	    }
        		},
        		'beforemove': { fn: function() { return false; } }
        	},
        	root: new Ext.tree.AsyncTreeNode({
                text: '/', 
                draggable:false, 
                expanded: true,
                id:'ext_root',
                listeners: {
            		'contextmenu': { fn: dirContext },
            		'load': { fn: expandTreeToDir }
            	}
            })
        },{
            layout: "border",
            region: "center",
            items: [{
                region: "north",
                xtype: "locationbar",
                id: "locationbarcmp",
                height: 28
            	},
            	{
                region: "center",
                xtype: "tabpanel",
	            id: "mainpanel",
	            enableTabScroll: true,
	            activeTab: 0,
	            items: [{
					xtype: "editorgrid",
		        	region: "center",
		            title: "<?php echo ext_lang::msg("actdir", true ) ?>",
		            autoScroll:true,
		            collapsible: false,
		            closeOnTab: true,
		            id: "gridpanel",
		            ds: datastore,
		            cm: cm,
		           	tbar: gridtb,
		            bbar: gridbb,
		            ddGroup : 'TreeDD',
		            enableDragDrop: true,
		            selModel: new Ext.grid.RowSelectionModel({
		                		listeners: {
		        					'rowselect': { fn: handleRowClick },
		                			'selectionchange': { fn: handleRowClick }
		            			}
		            		  }),
		            loadMask: true,
		            keys:
		            	[{
		                    key: 'c',
		                    ctrl: true,
		                    stopEvent: true,
		                    handler: function() { openActionDialog(this, 'copy'); }
		                   
		               },{
		                    key: 'x',
		                    ctrl: true,
		                    stopEvent: true,
		                    handler: function() { openActionDialog(this, 'move'); }
		                   
		               },{
		                 key: 'a',
		                 ctrl: true,
		                 stopEvent: true,
		                 handler: function() {
		            		ext_itemgrid.getSelectionModel().selectAll();
		                 }
		            }, 
		            {
		            	key: Ext.EventObject.DELETE,
		            	handler: function() { openActionDialog(this, 'delete'); }
		            }
		            ],
		        	listeners: { 'rowcontextmenu': { fn: rowContextMenu },
		        			'celldblclick': { fn: function( grid, rowIndex, columnIndex, e ) { 
	        										if( Ext.isOpera ) { 
	            										// because Opera <= 9 doesn't support the right-mouse-button-clicked event (contextmenu)
	            										// we need to simulate it using the ondblclick event
														rowContextMenu( grid, rowIndex, e );
													} else {
												    	gsm = ext_itemgrid.getSelectionModel();
												    	gsm.clickedRow = rowIndex;
												    	var selections = gsm.getSelections();
												    	if( !selections[0].get('is_file') ) {
													    	chDir( datastore.directory + '/' + selections[0].get('name') );
												    	} else if( selections[0].get('is_editable')) {
													    	openActionDialog( this, 'edit' );
												    	} else if( selections[0].get('is_readable')) {
													    	openActionDialog( this, 'view' );
												    	}
													}
												}
							 },
		        			'validateedit': { fn: function(e) {
		    						if( e.value == e.originalValue ) return true;
		    						var requestParams = getRequestParams();
		    						requestParams.newitemname = e.value;
		    						requestParams.item = e.originalValue;
		    						
		    						requestParams.confirm = 'true';
		    						requestParams.action = 'rename';
		    						handleCallback(requestParams);
		    						return true;
		    					}	
		        			}        			
	        			}
		        	}]
            	}]
	        }
        ],
        renderTo: Ext.getBody(),
        listeners: { "afterlayout": {
	        			fn: function() {
	        				ext_itemgrid = Ext.getCmp("gridpanel");
							//dirTree = Ext.getCmp("dirTree");
							locbar = Ext.getCmp("locationbarcmp");
							locbar.tree = Ext.getCmp("dirTree");
	        				try{ locbar.initComponent(); } catch(e) {}
	        			    /*
	        			    dirTree.loader.on('load', function(loader, o, response ) {
	        			    									if( response && response.responseText ) {
	        				    									var json = Ext.decode( response.responseText );
	        				    									if( json && json.error ) {
	        				    										Ext.Msg.alert('Error', json.error +'onLoad');
	        				    									}
	        				    								}
	        			    });*/
	        			    
	        			    
	        			    var tsm = Ext.getCmp("dirTree").getSelectionModel();
	        			    tsm.on('selectionchange', handleNodeClick );
	        			    
	        			    // create the editor for the directory tree
	        			    var dirTreeEd = new Ext.tree.TreeEditor(Ext.getCmp("dirTree"), {
	        			        allowBlank:false,
	        			        blankText:'A name is required',
	        			        selectOnFocus:true
	        			    });							

	        				chDir( '<?php echo str_replace("'", "\'", $dir ) ?>' );
							
	    				}
	    			}
		}
    });
	Ext.state.Manager.setProvider(new Ext.state.CookieProvider({
	    expires: new Date(new Date().getTime()+(1000*60*60*24*7)) //7 days from now
	}));
		
    <?php
    if( $GLOBALS['require_login'] && $GLOBALS['mainframe']->getUserName() == 'admin' && $GLOBALS['mainframe']->getPassword() == extEncodePassword('admin')) {
    	// Urge User to change admin password!
    	echo 'msgbox = Ext.Msg.alert(\''.ext_Lang::msg('password_warning_title', true ).'\', \''.ext_Lang::msg('password_warning_text', true ) .'\',
    		function(btn) { if( btn == \'ok\' ) openActionDialog( null, \'admin\') }
    	);
    	msgbox.setIcon(Ext.MessageBox.WARNING);
		';
    }
    ?>    
}
if( typeof Ext == 'undefined' ) {
	alert( 'The ExtJS Library could not be found.\nPlease make sure that eXtplorer has been fully installed\nand the file "fetchscript.php" in the\nextplorer directory is not chmodded to 777\n(so chmod to 644 or 666)' );
	document.location = '<?php echo basename( $GLOBALS['script_name']) ?>';
}

function startExtplorer() {
	if(Ext.isIE){
		// As this file is included inline (because otherwise it would throw Element not found JS errors in IE)
		// we need to run the init function onLoad, not onDocumentReady in IE
		Ext.EventManager.addListener(window, "load", ext_init );
	} else {
		// Other Browsers eat onReady
		Ext.onReady( ext_init );
	}
}
<?php
if( ext_isJoomla('1.0.13', '=')) {
	echo "	Ext.Msg.confirm('Joomla! = 1.0.13 detected', 'eXtplorer is not compatible with Joomla! 1.0.13. But if you have applied the <br /><a href=\"http://forum.joomla.org/index.php/topic,193707.msg943504.html#msg943504\">Hotfix for Joomla! 1.0.13</a>, you can continue.<br />'
										+	'<b>Do you have a &quot;hotfixed&quot; version of Joomla! 1.0.13?</b>', function(btn) {  if( btn == 'no' ) document.location='index2.php'; else startExtplorer(); } );\n";
} else {
	echo 'startExtplorer();';
}
?>
</script>
