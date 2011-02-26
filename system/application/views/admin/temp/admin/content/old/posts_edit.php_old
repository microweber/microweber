<? // var_dump($form_values['media']); ?>
<!--<script type="text/javascript" src="<?php print_the_static_files_url() ; ?>jquery/map_on_jquery.js"></script>-->
<script type="text/javascript">
$(document).ready(function() {
/*content_url_holder_assign(<? print $form_values['content_parent']; ?>)
$("input[name='content_parent']").change(
		function()
			{
			  $id = $(this).val();
			  content_url_holder_assign($id);
			}
	);*/
});
function content_url_holder_assign($id){
	 $val = $("#content_url_" + $id ).text();
	 $val =  $val + '/';
	 $("#content_url_holder").val($val);
	 

}
</script>
<? //var_dump($form_values); ?>

<form action="<? print site_url($this->uri->uri_string()); ?>" class="pull" method="post" enctype="multipart/form-data">
  <!--<div class="oh" style="padding-bottom:5px">
    <input type="submit" style="width:0px;height:0px;border:none; visibility:hidden">
    <input name="Save" value="Save" type="submit">
    &nbsp; </div>-->
  <input name="id" type="hidden" value="<?php print $form_values['id']; ?>">
  <h1 class="block" style="padding:7px 0">
  
  <? //var_dump( $form_values['custom_fields']['event_start']); ?> 
  
    <?php if( $className == 'content' and $the_action == 'posts_add')  : ?>
    Add new post
    <? endif; ?>
    <?php if( $className == 'content' and $the_action == 'posts_edit')  : ?>
    Edit: <?php print character_limiter($form_values['content_title'], 300, ' ') ; ?>
    <? endif; ?>
  </h1>
  <div id="tabs">
    <ul class="tabs">
      <li  class="ui-state-active"><a href="#tab3">Съдържание</a></li>
      <li style="display:none"><a href="#tab_map">Google Map</a></li>
      <li><a href="#tab_comments">Comments</a></li>
      <li><a href="#tab_custom">Custom</a></li>
    </ul>
    <div id="tab3">
      <fieldset>
      <h2>Content</h2>
      <br />
      <?php  
	$this->firecms = get_instance();  
	$sections = $this->firecms->content_model->getBlogSections();  
	?>
      <? foreach($sections as $section) : ?>
      <input type="hidden" name="content_subtype_value_<? print $section['id'] ?>"  id="content_subtype_value_<? print $section['id'] ?>"  value="<? print $section['content_subtype_value'] ?>" />
      <? endforeach;  ?>
      <script type="text/javascript">
 $(document).ready(function() {

$("#original_link").keyup( function() {
  original_link_toggle_link_properties();
});

original_link_toggle_link_properties();

});
 
 
 
 
	function original_link_toggle_link_properties(){
		$the_val = $("#original_link").val();
		
		
		
		$.post("<? print site_url('main/ajax_helpers_is_valid_url') ?>", {  data_to_validate: $the_val },
  function(data){
   // alert(data);
   if(data == 'yes'){
 //  $("#original_link_no_follow_row").fadeIn();
//   $("#original_link_include_in_advanced_search_row").fadeIn();
    $("#originallinkhiddenModalContent_controller").fadeIn();
   
   } else {
   //$("#original_link_no_follow_row").fadeOut();
  // $("#original_link_include_in_advanced_search_row").fadeOut();
   $("#originallinkhiddenModalContent_controller").fadeOut();
   
   
   }
  });
		

		
	}
