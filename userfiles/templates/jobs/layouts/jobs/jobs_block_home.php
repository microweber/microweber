<?
$posts = get_posts();
//echo count($posts);
foreach($posts as $post): ?>

<div class="exebox">
  <div class="exe_tit"><a href="<? print post_link($post['id']) ?>" class="exe_tit"><? print $post['content_title'] ?></a></div>
  <div class="exe_content" style="text-align:justify"><? print $post['content_body'] ?></div>
  <div class="exe_details"> Date Posted:&nbsp;&nbsp;&nbsp;<span class="blue"><? print $post['created_on'] ?></span><br />
      <?  $v = custom_field_value($post['id'], $field_name = 'Location') ;?>
      <? if($v != false): ?>
      Location:&nbsp;&nbsp;&nbsp;<span class="blue"><? print $v; ?> </span> <br />
      <? endif; ?>
      <?  $v = custom_field_value($post['id'], $field_name = 'sallary-range') ;?>
      <? if($v != false): ?>
      Salary:&nbsp;&nbsp;&nbsp;<span class="blue"><? print $v; ?></span> <br />
      <? endif; ?>
    </div>
    <div class="apply_but xright" style="margin-right: 20px;"><a href="<? print post_link($post['id']) ?>"><img src="<? print TEMPLATE_URL ?>images/apply_but.jpg" alt="apply" border="0" /></a></div>
    <div class="readmore_but xright"><a href="<? print post_link($post['id']) ?>"><img src="<? print TEMPLATE_URL ?>images/readmore_but.jpg" alt="read more" border="0" /></a></div>

</div>
<? endforeach;  ?>
