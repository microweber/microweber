<h2 style="font-size: 20px;" class="blue_title">Select Menus</h2>
<br />

<table cellpadding="0" cellspacing="0">
  <?php  $this->firecms = get_instance();  $menus = $this->firecms->content_model->getMenus(array('item_type' => 'menu')); ?>
  <?php foreach($menus as $item): ?>
  <?php $is_checked = false; $is_checked = $this->firecms->content_model->content_helpers_IsContentInMenu($form_values['id'],$item['id'] ); ?>
  <tr>
  <td width="20px"><input style="position: relative;top:2px;*top:0px;" name="menus[]" type="checkbox" <?php if($is_checked  == true): ?> checked="checked"  <?php endif; ?> value="<?php print $item['id'] ?>" />
    </td>
    <td><label class="lbl" style="padding: 0px"><?php print $item['item_title'] ?></label></td>

  </tr>
  <?php endforeach; ?>
</table>
<br>
<br>
<br>
<br>