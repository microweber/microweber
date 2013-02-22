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
<div class="accordion">
<? foreach ($data as $item): ?>

<div class="accordion-group">
  <? if(!isset($show_fields) or  $show_fields== false or in_array('title', $show_fields)): ?>
  <div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" href="#accordion-<? print url_title($item['title']); ?>"><? print $item['title'] ?></a></div>
  <div id="accordion-<? print url_title($item['title']); ?>" class="accordion-body collapse">
    <div class="accordion-inner"><? print $item['content'] ?></div>
    <? endif; ?>
  </div>
</div>
<?  endforeach; ?>
</div>
<? endif; ?>
