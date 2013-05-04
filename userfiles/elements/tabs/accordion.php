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
<?php if (!empty($data)): ?>
<div class="accordion">
<?php foreach ($data as $item): ?>

<div class="accordion-group">
  <?php if(!isset($show_fields) or  $show_fields== false or in_array('title', $show_fields)): ?>
  <div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" href="#accordion-<?php print url_title($item['title']).$counter; ?>"><?php print $item['title'] ?></a></div>
  <div id="accordion-<?php print url_title($item['title']).$counter; ?>" class="accordion-body collapse">
    <div class="accordion-inner"> <?php if(isset($show_fields) and isset($item['content']) and  $show_fields!= false and in_array('content', $show_fields)): ?><?php print $item['content'] ?><?php else: ?><?php print $item['description'] ?><?php endif; ?></div>
    <?php endif; ?>
  </div>
</div>
<?php $counter++; endforeach; ?>
</div>
<?php endif; ?>
