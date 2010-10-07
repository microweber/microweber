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

<form method="post" action="<?php print site_url('admin/content/posts_manage_do_search');  ?>" id="the_content_search_form" >
  <input type="hidden" value="" name="categories" id="the_content_search_form_categories" />
  <input type="hidden" value="" name="tags" id="the_content_search_form_tags"  />
</form>
<DIV id="sidebar">
  <form class="searchform" id="searchengine" action="<?php print site_url('admin/content/posts_manage_do_search_by_keyword/'); ?>" method="post">
    <INPUT id="livesearch" type="text" name="keyword" onblur="if (this.value == &#39;&#39;) {this.value = &#39;Search...&#39;;}" onfocus="if (this.value == &#39;Search...&#39;) {this.value = &#39;&#39;;}" value="<?php print $search_by_keyword ?>" class="searchfield ui-autocomplete-input" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
    <?php if(!empty($content_selected_categories)): ?>
    <!-- <input name="search_this_category" type="submit" value="Search in this category" />-->
    <?php endif; ?>
    <input name="do_keyword_search" type="hidden" value="1" />
    <?php if(!empty($content_selected_categories)): ?>
    <input name="categories" type="hidden" value="<?php print implode(',',$content_selected_categories) ;  ?>" />
    <?php endif; ?>
    <INPUT type="button" value="Go" onclick="$('#searchengine').submit()"  class="searchbutton">
  </form>
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
  <UL id="pagesnav">
    <LI><a href="#TB_inline?height=500&width=400&inlineId=create_new_content_choose_category_conpatiner&modal=false" class="bgb thickbox<?php if( $className == 'content' and $the_action == 'posts_add')  : ?> active<?php endif; ?>"> Create new content </a> </LI>
    <LI> <a href="javascript:edit_category_dialog(0,0)" class="oject_2w">Create new category</a></LI>
  </UL>
  <div class="ooyes_ul_tree_container" id="c31" style="float: left;clear: both" treeparams='<?php print $tree_params_string;  ?>'></div>
  <?php $tree_params_string = false;  ?>
  
  <!--   Start Search  
  <FORM class="searchform" action="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">
    <INPUT id="livesearch" type="text" onblur="if (this.value == &#39;&#39;) {this.value = &#39;Search...&#39;;}" onfocus="if (this.value == &#39;Search...&#39;) {this.value = &#39;&#39;;}" value="Search..." class="searchfield ui-autocomplete-input" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
    <INPUT type="button" value="Go" class="searchbutton">
  </FORM>
   End Search   
   Start Content Nav   
  <SPAN class="ul-header"><A id="toggle-pagesnav" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm" class="toggle visible">Content</A></SPAN>
  <UL id="pagesnav">
    <LI><A class="icn_manage_pages" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Manage Pages</A></LI>
    <LI><A class="icn_add_pages" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Add Pages</A></LI>
    <LI><A class="icn_edit_pages" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Edit Pages</A></LI>
    <LI><A class="icn_delete_pages" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Delete Pages</A></LI>
  </UL>
   End Content Nav   
   Start Comments Nav   
  <SPAN class="ul-header"><A id="toggle-commentsnav" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm" class="toggle visible">Comments</A></SPAN>
  <UL id="commentsnav">
    <LI><A class="icn_manage_comments" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Manage Comments</A></LI>
    <LI><A class="icn_add_comments" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Add Comments</A></LI>
    <LI><A class="icn_edit_comments" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Edit Comments</A></LI>
    <LI><A class="icn_delete_comments" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Delete Comments</A></LI>
  </UL>
   End Comments Nav   
   Start Users Nav   
  <SPAN class="ul-header"><A id="toggle-userssnav" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm" class="toggle visible">Users</A></SPAN>
  <UL id="userssnav">
    <LI><A class="icn_manage_users" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Manage Users</A></LI>
    <LI><A class="icn_add_users" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Add Users</A></LI>
    <LI><A class="icn_edit_users" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Edit Users</A></LI>
    <LI><A class="icn_delete_users" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Delete Users</A></LI>
  </UL>
   End Users Nav   
   Start Gallery Nav   
  <SPAN class="ul-header"><A id="toggle-imagesnav" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm" class="toggle visible">Gallery</A></SPAN>
  <UL id="imagesnav">
    <LI><A class="icn_manage_image" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Manage Images</A></LI>
    <LI><A class="icn_add_image" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Add Images</A></LI>
    <LI><A class="icn_edit_image" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Edit Images</A></LI>
    <LI><A class="icn_delete_image" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Delete Images</A></LI>
  </UL>
   End Gallery Nav   
   Start Statistics Area   
  <SPAN class="ul-header">Statistics</SPAN>
  <UL>
    <LI>Pages: 183</LI>
    <LI>Comments: 432</LI>
    <LI>Users: 1094</LI>
     End Statistics Area  
  </UL>--> 
