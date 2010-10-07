var url = tinyMCE.getParam("php_external_list_url");
if (url != null) {
	// Fix relative
	if (url.charAt(0) != '/' && url.indexOf('://') == -1)
		url = tinyMCE.documentBasePath + "/" + url;

	document.write('<sc'+'ript language="javascript" type="text/javascript" src="' + url + '"></sc'+'ript>');
}

function init() {
	tinyMCEPopup.resizeToInnerSize();

//	document.getElementById("filebrowsercontainer").innerHTML = getBrowserHTML('filebrowser','file','php','php');

	// Image list outsrc
	var html = getPHPListHTML('filebrowser','file','php','php');
	if (html == "")
		document.getElementById("linklistrow").style.display = 'none';
	else
		document.getElementById("linklistcontainer").innerHTML = html;

	var formObj = document.forms[0];
	var php_code   = tinyMCE.getWindowArg('php_code');
	
/* not implemented at this time
	var php_width  = '' + tinyMCE.getWindowArg('php_width');
	var php_height = '' + tinyMCE.getWindowArg('php_height');

	if (php_width.indexOf('%')!=-1) {
		formObj.width2.value = "%";
		formObj.width.value  = php_width.substring(0,php_width.length-1);
	} else {
		formObj.width2.value = "px";
		formObj.width.value  = php_width;
	}

	if (php_height.indexOf('%')!=-1) {
		formObj.height2.value = "%";
		formObj.height.value  = php_height.substring(0,php_height.length-1);
	} else {
		formObj.height2.value = "px";
		formObj.height.value  = php_height;
	}
*/
	//formObj.file.value = php_code;
	document.getElementById("codecontainer").innerHTML = 
	'<textarea id="code" name="code" cols="80" rows="20">' + php_code + '</textarea>';

	formObj.insert.value = tinyMCE.getLang('lang_' + tinyMCE.getWindowArg('action'), 'Insert', true);

	selectByValue(formObj, 'linklist', php_code);
/*
	// Handle file browser
	if (isVisible('filebrowser'))
		document.getElementById('file').style.width = '230px';
*/
	// Auto select flash in list
	var option_selected = false;
	if (typeof(tinyMCEPHPList) != "undefined" && tinyMCEPHPList.length > 0) {
		for (var i=0; i<formObj.linklist.length; i++) {
			if (formObj.linklist.options[i].value == tinyMCE.getWindowArg('php_code'))
			{
				formObj.linklist.options[i].selected = true;
				formObj.desc.value = formObj.linklist.options[i].title;
				document.getElementById("description").title = formObj.file.value;
				option_selected = true;
			}
		}
		if(option_selected != true)
		{
				formObj.desc.value = "Custom Code";
				document.getElementById("description").title = "Custom Code";
		}
	}
}

function getPHPListHTML() {
	if (typeof(tinyMCEPHPList) != "undefined" && tinyMCEPHPList.length > 0) {
		var html = "";

		html += '<select id="linklist" name="linklist" style="width: 250px" ' + 
					'onfocus="tinyMCE.addSelectAccessibility(event, this, window);" ' + 
					'onchange="this.form.code.value=this.options[this.selectedIndex].value; ' + 
					'this.form.desc.value=this.options[this.selectedIndex].title; ' + 
					'document.getElementById(\'description\').title=this.form.desc.value;">';
		html += '<option value="" title="Custom Code">---</option>';

		for (var i=0; i<tinyMCEPHPList.length; i++)
			html += '<option value="' + tinyMCEPHPList[i][1] + '" title="' + tinyMCEPHPList[i][2] + 
						'">' + tinyMCEPHPList[i][0] + '</option>';

		html += '</select>';

		return html;
	}

	return "";
}

function insertPHP() {
	var formObj = document.forms[0];
	var html      = '';
	var code      = formObj.code.value;
	var width = 20;
	var height = 20;

	code = code.replace(/"/gi, "&quot;");
	html += ''
		+ '<img src="' + (tinyMCE.getParam("theme_href") + "/images/spacer.gif") + '" mce_src="' + (tinyMCE.getParam("theme_href") + "/images/spacer.gif") + '" '
		+ 'width="' + width + '" height="' + height + '" '
		+ 'border="0" alt="' + code + '" class="mceItemPHP" />';
	tinyMCEPopup.execCommand("mceInsertContent", true, html);
	tinyMCE.selectedInstance.repaint();

	tinyMCEPopup.close();
}
