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
                mw.reload_module_everywhere('dynamic_text')
                mw.reload_module_everywhere('dynamic_text/list')
                mw.reload_module_everywhere('dynamic_text/select')
            });
        }
    }
</script>

<?php
$dynamic_texts = get_dynamic_text();



?>
<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
            <th><?php _e('Name'); ?></th>
            <th><?php _e('Content'); ?></th>
            <th><?php _e('Action'); ?></th>
        </tr>
        </thead>
        <tbody>
            <?php if(is_array($dynamic_texts)) : ?>
                <?php foreach($dynamic_texts as $dynamic_text) : ?>
                <tr class="small td-valign">
                    <td>
                        <label><?php echo $dynamic_text['name'];?></label>
                    </td>
                    <td style="word-wrap: break-word"><?php echo $dynamic_text['content'];?></td>
                    <td>
                        <a href="javascript:;" onclick="edit_dynamic_text(<?php echo $dynamic_text['id'];?>);" class="btn  btn-sm btn-secondary">Edit</a>
                        <a href="javascript:;" onclick="delete_dynamic_text(<?php echo $dynamic_text['id'];?>);" class="btn btn-sm  btn-secondary">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

</div>
