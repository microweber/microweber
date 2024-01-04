<?php must_have_access(); ?>
<?php
$templates = newsletter_get_templates();
?>

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
                        <a href="<?php print route('admin.newsletter.templates.edit', $template['id']); ?>" class="btn btn-outline-primary btn-sm"><?php _e('Edit'); ?></a>
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
