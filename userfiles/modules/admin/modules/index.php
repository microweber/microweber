<?

 $rand = uniqid(); ?>
<script  type="text/javascript">




$(document).ready(function(){
	 	window.addEventListener('message', receiveMessage, false);

function receiveMessage(evt)
{
   
    alert("got message: "+evt.data);
   
}
	


 









mw.$('#modules_categories_tree_<? print $rand  ?>').find('a').bind('click',function(e) { 
 
	$p_id = $(this).attr('data-category-id');
	
	mw.$('#modules_admin_<? print $rand  ?>').attr('data-category', $p_id);
 mw.reload_module('#modules_admin_<? print $rand  ?>');
 e.stopPropagation();
 e.preventDefault()
return false;

 });
 






});

 

 
</script>

<div id="mw_edit_pages_content">
  <div class="mw_edit_page_left" id="mw_edit_page_left">
    <div id="modules_categories_tree_<? print $rand  ?>" >
      <module type="categories" data-for="modules" id="modules_admin_categories_<? print $rand  ?>" />
    </div>
    <div class="tree-show-hide-nav"> <a href="javascript:;" class="mw-ui-btn" onclick="mw.tools.tree.openAll(mwd.getElementById('pages_tree_container_482146409'));">Open All</a> <a class="mw-ui-btn" href="javascript:;" onclick="mw.tools.tree.closeAll(mwd.getElementById('pages_tree_container_482146409'));">Close All</a> </div>
  </div>
  <div class="mw_edit_page_right">
    <div class="mw_edit_pages_nav" style="padding-left: 0;">
      <div class="top_label">Here you can easely manage your website pages and posts. Try the functionality below. <a href="#">You can see the tutorials here</a>.</div>
      <div class="vSpace"></div>
    </div>
    <div id="pages_edit_container" >
      <module type="admin/modules/manage" id="modules_admin_<? print $rand  ?>"   />
    </div>
  </div>
</div>
<script  type="text/javascript">
function mw_reload_all_modules(){

	mw.$('#modules_admin_<? print $rand  ?>').attr('reload_modules',1);
		 mw.$('#modules_admin_<? print $rand  ?>').attr('cleanup_db',1);
		 
  	 mw.load_module('admin/modules/manage','#modules_admin_<? print $rand  ?>');
	// mw.$('#modules_admin_<? print $rand  ?>').removeAttr('cleanup_db');
	
}


 


 
</script>
<button onclick="mw_reload_all_modules()">Reload modules</button>
