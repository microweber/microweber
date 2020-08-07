<?php
$user = get_user();

$user_image = modules_url() . 'microweber/api/libs/mw-ui/assets/img/no-image.jpg';
if (isset($user['thumbnail'])) {
    $user_image = $user['thumbnail'];
}

$user_name = '';
if (isset($user['first_name'])) {
    $user_name = $user['first_name'];
}

if (isset($user['last_name'])) {
    $user_name .= ' ' . $user['last_name'];
}

$user_role = 'User';
if (isset($user['is_admin'])) {
    $user_role = 'Admin';
}
if (isset($user['role'])) {
    $user_role = $user['role'];
}
?>

<div class="card style-1 bg-light mb-3">
    <div class="card-header">
        <h5>
            <i class="mdi mdi-account-multiple text-primary mr-3"></i> <strong>Users</strong>
        </h5>
    </div>

    <div class="card-body pt-3">
        <div class="row d-flex align-items-center justify-content-around">
            <div class="col-md-5 py-5">
                <h5 class="font-weight-bold">Manage your users</h5>
                <p>You are able to create and manage users, groups and roles.</p>
                <br/>

                <h6 class="font-weight-bold mb-1">Working with users</h6>
                <small class="text-muted d-block mb-2">Create and manage users</small>
                <a href="<?php print admin_url('view:modules/load_module:users/edit-user:0'); ?>" class="btn btn-primary btn-sm"><i class="mdi mdi-account-plus"></i> Add new user</a>
                <a href="<?php print admin_url('view:modules/load_module:users'); ?>" class="btn btn-outline-primary btn-sm"><i class="mdi mdi-account-cog"></i> Manage users</a>
                <br/>
                <br/>
                <br/>

                <?php
                if (class_exists(MicroweberPackages\Role\RoleServiceProvider::class)):
                ?>
                <h6 class="font-weight-bold mb-1">Set and manage user roles</h6>
                <small class="text-muted d-block mb-2">Create and manage users roles</small>
                <a href="<?php echo route('roles.create');?>" class="btn btn-success btn-sm"><i class="mdi mdi-book-account"></i> Add new role</a>
                <a href="<?php echo route('roles.index');?>" class="btn btn-outline-success btn-sm"><i class="mdi mdi-format-list-checks"></i> Manage roles</a>
                <?php endif; ?>

            </div>

            <div class="col-md-5">
                <div class="text-center">
                    <div class="d-inline-block mb-1">
                        <div class="img-circle-holder img-absolute w-80">
                            <img src="<?php print thumbnail($user_image, 120, 120); ?>"/>
                        </div>
                    </div>

                    <small class="d-block text-muted"><?php _e('You are logged in as'); ?></small>
                    <span class="d-block text-primary font-weight-bold"><?php echo $user_name; ?></span>
                    <small class="d-block text-dark"><?php echo $user_role; ?></small>

                    <a
                        href="<?php print admin_url('view:modules/load_module:users/edit-user:' . $user['id']); ?>"
                        class="btn btn-outline-primary btn-sm mt-2">Edit profile</a>
                </div>
            </div>
        </div>
    </div>
</div>
