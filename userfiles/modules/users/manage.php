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
  <? foreach($data  as $item): ?>
  <div>
   
  Username: <?  print $item['username']; ?><br />
  email: <?  print $item['email']; ?><br />
  first_name: <?  print $item['first_name']; ?><br />
  last_name: <?  print $item['last_name']; ?><br />
  is_active: <?  print $item['is_active']; ?><br />
is_admin: <?  print $item['is_admin']; ?><br />
  <a href="#edit-user=<?  print $item['id']; ?>" class="mw-ui-btn mw-ui-btn-small">Edit user</a>
 
</div>
 <? endforeach ; ?>
<? endif; ?>