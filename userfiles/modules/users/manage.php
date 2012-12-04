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

<table width="950" cellspacing="0" cellpadding="0" class="mw-ui-admin-table">
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
     <tr>
          <td><?  print $item['first_name']; ?>&nbsp;<?  print $item['last_name']; ?></td>
          <td><?  print $item['username']; ?></td>
          <td><?  print $item['email']; ?></td>
          <td> <?  if( $item['is_admin'] == 'y'){_e("Admin");} else{_e("User");} ?></td>
          <td align="center"><?php if($item['is_active']=='y'): ?><span class="ico icheck"></span><?php else:  ?><span class="ico iRemove"><?php endif; ?></span></td>
          <td><a class="mw-ui-admin-table-show-on-hover mw-ui-btn" href="#edit-user=<?  print $item['id']; ?>">Edit</a></td>
      </tr>
 <? endforeach ; ?>

  </tbody>
</table>
<? endif; ?>




