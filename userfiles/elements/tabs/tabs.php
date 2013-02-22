<?

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
<? if (!empty($data)): ?>

<ul class="nav nav-tabs">
  <? foreach ($data as $item): ?>
  <? if(!isset($show_fields) or  $show_fields== false or in_array('title', $show_fields)): ?>
  <li><a href="#tab-<? print url_title($item['title']); ?>" data-toggle="tab"><? print $item['title'] ?></a></li>
  <? endif; ?>
  <?  endforeach; ?>
</ul>
<div class="tab-content">
  <? foreach ($data as $item): ?>
  <? if(!isset($show_fields) or  $show_fields== false or in_array('content', $show_fields)): ?>
  <div class="tab-pane active" id="tab-<? print url_title($item['title']); ?>"><? print $item['content'] ?></div>
  <? endif; ?>
  <?  endforeach; ?>
</div>
<? endif; ?>
 