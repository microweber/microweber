<?php  if(is_admin() == false) { mw_error("Must be admin"); }


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
 $current_page_from_url = url_param($paging_param);
 
 
	if( intval( $current_page_from_url) > 0){
	$user_params['current_page'] = intval( $current_page_from_url);

	} elseif(isset($params['current_page'])){
		 $current_page_from_url = $user_params['current_page']  = $params['current_page'];
	}
 
if(isset($params['search'])){
	
	    if (isset($params['search'])) {
            $user_params['keyword'] = $params['search'];
        }

        if (isset($params['keyword'])) {
            $user_params['search_in_fields'] = array('username', 'email', 'first_name', 'last_name');
        }




$user_params['search_by_keyword'] =$params['search'];
$users_per_page = 1000;
$user_params['current_page'] = 1;
}
 
 //$user_params['debug'] = 1;

$user_params['limit'] =   $users_per_page; 

$data = get_users($user_params);


		 
		 $paging_data  = $user_params;
		 $paging_data['page_count']  = true;
 		 $paging = get_users( $paging_data);
		 

 $self_id = user_id();
 
 ?>
<?php if(is_array($data )): ?>

<table cellspacing="0" cellpadding="0" class="mw-ui-table users-list-table mw-ui-row-fixed" width="100%">
	<col>
	<col width="100">
	<col>
	<col width="60">
	<col width="80">
	<col width="65">
	<thead>
		<tr>
			<th><?php _e("Names"); ?></th>
			<th><?php _e("Username"); ?></th>
			<th><?php _e("Email"); ?></th>
			<th><?php _e("Role"); ?></th>
			<th><?php _e("Is Active"); ?></th>
			<th><?php _e("Edit"); ?>
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td><?php _e("Name"); ?></td>
			<td><?php _e("Username"); ?></td>
			<td><?php _e("Email"); ?></td>
			<td><?php _e("Role"); ?></td>
			<td><?php _e("Is Active"); ?></td>
			<td><?php _e("Edit"); ?></td>
		</tr>
	</tfoot>
	<tbody>
		<?php foreach($data  as $item): ?>
		<tr id="mw-admin-user-<?php  print $item['id']; ?>">
			<td width="170">

                <?php if(isset($item['thumbnail']) and trim($item['thumbnail'])!=''): ?>
                    <span class="mw-user-thumb mw-user-thumb-small" style="background-image: url(<?php print $item['thumbnail'] ?>);"></span>
                <?php else: ?>
                    <span class="mw-user-thumb mw-user-thumb-small mw-icon-user"></span>
                <?php endif; ?>
                <span class="mw-user-names">
				<?php if(isset($item['oauth_provider']) and trim($item['oauth_provider'])!=''): ?>
				<a href="<?php  print $item['profile_url']; ?>" target="_blank" title="<?php print ucwords($item['oauth_provider']) ?>" class="mw-icon-<?php print $item['oauth_provider'] ?>"></a>
				<?php endif; ?>
				<?php  print $item['first_name']; ?>
				&nbsp;
				<?php  print $item['last_name']; ?>
				</span></td>
			<td><?php  print $item['username']; ?></td>
			<td title="<?php  print $item['email']; ?>" style="overflow: hidden;text-overflow: ellipsis"><?php  print $item['email']; ?></td>
			<td align="center"><?php  if( $item['is_admin'] == 'y'){ _e("Admin"); } else{_e("User");} ?></td>
			<td align="center"><?php if($item['is_active']=='y'): ?>
				<span class="mw-icon-check" style="float: none"></span>
				<?php else:  ?>
				<span class="mw-icon-unpublish" style="float: none; ">
				<?php endif; ?>
				</span></td>
			<td><?php if($self_id != $item['id']): ?>
				<span class="show-on-hover del-row" title="<?php _e("Delete"); ?>"  onclick="mw_admin_delete_user_by_id('<?php  print $item['id']; ?>')"></span>
				<?php endif; ?>
				<a class="show-on-hover mw-ui-btn mw-ui-btn-invert mw-ui-btn-small"  href="#edit-user=<?php  print $item['id']; ?>"><?php _e("Edit"); ?></a></td>
		</tr>
		<?php endforeach ; ?>
	</tbody>
</table>
<?php if($paging != false and intval($paging) > 1 and isset($paging_param)): ?>
<script type="text/javascript">


                $(document).ready(function () {

                    mw.$('#<?php print $params['id'] ?> .mw-paging').find('a[data-page-number]').unbind('click');
                    mw.$('#<?php print $params['id'] ?> .mw-paging').find('a[data-page-number]').click(function (e) {
                        var pn = $(this).attr('data-page-number');

                        mw.$('#<?php print $params['id'] ?>').attr('paging_param', '<?php print $paging_param ?>');
                        mw.$('#<?php print $params['id'] ?>').attr('current_page', pn)
                        mw.reload_module('#<?php print $params['id'] ?>');


                        return false;
                    });

                });


            </script> 
<?php print paging("num={$paging}&paging_param={$paging_param}&current_page={$current_page_from_url}&class=mw-paging") ?>
<?php endif; ?>
<?php endif; ?>
