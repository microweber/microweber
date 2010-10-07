<table width="600" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><script type="text/javascript">

	

	

	function  contentMediaEditPicture($id){



	if($("#content_form_object").hasClass("save_disabled")){

	alert("Error: You cannot delete while uploading!");

	return false;

	} else {

	

	}





}

	

function  contentMediaDeletePicture($id){



if($("#content_form_object").hasClass("save_disabled")){

alert("Error: You cannot delete while uploading!");

return false;

}







var answer = confirm("Are you sure?")

	if (answer){

		$.post("<?php print site_url('admin/core/mediaDelete') ?>", { id: $id, time: "2pm" },

  function(data){

	  //$("#gallery_module_sortable_pics_positions_"+$id).fadeOut();

	  $("#gallery_module_sortable_pics_positions_"+$id).remove();

	// contentMediaPicturesRefreshList();

   //alert("Data Loaded: " + data);

  });

	}

	else{

		//alert("Thanks for sticking around!")

	}

 

}







function contentMediaPicturesRefreshList(){

var media_upload_queue_pic = $('#media_queue_pictures').val();

var to_table_id1 = $('#id').val();



$.post("<?php print site_url('admin/media/contentMediaPicturesList') ?>/to_table:table_content/queue_id:"+media_upload_queue_pic+"/to_table_id:"+to_table_id1+"/random_stuff:"+Math.random(), function(data){

  $("#media_pictures_placeholder").html(data);



if ( $("#gallery_module_sortable_pics").exists() ){

	$("#gallery_module_sortable_pics").sortable(

	{

	update : function () {

	var order = $('#gallery_module_sortable_pics').sortable('serialize');

	$.post("<?php print site_url('admin/media/reorderMedia') ?>", order,

	function(data){

	});

	}

	}				

	);

}









 

});



}

</script>
      <script type="text/javascript">



 $(document).ready(function(){

contentMediaPicturesRefreshList();

var media_upload_queue = $('#media_queue_pictures').val();



$("#pictures_upload_progressbar").progressbar({

			value: 0

		});

 });

 

 

 

 

 

 

 

 

 

 

 

 

