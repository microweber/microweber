



<? if(!empty($data)): ?>

<ul class="layouts-list" style="left:0px;">
  <? foreach($data as $item): ?>
  <li><a <? if($active == $item['filename']) : ?>  class="active" <? endif; ?>  href="javascript:user_content_post_swith_layout_style('<? print $item['filename'] ?>')"> <span style="background-image: url('<? print $item['screenshot']; ?>')">&nbsp;</span> <strong>Style name:</strong> <em><? print $item['name'] ?></em>
    <i><? print $item['description'] ?></i> <samp class="layout-hover">&nbsp;</samp><samp class="layout-active">&nbsp;</samp> </a> </li>
  <? endforeach;  ?>
</ul>
<? //var_dump($active); ?>





 

<? else: ?>
Sorry, no styles are defined.
<?  endif;?>