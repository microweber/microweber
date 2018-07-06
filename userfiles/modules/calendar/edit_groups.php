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

<form id="add_event_group" class="edit_event_groups">
    <label>New group:</label>
    <input class="mw-ui-field" name="title" type="text">
    <button class="mw-ui-btn" type="submit">Create</button>
</form>

<?php $groups = calendar_get_groups(); ?>

<?php if ($groups) { ?>
    <?php foreach ($groups as $group) { ?>
        <form class="edit_event_groups">
            <input name="id" type="hidden" value="<?php print $group['id']; ?>">
            <input name="title" type="text" class="mw-ui-field" value="<?php print $group['title']; ?>">
            <button class="mw-ui-btn" type="submit">Save</button>
            <a class="mw-ui-btn" href="javascript:delete_event_group('<?php print $group['id']; ?>')">X</a>
        </form>
    <?php } ?>
<?php } ?>
