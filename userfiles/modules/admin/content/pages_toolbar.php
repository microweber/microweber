
<script  type="text/javascript">


 

$(document).ready(function(){
	
	 
 
$('#pages_tree_toolbar a').live('click',function() { 

$p_id = $(this).parent().attr('data-page-id');

 
mw_select_page_for_editing($p_id);

 return false;});
   
});




function mw_select_page_for_editing($p_id){
	$('#edit_page_toolbar').attr('data-page-id',$p_id);
  mw.reload_module('#edit_page_toolbar');
	
}

</script>


<table  border="1" id="pages_temp_delete_me" style="z-index:9999999999; background-color:#efecec; position:absolute;" >
  <tr>
    <td>
	<module data-type="pages_menu" id="pages_tree_toolbar"  />
    
    
    <button onclick="mw_select_page_for_editing(0)">new page</button>
     </td>
    <td><module data-type="admin/content/edit_page" id="edit_page_toolbar" /></td>
  </tr>
</table>

