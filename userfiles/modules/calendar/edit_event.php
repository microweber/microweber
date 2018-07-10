<?php only_admin_access(); ?>
<?php
$data = false;
if (isset($params['event_id'])) {
    $data = calendar_get_event_by_id($params['event_id']);
}
if (!$data) {
    return;
}
?>

<style type="text/css">
    #editEventForm .mw-ui-field, #editEventForm .mw-component-post-search{
        width: 100%;
    }
</style>
<script type='text/javascript'>
    var content_id = <?php print !!$data['content_id'] ? $data['content_id'] : 'false'; ?>;
    mw.lib.require('datetimepicker');
    $(document).ready(function() {
        $("#postSearch").on("postSelected", function(event, data){
            content_id = data.id;
        });
        if(content_id){
            mw.tools.getPostById(content_id, function(posts){
                var post = posts[0];
                $("#postSearch")[0].value = post.title;
                $("#postSearch")[0]._value = post;
            });
        }
        //option A
        $("#editEventForm").submit(function(e){
            e.preventDefault(e);
            var form_data = '?'+$(this).serialize();
            form_data = mw.url.set_param('content_id', content_id, form_data ).replace('?','');
            $.ajax({
                url: '<?php print api_url('calendar_change_title');?>',
                data: form_data,
                type: 'POST',
                dataType: 'json',
                success: function (response) {
                    if (response.status == 'success'){
                        getData();
                        $('#calendar').fullCalendar('removeEvents');
                        $('#calendar').fullCalendar('addEventSource', json_events);
                        reload_calendar_after_save();
                    }
                },
                error: function (e) {
                    alert('Error processing your request: ' + e.responseText);
                }
            });
        });
        $('[name="startdate"]').datetimepicker({
            zIndex:1105
        });
        $('[name="enddate"]').datetimepicker({
            zIndex:1105
        });
    });
</script>

<form id="editEventForm">
    <input type="hidden" name="id" value="<?php print $data['id'] ?>" />
    <div class="mw-ui-field-holder">
        <label class="mw-ui-label">Title</label>
        <input type="text" name="title" class="mw-ui-field" value="<?php print $data['title'] ?>" />
    </div>

    <div class="mw-ui-field-holder">
        <label class="mw-ui-label">Start Date</label>
        <input type="text" name="startdate" autocomplete="off" class="mw-ui-field" value="<?php print $data['startdate'] ?>" />
    </div>

    <div class="mw-ui-field-holder">
        <label class="mw-ui-label">End Date</label>
        <input type="text" name="enddate" autocomplete="off" class="mw-ui-field" value="<?php print $data['enddate'] ?>" />
    </div>

    <div class="mw-ui-field-holder">
        <label class="mw-ui-label">Description</label>
        <textarea name="description" class="mw-ui-field"><?php print $data['description'] ?></textarea>
    </div>

    <div class="mw-ui-field-holder">
        <label class="mw-ui-label">Image</label>
        <input type="hidden" name="image_url" value="<?php print $data['image_url'] ?>" />
        <span id="mw_uploader" class="mw-ui-btn">
            <span class="ico iupload"></span>
            <span>Upload file <span id="upload_info"></span></span>
        </span>
    </div>

    <div class="mw-ui-field-holder">
        <label class="mw-ui-label">Link</label>
        <input type="text" name="link_url" autocomplete="off" class="mw-ui-field" value="<?php print $data['link_url'] ?>" />
    </div>

    <div class="mw-ui-field-holder">
        <label class="mw-ui-label">All day</label>

        <label class="mw-ui-check">
            <input type="radio" name="allDay" value="1" <?php if (isset($data['allDay']) and $data['allDay'] == 1) { ?> checked <?php }  ?> />
            <span></span><span>Yes</span>
        </label>

        <label class="mw-ui-check">
            <input type="radio" name="allDay" value="" <?php if (!isset($data['allDay']) or !$data['allDay']) { ?> checked <?php }  ?>  />
            <span></span><span>No</span>
        </label>
    </div>


    <?php if (isset($data['content_id']) and $data['content_id'] == 1) { ?>
    <?php $post = get_content_by_id($data['content_id']) ?>
    <?php }  ?>

    <div class="mw-ui-field-holder">
        <label for="postSearch" class="mw-ui-label"><?php _e('Connected post'); ?></label>
        <input id="postSearch" autocomplete="off" class="mw-ui-field colElement w100" type="text" value="<?php print $data['content_id'] ?>"   name="content_id" data-mwcomponent="postSearch" />
    </div>
    <hr>
    <div class="mw-ui-btn-nav pull-right">
        <span class="mw-ui-btn " onclick="editModal.modal.remove()">Cancel</span>
        <button class="mw-ui-btn mw-ui-btn-invert " onclick='$("#editEventForm").submit();'>Save</button>
    </div>
    <div class="mw-ui-btn-nav pull-left">
        <span class="mw-ui-btn">Delete</span>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function(){
        var uploader = mw.uploader({
            filetypes: "images",
            multiple: false,
            element: "#mw_uploader"
        });

        $(uploader).bind("FileUploaded", function(event, data){
            mw.$("#mw_uploader_loading").hide();
            mw.$("#mw_uploader").show();
            mw.$("#upload_info").html("");
            $('input[name="image_url"]').val(data.src);
        });

        $(uploader).bind('progress', function(up, file) {
            mw.$("#mw_uploader").hide();
            mw.$("#mw_uploader_loading").show();
            mw.$("#upload_info").html(file.percent + "%");
        });

        $(uploader).bind('error', function(up, file) {
            mw.notification.error("The file is not uploaded.");
        });

    });
</script>