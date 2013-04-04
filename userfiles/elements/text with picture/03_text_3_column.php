<?php $rand = uniqid();  ?>
<?
if(!isset($data)){
	$default_data = array( 
    "title" => "Three Columns with Pictures", 
    "description" =>  lipsum(), 
    "image" => '', 
    "red" 
);
	$data = array_fill(0, 3,$default_data );
//d($data );
}
$counter = 3;
 ?>
<? if (!empty($data)): ?>
<? foreach ($data as $item): ?>
<? if ($counter % 3 == 0): ?>

<div class="mw-row" id='<?php print $rand.$counter; ?>'>
  <? endif; ?>
   <div class="mw-col" style="width:33.33%" >
    <div class="mw-col-container">
      <div class="element">
        <div class="thumbnail edit">
          <? if(!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
          <img src="<? print thumbnail($item['image'], 290, 150); ?>" alt="">
          <? endif; ?>
          <div class="caption">
            <? if(!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
            <h2 class="element content-item-title"><? print $item['title'] ?></h2>
            <? endif; ?>
            <? if(isset($show_fields) and  $show_fields != false and in_array('created_on', $show_fields)): ?>
            <div class="post-meta">Date: <? print $item['created_on'] ?></div>
            <? endif; ?>
            <? if(!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
            <p class="element layout-paragraph"><? print $item['description'] ?></p>
            <? endif; ?>
            <? if(isset($show_fields) and  $show_fields != false and in_array('read_more', $show_fields)): ?>
            <a href="<? print $item['link'] ?>" class="btn btn-success blog-fleft">
            <? $read_more_text ? print $read_more_text : print 'Continue Reading'; ?>
            </a>
            <? endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <? if ($counter+3 % 3 == 0): ?>
</div>
<? endif; ?>
<? $counter++; endforeach; ?>
<? endif; ?>

