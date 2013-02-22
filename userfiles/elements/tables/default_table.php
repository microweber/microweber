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
<? if (!empty($data)): ?>

<table class="responsive table">
  <tbody>
    <? foreach ($data as $item): ?>
    <tr>
      <? if(isset($show_fields) and $show_fields != false and in_array('thumbnail', $show_fields)): ?>
      <td><img src="<? print thumbnail($item['image'], 400,150); ?>"  class="element element-image layout-img"></td>
      <? endif; ?>
      <? if(!isset($show_fields) or  $show_fields== false or in_array('title', $show_fields)): ?>
      <td><? print $item['title'] ?></td>
      <? endif; ?>
      <? if(isset($show_fields) and  $show_fields != false and in_array('created_on', $show_fields)): ?>
      <td><? print $item['created_on'] ?></td>
      <? endif; ?>
      <? if(isset($show_fields) and  $show_fields != false and in_array('description', $show_fields)): ?>
      <td><? print $item['description'] ?></td>
      <? endif; ?>
      <? if(isset($show_fields) and  $show_fields != false and in_array('read_more', $show_fields)): ?>
      <td><a href="<? print $item['link'] ?>" class="btn btn-success blog-fleft">
        <? $read_more_text ? print $read_more_text : print 'Continue Reading'; ?>
        </a></td>
      <? endif; ?>
    </tr>
    <? $counter++; endforeach; ?>
  </tbody>
</table>
<? endif; ?>
