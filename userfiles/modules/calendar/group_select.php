<?php only_admin_access(); ?>

<?php
$use_only_as_input = false;
$calendar_group_id = false;

?>

<?php $groups = calendar_get_groups(); ?>
<?php
$mod_id = $params['id'];


if (isset($params['calendar-group-module-id'])) {
    $mod_id = $params['calendar-group-module-id'];
    $use_only_as_input = false;
    $calendar_group_id = get_option('calendar_group_id', $mod_id);
}

if (isset($params['calendar-event-id'])) {
    $use_only_as_input = true;
    $event_data = calendar_get_event_by_id($params['calendar-event-id']);
    if (isset($event_data['calendar_group_id'])) {
        $calendar_group_id = $event_data['calendar_group_id'];
    }
}


?>
<?php if (!$use_only_as_input) {
    ?>
    <script type="text/javascript">
        $(document).ready(function () {

            mw.options.form('.<?php print $config['module_class'] ?>', function () {
                mw.notification.success("<?php _ejs("All changes are saved"); ?>.");
                mw.reload_module_parent('calendar')

                getData()
                reload_calendar_after_save()
                //window.parent.$(window.parent.document).trigger('calendar.update');
                mw.reload_module('calendar')
            });
        });
    </script>
    <?php
} ?>
<?php if ($groups) { ?>
    <label>
        <label class="mw-ui-label">Group:</label>
        <select name="calendar_group_id" class="mw-ui-field js-calendar-group-selector mw_option_field mw-full-width" option-group="<?php print $mod_id; ?>">
            <option value="0">Default</option>
            <?php foreach ($groups as $group) {
                ?>
                <option
                        value="<?php print $group['id']; ?>" <?php if ($calendar_group_id == $group['id']) {
                    ?>  selected <?php
                } ?> >
                    <?php print $group['title']; ?>
                </option>
                <?php
            } ?>
        </select>
    </label>
<?php } else { ?>
    <input type="hidden" name="calendar_group_id" value="0"/>
<?php } ?>
