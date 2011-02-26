menus

<table width="100%" border="0">
  <tr>
    <td>
    
    
    
    <?
	$menus_data = array();
	$menus_data['item_type']= 'menu';
	$menus = CI::model('content')->getMenus($menus_data) ;

	?>
    
    
    <mw module="admin/content/menu" id="1" />
    
    </td>
    <td>&nbsp;</td>
  </tr>
</table>





