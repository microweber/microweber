<?php

if(!isset($data)){
  $default_data = array(
    "title" => "Tab title",
    "description" =>  lipsum(),
    "image" => ''

);
  $data = array_fill(0, 10,$default_data );

}
$counter = 0;
 ?>
<?php if (!empty($data)): ?>

<ul class="nav nav-tabs">
  <?php foreach ($data as $item): ?>
  <?php if(!isset($show_fields) or  $show_fields== false or in_array('title', $show_fields)): ?>
  <li><a href="#tab-<?php print url_title($item['title']); ?>" data-toggle="tab"><?php print $item['title'] ?></a></li>
  <?php endif; ?>
  <?php  endforeach; ?>
</ul>
<div class="tab-content">
  <?php foreach ($data as $item): ?>
  <?php if(!isset($show_fields) or  $show_fields== false or in_array('content', $show_fields)): ?>
  <div class="tab-pane active" id="tab-<?php print url_title($item['title']); ?>"><?php print $item['content'] ?></div>
  <?php endif; ?>
  <?php  endforeach; ?>
</div>
<?php endif; ?>
 