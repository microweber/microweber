<?php
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

 <?php if (!empty($data)): ?>
<?php foreach ($data as $item): ?>
<?php if ($counter % 2 == 0): ?>

<div class="mw-row">
  <?php endif; ?>
   <div class="mw-col" style="width:50%" >
<div class="thumbnail">
        <?php if(!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
        <img src="<?php print thumbnail($item['image'], 400,150); ?>"  class="element element-image layout-img">
        <?php endif; ?>

          <?php if(isset($show_fields) and  $show_fields != false and in_array('title', $show_fields)): ?>
          <h2 class="element content-item-title"><?php print $item['title'] ?></h2>
          <?php endif; ?>
          <?php if(isset($show_fields) and  $show_fields != false and in_array('created_on', $show_fields)): ?>
          <div class="post-meta">Date: <?php print $item['created_on'] ?></div>
          <?php endif; ?>
          <?php if(isset($show_fields) and  $show_fields != false and in_array('description', $show_fields)): ?>
          <p class="element layout-paragraph"><?php print $item['description'] ?></p>
          <?php endif; ?>
          <?php if(isset($show_fields) and  $show_fields != false and in_array('read_more', $show_fields)): ?>
          <a href="<?php print $item['link'] ?>" class="btn btn-success blog-fleft">
          <?php $read_more_text ? print $read_more_text : print 'Continue Reading'; ?>
          </a>
          <?php endif; ?>


    </div>

  </div>
  <?php if ($counter+2 % 2 == 0): ?>
</div>
<?php endif; ?>
<?php $counter++; endforeach; ?>
<?php endif; ?>



