<div class="mw-module-admin-wrap">
 <?php if(isset($params['backend'])): ?>
<module type="admin/modules/info" history_back="true" />
<?php endif; ?>
  <div id="users-admin">
    <style scoped="scoped">
        #sort-users .mw-ui-row,
        #sort-users .mw-ui-row *{
          vertical-align: middle;
        }
        #sort-users{
          padding-bottom: 35px;
        }
        @media (max-width:1024px){
          #sort-users .mw-ui-col{
            padding-bottom: 15px;
          }

        }

    </style>
    <?php only_admin_access(); ?>
    <script type="text/javascript"> mw.require('forms.js', true); </script>
    <script type="text/javascript">


    userSections = {
      create:'<?php _e("Add new user"); ?>',
      edit:'<?php _e("Edit user"); ?>',
      manage:'<?php _e("Manage users"); ?>'
    }


    $(document).ready(function () {
        if (typeof UsersRotator === 'undefined') {
            UsersRotator = mw.admin.simpleRotator(mwd.getElementById('mw-users-manage-edit-rotattor'));
        }



    });

    function mw_show_users_list() {
        var ui = mw.url.getHashParams(window.location.hash).ui;

        if (typeof ui == 'undefined') {
            var ui = mwd.querySelector('#mw_index_users input.mw_users_filter_show:checked').value;
        }
        if (typeof ui !== 'undefined') {
            mw.$('#users_admin_panel').attr('data-show-ui', ui);
        }
        else {
            mw.$('#users_admin_panel').removeAttr('data-show-ui');
        }

        var search = mw.url.getHashParams(window.location.hash).search;
        if (typeof search !== 'undefined') {
            mw.$('#users_admin_panel').attr('data-search-keyword', search);

        }
        else {
            mw.$('#users_admin_panel').removeAttr('data-search-keyword');
        }

        var is_admin = mw.url.getHashParams(window.location.hash).is_admin;
        if (typeof is_admin !== 'undefined' && parseInt(is_admin) !== 0) {
            mw.$('#users_admin_panel').attr('data-is_admin', is_admin);
        }
        else {
            mw.$('#users_admin_panel').removeAttr('data-is_admin');
        }
        var installed = mw.url.getHashParams(window.location.hash).installed;
        if (typeof installed !== 'undefined') {
            mw.$('#users_admin_panel').attr('data-installed', installed);
        }
        else {
            mw.$('#users_admin_panel').removeAttr('data-installed');
        }
        mw.load_module('users/edit_user', '#user_edit_admin_panel', function(){
          mw.responsive.table('.users-list-table', {
              breakPoints:{
                920:3,
                620:1
              }
            })
        });
    }


    _mw_admin_users_manage = function () {
        var attrs = mw.url.getHashParams(window.location.hash);
        var holder = mw.$('#users_admin_panel');
        var arr = ['data-show-ui', 'data-search-keyword', 'data-category', 'data-installed', 'is_admin', 'is_active'],
            i = 0,
            l = arr.length;
        var sync = ['ui', 'search', 'category', 'installed', 'mw-users-is-admin', 'mw-users-is-active'];
        for ( ;i < l; i++) {
            holder.removeAttr(arr[i]);
        }
        for (var x in attrs) {
            if (x === 'category' && (attrs[x] === '0' || attrs[x] === undefined)) continue;
            holder.attr(x, attrs[x]);
        }
        mw.load_module('users/manage', '#users_admin_panel', function () {
            TableLoadded = true;
            var params = mw.url.getHashParams(window.location.hash);
            if (params['edit-user'] !== undefined) {
                _mw_admin_user_edit();
            }
            else{
               UsersRotator.go(0)
            }
            mw.responsive.table('.users-list-table', {
              breakPoints:{
                920:3,
                620:1
              }
            })
        });
    }

    TableLoadded = false;
    $(window).bind("load", function () {
        var hash = mw.url.getHashParams(window.location.hash);
        if (typeof hash['edit-user'] == 'undefined') {
            if (hash.sortby === undefined) {
                mw.url.windowHashParam('sortby', 'created_at desc');
            }
        }

    });

    _mw_admin_user_edit = function () {
        var attrs = mw.url.getHashParams(window.location.hash);
        var holder = mw.$('#user_edit_admin_panel');
        if (attrs['edit-user'] !== undefined && attrs['edit-user'] !== '') {
            holder.attr('edit-user', attrs['edit-user']);
            mw.load_module('users/edit_user', '#user_edit_admin_panel', function () {
                if (typeof UsersRotator === 'undefined') {
                    UsersRotator = mw.admin.simpleRotator(mwd.getElementById('mw-users-manage-edit-rotattor'));
                }
                UsersRotator.go(1, function () {
                    mw.tools.scrollTo(mwd.querySelector('#mw_toolbar_nav'));
                });

            });
        }
    }

    mw.on.hashParam('is_admin', function () {
      if(this == false) return false;
        mw.url.windowDeleteHashParam('edit-user');
        _mw_admin_users_manage();
        mw.url.hashParamToActiveNode('is_admin', 'mw-users-is-admin');
    });
    mw.on.hashParam('search', function () {
        if(this == false) return false;
        mw.url.windowDeleteHashParam('edit-user');
        _mw_admin_users_manage();
    });
    mw.on.hashParam('is_active', function () {
      if(this == false) return false;
        mw.url.windowDeleteHashParam('edit-user');
        _mw_admin_users_manage();
        mw.url.hashParamToActiveNode('is_active', 'mw-users-is-active');
    });
    mw.on.hashParam('sortby', function () {
      if(this == false) return false;
        mw.url.windowDeleteHashParam('edit-user');
       _mw_admin_users_manage();
    });
    mw.on.hashParam('edit-user', function () {
        if (this == false && this != 0) {
            _mw_admin_users_manage();
            UsersRotator.go(0);
            mw.$('.modules-index-bar, .manage-items').fadeIn();
        }
        else {
            _mw_admin_user_edit();
            mw.$('.modules-index-bar, .manage-items').fadeOut();
        }

        var val = this.toString();


        if(val == 'false'){
            mw.$("#user-section-title").html(userSections.manage);
            mw.$("#add-new-user-btn").show();
            $("#main-menu-my-profile").addClass('active');
            $("#main-menu-manage-users").removeClass('active');
        }
        else if(val == '0'){
            mw.$("#user-section-title").html(userSections.create);
            mw.$("#add-new-user-btn").hide();
            $("#main-menu-my-profile").removeClass('active');
            $("#main-menu-manage-users").addClass('active');
        }
        else{
            mw.$("#user-section-title").html(userSections.edit);
            mw.$("#add-new-user-btn").hide();
            $("#main-menu-my-profile").removeClass('active');
            $("#main-menu-manage-users").addClass('active');
        }

    });


    function mw_admin_delete_user_by_id(id) {
        mw.tools.confirm("<?php _e("Are you sure you want to delete this user?"); ?>", function () {
            data = {};
            data.id = id
            $.post("<?php print api_link() ?>delete_user", data, function () {
                _mw_admin_users_manage();
            });
        });
    }

