 <script type="text/javascript">
	$(document).ready(function(){
		$("#more_images").click(function(){
			var up_length = $(".input_Up").length;
            if(up_length<=50){
    			var first_up = $("#more_f input:first");
    			$("#more_f").append("<br><br><input class='input_Up' name='picture_' type='file'>");
    			$("#more_f input:last").attr("name", "picture_" + up_length);
            }
		});
	});
</script>
  <label><strong>Upload Pictures</strong></label>
  <a href="javascript:void(0);" id="more_images" class="right">Add more pictures</a>
  <div id="more_f" style="padding-bottom:10px">
    <input class="input_Up" name="picture_0" type="file">
  </div>