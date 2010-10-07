<script type="text/javascript">
function check_all_categories(){
			alert('test');
			/*$("#paradigm_all").click(function()
			{
				var checked_status = this.checked;
				$("input[@name=paradigm]").each(function()
				{
					this.checked = checked_status;
				});
			});			*/
}
function uncheck_all_categories(){
			alert('test');
			/*$("#paradigm_all").click(function()				
			{
				var checked_status = this.checked;
				$("input[@name=paradigm]").each(function()
				{
					this.checked = checked_status;
				});
			});			*/
}
	

$(document).ready(function() {


<?php $actve_ids = $content_selected_categories; ?>
		<?php if(!empty($actve_ids )) :?>
<?php foreach($actve_ids as $item) : ?>
//d.openTo(<?php print $item ; ?>, true);

<?php endforeach; ?>
<?php endif; ?>




 do_the_content_search_assign()
 do_the_content_batch_edit_assign()
test = $("#subheader");
	var tax = $("input[name='taxonomy_categories[]']");
	tax.click(function(){
		do_the_content_search_assign();
		})


	var tags = $("input[name='taxonomy_tags[]']");
	tags.click(function(){
		do_the_content_search_assign();
		})


	
	var content_item_batch = $("input[name='content_item_batch[]']");
	content_item_batch.click(function(){
		do_the_content_batch_edit_assign();
		})
	
	
})

function do_the_content_batch_edit_assign(){

/*



*/

var tax_parent = $("#the_content_items_container");
		var find_c = tax_parent.find(":checked");
			var cc = find_c.serializeArray();
			//alert(cc)
			// $("#the_content_search_form").empty();
			var temp = new Array();
			 jQuery.each(cc, function(i, field){
       		 //$("#the_content_search_form").append(field.value + " ");
			 temp[i] = field.value;
      		});
			temp = temp.join(',') 
			$("#the_batch_edit_items").val(temp);





$test = $("#the_batch_edit_items").val();
if($test == ''){
$("#the_batch_edit_button").hide();
} else {
$("#the_batch_edit_button").show();
}




}
	   
	   
	   
	   
	   

function do_the_content_search_assign(){
		var tax_parent = $("#categories_container");
		var find_c = tax_parent.find(":checked");
			var cc = find_c.serializeArray();
			//alert(cc)
			// $("#the_content_search_form").empty();
			var temp = new Array();
			 jQuery.each(cc, function(i, field){
       		 //$("#the_content_search_form").append(field.value + " ");
			 temp[i] = field.value;
      		});
			temp = temp.join(',') 
			$("#the_content_search_form_categories").val(temp);
			//
			
			
			
			var tax_parent = $("#tags_container");
		var find_c = tax_parent.find(":checked");
			var cc = find_c.serializeArray();
			//alert(cc)
			// $("#the_content_search_form").empty();
			var temp = new Array();
			 jQuery.each(cc, function(i, field){
       		 //$("#the_content_search_form").append(field.value + " ");
			 temp[i] = field.value;
      		});
			temp = temp.join(',') 
			$("#the_content_search_form_tags").val(temp);
			//
	
			
	
}	   
	   
function do_the_content_search(){
	$("#the_content_search_form").submit();
	
}




</script>
<script type="text/javascript">
	function tags_append_csv_to_search($tag){
		$the_val = $("#search_by_tags").val();

 $("#search_by_tags").val($the_val+ ", "+ $tag);

 
	}
</script>

<div id="breadcrumb_area">
  <div id="breadcrumb_wrap" style="border:0;">
    <div class="title_left_bar">Categories tree</div> 
    <div class="title_content">
      <div class="show_on_page"> <span>Show on page</span>
        <ul>
          <li class="n10"><a href="javascript:;"></a></li>
          <li class="n15"><a href="javascript:;"></a></li>
          <li class="n25"><a href="javascript:;"></a></li>
        </ul>
      </div>
      Content </div>
  </div>
  <!-- /breadcrumb_wrap -->
</div>
<!-- /breadcrumb_area -->
<div style="clear: both;height:1px;overflow: hidden">
  <!--  -->
