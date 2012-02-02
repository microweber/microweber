Other options:
<a href="#TB_inline?height=500&width=505&inlineId=metatagshiddenModalContent&modal=false" class="thickbox">Meta Tags</a>,
<a href="#TB_inline?height=500&width=505&inlineId=otheroptionshiddenModalContent&modal=false" class="thickbox">Other</a>,
<a href="#TB_inline?height=500&width=605&inlineId=galleryhiddenModalContent&modal=false" class="thickbox">Gallery</a>


            <script type="text/javascript">
function metaTagsGenerateAll(){
	metaTagsGenerate('content_meta_title');
	metaTagsGenerate('content_meta_description');
	metaTagsGenerate('content_meta_keywords');
	
}

function metaTagsGenerate($what){

var some_data = false;

if($what == 'content_meta_title'){
	some_data = $("#content_title").val();
}


if($what == 'content_meta_description'){
	some_data = $("#content_body").val();
}


if($what == 'content_meta_keywords'){
	some_data = $("#content_body").val();
	some_data = some_data + $("#content_title").val();
	some_data = some_data + $("#taxonomy_tags_csv").val();

}


$.post("<?php print site_url('admin/content/contentGenerateMeta') ?>", { generate_what: $what, data: some_data },
  function(data){
    $("#"+$what).val('');
    $("#"+$what).val(data);
  });
}



</script>
            <h2>Meta tags</h2>
            <input type="button" value="Autofill all tags" onclick="metaTagsGenerateAll()" />

            <label class="lbl">Content Meta Title:</label>

            <input name="content_meta_title"  id="content_meta_title" type="text" value="<?php print $form_values['content_meta_title']; ?>" style="width:600px;"  >
            <input type="button" value="Autofill" onclick="metaTagsGenerate('content_meta_title')" />


            <label class="lbl">Content Meta Description:</label>
            <textarea name="content_meta_description" id="content_meta_description" style="width:600px; height:300px;"  ><?php print $form_values['content_meta_description']; ?></textarea>
            <input type="button" value="Autofill" onclick="metaTagsGenerate('content_meta_description')" />

            <label class="lbl">Content Meta Keywords: </label>
            <textarea name="content_meta_keywords"  id="content_meta_keywords"  style="width:600px;  height:300px;"   ><?php print $form_values['content_meta_keywords']; ?></textarea>
            <input type="button" value="Autofill" onclick="metaTagsGenerate('content_meta_keywords')" />


            <label class="lbl">Content Meta other code:</label>
            <textarea name="content_meta_other_code"   style="width:600px;"    ><?php print $form_values['content_meta_other_code']; ?></textarea>


          <div id="otheroptionshiddenModalContent" style="display:none; width:400px; height:500; overflow:scroll"> <br />
            <br />
            <table width="350" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td> content filename:
                  <input name="content_filename" type="text" value="<?php print $form_values['content_filename']; ?>">
                  
                   <label class="lbl">content filename sync with editor?</label>
<input name="content_filename_sync_with_editor" type="radio" value="y" <?php if($form_values['content_filename_sync_with_editor'] != 'n') : ?> checked="checked" <?php endif; ?> />Yes<br />
<input name="content_filename_sync_with_editor" type="radio" value="n" <?php if($form_values['content_filename_sync_with_editor'] == 'n') : ?> checked="checked" <?php endif; ?> />No<br />
 <br />
                  
                  
                  
                </td>
              </tr>
              
              
              
              
              
              
              
              
              <tr>
                <td> page 301 redirect link:
                  <input name="page_301_redirect_link" type="text" value="<?php print $form_values['page_301_redirect_link']; ?>">
                </td>
              </tr>
              <tr>
                <td><label><span>301 redirect to content id:</span>
                  <input name="page_301_redirect_to_post_id" type="text" value="<?php print $form_values['page_301_redirect_to_post_id']; ?>">
                  </label>
                </td>
              </tr>
            </table>
          </div>