<?php

only_admin_access();


$update_api = new \Microweber\Update();




?>apply_updates


<?php if (isset($_REQUEST['mw_version'])) { ?>

    <h2><?php _e("Installing new version of Microweber"); ?>: <?php print  $_REQUEST['mw_version'] ?></h2>
    <textarea>
        <?php $iudates = $update_api->install_version($_REQUEST['mw_version']);
        d($iudates);
        ?>
    </textarea>

<?php } ?>

<?php
if (isset($_REQUEST['modules'])) {
    ?>
    <?php if (is_array($_REQUEST['modules'])): ?>
        <?php foreach ($_REQUEST['modules'] as $item): ?>
            <h2><?php _e('Installing module:'); ?> <?php print  $item ?></h2>
            <textarea>
                <?php $iudates = $update_api->install_module($item);
                d($iudates);
                ?>
            </textarea>
        <?php endforeach; ?>
    <?php endif; ?>
<?php } ?>



<?php
if (isset($_REQUEST['elements'])) {
    ?>
    <?php if (is_array($_REQUEST['elements'])): ?>
        <?php foreach ($_REQUEST['elements'] as $item): ?>
            <h2><?php _e('Installing layouts:'); ?> <?php print  $item ?></h2>
            <textarea>
                <?php $iudates = $update_api->install_element($item);
                d($iudates);
                ?>
            </textarea>
        <?php endforeach; ?>
    <?php endif; ?>
<?php } ?>



<?php
if (isset($_REQUEST['module_templates'])) {
    ?>
    <?php if (is_array($_REQUEST['module_templates'])): ?>
        <?php foreach ($_REQUEST['module_templates'] as $k => $item): ?>
            <h2><?php _e('Installing module template:'); ?> <?php print  $item ?> (for <em><?php print $k ?></em>)</h2>
            <textarea>
                <?php $iudates = $update_api->install_module_template($k, $layout_file);
                d($iudates);
                ?>
            </textarea>
        <?php endforeach; ?>
    <?php endif; ?>
<?php } ?>


<?php
if (isset($_REQUEST['templates'])) {
    ?>
    <?php if (is_array($_REQUEST['templates'])): ?>
        <?php foreach ($_REQUEST['templates'] as $k => $item): ?>
            <h2><?php _e('Installing template:'); ?> <?php print  $item ?> (for <em><?php print $k ?></em>)</h2>
            <textarea>
                <?php $iudates = $update_api->install_template($item);
                d($iudates);
                ?>
            </textarea>
        <?php endforeach; ?>
    <?php endif; ?>
<?php } ?>