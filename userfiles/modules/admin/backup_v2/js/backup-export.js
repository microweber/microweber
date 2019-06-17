mw.backup_export = {

	select_all: function() {
		exportContentSelector.select(exportContentSelector.options.data);
		$('.js-export-items').prop('checked','true');
	},
	
	unselect_all: function() {
		exportContentSelector.unselectAll();
		$('.js-export-items').removeAttr('checked');
	},
	
	choice: function() {
		
		var modalContent = ''
			+ '<h3><b>Create full backup of your site</b></h3>'
			+ 'Use the button to export full backup of your website with all data.'
			+ '<br />'
			+ '<br />'
			+ '<div class="mw-flex-row">'
			+ '<div class="mw-flex-col-md-6">'
			+ '&nbsp;&nbsp;<a class="mw-ui-btn mw-ui-btn-notification" onclick="mw.backup_export.export_fullbackup_start()"><i class="mw-icon-refresh"></i> Create Full Backup</a>'
			+ '</div>'
			+ '<div class="mw-flex-col-md-6">'
			+ '<div class="js-export-log" style="padding-top:8px"></div>'
			+ '</div>'
			+ '</div>'
			+ '<br />'
			+ '<br />'
			+ '<h3><b>Select items to export</b></h3>'
			+ 'Select pages, categories, posts etc. you want to export.'
			+ '<br />'
			+ '<br />'
			+ '<a class="mw-ui-btn mw-ui-btn-outline mw-ui-btn-medium" onclick="mw.backup_export.select_all();"><i class="mw-icon-checkmark"></i> Select all</a>'
			+ '&nbsp;&nbsp;<a class="mw-ui-btn mw-ui-btn-outline mw-ui-btn-medium" onclick="mw.backup_export.unselect_all();"><i class="mw-icon-close"></i> Unselect all</a><br /><br />'
			+ '<div id="quick-parent-selector-tree"></div>'
			
			+ '<hr />'
			+ 'Select types of data you want to export.'
			+ '<br />'
			+ '<br />'
			
			+ '<label class="mw-ui-check">'
			+ '<input  class="js-export-items" type="checkbox" value="media" name="export_items"><span></span><span>Export media</span>'
			+ '</label>&nbsp;&nbsp;'
			
			+ '<label class="mw-ui-check">'
			+ '<input  class="js-export-items" type="checkbox" value="comments" name="export_items"><span></span><span>Export comments</span>'
			+ '</label>&nbsp;&nbsp;'
			
			+ '<label class="mw-ui-check">'
			+ '<input class="js-export-items" type="checkbox" value="cart_orders" name="export_items"><span></span><span>Export orders</span>'
			+ '</label>&nbsp;&nbsp;'
			
			+ '<label class="mw-ui-check">'
			+ '<input class="js-export-items" type="checkbox" value="users" name="export_items"><span></span><span>Export users</span>'
			+ '</label>&nbsp;&nbsp;'
			
			+ '<label class="mw-ui-check">'
			+ '<input  class="js-export-items" type="checkbox" value="menus" name="export_items"><span></span><span>Export menus</span>'
			+ '</label><br /><br />'
			
			+ '<label class="mw-ui-check">'
			+ '<input  class="js-export-items" type="checkbox" value="options" name="export_items"><span></span><span>Export settings</span>'
			+ '</label>'
			+ '<br /><br />'
			+ '<div class="mw-flex-row">'
			+ '<div class="mw-flex-col-md-6">'
			+ '<a class="mw-ui-btn mw-ui-btn-warn" onclick="mw.backup_export.export_start()"><i class="mw-icon-download"></i> Export selected data</a>'
			+ '</div>'
			+ '<div class="mw-flex-col-md-6">'
			+ '<div class="js-export-log"></div>'
			+ '</div>'
			+ '</div>';
		
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
	
	export_selected: function(manifest) {
		
		mw.backup_export.get_log_check('start');
		
		$.post(mw.settings.api_url+'Microweber/Utils/BackupV2/export', manifest , function(exportData) {
			
			if (typeof(exportData.data.download) !== 'undefined') {
				mw.backup_export.get_log_check('stop');
				$('.js-export-log').html('<a href="'+exportData.data.download+'" class="mw-ui-link" style="font-size:14px;font-weight:bold;"><i class="mw-icon-download"></i> Download your backup</a>');
			 	mw.notification.success(exportData.success);
			} else {
				mw.backup_export.export_selected(manifest);
			}
			// console.log(exportData.data.download);
		 });
	},
	
	get_log_check: function(action = 'start') {
		
		// mw.notification.success("Export started...");
		
		var importLogInterval = setInterval(function() {
			mw.backup_export.get_log();
		}, 5000);
		
		if (action == 'stop') {
			for(i=0; i<10000; i++) {
		        window.clearInterval(i);
		    }
		}
		
	}, 
	
	get_log: function() {
		$.ajax({
		    url: userfilesUrl + 'backup-export-session.log',
		    success: function (data) {
		    	data = data.replace(/\n/g, "<br />"); 
		    	$('.js-export-log').html('<br />' + data + '<br /><br />');
		    },  
		    error: function() {
		    	$('.js-export-log').html('Error opening log file.');
		    }
		});
	},
	
	export_fullbackup_start: function() {
		$('.js-export-log').html('Generating full backup...');
        mw.backup_export.export_selected('all');
	},
	
	export_start: function () {
		
        var selected_content = exportContentSelector.options.selectedData;
        var selected_export_items = exportContentSelector.options.selectedData;

        var selected_export_items = [];

        /* look for all checkboes that have a class 'chk' attached to it and check if it was checked */
        $(".js-export-items:checked").each(function() {
            selected_export_items.push($(this).val());
        });

        var selected;
        selected = selected_export_items.join(',') ;

        if(selected.length == 0 && selected_content.length == 0){
            Alert("Please check at least one of the checkboxes");
            return;
        }

        var export_manifest = {};
        export_manifest.content_ids = [];
        export_manifest.categories_ids = [];
        export_manifest.items = selected;
        
        selected_content.forEach(function(item, i){
            if(item.type == 'category' ){
                export_manifest.categories_ids.push(item.id);
            } else {
                export_manifest.content_ids.push(item.id);
            }
        });
        
        $('.js-export-log').html('Generating backup...');
        
        mw.backup_export.export_selected(export_manifest);
        mw.log(export_manifest);
    }
}