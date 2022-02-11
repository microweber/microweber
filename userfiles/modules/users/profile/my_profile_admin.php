<?php
$user = get_user();

$user_image = modules_url() . 'microweber/api/libs/mw-ui/assets/img/no-user.png';
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
            <i class="mdi mdi-account-multiple text-primary mr-3"></i> <strong><?php _e('Users'); ?></strong>
        </h5>
    </div>

    <div class="card-body pt-3">
        <div class="row d-flex align-items-center justify-content-around">
            <div class="col-md-5 py-5">
                <h5 class="font-weight-bold"><?php _e('Manage your users'); ?></h5>
                <p><?php _e('You are able to create and manage users, groups and roles.'); ?></p>
                <br/>

                <h6 class="font-weight-bold mb-1"><?php _e('Working with users'); ?></h6>
                <small class="text-muted d-block mb-2"><?php _e('Create and manage users'); ?></small>
                <a href="<?php print admin_url('view:modules/load_module:users/edit-user:0'); ?>" class="btn btn-primary btn-sm"><i class="mdi mdi-account-plus"></i><?php _e('Add new user'); ?></a>
                <a href="<?php echo route('admin.user.index'); ?>" class="btn btn-outline-primary btn-sm"><i class="mdi mdi-account-cog"></i><?php _e('Manage users'); ?></a>
                <br/>
                <br/>
                <br/>

                <?php
                if (\Illuminate\Support\Facades\Route::has('admin.role.create')):
                ?>
               <div class="d-none">
                   <h6 class="font-weight-bold mb-1"><?php _e('Set and manage user roles'); ?></h6>
                   <small class="text-muted d-block mb-2"><?php _e('Create and manage users roles'); ?></small>
                   <a href="<?php echo route('admin.role.create');?>" class="btn btn-success btn-sm"><i class="mdi mdi-book-account"></i><?php _e('Add new role'); ?></a>
                   <a href="<?php echo route('admin.role.index');?>" class="btn btn-outline-success btn-sm"><i class="mdi mdi-format-list-checks"></i><?php _e('Manage roles'); ?></a>
               </div>
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
                    <span class="d-block text-outline-primary font-weight-bold"><?php echo $user_name; ?></span>
                    <small class="d-block text-dark"><?php echo $user_role; ?></small>

                    <a
                        href="<?php print admin_url('view:modules/load_module:users/edit-user:' . $user['id']); ?>"
                        class="btn btn-outline-primary btn-sm mt-2"><?php _e('Edit profile'); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