</script>
      <table border="0" cellspacing="3" cellpadding="5" class="admin_inner_table_settings">
        <tr  valign="top">
          <td><script type="text/javascript">
	 $(document).ready(function() {
select_blog_category_for_section_id()
});
	
	
	function select_blog_category_for_section_id(){
	
	
	var mySections=new Array();
	<? foreach($sections as $section) : ?>
mySections[<? print $section['id']  ?>]="<? print $section['content_subtype_value']  ?>";
<? endforeach;  ?>
	
	
	
	var the_section_id = $("#content_parent_items").val();
	//alert("#category_selector_"+mySections[the_section_id]);
	 $("#category_selector_"+mySections[the_section_id]).attr("checked","checked");
	do_the_taxonomy_categories_show_selected_values()
	}
	
	</script>
            Адрес на страницата:
            <? foreach($sections as $section) : ?>
            <? // var_Dump($section); ?>
            <? endforeach;  ?>
            <select name="content_parent" id="content_parent_items" onchange="select_blog_category_for_section_id()" >
              <option value="">-- Choose --</option>
              <? foreach($sections as $section) : ?>
              <option onchange="select_blog_category_for_section_id()" value="<? print $section['id'] ?>"  <? if($form_values['content_parent'] == $section['id']) : ?>   selected="selected"  <? endif; ?>><? print site_url($section['content_url']); ?></option>
              <? endforeach;  ?>
            </select>
            /</td>
          <td><input name="content_url" type="text" value="<?php print $form_values['content_url']; ?>"   style="width:200px; padding:0px;"   ></td>
          <td>&nbsp;</td>
        </tr>
        <tr  valign="top">
          <td>Title:</td> 
          <td><input name="content_title"  id="content_title" type="text" value="<?php print $form_values['content_title']; ?>"   style="width:200px;" /></td>
          <td>&nbsp;</td>
        </tr>
        <tr  valign="top">
          <td>В представени:</td>
          <td><select name="is_featured">
              <option  <? if($form_values['is_featured'] == 'n' ): ?> selected="selected" <? endif; ?>  value="n">no</option>
              <option  <? if($form_values['is_featured'] == 'y' ): ?> selected="selected" <? endif; ?>  value="y">yes</option>
            </select></td>
          <td>&nbsp;</td>
        </tr>
        <tr valign="top">
          <td>Original link:</td>
          <td><input name="original_link"  id="original_link" type="text" value="<?php print $form_values['original_link']; ?>" />
            <? if($form_values['is_from_rss'] == 'y' ) : ?>
            <img src="<?php print_the_static_files_url() ; ?>/icons/feed__arrow.png" alt=" " border="0">
            <? endif; ?>
            <a href="#TB_inline?height=150&width=400&inlineId=originallinkhiddenModalContent&modal=false" id="originallinkhiddenModalContent_controller" style="display:none"   class="thickbox"><img src="<?php print_the_static_files_url() ; ?>/icons/link_edit.png" alt=" " border="0"></a>
            <div id="originallinkhiddenModalContent" style="display:none">
              <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td>Original link no_follow:</td>
                  <td><select name="original_link_no_follow">
                      <option  <? if($form_values['original_link_no_follow'] == 'y' ): ?> selected="selected" <? endif; ?>  value="y">yes</option>
                      <option  <? if($form_values['original_link_no_follow'] == 'n' ): ?> selected="selected" <? endif; ?>  value="n">no</option>
                    </select></td>
                </tr>
                <tr>
                  <td>Original link include in advanced search:</td>
                  <td><select name="original_link_include_in_advanced_search">
                      <option  <? if($form_values['original_link_include_in_advanced_search'] == 'y' ): ?> selected="selected" <? endif; ?>  value="y">yes</option>
                      <option  <? if($form_values['original_link_include_in_advanced_search'] == 'n' ): ?> selected="selected" <? endif; ?>  value="n">no</option>
                    </select></td>
                </tr>
              </table>
            </div></td>
          <td>&nbsp;</td>
        </tr>
        <tr valign="top">
          <td>Categories: </td>
          <td><script type="text/javascript">
 $(document).ready(function() {
do_the_taxonomy_categories_show_selected_values()
});



function do_the_taxonomy_categories_show_selected_values(){
		var tax_parent = $(".edit_post_the_categories_tree");
		var find_c = tax_parent.find(":checked");
			var cc = find_c.serializeArray();
			var temp = new Array();
			 jQuery.each(cc, function(i, field){
			 temp[i] = field.value;
			 // var titles123123 = $(this).attr("title");
			// temp[i] = titles123123;
      		});
			temp = temp.join(',') 
			
			
			$.post("<? print site_url('main/ajax_helpers_taxonomy_get_taxonomy_values_for_taxonomy_serialized_id_and_return_string') ?>", {  data_to_work: temp },
  function(data){
   $("#the_taxonomy_categories_show_selected_values").html(data);
  });
			//
			
			
			

}	  
</script>
            <a href="#TB_inline?height=500&width=400&inlineId=thetaxonomycategorieshiddenModalContent&modal=false"  class="thickbox"><img src="<?php print_the_static_files_url() ; ?>/icons/text_padding_top.png" alt=" " border="0">Edit categories</a><br />
            <div id="the_taxonomy_categories_show_selected_values" style="width:400px; display:block;"></div>
            <div id="thetaxonomycategorieshiddenModalContent" style="display:none; width:400px; height:500; overflow:scroll">
              <table width="400%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><div class="ullist"  id="thetaxonomycategorieshiddenModalContent_categories1">
                      <? 
	 $this->firecms = get_instance();
	 $active_categories = $this->firecms->content_model->taxonomyGetTaxonomyIdsForContentId( $form_values['id'] , 'categories');
	// var_dump($active_categories );
	  $actve_ids = $active_categories;
	 $active_code = ' checked="checked"  ';
	//content_helpers_getCaregoriesUlTree($content_parent, $link = false, $actve_ids = false, $active_code = false, $remove_ids = false, $removed_ids_code = false, $ul_class_name = false, $include_first = false)

 $this->content_model->content_helpers_getCaregoriesUlTree(0, "<label><input name='taxonomy_categories[]' type='checkbox' onchange='do_the_taxonomy_categories_show_selected_values()'  {active_code}  title='{taxonomy_value}'  id='category_selector_{id}' value='{id}' />{taxonomy_value}</label>", $actve_ids , $active_code , $remove_ids = false, $removed_ids_code = false, $ul_class_name='edit_post_the_categories_tree' );  ?>
                    </div></td>
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
$.post("<? print site_url('admin/content/contentGenerateTagsForPost') ?>", {  data: some_data },
  function(data){
    $("#taxonomy_tags_csv").val('');
    $("#taxonomy_tags_csv").val(data);
  });
}