</DIV>
<DIV id="page-content"> 
  <!-- Start Page Header -->
  <DIV id="page-header">
    <H1>Content</H1>
  </DIV>
  <!-- End Page Header --> 
  <!-- Start Grid -->
  <DIV class="container_12">
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

    }; 
 
    // bind form using 'ajaxForm' 
    $('#posts_batch_edit_form').ajaxForm(options); 
}); 
 
// pre-submit callback 
function posts_batch_edit_form_showRequest(formData, jqForm, options) { 
    var queryString = $.param(formData); 
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


 

 function opt_nav(){
        var tree_height = $(".ooyes_ul_tree_container:first").find("ul:first").height() + 50;
    $(".ooyes_ul_tree_container:first").css("height", tree_height)

 }
 setInterval("opt_nav()", 100);

</script> 
    </div>
    <script type="text/javascript">
$(document).ready(function(){
     $("#sortedtable").tablesorter()
});
</script> 
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
    <DIV class="box-header">Manage posts</DIV>
    <DIV class="box table">
      <table class="tablesorter tables" id="sortedtable" cellspacing="0" cellpadding="0">
        <thead>
          <tr>
            <th scope="col">ID</th>
            <th scope="col"> </th>
            <th scope="col">Title</th>
            <th id="tableComments" scope="col">Comments</th>
            <!--   <th id="tableVotes" scope="col">Votes</th>-->
            
            <th id="tableUpdated" scope="col">Updated</th>
            <th id="tableEdit" scope="col">Edit</th>
          </tr>
        </thead>
        <tbody id="the_content_items_container">
          <?php foreach($form_values as $item):  ?>
          <tr id="content_row_id_<?php print $item['id']; ?>">
            <td ><?php print $item['id']; ?><br />
              <a href="<?php print $this->content_model->contentGetHrefForPostId($item['id']) ; ?>" target="_blank" ><img src="<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>icons/link_go.png"  border="0" alt="" style="float:left" />Visit</a></td>
            <td ><div style="background-image: url('<?php print   $thumb = $this->content_model->contentGetThumbnailForContentId($item['id'], 110); ?>'); height:100px; width:100px; background-repeat:no-repeat;"></div></td>
            <td ><h2><?php print ($item['content_title']) ?></h2>
              <div>
                <?php if($item['content_description'] != ''): ?>
                <?php print character_limiter(strip_tags(html_entity_decode($item['content_description'])), 250, '...'); ?>
                <?php else: ?>
                <?php print character_limiter(strip_tags(html_entity_decode($item['content_body_nohtml'])), 250, '...'); ?>
                <?php endif; ?>
              </div></td>
            <td ><?php $temp= $this->content_model->commentsGetCountForContentId( $item['id']); print ((  $temp )); ?></td>
            <!-- <td ><?php if($selected_voted == false) : ?>
                  <?php $temp = 7 ?>
                  <?php else: ?>
                  <?php $temp = $selected_voted ?>
                  <?php endif; ?>
                  <?php print $this->content_model->contentGetVotesCountForContentId($item['id'], $temp); ?></td>-->
            
            <td ><?php print ($item['updated_on']) ?></td>
            <td ><a href="<?php print site_url('admin/content/posts_edit/id:'.$item['id'])  ?>" class="button small"  >Edit post</a> <br />
              <br />
              <a class="deleteanchor"  href="javascript:deleteContentItem(<?php print $item['id']; ?>, 'content_row_id_<?php print $item['id']; ?>')">Delete</a></td>
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
    </DIV>
    <BR class="cl">
    <!-- End Layout Example --> 
    <!-- End Grid --> 
  </DIV>
  <!-- End Page Wrapper --> 
</DIV>
