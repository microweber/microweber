

    <? $pages = get_pages_old(); $pages = $pages['posts']; ?>
    <? if(!empty($pages)): ?>
    <? foreach($pages as $page): ?>
    <div class="field" style="display:none">
      <input type="text" value="<? print $page['content_title'] ?>" />
      <a target="_blank" class="btn2"  href="<? print site_url('admin/edit/url:'); ?><? print base64_encode(page_link( $page['id'] )) ?>">iframe</a> | <a target="_blank" class="btn2"  href="<? print page_link( $page['id'] ) ?>/editmode:y">editmode</a> | <a target="_blank" class="btn2" href="<? print page_link( $page['id'] ) ?>">visit</a> | <a class="btn2" href="<? print ADMIN_URL ?>/action:page_edit/id:<? print $page['id'] ?>">edit</a> </div>
    <? endforeach; ?>
    <? endif; ?>


    <script type="text/javascript">
var del_page_confirm = function(id){
	
	
	var answer = confirm("Are you sude you want to delete this page?")
	if (answer){
		$.post("<? print site_url('api/content/delete') ?>",  { id:id} ,	function(data1){ 
																		
																//$('#del_pa').html(data1)	;	
																	//	alert(data1);
										//
										$('#page_list_'+id).fadeOut();		
 
							
																					 });
	}
	else{
		 
	}
	
	
 
	
}
</script>



  <div class="Pages">
    <? 
	
	$url  =  '<div class="field" id="page_list_{id}"><div class="field_ctrl">';

    //$url .=  '<a href="'.ADMIN_URL .'action:page_edit/id:{id}">{content_title}</a>';
    $url .=  '<a class="fiedl_edit" href="'.ADMIN_URL .'/action:page_edit/id:{id}">Edit page</a>';
    $url .=  '<a class="fiedl_visit_page" href="{link}/editmode:y">Visit page</a>';
    $url .=  '<a class="fiedl_add_sub_page" href="'.ADMIN_URL .'/action:page_edit/id:0/content_parent:{id}">Add subpage</a>';
    $url .=  '<a class="fiedl_delete_page"  href="javascript: del_page_confirm({id})">Delete page</a>';
    $url .= '</div>';
    $url .= '<h2><span class="field_ctrl_plus"></span><a href="#">{content_title}</a></h2>';
    $url .= '</div>';




	 CI::model('content')->content_helpers_getPagesAsUlTree($content_parent = 0, $link = $url, $actve_ids = false, $active_code = false, $remove_ids = false, $removed_ids_code = false);
	?>
    

  </div>
  <!-- /.createpages -->
  <a href="<? print ADMIN_URL ?>/action:page_edit/id:0" class="btn">Add New Page</a>
