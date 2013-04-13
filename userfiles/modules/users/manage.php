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
$users_per_page = 100;
$paging_param = $params['id'].'_page';
 $curent_page_from_url = url_param($paging_param);
 
 
	if( intval( $curent_page_from_url) > 0){
	$user_params['curent_page'] = intval( $curent_page_from_url);

	}
 
if(isset($params['search'])){
$user_params['search_by_keyword'] =$params['search'];
$users_per_page = 1000;
$user_params['curent_page'] = 1;
}

 //$user_params['debug'] = 1;

$user_params['limit'] =   $users_per_page; 
 
$data = get_users($user_params);


		 
		 $paging_data  = $user_params;
		 $paging_data['page_count']  = true;
 		 $paging = get_users( $paging_data);
		 
		 
 
 ?>

 
 <? if(isarr($data )): ?>

<table cellspacing="0" cellpadding="0" class="mw-ui-admin-table users-list-table" width="100%">
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
          <td >
          
           <span class="mw-user-thumb mw-user-thumb-small">
           <?php if(isset($item['thumbnail']) and trim($item['thumbnail'])!=''): ?>
           <img style="vertical-align:middle" src="<? print $item['thumbnail'] ?>"  height="24" width="24" />  
		   
		   <?php endif; ?>
            </span>
           
           
		  
          
		  <span class="mw-user-names">
          
          
          
		   <?php if(isset($item['oauth_provider']) and trim($item['oauth_provider'])!=''): ?>
           <a href="<?  print $item['profile_url']; ?>" target="_blank" title="<? print ucwords($item['oauth_provider']) ?>" class="mw-social-ico-<? print $item['oauth_provider'] ?>"></a>
		   
		   
		   
		   <?php endif; ?><?  print $item['first_name']; ?>&nbsp;<?  print $item['last_name']; ?>
          </span>




          </td>
          <td><?  print $item['username']; ?></td>
          <td><?  print $item['email']; ?></td>
          <td align="center"> <?  if( $item['is_admin'] == 'y'){_e("Admin");} else{_e("User");} ?></td>
          <td align="center"><?php if($item['is_active']=='y'): ?><span class="ico icheck" style="float: none"></span><?php else:  ?><span class="ico iRemove" style="float: none"><?php endif; ?></span></td>
          <td>
            <a class="mw-ui-admin-table-show-on-hover mw-ui-btn mw-ui-btn-small" onclick="mw.url.windowHashParam('edit-user', '<?  print $item['id']; ?>');return false;" href="#edit-user=<?  print $item['id']; ?>">Edit</a>
            <span class="mw-ui-admin-table-show-on-hover del-row"  onclick="mw_admin_delete_user_by_id('<?  print $item['id']; ?>')"></span>
          </td>
      </tr>
 <? endforeach ; ?>
  </tbody>
</table>
 <? if($paging != false and intval($paging) > 1 and isset($paging_param)): ?>
    <? print paging("num={$paging}&paging_param={$paging_param}&curent_page={$curent_page_from_url}&class=mw-paging") ?>
    <? endif; ?>
<? endif; ?>




