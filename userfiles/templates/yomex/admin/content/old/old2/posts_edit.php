<!--<script type="text/javascript" src="<?php print_the_static_files_url() ; ?>jquery/map_on_jquery.js"></script>-->
<script type="text/javascript">
$(document).ready(function() {
content_url_holder_assign(<?php print $form_values['content_parent']; ?>)	
$("input[name='content_parent']").change(
		function()
			{
			  $id = $(this).val();
			  content_url_holder_assign($id);
			}
	);
});
function content_url_holder_assign($id){
	 $val = $("#content_url_" + $id ).text();
	 $val =  $val + '/';
	  $("#content_url_holder").val($val);
	  
	/*  if($id != 0 && (typeof $id != "undefined") ){
	  
	   $val1 = $("#content_subtype_value_" + $id ).val();
	   if (typeof $val1 != "undefined"){ 
	   //alert( $val);
		//$("input[@type='checkbox']").check();
		$("#category_selector_" + $val1 ).check();
		//$("#category_selector_" +  $val ).checked = true;
		
	   }
	  }*/

	 
	  
}
</script>

<form action="<?php print $this->uri->uri_string(); ?>" class="pull" method="post" enctype="multipart/form-data">
  <div class="oh" style="padding-bottom:5px">
    <input type="submit" style="width:0px;height:0px;border:none; visibility:hidden">
    <input name="Save" value="Save" type="submit">
    &nbsp; </div>
  <input name="id" type="hidden" value="<?php print $form_values['id']; ?>">
  <h1 class="block" style="padding:7px 0"><?php if( $className == 'content' and $the_action == 'posts_add')  : ?>Add new post<?php endif; ?><?php if( $className == 'content' and $the_action == 'posts_edit')  : ?>Edit: <?php print character_limiter($form_values['content_title'], 300, ' ') ; ?><?php endif; ?></h1>
  <div id="tabs">
    <ul class="tabs">
    <li  class="ui-state-active"><a href="#tab3">Съдържание</a></li>
      <li><a href="#tab1">Основни настойки</a></li>
      <li><a href="#tab2">Категории</a></li>
      <li><a href="#tab_tags">Тагове</a></li>
      
      <li><a href="#tab4">Meta Tags</a></li>
      <li><a href="#tab_map">Google Map</a></li>
      <li><a href="#tab_comments">Comments</a></li>
    </ul>
    <div id="tab1">
      <fieldset>
       <legend>Основни настройки</legend>
        <h2>Секция</h2>
		<?php  
	$this->firecms = get_instance();  
	$sections = $this->firecms->content_model->getBlogSections();  
	?>
        <?php foreach($sections as $section) : ?>
        <?php // var_dump($section); ?>
        <label>
          <input name="content_parent" type="radio" value="<?php print $section['id'] ?>"  <?php if($form_values['content_parent'] == $section['id']) : ?>   checked="checked"  <?php endif; ?>  />
          <input type="hidden" name="content_subtype_value_<?php print $section['id'] ?>"  id="content_subtype_value_<?php print $section['id'] ?>"  value="<?php print $section['content_subtype_value'] ?>" />
          <span id="content_url_<?php print $section['id'] ?>"><?php print site_url($section['content_url']); ?></span></label>
        <br />
        <?php endforeach;  ?>
      
      <p>
      
        <label>Адрес на страницата:
          <input name="content_url_holder" id="content_url_holder" type="text"  size="30" style="width:300px; text-align:right;"  disabled="disabled" value="content_url_holder">
          <input name="content_url" type="text" value="<?php print $form_values['content_url']; ?>"   style="width:400px;"   >
        </label>
        </p>   
<p>

      
</p>         
        
        <br />
<br />
<br />
<br />
<br />
<br />
<label>content_filename:
          <input name="content_filename" type="text" value="<?php print $form_values['content_filename']; ?>">
        </label>
        <br />
        
      </fieldset>
      
    </div>
     
     
     
    <div id="tab2">
      <fieldset>
        <h2>Categories</h2>
        <div class="ullist"  id="categories1">
          <?php $this->firecms = get_instance();
	 $active_categories = $this->taxonomy_model->getTaxonomiesForContent( $form_values['id'] , 'categories');
	// var_dump($active_categories );
	  $actve_ids = $active_categories;
	 $active_code = ' checked="checked"  ';
	//function content_helpers_getCaregoriesUlTree($content_parent, $link = false, $actve_ids = false, $active_code = false, $remove_ids = false, $removed_ids_code = false) {

 $this->firecms = get_instance();
 $this->firecms->content_model->content_helpers_getCaregoriesUlTree(0, "<label><input name='taxonomy_categories[]' type='checkbox'  {active_code}   id='category_selector_{id}' value='{id}' />{taxonomy_value}</label>", $actve_ids , $active_code , $remove_ids = false, $removed_ids_code = false);  ?>
        </div>
      </fieldset>
      
      
      
    </div>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    <div id="tab_tags">

      
      
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
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
        <h2>Tags</h2>
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
        <textarea name="taxonomy_tags_csv" id="taxonomy_tags_csv" wrap="virtual" cols="20" rows="10"><?php print $thetags; ?></textarea>
       
        <?php asort($avalable_tags);
	?>
        <table border="0" cellspacing="5" cellpadding="5"> 
          <tr>
            <th scope="col">Avaiable tags </th>
          </tr>
          <tr>
            <td><?php /// foreach($avalable_tags as $tag): ?>
              <!--<a href="javascript:tags_append_csv('<?php print $tag; ?>')"><?php print $tag; ?></a>,-->
              <?php // endforeach; ?>
              
              
              <?php $this->taxonomy_model->generateTagCloud("javascript:tags_append_csv('{taxonomy_value}')");  ?>
              </td>
          </tr>
          <tr>
            <td align="right"><input type="button" value="Guess tags" onclick="contentTagsGenerate()" /></td>
          </tr>
        </table>
        
        
        <br />
