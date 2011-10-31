function tinyupload(field_name, url, type, win){
	/**
	The tiny upload callback hi-jacks the calling window and impliments an upload and visual 
	image selection.
	*/
	/*
	CONFIG
	*/
	var pathToPhp = 'http://www.example.com/images/tinyupload.php';
	/*
	Nothing to edit past this point unless you know what your doing.
	*/
	win.tuSeclectedUrl = '';
	win.tuIframeLoaded = function(){}
	var wDoc = win.document;
	/*
	Build the UI.
	*/
	var whiteOutDiv = wDoc.createElement('div');
	whiteOutDiv.setAttribute('id', 'tuWhiteOut');
	wDoc.body.appendChild(whiteOutDiv);
	whiteOutDiv.style.cssText = 'position:absolute;top:0px;left:0px;width:100%;height:100%;background:#F0F0EE;';
	var el1 = wDoc.createElement('div');
	el1.setAttribute('id', 'tuUiDiv');
	el1.style.cssText = 'color:#000;padding:20px;';
	whiteOutDiv.appendChild(el1);
	var uiDiv = wDoc.getElementById('tuUiDiv');
	//First fieldset.
	el1 = wDoc.createElement('fieldset');
	el1.style.cssText = 'background:#fff;padding:10px;';
	el1.setAttribute('id', 'tuFsUpload');
	uiDiv.appendChild(el1);
	var fs = wDoc.getElementById('tuFsUpload');
	el1 = wDoc.createElement('legend');
	el1.appendChild(wDoc.createTextNode('Upload'));
	fs.appendChild(el1);
	el1 = wDoc.createElement('div');
	el1.style.cssText = 'overflow:hidden;padding-bottom:5px;';
	el1.setAttribute('id', 'tuDivUpload');
	var el2 = wDoc.createElement('label');
	el2.appendChild(wDoc.createTextNode('Image Upload'));
	el2.style.cssText = 'width:120px;float:left;display:block;';
	el1.appendChild(el2);
	//Frame
	el2 = wDoc.createElement('iframe');
	el2.setAttribute('id', 'tuFrame');
	el2.setAttribute('name', 'tuFrame');
	el2.setAttribute('frameborder', '0');
	el2.style.cssText = 'height:32px;width:290px;background:#ffffff;border-width:0px;';
	el2.setAttribute('src', pathToPhp);
	el1.appendChild(el2);
	fs.appendChild(el1);
	//Inside the frame

	wDoc.getElementById('tuDivUpload').appendChild(el2);
	//fs.appendChild(el1);
	
	/*
	//Where the progress meter should go.
	el1 = wDoc.createElement('div');
	el2 = wDoc.createElement('label');
	el2.appendChild(wDoc.createTextNode('Progress'));
	el2.style.cssText = 'width:120px;float:left;display:block;';
	el1.appendChild(el2);
	//Progress.
	el2 = wDoc.createElement('div');
	el2.setAttribute('id', 'tuProgressBox');
	el2.style.cssText = 'border:1px solid #808080;width:292px;float:left;height:1em;';
	el1.appendChild(el2);
	fs.appendChild(el1);
	el1 = wDoc.createElement('div');
	el1.style.cssText = 'background:#2B6FB6;width:20%;height:1em;';
	el1.setAttribute('id', 'tuProgress');
	wDoc.getElementById('tuProgressBox').appendChild(el1);
	*/
	//Second feildset.
	el1 = wDoc.createElement('fieldset');
	el1.setAttribute('id', 'tuFsSelect');
	el1.style.cssText = 'background:#fff;padding:10px;';
	uiDiv.appendChild(el1);
	fs = wDoc.getElementById('tuFsSelect');
	el1 = wDoc.createElement('legend');
	el1.appendChild(wDoc.createTextNode('Select'));
	fs.appendChild(el1);
	el1 = wDoc.createElement('div');
	el1.style.cssText = 'height:220px;border:1px solid #808080;overflow:auto;';
	el1.setAttribute('id', 'tuDivSelect');
	fs.appendChild(el1);
	//Buttons
	el1 = wDoc.createElement('input');
	el1.setAttribute('type', 'button');
	el1.setAttribute('value', 'Select');
	el1.style.cssText = 'float:left;width:100px;margin-top:5px;';
	el1.setAttribute('id', 'tuBtnSelect');
	uiDiv.appendChild(el1);
	el1 = wDoc.createElement('input');
	el1.setAttribute('type', 'button');
	el1.setAttribute('value', 'Cancel');
	el1.style.cssText = 'float:right;width:100px;margin-top:5px;';
	el1.setAttribute('id', 'tuBtnCancel');
	uiDiv.appendChild(el1);
	
	
	/*
	Events.
	*/
	//Select
	function selectEvt(win){
		return function(){
			win.document.forms[0].elements[field_name].value = win.tuSeclectedUrl;
			win.document.forms[0].elements[field_name].onchange();
			win.document.body.removeChild(win.document.getElementById('tuWhiteOut'));
		}
	}
	wDoc.getElementById('tuBtnSelect').onclick = selectEvt(win);
	
	//Cancel
	function cancelEvt(win){
		return function(){
			win.document.body.removeChild(win.document.getElementById('tuWhiteOut'));
		}
	}
	wDoc.getElementById('tuBtnCancel').onclick = cancelEvt(win);
	
	win.tuFileUploadStarted = function(pth, nme){
		function rntUrl(pth){
			return function(){
				win.document.forms[0].elements[field_name].value = pth;
				win.document.forms[0].elements[field_name].onchange();
				win.document.body.removeChild(win.document.getElementById('tuWhiteOut'));
			}
		}
		win.tuIframeLoaded = rntUrl(pth);
		return true;
	}
	
	/*
	Ajax request 
	*/
	var xhr; 
	try {xhr = new ActiveXObject('Msxml2.XMLHTTP');}
	catch (e) 
	{
		try {xhr = new ActiveXObject('Microsoft.XMLHTTP');}
		catch (e2) 
		{
			try {  xhr = new XMLHttpRequest();     }
			catch (e3) {  xhr = false;   }
		}
	 }

	xhr.onreadystatechange  = function()
	{ 
	 if(xhr.readyState  == 4)
	 {
		if(xhr.status  == 200){ 
			function setUrl(u){
				return function(){
					win.tuSeclectedUrl = u;
					for (var j=0; j<win.tuImageList.length; j++){
						if (win.tuSeclectedUrl == win.tuImageList[j].url){
							wDoc.getElementById('tuSelectEl'+j).style.cssText = 'width:115px;height:128px;float:left;border:1px solid #83A57A;background-color:#CBFFBC;margin:1px;text-align:center;';
						}
						else{
							wDoc.getElementById('tuSelectEl'+j).style.cssText = 'width:115px;height:128px;float:left;border:1px solid #fff;background-color:#fff;margin:1px;text-align:center;cursor:pointer;';
						}
					}
				}
			}
		
			win.tuImageList = eval(xhr.responseText); 
			var imgList = win.tuImageList;
			
			for (var i=0; i<imgList.length; i++){
				cont = wDoc.createElement('div');
				cont.setAttribute('id', 'tuSelectEl'+i);
				cont.style.cssText = 'width:115px;height:128px;float:left;border:1px solid #fff;background-color:#fff;margin:1px;text-align:center;cursor:pointer;';
				img = wDoc.createElement('img');
				img.setAttribute('src', imgList[i].url);
				img.style.cssText = 'width:110px;max-height:98px;border:1px solid #2020D2;';
				cont.appendChild(img);
				txt = wDoc.createElement('div');
				txt.appendChild(wDoc.createTextNode(imgList[i].file));
				cont.appendChild(txt);
				wDoc.getElementById('tuDivSelect').appendChild(cont);
				wDoc.getElementById('tuSelectEl'+i).onclick = setUrl(imgList[i].url);
				if (url == imgList[i].url){
					wDoc.getElementById('tuSelectEl'+i).style.cssText = 'width:115px;height:128px;float:left;border:1px solid #83A57A;background-color:#CBFFBC;margin:1px;text-align:center;';
				}
			}
		}
		else {
		  document.ajax.dyn="Error code " + xhr.status;
		}
	 }
	}; 

 xhr.open('GET', pathToPhp + "?poll=poll",  true); 
 xhr.send(null); 
 

}