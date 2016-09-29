<?php

/*

type: layout

name: Default

description: Default Twitter Feed

*/

?>

<?php if($items): ?>
<?php foreach($items as $tweet): ?>


<div class="twitter-feed-item">

<a href="<?php print $tweet['url']; ?>" target="_blank"><span class="twitter-feed-item-user"><?php print $tweet['name']; ?></span><?php print $tweet['text']; ?></a>
<hr>
</div>
<?php endforeach; ?>
<?php endif; ?>