<br />
<br />
<br />

        

    </div>
   
   
   
   
   
   
   
   
   
   
   
    <div id="tab3">
      <fieldset>
        <h2>Content</h2>
        <br />
        
        <table border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td>Title:</td>
    <td><input name="content_title"  id="content_title" type="text" value="<?php print $form_values['content_title']; ?>"   style="width:600px;" /></td>
  </tr>
  
           <tr>
             <td>В представени:</td>
             <td> 
          <select name="is_featured">
            <option  <?php if($form_values['is_featured'] == 'n' ): ?> selected="selected" <?php endif; ?>  value="n">no</option>
            <option  <?php if($form_values['is_featured'] == 'y' ): ?> selected="selected" <?php endif; ?>  value="y">yes</option>
          </select>
      </td>
           </tr>
</table>

<br />
<br />



 <script type="text/javascript">
 $(document).ready(function() {

$("#original_link").keyup( function() {
  original_link_toggle_link_properties();
});

original_link_toggle_link_properties();

});
 
 
 
 
	function original_link_toggle_link_properties(){
		$the_val = $("#original_link").val();
		
		
		
		$.post("<?php print site_url('main/ajax_helpers_is_valid_url') ?>", {  data_to_validate: $the_val },
  function(data){
   // alert(data);
   if(data == 'yes'){
   $("#original_link_no_follow_row").fadeIn();
   $("#original_link_include_in_advanced_search_row").fadeIn();
   
   
   } else {
   $("#original_link_no_follow_row").fadeOut();
   $("#original_link_include_in_advanced_search_row").fadeOut();
   
   
   }
  });
		
		
		
		
		
		
		
		
	}
</script>



 <table border="0" cellspacing="3" cellpadding="3">
<!-- <tr>
    <td>From RSS:</td>
    <td> <input name="is_from_rss"  type="text" disabled="disabled" value="<?php print $form_values['is_from_rss']; ?>" /></td>
  </tr>-->
  <tr>
    <td>Original link:</td>
    <td><input name="original_link"  id="original_link" type="text" value="<?php print $form_values['original_link']; ?>" />
    <?php if($form_values['is_from_rss'] == 'y' ) : ?><img src="<?php print_the_static_files_url() ; ?>/icons/feed__arrow.png" alt=" " border="0"><?php endif; ?>
    
    </td>
  </tr>
   <tr id="original_link_no_follow_row" style="display:none;">
    <td>Original link no_follow:</td>
    <td><select name="original_link_no_follow">
          <option  <?php if($form_values['original_link_no_follow'] == 'y' ): ?> selected="selected" <?php endif; ?>  value="y">yes</option>
            <option  <?php if($form_values['original_link_no_follow'] == 'n' ): ?> selected="selected" <?php endif; ?>  value="n">no</option>
          </select></td> </tr>
          
           <tr id="original_link_include_in_advanced_search_row" style="display:none;">
    <td>Original link include in advanced search:</td>
    <td><select name="original_link_include_in_advanced_search">
          <option  <?php if($form_values['original_link_include_in_advanced_search'] == 'y' ): ?> selected="selected" <?php endif; ?>  value="y">yes</option>
            <option  <?php if($form_values['original_link_include_in_advanced_search'] == 'n' ): ?> selected="selected" <?php endif; ?>  value="n">no</option>
          </select></td> </tr>
           
</table>


 

      </fieldset>
      <textarea name="content_body" id="content_body" rows="100" cols=""><?php print $form_values['content_body']; ?></textarea>
      <fieldset style="display:none;" >
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
      </fieldset>
      
    </div>
    <!--/tab3-->
    <div id="tab4">
    
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

    
    
    
    
    
    
    
    
    
    
    
      <fieldset>
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
      </fieldset>
    </div>
    <div id="tab_map">
      <?php include_once ('geodata_map.php') ; ?>
    </div>
     <div id="tab_comments">
       <?php $comments = $temp= $this->comments_model->commentsGetForContentId( $form_values['id']); ?>
  <?php include (ADMINVIEWSPATH.'comments/comments_list.php') ?>
    </div>
  </div>
  <div class="clear"></div>
  <br />
</form>
