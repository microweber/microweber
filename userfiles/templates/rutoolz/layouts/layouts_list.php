
<ul class="layouts-list" style="left:0px;">
  <? foreach($data as $layout): ?>
  <li><a <? if($active == $layout['dir']) : ?>  class="active" <? endif; ?>  href="javascript:user_content_post_switch_layout('<? print $layout['dir'] ?>')"> <span style="background-image: url('<? print $layout['screenshots'][0]; ?>')">&nbsp;</span> <strong>Template name:</strong> <em><? print $layout['name'] ?></em>
    <i><? print $layout['description'] ?></i> <samp class="layout-hover">&nbsp;</samp><samp class="layout-active">&nbsp;</samp> </a> </li>
  <? endforeach;  ?>
</ul>
<? // var_dump($active); ?>
