
var SWFUpload;if(typeof(SWFUpload)==="function"){SWFUpload.prototype.initSettings=function(oldInitSettings){return function(userSettings){if(typeof(oldInitSettings)==="function"){oldInitSettings.call(this,userSettings);}
this.refreshCookies(false);};}(SWFUpload.prototype.initSettings);SWFUpload.prototype.refreshCookies=function(sendToFlash){if(sendToFlash===undefined){sendToFlash=true;}
sendToFlash=!!sendToFlash;var postParams=this.settings.post_params;var i,cookieArray=document.cookie.split(';'),caLength=cookieArray.length,c,eqIndex,name,value;for(i=0;i<caLength;i++){c=cookieArray[i];while(c.charAt(0)===" "){c=c.substring(1,c.length);}
eqIndex=c.indexOf("=");if(eqIndex>0){name=c.substring(0,eqIndex);value=c.substring(eqIndex+1);postParams[name]=value;}}
if(sendToFlash){this.setPostParams(postParams);}};}