========================
IMPORTANT
========================
The upload folder and cache folder need write permission for the file browser to function properly!


========================
CONFIG.PHP
========================
Edit the config.php file to specify your upload folder, default language and default view layout and many more.


========================
TinyMCE installation
========================

Add the following setting to the TinyMCE configuration:
--------------------------------------------------------------------------
file_browser_callback: "filebrowser",


Add the following function to your javascript (for example below the configuration settings)
--------------------------------------------------------------------------
function filebrowser(field_name, url, type, win) {
		
	fileBrowserURL = "/path/to/file/browser/index.php?filter=" + type;
			
	tinyMCE.activeEditor.windowManager.open({
		title: "PDW File Browser",
		url: fileBrowserURL,
		width: 950,
		height: 650,
		inline: 0,
		maximizable: 1,
		close_previous: 0
	},{
		window : win,
		input : field_name
	});		
}


========================
CKEditor installation
========================

Add the following settings to the CKEditor configuration:
--------------------------------------------------------------------------
filebrowserBrowseUrl : '/pdw_file_browser/index.php?editor=ckeditor',
filebrowserImageBrowseUrl : '/pdw_file_browser/index.php?editor=ckeditor&filter=image',
filebrowserFlashBrowseUrl : '/pdw_file_browser/index.php?editor=ckeditor&filter=flash',
