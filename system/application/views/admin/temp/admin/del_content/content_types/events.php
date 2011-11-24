<?php require_once (ADMINVIEWSPATH.'content/content_blocks/default_start.php')  ?>












<h1 class="block" style="padding:7px 0">
  <?php if( $className == 'content' and $the_action == 'posts_add')  : ?>
  Add new post
  <?php endif; ?>
  <?php if( $className == 'content' and $the_action == 'posts_edit')  : ?>
  Edit: <?php print character_limiter($form_values['content_title'], 300, ' ') ; ?>
  <?php endif; ?>
</h1>
<div id="tabs">
  <ul class="tabs">
    <li  class="ui-state-active"><a href="#tab3">Content</a></li>
    <li><a href="#tab_comments">Comments</a></li>
  </ul>
  <div id="tab3">
    <fieldset>
    <h2>Content</h2>
    <?php require_once (ADMINVIEWSPATH.'content/content_blocks/categories_selector.php')  ?>
    <br />

     <?php require_once (ADMINVIEWSPATH.'content/content_blocks/title_and_url_selector.php')  ?>
    <br />
    
    <script type="text/javascript">
 $(document).ready(function() {

$("#original_link").change( function() {
  original_link_toggle_link_properties();
});

original_link_toggle_link_properties();

});
 
 
 
 
function original_link_toggle_link_properties(){
$the_val = $("#original_link").val();
	if($the_val != ''){
	$("#original_link_no_follow_row").fadeIn();
			$("#original_link_include_in_advanced_search_row").fadeIn();
			$("#originallinkhiddenModalContent_controller").fadeIn();
	}
}
</script>
    <table border="0" cellspacing="3" cellpadding="5" class="admin_inner_table_settings">
       
      <tr  valign="top">
        <td>Is featured:</td>
        <td><select name="is_featured">
            <option  <?php if($form_values['is_featured'] == 'n' ): ?> selected="selected" <?php endif; ?>  value="n">no</option>
            <option  <?php if($form_values['is_featured'] == 'y' ): ?> selected="selected" <?php endif; ?>  value="y">yes</option>
          </select></td>
        <td>&nbsp;</td>
      </tr>
      <tr valign="top">
        <td>Original link:</td>
        <td><input name="original_link"  id="original_link" type="text" value="<?php print $form_values['original_link']; ?>" />
          <?php if($form_values['is_from_rss'] == 'y' ) : ?>
          <img src="<?php print_the_static_files_url() ; ?>/icons/feed__arrow.png" alt=" " border="0">
          <?php endif; ?>
          <a href="#TB_inline?height=150&width=400&inlineId=originallinkhiddenModalContent&modal=false" id="originallinkhiddenModalContent_controller" style="display:none"   class="thickbox"><img src="<?php print_the_static_files_url() ; ?>/icons/link_edit.png" alt=" " border="0"></a>
          <div id="originallinkhiddenModalContent" style="display:none">
            <table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td>Original link no_follow:</td>
                <td><select name="original_link_no_follow">
                    <option  <?php if($form_values['original_link_no_follow'] == 'y' ): ?> selected="selected" <?php endif; ?>  value="y">yes</option>
                    <option  <?php if($form_values['original_link_no_follow'] == 'n' ): ?> selected="selected" <?php endif; ?>  value="n">no</option>
                  </select></td>
              </tr>
              <tr>
                <td>Original link include in advanced search:</td>
                <td><select name="original_link_include_in_advanced_search">
                    <option  <?php if($form_values['original_link_include_in_advanced_search'] == 'y' ): ?> selected="selected" <?php endif; ?>  value="y">yes</option>
                    <option  <?php if($form_values['original_link_include_in_advanced_search'] == 'n' ): ?> selected="selected" <?php endif; ?>  value="n">no</option>
                  </select></td>
              </tr>
            </table>
          </div></td>
        <td>&nbsp;</td>
      </tr>
        <tr>
        <td><script type="text/javascript">
	
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
          Tags: </td>
        <td><a href="#TB_inline?height=500&width=505&inlineId=tagCloudhiddenModalContent&modal=false" id="tagCloudhiddenModalContent_controller"    class="thickbox"><img src="<?php print_the_static_files_url() ; ?>/icons/tag_blue_edit.png" alt=" " border="0">Edit tags</a><br />
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
                <?php $letters = $this->content_model->taxonomyTagsGetExisingTagsFirstLetters(); ?>
                <?php foreach($letters  as $letter_item): ?>
                <li><a href="#alphabetic_tags_tabs_<?php print md5($letter_item) ?>"><?php print $letter_item ?></a></li>
                <?php endforeach;  ?>
              </ul>
              <div id="alphabetic_tags_tabs_all">
                <?php $this->content_model->taxonomy_helpers_generateTagCloud("javascript:tags_append_csv('{taxonomy_value}')");  ?>
              </div>
              <?php foreach($letters  as $letter_item): ?>
              <div id="alphabetic_tags_tabs_<?php print md5($letter_item) ?>">
                <?php $this->content_model->taxonomy_helpers_generateTagCloud("javascript:tags_append_csv('{taxonomy_value}')", false,$letter_item );  ?>
              </div>
              <?php endforeach;  ?>
            </div>
            <table width="90%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><input type="button" value="Guess tags" onclick="contentTagsGenerate()" /></td>
                <td><input type="button" value="Close" onclick="tb_remove()" /></td>
              </tr>
            </table>
          </div></td>
        <td></td>
      </tr>
      <tr>
        <td>Other options: <a href="#TB_inline?height=500&width=505&inlineId=metatagshiddenModalContent&modal=false" class="thickbox">Meta Tags</a>, <a href="#TB_inline?height=500&width=505&inlineId=otheroptionshiddenModalContent&modal=false" class="thickbox">Other</a>, <a href="#TB_inline?height=500&width=605&inlineId=galleryhiddenModalContent&modal=false" class="thickbox">Gallery</a> </td>
        <td><div id="metatagshiddenModalContent" style="display:none; width:400px; height:500; overflow:scroll">
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
            <br />
            <br />
            <label>content_meta_title:
            <input name="content_meta_title"  id="content_meta_title" type="text" value="<?php print $form_values['content_meta_title']; ?>" style="width:500px;"  >
            <input type="button" value="Autofill" onclick="metaTagsGenerate('content_meta_title')" />
            </label>
            <br />
            <br />
            <label>content_meta_description:
            <textarea name="content_meta_description" id="content_meta_description" style="width:500px; height:100px;"  ><?php print $form_values['content_meta_description']; ?></textarea>
            <input type="button" value="Autofill" onclick="metaTagsGenerate('content_meta_description')" />
            </label>
            <br />
            <br />
            <label>content_meta_keywords:
            <textarea name="content_meta_keywords"  id="content_meta_keywords"  style="width:500px;  height:100px;"   ><?php print $form_values['content_meta_keywords']; ?></textarea>
            <input type="button" value="Autofill" onclick="metaTagsGenerate('content_meta_keywords')" />
            </label>
            <br />
            <br />
            <label>content_meta_other_code:
            <textarea name="content_meta_other_code"   style="width:500px;"    ><?php print $form_values['content_meta_other_code']; ?></textarea>
            </label>
          </div>
          <div id="otheroptionshiddenModalContent" style="display:none; width:400px; height:500; overflow:scroll"> <br />
            <br />
            <table width="350" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td> content filename:
                  <input name="content_filename" type="text" value="<?php print $form_values['content_filename']; ?>">
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
          </div></td>
        <td>&nbsp;</td>
      </tr>
    </table>
    <br />
    <br />
    <br />
    <br />
    <script type="text/javascript">
	 $(document).ready(function(){
		$("#datepicker_custom_field_event_start").datepicker({ 
		dateFormat: 'yy-mm-dd',
		<?php if(strval($form_values['custom_fields']['event_start']) != ''): ?>
		defaultDate: '<?php print  $form_values['custom_fields']['event_start']; ?>' ,
		<?php endif; ?>
		altField: '#custom_field_event_start' ,
			onSelect: function(dateText, inst) {
			$check_for_the_past = $("#custom_field_event_end").val();
			//alert(dateText);
			if($check_for_the_past < dateText){
			//	$("#datepicker_custom_field_event_end").datepicker('option', 'minDate', dateText);
				//$("#datepicker_custom_field_event_end").datepicker('option', 'defaultDate', dateText);
				
			}
			
			}
		 });
	});
	 $(document).ready(function(){
		$("#datepicker_custom_field_event_end").datepicker({ 
		dateFormat: 'yy-mm-dd',
		<?php if(strval($form_values['custom_fields']['event_end']) != ''): ?>
		defaultDate: '<?php print  $form_values['custom_fields']['event_end']; ?>' ,
		
		
		<?php endif; ?>
		
		//set up min date
		<?php if(strval($form_values['custom_fields']['event_start']) != ''): ?>
		minDate: '<?php print  $form_values['custom_fields']['event_start']; ?>' ,
		<?php endif; ?>
		
		

		altField: '#custom_field_event_end' 
		 });
	});
	</script>
    <input name="custom_field_event_start" id="custom_field_event_start" type="text" value="<?php print  $form_values['custom_fields']['event_start']; ?>" />
    <div id="datepicker_custom_field_event_start"></div>
    <input name="custom_field_event_end" id="custom_field_event_end" type="text" value="<?php print  $form_values['custom_fields']['event_end']; ?>" />
    <div id="datepicker_custom_field_event_end"></div>
    Description:<br />
    <table width="100%" border="0" cellspacing="0" cellpadding="3">
      <tr>
        <td><textarea name="content_description"  rows="10" cols="100"><?php print $form_values['content_description']; ?></textarea></td>
      </tr>
    </table>
    <br />
    <br />
    </fieldset>
    <textarea name="content_body" id="content_body" rows="100" cols=""><?php print $form_values['content_body']; ?></textarea>
    <div id="galleryhiddenModalContent" style="display:none; width:600px; height:500; overflow:scroll">
      <h2>Pictures</h2>
      <script type="text/javascript">
	
 $(document).ready(function(){

		$("#sortable_pics").sortable(
								
				{
			update : function () {
				//alert(1);
				
			var order = $('#sortable_pics').sortable('serialize');
			//alert(order);
			$.post("<?php print site_url('admin/core/reorderMedia') ?>", order,
			  function(data){
				//alert("Data Loaded: " + data);
			  });
			
}
		}				
			
								)

 
 });
 
