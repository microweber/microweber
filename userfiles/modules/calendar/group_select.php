<?php only_admin_access(); ?>
<script type="text/javascript">
    $(document).ready(function () {

        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _e("All changes are saved"); ?>.");
            mw.reload_module_parent('calendar')

             getData()
            reload_calendar_after_save()
           // window.parent.$(window.parent.document).trigger('calendar.update');
            // mw.reload_module('calendar')
        });
    });
</script>

<?php $groups = calendar_get_groups();  ?>
<?php
$mod_id= $params['id'];
if(isset($params['calendar-group-module-id'])){
    $mod_id= $params['calendar-group-module-id'];
}




?>

<?php $calendar_group_id = get_option('calendar_group_id',$mod_id); ?>


<?php if($groups) { ?>
<label class="pull-right">
    Group:
    <select name="calendar_group_id" class="mw-ui-field js-calendar-group-selector mw_option_field"  option-group="<?php print $mod_id; ?>">
        <option value="0">Default</option>
        <?php foreach ($groups as $group) { ?>
            <option value="<?php print $group['id']; ?>" <?php if($calendar_group_id == $group['id']) { ?>  selected <?php } ?>   >
                <?php print $group['title']; ?>
            </option>
        <?php } ?>
    </select>
</label>
<?php } ?>
