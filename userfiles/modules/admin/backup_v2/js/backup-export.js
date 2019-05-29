mw.backup_export = {

	choice: function() {
		
		var modalContent = ''
			+ '<a class="mw-ui-btn" onclick="exportContentSelector.select(exportContentSelector.options.data)"><i class="mw-icon-checkmark"></i> Select all</a>'
			+ '&nbsp;&nbsp;<a class="mw-ui-btn" onclick="exportContentSelector.unselectAll()"><i class="mw-icon-close"></i> Unselect all</a><br /><br />'
			+ '<div id="quick-parent-selector-tree"></div>'
			+ '<br /><br /><a class="mw-ui-btn mw-ui-btn-warn" onclick=""><i class="mw-icon-download"></i> Export selected data</a>'
			+ '&nbsp;&nbsp;<a class="mw-ui-btn mw-ui-btn-notification" onclick=""><i class="mw-icon-refresh"></i> Create Full Backup</a>';
		
		mw.modal({
		    content: modalContent,
		    title: 'Select data wich want to export',
		    id: 'mw_backup_export_modal' 
		});
		
        $.get(mw.settings.api_url + "content/get_admin_js_tree_json", function (treeData) {

            var selectedPages = [];
            var selectedCategories = [];

            window.exportContentSelector = new mw.tree({
                data: treeData,
                selectable: true,
                multiPageSelect: true,
                element: '#quick-parent-selector-tree',
                saveState:false
            }); 
            
        });
	},

	
}