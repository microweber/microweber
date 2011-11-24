/**
* Name: editor_plugin_src.js (for ccSimpleUploader tinyMCE plugin)
* Author: Timur Kovalev - www.creativecodedesign.com
* Distribution: Free for all to modify, distribute, and use, but I am not liable for any issues or problems.
*/

(function() {
    var strPluginURL;
    tinymce.create('tinymce.plugins.ccSimpleUploaderPlugin', {
        init: function(ed, url) 
        {
            strPluginURL = url;                                         // store the URL for future use..
            ed.addCommand('mceccSimpleUploader', function() {
                ccSimpleUploader();              
            });
            ed.addButton('ccSimpleUploader', {
                title: 'ccSimpleUploader',
                cmd: 'mceccSimpleUploader',
                image: url + '/img/ccSimpleUploader.png'
            });
        },
        createControl: function(n, cm) {
            return null;
        },
        getPluginURL: function() {
            return strPluginURL;
        },
        getInfo: function() {
            return {
                longname: 'ccSimpleUploader plugin',                
                author: 'Timur Kovalev',
                authorurl: 'http://www.creativecodedesign.com',
                infourl: 'http://www.creativecodedesign.com',
                version: "0.1"
            };
        }
    });
    tinymce.PluginManager.add('ccSimpleUploader', tinymce.plugins.ccSimpleUploaderPlugin);
})();

// this function can get called from the plugin inint (above) or from the callback on advlink/advimg plugins..
// in the latter case, win and type will be set.. In the rist case, we will just update the main editor window
// with the path of the uploaded file
function ccSimpleUploader(field_name, url, type, win) {    
    var strPluginPath = tinyMCE.activeEditor.plugins.ccSimpleUploader.getPluginURL();                               // get the path to the uploader plugin    
    var strUploaderURL = strPluginPath + "/uploader.php";                                                           // generate the path to the uploader script    
    var strUploadPath = tinyMCE.activeEditor.getParam('plugin_ccSimpleUploader_upload_path');                       // get the relative upload path
    var strSubstitutePath = tinyMCE.activeEditor.getParam('plugin_ccSimpleUploader_upload_substitute_path');        // get the path we'll substitute for the for the upload path (i.e. fully qualified)

    if (strUploaderURL.indexOf("?") < 0)                                                                            // if we were called without any GET params
        strUploaderURL = strUploaderURL + "?type=" + type + "&d=" + strUploadPath + "&subs=" + strSubstitutePath;   // add our own params 
    else
        strUploaderURL = strUploaderURL + "&type=" + type + "&d=" + strUploadPath + "&subs=" + strSubstitutePath;
    
    tinyMCE.activeEditor.windowManager.open({                                                                       // open the plugin popup
        file            : strUploaderURL,
        title           : 'cc Simple Uploader',
        width           : 400,  
        height          : 100,
        resizable       : "yes", 
        inline          : 1,        // This parameter only has an effect if you use the inlinepopups plugin!
        close_previous  : "no"
    }, {
        window : win,
        input : field_name
    });
  
    return false;
}
// This function will get called when the uploader is done uploading the file and ready to update
// calling dialog and close the upload popup
// strReturnURL should be the string with the path to the uploaded file
function ClosePluginPopup (strReturnURL) {
    var win = tinyMCEPopup.getWindowArg("window");                                          // insert information now
    if (!win)
        tinyMCE.activeEditor.execCommand('mceInsertContent', false, strReturnURL);
    else
    {
        win.document.getElementById(tinyMCEPopup.getWindowArg("input")).value = strReturnURL;	
	    if (typeof(win.ImageDialog) != "undefined")                                             // are we an image browser
	    {		
		    if (win.ImageDialog.getImageData) win.ImageDialog.getImageData();                   // we are, so update image dimensions and preview if necessary
		    if (win.ImageDialog.showPreviewImage) win.ImageDialog.showPreviewImage(strReturnURL);
	    }	
	}
	tinyMCEPopup.close();	                                                                    // close popup window
}