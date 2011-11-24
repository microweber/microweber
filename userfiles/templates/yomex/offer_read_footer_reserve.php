<?php /*
 <div class="hidden">
    <form id="reserve_form" method="get" action="#">
        <div class="heading"><h1>Резервирай <?php print ($post['content_title']); ?></h1></div>
        <input type="hidden" id="reserve_title" value="<?php print ($post['content_title']); ?>" />
        <input type="hidden" id="reserve_url" value="<?php print  $link = $this->content_model->getContentURLByIdAndCache($post['id']); ?>" />

        <label>Име: *</label>
        <div class="contact_input">
            <input type="text" class="required" id="reserve_name" />
        </div>

        <label>Телефон: *</label>
        <div class="contact_input">
            <input type="text" class="required" id="reserve_phone" />
        </div>

        <label>Email: *</label>
        <div class="contact_input">
            <input type="text" class="required-email" id="reserve_mail" />
        </div>
        <label>Съобщение: *</label>
        <div class="contact_textarea" style="padding-bottom: 12px;">
            <textarea class="required" id="reserve_message" rows="" cols=""></textarea>
        </div>

        <input type="submit" value="Резервирай" />
    </form>
 </div>
*/
?>

<div class="options">
  <ul>
    <?php /*
      <li><a href="#" title="Резервирай" class="reserve"><span>Резервирай</span></a></li>
      */ ?>
    <?php $what = intval($page['id']); ?>
    <?php switch($what){
	
case 494 :
	$res_iframe =TEMPLATE_URL.'reserve_ekskurzii.php';
break;	


case 489 :
	$res_iframe =TEMPLATE_URL.'reserve-congress.php';
break;	


case 493 :
	$res_iframe =TEMPLATE_URL.'reserve-air.php';
break;
	
case 492 :
	$res_iframe =TEMPLATE_URL.'reserve-hotels.php';
break;

case 490 :
	$res_iframe =TEMPLATE_URL.'reserve_pochivki.php';
	//$res_iframe =TEMPLATE_URL.'reserve_ekskurzii.php';
break;

default:
$res_iframe =TEMPLATE_URL.'reserve-hotels.php';
break;



	
	
	
	
	
}?>
    <?php //var_dump($more); ?>
    <?php $more_dates = false;
if(!empty($more)){
	
asort($more);


foreach($more as $k => $v){
	if(stristr($k, 'date_')){
		$more_dates[] = $v;
		
	}
	
}
}
if(!empty($more_dates)){
	$more_dates = '&dates='.base64_encode(serialize($more_dates));
} else {
$more_dates = false;	
	
}

//var_dump($more_dates);
if($what  == 489){
	$title_for_request = $page['content_title'];
} else {
$title_for_request = $page['content_title'].': '.$post['content_title'];	
}

?>


<? // var_dump($active_categories); ?>


<? if(in_array(5613, $active_categories) == false): ?>
    <li><a href="<?php print $res_iframe ?>?site_url=<?php print base64_encode(site_url()); ?>&curent_url=<?php print base64_encode(mw_curent_url()); ?><?php print $more_dates ?>&title=<?php print base64_encode($title_for_request); ?>&page_id=<?php print ($page['id']); ?><?php print $more_dates ?>" class="ajax-box reserve"><span>
      <?php if($what !=  489): ?>
      Резервирай
      <?php else: ?>
      Направи запитване
      <?php endif; ?>
      </span></a></li>
      <? endif; ?>
      
      
      
    <li><a href="#" title="Сподели с приятел" class="share"><span>Сподели с приятел</span></a></li>
    <?php if($what !=  489): ?>
    <li><a href="<?php print TEMPLATE_URL; ?>contact_form.php" title="Направи запитване" class="ask ajax-box"><span>Направи запитване</span></a></li>
    <?php endif; ?>
    <li><a href="#" title="Разпечатай" class="print"><span>Разпечатай</span></a></li>
    <!--
      <li><a href="<?php print TEMPLATE_URL; ?>reserve-hotels.php" class="ajax-box"><span>Резервирай (хотел)</span></a></li>
      <li><a href="<?php print TEMPLATE_URL; ?>reserve-air.php" class="ajax-box"><span>Резервирай (самолетни билети)</span></a></li>
      <li><a href="<?php print TEMPLATE_URL; ?>reserve-congress.php" class="ajax-box"><span>Резервирай (конгресен туризъм)</span></a></li>
      <li><a href="<?php print TEMPLATE_URL; ?>reserve-pochivki.php" class="ajax-box"><span>Резервирай (Почивки)</span></a></li>-->
  </ul>
  <div class="c"></div>
</div>
