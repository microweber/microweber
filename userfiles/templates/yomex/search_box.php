<?php // var_dump($active_categories); ?>
<?php if(in_array(1860, $active_categories) == false) : ?>
<?php $kw_value =   $this->core_model->getParamFromURL ( 'keyword' ); 
if($kw_value  == false){
$kw_value = 	'Търсене';
}

?>

<div class="box wrap" id="topsearch" style="padding-bottom: 12px;">
  <h2 class="gtitle" style="padding: 10px 0 0 10px">Търсене</h2>
  <div>
    <?php //p($page); ?>
    <form method="post" action="<?php print $this->content_model->getContentURLByIdAndCache($page['id']) ?>/view:all" id="topsearch-form">
      <input type="submit" value="Търсене" style="margin-right: 1px;height: 26px;" class="search right"/>
      <span class="field left" style="margin-right: 5px">
      <input id="searchtext"  name="search_by_keyword" type="text" class="required" style="width: <?php if(in_array(1, $active_categories) == false) : ?>50<?php else : ?>200<?php endif; ?>px;padding: 5px 5px 6px 5px;" value="<?php print $kw_value ?>" onfocus="this.value=='Търсене'?this.value='':''" onblur="this.value==''?this.value='Търсене':''" >
      </span>
      <script type="text/javascript">
      $(document).ready(function(){

      });

     /* setInterval(function(){
        var c = "";
        $("#topsearch-form input[type='hidden']").each(function(){
          var t = $(this).val();
          c = c + "&nbsp;&nbsp;" + t;
        });

        $("#top-nav").html("<span style='color:white'>" + c + "</span>");

      }, 100); */


      </script>
      <div class="field left" style="width: 110px;margin-right: 5px">
        <div class="dropdown" id="DestiNacia"> <span class="dropdownValue">Дестинация</span>
          <?php $link = false;
if($view == false){
$link = $this->content_model->getContentURLByIdAndCache($content_item['id']).'/categories:{taxonomy_value}' ;
} else {
	$link = $this->content_model->getContentURLByIdAndCache($content_item['id']).'/categories:{taxonomy_value}/view:'. $view ;
}
$active = '  class="active"   ' ;
$actve_ids = $active_categories;
if( empty($actve_ids ) == true){
$actve_ids = array($page['content_subtype_value']);
}
$this->content_model->content_helpers_getCaregoriesUlTree(2161, "<a rev='{id}' rel='destination' href='$link'  hreflang='{empty_or_full}'   {active_code}   type='selected_categories'     >{taxonomy_value}</a>" , $actve_ids = $actve_ids, $active_code = $active, $remove_ids = false, $removed_ids_code = false, $ul_class_name = 'dropdownList', $include_first = false, $content_type = false, $li_class_name = false, $add_ids = false, $order = array('taxonomy_value', 'asc'), $only_with_content = array($page['content_subtype_value'],'{id}'));


	?>
          <?php $custom_fields_search_criteria = $this->core_model->getParamFromURL('custom_fields_criteria');
	  
	  //var_dump($custom_fields_search_criteria );
	  ?>
        </div>
      </div>
      
      
      
      

      <div class="field left" style="width: 120px;margin-right: 5px">
        <div class="dropdown" id="kategoria_ID"> <span class="dropdownValue">Категория</span>

          <?php $link = false;
