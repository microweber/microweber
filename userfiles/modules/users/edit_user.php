<?php
if (!user_can_access('module.users.edit')) {
    return;
}

$uid = 0;
$rand = uniqid();

$registration_approval_required = get_option('registration_approval_required', 'users');
$user_params = array();
$user_params['id'] = 0;
if (isset($params['edit-user'])) {
    $user_params['id'] = intval($params['edit-user']);
}

if ($user_params['id'] > 0) {
    $user_params['limit'] = 1;
    $data = get_users($user_params);

} else {
    $data = FALSE;
}

if (isset($data[0]) == false) {
    $data = array();
    $data['id'] = 0;
    $data['username'] = '';
    $data['password'] = '';
    $data['email'] = '';
    $data['first_name'] = '';
    $data['last_name'] = '';
    $data['api_key'] = '';
    $data['is_active'] = 1;
    $data['is_admin'] = 0;
    $data['basic_mode'] = 0;
    $data['thumbnail'] = '';
    $data['profile_url'] = '';
    $data['phone'] = '';
} else {
    $data = $data[0];
}

if(isset( $data['id']) and  $data['id'] != 0){
$saveRoute = route('api.user.update',$data['id']);
    $saveRouteMethod = "PATCH";

} else {
$saveRoute = route('api.user.store');
    $saveRouteMethod = "PUT";

}


?>

<script>mw.lib.require("mwui_init");</script>
<script>mw.require("files.js");</script>

