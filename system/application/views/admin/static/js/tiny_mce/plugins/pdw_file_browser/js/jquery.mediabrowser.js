(function($){

	$.MediaBrowser = {
	
		absoluteURL: false,
		clipboard: new Array(),
		copyMethod: '',
		ctrlKeyPressed: false,
		currentFile: '',
		currentFolder: '',
		currentView: '',
		dragMode: false,
		dragObj: null,
		dragID: '',
		hostname: "http://" + window.location.host,
		lastSelectedItem:null,
		searchDefaultValue: '',
		shiftKeyPressed: false,
		tableHeadersFixed: 0,
		timeout: null,
		
		init: function(){
			
			// Add treeview
			$('ul.treeview').TreeView();
			
			// Calculate widths and heights of treeview, file viewer and footer
			$.MediaBrowser.resizeWindow();
			
			// Make treeview resizable
			$.MediaBrowser.setResizeHandlers();
			
			// If a filter is specified then hide files with a wrong file type
			$.MediaBrowser.filter();
			
			// Set currently selected folder and view
			$.MediaBrowser.setCurrentFolder($('input#currentfolder').val());
			$.MediaBrowser.currentView = $('input#currentview').val();
			
			//Check if a url should be returned absolute
			$.MediaBrowser.absoluteURL = $('#absolute_url').is(':checked') ? true : false;
			
			//Stripe table if view is Details
			$('div#files table#details tbody tr:odd').addClass('odd');
			
			// *** Navbar ***//
			
			// Style navbar children
			$('div#navbar ul li:has(ul) > a')
				// Add class 'children' for the arrow to show up
				.addClass('children')
				// Add spacer to link for arrow to show up
				.append('<span class="options"><img src="img/spacer.gif" width="20" /></span>')
				.click(function(){
					$(this).next().toggle().end().toggleClass('selected');	
					return false;
				})
			.end();
			
			// select all <li> with children
			$('div#navbar ul li:has(ul)').hover(function(){},function(event){
				$('a.selected', this).removeClass('selected').next().hide();
				event.preventDefault(); //Don't follow link
			});
			
			// *** Search ***//
			
			// Clear search form on first click
			$('div#searchbar')
				.find('input#search').click(function(){
					if ( this.value == this.defaultValue ){	
						this.value = '';	
					}
				})
			.end();
			
			// Start searching while typing
			$('input#search').keyup(function(event){
				var keycode = event.keyCode;
				
				if (!(keycode == 9 || keycode == 13 || keycode == 16 || keycode == 17 || keycode == 18 || keycode == 38 || keycode == 40 || keycode == 224))
				{
					$.MediaBrowser.searchFor($(this).val());
				}
				
				event.preventDefault();
			});
			
			// Set searchbar value to default if input is empty
			$.MediaBrowser.searchDefaultValue = $('input#search').val();
			
			$('input#search').blur(function(){
				if($('input#search').val() == ""){
					$('input#search').val($.MediaBrowser.searchDefaultValue);
				}
			});
			
			// *** Events *** //
			// Check if ctrl key or shift key is pressed
			$(document).keydown(function(event) {
				if(event.ctrlKey || event.metaKey){
					$.MediaBrowser.ctrlKeyPressed = true;
				}
				if(event.shiftKey){
					$.MediaBrowser.shiftKeyPressed = true;	
				}
			}); 
			
			$(document).keyup(function(event) {
				$.MediaBrowser.ctrlKeyPressed = false;
				$.MediaBrowser.shiftKeyPressed = false;
			}); 
			
			// If filter has changed then apply new filtering
			$('select#filters').change(function(){
				$.MediaBrowser.filter();								
			});
			
			// Folder events					
			$('div#files ul li a.folder, div#files table tr.folder').live('dblclick', function(event){
				$.MediaBrowser.loadFolder($(this).attr('href'));	
				event.preventDefault(); //Don't follow link
			});

			$('div#files ul li a.folder, div#files table tr.folder').live('click', function(event){
				if (event.button != 0) return true; //If right click then return true
				$.MediaBrowser.selectFileOrFolder(this,$(this).attr('href'),'folder'); //Select clicked folder
				event.preventDefault(); //Don't follow link
			});
			
			// File events
			$('div#files ul li a.file, div#files table tr.file').live('click', function(event){
				if (event.button != 0) return true; //If right click then return true
				$.MediaBrowser.selectFileOrFolder(this,$(this).attr('href'),'file'); //Select clicked file
				event.preventDefault(); //Don't follow link
			});
			
			$('div#files ul li a.file, div#files table tr.file').live('dblclick', function(event){
                $("form#fileform input#file").val($(this).attr('href'));
				$.MediaBrowser.insertFile();
				event.preventDefault(); //Don't follow link
            });
			
			// Image events
			$('div#files ul li a.image, div#files table tr.image').live('click', function(event){
				if (event.button != 0) return true; //If right click then return true
				$.MediaBrowser.selectFileOrFolder(this,$(this).attr('href'),'image'); //Select clicked image
				event.preventDefault(); //Don't follow link
			});
			
			$('div#files ul li a.image, div#files table tr.image').live('dblclick', function(event){
                var host = '';
				var path = $(this).attr('href');
				
				if($.MediaBrowser.absoluteURL){
                    host = $.MediaBrowser.hostname;
                }
				
				$("form#fileform input#file").val(host + path);
                $.MediaBrowser.insertFile();
                event.preventDefault(); //Don't follow link
            });
			
            $('div#files').click(function(event){
                if (event.button != 0) return true; //If right click then return true
                if ($(event.target).closest('.files').length == 1) return true;

                $('div#files li.selected, div#files tr.selected').removeClass("selected"); //Deselect all selected items
                $.MediaBrowser.updateFileSpecs($.MediaBrowser.currentFolder, 'folder');
                $.MediaBrowser.currentFile = '';

                $('table.contextmenu, div.context-menu-shadow').css({'display': 'none'}); //Hide all contextmenus
            });

			// Add event handlers to links in addressbar
			$('div#addressbar a[href]').live('click', function(event){
				$.MediaBrowser.loadFolder($(this).attr('href'));
				event.preventDefault(); //Don't follow link
			});

			// Add event handlers to links in addressbar
			$('input#fn').live('keyup', function(event){
				if(this.value != this.defaultValue){
					$('a.save_rename').css({'display':'inline'});
				} else {
					$('a.save_rename').css({'display':'none'});	
				}
			});
			
			// Add event handlers to links in treeview
			$('ul.treeview a[href]').live('click', function(event){
				$.MediaBrowser.loadFolder($(this).attr('href'));
				event.preventDefault(); //Don't follow link
			});
			
			//Hide all handlers and show on entering tree div
			$('ul.treeview a.children').css({'opacity' : 0}); //Hide all handlers
			$('div#tree').hover(function(){
					$('ul.treeview a.children').animate({'opacity' : 1}, 'slow');
				}, function(){
					$('ul.treeview a.children').animate({'opacity' : 0}, 'slow');	
				}
			);
			
			//Absolute URl active/inactive
			$('#absolute_url').click(function(){
				if ($('#absolute_url').is(':checked')) {
					$.MediaBrowser.absoluteURL = true;
					if ($.MediaBrowser.currentFile !== '') {
						$("form#fileform input#file").val($.MediaBrowser.hostname + $.MediaBrowser.currentFile);
					}
					$.MediaBrowser.createCookie('absoluteURL', 1, 365);
				}
				else 
				{
					$.MediaBrowser.absoluteURL = false;
					if ($.MediaBrowser.currentFile !== '') {
						$("form#fileform input#file").val($.MediaBrowser.currentFile);
					}
					$.MediaBrowser.createCookie('absoluteURL', 0, 365);
				}
			});
			
			// Reset layout if window is being resized
			window.onresize = window.onload = function(){
				$.MediaBrowser.resizeWindow();
			};
		},
		
		changeview: function(view){
			$.MediaBrowser.currentView = view;
			$.MediaBrowser.loadFolder($.MediaBrowser.currentFolder);
			
			//Clear searchbox
			$('input#search').val($.MediaBrowser.searchDefaultValue);
			
			//Save view to cookie
			$.MediaBrowser.createCookie("pdw-view", $.MediaBrowser.currentView, 30);
			
			return false;
		},
		
		contextmenu: function(){
			$('div#files li a.folder, div#files tr.folder').contextMenu(foldercmenu);
			$('div#files li a.file, div#files tr.file').contextMenu(filecmenu);
			$('div#files li a.image, div#files tr.image').contextMenu(imagecmenu);
			$('div#files').contextMenu(cmenu);
		},
		
		copy: function(){
			// Clear clipboard
			$.MediaBrowser.clipboard = [];
			$.MediaBrowser.copyMethod = 'copy';
			
			$('div#files li.selected a, div#files tr.selected').each(function(){
				$.MediaBrowser.clipboard.push( urlencode($(this).attr("href")) );
			});
			
			//Update clipboard label
			$('div#cbItems').text( $.MediaBrowser.clipboard.length );
		},
		
		cut: function(){
			$.MediaBrowser.copy();
			$.MediaBrowser.copyMethod = 'cut';
			
			$('div#files li.selected, div#files tr.selected').addClass('cut');
		},

		delete_all: function(){
			var message;
			var files = new Array();
			
			// Get all selected files and folders
			$('div#files li.selected a, div#files tr.selected').each(function(){
				files.push( urlencode($(this).attr("href")) );
			});
			
			$.post("actions.php", {'action': 'delete', 'files': files}, function(data){
				if(data.substring(0,7) == 'success'){ //Delete was a success
					message = data.split("||");
					$.MediaBrowser.hideContextMenu();
					$.MediaBrowser.loadFolder($.MediaBrowser.currentFolder);
					$.MediaBrowser.reloadTree();
					$('input#file').val("");
					$.MediaBrowser.showMessage(message[1], "success");
				} else {
					message = data.split("||");
					$.MediaBrowser.showMessage(message[1], "error");
				}							   
			});
		},

		filter: function(){
			if($.MediaBrowser.filterString != ''){
					
				if ($.MediaBrowser.currentView == "details"){
					//Give table headers a fixed width so colums won't change widths when a row gets hidden
					if (!$.MediaBrowser.tableHeadersFixed) $.MediaBrowser.fix_widths();
					$('div#files table tbody tr').css({display: ""}); //NO DISPLAY:BLOCK TABLE CELLS DON"T LIKE IT!!
				} else {
					$('div#files ul li').css({display: "block"});
				}
				
				$('div#files table tbody tr, div#files ul li').removeClass('filter');
				
				// Normalise
				var filterString = $('select#filters').val();
				var strFilter = $.trim(filterString.toLowerCase().replace(/\n/, '').replace(/\s{2,}/, ' '));
				
				if (strFilter != ""){	
				
					var arrList = [];
				
					var rgxpFilter = new RegExp(strFilter,'i');
								
					// Fill array with the list items or table rows depending on view
					if ($.MediaBrowser.currentView == "details"){ 
						arrList = $('div#files table tbody tr:not(.folder) .filename').get();
					
						for(var i = 0; i < arrList.length; i++){
							if ( !rgxpFilter.test( $(arrList[i]).text() ) ) $(arrList[i]).parent().addClass('filter').css({'display': 'none'});
						}
					} else {
						arrList = $('div#files ul li a:not(.folder) .filename').get();
						
						for(var i = 0; i < arrList.length; i++){
							if ( !rgxpFilter.test( $(arrList[i]).text() ) ) $(arrList[i]).parent().parent().addClass('filter').css({'display': 'none'});
						}
					}
		
				}	
				
			}
		},
		
		fix_widths: function(){
			if($.MediaBrowser.currentView == "details"){
				$('table#details th').each(function () {
					$(this).attr('width', parseInt($(this).outerWidth()));
				});
				$.MediaBrowser.tableHeadersFixed = 1;
			}
		},
		
		hideContextMenu: function(){
			// Hide all other contextmenus
			$('table.contextmenu, div.context-menu-shadow').css({'display': 'none'});
		},
			
		hideLayer: function(){
			$(".layer").css({'display':'none'});
			$("div#filelist").css({'display':'block'});
		},
		
		insertFile: function(){
			
			var URL = $("form#fileform input#file").val();
			
			if (URL == '') 
			{
                $.MediaBrowser.showMessage(select_one_file);
			}
			
			
            if(editor == "tinymce")
			{
				try 
				{
					var win = tinyMCEPopup.getWindowArg("window");
				} 
				catch(err) 
				{
					$.MediaBrowser.showMessage(insert_cancelled);
					return;
				}	
					
				// insert information now
				win.document.getElementById(tinyMCEPopup.getWindowArg("input")).value = URL;
						
				// are we an image browser
				if (typeof(win.ImageDialog) != "undefined") 
				{
					// we are, so update image dimensions...
					if (win.ImageDialog.getImageData) 
						win.ImageDialog.getImageData();
							
					// ... and preview if necessary
					if (win.ImageDialog.showPreviewImage) 
						win.ImageDialog.showPreviewImage(URL);
				}
						
				// close popup window
	            tinyMCEPopup.close();
			
				
            } 
			else if(editor == "ckeditor")	
			{
                try {
					window.opener.CKEDITOR.tools.callFunction(funcNum, URL);
					window.close();
				} catch(err) {
					$.MediaBrowser.showMessage(insert_cancelled);
                    return;
				}
			} 
			else if(editor == "standalone")
			{
				window.opener.document.getElementById(returnID).value = URL;
				window.close();
			} 
			else 
			{
				$.MediaBrowser.showMessage("No editor available, see config.php!");
			}

		},
		
		loadFolder: function(folder){
			var message;
			
			// Show loading icon
			$('div#files').html('<div class="loading"></div>');
			
			switch($.MediaBrowser.currentView){
				case 'large_images': 
					viewfile = 'view_images_large.php';
					break;
				case 'small_images': 
					viewfile = 'view_images_small.php';
					break;
				case 'list': 
					viewfile = 'view_list.php';
					break;
				case 'details': 
					viewfile = 'view_details.php';
					break;
				case 'tiles':
					viewfile = 'view_tiles.php';
					break;
				default: //Content
					viewfile = 'view_content.php';
					break;
			}
			
			$.post(viewfile, {'ajax':true, 'path': urlencode(folder)}, function(data){
				if(data.substring(0,3) == '0||'){
					message = data.split("||");
					$('div#files').html("");
					$.MediaBrowser.showMessage(message[1],"error");	
				} else {
					// Set currently selected folder
					$.MediaBrowser.setCurrentFolder(folder);
					
					$.MediaBrowser.updateAddressBar();
					$.MediaBrowser.updateHeader();
					$.MediaBrowser.updateTreeView(folder);
					
					$.MediaBrowser.updateFileSpecs(folder, 'folder');
					
					$('div#files').html(data);
					$.MediaBrowser.filter();
					
					if($.MediaBrowser.currentView == 'details') {
						$('div#files table#details tbody tr:odd').addClass('odd');	
						$.MediaBrowser.tableHeadersFixed = 0;
					}
					$.MediaBrowser.contextmenu();
				}
			});
		},
		
		newFolder: function(){
			var message; 
			var folderpath = $('form#newfolderform input#folderpath').val();
			var foldername = $('form#newfolderform input#foldername').val();
			
			$.post('actions.php', {'ajax':true, 'action': 'create_folder', 'folderpath': urlencode(folderpath), 'foldername': urlencode(foldername)}, function(data){
				if(data.substring(0,7) == 'success'){
					
					$.MediaBrowser.currentFolder = folderpath + foldername + '/';
					
					$.MediaBrowser.reloadTree();
					$('form#newfolderform input#folderpath').val($.MediaBrowser.currentFolder);
					$('form#newfolderform input#foldername').val("");
					
					message = data.split("||");
					$.MediaBrowser.showMessage(message[1],"success");
				} else {
					message = data.split("||");
					$.MediaBrowser.showMessage(message[1],"error");	
				}
			});
		},
		
		paste: function(){
			var action, message;
			
			// Only paste if copyMethod is set
			if($.MediaBrowser.copyMethod != ''){
				action = $.MediaBrowser.copyMethod == 'cut' ? 'cut' : 'copy';
				
				// Show loading icon
				$('div#files').html('<div class="loading"></div>');
				
				$.post("actions.php",
					   { // Post arguments
					   		'action': action+'_paste', 
					   		'files': $.MediaBrowser.clipboard, 
					   		'folder': urlencode($.MediaBrowser.currentFolder)
					   }, 
					   function(data){ // Callback
							if(data.substring(0,7) != 'success'){ // Paste was NOT successful
	
								message = data.split("||");
								$.MediaBrowser.showMessage(message[1],"error");	
								
								// Reload current folder
								$.MediaBrowser.loadFolder($.MediaBrowser.currentFolder);
							}	
							
							// Clear clipboard
							$.MediaBrowser.clipboard = [];
							
							// Reset copyMethod
							$.MediaBrowser.copyMethod = '';					
							
							// Update clipboard label to 0
							$('div#cbItems').text('0');
								
							// Reload current folder
							$.MediaBrowser.loadFolder($.MediaBrowser.currentFolder);
							
							//Reload tree
							$.MediaBrowser.reloadTree();
						}
				);
			}		
		},
		
		printClipboard: function(){
			var cb, str;
			
			cb = $.MediaBrowser.clipboard;
			str = $('div#navbar li.label span').text() + "<br /><br />";
			
			for(i = 0; i < cb.length; i++){
				str	+= urldecode(cb[i]) + "<br />";
			}
			
			$.MediaBrowser.showMessage(str);
			return false;
		},
		
		reloadTree: function(){
			$.post('treeview.php', {'ajax': true}, function(data){
				$('div#tree').html(data);	
				$('ul.treeview').TreeView();
				
				$.MediaBrowser.updateTreeView($.MediaBrowser.currentFolder);
			});	
		},
		
		rename: function(path, type){
			var path_segments, name, old_filename, message, file_segments, file_ext, new_name, prompt_message, new_filename;
			
			path_segments = ($.MediaBrowser.trim(path,"/")).split("/");
			name = path_segments[path_segments.length - 1];
			old_filename = name;
			message = window.rename_folder;
			
			if (type == 'file') {
				//Save extension for later use
				file_segments = name.split(".");
				name = file_segments[0];
				file_ext = file_segments[file_segments.length - 1];
				message = window.rename_file;
			}
			
			prompt_message = printf(message, name, "\n", "^ \\ / ? * \" ' < > : | .");
			new_name = prompt(prompt_message, name);
		
			// Validate new name
			if(new_name === "" || new_name == name || new_name == null)
				return;
				
			// Check if any unwanted characters are used
			if(/\\|\/|\.|\?|\.|\^|\*|\"|'|\<|\>|\:|\|/.test(new_name)){
				$.MediaBrowser.showMessage(invalid_characters_used,"error");	
				return;								
			}
			
			if (type == 'file') {
				new_filename = new_name + '.' + file_ext;
			} else {
				new_filename = new_name;
			}

			//Send new filename to server and do rename		
			$.post("actions.php",
				{ // Post arguments
					'action': 'rename', 
					'new_filename': urlencode(new_filename),  
					'old_filename': urlencode(old_filename), 
					'folder': urlencode($.MediaBrowser.currentFolder),
					'type': type
				}, 
				function(data){ // Callback
					if(data.substring(0,7) != 'success'){ // Paste was NOT successful
						message = data.split("||");
						$.MediaBrowser.showMessage(message[1],"error");
					} else {
						message = data.split("||");
						$.MediaBrowser.showMessage(message[1],"success");
					}
							
					// Reload current folder
					$.MediaBrowser.loadFolder($.MediaBrowser.currentFolder);
							
					// Reload tree
					if(type === "folder")
						$.MediaBrowser.reloadTree();
				}
			);
		},
		
		resizeWindow: function(){
			
			// Set default screen layout
			var windowHeight = $(window).height();
			var addressbarHeight = $('div#addressbar').outerHeight();
			var navbarHeight = $('div#navbar').outerHeight();
			var detailsHeight = $('div#file-specs').outerHeight();
			var explorerHeight = windowHeight - navbarHeight - addressbarHeight - detailsHeight;
			
			var windowWidth = $(window).width();
			var treeWidth = $('div#tree').outerWidth();
			var separatorWidth = $('div#vertical-resize-handler').outerWidth();
			var mainWidth = windowWidth - treeWidth - separatorWidth;
			
			//Set Explorer Height
			$('div#explorer').height(explorerHeight);
			$('div#main').height(explorerHeight);
			$('div#files, div.window').height(explorerHeight - 41); // -41 because of the fixed heading and ruler (H2) above the files
			$('div#main').width(mainWidth);
		},
		
		saveSettings: function(){
			var skin = $("#settings_skin").val();
			var language = $("#settings_language").val();
			
			//Save settings to cookies
			$.MediaBrowser.createCookie('language', language, 365);
			$.MediaBrowser.createCookie('skin', skin, 365);
			
			$.post("actions.php", { 'action': 'settings' }, 
                function(data){ // Callback
                    alert(data);
                    window.location.reload();
                }
            );
		},
		
		searchFor: function(strSearchFor){
			
			clearTimeout($.MediaBrowser.timeout);
			
			$.MediaBrowser.timeout = setTimeout(function () {
			
				if ($.MediaBrowser.currentView == "details"){
					//Give table headers a fixed width so colums won't change widths when a row gets hidden
					if (!$.MediaBrowser.tableHeadersFixed) $.MediaBrowser.fix_widths();
					$('div#files table tbody tr:not(.filter)').css({display: ""}); //NO DISPLAY:BLOCK!!
				} else {
					$('div#files ul li:not(.filter)').css({display: "block"});
				}
				
				// Normalise
				strSearchFor = $.trim(strSearchFor.toLowerCase().replace(/\n/, '').replace(/\s{2,}/, ' '));
				
				if (strSearchFor != ""){	
				
					var arrList = [];
				
					var rgxpSearchFor = new RegExp(strSearchFor,'i');
								
					// Fill array with the list items or table rows depending on view
					if ($.MediaBrowser.currentView == "details"){ 
						arrList = $('div#files table tbody tr:not(.filter) .filename').get();
					
						for(var i = 0; i < arrList.length; i++){
							if ( !rgxpSearchFor.test( $(arrList[i]).text() ) ) $(arrList[i]).parent().css({'display': "none"});
						}
					} else {
						arrList = $('div#files ul li:not(.filter) .filename').get();
						
						for(var i = 0; i < arrList.length; i++){
							if ( !rgxpSearchFor.test( $(arrList[i]).text() ) ) $(arrList[i]).parent().parent().css({'display': "none"});
						}
					}
		
				}
			}, 250);
		},
		
		selectFileOrFolder: function(el, path, type /* , contextmenu */){

			//See if function is called via a context menu
			var cm = (typeof arguments[3] == 'undefined') ? false : true;
            var host = '';

			// Hide all visible contextmenus
			$('table.contextmenu, div.context-menu-shadow').css({'display': 'none'});

			$.MediaBrowser.setSelection(el, cm);
			$.MediaBrowser.updateFileSpecs(path, type);
			
			if(type != "folder" && $('div#files li.selected, div#files tr.selected').length == 1){
				if($.MediaBrowser.absoluteURL){
					host = $.MediaBrowser.hostname;
				}
				$("form#fileform input#file").val(host + path);
				$.MediaBrowser.currentFile = path;
			} else {
				$("form#fileform input#file").val("");	
				$.MediaBrowser.currentFile = '';
			}
		},
		
		setCurrentFolder: function(str){
			$.MediaBrowser.currentFolder = str;
			$('input#uploadpath, input#folderpath').val(str);
		},
		
		setSelection: function(el, cm){
			
			var lastItemNo = null;
			var currentItemNo = null;
			var currentSelectedItem = $(el).attr('href');
			
			el = ($.MediaBrowser.currentView == 'details') ? $(el) : $(el).parent();
			var container = ($.MediaBrowser.currentView == 'details') ? 'tbody' : 'ul';
			
			if($.MediaBrowser.shiftKeyPressed && $.MediaBrowser.lastSelectedItem != null){
				$('div#files li a, div#files tr').each(function(i){
					if($.MediaBrowser.lastSelectedItem == $(this).attr('href')){
						lastItemNo = i;
					}
					
					if(currentSelectedItem == $(this).attr('href')){
						currentItemNo = i;
					}
				});
				
				if(isNumber(lastItemNo) && isNumber(currentItemNo)){
					if(lastItemNo > currentItemNo){
						for(i = currentItemNo; i <= lastItemNo; i++){
							$('div#files li, div#files tr').eq(i).addClass('selected');
						}
					} else {
						for(i = lastItemNo; i <= currentItemNo; i++){
							$('div#files li, div#files tr').eq(i).addClass('selected');
						}
					}
				}
			}
			
			//See if selections should be removed
			if(!$.MediaBrowser.ctrlKeyPressed && !$.MediaBrowser.shiftKeyPressed){
				if(!cm || !el.hasClass("selected")){ //If click is called via a context menu then don't remove selections
					el.parents(container)
						.find('.selected')
						.removeClass('selected')
					.end();
				}
				
				el.addClass('selected');
				
			} else if($.MediaBrowser.ctrlKeyPressed && el.hasClass("selected")) { //If ctrl-key is pressed and item is already selected then deselect item
				el.removeClass('selected');
			} else {		
				el.addClass('selected');
			}
			
			$.MediaBrowser.lastSelectedItem = currentSelectedItem;

		},

		// Resize treeview and details screen
		setResizeHandlers: function(){
			var startingPositionX = 0;
			var startingPositionY = 0;
			var endPositionX = 0;
			var endPositionY = 0;
			
			$(document)

				.mousedown(function(e){
					if ($(e.target).attr('className') == 'resize-grip') {
						$.MediaBrowser.dragID = $(e.target).attr('id');
						startingPositionX = e.pageX;
						startingPositionY = e.pageY;
						$.MediaBrowser.dragMode = true;
						
						$.MediaBrowser.logger('dragID', $.MediaBrowser.dragID);
						
						// Thanks http://luke.breuer.com/tutorial/javascript-drag-and-drop-tutorial.aspx
						// cancel out any text selections 
						document.body.focus(); 
						
						// prevent text selection in IE 
						document.onselectstart = function () { return false; }; 
						
						// prevent IE from trying to drag an image 
						e.target.ondragstart = function() { return false; }; 
						
						// prevent text selection (except IE) 
						return false; 
					}
				})

				.mousemove(function(e){
					if(!$.MediaBrowser.dragMode) return false;
					endPositionX = e.pageX;
					endPositionY = e.pageY;
					if ($.MediaBrowser.dragID == 'vertical-resize-handler'){
						//Horizontal
						slide = endPositionX - startingPositionX
						if ($('div#tree').width() + slide < 300 && $('div#tree').width() + slide > 50 ){
							if (slide > 0){
								$('div#main').width($('div#main').width() - slide);
								$('div#tree').width($('div#tree').width() + slide);
							} else {
								$('div#tree').width($('div#tree').width() + slide);
								$('div#main').width($('div#main').width() - slide);
							}
						} else {
							$.MediaBrowser.dragMode = false;
							$.MediaBrowser.dragID = '';
						}
						
					} else {
						//Vertical
						slide = endPositionY - startingPositionY
						if ($('div#file-specs').height() - slide < 250 && $('div#file-specs').height() - slide > 50 ){
							if (slide > 0){
								$('div#file-specs').height($('div#file-specs').height() - slide);
								$('div#explorer').height($('div#explorer').height() + slide);	
								$('div#files').height($('div#files').height() + slide);	
								$('div#main').height($('div#main').height() + slide);	
							} else {
								$('div#files').height($('div#files').height() + slide);	
								$('div#main').height($('div#main').height() + slide);	
								$('div#explorer').height($('div#explorer').height() + slide);
								$('div#file-specs').height($('div#file-specs').height() - slide);
								
							}
						} else {
							$.MediaBrowser.dragMode = false;
							$.MediaBrowser.dragID = '';
						}
					}
					startingPositionX = e.pageX;
					startingPositionY = e.pageY;
				})

				.mouseup(function(e){
					if($.MediaBrowser.dragMode){
						$.MediaBrowser.dragMode = false;
						$.MediaBrowser.dragID = '';
					}
				})				
			.end();
		},
		
		showMessage: function(str, type){
			$('div#message').removeClass();
			if (type == "success" || type == "error") $('div#message').addClass(type);
			$('div#message').html(str);
			$('div#message').slideDown();
			
			timeout = (type != "error") ? 4000 : 7000;
			
			setTimeout(function() {
				$("div#message").slideUp();
			}, timeout);	
		},
		
		showLayer: function(elID){
			$.MediaBrowser.hideContextMenu();
			$(".layer").css({'display':'none'});
			$("div#" + elID).css({'display':'block'});
			if(elID == 'newfolder') $('input#foldername').focus();
			return false;
		},	

		// Breadcrumbs
		updateAddressBar: function(){
			var strLink = '';
			var html = '<li class=\'root\'><span>&nbsp;</span></li>';
			
			var uploadFolder = $.MediaBrowser.trim($('input#currentfolder').val(), '/');
			var uploadFolders = uploadFolder.split('/');
			var h = uploadFolders.length - 1;  
			
			var curFolder = $.MediaBrowser.trim($.MediaBrowser.currentFolder, '/');
			var folders = curFolder.split('/');
			
			folders.reverse();
			
			for(var i = 0; i < h; i++){
				strLink += '/' + folders.pop();
			}
			
			folders.reverse();
			
			for(var j = 0; j < folders.length; j++){
				html += '<li><a href="';
				
				strLink += '/' + folders[j];
				
				html += strLink + '/' + '" title="' + folders[j] + '"><span>' + folders[j] + '</span></a></li>';
				
			}
			
			$('div#addressbar ol').html(html);
			
		},
		
		// Set name of the folder as header
		updateHeader: function(){	
		
			var curFolder = $.MediaBrowser.trim($.MediaBrowser.currentFolder, '/');
			var folders = curFolder.split('/');
			
			$('div#main div#filelist h2').text(folders[folders.length-1]);
		},
		
		// Open folders and select currently active folder
		updateTreeView: function(folder){
			$('ul.treeview li').removeClass();
			
			$('ul.treeview a[href=' + folder + ']')
				.parents('ul')
					.css({'display':'block'})
					.prevAll('a.children')
						.addClass('open')
					.end()
				.end()
				
				.parent()
					.addClass('selected')
			.end();
			
		},
		
		// Show detailed information over the selected file or folder
		updateFileSpecs: function(path, type){
			$('div#file-specs #info').load('file_specs.php', {'ajax':true, 'path': urlencode(path), 'type': type}, function(){
				$("div#file-specs a[rel^='lightbox']").slimbox({/* Put custom options here */}, null, function(el) {
		            return (this == el) || ((this.rel.length > 8) && (this.rel == el.rel));
		        });
			});
			$('input#file').val("");
		},
		
		// Quirksmode.org --> http://www.quirksmode.org/js/cookies.html
		createCookie: function(name, value, days) {
			if (days) {
				var date = new Date();
				date.setTime(date.getTime()+(days*24*60*60*1000));
				var expires = "; expires="+date.toGMTString();
			}
			else var expires = "";
			document.cookie = name+"="+value+expires+"; path=/";
		},

		trim: function(str, chars) {
			return $.MediaBrowser.ltrim($.MediaBrowser.rtrim(str, chars), chars);
		},
 
		ltrim: function(str, chars) {
			chars = chars || '\\s';
			return str.replace(new RegExp('^[' + chars + ']+', 'g'), '');
		},
 
		rtrim: function(str, chars) {
			chars = chars || '\\s';
			return str.replace(new RegExp('[' + chars + ']+$', 'g'), '');
		}
	};

})(jQuery);