if($view == false){
$link = $this->content_model->getContentURLByIdAndCache($content_item['id']).'/categories:{taxonomy_value}' ;
} else {
	$link = $this->content_model->getContentURLByIdAndCache($content_item['id']).'/categories:{taxonomy_value}/view:'. $view ;
}
$active = '  class="active"   ' ;
$actve_ids = $active_categories;
if( empty($actve_ids ) == true){
$actve_ids = array($page['content_subtype_value']);
}
$this->content_model->content_helpers_getCaregoriesUlTree($page['content_subtype_value'], "<a rev='{id}' rel='destination' href='$link'  hreflang='{empty_or_full}'   {active_code}   type='selected_categories'     >{taxonomy_value}</a>" , $actve_ids = $actve_ids, $active_code = $active, $remove_ids = false, $removed_ids_code = false, $ul_class_name = 'dropdownList', $include_first = false, $content_type = false, $li_class_name = false, $add_ids = false, $order = array('taxonomy_value', 'asc'), $only_with_content = array($page['content_subtype_value'],'{id}'));


	?>
          <?php $custom_fields_search_criteria = $this->core_model->getParamFromURL('custom_fields_criteria'); 
	  
	  //var_dump($custom_fields_search_criteria );
	  ?>
        </div>
      </div>
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      <?php if(in_array(1, $active_categories) == false) : ?>
      <div class="field left" style="width: 70px;margin-right: 5px">
        <div class="dropdown"> <span class="dropdownValue">Звезди</span>
          <ul class="dropdownList" style="width: 90px">
            <li><a type="custom_fields[stars]" <?php if($custom_fields_search_criteria['stars'] == '' ): ?> class="active" <?php endif; ?> rev="">Звезди</a></li>
            <li><a type="custom_fields[stars]" rev="">Всички</a></li>
            <li><a type="custom_fields[stars]" <?php if( $custom_fields_search_criteria['stars']  == '2' ): ?> class="active" <?php endif; ?>  rev="2">2 звезда</a></li>
            <li><a type="custom_fields[stars]" <?php if( $custom_fields_search_criteria['stars']  == '3' ): ?> class="active" <?php endif; ?>  rev="3">3 звезди</a></li>
            <li><a type="custom_fields[stars]" <?php if( $custom_fields_search_criteria['stars']  == '4' ): ?> class="active" <?php endif; ?>  rev="4">4 звезди</a></li>
            <li><a type="custom_fields[stars]" <?php if( $custom_fields_search_criteria['stars']  == '5' ): ?> class="active" <?php endif; ?>  rev="5">5 звезди</a></li>
          </ul>
        </div>
      </div>
      <input type="hidden" name="custom_fields[stars]" id="custom_fields[stars]"  value="<?php print $custom_fields_search_criteria['stars'] ?>" />
      <?php endif; ?>
      <?php if(in_array(1, $active_categories) == false) : ?>
      <div class="field left" style="width: 110px;margin-right: 5px">
        <div class="dropdown"> <span class="dropdownValue">Настаняване</span>
          <ul class="dropdownList" style="width: 190px">
            <li><a type="custom_fields[accom_base]" <?php if($custom_fields_search_criteria['accom_base'] == '' ): ?> class="active" <?php endif; ?> rev="">Настаняване</a></li>
            <li><a type="custom_fields[accom_base]" rev="">Всички</a></li>
            <li><a type="custom_fields[accom_base]" <?php if( $custom_fields_search_criteria['accom_base']  == 'RO' ): ?> class="active" <?php endif; ?>  rev="RO">RO – само нощувка</a></li>
            <li><a type="custom_fields[accom_base]" <?php if( $custom_fields_search_criteria['accom_base']  == 'BB' ): ?> class="active" <?php endif; ?>  rev="BB">BB – нощувка и закуска</a></li>
            <li><a type="custom_fields[accom_base]" <?php if( $custom_fields_search_criteria['accom_base']  == 'HB' ): ?> class="active" <?php endif; ?>  rev="HB">HB – полупансион</a></li>
            <li><a type="custom_fields[accom_base]" <?php if( $custom_fields_search_criteria['accom_base']  == 'FB' ): ?> class="active" <?php endif; ?>  rev="FB">FB – пълен пансион</a></li>
            <li><a type="custom_fields[accom_base]" <?php if($custom_fields_search_criteria['accom_base']  == 'AI' ): ?> class="active" <?php endif; ?>  rev="AI">AI – всичко включено</a></li>
            <li><a type="custom_fields[accom_base]" <?php if( $custom_fields_search_criteria['accom_base']  == 'UAI' ): ?> class="active" <?php endif; ?>  rev="UAI">UAI – всичко включено +</a></li>
          </ul>
        </div>
      </div>
      <input type="hidden" id="custom_fields[accom_base]" name="custom_fields[accom_base]"  value="<?php print $custom_fields_search_criteria['accom_base'] ?>" />
      <?php endif; ?>
      <?php /*
      <select name="custom_fields[accom_base]">
        <option  <?php if($custom_fields_search_criteria['accom_base'] == '' ): ?> selected="selected" <?php endif; ?>></option>
        <option  <?php if( $custom_fields_search_criteria['accom_base']  == 'BB' ): ?> selected="selected" <?php endif; ?>  value="BB">BB – нощувка и закуска</option>
        <option  <?php if( $custom_fields_search_criteria['accom_base']  == 'HB' ): ?> selected="selected" <?php endif; ?>  value="HB">HB – полупансион</option>
        <option  <?php if( $custom_fields_search_criteria['accom_base']  == 'FB' ): ?> selected="selected" <?php endif; ?>  value="FB">FB – пълен пансион</option>
        <option  <?php if($custom_fields_search_criteria['accom_base']  == 'AI' ): ?> selected="selected" <?php endif; ?>  value="FB">AI – всичко включено</option>
        <option  <?php if( $custom_fields_search_criteria['accom_base']  == 'UAI' ): ?> selected="selected" <?php endif; ?>  value="UAI">UAI – ултра всичко включено</option>
      </select>
      */ ?>
      <?php /*
      <p> <span class="field">
        <input type="text" class="one" style="width: 76px;" value="От" name="date">
        </span>&nbsp;<span class="field">
        <input type="text" class="two" style="width: 76px;" value="До" name="date">
        </span> </p>
      */ ?>
      <?php $ord=  $this->core_model->getParamFromURL ( 'ord' );   ?>
      <?php $ord_dir=  $this->core_model->getParamFromURL ( 'ord-dir' );   ?>
      <?php /*
      <select name="ord">
        <option  <?php if($ord): ?> selected="selected" <?php endif; ?>></option>
        <option  <?php if( $ord  == 'updated_on' ): ?> selected="selected" <?php endif; ?>  value="updated_on">Най нови</option>
        <option  <?php if( $ord == 'content_title' ): ?> selected="selected" <?php endif; ?>  value="content_title">Заглавие</option>
      </select>
      */ ?>
      <div class="field left" style="width: 90px;margin-right: 10px">
        <div class="dropdown"> <span class="dropdownValue">Подреди по</span>
          <ul class="dropdownList" style="width: 100px">
            <li><a type="ord" <?php if( $ord == false): ?> class="active" <?php endif; ?> rev="">Подреди по</a></li>
            <li><a type="ord" <?php if( $ord  == 'is_special' ): ?> class="active" <?php endif; ?> rev="is_special,desc">Специални</a></li>
            <li><a type="ord" <?php if( $ord == 'content_title' ): ?> class="active" <?php endif; ?>  rev="content_title,asc">Азбучен ред</a></li>
            <li><a type="ord" <?php if( $ord  == 'updated_on' ): ?> class="active" <?php endif; ?> rev="updated_on">Най-нови</a></li>
          </ul>
        </div>
      </div>
      <input type="hidden" name="ord" id="ord" value="<?php print $ord ?>" />
      <input type="hidden" name="ord-dir" value="<?php print $ord_dir ?>" />
      <?php /*       <input type="hidden" name="ord-dir" id="ord-dir" />

     <?php $ord2=  $this->core_model->getParamFromURL ( 'ord-dir' );   ?>

     <div class="field left"  style="width: 120px;margin-right: 10px">
      <div class="dropdown"> <span class="dropdownValue">Сортиране</span>

      <ul class="dropdownList" style="width: 120px">

          <li><a type="ord-dir" <?php if( !$ord2): ?> class="active" <?php endif; ?> rev="">Сортиране</a></li>
          <li><a type="ord-dir" <?php if( $ord2  == 'asc' ): ?> class="active" <?php endif; ?> rev="asc">Възходящо</a></li>
          <li><a type="ord-dir" <?php if( $ord2 == 'content_title' ): ?> class="active" <?php endif; ?>  rev="desc">Низходящо</a></li>

      </ul>
       </div>
        </div>
     */ ?>
      <input type="hidden" name="selected_categories" value="<?php //print  $this->core_model->getParamFromURL ( 'categories' ); ?>" id="selected_categories" />
      <input type="hidden" name="begin_search" value="1" />
      <?php //p($custom_fields_search_criteria); ?>
    </form>
  </div>
</div>
<?php endif; ?>
