<div class="mw-module-select-template">


    select template

    <select wire:model.debounce.100ms="settings.template">
        <option value="default" <?php if (('default' == $currentTemplate)): ?>   selected="selected" <?php endif; ?>>
            <?php _e("Default"); ?>
        </option>

        <?php foreach ($moduleTemplates as $item): ?>
            <?php if ((strtolower($item['name']) != 'default')): ?>
            <?php $default_item_names[] = $item['name']; ?>
        <option <?php if (($item['layout_file'] == $currentTemplate)): ?>   selected="selected"
                <?php endif; ?> value="<?php print $item['layout_file'] ?>"
                title="Template: <?php print str_replace('.php', '', $item['layout_file']); ?>"> <?php print $item['name'] ?> </option>
        <?php endif; ?>
        <?php endforeach; ?>
    </select>


    <?php

    var_dump($moduleId);
    var_dump($moduleTemplates);
    var_dump($currentTemplate);

    ?>





</div>
