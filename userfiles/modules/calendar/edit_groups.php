<?php only_admin_access(); ?>
<script>
after_event_group_edit = function(id){
    mw.reload_module('calendar/edit_groups');
    mw.reload_module('calendar/group_select');
}
delete_event_group = function(id){
    var r = confirm("Are you sure you want to delete this event group.");
    if (r != true) {
        return;
    }
    var del = {}
    del.id = id;
    var actionurl = mw.settings.api_url + 'calendar_delete_group';
    $.ajax({
        url: actionurl,
        type: 'post',
        dataType: 'application/json',
        data: del,
        complete: after_event_group_edit
    });
}
</script>
<script>
$(function () {
    $(".edit_event_groups").submit(function (e) {
        e.preventDefault();
        var actionurl = mw.settings.api_url + 'calendar_save_group';
        $.ajax({
            url: actionurl,
            type: 'post',
            dataType: 'application/json',
            data: $(this).serialize(),
            complete: after_event_group_edit
        });
    });
});
</script>
Select group for calendar
<module type="calendar/group_select" calendar-group-module-id="<?php print $params['id'] ?>"  />
<hr>

<form id="add_event_group" class="edit_event_groups">
    <label>New group:</label>
    <input name="title"  class="mw-ui-field"  type="text">
    <button type="submit" class="mw-ui-btn mw-ui-btn-notification mw-ui-btn-small">Create</button>
</form>
<br />
<br />
<?php $groups = calendar_get_groups(); ?>

<?php if ($groups) { ?>
    <?php foreach ($groups as $group) { ?>
        <form class="edit_event_groups">
            <input name="id" type="hidden" value="<?php print $group['id']; ?>">
            <input name="title" type="text" class="mw-ui-field" value="<?php print $group['title']; ?>">
            <button type="submit" class="mw-ui-btn mw-ui-btn-notification mw-ui-btn-small">Save</button>
            <a class="mw-ui-btn mw-ui-btn-default mw-ui-btn-small" href="javascript:delete_event_group('<?php print $group['id']; ?>')">X</a>
        </form>
        <br />
    <?php } ?>
<?php } ?>