function deletePicture($id){


var answer = confirm("Are you sure?")
	if (answer){
		$.post("<?php print site_url('admin/core/mediaDelete') ?>", { id: $id, time: "2pm" },
  function(data){
	  $("#positions_"+$id).remove();
   //alert("Data Loaded: " + data);
  });
	}
	else{
		//alert("Thanks for sticking around!")
	}
 
}
   
	</script>
      <?php $pics = ($form_values['media']['pictures']); ?>
      <?php if(!empty($pics)) : ?>
      <ul id="sortable_pics">
        <?php $i = 1;
  foreach($pics as $pic): ?>
        <li id="positions_<?php print $pic['id'] ?>"> <img src="<?php print $pic['urls']['128'];  ?>" id="pictire_id_<?php print $pic['id'] ?>"   alt=" " /><br />
          <img src="<?php print_the_static_files_url() ; ?>admin/icons/silk/cross.png" onClick="deletePicture('<?php print $pic['id'] ?>')"  border="0" alt=" " /> </li>
        <?php $i++; endforeach; ?>
      </ul>
      <?php endif; ?>
      <script type="text/javascript">
	$(document).ready(function(){
		$("#more_images").click(function(){
			var up_length = $(".input_Up").length;
			var first_up = $("#more_f input:first");
			$("#more_f").append("<br><br>&nbsp;&nbsp;&nbsp;<input class='input_Up' name='picture_' type='file'>");
			$("#more_f input:last").attr("name", "picture_" + up_length)
		});	
	});
</script>
      <a style="font:bold 12px Arial;color#456" href="javascript:;" id="more_images"><img src="<?php print_the_static_files_url() ; ?>admin/icons/silk/picture_add.png"  border="0" alt=" " />Add Pictures</a>
      <div id="more_f" style="padding-bottom:10px"> &nbsp;&nbsp;&nbsp;
        <input class="input_Up" name="picture_" type="file">
      </div>
    </div>
  </div>
  <!--/tab3-->
  <div id="tab_comments">
    <?php $comments = $temp= $this->content_model->commentsGetForContentId( $form_values['id']); ?>
    <?php include (ADMINVIEWSPATH.'comments/comments_list.php') ?>
  </div>
</div>
<br />
<?php require_once (ADMINVIEWSPATH.'content/content_blocks/default_end.php')  ?>
