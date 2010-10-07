<div class="content tabbed">
  <ul class="tab-nav">
    <li><a href="#general-info" title="#" class="active"><span>Обща информация</span></a></li>
    <?php if($post['custom_fields']['prices']) : ?>
    <li><a href="#prices" title="#"><span>Цени</span></a></li>
    <?php endif; ?>
    <?php if($post['custom_fields']['promotions']) : ?>
    <li><a href="#promotions" title="#"><span>Промоции</span></a></li>
    <?php endif; ?>
    <?php if($post['custom_fields']['map']) : ?>
    <li><a href="#offer-map" title="#"><span>Карта</span></a></li>
    <?php endif; ?>
  </ul>
  <?php if($post['custom_fields']['price']) : ?>
  <span class="inline-price"><span class="inline-price-bg">&nbsp;</span><span class="inline-price-content">Цени от: <?php print ($post['custom_fields']['price']); ?> <?php echo  $post['custom_fields']['curency'] ?></span></span>
  <?php endif; ?>
  <div class="c"></div>
  <div class="panes richtext">
    <div class="xtab tab" style="display:block;"  id="general-info">
    <?php if(mb_trim($post['custom_fields']['accom_base'] != '') and ($post['custom_fields']['accom_base'] != '--')) : ?>
        <strong class="tag-accomodation">Настаняване: 
		<?php switch($post['custom_fields']['accom_base']){
		case 'BB':
		print 'BB – нощувка и закуска';
		break;
		
		case 'HB':
		print 'HB – полупансион';
		break;
		
		case 'FB':
		print 'FB – пълен пансион';
		break;
		
		case 'AI':
		print 'AI – всичко включено';
		break;
		
		case 'UAI':
		print 'UAI – ултра всичко включено';
		break;
		
		
			case 'RO':
		print 'RO – само нощувка';
		break;
	
		}?>
        
        </strong>
        <?php endif; ?>
    
    
    
      <?php if($post['custom_fields']['price']) : ?>
      <?php /*
      <strong> Цени от: <?php print ($post['custom_fields']['price']); ?> <?php echo  $post['custom_fields']['curency'] ?></strong>
      */ ?><br />
      <?php include (ACTIVE_TEMPLATE_DIR."offer_read_checkboxes.php"); ?>
      <?php print ($post['custom_fields']['price_desc']); ?> <br />
      <?php endif; ?>
      <?php include (ACTIVE_TEMPLATE_DIR."offer_date_blocks.php"); ?>
      <?php print ($post['content_body']); ?>
      <br />
      <?php // p($post); ?>
      <?php include (ACTIVE_TEMPLATE_DIR."content_blocks.php"); ?>
    </div>
    <?php if($post['custom_fields']['prices']) : ?>
    <div class="xtab tab" id="prices">
      <p> <?php print ($post['custom_fields']['prices']); ?> </p>
    </div>
    <?php endif; ?>
    <?php if($post['custom_fields']['promotions']) : ?>
    <div class="xtab tab" id="promotions">
      <p>
        <?php print ($post['custom_fields']['promotions']); ?>
      </p>
    </div>
    <?php endif; ?>
    <?php if($post['custom_fields']['map']) : ?>
    <div class="xtab tab googleMap" id="offer-map"> <?php print ($post['custom_fields']['map']); ?> </div>
    <?php endif; ?>
    <div class="c"></div>
  </div>
  <?php include (ACTIVE_TEMPLATE_DIR."offer_read_footer_reserve.php"); ?>
</div>
