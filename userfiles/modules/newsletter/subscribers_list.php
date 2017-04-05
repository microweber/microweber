<?php only_admin_access(); ?>
<?php 
$subscribers_params=array();
$subscribers_params['no_limit'] = true;
$subscribers_params['order_by'] = "created_at desc";
$subscribers = newsletter_get_subscribers($subscribers_params); 
?>
<?php if($subscribers): ?>

<table width="100%" border="0" class="mw-ui-table" style="table-layout:fixed">
  <thead>
    <tr>
      <th>Date</th>
      <th>Name</th>
      <th>Email</th>
      <th>Subscribed</th>
      <th width="140px">&nbsp;</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($subscribers as $subscriber): ?>
    <tr id="newsletter-subscriber-<?php print $subscriber['id']; ?>">
      <td><?php print $subscriber['created_at']; ?></td>
      <td><input type="text" class="mw-ui-field" name="name" value="<?php print $subscriber['name']; ?>" /></td>
      <td><input type="email" class="mw-ui-field" name="email" value="<?php print $subscriber['email']; ?>" /></td>
      <td><select class="mw-ui-field mw-ui-field-medium" name="is_subscribed">
          <option value="1" <?php if($subscriber['is_subscribed']): ?>  selected <?php endif; ?> >Yes</option>
          <option value="0" <?php if(!$subscriber['is_subscribed']): ?>  selected <?php endif; ?> >No</option>
        </select></td>
      <td><input type="hidden" name="id" value="<?php print $subscriber['id']; ?>" />
        <button class="mw-ui-btn" onclick="edit_subscriber('#newsletter-subscriber-<?php print $subscriber['id']; ?>')">Save</button>
        <a class="mw-ui-btn mw-ui-btn-icon" href="javascript:;" onclick="delete_subscriber('<?php print $subscriber['id']; ?>')"> <span class="mw-icon-bin"></span> </a></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>
