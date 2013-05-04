<?
if(!isset($data)){
  $default_data = array(
    "title" => "Item title",
    "description" =>  lipsum(),
    "image" => ''

);
  $data = array_fill(0, 10,$default_data );

}
$counter = 0;
 ?>
<?php if (!empty($data)): ?>

<table class="responsive table">
  <tbody>
    <?php foreach ($data as $item): ?>
    <tr>
      <?php if(isset($show_fields) and $show_fields != false and in_array('thumbnail', $show_fields)): ?>
      <td><img src="<?php print thumbnail($item['image'], 400,150); ?>"  class="element element-image layout-img"></td>
      <?php endif; ?>
      <?php if(!isset($show_fields) or  $show_fields== false or in_array('title', $show_fields)): ?>
      <td><?php print $item['title'] ?></td>
      <?php endif; ?>
      <?php if(isset($show_fields) and  $show_fields != false and in_array('created_on', $show_fields)): ?>
      <td><?php print $item['created_on'] ?></td>
      <?php endif; ?>
      <?php if(isset($show_fields) and  $show_fields != false and in_array('description', $show_fields)): ?>
      <td><?php print $item['description'] ?></td>
      <?php endif; ?>
      <?php if(isset($show_fields) and  $show_fields != false and in_array('read_more', $show_fields)): ?>
      <td><a href="<?php print $item['link'] ?>" class="btn btn-success blog-fleft">
        <?php $read_more_text ? print $read_more_text : print 'Continue Reading'; ?>
        </a></td>
      <?php endif; ?>
    </tr>
    <?php $counter++; endforeach; ?>
  </tbody>
</table>
<?php endif; ?>