</div>
<form method="post" action="<?php print site_url('admin/content/posts_manage_do_search');  ?>" id="the_content_search_form">
  <input type="hidden" value="" name="categories" id="the_content_search_form_categories" />
  <input type="hidden" value="" name="tags" id="the_content_search_form_tags"  />
</form>
<table cellpadding="0" cellspacing="0" id="asdbtable">
  <tr>
    <td width="280px" style="padding-left: 74px"><div class="bar">
        <!--<h3 style="float: left">Categories</h3>-->
        <div class="c_control"> <a href="javascript:;" class="btn" onclick="tree.showall('#c31')">Open All</a> <a href="javascript:;" class="btn" onclick="tree.hideall('#c31')">Close All</a> </div>
        <?php // var_dump($active_categories); ?>
        <?php $link = site_url('admin/content/posts_manage/categories:'). '{id}';
	 $link = "<a {active_code} href='$link' name='{id}'>{taxonomy_value}</a>";
	 $tree_params = array();
	 $tree_params['content_parent'] = 0;
	 $tree_params['link'] = $link;
	 $tree_params['actve_ids'] = $content_selected_categories;
	 $tree_params['active_code'] = " class='active'  ";
	 $tree_params_string = $this->core_model->securityEncryptArray($tree_params);
	 ?>
        <div class="ooyes_ul_tree_container" id="c31" style="float: left;clear: both" treeparams='<?php print $tree_params_string;  ?>'></div>
        <?php $tree_params_string = false;  ?>
        <!--[if IE 6]>
     <style>
     .treeparam_object{
        height: 1000px;
        float:left;
     }
     </style>  <![endif]-->
        <div style="clear: both;height:19px;overflow: hidden">
          <!--  -->
        </div>
        

<img src="<?php print_the_static_files_url() ; ?>icons/mouse.png"  border="0" alt="Right click for actions" align="left" /> Right click on the categories tree to add content and edit categories.
<br /><br />
        
        <a href="javascript:edit_category_dialog(0,0)" class="oject_2w">Create new category</a>
        <div style="clear: both;height:15px;overflow: hidden">
          <!--  -->
        </div>
        <a href="#TB_inline?height=500&width=400&inlineId=create_new_content_choose_category_conpatiner&modal=false" class="bgb thickbox<?php if( $className == 'content' and $the_action == 'posts_add')  : ?> active<?php endif; ?>"> Create new content </a> </div></td>
    <td><div id="side-content-wrapper">
        <div id="side-content">
          <div style="height: 15px;overflow: hidden;clear: both">&nbsp;</div>
          <form class="" id="searchengine" action="<?php print site_url('admin/content/posts_manage_do_search_by_keyword/'); ?>" method="post">
            <label class="lbl search_label">Search<span></span></label>
            <input name="keyword" type="text" style="width:407px;" value="<?php print $search_by_keyword ?>" />
            <div class="d"></div>
            <table cellpadding="2">
            
            
            
            
            
            <tr>
                <td><label class="ico"><img src="<?php print_the_static_files_url() ; ?>/icons/connect.png" alt=" " border="0">Type?</label>
                  <select name="content_subtype">
                    <option value=""  <?php if($content_subtype == '') : ?> selected="selected" <?php endif; ?> >Any</option>
                    <option value="trainings" <?php if($content_subtype == 'trainings') : ?> selected="selected" <?php endif; ?> >trainings</option>
                    <option value="products" <?php if($content_subtype == 'products') : ?> selected="selected" <?php endif; ?>>products</option>
                      <option value="services" <?php if($content_subtype == 'services') : ?> selected="selected" <?php endif; ?>>services</option>
                  </select></td>
                <td> </td>
              </tr>
            
            
            
            
            
            
            
            
              <tr>
                <td><label class="ico"><img src="<?php print_the_static_files_url() ; ?>/icons/star.png" alt=" " border="0">In Featured?</label>
                  <select name="is_featured">
                    <option value=""  <?php if($search_by_is_is_featured == '') : ?> selected="selected" <?php endif; ?> >Any</option>
                    <option value="y" <?php if($search_by_is_is_featured == 'y') : ?> selected="selected" <?php endif; ?> >Yes</option>
                    <option value="n" <?php if($search_by_is_is_featured == 'n') : ?> selected="selected" <?php endif; ?>>No</option>
                  </select></td>
                <td><label class="ico"><img src="<?php print_the_static_files_url() ; ?>/icons/comment.png" alt=" " border="0">Commented </label>
                  <select name="with_comments">
                    <!--<option value=""  <?php if($search_by_with_comments == '') : ?> selected="selected" <?php endif; ?> >Any</option>
                <option value="y" <?php if($search_by_with_comments == 'y') : ?> selected="selected" <?php endif; ?> >Yes</option>
                <option value="n" <?php if($search_by_with_comments == 'n') : ?> selected="selected" <?php endif; ?>>No</option>-->
                    
                    <option value=""  <?php if($search_by_with_comments == false) : ?> selected="selected" <?php endif; ?> >Any</option>
                    <?php $number = 365*2;
