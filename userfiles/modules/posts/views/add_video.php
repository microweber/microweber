
<div class="video_link">
  <label>Paste the video url</label>
  <span>
      <input style="width: 95%;" name="custom_field_external_link"  id="ext_link" type="text" onchange="parse_embeds()" value="<? print $the_post['custom_fields']['external_link']; ?>"  />
  </span>
</div>


<div class="c">&nbsp;</div>

<div class="video_embed">
  <label>Paste the video embed code</label>
  <span>
      <textarea style="height: 53px;padding: 2px"  name="custom_field_embed_code" id="ext_link_embed_code"><? print $the_post['custom_fields']['embed_code']; ?></textarea>
  </span>
</div>



<input class="hidden"  name="screenshot_url" id="ext_link_screenshot_url"   type="text" value=""  />
<script type="text/javascript">
        $(document).ready(function() {
          	 if($("#ext_link_embed_code").val()!=''){
                  parse_embeds();
          	 }
        });
		function parse_embeds(){

		q = $("#ext_link").val();


        if(q.indexOf("<object")==-1 || q.indexOf("<embed")==-1 || q.indexOf("<iframe")==-1){


        if($.isEmbedly(q)){
    		$.embedly(q, { /*maxWidth: 600, wrapElement: false*/ }, function(oembed, dict){

    			if (oembed == null){
    				//$("#embed").html('<p class="text"> Not A Valid URL </p>');
                     $(".video_link").hide();
                     $(".video_embed").show();

    			}else {
    				$("#ext_link_embed_code").val(oembed.code);
    				$("#ext_link_screenshot_url").val(oembed.thumbnail_url);
                    //$(".video_preview").html("<img src='" + oembed.thumbnail_url + "' />");
                    $(".video_preview").html(oembed.code);
                    $("input[name='content_title']").val(oembed.title);
                    $("textarea[name='content_body']").val(oembed.description);
    			}
    		});
        }
        else{
             //$(".video_link").hide();
             //$(".video_embed").show();
             mw.box.alert("Video was not found on this address, please enter the embed code.")


        }

        }
         if(q.indexOf("<object")!=-1 || q.indexOf("<embed")!=-1 || q.indexOf("<iframe")!=-1){
           //alert(q.indexOf("<object"))
          //$(".video_preview").html(q)
        }



}
</script>
<div class="video_preview">
</div>
