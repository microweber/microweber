<script type="text/javascript">
    $(document).ready(function(){
      $(".offers ul li:nth-child(4n)").css("marginRight","0px");
      $(".offers ul li:nth-child(5n)").css("clear","both");
    });
</script>

<div id="home-image">
  <ul>
    <li> <a href="#"> <img src="<?php print TEMPLATE_URL; ?>img/slide_image_0.jpg" alt=""  /> <strong>... Вашият път е нашата цел ...</strong> </a> </li>
    <li> <a href="#"> <img src="<?php print TEMPLATE_URL; ?>img/slide_image_1.jpg" alt=""  /> <strong>... Вашият път е нашата цел ...</strong> </a> </li>
  </ul>
</div>
<?php $chosen = $this->content_model->taxonomyGetChildrenItemsIdsRecursiveAndCache(1952, 'category');
 $chosen_count = count($chosen);
//p($chosen);
?>
<?php $cat  = $this->content_model->taxonomyGetSingleItemById($chosen[1]);
//p($cat);
 ?>
<div id="Fslider"> <a href="#" id="fsliderleft">&nbsp;</a> <a href="#" id="fsliderright">&nbsp;</a>
  <div id="Fslidercontent">
    <h2 class="blue-title border-bottom"><?php print $cat['taxonomy_value'] ?></h2>
    <div id="sliderAction">
      <?php $params = array();
	  							    $params['selected_categories'] = array($cat['id']); //izbrahme za vas
									 $params['visible_on_frontend'] = 'y';
	  							    $items = $this->content_model->getContentAndCache($params);
	  							?>
      <?php if(!empty($items)): ?>
      <ul style="left: 0;" class="notXfader">
        <?php $i = 0; foreach($items as $item):
	  $more = $this->core_model->getCustomFields($table = 'table_content', $id = $item['id']);
	  $thumb = $this->content_model->contentGetThumbnailForContentId($item['id'], 290);
	   ?>
        <li> <a href="<?php print $this->content_model->getContentURLByIdAndCache($item['id']); ?>"> <span style="background-image: url('<?php print $thumb ?>')">
          <?php if(intval($more['price'] > 1)) : ?>
          <strong><?php echo  $more['price'] ?> <?php echo  $more['curency'] ?></strong>
          <?php endif; ?>
          </span> <big> <?php echo $item['content_title'] ?>
          <?php $stars = intval($more['stars']); ?>
          <?php if($stars >0): ?>
          <span class="stars">
          <?php for ($i = 1; $i <= $stars; $i++) { ?>
          <span class="star">&nbsp;</span>
          <?php }  ?>
          </span>
          <?php endif; ?>
          </big></a> <?php echo strip_tags($item['content_description']) ?></li>
        <?php endforeach; ?>
      </ul>
      <?php endif; ?>
    </div>
    <!-- /sliderAction -->
  </div>
  <!-- /Fslidercontent -->
</div>
<!-- /Fslider -->
<?php $cat  = $this->content_model->taxonomyGetSingleItemById($chosen[2]); ?>
<div class="offers">
  <!--<a href="<?php print $this->content_model->taxonomyGetUrlForTaxonomyIdAndCache($chosen[2]); ?>" class="seeall right">Виж всички</a>-->
  <a href="<?php print site_url('search/categories:'.$chosen[2]); ?>" class="seeall right">Виж всички</a>
  <h2 class="gtitle"><?php print $cat['taxonomy_value'] ?></h2>
  <?php $related = array();
	  $related['selected_categories'] = array($cat['id']);
 $related['visible_on_frontend'] = 'y';
	  $limit[0] = 0;
	  $limit[1] = 4;
	  $related = $this->content_model->getContentAndCache($related, false,$limit ); ?>
  <?php if(!empty($related)): ?>
  <span class="hr">&nbsp;</span>
  <ul class="offers-list">
    <?php foreach($related as $item): ?>
    <?php $thumb = $this->content_model->contentGetThumbnailForContentId($item['id'], 230);
	  $more = $this->core_model->getCustomFields($table = 'table_content', $id = $item['id']);  ?>
    <li> <a href="<?php print $this->content_model->contentGetHrefForPostId($item['id']) ; ?>"> <span style="background-image: url(<?php print $thumb ?>)">
      <?php if(intval($more['price'] > 1)) : ?>
      <strong class="price"><?php print intval($more['price']); ?> <?php echo  $more['curency'] ?></strong>
      <?php endif;  ?>
      </span> <big><?php print character_limiter(strip_tags($item['content_title']), 65, '...'); ?>
      <?php $stars = intval($more['stars']); ?>
      <?php if($stars >0): ?>
      <span class="stars">
      <?php for ($i = 1; $i <= $stars; $i++) { ?>
      <span class="star">&nbsp;</span>
      <?php }  ?>
      </span>
      <?php endif; ?>
      </big> <?php print (character_limiter(strip_tags(strip_tags($item['content_description'])), 130, '...')); ?> </a> </li>
    <?php endforeach; ?>
  </ul>
  <?php endif;  ?>
