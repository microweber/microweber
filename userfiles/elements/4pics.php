<?
if(!isset($data)){
  $default_data = array(
    "title" => "Picture title",
    "description" =>  lipsum(),
    "image" => ''

);
  $data = array_fill(0, 3,$default_data );

}
$counter = 4;
 ?>

 <? if (!empty($data)): ?>
<? foreach ($data as $item): ?>
<? if ($counter % 2 == 0): ?>

<div class="mw-row">
  <? endif; ?>
   <div class="mw-col" style="width:50%" >
<div class="thumbnail">
        <? if(!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
        <img src="<? print thumbnail($item['image'], 400,150); ?>"  class="element element-image layout-img">
        <? endif; ?>

          <? if(isset($show_fields) and  $show_fields != false and in_array('title', $show_fields)): ?>
          <h2 class="element content-item-title"><? print $item['title'] ?></h2>
          <? endif; ?>
          <? if(isset($show_fields) and  $show_fields != false and in_array('created_on', $show_fields)): ?>
          <div class="post-meta">Date: <? print $item['created_on'] ?></div>
          <? endif; ?>
          <? if(isset($show_fields) and  $show_fields != false and in_array('description', $show_fields)): ?>
          <p class="element layout-paragraph"><? print $item['description'] ?></p>
          <? endif; ?>
          <? if(isset($show_fields) and  $show_fields != false and in_array('read_more', $show_fields)): ?>
          <a href="<? print $item['link'] ?>" class="btn btn-success blog-fleft">
          <? $read_more_text ? print $read_more_text : print 'Continue Reading'; ?>
          </a>
          <? endif; ?>


    </div>

  </div>
  <? if ($counter+2 % 2 == 0): ?>
</div>
<? endif; ?>
<? $counter++; endforeach; ?>
<? endif; ?>



