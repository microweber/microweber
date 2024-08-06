<?php if (isset($slide['must-save-from-settings'])): ?>
    <div class="noedit noelement nodrop">

        <p> <?php print _e('Click on settings to edit accordion item'); ?> </p>

    </div>

<?php else: ?>

    <?php if (isset($useTextFromLiveEdit) and $useTextFromLiveEdit): ?>
        <div class="allow-drop edit" field="faq-item-<?php print $edit_field_key ?>"
             rel="module-<?php print $params['id'] ?>">
            <div class="element">
                <p> <?php print isset($slide['answer']) ? $slide['answer'] : 'FAQ Answer' ?></p>
            </div>
        </div>
    <?php else: ?>
        <div class="noedit noelement nodrop">

            <p><?php print isset($slide['answer']) ? $slide['answer'] : 'FAQ Answer' ?></p>

        </div>
    <?php endif; ?>
<?php endif; ?>
