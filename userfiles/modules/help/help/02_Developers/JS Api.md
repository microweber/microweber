#forms.js
--- 
Usage

Require the forms api anywhere in the HTML
```html
<script  type="text/javascript">
mw.require('forms.js', true);
</script>
```
 
####Method: `mw.form.post`
---
Allows you to post form fields found in some DOM element by AJAX


The form post function uses the following parameters

`mw.form.post(selector,url,callback)`


You can post any form fields found in given selector from from your  JavaScript functions
```javascript
mw.form.post($('#my_form_or_div') , 'my_url', function(){
var resp =  (this);
});
```


#tools.js
---
Gives you many useful functions.

Usage

Require the utils api anywhere in the HTML
```html
<script  type="text/javascript">
mw.require('utils.js', true);
</script>
```

##Class: mw.notification
--------
Allows you to show notifications to the user's' screen. 

It comes with several methods.

####
 
```javascript
 mw.notification.success("My message.");
 mw.notification.warning("My warning");
 mw.notification.error("Some error!");
```


#### Method:  `mw.notification.msg`
If you want to post something and handle the server response

use the `mw.notification.msg` function with parameter = the server response

```javascript

//usage: mw.notification.msg(resp);

var data = {}
data.id=1
mw.notification.success("Backup started...");
$.post(mw.settings.api_url+'admin/backup/api/create', data ,
     function(resp) {
	 mw.reload_module('admin/backup/manage');
	 mw.notification.msg(resp);
    }
);
     
```







#files.js
---
Gives you functions to work with files. Upload. Unzip, etc. 

Usage

Require the files api anywhere in the HTML
```html
<script  type="text/javascript">
mw.require('files.js', true);
</script>
```




#### Method: `mw.files.uploader`

How to make uploader
`mw.files.uploader` method is used to make a file uploader from Javascript.

Set holder element
```html
  <div id="mw_uploader" class="mw-ui-btn">Browse files</div>
```

Load from Javascript
```javascript
$(document).ready(function(){

    var uploader = mw.files.uploader({
		filetypes:"zip,jpg,png",
		multiple:false
	});
		
	mw.$("#mw_uploader").append(uploader);
	$(uploader).bind("FileUploaded", function(obj, data){
	    alert(data.src);	
    });
    			
});
		
```

##### Parameters for `mw.files.uploader`
parameter  | description |  usage|
|--------------|--------------|--------------|
|`filetypes`  | type of file, you can set custom file types supported by the uploader. Some predefined filetypes are `images`, `videos`, `files`, `documents`, `archives`, `all` | `filetypes:"zip,jpg,png"` |
|`multiple`  | if set to true will allow the user to upload multiple files| `multiple:true` |
|`url`  | you can set custom url to send the files. default is `mw.settings.upload_url`| `url: my_url` |
|`multiple`  | if set to true will allow the user to upload multiple files| `multiple:true` |
|`type`  | 'explorer', or 'filedrag' | `type:'explorer'` |
  
        

 