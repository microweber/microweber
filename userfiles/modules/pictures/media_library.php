<?php
	only_admin_access();
?>
<div class="mw-module-admin-wrap">
  <div class="admin-side-content">
  <style>
  .mw-browser-list-png {
  	cursor:pointer;
  }
  </style>
<script>
function selectMediaImage(image) {
	mw.url.windowHashParam('select-file', image);
}

function searchMediaLibrary(search) {
	$.getJSON(mw.settings.api_url + "media_library/search?keyword=" + search, function(data) {

		$('#mw-media-library-results').find('.mw-browser-list').html('<?php echo _e('Searching'); ?>...');
		
		if (data.success) {
			 $.each(data.photos, function(key, val) {
	   			 $('#mw-media-library-results').find('.mw-browser-list').append('<li><a class="mw-browser-list-file mw-browser-list-png"><img class="image-item" onclick="selectMediaImage($(this).attr(\'\data-src\'\)); return false;" style="width:65px;height:65px;"  data-src="' + val.url_regular + '" src="' + val.url_thumb + '" /></a></li>');
	 		 });
		} else {
			$('#mw-media-library-results').find('.mw-browser-list').html(data.error);
		}
		
	});
}
searchMediaLibrary('');
</script>
<h3><b>Search for free stock photos</b></h3>
Type the keyword for looking photos.
<br />
<input onkeyup="mw.on.stopWriting(this,function(){searchMediaLibrary(this.value)})" class="mw-ui-field" style="width:100%;" type="text" placeholder="Search" />
<br /><br />
<div class="mw-ui-box mw-file-browser">

<div class="mw-ui-box-header">
<a class="mw-ui-btn mw-ui-btn-small pull-right mw-ui-btn-invert">
Back
</a>
<span class="mw-browser-uploader-path">
<a href="#path=" style="color: #212121;">
<span class="module-files-browser path-item">Main</span>
</a>»</span>
</div>

<div class="mw-ui-box-content" id="mw-media-library-results">
	<ul class="mw-browser-list">
	</ul>
</div>
</div>

</div>
</div>