</script>
            <? //var_dump( $form_values["taxonomy_data"]['tag']) 
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
            <textarea name="taxonomy_tags_csv" id="taxonomy_tags_csv" wrap="virtual"  style="width:400px; padding:0px; height:60px; overflow:scroll;" cols="10" rows="10"><? print $thetags; ?></textarea>
            <div id="tagCloudhiddenModalContent" style="display:none">
              <script type="text/javascript">
  $(document).ready(function(){
    $("#alphabetic_tags_tabs").tabs();
  });
  </script>
              <div id="alphabetic_tags_tabs">
                <ul>
                  <li><a href="#alphabetic_tags_tabs_all">All</a></li>
                  <? $letters = $this->content_model->taxonomyTagsGetExisingTagsFirstLetters(); ?>
                  <? foreach($letters  as $letter_item): ?>
                  <li><a href="#alphabetic_tags_tabs_<? print md5($letter_item) ?>"><? print $letter_item ?></a></li>
                  <? endforeach;  ?>
                </ul>
                <div id="alphabetic_tags_tabs_all">
                  <? $this->content_model->taxonomy_helpers_generateTagCloud("javascript:tags_append_csv('{taxonomy_value}')");  ?>
                </div>
                <? foreach($letters  as $letter_item): ?>
                <div id="alphabetic_tags_tabs_<? print md5($letter_item) ?>">
                  <? $this->content_model->taxonomy_helpers_generateTagCloud("javascript:tags_append_csv('{taxonomy_value}')", false,$letter_item );  ?>
                </div>
                <? endforeach;  ?>
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


$.post("<? print site_url('admin/content/contentGenerateMeta') ?>", { generate_what: $what, data: some_data },
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
			$.post("<? print site_url('admin/core/reorderMedia') ?>", order,
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
		$.post("<? print site_url('admin/core/mediaDelete') ?>", { id: $id, time: "2pm" },
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
        <? if(!empty($pics)) : ?>
        <ul id="sortable_pics">
          <? 
  $i = 1;
  foreach($pics as $pic): ?>
          <li id="positions_<? print $pic['id'] ?>"> <img src="<?  print $pic['urls']['128'];  ?>" id="pictire_id_<? print $pic['id'] ?>"   alt=" " /><br />
            <img src="<?php print_the_static_files_url() ; ?>admin/icons/silk/cross.png" onClick="deletePicture('<? print $pic['id'] ?>')"  border="0" alt=" " /> </li>
          <? $i++; endforeach; ?>
        </ul>
        <? endif; ?>
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
    <div id="tab_map">
      <? include_once ('geodata_map.php') ; ?>
    </div>
    <div id="tab_custom">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <? for ($i = 1; $i <= 10; $i++) : ?>
        <tr>
          <td>custom_field_<? print $i ?>:</td>
          <td><textarea name="custom_field_<? print $i ?>"  rows="5" cols="50"><?php print $form_values['custom_field_'. $i]; ?></textarea></td>
        </tr>
        <?   endfor ; ?>
      </table>
    </div>
    <div id="tab_comments">
      <? $comments = $temp= $this->content_model->commentsGetForContentId( $form_values['id']); ?>
      <? include (ADMINVIEWSPATH.'comments/comments_list.php') ?>
    </div>
  </div>
  <br />
  <script type="text/javascript">
function deleteThisPost(){


var answer = confirm("Are you sure?")
	if (answer){
	//""
	window.location = '<? print site_url('admin/content/posts_delete') ?>/id:<? print $form_values['id']; ?>'
	}
	else{

	}
 
}
   
</script>
  <? if($form_values['id'] != 0) : ?>
  <a href="javascript:deleteThisPost()">delete this post</a>
  <? endif; ?>
  <div class="clear"></div>
  <br />
  <br />
</form>
