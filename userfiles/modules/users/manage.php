<?php
if (!user_can_access('module.users.edit')) {
    return;
}

$user_params = array();
if (isset($params['sortby'])) {
    $user_params['order_by'] = $params['sortby'];
}
if (isset($params['is_admin'])) {
    $user_params['is_admin'] = $params['is_admin'];
}
if (isset($params['is_active'])) {
    $user_params['is_active'] = $params['is_active'];
}
$users_per_page = 100;
$paging_param = $params['id'] . '_page';
$current_page_from_url = url_param($paging_param);


if (intval($current_page_from_url) > 0) {
    $user_params['current_page'] = intval($current_page_from_url);

} elseif (isset($params['current_page'])) {
    $current_page_from_url = $user_params['current_page'] = $params['current_page'];
}

if (isset($params['search'])) {

    if (isset($params['search'])) {
        $user_params['keyword'] = $params['search'];
    }

    if (isset($params['keyword'])) {
        $user_params['search_in_fields'] = array('username', 'email', 'first_name', 'last_name');
    }


    $user_params['search_by_keyword'] = $params['search'];
    $users_per_page = 1000;
    $user_params['current_page'] = 1;
}

//$user_params['debug'] = 1;

$user_params['limit'] = $users_per_page;

$data = get_users($user_params);


$paging_data = $user_params;
$paging_data['page_count'] = true;
$paging = get_users($paging_data);


$self_id = user_id();

$registration_approval_required = get_option('registration_approval_required', 'users');

?>
<style>
    .mw-admin-users-manage-table td,
    .mw-admin-users-manage-table td *{
        vertical-align: middle;
    }
</style>

<?php if (is_array($data)): ?>
    <div class="table-responsive   mw-admin-users-manage-table">
        <table cellspacing="0" cellpadding="0" class="table table-hover m-0">
            <tbody>
            <?php foreach ($data as $item): ?>
                <tr id="mw-admin-user-<?php print $item['id']; ?>">
                    <td>
                        <?php if (isset($item['thumbnail']) and trim($item['thumbnail']) != ''): ?>
                            <div class="d-flex justify-content-center mx-auto rounded-circle" style="max-width: 100px;">
                                <img src="<?php print $item['thumbnail'] ?>">
                            </div>
                        <?php else: ?>
                            <div class="d-flex justify-content-center">
                                <img src="<?php print modules_url(); ?>microweber/api/libs/mw-ui/assets/img/no-user.png"/>
                            </div>
                        <?php endif; ?>
                    </td>

                    <td>
                        <div class="settings-info-holder-title">
                            <?php if (isset($item['oauth_provider']) and trim($item['oauth_provider']) != ''): ?>
                                <a href="<?php print $item['profile_url']; ?>" target="_blank" title="<?php print ucwords($item['oauth_provider']) ?>" class="mw-icon-<?php print $item['oauth_provider'] ?>"></a>
                            <?php endif; ?>
                            <?php print $item['first_name'] . ' ' . $item['last_name']; ?>
                            <br>
                            <small class=" "><?php if ($item['is_admin'] == 1) {_e("Admin");} else {_e("User");}  ?></small>
                        </div>
                    </td>

                    <td>
                        <small class="text-muted d-block"><?php _e("Username"); ?></small>
                        <?php print $item['username']; ?>
                    </td>

                    <td>
                        <small class="text-muted d-block"><?php _e("Email"); ?></small>
                        <?php print $item['email']; ?>
                    </td>

                    <td>
                        <?php if ($item['is_active'] == 1): ?>
                            <span class="mw-icon-check mw-registered" style="float: none"></span>
                        <?php else: ?>
                            <?php if ($registration_approval_required == 'y' && $item['is_active'] == 0): ?>
                                <span class="mw-icon-unpublish mw-approval-required" data-id="<?php print $item['id']; ?>" style="float: none; "></span>
                            <?php else: ?>
                                <span class="mw-icon-unpublish mw-inactive" data-id="<?php print $item['id']; ?>" style="float: none; "></span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>

                    <td>
                        <?php if ($self_id != $item['id']): ?>
                            <span class="btn btn-outline-danger btn-sm del-row" title="<?php _e("Delete"); ?>" onclick="mw_admin_delete_user_by_id('<?php print $item['id']; ?>')"><?php _e("Delete"); ?></span>
                        <?php endif; ?>

                        <a class="btn btn-outline-primary btn-sm" href="<?php print admin_url('module/view?type=users/edit-user:' . $item['id']); ?>"><?php _e("Edit"); ?></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php if ($paging != false and intval($paging) > 1 and isset($paging_param)): ?>
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

<script type="text/javascript">

    $(document).ready(function () {

        $(".mw-approval-required").mouseenter(function () {
            if ($("#mw-approval-required-tooltip-show-" + $(this).data("id")).length) {
                $("#mw-approval-required-tooltip-show-" + $(this).data("id")).show();
            } else {
                var el = $(".mw-approval-required");
                var text = "Account requires approval";
                mw.tooltip({id: 'mw-approval-required-tooltip-show-' + $(this).data("id"), element: el, content: text, position: 'top-left'}); // 'close_on_click_outside: true' not working and top-left shows top-right
            }
        });

        $(".mw-approval-required").mouseleave(function () {
            $("#mw-approval-required-tooltip-show-" + $(this).data("id")).hide();
        });

        $(".mw-inactive").mouseenter(function () {
            if ($("#mw-inactive-tooltip-show-" + $(this).data("id")).length) {
                $("#mw-inactive-tooltip-show-" + $(this).data("id")).show();
            } else {
                var el = $(".mw-inactive");
                var text = "Account disabled";
                mw.tooltip({id: 'mw-inactive-tooltip-show-' + $(this).data("id"), element: el, content: text, position: 'top-left'});
            }
        });

        $(".mw-inactive").mouseleave(function () {
            $("#mw-inactive-tooltip-show-" + $(this).data("id")).hide();
        });
    });
</script>
