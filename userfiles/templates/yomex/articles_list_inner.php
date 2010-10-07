<?php if(!empty($posts)):  ?>
      <?php $i = 0;   foreach ($posts as $the_post): ?>
      <div class="vaprosi post<?php if(($the_post['is_special']) == 'y'):  ?> cek<?php endif; ?>" style="margin-top:20px;">
        <?php //$thumb = $this->content_model->contentGetThumbnailForContentId($the_post['id'], 250);
	  $more = $this->core_model->getCustomFields($table = 'table_content', $id = $the_post['id']);	  ?>
        <h3><a href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>" title="<?php print addslashes($the_post['content_title']); ?>"><?php print ($the_post['content_title']); ?></a></h3>
        <p>
          <?php if($the_post['content_description'] != ''): ?>
          <?php print (character_limiter($the_post['content_description'], 120, '...')); ?>
          <?php else: ?>
          <?php print character_limiter($the_post['content_body_nohtml'], 120, '...'); ?>
          <?php endif; ?>
          <a href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>" title="<?php print addslashes($the_post['content_title']); ?>" class="button-more">прочети повече</a> </p>
        <div class="c" style="padding: 0;"></div>
      </div>
      <?php $i++; endforeach; ?>
      <?php else : ?>
      <div class="post">
        <p> Няма намерени резултати. </p>
      </div>
      <?php endif; ?> 