for ($x = 1; $x <= $number; $x++) : ?>
                    <option value="<?php print $x ?> days"  <?php if($search_by_with_comments ==  "$x days") : ?> selected="selected" <?php endif; ?> ><?php print $x ?> days</option>
                    <?php endfor; ?>
                  </select></td>
              </tr>
              <tr>
                <td><label class="ico"><img src="<?php print_the_static_files_url() ; ?>/icons/thumb_up.png" alt=" " border="0">Only voted</label>
                  <select name="voted">
                    <option value=""  <?php if($selected_voted == false) : ?> selected="selected" <?php endif; ?> >No</option>
                    <?php $number = 365*2;
for ($x = 1; $x <= $number; $x++) : ?>
                    <option value="<?php print $x ?> days"  <?php if($selected_voted ==  "$x days") : ?> selected="selected" <?php endif; ?> ><?php print $x ?> days</option>
                    <?php endfor; ?>
                  </select></td>
                <td><label class="ico">Results per page</label>
                  <select name="items_per_page">
                    <?php $number = 100*1;
for ($x = 1; $x <= $number; $x++) : ?>
                    <option value="<?php print $x ?>"  <?php if($search_items_per_page ==  $x) : ?> selected="selected" <?php endif; ?> ><?php print $x ?></option>
                    <?php endfor; ?>
                  </select></td>
              </tr>
            </table>
            
            <!-- <div class="d"></div>
              <label class="ico"><img src="<?php print_the_static_files_url() ; ?>/icons/tag_green.png" alt=" " border="0">Tags:</label>
              <input name="tags" id="search_by_tags" type="text" style="width:80%;" value="<?php print $content_selected_tags ?>" />-->
            <div class="d"></div>
            <div class="d"></div>
    
            
            <!-- /end hide of unused elements --> 
            <!--<input name="search_all_categories" type="submit" value="Search in all categories" /> --> 
            <a href="javascript:;" onclick="$('#searchengine').submit()" class="btn" style="position: static">Search </a>
            <div style="height: 10px;overflow: hidden;clear: both">&nbsp;</div>
            <?php if(!empty($content_selected_categories)): ?>
            <!-- <input name="search_this_category" type="submit" value="Search in this category" />-->
            <?php endif; ?>
            <input name="do_keyword_search" type="hidden" value="1" />
            <?php if(!empty($content_selected_categories)): ?>
            <input name="categories" type="hidden" value="<?php print implode(',',$content_selected_categories) ;  ?>" />
            <?php endif; ?>
          </form>
          <div id="the_batch_edit_form_container" style="height:500px; width:400px; overflow:scroll; display:none">
            <script type="text/javascript">
$(document).ready(function() {
     $('#the_batch_edit_tabs').tabs({ });
	 

})



