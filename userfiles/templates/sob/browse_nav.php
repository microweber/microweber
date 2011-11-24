<?php dbg(__FILE__); ?>
<?php $type = $this->core_model->getParamFromURL ( 'type' ); ?>
<div id="comunity-bar">
 
  <?php $active_categories123 = count($active_categories);
  $cat = ( $this->taxonomy_model->getSingleItem($active_categories[$active_categories123 - 1])); 
  $view = $this->core_model->getParamFromURL ( 'view' ); 
 ?>
  <?php if($cat['taxonomy_value']): ?>
  <h2 class="title left">
   <?php if(strtolower($cat['taxonomy_value']) == 'categories'): ?>
  All content
  <?php else: ?>
   <?php print $cat['taxonomy_value'] ?>
  <?php endif; ?>
  </h2>
  <?php else: ?>
  <h2 class="title left">Browse</h2>
  <?php endif; ?>
  
   <ul>
    <li <?php if($type == 'all'): ?> class="active" <?php endif; ?> ><a  href="<?php print site_url('browse/type:all'); ?>">All</a></li>
    <li <?php if(($type == 'none') or (!$type)): ?> class="active" <?php endif; ?> ><a    href="<?php print site_url('browse/type:none'); ?>">Articles</a></li>
    <li  <?php if($type == 'trainings'): ?> class="active" <?php endif; ?> ><a   href="<?php print site_url('browse/type:trainings'); ?>">Trainings</a></li>
    <li  <?php if($type == 'services'): ?> class="active" <?php endif; ?> ><a  href="<?php print site_url('browse/type:services'); ?>">Services</a></li>
    <li <?php if($type == 'products'): ?> class="active" <?php endif; ?>><a   href="<?php print site_url('browse/type:products'); ?>">Products</a></li>
  </ul>
  
</div>
<div class="c" style="padding-bottom: 12px">&nbsp;</div>
<?php dbg(__FILE__, 1); ?>
