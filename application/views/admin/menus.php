<div class="box radius">
<div class="box_header radius_t">
     <h2>Menus</h2>
</div>

<div id="menus">





 <?php  $menus = get_instance()->content_model->content_model->getMenus(array('item_type' => 'menu'));

		 
		?>
    
     <?php foreach($menus as $item): ?>
      <mw module="admin/content/menu" id="<?php print $item['id'] ?>" />
         
        <?php endforeach; ?>


</div>


</div>





