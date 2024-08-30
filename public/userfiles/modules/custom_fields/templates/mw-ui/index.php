<?php

/*
 *
 * type: layout
 *
 * name: MW UI
 *
 * description: MW UI
 *
 */
?>
<div class="mw-flex-row">
    <?php if (!empty($fields_group)): ?>
        <?php foreach ($fields_group as $fields): ?>

            <?php if (!empty($fields)): ?>

                <?php foreach ($fields as $field): ?>
                    <?php echo $field['html']; ?>
                <?php endforeach; ?>

            <?php endif; ?>

        <?php endforeach; ?>
    <?php endif; ?>
</div>