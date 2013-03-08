<?   $users_last5 = get_visits('last5');
$requests_num = get_visits('requests_num');
?><div id="users_online"><h2>Users Online</h2>
  <div class="users_online" id="real_users_online">
    <? $users_online = get_visits('users_online'); print intval($users_online); ?>
  </div>  </div>
  <div id="visits_info_table">
  <h2><?php _e("User Info"); ?><? if($requests_num != false): ?> <small>(<? print $requests_num ?> <?php _e("req/s"); ?>)</small><? endif; ?></h2>
  <?

    ?>
  <? if(!empty($users_last5)): ?>
  <table border="0" cellspacing="0" cellpadding="0" class="stats_table">
    <thead>
      <tr>
        <th scope="col">Date</th>
        <? if(function_exists('ip2country')): ?>
        <th scope="col">Country</th>
        <? endif; ?>
        <th scope="col">IP</th>
        <th scope="col">Last page</th>
        <th scope="col">Page views</th>
      </tr>
    </thead>
    <tbody>
      <? $i=0; foreach($users_last5 as $item) : ?>
      <tr>
        <td><? print $item['visit_date'] ?> <? print $item['visit_time'] ?></td>
        <? if(function_exists('ip2country')): ?>
        <td><?  //print ip2country($item['user_ip']); ?></td>
        <? endif; ?>
        <td><? print $item['user_ip'] ?></td>
        <td><? print $item['last_page'] ?></td>
        <td><? print $item['view_count'] ?></td>
      </tr>
      <? $i++; endforeach; ?>
    </tbody>
  </table>
  <? endif; ?>
</div>