$(document).ready(function() { 
    var options = { 
        //target:        '#output1',   // target element(s) to be updated with server response 
       url : '<?php print site_url('admin/content/posts_batch_edit')  ?>',
	   clearForm: false,
	   resetForm: false ,
	   beforeSubmit:  posts_batch_edit_form_showRequest,  // pre-submit callback
        success:       posts_batch_edit_form_showResponse  // post-submit callback

        // other available options: 
        //url:       url         // override for form's 'action' attribute 
        //type:      type        // 'get' or 'post', override for form's 'method' attribute 
        //dataType:  null        // 'xml', 'script', or 'json' (expected server response type) 
        //clearForm: true        // clear all form fields after successful submit
        //resetForm: true        // reset the form after successful submit 
 
        // $.ajax options can be used here too, for example: 
        //timeout:   3000 
    }; 
 
    // bind form using 'ajaxForm' 
    $('#posts_batch_edit_form').ajaxForm(options); 
}); 
 
// pre-submit callback 
function posts_batch_edit_form_showRequest(formData, jqForm, options) { 
    // formData is an array; here we use $.param to convert it to a string to display it 
    // but the form plugin does this for you automatically when it submits the data 
    var queryString = $.param(formData); 
 	/*$test = $("#the_batch_edit_items").val();
	$test = $test.split(',');
	jQuery.each($test, function() {
	//alert(this);
      $("#content_row_id_"+this).fadeOut();
    });*/
    // jqForm is a jQuery object encapsulating the form element.  To access the 
    // DOM element for the form do this: 
    // var formElement = jqForm[0]; 
 
    //alert('About to submit: \n\n' + queryString); 
 
    // here we could return false to prevent the form from being submitted; 
    // returning anything other than false will allow the form submit to continue 
    return true; 
} 
 
// post-submit callback 
function posts_batch_edit_form_showResponse(responseText, statusText)  { 
    
	$test = responseText;
	//alert(responseText);
	$test = $test.split(',');
	jQuery.each($test, function() {
	//alert(this);
      $("#content_row_id_"+this).fadeOut();
	  $("#content_row_id_"+this).remove();
    });
	 $("#the_batch_edit_items").val('');
	tb_remove();
	

	

	
	
	// for normal html responses, the first argument to the success callback 
    // is the XMLHttpRequest object's responseText property 
 
    // if the ajaxForm method was passed an Options Object with the dataType 
    // property set to 'xml' then the first argument to the success callback 
    // is the XMLHttpRequest object's responseXML property 
 
    // if the ajaxForm method was passed an Options Object with the dataType 
    // property set to 'json' then the first argument to the success callback 
    // is the json data object returned by the server 
 
   //alert('status: ' + statusText + '\n\nresponseText: \n' + responseText + '\n\nThe output div should have already been updated with the responseText.'); 
} 
















function batch_delete_all_items(){

}

 function opt_nav(){
        var tree_height = $(".ooyes_ul_tree_container:first").find("ul:first").height() + 50;
    $(".ooyes_ul_tree_container:first").css("height", tree_height)

 }
 setInterval("opt_nav()", 100);

</script>
            <form id="posts_batch_edit_form" method="post" >
              <input type="submit" name="Save" value="Save"/>
              <div id="the_batch_edit_tabs" style="display:none">
                <ul>
                  <li><a href="#tabs-1">Nunc tincidunt</a></li>
                  <li><a href="#tabs-2">Proin dolor</a></li>
                  <li><a href="#tabs-3">Aenean lacinia</a></li>
                </ul>
                <div id="tabs-1">
                  <p>Tab 1 content</p>
                </div>
                <div id="tabs-2">
                  <p>Tab 2 content</p>
                </div>
                <div id="tabs-3">
                  <p>Tab 3 content</p>
                </div>
              </div>
              <h2>Move to section</h2>
              <?php
	$this->firecms = get_instance();
	$sections = $this->firecms->content_model->getBlogSections();
	?>
              <?php foreach($sections as $section) : ?>
              <label>
              <input name="content_parent" type="radio" value="<?php print $section['id'] ?>"      />
              <input type="hidden" name="content_subtype_value_<?php print $section['id'] ?>"  id="content_subtype_value_<?php print $section['id'] ?>"  value="<?php print $section['content_subtype_value'] ?>" />
              <span id="content_url_<?php print $section['id'] ?>"><?php print site_url($section['content_url']); ?></span> </label>
              <br />
              <?php endforeach;  ?>
              <br />
              <h2>Categories</h2>
              <div class="ullist"  id="categories1">
                <?php $this->firecms = get_instance();
	// $active_categories = $this->firecms->content_model->taxonomyGetTaxonomyIdsForContentId( $form_values['id'] , 'categories');
	// var_dump($active_categories );
	  $actve_ids = $active_categories;
	 $active_code = ' checked="checked"  ';
	//function content_helpers_getCaregoriesUlTree($content_parent, $link = false, $actve_ids = false, $active_code = false, $remove_ids = false, $removed_ids_code = false) {

 $this->firecms = get_instance();
 $this->firecms->content_model->content_helpers_getCaregoriesUlTree(0, "<label><input name='taxonomy_categories[]' type='checkbox'  {active_code}   id='category_selector_{id}' value='{id}' />{taxonomy_value}</label>", $actve_ids , $active_code , $remove_ids = false, $removed_ids_code = false);  ?>
              </div>
              <input type="submit" name="Save1" value="Save"/>
              Edit Items:
              <textarea name="the_batch_edit_items" cols="" rows="" id="the_batch_edit_items"></textarea>
            </form>
          </div>
          <script type="text/javascript">
