 
 <? $author = CI::model('users')->getUserById( $data); ?>
    <? $thumb = CI::model('users')->getUserThumbnail( $author['id'], 70); ?>

 <li> 
 
 
 
 
 <a href="<? print user_link($author['id']); ?>"> <span style="background-image: url('<?  print $thumb; ?>');"></span> <strong><?  print user_name($author['id']) ; ?></strong> </a> </li>