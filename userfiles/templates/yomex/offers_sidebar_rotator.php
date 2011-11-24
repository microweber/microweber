      <?php $params = array();
	  							    $params['selected_categories'] = $active_categories; //izbrahme za vas	
									$params['is_features'] = 'y';
									$params['use_fetch_db_data'] = true;
									$params['limit'] =array(0,10); 
									$params['only_fields'] =array('id','content_title', 'content_description'); 
									$params['visible_on_frontend'] = 'y'; 
									
									if(!empty($post)){
										$params['exclude_ids'] =array($post['id']); 
										
									}
									
	  							    $items = $this->content_model->getContentAndCache($params); 
	  							?>
      <?php if(!empty($items)):
	  shuffle($items);
	   ?>
      <?php $i = 0; foreach($items as $item):
	  $more = $this->core_model->getCustomFields($table = 'table_content', $id = $item['id']);
	  $thumb = $this->content_model->contentGetThumbnailForContentId($item['id'], 180); 
	   ?>
     <?php if($i <3) : ?>
     
     <div class="box">
  <h2 class="gtitle">
   <?php if(intval($more['price'] > 1)) : ?>
  <strong>От: <?php echo  $more['price'] ?> <?php echo  $more['curency'] ?></strong>
   <?php endif; ?>
  </h2>
  <div>
    <h3><?php echo $item['content_title'] ?></h3>
    <?php $stars = intval($more['stars']); ?>
        <?php if($stars >0): ?>
        <span class="stars">
        <?php for ($i = 1; $i <= $stars; $i++) { ?>
        <span class="star">&nbsp;</span>
        <?php }  ?>
        </span>
        <?php endif; ?>
    
    <p>
    <a  href="<?php print $this->content_model->getContentURLByIdAndCache($item['id']); ?>" title="<?php echo addslashes($item['content_title'] ) ?>"><img title="<?php echo addslashes($item['content_title'] ) ?>" src="<?php print  $thumb  ?>" width="180" border="0" /></a>
	
	<?php echo character_limiter($item['content_description'], 90);  ?> <br />
      <br />
      <a class="seeall" href="<?php print $this->content_model->getContentURLByIdAndCache($item['id']); ?>" title="<?php echo addslashes($item['content_title'] ) ?>">повече</a></p>
  </div>
</div>
     
     
       <?php endif; ?>
 
 
      <?php $i++; endforeach; ?>
      <?php endif; ?>


<!--
<div class="box">
  <h2 class="gtitle">Категория</h2>
  <div>
    <h3>Почивка в Ахтопол</h3>
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus ultricies enim tincidunt orci vehicula fringilla at ut mauris. Integer nec purus eu libero vulputate auctor. <br />
      <br />
      <a class="seeall" href="#" title="#">повече</a></p>
  </div>
</div>-->