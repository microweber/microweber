menus

<table width="100%" border="0">
  <tr>
    <td>
    
    
 <?php  $menus = CI::model('content')->content_model->getMenus(array('item_type' => 'menu'));
		
		 
		?>
    
     <?php foreach($menus as $item): ?>
      <mw module="admin/content/menu" id="<?php print $item['id'] ?>" />
         
        <?php endforeach; ?>
        
   
    
    </td>
    <td>&nbsp;</td>
  </tr>
</table>





