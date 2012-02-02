<table width="600" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><script type="text/javascript">

	

	

	function  contentMediaEditVideo($id){



	if($("#content_form_object").hasClass("save_disabled")){

	alert("Error: You cannot delete while uploading!");

	return false;

	} else {

	

	}





}

	

function  contentMediaDeleteVideo($id){



if($("#content_form_object").hasClass("save_disabled")){

alert("Error: You cannot delete while uploading!");

return false;

}







var answer = confirm("Are you sure?")

	if (answer){

		$.post("<?php print site_url('admin/core/mediaDelete') ?>", { id: $id, time: "2pm" },

  function(data){

	  //$("#sortable_video_objects_positions_"+$id).fadeOut();

	  $("#video_module_sortable_vids_positions_"+$id).remove();

	// contentMediaVideosRefreshList();

   //alert("Data Loaded: " + data);

  });

	}

	else{

		//alert("Thanks for sticking around!")

	}

 

}







function contentMediaVideosRefreshList(){

var media_upload_queue_vid = $('#media_queue_videos').val();

var to_table_id1 = $('#id').val();



$.post("<?php print site_url('admin/media/contentMediaVideosList') ?>/to_table:table_content/queue_id:"+media_upload_queue_vid+"/to_table_id:"+to_table_id1+"/random_stuff:"+Math.random(), function(data){

  $("#media_videos_placeholder").html(data);



if ( $("#video_module_sortable_vids").exists()){

	$("#sortable_video_objects").sortable({

	update : function () {

	var order = $('#sortable_video_objects').sortable('serialize');

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

contentMediaVideosRefreshList();

var media_upload_queue = $('#media_queue_videos').val();



$("#videos_upload_progressbar").progressbar({

			value: 0

		});

 });

 

 

 

 

 

 

 

 

 

 

 

 

</script>
      <script type="text/javascript">



	function videosUploadUpdateProgressbar(){

	

	//$total = $('#videos_upload_progressbar_total_count').html();

	//$total = parseInt($total);

	

	//$currently_uploaded = $('#videos_upload_progressbar_currently_uploaded').html();

	//$currently_uploaded = parseInt($currently_uploaded);

		

		//if($currently_uploaded == $total){

			

		//} else {

			//if(($total > 0)){

			//	a = $currently_uploaded;

			//	b = $total;

			//	c = a/b;

			//	d = Math.round(c*100);

			//	d = 100 - d;

				//$('#videos_upload_progressbar').progressbar('option', 'value', d);

		//	}

		//}

	

	}

	</script>
      <div>
        <div id="uploaderUI" style="width:100px;height:40px;margin-left:5px;float:left"></div>
      </div>
      <div id="uiElements"> </div>
      <div id="dataTableContainer"> </div>
      <script type="text/javascript"> 

 

	// Custom URL for the uploader swf file (same folder).

	YAHOO.widget.Uploader.SWFURL = "<?php print_the_static_files_url() ; ?>yui/uploader.swf"; 

 

    // Instantiate the uploader and write it to its placeholder div.

	

 

	var uploader = new YAHOO.widget.Uploader( "uploaderUI", "<?php print_the_static_files_url() ; ?>yui/selectFileButton.png" );

	// Add event listeners to various events on the uploader.

	// Methods on the uploader should only be called once the 

	// contentReady event has fired.

	

	uploader.addListener('contentReady', handleContentReady);

	uploader.addListener('fileSelect', onFileSelect)

	uploader.addListener('uploadStart', onUploadStart);

	uploader.addListener('uploadProgress', onUploadProgress);

	uploader.addListener('uploadCancel', onUploadCancel);

	uploader.addListener('uploadComplete', onUploadComplete);

	uploader.addListener('uploadCompleteData', onUploadResponse);

	uploader.addListener('uploadError', onUploadError);

    uploader.addListener('rollOver', handleRollOver);

    uploader.addListener('rollOut', handleRollOut);

    uploader.addListener('click', handleClick);

    	

    // Variable for holding the filelist.

	var fileList;

	

	// When the mouse rolls over the uploader, this function

	// is called in response to the rollOver event.

	// It changes the appearance of the UI element below the Flash overlay.

	function handleRollOver () {

	//	YAHOO.util.Dom.setStyle(YAHOO.util.Dom.get('selectLink123'), 'color', "#FFFFFF");

		//YAHOO.util.Dom.setStyle(YAHOO.util.Dom.get('selectLink123'), 'background-color', "#000000");

	}

	

	// On rollOut event, this function is called, which changes the appearance of the

	// UI element below the Flash layer back to its original state.

	function handleRollOut () {

	//	YAHOO.util.Dom.setStyle(YAHOO.util.Dom.get('selectLink123'), 'color', "#0000CC");

	//	YAHOO.util.Dom.setStyle(YAHOO.util.Dom.get('selectLink123'), 'background-color', "#FFFFFF");

	}

	

	// When the Flash layer is clicked, the "Browse" dialog is invoked.

	// The click event handler allows you to do something else if you need to.

	function handleClick () {

	}

	

	// When contentReady event is fired, you can call methods on the uploader.

	function handleContentReady () {

	    // Allows the uploader to send log messages to trace, as well as to YAHOO.log

		uploader.setAllowLogging(false);

		

		// Allows multiple file selection in "Browse" dialog.

		uploader.setAllowMultipleFiles(true);

		

		// New set of file filters.

	/*	var ff = new Array({description:"Images", extensions:"*.jpg;*.png;*.gif"},

		                   {description:"Videos", extensions:"*.avi;*.mov;*.mpg"});*/

						//   var ff = new Array({description:"Images", extensions:"*.jpg;*.png;*.gif"});
						
						 var ff = new Array({description:"Videos", extensions:"*.avi;*.mov;*.mpg;*.flv;*.mpeg;*.f4v;"});
						
						

		                   

		// Apply new set of file filters to the uploader.

		uploader.setFileFilters(ff);

	}

 

	// Actually uploads the files. In this case,

	// uploadAll() is used for automated queueing and upload 

	// of all files on the list.

	// You can manage the queue on your own and use "upload" instead,

	// if you need to modify the properties of the request for each

	// individual file.

	function do_yui_upload() {

	

		var media_upload_queue_vid = $('#media_queue_videos').val();

		var to_table_id1 = $('#id').val();

		//uploader.setSimUploadLimit(1);

		uploader.setSimUploadLimit(parseInt(1)); 

		uploader.uploadAll("<?php print site_url('admin/media/mediaUploadVideos') ?>/to_table:table_content/queue_id:"+media_upload_queue_vid, "POST", null, "Filedata");

		//alert('done'); 

	

	}

	

	// Fired when the user selects files in the "Browse" dialog

	// and clicks "Ok".

	function onFileSelect(event) {

		if('fileList' in event && event.fileList != null) {

			fileList = event.fileList;

			createDataTable(fileList);

			do_yui_upload();

			

			//uploader.disable();

		}

	}

 

	function createDataTable(entries) {

	  rowCounter = 0;

	  this.fileIdHash = {};

	  this.dataArr = [];

	  for(var i in entries) {

	     var entry = entries[i];

		 //entry["progress"] = "<div style='height:5px;width:100px;background-color:#CCC;'></div>";

		

	     dataArr.unshift(entry);

	  }

	

	  for (var j = 0; j < dataArr.length; j++) {

	   //	new_progress_bar = "<div id='videos_upload_progressbar_"+j+"'>Uploading: "+dataArr[j].name+"</div>";

		// $('#videos_upload_progressbars').append(new_progress_bar);

	   // this.fileIdHash[dataArr[j].id] = j;

	  }

	/*

	    var myColumnDefs = [

	        {key:"name", label: "File Name", sortable:false},

	     	{key:"size", label: "Size", sortable:false},

	     	{key:"progress", label: "Upload progress", sortable:false}

	    ];

 

	  this.myDataSource = new YAHOO.util.DataSource(dataArr);

	  this.myDataSource.responseType = YAHOO.util.DataSource.TYPE_JSARRAY;

      this.myDataSource.responseSchema = {

          fields: ["id","name","created","modified","type", "size", "progress"]

      };

 

	  this.singleSelectDataTable = new YAHOO.widget.DataTable("dataTableContainer",

	           myColumnDefs, this.myDataSource, {

	               caption:"Files To Upload",

	               selectionMode:"single"

	           });*/

	}

 

    // Do something on each file's upload start.

	function onUploadStart(event) {

		uploader.disable();

		

		 $('#videos_upload_progressbars').html('Uploading... Please wait...');

		 $('#videos_upload_progressbars').fadeIn();

	}

	

	// Do something on each file's upload progress event.

	function onUploadProgress(event) {

		rowNum = fileIdHash[event["id"]];

		prog = Math.round(100*(event["bytesLoaded"]/event["bytesTotal"]));

		

		$('#videos_upload_progressbars').html('Uploading... '+prog+ "%");

		

		

		//progbar = "<div style='height:5px;width:100px;background-color:#CCC;'><div style='height:5px;background-color:#F00;width:" + prog + "px;'></div></div>";

		//singleSelectDataTable.updateRow(rowNum, {name: dataArr[rowNum]["name"], size: dataArr[rowNum]["size"], progress: progbar});	

		//contentMediaVideosRefreshList();

	}

	

	// Do something when each file's upload is complete.

	function onUploadComplete(event) { 

		rowNum = fileIdHash[event["id"]];

		prog = Math.round(100*(event["bytesLoaded"]/event["bytesTotal"]));

		//$('#videos_upload_progressbar').progressbar('option', 'value', prog);

		//$('#videos_upload_progressbar_'+rowNum).html(rowNum);

		//$('#videos_upload_progressbar1').append(dataArr[rowNum]["id"] + '____' + rowNum + '<br>');

		

		progbar = "<div style='height:5px;width:100px;background-color:#CCC;'><div style='height:5px;background-color:#F00;width:100px;'></div></div>";

		//singleSelectDataTable.updateRow(rowNum, {name: dataArr[rowNum]["name"], size: dataArr[rowNum]["size"], progress: progbar});

		//singleSelectDataTable.deleteRow(rowNum);

		 $('#videos_upload_progressbars').fadeOut();

		contentMediaVideosRefreshList();

		//uploader.clearFileList();

		uploader.enable();

	}

	

	// Do something if a file upload throws an error.

	// (When uploadAll() is used, the Uploader will

	// attempt to continue uploading.

	function onUploadError(event) {

 //contentMediaVideosRefreshList();

 	uploader.enable();

	}

	

	// Do something if an upload is cancelled.

	function onUploadCancel(event) {

 //contentMediaVideosRefreshList();

 	uploader.enable();

	}

	

	// Do something when data is received back from the server.

	function onUploadResponse(event) {

 //contentMediaVideosRefreshList();

	}

</script>
      <br />
      <br />
      <div id="videos_upload_progressbars" style="width:100%; display:block;"></div>
      <br />
      <br />
      <br />
      <br /></td>
  </tr>
  <tr>
    <td><div id="media_videos_placeholder">Loading videos module...</div></td>
  </tr>
</table>
