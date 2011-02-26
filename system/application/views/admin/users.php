Manage users

 

 <? if(url_param('edit_user')): ?>  
 <mw module="admin/users/edit" user_id="<? print url_param('edit_user') ?>" />
 <? else: ?>
 
 <mw module="admin/users/manage" />
  <? endif; ?>

