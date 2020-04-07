<?php only_admin_access(); ?>
<script>
    function delete_dynamic_text(id) {
        var are_you_sure = confirm("Are you sure?");
        if (are_you_sure == true) {
            var data = {}
            data.id = id;
            var url = "<?php print api_url('delete_dynamic_text'); ?>";
            var post = $.post(url, data);
            post.done(function (data) {
                window.location.href = window.location.href;
            });
        }
    }
</script>

<?php
$dynamic_texts = get_dynamic_text();
?>

<table class="mw-ui-table" width="100%" cellspacing="0" cellpadding="0">
    <thead>
    <tr>
        <th>Variable</th>
        <th>Content</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>

        <?php if(is_array($dynamic_texts)) : ?>
            <?php foreach($dynamic_texts as $dynamic_text) : ?>
            <tr>
                <td><?php echo $dynamic_text['variable'];?></td>
                <td><?php echo $dynamic_text['content'];?></td>
                <td>
                    <a href="javascript:;" onclick="edit_dynamic_text(<?php echo $dynamic_text['id'];?>);" class="mw-ui-btn mw-ui-btn-medium">Edit</a>
                    <a href="javascript:;" onclick="delete_dynamic_text(<?php echo $dynamic_text['id'];?>);" class="mw-ui-btn mw-ui-btn-medium">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>

    </tbody>
</table>
