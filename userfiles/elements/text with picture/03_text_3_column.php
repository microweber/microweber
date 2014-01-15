<?php $rand = uniqid();  ?>
<?php
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
<?php if (!empty($data)): ?>
<?php foreach ($data as $item): ?>
<?php if ($counter % 3 == 0): ?>

<div class="mw-row" id='<?php print $rand.$counter; ?>'>
  <?php endif; ?>
   <div class="mw-col" style="width:33.33%" >
    <div class="mw-col-container">
      <div class="element">
        <div class="thumbnail edit">
          <?php if(!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
          <img src="<?php print thumbnail($item['image'], 290, 150); ?>" alt="">
          <?php endif; ?>
          <div class="caption">
            <?php if(!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
            <h2 class="element content-item-title"><?php print $item['title'] ?></h2>
            <?php endif; ?>
            <?php if(isset($show_fields) and  $show_fields != false and in_array('created_on', $show_fields)): ?>
            <div class="post-meta">Date: <?php print $item['created_on'] ?></div>
            <?php endif; ?>
            <?php if(!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
            <p class="element layout-paragraph"><?php print $item['description'] ?></p>
            <?php endif; ?>
            <?php if(isset($show_fields) and  $show_fields != false and in_array('read_more', $show_fields)): ?>
            <a href="<?php print $item['link'] ?>" class="btn btn-default btn-success blog-fleft">
            <?php $read_more_text ? print $read_more_text : print 'Continue Reading'; ?>
            </a>
            <?php endif; ?>
          </div>
        </div>
      </div>

    </div>
  </div>
  <?php if ($counter+3 % 3 == 0): ?>
</div>
<?php endif; ?>
<?php $counter++; endforeach; ?>
<?php endif; ?>

