<div class="list-offers"> <span class="hr"></span> <br />


  <a href="<?php print $this->content_model->getContentURLByIdAndCache($page['id']); ?>/view:all" class="seeall right">Виж всички</a>
  <h2 class="gtitle">Други предложения</h2>
  <span class="hr"></span>
  
  
  
  
  
  
  
   
  
  
  
  
  
  
  
  
  
  
  
  

  
  
  
  
  
  <div id="Slider">
  
  
  
  
  
  <?php $params = array();
	  							    $params['selected_categories'] = ($active_categories); //izbrahme za vas	
									$params['use_fetch_db_data'] = true;
										$params['only_fields'] =array('id','content_title', 'content_description'); 
									 $params['limit'] = array(0,16); //izbrahme za vas	
									 $params['visible_on_frontend'] = 'y'; 
	  							    $items = $this->content_model->getContentAndCache($params); 
	  							?>                                            
      <?php if(!empty($items)): ?>
      <?php $i = 0; foreach($items as $item):
	  $more = $this->core_model->getCustomFields($table = 'table_content', $id = $item['id']);
	  $thumb = $this->content_model->contentGetThumbnailForContentId($item['id'], 300); 
	   ?>
        <div class="list-offer">
      <?php if($i == 0): ?>
     
        <?php endif; ?>
        
         <div class="image-holder">
         <a style="background-image: url(<?php print $thumb ?>)" href="<?php print $this->content_model->getContentURLByIdAndCache($item['id']); ?>" title="<?php echo addslashes($item['content_title']) ?>">

         </a></div>
      <h3><a href="<?php print $this->content_model->getContentURLByIdAndCache($item['id']); ?>" title="<?php echo addslashes($item['content_title']) ?>"><?php echo $item['content_title'] ?></a></h3>
      <?php $stars = intval($more['stars']); ?>
        <?php if($stars >0): ?>
        <span class="stars">
        <?php for ($i = 1; $i <= $stars; $i++) { ?>
        <span class="star">&nbsp;</span>
        <?php }  ?>
        </span>
        <?php endif; ?>
      <?php if(intval($more['price'] > 1)) : ?> <p class="tag-price">Цена от: <?php echo  $more['price'] ?> <?php echo  $more['curency'] ?></p> <?php endif; ?>
      <p> <?php echo character_limiter($item['content_description'], 90);  ?> <br />
        <a href="<?php print $this->content_model->getContentURLByIdAndCache($item['id']); ?>" title="<?php echo addslashes($item['content_title']) ?>" class="button-more">прочети повече</a> </p>
      <div class="c"></div>
        
        
        
         
        
        
        
        <?php $i++; if($i == 3): ?>
    
      <?php endif; ?>
      <?php if($i == 3) $i = 0; ?>
        </div>
      <?php endforeach; ?>
      <?php endif; ?>
  
  
  
  
    <!--<div class="list-offer">
      <div class="image-holder"><a href="#" title="#"><img src="<?php print TEMPLATE_URL; ?>img/slider_img_1.jpg"/></a></div>
      <h3><a href="#" title="#">Заглавие на продукта</a></h3>
      <p class="tag-price">Цена от: &euro; 389</p>
      <p> Lorem ipsum dolor sit amet. Nam eleifend viverra odio at adipiscing.Nam eleifend viverra odio at adipiscing. Lorem ipsum dolor sit amet, consectetur... <br />
        <a href="#" title="#" class="button-more">прочети повече</a> </p>
      <div class="c"></div>
    </div>
    
    
    
    
    
    
    
    
    
    
    <div class="list-offer">
      <div class="image-holder"><a href="#" title="#"><img src="<?php print TEMPLATE_URL; ?>img/slider_img_1.jpg"/></a></div>
      <h3><a href="#" title="#">Заглавие на продукта</a></h3>
      <p class="tag-price">Цена от: &euro; 389</p>
      <p>gr. Sofia</p>
      <p> Lorem ipsum dolor sit amet. Nam eleifend viverra odio at adipiscing.Nam eleifend viverra odio at adipiscing. Lorem ipsum dolor sit amet, consectetur... <br />
        <a href="#" title="#" class="button-more">прочети повече</a> </p>
      <div class="c"></div>
    </div>
    <span class="hr"></span>
    <div class="list-offer">
      <div class="image-holder"><a href="#" title="#"><img src="<?php print TEMPLATE_URL; ?>img/slider_img_1.jpg"/></a></div>
      <h3><a href="#" title="#">Заглавие на продукта</a></h3>
      <p class="tag-price">Цена от: &euro; 389</p>
      <p> Lorem ipsum dolor sit amet. Nam eleifend viverra odio at adipiscing.Nam eleifend viverra odio at adipiscing. Lorem ipsum dolor sit amet, consectetur... <br />
        <a href="#" title="#" class="button-more">прочети повече</a> </p>
      <div class="c"></div>
    </div>
    <div class="list-offer">
      <div class="image-holder"><a href="#" title="#"><img src="<?php print TEMPLATE_URL; ?>img/slider_img_1.jpg"/></a></div>
      <h3><a href="#" title="#">Заглавие на продукта</a></h3>
      <p class="tag-price">Цена от: &euro; 389</p>
      <p> Lorem ipsum dolor sit amet. Nam eleifend viverra odio at adipiscing.Nam eleifend viverra odio at adipiscing. Lorem ipsum dolor sit amet, consectetur... <br />
        <a href="#" title="#" class="button-more">прочети повече</a> </p>
      <div class="c"></div>
    </div>-->
    <div class="c"></div>
  </div>
  <div class="pagining"> <a class="seeall right" title="#">следващи</a> <a class="seeall left" title="#">предишни</a>
    <p>Страница: 1 от 1</p>
    <div class="c"></div>
  </div>
</div>
