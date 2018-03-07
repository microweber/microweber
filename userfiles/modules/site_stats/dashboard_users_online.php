 <div id="users_online"><h2><?php _e("Users Online"); ?></h2>
  <div class="users_online" id="real_users_online">
    <?php $users_online = get_visits('users_online'); print intval($users_online); ?>
  </div>  </div>