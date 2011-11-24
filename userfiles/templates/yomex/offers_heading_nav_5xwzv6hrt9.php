<div class="heading">
<h1>


<?php $nav_options = array();
$nav_options['start_from_category'] = $page['content_subtype_value'];
$nav1 =  $this->content_model->getBreadcrumbsByURLAsArray(false, false, $nav_options);
 ?> 
<?php if(!empty( $nav1) and !empty( $post)): ?>
 <?php $i = 0;  foreach($nav1 as $n): ?>
 <?php if($nav1[$i+1] != false): ?>
<a href="<?php print $n['url']; ?>"><?php print $n['title']; ?></a>  <?php if($nav1[$i+2] != false): ?>/ <?php endif; ?>
<?php endif; ?>
  <?php $i ++; endforeach; ?>
<?php endif; ?>





<?php if(!empty( $nav1) and empty( $post)): ?>
 <?php $i = 0;  foreach($nav1 as $n): ?>

<a href="<?php print $n['url']; ?>"><?php print $n['title']; ?></a>  <?php if($nav1[$i+1] != false): ?>/ <?php endif; ?>

  <?php $i ++; endforeach; ?>
<?php endif; ?>

 </h1>

</div>