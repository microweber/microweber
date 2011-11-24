 <?php dbg(__FILE__); ?>
    <h2 class="title">How to?</h2>
    <br />
  
  <?php $related = array();
$related['selected_categories'] = array(2312);
$limit[0] = 0;
$limit[1] = 5;
$related_posts = $this->content_model->getContentAndCache($related, false,$limit,  $count_only = false, $short = true, $only_fields = array ('id', 'content_type', 'content_url', 'content_title', 'content_description',  'content_body', 'created_by' ) );
?>
      <?php if(!empty($related_posts)): ?>
       <ul class="how-to-nav">
      <?php foreach($related_posts as $the_post): ?>
      <li><a href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>"><?php print $the_post['content_title']; ?></a></li>
      <?php endforeach; ?>
        </ul>
      
      <?php else: ?>
      Sorry, we don't have any how-to's yet!
      <?php endif; ?>
   <?php dbg(__FILE__, 1); ?>