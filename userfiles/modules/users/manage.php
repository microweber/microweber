<?  if(is_admin() == false) { error("Must be admin"); }


$user_params = array();
if(isset($params['sortby'])){
$user_params['order_by'] =$params['sortby'];
}
if(isset($params['is_admin'])){
$user_params['is_admin'] =$params['is_admin'];
}
if(isset($params['is_active'])){
$user_params['is_active'] =$params['is_active'];
}

if(isset($params['search'])){
$user_params['search_by_keyword'] =$params['search'];
}

//$user_params['debug'] = 1;
$data = get_users($user_params);

 
 ?><? if(isarr($data )): ?>

<table cellspacing="0" cellpadding="0" class="mw-ui-admin-table" width="100%">
  <thead>
      <tr>
          <th>Names</th>
          <th>Username</th>
          <th>Email</th>
          <th>Role</th>
          <th>Is Active</th>
          <th>Edit </th>
      </tr>
  </thead>
  <tfoot>
      <tr>
          <td>Name</td>
          <td>Username</td>
          <td>Email</td>
          <td>Role</td>
          <th>Is Active</th>
          <th>Edit </th>
      </tr>
  </tfoot>
  <tbody>
  <? foreach($data  as $item): ?>
     <tr id="mw-admin-user-<?  print $item['id']; ?>">
          <td><?  print $item['first_name']; ?>&nbsp;<?  print $item['last_name']; ?></td>
          <td><?  print $item['username']; ?></td>
          <td><?  print $item['email']; ?></td>
          <td align="center"> <?  if( $item['is_admin'] == 'y'){_e("Admin");} else{_e("User");} ?></td>
          <td align="center"><?php if($item['is_active']=='y'): ?><span class="ico icheck" style="float: none"></span><?php else:  ?><span class="ico iRemove" style="float: none"><?php endif; ?></span></td>
          <td>
            <a class="mw-ui-admin-table-show-on-hover mw-ui-btn" onclick="mw.url.windowHashParam('edit-user', '<?  print $item['id']; ?>');return false;" href="#edit-user=<?  print $item['id']; ?>">Edit</a>
            <span class="mw-ui-admin-table-show-on-hover del-row"></span>
          </td>
      </tr>
 <? endforeach ; ?>
  </tbody>
</table>
<? endif; ?>




