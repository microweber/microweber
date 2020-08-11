<?php only_admin_access(); ?>

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
if(isset($params['mail_template_type'])){
    $get_params['type'] = $params['mail_template_type'];
}


$data = get_mail_templates($get_params); ?>

<?php if ($data): ?>
    <table width="100%" class="mw-ui-table">
        <thead>
        <tr>
			<th>Name</th>
			<th>Type</th>
			<th>Subject</th>
			<th>From Name</th>
			<th>From Email</th>
            <th style="width:70px;">Is Active</th>
            <th style="width:170px;">&nbsp; Action</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data as $item): ?>
            <tr>
	            <td><?php print $item['name'] ?></td>
	            <td><?php print $item['type'] ?></td>
	            <td><?php print $item['subject'] ?></td>
	            <td><?php print $item['from_name'] ?></td>
	            <td><?php print $item['from_email'] ?></td>
                 <td>
                 <center>
                 <?php if($item['is_active']): ?> 
                	 <span style="color: green;"> Yes</span> 
                 <?php else: ?>
                	 <span style="color: green;"> No</span>
                 <?php endif; ?>
                 </center>
                 </td>
                <td>
                  <center>
                <a class="mw-ui-btn"
                       href="javascript:edit_mail_template('<?php print $item['id'] ?>');">Edit
                    </a>
                    <?php if (!isset($item['is_default'])): ?>
                <a class="mw-ui-btn"
                       href="javascript:delete_mail_template('<?php print $item['id'] ?>');">Delete
                    </a>
                    <?php endif; ?>
                     </center>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>