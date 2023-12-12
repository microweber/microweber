<?php if(isset($slide['must-save-from-settings'])): ?>
    <div class="noedit noelement nodrop">

        <p> <?php  print _e('Click on settings to edit accordion item'); ?> </p>

    </div>

<?php else: ?>
    <div class="allow-drop edit" field="accordion-item-<?php print $edit_field_key ?>" rel="module-<?php print $params['id'] ?>">
        <div class="element">
            <p> <?php print isset($slide['content']) ? $slide['content'] : 'Type your text here' ?></p>
        </div>
    </div>

<?php endif; ?>
