<? $pages = get_pages_old(); $pages = $pages['posts']; ?>
<? if(!empty($pages)): ?>
<? foreach($pages as $page): ?>

<div class="field" style="display:none">
  <input type="text" value="<? print $page['content_title'] ?>" />
  <a target="_blank" class="btn2"  href="<? print site_url('admin/edit/url:'); ?><? print base64_encode(page_link( $page['id'] )) ?>">iframe</a> | <a target="_blank" class="btn2"  href="<? print page_link( $page['id'] ) ?>/editmode:y">editmode</a> | <a target="_blank" class="btn2" href="<? print page_link( $page['id'] ) ?>">visit</a> | <a class="btn2" href="<? print ADMIN_URL ?>/action:page_edit/id:<? print $page['id'] ?>">edit</a> </div>
<? endforeach; ?>
<? endif; ?>
<script type="text/javascript">
 
	   //sortable table
  $(document).ready(function(){
      //

	$('.Pages ul').sortable({
					opacity: '0.5',
					containment: 'parent',
					items: 'ul, li' ,
					forcePlaceholderSize: true,
					forceHelperSize: true ,
					 
					update: function(e, ui){
						serial = $(this).sortable("serialize");
						$.ajax({
							url: "<?php print site_url('api/content/posts_sort_by_date')  ?>",
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
 
//
//
//$(document).ready(function(){
//
//        $('.Pages ul:first').nestedSortable({
//			disableNesting: 'no-nest',
//			forcePlaceholderSize: true,
//			handle: 'h2',
//			items: 'li',
//			opacity: .6,
//			placeholder: 'placehulder',
//			tabSize: 25,
//			tulerance: 'pointer',
//			update: function(serialized) {
//    			/*serialized = $('ul.category_tree').nestedSortable('serialize');
//     	        $.post("http://pecata/microweber/api/content/save_taxonomy_items_order", { items: serialized },
//                function(data){
//
//                }); */
//            }
//		});
//});





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
	//$url .=  '<a class="fiedl_add_sub_page" href="'.ADMIN_URL .'/action:toolbar_fs/page_id:{id}">iframe</a>';
    $url .=  '<a class="fiedl_delete_page"  href="javascript: del_page_confirm({id})">Delete page</a>';
    $url .= '</div>';
    $url .= '<h2><span class="field_ctrl_plus"></span><a href="#">{content_title}</a></h2>';
    $url .= '</div>';




	 CI::model('content')->content_helpers_getPagesAsUlTree($content_parent = 0, $link = $url, $actve_ids = false, $active_code = false, $remove_ids = false, $removed_ids_code = false);
	?>
</div>
<!-- /.createpages -->