<?php if (is_array($data)): ?>
    <?php event_trigger('mw.admin.user.edit', $data); ?>
    <?php $custom_ui = mw()->module_manager->ui('mw.admin.user.edit'); ?>
    <?php $custom_user_fields = mw()->module_manager->ui('mw.admin.user.edit.fields'); ?>
    <script>

        var userId = <?php print $data['id']; ?>;
        DeleteUserAdmin<?php  print $data['id']; ?> = function ($user_id) {
            if (confirm("Are you sure you want to delete this user?")) {
                $.post("<?php print api_link() ?>delete_user", {id: $user_id})
                    .done(function (data) {
                        location.href = "<?php print admin_url('view:modules/load_module:users'); ?>";
                    });
            }
        }

        LoginAsUserFromAdmin<?php  print $data['id']; ?> = function ($user_id) {
            if (confirm("Are you sure you want to login as this user?")) {
                $.post("<?php print api_url('user_make_logged') ?>", {id: $user_id}).done(function (data) {
                    window.location.reload()
                });
            }
        }

        <?php
            $usersEditRand = uniqid();
        ?>

        var isValid = function () {
            var valid = true;
            mw.$('[name="email"], [name="text"]', '#users_edit_<?php echo $usersEditRand; ?>').each(function () {
                if (!this.validity.valid) {
                    $(this).addClass('is-invalid')
                    valid = false;
                } else {
                    $(this).removeClass('is-invalid')
                }
            })
            return valid;
        }

        SaveAdminUserForm<?php  print $data['id']; ?> = function () {
            if (!isValid()) {
                return;
            }

            var val = document.getElementById("reset_password").value.trim();
            if (!val) {
                document.getElementById("reset_password").disabled = true;
            }
            var el = document.getElementById('user-save-button');
            mw.form.post(mw.$('#users_edit_<?php echo $usersEditRand; ?>'), '<?php print $saveRoute  ?>', function (scopeEl) {
                if (this.error) {
                    mw.notification.error(this.error);
                    return;
                }
                saveduserid = 0;
                if(this.data){
                   var saveduserid = this.data.id;
                }

                mw.notification.success(mw.lang('All changes saved'));
                if (userId === 0) {
                    location.href = "<?php print admin_url('view:modules/load_module:users/edit-user:'); ?>" + saveduserid;
                }
                mw.spinner({element: el, color: 'white'}).remove();
                el.disabled = false;
            },false,false,false,false,'<?php print $saveRouteMethod ?>');
        }



        $(document).ready(function () {

            uploader = mw.files.uploader({
                filetypes: "images",
                element: mw.$("#change_avatar")
            });


            $(uploader).on("FileUploaded", function (a, b) {
                mw.$(".js-user-image").attr("src", b.src);
                mw.$("#user_thumbnail").val(b.src);
            });

            mw.$("#avatar_holder .mw-icon-close").click(function () {
                if (mw.$("#avatar_holder").length === 0) {
                    mw.$(".js-user-image").attr("src", '<?php print modules_url(); ?>microweber/api/libs/mw-ui/assets/img/no-user.png');
                    mw.$("#user_thumbnail").val("");
                }
            });
        });

        reset_password = function (y) {
            var y = y || false;
            var field2 = mw.$(".js-reset-password");
            var field = mw.$("#reset_password");
            if (field2.hasClass("semi_hidden") && !y) {
                field2.removeClass("semi_hidden");
                field[0].disabled = false;
                field.focus();
            } else {
                field2.addClass("semi_hidden");
                field[0].disabled = true;
            }
        }
    </script>

    <style>
        .js-img-holder:hover img {
            display: none;
        }

        .js-img-holder:hover .js-add-image {
            display: block;
        }

        .js-img-holder .js-add-image .add-the-image {
            width: 100%;
            height: 100%;
            position: absolute;
            left: 0;
            top: 0;
            align-items: center;
            justify-content: center;
            display: none;
        }

        .js-img-holder:hover .js-add-image .add-the-image {
            display: flex;
        }
    </style>

    <?php
    $action = 'Edit';

    if ($data['id'] == 0) {
        $action = 'Add new';
    }

     ?>

    <div class="card style-1 bg-light mb-3">
        <div class="card-header d-flex">
            <h5>
                <i class="mdi mdi-account-plus text-primary mr-3"></i> <strong><?php _e($action . ' user'); ?></strong>
            </h5>

            <button id="user-save-button-top" class="btn btn-success btn-sm floar-end" onclick="SaveAdminUserForm<?php print $data['id']; ?>()"><?php _e("Save"); ?></button>

        </div>
        <div class="card-body pt-3">
            <div class="row">
                <div class="col-xl-6 mx-auto">
                    <div class="<?php print $config['module_class'] ?> user-id-<?php print $data['id']; ?>" id="users_edit_<?php echo $usersEditRand; ?>">
                        <div id="avatar_holder" class="text-center">
                            <div class="d-inline-block">
                                <?php if ($data['thumbnail'] == '') { ?>
                                    <div class="img-circle-holder img-absolute js-img-holder">
                                        <img src="<?php print modules_url(); ?>microweber/api/libs/mw-ui/assets/img/no-user.png" class="js-user-image"/>

                                        <div class="js-add-image">
                                            <a href="javascript:;" class="add-the-image" id="change_avatar"><i class="mdi mdi-image-plus mdi-24px"></i></a>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <div class="img-circle-holder img-absolute js-img-holder">
                                        <img src="<?php print $data['thumbnail']; ?>" class="js-user-image"/>

                                        <div class="js-add-image">
                                            <a href="javascript:;" class="add-the-image" id="change_avatar"><i class="mdi mdi-image-plus mdi-24px"></i></a>
                                        </div>
                                    </div>
                                <?php } ?>

                                <span class="text-primary mt-2 d-block"><?php _e("User image"); ?></span>
                                <input type="hidden" class="form-control" name="thumbnail" id="user_thumbnail" value="<?php print $data['thumbnail']; ?>"/>
                            </div>
                        </div>

                        <?php if (!empty($custom_ui)): ?>
                            <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs pull-right">
                                <a class="mw-ui-btn mw-ui-btn-outline active mw-admin-user-tab" href="javascript:;"><?php _e('Profile'); ?></a>
                                <?php foreach ($custom_ui as $item): ?>
                                    <?php $title = (isset($item['title'])) ? ($item['title']) : false; ?>
                                    <?php $class = (isset($item['class'])) ? ($item['class']) : false; ?>
                                    <?php $html = (isset($item['html'])) ? ($item['html']) : false; ?>
                                    <a class="mw-ui-btn mw-ui-btn-outline mw-admin-user-tab" href="javascript:;"><?php print $title; ?></a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <input type="hidden" name="id" value="<?php print $data['id']; ?>">
                        <input type="hidden" name="token" value="<?php print csrf_token() ?>" autocomplete="off">

                        <?php if ($data['id'] > 0): ?>
                            <input name="_method" type="hidden" value="PATCH">
                        <?php endif; ?>

                        <div class="d-block">
                            <small class="d-block text-muted text-center mb-4 mt-2"><?php _e("Fill in the fields to create a new user"); ?></small>

                            <div class="form-group">
                                <label class="control-label"><?php _e("Username"); ?></label>
                                <input type="text" class="form-control" name="username" value="<?php print $data['username']; ?>"/>
                            </div>


                            <div class="form-group">
                                <label class="control-label"><?php _e("Password"); ?></label>
                                <?php if ($data['id'] != 0): ?>
                                    <span class="mw-ui-link" onclick="reset_password();$(this).hide()"><?php _e("Change Password"); ?></span>
                                <?php endif; ?>
                                <div class="input-group input-group-password mb-3 append-transparent <?php if ($data['id'] != 0): ?>semi_hidden js-reset-password<?php endif; ?>">
                                    <input type="password" <?php if ($data['id'] != 0): ?>disabled="disabled"<?php endif; ?> name="password" class="form-control" id="reset_password"/>
                                    <div class="input-group-append">
                                        <span class="input-group-text js-show-password bg-white" data-bs-toggle="tooltip" data-title="<?php _e("Show/Hide Password"); ?>"><i class="mdi mdi-eye-outline text-muted mdi-20px"></i></span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group <?php if ($data['id'] != 0): ?>semi_hidden js-reset-password<?php endif; ?>">
                                <label class="control-label"><?php _e("Password again"); ?></label>
                                <div class="input-group input-group-password mb-3 append-transparent">
                                    <input type="password" name="verify_password" class="form-control" id="verify_password"/>
                                    <div class="input-group-append">
                                        <span class="input-group-text js-show-password bg-white" data-bs-toggle="tooltip" data-title="<?php _e("Show/Hide Password"); ?>"><i class="mdi mdi-eye-outline text-muted mdi-20px"></i></span>
                                    </div>
                                </div>
                            </div>


                            <small class="d-block text-muted text-center mb-2"><?php _e("Personal data of the user"); ?></small>

                            <div class="form-group">
                                <label class="control-label"><?php _e("First Name"); ?></label>
                                <input type="text" class="form-control" name="first_name" value="<?php print $data['first_name']; ?>">
                            </div>

                            <div class="form-group">
                                <label class="control-label"><?php _e("Last Name"); ?></label>
                                <input type="text" class="form-control" name="last_name" value="<?php print $data['last_name']; ?>"/>
                            </div>

                            <div class="form-group">
                                <label class="control-label"><?php _e("Email"); ?></label>
                                <input type="email" class="form-control" name="email" value="<?php print $data['email']; ?>">
                            </div>

                            <div class="form-group">
                                <label class="control-label"><?php _e("Phone"); ?></label>
                                <input type="text" class="form-control" name="phone" value="<?php print $data['phone']; ?>">
                            </div>

                            <div class="form-group mt-4 mb-4">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="send_new_user_email" checked="">
                                    <label class="custom-control-label" for="send_new_user_email"><?php _e("Send the new user an email about their account"); ?>. <br/>
                                    </label>
                                    <br />
                                    <a href="<?php echo admin_url();?>view:settings#option_group=users" target="_blank"><?php _e("Edit e-mail template"); ?>.</a>
                                </div>
                            </div>

                            <?php
                            /*$userRoles = \Illuminate\Support\Facades\Auth::user()->with('roles')->get();
                            var_dump($userRoles->roles); */


                             ?>

                            <?php if (is_admin()) : ?>
                                <small class="d-block text-muted text-center mb-3"><?php _e("User status and role"); ?></small>

                                <div class="form-group">
                                    <label class="control-label mb-1"><?php _e("Role of the user"); ?></label>
                                    <small class="text-muted d-block mb-1"><?php _e("Choose the current role of the user"); ?>.
                                      <!--  <a href=""><?php /*_e("Manage user roles"); */?></a>-->
                                    </small>
                                    <select class="selectpicker" data-live-search="true" data-width="100%" name="is_admin">

                                        <option value="1" <?php if( $data['is_admin'] == 1): ?> selected="selected" <?php endif; ?>>Admin</option>
                                        <option value="0" <?php if( $data['is_admin'] == 0): ?> selected="selected" <?php endif; ?>>User</option>

                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="control-label mb-1"><?php _e('Is Active'); ?>?</label>
                                    <small class="text-muted d-block mb-1"><?php _e("Choose the current status of this user"); ?></small>

                                    <div class="custom-control custom-radio d-inline-block mr-3">
                                        <input type="radio" id="is_active1" class="custom-control-input" value="1" name="is_active" <?php if ($data['is_active'] == 1): ?> checked="checked" <?php endif; ?>>
                                        <label class="custom-control-label" for="is_active1"><?php _e("Active"); ?></label>
                                    </div>
                                    <div class="custom-control custom-radio d-inline-block">
                                        <input type="radio" id="is_active2" class="custom-control-input" value="0" name="is_active" <?php if ($data['is_active'] == 0): ?> checked="checked" <?php endif; ?>>
                                        <label class="custom-control-label" for="is_active2"><?php _e("Disabled"); ?></label>
                                    </div>

                                    <?php if ($registration_approval_required == 'y' && $data['is_active'] == 0): ?>
                                        <span class="mw-approval-required d-block"><?php _e("Account requires approval"); ?></span>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group d-none">
                                    <label class="control-label d-block"><?php _e("Basic mode"); ?></label>

                                    <div class="custom-control custom-radio d-inline-block mr-3">
                                        <input type="radio" id="basic_mode1" class="custom-control-input" value="1" name="basic_mode" <?php if ($data['basic_mode'] == 1): ?> checked="checked" <?php endif; ?>>
                                        <label class="custom-control-label" for="basic_mode1"><?php _e("Active"); ?></label>
                                    </div>

                                    <div class="custom-control custom-radio d-inline-block">
                                        <input type="radio" id="basic_mode0" class="custom-control-input" value="0" name="basic_mode" <?php if ($data['basic_mode'] == 0): ?> checked="checked" <?php endif; ?>>
                                        <label class="custom-control-label" for="basic_mode0"><?php _e("Disabled"); ?></label>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($custom_user_fields)): ?>
                                <?php foreach ($custom_user_fields as $item): ?>
                                    <?php $title = (isset($item['title'])) ? ($item['title']) : false; ?>
                                    <?php $class = (isset($item['class'])) ? ($item['class']) : false; ?>
                                    <?php $html = (isset($item['html'])) ? ($item['html']) : false; ?>

                                    <div class="form-group <?php print $class; ?>">
                                        <label class="control-label"><?php print ($title); ?></label>
                                        <div><?php print $html; ?></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <a href="javascript:;" class="btn btn-outline-primary" data-bs-toggle="collapse" data-target="#advanced-settings"><?php _e("Advanced settings"); ?></a>

                            <div class="collapse" id="advanced-settings">
                                <div class="form-group">
                                    <label class="control-label"><?php _e("Api key"); ?></label>
                                    <input type="text" class="form-control" name="api_key" value="<?php print $data['api_key']; ?>">
                                </div>

                                <?php if ($data['id'] != false and $data['id'] != user_id()): ?>
                                    <div class="d-flex align-items-center">
                                        <a onclick="LoginAsUserFromAdmin<?php print $data['id']; ?>('<?php print $data['id']; ?>')" class="btn btn-primary btn-sm"><?php _e('Login as User'); ?></a>
                                        <a onclick="DeleteUserAdmin<?php print $data['id']; ?>('<?php print $data['id']; ?>')" class="btn btn-danger btn-sm ml-2"><?php _e('Delete user'); ?></a>
                                    </div>
                                <?php endif; ?>

                                <?php if (is_admin()) : ?>
                                    <script>
                                        function mw_admin_tos_popup(user_id) {
                                            var modalTitle = '<?php _e('Terms agreement log'); ?>';

                                            mw.dialog({
                                                content: '<div id="mw_admin_edit_tos_item_module"></div>',
                                                title: modalTitle,
                                                id: 'mw_admin_edit_tos_item_popup_modal'
                                            });

                                            var params = {}
                                            params.user_id = user_id;
                                            mw.load_module('users/terms/log', '#mw_admin_edit_tos_item_module', null, params);
                                        }

                                        function mw_admin_login_attempts_popup(user_id) {
                                            var modalTitle = '<?php _e('Login attempts'); ?>';

                                            mw_admin_login_attempts_popup_modal_opened = mw.dialog({
                                                content: '<div id="mw_admin_login_attempts_module"></div>',
                                                title: modalTitle,
                                                id: 'mw_admin_login_attempts_popup_modal'
                                            });

                                            var params = {}
                                            params.user_id = user_id;
                                            mw.load_module('users/login_attempts', '#mw_admin_login_attempts_module', null, params);
                                        }
                                    </script>

                                    <div class="export-label d-flex align-items-center justify-content-center-x">
                                        <a href="<?php echo api_url('users/export_my_data'); ?>?user_id=<?php echo $data['id']; ?>" class="btn btn-link px-0"><?php _e('Export user data'); ?></a>
                                        &nbsp;
                                    </div>
                                    <div class="export-label d-flex align-items-center justify-content-center-x">

                                        <a href="javascript:mw_admin_tos_popup(<?php echo $data['id']; ?>)" class="btn btn-link px-0"><?php _e('Terms agreement log'); ?></a>
                                        &nbsp;
                                    </div>
                                    <div class="export-label d-flex align-items-center justify-content-center-x">

                                        <a href="javascript:mw_admin_login_attempts_popup(<?php echo $data['id']; ?>)" class="btn btn-link px-0"><?php _e('Login attempts'); ?></a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="thin"/>

            <div class="d-flex justify-content-between">
                <a class="btn btn-outline-primary btn-sm" href="<?php print admin_url('view:modules/load_module:users'); ?>"><?php _e("Cancel"); ?></a>
                <button id="user-save-button" class="btn btn-success btn-sm" onclick="SaveAdminUserForm<?php print $data['id']; ?>()"><?php _e("Save"); ?></button>
            </div>
        </div>
    </div>
    <?php if (!empty($custom_ui)): ?>
        <?php foreach ($custom_ui as $item): ?>
            <?php $title = (isset($item['title'])) ? ($item['title']) : false; ?>
            <?php $class = (isset($item['class'])) ? ($item['class']) : false; ?>
            <?php $html = (isset($item['html'])) ? ($item['html']) : false; ?>
            <div style="display:none;" class="mw-ui-box-content mw-admin-user-tab-content  <?php print $class; ?>" title="<?php print addslashes($title); ?>"><?php print $html; ?></div>
        <?php endforeach; ?>
    <?php endif; ?>
<?php endif; ?>
