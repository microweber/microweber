
  <script type="text/javascript">
	
function contentTagsGenerate(){	

var some_data = false;
	some_data = $("#content_body").val();
	some_data = some_data + $("#content_title").val();
	//some_data = some_data + $("#taxonomy_tags_csv").val();
$.post("<?php print site_url('admin/content/contentGenerateTagsForPost') ?>", {  data: some_data },
  function(data){
    $("#taxonomy_tags_csv").val('');
    $("#taxonomy_tags_csv").val(data);
  });
}



</script>
          <?php //var_dump( $form_values["taxonomy_data"]['tag']) 
	if(!empty($form_values["taxonomy_data"]['tag'])){
	foreach($form_values["taxonomy_data"]['tag'] as $temp){
		$thetags[] = $temp['taxonomy_value'];
		
	}
		$thetags = implode(', ',$thetags);
	} else {
	$thetags = false;	
	}
	
	//var_dump($thetags);
	?>
          <script type="text/javascript">
	function tags_append_csv($tag){
		$the_val = $("#taxonomy_tags_csv").val();
		
 $("#taxonomy_tags_csv").val($the_val+ ", "+ $tag);
  
 
	}
</script>
  <label class="lbl left" style="width: 40px">Tags: </label>
  <a href="#TB_inline?height=500&width=505&inlineId=tagCloudhiddenModalContent&modal=false" id="tagCloudhiddenModalContent_controller"    class="thickbox"><img src="<?php print_the_static_files_url() ; ?>/icons/tag_blue_edit.png" alt=" " border="0">Edit tags</a><br />
  <div class="c" style="height: 10px"></div>
  <textarea name="taxonomy_tags_csv" id="taxonomy_tags_csv" wrap="virtual"  style="width:400px; padding:0px; height:60px; overflow:scroll;" cols="10" rows="10"><?php print $thetags; ?></textarea>
          <div id="tagCloudhiddenModalContent" style="display:none">
            <script type="text/javascript">
  $(document).ready(function(){
    $("#alphabetic_tags_tabs").tabs();
  });
  </script>
            <div id="alphabetic_tags_tabs">
              <ul>
                <li><a href="#alphabetic_tags_tabs_all">All</a></li>
                <?php $letters = $this->taxonomy_model->taxonomyTagsGetExisingTagsFirstLetters(); ?>
                <?php foreach($letters  as $letter_item): ?>
                <li><a href="#alphabetic_tags_tabs_<?php print md5($letter_item) ?>"><?php print $letter_item ?></a></li>
                <?php endforeach;  ?>
              </ul>
              <div id="alphabetic_tags_tabs_all">
                <?php $this->taxonomy_model->generateTagCloud("javascript:tags_append_csv('{taxonomy_value}')");  ?>
              </div>
              <?php foreach($letters  as $letter_item): ?>
              <div id="alphabetic_tags_tabs_<?php print md5($letter_item) ?>">
                <?php $this->taxonomy_model->generateTagCloud("javascript:tags_append_csv('{taxonomy_value}')", false,$letter_item );  ?>
              </div>
              <?php endforeach;  ?>
            </div>
            <table width="90%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><input type="button" value="Guess tags" onclick="contentTagsGenerate()" /></td>
                <td><input type="button" value="Close" onclick="tb_remove()" /></td>
              </tr>
            </table>
          </div>