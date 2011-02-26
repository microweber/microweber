   <?php if(!empty($comments)) : ?>
  <?php foreach($comments as $item): ?>
  <?php $author = CI::model('users')->getUserById( $item['created_by']); ?>
  <?php $thumb = CI::model('users')->getUserThumbnail( $author['id'], 45); ?>
  <?php //  print $thumb; ?>
  <div class="comment"><a style="background-image: url('<?php print $thumb; ?>');" href="<?php print site_url('userbase/action:profile/username:'); ?><?php print $author['username']; ?>" class="img">
    <?php if($thumb != ''): ?>
    <?php else: ?>
       <?php endif; ?>
    </a>
    <h3 class="post-title"><a href="<?php /* $item['comment_website'] ? print prep_url($item['comment_website']) :  print '#'; */ print site_url('userbase/action:profile/username:'); ?><?php print $author['username'];   ?>">
      <?php $item['comment_name'] ? print $item['comment_name']:  print $author['first_name'] . ' '. $author['last_name']  ; ?>
      </a></h3>
    <span class="date"><?php print date(DATETIME_FORMAT, strtotime($item['created_on'])) ?></span>
   <br><?php print ($item['comment_body']); ?>
    </div>
  <?php endforeach ; ?>
<?php endif; ?>