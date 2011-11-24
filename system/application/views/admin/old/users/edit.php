<?php //var_dump($form_values);

 ?>
 <h2>Edit user id: <?php print $form_values['id']; ?> </h2>
 
<form action="<?php print site_url('admin/users/edit') ?>" method="post">
<input type="hidden" name="savedata" value="yes">
 
 <table border="1">
  
   <?php foreach($form_values as $k => $v): ?>


  <tr>
   
    <th scope="row"><?php print $k ?></th>
    <td>
     <?php if($k == 'id')  : ?>
  <input type="hidden" name="<?php print $k ?>" value="<?php print $v ?>">
  <?php else : ?>
     <textarea name="<?php print $k ?>" cols="30" rows="5"><?php print $v ?></textarea>
     <?php endif; ?>
 </td>
 
  </tr>
  
  
  <?php endforeach; ?>
</table> 
<input name="save" type="submit" value="save">
</form>
