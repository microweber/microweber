<div id="comunity-bar">

<?php if(empty($post) and empty($posts)): ?>
    <?php require (ACTIVE_TEMPLATE_DIR.'sidebar_search.php') ?>

<?php endif;  ?>

  <ul>
    <li><a href="<?php print $this->content_model->getContentURLByIdAndCache(258) ; ?>" <?php if($page['id'] == 258): ?> class="active" <?php endif; ?>>Community home</a></li>
    <li><a href="<?php print site_url('userbase') ?>">Users</a></li>
    <li><a href="<?php print site_url('forum') ?>" target="_blank">Forum</a></li>
    <li><a  <?php if($page['id'] == 490): ?> class="active" <?php endif; ?> href="<?php print $this->content_model->taxonomyGetUrlForTaxonomyIdAndCache(1859) ; ?>">Blog</a></li>
  </ul>
  
   <?php $cat = ( $this->content_model->taxonomyGetSingleItemById($active_categories[0])); 
  $view = $this->core_model->getParamFromURL ( 'view' ); 
 ?>
 
 <?php if($cat['taxonomy_value']): ?>
  <h2 class="title left"><?php print $cat['taxonomy_value'] ?></h2>
  <?php else: ?>
  <h2 class="title left">Community</h2>
  <?php endif; ?>
</div>
