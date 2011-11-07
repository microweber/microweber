
<div class="box radius">
  <div class="box_header radius_t">
    <table border="0" cellspacing="0" width="100%" cellpadding="0">
      <tr>
        <td><h2>Manage users</h2></td>
        <td><a class="btn right" href="<? print site_url('admin/action:users/edit_user:new') ?>">Add new user</a></td>
      </tr>
    </table>
  </div>
  <div class="box_content">
    <? if(url_param('edit_user')): ?>
    <mw module="admin/users/edit" user_id="<? print url_param('edit_user') ?>" />
    <? else: ?>
    <mw module="admin/users/list" />
    <? endif; ?>
  </div>
</div>
