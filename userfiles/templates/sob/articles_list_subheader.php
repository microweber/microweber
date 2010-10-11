<?php dbg(__FILE__); ?>

<div id="top-inner-title" class="border-bottom">
  <?php 
  
// var_Dump($active_categories);
  $cat = ( $this->taxonomy_model->getSingleItem($active_categories[0])); 
  $view = $this->core_model->getParamFromURL ( 'view' ); 
   $type = $this->core_model->getParamFromURL ( 'type' ); 
   
  // p($cat,1);
 ?>
  <?php if($view != 'list' ) : ?>
  <a class="btn right" href="<?php print $this->taxonomy_model->getUrlForIdAndCache($active_categories[0]);  ?>/view:list"> <span>See all <?php print $cat['taxonomy_value'] ?> articles</span> </a>
  <?php else: ?>
  <a class="btn right" href="<?php print $this->taxonomy_model->getUrlForIdAndCache($active_categories[0]);  ?>"> <span> Back to <?php print $cat['taxonomy_value'] ?> home </span> </a>
  <?php endif; ?>
  <h2><?php print $cat['taxonomy_value'] ?>
    <?php ($type)? print $type : print 'articles'   ?>
  </h2>
  <?php if($view == 'list' ) : ?>
  <div class="status-nav top-status-nav">
    <ul>
      <li<?php if(!$type): ?>  class="u" <?php endif; ?>><a href="<?php print $this->taxonomy_model->getUrlForIdAndCache($active_categories[0]);  ?>/view:list"><span>Artciles</span></a></li>
      <li<?php if($type == 'trainings'): ?>  class="u" <?php endif; ?>><a href="<?php print $this->taxonomy_model->getUrlForIdAndCache($active_categories[0]);  ?>/view:list/type:trainings"><span>Trainings</span></a></li>
      <li<?php if($type == 'products'): ?>  class="u" <?php endif; ?>><a href="<?php print $this->taxonomy_model->getUrlForIdAndCache($active_categories[0]);  ?>/view:list/type:products"><span>Products</span></a></li>
      <li<?php if($type == 'services'): ?>  class="u" <?php endif; ?> ><a href="<?php print $this->taxonomy_model->getUrlForIdAndCache($active_categories[0]);  ?>/view:list/type:services"><span>Services</span></a></li>
      <?php $params = array();
$params['view'] = 'inherit';
$params['commented'] == 'inherit';
$params['voted'] = 'inherit';
$params['curent_page'] = 1;
$params['type'] = 'inherit';
 

$temp = $this->core_model->urlConstruct($base_url = $this_page_url,$params );
?>
      <?php $time_span = array();
$time_span[] = '1 hour';
//$time_span[] = '24 hours';
$time_span[] = '1 day';
$time_span[] = '7 days'; 
$time_span[] = '30 days'; 
$time_span[] = '6 months';
$time_span[] = '1 year';


?>
      <li>
        <select class="select_redirect">
          <option value="<?php print $this->taxonomy_model->getUrlForIdAndCache($active_categories[0]);  ?>/view:list">Most voted:&nbsp;</option>
          <?php foreach($time_span as $item):
  $params = array();
  $params['view'] = 'inherit';
  //$params['commented'] == false;
  $params['commented'] =  'remove';
  $params['voted'] = $item;
  $params['keyword'] =  'inherit';
  $params['ord'] =  'voted';
  $params['curent_page'] = 1;
  $params['type'] = 'inherit';

  $temp = $this->core_model->urlConstruct($base_url = $this->taxonomy_model->getUrlForIdAndCache($active_categories[0]),$params );
  ?>
          <option value="<?php print  $temp ?>"  <?php if ($this->core_model->getParamFromURL ( 'voted' ) == $item) { print ' selected="selected" ' ; }  ?> >&nbsp;-&nbsp;<?php print $item ?></option>
          <?php endforeach; ?>
        </select>
      </li>
      <li>
        <select class='select_redirect'>
          <option value="<?php print $this->taxonomy_model->getUrlForIdAndCache($active_categories[0]);  ?>/view:list">Most commented:&nbsp;</option>
          <?php foreach($time_span as $item): 
$params = array();
  $params['view'] = 'inherit';
$params['commented'] =  $item;


$params['voted'] =  'remove';
$params['keyword'] =  'inherit';
$params['ord'] =  'commented'; 
$params['type'] = 'inherit';
$params['curent_page'] = 1;
$params['type'] = 'inherit';
$temp = $this->core_model->urlConstruct($base_url = $this->taxonomy_model->getUrlForIdAndCache($active_categories[0]),$params );
?>
          <option value="<?php print  $temp ?>"  <?php if ($this->core_model->getParamFromURL ( 'commented' ) == $item) { print ' selected="selected" ' ; }  ?> >&nbsp;-&nbsp;<?php print $item ?></option>
          <?php endforeach; ?>
        </select>
      </li>
    </ul>
  </div>
  <?php endif; ?>
  <?php /*

   H2-to trqbva da e taka


    */ ?>
  
  <!--   <p>Obstacles are things a person sees when he takes his eyes off his goal</p>--> 
</div>
<?php dbg(__FILE__, 1); ?>
