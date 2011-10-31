<?

//var_dump($params);
  shuffle($posts['posts']);
 
//var_dump($posts);

 $lim = 1;
?>
<? $i =0; foreach ($posts['posts'] as $post)  : ?>
<? if($i < $lim): ?>

<div class="author-content2">
 <h2 class="post_title_small2"> <a href="<? print  post_link($post['id']); ?>"><? print  $post['content_title']; ?></a></h2>
 <br>
  <? $th = thumbnail($post['id'], 100); ?>
  <a href="<? print  post_link($post['id']); ?>" class="left"><span></span><img src="<? print $th ?>" align="left" hspace="10" class="img_pad" border="0" /></a>
 
  
  <p><? print  character_limiter($post['content_body_nohtml'], 100); ?> </p>
  <a href="<? print  post_link($post['id']); ?>" class="read_more_link">Прочети повече</a> </div>
<? //var_dump($i); ?>
<? endif; ?>
<?  $i++; endforeach; ?>
