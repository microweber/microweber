<?php must_have_access(); ?>

    <script>
        function delete_mail_template(id) {
            var are_you_sure = confirm("Are you sure?");
            if (are_you_sure == true) {
                var data = {}
                data.id = id;
                var url = "<?php print api_url('delete_mail_template'); ?>";
                var post = $.post(url, data);
                post.done(function (data) {
                    mw.reload_module("admin/mail_templates");
                    mw.reload_module("admin/mail_templates/list");
                });
            }
        }
    </script>

<?php
$get_params = array();
$get_params['no_limit'] = true;
if (isset($params['mail_template_type'])) {
    $get_params['type'] = $params['mail_template_type'];
}

$data = get_mail_templates($get_params); ?>

<?php if ($data): ?>
    <table width="100%" class="table">
        <thead>
        <tr>
            <th><?php _e("Name"); ?></th>
            <th><?php _e("Type"); ?></th>
            <th><?php _e("Subject"); ?></th>
            <th><?php _e("From Name"); ?></th>
            <th><?php _e("From Email"); ?></th>
            <th style="width:120px;" class="text-center"><?php _e("Is Active"); ?></th>
            <th style="width:170px;" class="text-center"><?php _e("Action"); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data as $item): ?>
            <tr>
                <td class="small"><?php print $item['name'] ?></td>
                <td class="small"><?php print $item['type'] ?></td>
                <td class="small"><?php print $item['subject'] ?></td>
                <td class="small"><?php print $item['from_name'] ?></td>
                <td class="small"><?php print $item['from_email'] ?></td>
                <td class="text-center">
                    <?php if ($item['is_active']): ?>
                        <span style="color: green;"> Yes</span>
                    <?php else: ?>
                        <span style="color: green;"> No</span>
                    <?php endif; ?>
                <td class="text-center">
                    <a class="btn btn-outline-primary btn-sm" href="javascript:edit_mail_template('<?php print $item['id'] ?>');"><?php _e("Edit"); ?></a>
                    <?php if (!isset($item['is_default'])): ?>
                        <a class="btn btn-outline-danger btn-sm" href="javascript:delete_mail_template('<?php print $item['id'] ?>');"><?php _e("Delete"); ?></a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>