</script>
      <script type="text/javascript">



	function picturesUploadUpdateProgressbar(){

	

	//$total = $('#pictures_upload_progressbar_total_count').html();

	//$total = parseInt($total);

	

	//$currently_uploaded = $('#pictures_upload_progressbar_currently_uploaded').html();

	//$currently_uploaded = parseInt($currently_uploaded);

		

		//if($currently_uploaded == $total){

			

		//} else {

			//if(($total > 0)){

			//	a = $currently_uploaded;

			//	b = $total;

			//	c = a/b;

			//	d = Math.round(c*100);

			//	d = 100 - d;

				//$('#pictures_upload_progressbar').progressbar('option', 'value', d);

		//	}

		//}

	

	}

	</script>
      <div>
        <div id="pics_uploaderUI" style="width:100px;height:40px;margin-left:5px;float:left"></div>
      </div>
      <div id="pic_uiElements"> </div>
      <div id="pic_dataTableContainer"> </div>
      <script type="text/javascript"> 

 

	// Custom URL for the uploader swf file (same folder).

	YAHOO.widget.Uploader.SWFURL = "<?php print_the_static_files_url() ; ?>yui/uploader.swf?pic"; 

 

    // Instantiate the uploader and write it to its placeholder div.

	

 

	var pic_uploader = new YAHOO.widget.Uploader( "pics_uploaderUI", "<?php print_the_static_files_url() ; ?>yui/selectFileButton.png" );

	// Add event listeners to various events on the pic_uploader.

	// Methods on the uploader should only be called once the 

	// contentReady event has fired.

	

	pic_uploader.addListener('contentReady', pic_uploader_handleContentReady);

	pic_uploader.addListener('fileSelect', pic_uploader_onFileSelect)

	pic_uploader.addListener('uploadStart', pic_uploader_onUploadStart);

	pic_uploader.addListener('uploadProgress', pic_uploader_onUploadProgress);

	pic_uploader.addListener('uploadCancel', pic_uploader_onUploadCancel);

	pic_uploader.addListener('uploadComplete', pic_uploader_onUploadComplete);

	pic_uploader.addListener('uploadCompleteData', pic_uploader_onUploadResponse);

	pic_uploader.addListener('uploadError', pic_uploader_onUploadError);

    pic_uploader.addListener('rollOver', pic_uploader_handleRollOver);

    pic_uploader.addListener('rollOut', pic_uploader_handleRollOut);

    pic_uploader.addListener('click', pic_uploader_handleClick);

    	

    // Variable for holding the filelist.

	var pic_fileList;

	

	// When the mouse rolls over the uploader, this function

	// is called in response to the rollOver event.

	// It changes the appearance of the UI element below the Flash overlay.

	function pic_uploader_handleRollOver () {

	//	YAHOO.util.Dom.setStyle(YAHOO.util.Dom.get('selectLink123'), 'color', "#FFFFFF");

		//YAHOO.util.Dom.setStyle(YAHOO.util.Dom.get('selectLink123'), 'background-color', "#000000");

	}

	

	// On rollOut event, this function is called, which changes the appearance of the

	// UI element below the Flash layer back to its original state.

	function pic_uploader_handleRollOut () {

	//	YAHOO.util.Dom.setStyle(YAHOO.util.Dom.get('selectLink123'), 'color', "#0000CC");

	//	YAHOO.util.Dom.setStyle(YAHOO.util.Dom.get('selectLink123'), 'background-color', "#FFFFFF");

	}

	

	// When the Flash layer is clicked, the "Browse" dialog is invoked.

	// The click event handler allows you to do something else if you need to.

	function pic_uploader_handleClick () {

	}

	

	// When contentReady event is fired, you can call methods on the pic_uploader.

	function pic_uploader_handleContentReady () {

	    // Allows the uploader to send log messages to trace, as well as to YAHOO.log

		pic_uploader.setAllowLogging(false);

		

		// Allows multiple file selection in "Browse" dialog.

		pic_uploader.setAllowMultipleFiles(true);

		

		// New set of file filters.

	/*	var ff = new Array({description:"Images", extensions:"*.jpg;*.png;*.gif"},

		                   {description:"Videos", extensions:"*.avi;*.mov;*.mpg"});*/

						   var ff1 = new Array({description:"Images", extensions:"*.jpg;*.png;*.gif"});

		                      
	    // New set of file filters. 
	    var ff2 = new Array({description:"Images", extensions:"*.jpg;*.png;*.gif"}); 

		// Apply new set of file filters to the pic_uploader.
//alert(ff2);
		pic_uploader.setFileFilters(ff2);

	}

 

	// Actually uploads the files. In this case,

	// uploadAll() is used for automated queueing and upload 

	// of all files on the list.

	// You can manage the queue on your own and use "upload" instead,

	// if you need to modify the properties of the request for each

	// individual file.

	function pic_uploader_do_yui_upload() {

	

		var media_upload_queue_pic = $('#media_queue_pictures').val();
		//alert(media_upload_queue_pic);

		var to_table_id1 = $('#id').val();

		//pic_uploader.setSimUploadLimit(1);

		pic_uploader.setSimUploadLimit(parseInt(1)); 

		pic_uploader.uploadAll("<?php print site_url('admin/media/mediaUploadPictures') ?>/to_table:table_content/queue_id:"+media_upload_queue_pic, "POST", null, "Filedata");

		//alert('done'); 

	

	}

	

	// Fired when the user selects files in the "Browse" dialog

	// and clicks "Ok".

	function pic_uploader_onFileSelect(event) {
		if('fileList' in event && event.fileList != null) {
			fileList = event.fileList;
			pics_createDataTable(fileList);
			pic_uploader_do_yui_upload();
			//pic_uploader.disable();
		} else {
		
		}

	}

 

	function pics_createDataTable(entries) {

	  rowCounter = 0;

	  this.fileIdHash = {};

	  this.pic_dataArr = [];

	  for(var i in entries) {

	     var entry = entries[i];

		 //entry["progress"] = "<div style='height:5px;width:100px;background-color:#CCC;'></div>";

		

	     pic_dataArr.unshift(entry);

	  }

	

	  for (var j = 0; j < pic_dataArr.length; j++) {

	   //	new_progress_bar = "<div id='pictures_upload_progressbar_"+j+"'>Uploading: "+pic_dataArr[j].name+"</div>";

		// $('#pictures_upload_progressbars').append(new_progress_bar);

	   // this.fileIdHash[pic_dataArr[j].id] = j;

	  }

	/*

	    var myColumnDefs = [

	        {key:"name", label: "File Name", sortable:false},

	     	{key:"size", label: "Size", sortable:false},

	     	{key:"progress", label: "Upload progress", sortable:false}

	    ];

 

	  this.myDataSource = new YAHOO.util.DataSource(pic_dataArr);

	  this.myDataSource.responseType = YAHOO.util.DataSource.TYPE_JSARRAY;

      this.myDataSource.responseSchema = {

          fields: ["id","name","created","modified","type", "size", "progress"]

      };

 

	  this.singleSelectDataTable = new YAHOO.widget.DataTable("pic_dataTableContainer",

	           myColumnDefs, this.myDataSource, {

	               caption:"Files To Upload",

	               selectionMode:"single"

	           });*/

	}

 

    // Do something on each file's upload start.

	function pic_uploader_onUploadStart(event) {

		pic_uploader.disable();

		

		 $('#pictures_upload_progressbars').html('Uploading... Please wait...');

		 $('#pictures_upload_progressbars').fadeIn();

	}

	

	// Do something on each file's upload progress event.

	function pic_uploader_onUploadProgress(event) {

		rowNum = fileIdHash[event["id"]];

		prog = Math.round(100*(event["bytesLoaded"]/event["bytesTotal"]));

		

		$('#pictures_upload_progressbars').html('Uploading... '+prog+ "%");

		

		

		//progbar = "<div style='height:5px;width:100px;background-color:#CCC;'><div style='height:5px;background-color:#F00;width:" + prog + "px;'></div></div>";

		//singleSelectDataTable.updateRow(rowNum, {name: pic_dataArr[rowNum]["name"], size: pic_dataArr[rowNum]["size"], progress: progbar});	

		//contentMediaPicturesRefreshList();

	}

	

	// Do something when each file's upload is complete.

	function pic_uploader_onUploadComplete(event) { 

		rowNum = fileIdHash[event["id"]];

		prog = Math.round(100*(event["bytesLoaded"]/event["bytesTotal"]));

		//$('#pictures_upload_progressbar').progressbar('option', 'value', prog);

		//$('#pictures_upload_progressbar_'+rowNum).html(rowNum);

		//$('#pictures_upload_progressbar1').append(pic_dataArr[rowNum]["id"] + '____' + rowNum + '<br>');

		

		progbar = "<div style='height:5px;width:100px;background-color:#CCC;'><div style='height:5px;background-color:#F00;width:100px;'></div></div>";

		//singleSelectDataTable.updateRow(rowNum, {name: pic_dataArr[rowNum]["name"], size: pic_dataArr[rowNum]["size"], progress: progbar});

		//singleSelectDataTable.deleteRow(rowNum);

		 $('#pictures_upload_progressbars').fadeOut();

		contentMediaPicturesRefreshList();

		//pic_uploader.clearFileList();

		pic_uploader.enable();

	}

	

	// Do something if a file upload throws an error.

	// (When uploadAll() is used, the Uploader will

	// attempt to continue uploading.

	function pic_uploader_onUploadError(event) {

 //contentMediaPicturesRefreshList();

 	pic_uploader.enable();

	}

	

	// Do something if an upload is cancelled.

	function pic_uploader_onUploadCancel(event) {

 //contentMediaPicturesRefreshList();

 	pic_uploader.enable();

	}

	

	// Do something when data is received back from the server.

	function pic_uploader_onUploadResponse(event) {

 //contentMediaPicturesRefreshList();

	}

</script>
      <br />
      <br />
       
      <div id="pictures_upload_progressbars" style="width:100%; display:block;"></div>
      <br />
      <br />
      <br />
      <br /></td>
  </tr>
  <tr>
    <td><div id="media_pictures_placeholder">Loading gallery module...</div></td>
  </tr>
</table>
