var action, pre, codeType;

function init() {
	tinyMCEPopup.resizeToInnerSize();

	var inst = tinyMCE.getInstanceById(tinyMCE.getWindowArg('editor_id'));
	pre = tinyMCE.getParentElement(inst.getFocusElement(), "pre");
	action = 'insert';

	document.forms[0].insert.value = tinyMCE.getLang('lang_' + action, 'Insert', true);
	getAuthorizedLanguages();
	resizeInputs();
}

function insertCode() {
	codeType = document.forms[0].codeType.options[document.forms[0].codeType.selectedIndex].value;
	var codeLegend = document.forms[0].codeLegend.value;
	var code = document.forms[0].codeContent.value;
	
	var urlParams = 'lang='+codeType+'&legend='+encodeURIComponent(codeLegend)
					+'&code='+encodeURIComponent(code)+'&action='+action;
	highlightAndSetCode(urlParams);
	if(tinyMCE.isGecko) {
	   // workaround a FF bug   
	   setTimeout(function(){tinyMCEPopup.close();},1000);
	} else {
	   tinyMCEPopup.close();
	}
}

function doInsertCode(code) {
	var inst = tinyMCE.getInstanceById(tinyMCE.getWindowArg('editor_id'));
	tinyMCEPopup.execCommand("mceBeginUndoLevel");

	if (action == "update") {
		pre.setAttribute("class", codeType);
		pre.innerHTML = '';
		pre.innerHTML = code;
	} else {
		var rng = inst.getRng();

		if (rng.collapse)
			rng.collapse(false);
		
		tinyMCEPopup.execCommand("mceInsertContent", false, code+'<p></p>');
		tinyMCE.handleVisualAid(inst.getBody(), true, inst.visualAid, inst);
	}

	tinyMCEPopup.execCommand("mceEndUndoLevel");

	tinyMCE.triggerNodeChange();
}

var wHeight=0, wWidth=0;

function resizeInputs() {
	if (!tinyMCE.isMSIE) {
		 wHeight = self.innerHeight-140;
		 wWidth = self.innerWidth-16;
	} else {
		 wHeight = document.body.clientHeight - 155;
		 wWidth = document.body.clientWidth - 16;
	}

	document.forms[0].codeContent.style.height = Math.abs(wHeight) + 'px';
	document.forms[0].codeContent.style.width  = Math.abs(wWidth) + 'px';
	document.forms[0].codeLegend.style.width  = Math.abs(wWidth) + 'px';
}

// AJAX Handling
function createRequestObject() {
    var ro;
    var browser = navigator.appName;
    if(browser == "Microsoft Internet Explorer"){
        ro = new ActiveXObject("Microsoft.XMLHTTP");
    }else{
        ro = new XMLHttpRequest();
    }
    return ro;
}

var http = createRequestObject();

function renewRequestObject() {
	http = null;
	http = createRequestObject();
}

function highlightAndSetCode(urlParams) {
	if(http.readyState) {
		if(http.readyState == 4) {
			if (http.status==200) {
				doInsertCode(http.responseText);
  				renewRequestObject();
				return true;
  			} else {
  				alert("Problem retrieving data:" + http.statusText);
  				renewRequestObject();
  				return false;
  			}
		}
  	} else {
    	http.open('post', tinyMCE.baseURL+'/plugins/insertcode/webservice/get_highlighted_code.php', true);
    	http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    	http.onreadystatechange = highlightAndSetCode;
    	http.send(urlParams);
  	}
}

function deleteHighlighting(urlParams) {
	if(http.readyState) {
		if(http.readyState == 4) {
			if (http.status==200) {
				document.forms[0].codeContent.value = http.responseText;
  				renewRequestObject();
				return true;
  			} else {
  				alert("Problem retrieving data:" + http.statusText);
  				renewRequestObject();
  				return false;
  			}
		}
  	} else {
    	http.open('post', tinyMCE.baseURL+'/plugins/insertcode/webservice/get_source_code.php', true);
    	http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    	http.onreadystatechange = deleteHighlighting;
    	http.send(urlParams);
  	}
}

function getAuthorizedLanguages() {
	if(http.readyState) {
		if(http.readyState == 4) {
			if (http.status==200) {
				document.getElementById('codeTypeCt').innerHTML = http.responseText;
				renewRequestObject();
				            	    
			    if (pre != null) {
					action = "update";
					document.forms[0].insert.value = tinyMCE.getLang('lang_' + action, 'Insert', true); 
					// set the selected language
	            	for(var i=0;i<document.forms[0].codeType.options.length;i++)
	            	  if(document.forms[0].codeType.options[i].value == pre.getAttribute('class') )
	            	    document.forms[0].codeType.selectedIndex = i;
							
					// get the legend (if any)
					if((head = tinyMCE.getElementByAttributeValue(pre, 'div', 'class', 'head')) != null)
					   document.forms[0].codeLegend.value = head.innerHTML;
			
					// get the source code
					var olArr = pre.getElementsByTagName('ol');
					var ol = olArr[0];
					var urlParams = 'code='+encodeURIComponent(ol.innerHTML);
			
					deleteHighlighting(urlParams);
				}
				return true;
  			} else {
  				alert("Problem retrieving data:" + http.statusText);
  				renewRequestObject();
  				return false;
  			}
		}
  	} else {
    	http.open('get', tinyMCE.baseURL+'/plugins/insertcode/webservice/get_auth_lang.php');
    	http.onreadystatechange = getAuthorizedLanguages;
    	http.send(null);	
  	}
}