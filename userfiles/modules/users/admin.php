<?php
if (!user_can_access('module.users.edit')) {
    return;
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info" history_back="true"/>
<?php endif; ?>

<?php
$action = url_param('action');
$editUser = url_param('edit-user') or url_param('edituser');

if ($action == 'profile') {
    print '<module type="users/profile/my_profile_admin"/>';
    return;
} elseif ($editUser !== false) {
    print('<module type="users/edit_user" edit-user="' . $editUser . '" />');
    return;
}

?>


<style>
    #mw-admin-manage-users-header {
        display: flex;
    }

    #users-manage-body {
        position: relative;
    }

    #users-manage-body .mw-spinner {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        border-radius: 50px;
    }
</style>

<script type="text/javascript"> mw.require('forms.js', true); </script>

<script type="text/javascript">
    userSections = {
        create: '<?php _e("Add new user"); ?>',
        edit: '<?php _e("Edit user"); ?>',
        manage: '<?php _e("Manage users"); ?>'
    }

    $(document).ready(function () {
        if (typeof UsersRotator === 'undefined') {
            UsersRotator = mw.admin.simpleRotator(document.getElementById('mw-users-manage-edit-rotattor'));
        }
    });

    function mw_show_users_list() {
        var ui = mw.url.getHashParams(window.location.hash).ui;

        if (typeof ui == 'undefined') {
            var ui = document.querySelector('#mw_index_users input.mw_users_filter_show:checked').value;
        }

        if (typeof ui !== 'undefined') {
            mw.$('#users_admin_panel').attr('data-show-ui', ui);
        } else {
            mw.$('#users_admin_panel').removeAttr('data-show-ui');
        }

        var search = mw.url.getHashParams(window.location.hash).search;
        if (typeof search !== 'undefined') {
            mw.$('#users_admin_panel').attr('data-search-keyword', search);
        } else {
            mw.$('#users_admin_panel').removeAttr('data-search-keyword');
        }

        var is_admin = mw.url.getHashParams(window.location.hash).is_admin;
        if (typeof is_admin !== 'undefined' && parseInt(is_admin) !== 0) {
            mw.$('#users_admin_panel').attr('data-is_admin', is_admin);
        } else {
            mw.$('#users_admin_panel').removeAttr('data-is_admin');
        }

        var installed = mw.url.getHashParams(window.location.hash).installed;
        if (typeof installed !== 'undefined') {
            mw.$('#users_admin_panel').attr('data-installed', installed);
        } else {
            mw.$('#users_admin_panel').removeAttr('data-installed');
        }

        mw.load_module('users/edit_user', '#user_edit_admin_panel', function () {
            mw.responsive.table('.users-list-table')
        });
    }

    _mw_admin_users_manage = function () {
        var attrs = mw.url.getHashParams(window.location.hash);
        var holder = mw.$('#users_admin_panel');
        var arr = ['data-show-ui', 'data-search-keyword', 'data-category', 'data-installed', 'is_admin', 'is_active'],
            i = 0,
            l = arr.length;
        var sync = ['ui', 'search', 'category', 'installed', 'mw-users-is-admin', 'mw-users-is-active'];
        for (; i < l; i++) {
            holder.removeAttr(arr[i]);
        }
        for (var x in attrs) {
            if (x === 'category' && (attrs[x] === '0' || attrs[x] === undefined)) continue;
            holder.attr(x, attrs[x]);
        }
        mw.load_module('users/manage', '#users_admin_panel', function () {
            var params = mw.url.getHashParams(window.location.hash);
            mw.responsive.table('.users-list-table')
        });
    }

    $(window).on("load", function () {
        var hash = mw.url.getHashParams(window.location.hash);
        if (hash.sortby === undefined) {
            mw.url.windowHashParam('sortby', 'created_at desc');
        }
    });

    mw.on.hashParam('is_admin', function (pval) {
        if (pval === false) return false;
        _mw_admin_users_manage();
        mw.url.hashParamToActiveNode('is_admin', 'mw-users-is-admin');
    });

    mw.on.hashParam('search', function (pval) {
        if (pval === false) return false;
        _mw_admin_users_manage();
    });

    mw.on.hashParam('is_active', function (pval) {
        if (pval === false) return false;
        _mw_admin_users_manage();
        mw.url.hashParamToActiveNode('is_active', 'mw-users-is-active');
    });

    mw.on.hashParam('sortby', function (pval) {
        if (pval === false) return false;
        _mw_admin_users_manage();
    });

    $("#main-menu-my-profile").parent().removeClass('active');
    $("#main-menu-manage-users").parent().addClass('active');


    function mw_admin_delete_user_by_id(id) {
        mw.tools.confirm("<?php _ejs("Are you sure you want to delete this user?"); ?>", function () {
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


<div class="card style-1 bg-light mb-3">
    <div class="card-header">
        <?php
        $user_params['id'] = 0;
        if (isset($params['edit-user'])) {
            $user_params['id'] = intval($params['edit-user']);
        }
        ?>

        <?php if ($user_params['id'] != 0): ?>
            <h5><i class="mdi mdi-account-multiple text-primary mr-3"></i> <strong><?php _e("Edit User"); ?></strong></h5>
        <?php else: ?>
            <h5><i class="mdi mdi-account-multiple text-primary mr-3"></i> <strong><?php _e("Manage Users"); ?></strong></h5>
        <?php endif; ?>
    </div>


    <div class="card-body pt-3" id="users-manage-body">
        <div class="align-items-center justify-content-between mt-2" id="mw-admin-manage-users-header">
            <div>
                <script>
                    handleUserSearch = function (e) {
                        e.preventDefault();
                        var target = e.target.querySelector('[type="search"]');
                        mw.url.windowHashParam('search', target.value.trim());
                    }
                </script>
                <form class="form-inline" onsubmit="handleUserSearch(event)">
                    <div class="form-group">
                        <div class="input-group mb-0 prepend-transparent">
                            <div class="input-group-prepend bg-white">
                                <span class="input-group-text"><i class="mdi mdi-magnify mdi-20px"></i></span>
                            </div>
                            <input type="search" class="form-control" aria-label="Search" placeholder="<?php _e("Search for users"); ?>">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-outline-primary ml-2"><?php _e("Search"); ?></button>
                </form>
            </div>

            <div>
                <a href="#" class="btn btn-outline-primary icon-left btn-md js-show-filter" data-bs-toggle="collapse" data-bs-target="#show-filter"><i class="mdi mdi-filter-outline"></i><?php _e('Filter'); ?></a>

                <a href="<?php print admin_url('view:modules/load_module:users/edit-user:0'); ?>" class="btn btn-primary" id="add-new-user-btn">
                    <i class="mdi mdi-account-plus mr-2"></i> <?php _e("Add user"); ?>
                </a>
            </div>
        </div>

        <div class="manage-items mt-4" id="sort-users">
            <script>
                $(document).ready(function () {
                    $('.js-show-filter').on('click', function () {
                        $(this).toggleClass('active');
                        if ($(this).hasClass('active')) {
                            $(this).find('i').removeClass('mdi-filter-outline').addClass('mdi-close-thick');
                        } else {
                            $(this).find('i').removeClass('mdi-close-thick').addClass('mdi-filter-outline');
                        }
                    });
                });
            </script>

            <div class="collapse" id="show-filter">
                <div class="bg-primary-opacity-1 rounded px-3 py-2 mb-4">
                    <div class="row d-flex">
                        <div class="col-auto">
                            <div class="row d-flex justify-content-between">
                                <div class="col-md-4">
                                    <div>
                                        <label class="d-block mb-2"><?php _e("Role"); ?></label>
                                        <script>
                                            handleUserSortByRoles = function (e) {
                                                e.preventDefault();
                                                var val = e.target.value;
                                                if (val === '-1') {
                                                    mw.url.windowDeleteHashParam('is_admin');
                                                } else {
                                                    mw.url.windowHashParam('is_admin', val);
                                                }
                                            }
                                        </script>

                                        <select class="selectpicker" data-style="btn-md" onchange="handleUserSortByRoles(event)">
                                            <option value="-1"><?php _e("All"); ?></option>
                                            <option value="0"><?php _e("User"); ?></option>
                                            <option value="1"><?php _e("Admin"); ?></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div>
                                        <label class="d-block mb-2"><?php _e('Status'); ?></label>
                                        <script>
                                            handleUserSortByActiveState = function (e) {
                                                e.preventDefault();
                                                var val = e.target.value;
                                                if (val === '-1') {
                                                    mw.url.windowDeleteHashParam('is_active');
                                                } else {
                                                    mw.url.windowHashParam('is_active', val);
                                                }
                                            }
                                            function resetFilters() {
                                                mw.url.windowDeleteHashParam('is_active')
                                                mw.url.windowDeleteHashParam('is_admin')
                                                mw.url.windowDeleteHashParam('sortby')
                                                $('#show-filter .selectpicker').selectpicker('val', -1);
                                                mw.$('#sortby1')[0].checked = true
                                            }
                                        </script>

                                        <select class="selectpicker" data-style="btn-md" onchange="handleUserSortByActiveState(event)">
                                            <option value="-1"><?php _e("All users"); ?></option>
                                            <option value="1"><?php _e("Active users"); ?></option>
                                            <option value="0"><?php _e("Disabled users"); ?></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div>
                                        <label class="d-block mb-2"><?php _e('Sort by'); ?></label>

                                        <div class="form-group mb-0">
                                            <div class="custom-control custom-radio d-inline-block mr-2">
                                                <input type="radio" id="sortby1" name="sortby" class="custom-control-input mw_users_filter_show" value="created_at desc" checked="checked" onchange="mw.url.windowHashParam('sortby', this.value)">
                                                <label class="custom-control-label" for="sortby1"><?php _e("Date created"); ?></label>
                                            </div>
                                            <div class="custom-control custom-radio d-inline-block mr-2">
                                                <input type="radio" id="sortby2" name="sortby" class="custom-control-input mw_users_filter_show" value="last_login desc" onchange="mw.url.windowHashParam('sortby', this.value)">
                                                <label class="custom-control-label" for="sortby2"><?php _e("Last login"); ?></label>
                                            </div>
                                            <div class="custom-control custom-radio d-inline-block">
                                                <input type="radio" id="sortby3" name="sortby" class="custom-control-input mw_users_filter_show" value="username asc" onchange="mw.url.windowHashParam('sortby', this.value)">
                                                <label class="custom-control-label" for="sortby3"><?php _e("Username"); ?></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col d-flex align-items-center justify-content-center">
                            <div class="text-center">
                                <button onclick="resetFilters()" class="btn btn-outline-primary btn-sm" type="button">Reset filters</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mw-simple-rotator">
            <div class="mw-simple-rotator-container" id="mw-users-manage-edit-rotattor">
                <div id="users_admin_panel"></div>
                <div id="user_edit_admin_panel"></div>
            </div>
        </div>
    </div>
</div>

