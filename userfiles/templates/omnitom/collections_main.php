<?php $menu_items = CI::model ( 'content' )->getMenuItemsByMenuUnuqueId('collections_menu');	?>
     
      <div id="collections" class="box">
     <?php if(!empty($menu_items)): ?>
                        <?php $i = 1; foreach($menu_items as $item): ?>
                        

                        

                         <?php $picture =  TEMPLATE_URL.'collections/'.$item['content_id'] .'.jpg' ?>


     <div class="coll coll-<?php print $i ?>" onclick="GoTo('<?php print $item['the_url'] ?>')">
        <img src="<?php print $picture; ?>" class="coll-image" alt="" />
        <h3><?php print ( $item['item_title'] ) ?></h3>
 
   </div>
                 

           <!-- 10% off for Yoga Journal readers -->
                        



                        
 
      
      
                             <?php $i++; endforeach ;  ?>
							 
                             
                             
                             
                             
                            

<!--    
    <div class="coll coll-2" onclick="GoTo('#')">
         <h3>Embrace</h3>
         <img src="site_img/_O5W7279.jpg" class="coll-image" alt="" />
    </div>
    <div class="coll coll-3" onclick="GoTo('#')">
          <h3>Chakra Chick</h3>
          <img src="site_img/_O5W7321.jpg" class="coll-image" alt="" />
    </div>-->

</div>

<?php endif; ?>