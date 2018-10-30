<?php only_admin_access(); ?>



<?php
$text = get_option('text', $params['id']);

if ($text == false) {
    $text = '<?php print "Hello Wordld"; ?>';
}
?>

<div class="module-live-edit-settings module-highlight-code-settings">
    <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php _e('Enter some text'); ?></label>
        <textarea class="mw_option_field mw-ui-field w100" rows="20" name="text"><?php print $text; ?></textarea>
    </div>
</div>