$(document).ready(function(){
     $("#sortedtable").tablesorter()
});
</script>
          
          <a class="btn thickbox" href="#TB_inline?height=500&width=400&inlineId=the_batch_edit_form_container&modal=false" id="the_batch_edit_button"><img src="<?php print_the_static_files_url() ; ?>/icons/page_white_lightning.png" alt=" " border="0">Batch edit</a>
          <!--
                  <a class="ovalbutton<?php if( $className == 'content' and $the_action == 'posts_add')  : ?> active<?php endif; ?>" href="<?php print site_url('admin/content/posts_edit/id:0')  ?>"><span><img src="<?php print_the_static_files_url() ; ?>/icons/document__plus.png" alt=" " border="0">Create new content</span></a> -->
          <div style="height: 5px;overflow: hidden"></div>
          <div style="height: 5px;overflow: hidden"></div>
          <!--</td>-->
          <?php //$this->firecms = get_instance();	 ?>
          <script type="text/javascript">
	   //sortable table
  $(document).ready(function(){
      //

	$('#the_content_items_container').sortable({
					opacity: '0.5',
					update: function(e, ui){
						serial = $(this).sortable("serialize");
						$.ajax({
							url: "<?php print site_url('admin/content/posts_sort_by_date')  ?>",
							type: "POST",
							data: serial,
							// complete: function(){},
							success: function(feedback){
							//alert(feedback);
								//$('#data').html(feedback);
							}
							// error: function(){}
						});
					}
				});

	

  });
  </script>
          <?php if(!empty($form_values)) :   ?>
          <div id="sotableThead">
            <table cellpadding="0" cellspacing="0">
              <tr>
                <td width="150px">Sort posts by:</td>
                <td class="sort" id="sort-by-title" onclick="$('#tableTitle').click();">Title</td>
                <td class="sort" id="sort-by-last-added" onclick="$('#tableCreated').click()">Last added</td>
                <td class="sort" id="sort-by-number-of-comments" onclick="$('#tableComments').click()">Comments</td>
              </tr>
            </table>
          </div>
          <div style="height:10px;overflow: hidden"></div>
          <table class="tablesorter tables" id="sortedtable" cellspacing="0" cellpadding="0">
            <thead style="display: none">
              <tr>
                <th id="tableId" scope="col">ID</th>
                <th id="tableGo" scope="col">Go</th>
                <th id="tableUrl" scope="col">Url</th>
                <th id="tableTitle" scope="col">Title</th>
                <th id="tableActive" scope="col">Active?</th>
                <th id="tableComments" scope="col">Comments</th>
                <th id="tableVotes" scope="col">Votes</th>
                <th id="tableCreated" scope="col">Created</th>
                <th id="tableUpdated" scope="col">Updated</th>
                <th id="tableEdit" scope="col">Edit</th>
                <th id="tableDelete" scope="col">Delete</th>
              </tr>
            </thead>
            <tbody id="the_content_items_container">
              <?php foreach($form_values as $item):  ?>
              <tr id="content_row_id_<?php print $item['id']; ?>">
                <!-- ID -->
                <td style="display: none"><?php print $item['id']; ?><a href="<?php print $this->content_model->contentGetHrefForPostId($item['id']) ; ?>" target="_blank" ><img src="<?php print_the_static_files_url() ; ?>admin/icons/silk/page_go.png"  border="0" alt=" " /></a>
                <br />
                <?php print $item['id']; ?>
                
                
                  <input type="checkbox" value="<?php print $item['id'] ?>" name="content_item_batch[]" />
                </td>
                <!-- Image -->
                <td style="display: none"><div class="cont_image" style="background-image: url('<?php print   $thumb = $this->content_model->contentGetThumbnailForContentId($item['id'], 110); ?>')"></div>

                </td>
                <td style="display: none"><?php //print $the_url.'/'.$item['content_url'] ?>
                  <?php print $this->content_model->contentGetHrefForPostId($item['id']) ; ?></td>
                <td style="display: none"><?php print ($item['content_title']) ?></td>
                <td style="display: none"><?php print ($item['is_active']) ?></td>
                <td style="display: none"><?php $temp= $this->content_model->commentsGetForContentId( $item['id']); print (count(  $temp )); ?>
                </td>
                <td style="display: none"><?php if($selected_voted == false) : ?>
                  <?php $temp = 7 ?>
                  <?php else: ?>
                  <?php $temp = $selected_voted ?>
                  <?php endif; ?>
                  <?php print $this->content_model->contentGetVotesCountForContentId($item['id'], $temp); ?> </td>
                <td style="display: none"><?php print ($item['created_on']) ?></td>
                <td style="display: none"><?php print ($item['updated_on']) ?></td>
                <td style="display: none"><a target="_blank" href="<?php print site_url('admin/content/posts_edit/id:'.$item['id'])  ?>">Edit post</a></td>
                <td style="display: none"></td>
                <td class="visibleCell"><div class="cont_image" style="background-image: url('<?php print   $thumb = $this->content_model->contentGetThumbnailForContentId($item['id'], 150); ?>')"><a class="visit_post_link" href="<?php print $this->content_model->contentGetHrefForPostId($item['id']) ; ?>" target="_blank" >Visit</a></div>
            

                  <div class="visibleCell_right"> <span class="visibleCell_right_comments">Comments
                    <?php $temp= $this->content_model->commentsGetForContentId( $item['id']); print (count(  $temp )); ?>
                    </span> <a href="<?php print site_url('admin/content/posts_edit/id:'.$item['id'])  ?>" class="btnedit">Edit post</a>
                    <div style="height: 0px;clear: both;overflow: hidden;">
                      <!--  -->
                    </div>
                    <a class="deleteanchor"  href="javascript:deleteContentItem(<?php print $item['id']; ?>, 'content_row_id_<?php print $item['id']; ?>')">Delete</a> </div>
                  <div class="visibleCell_middle">
                    <h3><?php print ($item['content_title']) ?></h3>
                    <span class="date">Created on:<?php print ($item['created_on']) ?>; Updated on:<?php print ($item['updated_on']) ?></span>
                    <div>
                      <?php if($item['content_description'] != ''): ?>
                      <?php print character_limiter($item['content_description'], 250, '...'); ?>
                      <?php else: ?>
                      <?php print character_limiter($item['content_body_nohtml'], 250, '...'); ?>
                      <?php endif; ?>
                    </div>
                  </div></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <?php if($content_pages_count > 1) :   ?>
          <div align="center" id="admin_content_paging">
            <?php for ($i = 1; $i <= $content_pages_count; $i++) : ?>
            <a href="<?php print  $content_pages_links[$i]  ?>" <?php if($content_pages_curent_page == $i) :   ?> class="active" <?php endif; ?> ><?php print $i ?></a>
            <?php endfor; ?>
          </div>
          <?php endif; ?>
          <?php else : ?>
          No posts found!
          <?php endif; ?>
        </div>
        <!-- /side-content -->
      </div>
      <!-- /side-content-wrapper -->
    </td>
  </tr>
</table>
