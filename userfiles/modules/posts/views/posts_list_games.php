<? foreach($posts as $the_post): ?>

<div class="game_item" onclick="window.location.href='<?php print post_link($the_post['id']); ?>'"> <span class="gametitle"><?php print $the_post['content_title']; ?></span> <a title="<?php print $the_post['content_title']; ?>" href="<?php print post_link($the_post['id']) ; ?>" class="img" style="background-image: url('<?php print thumbnail(array('id' => $the_post['id'], 'size' => 150));  ?>')"> </a> </div>
<? endforeach; ?>
