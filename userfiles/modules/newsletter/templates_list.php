<?php must_have_access(); ?>
<?php
$templates = newsletter_get_templates();
?>
<script>
    function edit_template(id = false) {
        var data = {};
        data.id = id;

        mw.notification.success('<?php _ejs('Loading...'); ?>');

        if (data.id > 0) {
            $.ajax({
                url: mw.settings.api_url + 'newsletter_get_template',
                type: 'POST',
                data: data,
                success: function (result) {

                    $('.js-edit-template-id').val(result.id);
                    $('.js-edit-template-title').val(result.title);
                    $('.js-edit-template-text').val(result.text);

                    initEditor(result.text);
                }
            });
        } else {
            $('.js-edit-template-id').val('0');
            $('.js-edit-template-title').val('');
            $('.js-edit-template-text').val('');

            initEditor('');
        }

        $('.js-templates-list-wrapper').slideUp();
        $('.js-edit-template-wrapper').slideDown();
    }

</script>


<div class="card mt-2">
    <div class="card-body">
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h4>List of templates</h4>
    </div>
    <div>
        <a href="javascript:;" onclick='Livewire.emit("openModal", "admin-newsletter-choose-template-modal")'
           class="btn btn-outline-primary mb-3"
        >
            <i class="mdi mdi-plus"></i>  <?php _e('Add new template'); ?>
        </a>
    </div>
    </div>

    <?php if ($templates): ?>
        <div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th class="font-weight-bold"><?php _e('Title'); ?></th>
                <th class="font-weight-bold"><?php _e('Date'); ?></th>
                <th class="font-weight-bold text-center" width="140px"><?php _e('Action'); ?></th>
            </tr>
        </thead>

        <tbody class="small">
            <?php foreach ($templates as $template): ?>
                <tr>
                    <td><?php print $template['title']; ?></td>
                    <td><?php print $template['created_at']; ?></td>
                    <td class="text-center">
                        <button class="btn btn-outline-primary btn-sm" onclick="edit_template('<?php print $template['id']; ?>')"><?php _e('Edit'); ?></button>
                        <a class="btn btn-outline-danger btn-sm" href="javascript:;" onclick="delete_template('<?php print $template['id']; ?>')"><i class="mdi mdi-trash-can-outline"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
    <?php else: ?>
        <div class="alert alert-info"><?php _e("No templates found"); ?></div>
    <?php endif; ?>

    </div>
    </div>