</div>
<div class="offers offers-tabs">
  <ul class="tab-nav">
    <?php for($i =3; $i<$chosen_count; $i++): ?>
    <?php if($i<7): ?>
    <?php $cat  = $this->content_model->taxonomyGetSingleItemById($chosen[$i]); ?>
    <li><a href="#category-<?php print $i?>"><?php print $cat['taxonomy_value'] ?></a></li>
    <?php endif;  ?>
    <?php endfor; ?>
    <!--    <li><a href="#spa-hoteli">Спа хотели</a></li>
    <li><a href="#balneologia">Балнеология</a></li>
    <li><a href="#kulturen-turizam">Културен туризъм</a></li>-->
  </ul>
  <div class="c"></div>
  <?php for($i =3; $i<$chosen_count; $i++): ?>
  <?php if($i<7): ?>
  <?php $cat  = $this->content_model->taxonomyGetSingleItemById($chosen[$i]); ?>
  <div id="category-<?php print $i?>" class="xtab">
    <ul class="offers-list">
      <?php $related = array();
	  $related['selected_categories'] = array($cat['id']);
	  $related['visible_on_frontend'] = 'y';

	  $limit[0] = 0;
	  $limit[1] = 4;
	  $related = $this->content_model->getContentAndCache($related, false,$limit ); ?>
      <?php if(!empty($related)): ?>
      <?php foreach($related as $item): ?>
      <?php $thumb = $this->content_model->contentGetThumbnailForContentId($item['id'], 230);
	  $more = $this->core_model->getCustomFields($table = 'table_content', $id = $item['id']);  ?>
      <li> <a href="<?php print $this->content_model->contentGetHrefForPostId($item['id']) ; ?>"> <span style="background-image: url(<?php print $thumb ?>)">
        <?php if(intval($more['price'] > 1)) : ?>
        <strong class="price"> <?php print intval($more['price']); ?> <?php echo  $more['curency'] ?></strong>
        <?php endif;  ?>
        </span> <big><?php print character_limiter($item['content_title'], 65, '...'); ?>
        <?php $stars1 = intval($more['stars']); ?>
        <?php if($stars1 >1): ?>
        <span class="stars"> <span class="star-num"><?php print $stars1 ?></span></span>
        <?php endif; ?>
        </big> <?php print (character_limiter(strip_tags($item['content_description']), 130, '...')); ?> </a> </li>
      <?php endforeach; ?>
      <?php endif;  ?>
    </ul>
    <!--    <a href="<?php print $this->content_model->taxonomyGetUrlForTaxonomyIdAndCache($cat['id']); ?>" class="seeall right" style="margin: 25px 0 0">Виж всички</a> -->
    <a href="<?php print site_url('search/categories:'.$cat['id']); ?>" class="seeall right">Виж всички</a> </div>
  <?php endif;  ?>
  <?php endfor; ?>
  <!-- <div class="xtab" id="spa-hoteli">2</div>
  <div class="xtab" id="balneologia">3</div>
  <div class="xtab" id="kulturen-turizam">4</div>-->