</script>
    <?php

      $mw_notif = (url_param('mw_notif'));
      if ($mw_notif != false) {
          $mw_notif = mw()->notifications_manager->read($mw_notif);
      }
      mw()->notifications_manager->mark_as_read('users');

    ?>
    <?php if (is_array($mw_notif) and isset($mw_notif['rel_id'])): ?>
    <script type="text/javascript">
        $(document).ready(function () {
            mw.url.windowHashParam('edit-user', '<?php print $mw_notif['rel_id'] ?>');
            _mw_admin_user_edit();
        });
    </script>
    <?php endif; ?>

      <div class="admin-side-content">

    <div class="mw-ui-row-nodrop pull-left" style="width: auto">
        <div class="mw-ui-col"><div class="mw-col-container"><h2 id="user-section-title"><?php _e("Manage Users"); ?></h2></div></div>
        <div class="mw-ui-col">
            <div class="mw-col-container">
          <a href="#edit-user=0" class="mw-ui-btn mw-ui-btn-notification" id="add-new-user-btn">
            <span class="mw-icon-plus"></span>
            <span>
              <?php _e("Add new user"); ?>
            </span>
          </a>
        </div>
        </div>
    </div>




    <input
          name="module_keyword"
          class="mw-ui-searchfield pull-right" type="search"
          placeholder="<?php _e("Search for users"); ?>"
          onkeyup="mw.form.dstatic(event);mw.on.stopWriting(this, function(){mw.url.windowHashParam('search', this.value)});" />
    <div class="mw-clear">
       <hr>
    </div>

    <div class="manage-items" id="sort-users">
      <div class="mw-ui-row mw-ui-row-drop-on-1024">
        <div class="mw-ui-col" style="width: 200px;">
          <label class="mw-ui-label">
            <?php _e("Sort Users by Roles"); ?>
          </label>
          <div class="mw-ui-btn-nav"> <a class="mw-ui-btn mw-users-is-admin mw-users-is-admin-none active" href="javascript:;" onclick="mw.url.windowDeleteHashParam('is_admin');">
            <?php _e("All"); ?>
            </a> <a class="mw-ui-btn mw-users-is-admin mw-users-is-admin-n" href="javascript:;" onclick="mw.url.windowHashParam('is_admin', '0');">
            <?php _e("User"); ?>
            </a> <a class="mw-ui-btn mw-users-is-admin mw-users-is-admin-y" href="javascript:;" onclick="mw.url.windowHashParam('is_admin', '1');">
            <?php _e("Admin"); ?>
            </a> </div>
        </div>
        <div class="mw-ui-col">
          <label class="mw-ui-label"><?php _e('Sort Users by Status'); ?></label>
          <div class="mw-ui-btn-nav"> <a class="mw-ui-btn mw-users-is-active mw-users-is-active-none" href="javascript:;" onclick="mw.url.windowDeleteHashParam('is_active');">
            <?php _e("All users"); ?>
            </a> <a class="mw-ui-btn mw-users-is-active mw-users-is-active-y" href="javascript:;" onclick="mw.url.windowHashParam('is_active', '1');">
            <?php _e("Active users"); ?>
            </a> <a class="mw-ui-btn mw-users-is-active mw-users-is-active-n" href="javascript:;" onclick="mw.url.windowHashParam('is_active', '0');">
            <?php _e("Disabled users"); ?>
            </a> </div>
        </div>
        <div class="mw-ui-col">
          <ul class="mw-ui-inline-list" >
            <li>
              <label class="mw-ui-check">
                <input name="sortby" class="mw_users_filter_show" type="radio" value="created_at desc" checked="checked" onchange="mw.url.windowHashParam('sortby', this.value)" />
                <span></span><span>
                <?php _e("Date created"); ?>
                </span> </label>
            </li>
            <li>
              <label class="mw-ui-check">
                <input name="sortby" class="mw_users_filter_show" type="radio" value="last_login desc" onchange="mw.url.windowHashParam('sortby', this.value)" />
                <span></span><span>
                <?php _e("Last login"); ?>
                </span> </label>
            </li>
            <li>
              <label class="mw-ui-check">
                <input name="sortby" class="mw_users_filter_show" type="radio" value="username asc" onchange="mw.url.windowHashParam('sortby', this.value)"/>
                <span></span><span>
                <?php _e("Username"); ?>
                </span> </label>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="mw-simple-rotator">
      <div class='mw-simple-rotator-container' id="mw-users-manage-edit-rotattor">
        <div id="users_admin_panel"></div>
        <div id="user_edit_admin_panel"></div>
      </div>
    </div>
  </div>
</div></div>