/**
 * PHP.JS (http://phpjs.org)
 *
 * This function is convenient when encoding a string to be used in a query part of a URL, 
 * as a convenient way to pass variables to the next page.
 * 
 * http://phpjs.org/functions/urlencode:573
 */
function urlencode (str) {
    str = (str+'').toString();
    
    // Tilde should be allowed unescaped in future versions of PHP (as reflected below), but if you want to reflect current
    // PHP behavior, you would need to add ".replace(/~/g, '%7E');" to the following.
    return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');
}

/**
 * PHP.JS (http://phpjs.org)
 *
 * http://phpjs.org/functions/urldecode:572
 */
function urldecode (str) {
    return decodeURIComponent(str.replace(/\+/g, '%20'));
}

function isNumber(val) {
	return /^-?((\d+\.?\d?)|(\.\d+))$/.test(val);
}

/**
 * Dav Glass extension for the Yahoo UI Library
 * 
 * Produces output according to format. 
 */
function printf() { 
	var num = arguments.length; 
  	var oStr = arguments[0];   
  	for (var i = 1; i < num; i++) { 
    	var pattern = "\\{" + (i-1) + "\\}"; 
    	var re = new RegExp(pattern, "g"); 
    	oStr = oStr.replace(re, arguments[i]); 
  	} 
  	return oStr; 
} 