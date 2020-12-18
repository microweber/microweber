<?php only_admin_access(); ?>
<script>
    after_event_group_edit = function (id) {
        mw.reload_module('calendar/edit_groups');
        mw.reload_module('calendar/group_select');
    }
    delete_event_group = function (id) {
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
<h3>Select group for calendar</h3>
<module type="calendar/group_select" calendar-group-module-id="<?php print $params['id'] ?>"/>
<hr>

<form id="add_event_group" class="edit_event_groups">
    <label class="mw-ui-label">New group:</label>

    <div class="mw-ui-row">
        <div class="mw-ui-col">
            <div class="mw-ui-col-container">
                <input name="title" class="mw-ui-field mw-full-width" type="text">
            </div>
        </div>
        <div class="mw-ui-col">
            <div class="mw-ui-col-container">
                <button type="submit" class="mw-ui-btn mw-ui-btn-notification">Create</button>
            </div>
        </div>
    </div>
</form>

<br/>
<br/>
<?php $groups = calendar_get_groups(); ?>

<?php if ($groups) { ?>
    <label class="mw-ui-label">Edit groups:</label>
    <?php foreach ($groups as $group) { ?>
        <form class="edit_event_groups">
            <input name="id" type="hidden" value="<?php print $group['id']; ?>">
            <div class="mw-ui-row">
                <div class="mw-ui-col">
                    <div class="mw-ui-col-container">
                        <input name="title" type="text" class="mw-ui-field mw-full-width" value="<?php print $group['title']; ?>">
                    </div>
                </div>
                <div class="mw-ui-col">
                    <div class="mw-ui-col-container">
                        <button type="submit" class="mw-ui-btn mw-ui-btn-notification">Save</button>
                        &nbsp;
                        <a class="mw-ui-btn mw-ui-btn-important mw-ui-btn-outline" href="javascript:delete_event_group('<?php print $group['id']; ?>')"><?php print _e('Delete'); ?></a>
                    </div>
                </div>
            </div>
        </form>
        <br/>
    <?php } ?>
<?php } ?>