</div>
<div class="pad">
  <div class="col col-home">
    <?php $cat  = $this->content_model->taxonomyGetSingleItemById($chosen[7]); ?>
    <?php $related = array();
	  $related['selected_categories'] = array($cat['id']);
	  $related['visible_on_frontend'] = 'y';

	  $limit[0] = 0;
	  $limit[1] = 1;
	  $related = $this->content_model->getContentAndCache($related, false,$limit ); ?>
    <?php if(!empty($related)): ?>
    <h2 class="blue-title border-bottom"><?php print $related[0]['content_title'] ?></h2>
    <?php print $related[0]['content_description'] ?>
    <?php endif;  ?>
    <br />
  </div>
  <div class="col col-home">
    <?php $cat  = $this->content_model->taxonomyGetSingleItemById($chosen[8]); ?>
    <?php $related = array();
	  $related['selected_categories'] = array($cat['id']);
	  $related['visible_on_frontend'] = 'y';

	  $limit[0] = 0;
	  $limit[1] = 1;
	  $related = $this->content_model->getContentAndCache($related, false,$limit ); ?>
    <?php if(!empty($related)): ?>
    <h2 class="blue-title border-bottom"><?php print $related[0]['content_title'] ?></h2>
    <?php print $related[0]['content_description'] ?>
    <?php endif;  ?>
    <br />
  </div>
  <div class="col col-home">
    <?php $cat  = $this->content_model->taxonomyGetSingleItemById($chosen[9]); ?>
    <?php $related = array();
	  $related['selected_categories'] = array($cat['id']);
	  $related['visible_on_frontend'] = 'y';

	  $limit[0] = 0;
	  $limit[1] = 1;
	  $related = $this->content_model->getContentAndCache($related, false,$limit ); ?>
    <?php if(!empty($related)): ?>
    <h2 class="blue-title border-bottom"><?php print $related[0]['content_title'] ?></h2>
    <?php print $related[0]['content_description'] ?>
    <?php endif;  ?>
    <br />
  </div>
  <div class="col col-home">
    <?php $cat  = $this->content_model->taxonomyGetSingleItemById($chosen[10]); ?>
    <?php $related = array();
	  $related['selected_categories'] = array($cat['id']);
	  $related['visible_on_frontend'] = 'y';

	  $limit[0] = 0;
	  $limit[1] = 1;
	  $related = $this->content_model->getContentAndCache($related, false,$limit ); ?>
    <?php if(!empty($related)): ?>
    <h2 class="blue-title border-bottom"><?php print $related[0]['content_title'] ?></h2>
    <?php print $related[0]['content_description'] ?>
    <?php endif;  ?>
    <br />
  </div>
</div>
<span class="hr" style="width: ">&nbsp;</span>
<div class="pad relative creatida-home"> <a href="#" class="left creatidalink"></a>
  <div id="mw-item">
    <div class="col">
      <h2 class="blue-title border-bottom">Идеи за пътуване</h2>
      <form method="post" action="#" id="subscribe" class="validate">
        Абонирайте се за нашия бюлетин и ще Ви вдъхновяваме с идеи за пътуване с най-актуалните оферти.. <br />
        <br />
        <span class="input">
        <input type="text" class="required-email" value="E-mail" onfocus="this.value=='E-mail'?this.value='':''" onblur="this.value==''?this.value='E-mail':''" />
        </span> <br />
        <a href="#" class="submit seeall">Регистрирай се</a>
        <input type="submit" class="xsubmit" />
        <br />
        <br />
      </form>
    </div>
    <div class="col col-home">
      <?php $cat  = $this->content_model->taxonomyGetSingleItemById($chosen[11]); ?>
      <?php $related = array();
	  $related['selected_categories'] = array($cat['id']);
	  $related['visible_on_frontend'] = 'y';

	  $limit[0] = 0;
	  $limit[1] = 1;
	  $related = $this->content_model->getContentAndCache($related, false,$limit ); ?>
      <?php if(!empty($related)): ?>
      <h2 class="blue-title border-bottom"><?php print $related[0]['content_title'] ?></h2>
      <?php print $related[0]['content_description'] ?>
      <?php endif;  ?>
      <br />
    </div>
    <div class="col yomex-baat">
      <h2 class="blue-title border-bottom">Yomex</h2>
      Yomex е член на:<br />
      <a target="_blank" href="http://www.baatbg.org" title="Българска асоциация за алтернативен туризъм"><img src="<?php print TEMPLATE_URL; ?>img/baat.jpg" alt="БААТ" /></a><br />
      При нас можете да платите с: <br />
      <img src="<?php print TEMPLATE_URL; ?>img/visa.jpg" alt="Visa" title="Visa" /> <img src="<?php print TEMPLATE_URL; ?>img/masterc.jpg" alt="Master Card" title="Master Card" /> <img src="<?php print TEMPLATE_URL; ?>img/paypal.jpg" alt="Paypal" title="Paypal" /> </div>
    <div id="ismsg" class="clear">
        <a href="#">Удостоверение за туроператор № 03526</a> / <a href="#">Удостоверение за регистрация на турагент РК-01-6455</a>
        / <a href="#">Туристическа агенция ЙОМЕКС е регистрирана по Закона за защита на личните данни – Удостоверение №5897</a>



        </div>
  </div>
</div>
<?php include ACTIVE_TEMPLATE_DIR. 'footer_nav.php'; ?>
