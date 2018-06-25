<?php only_admin_access(); ?>
<?php

$data = false;
if (isset($params['event_id'])) {
    $data = calendar_get_event_by_id($params['event_id']);
}
if (!$data) {
    return;
}


d($data);


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

        });



        mw.tools.getPostById(content_id, function(posts){
            var post = posts[0];
        });




        //option A
        $("#editEventForm").submit(function(e){

            e.preventDefault(e);
            var form_data = '?'+$(this).serialize();

            form_data = mw.url.set_param('content_id', 1, form_data ).replace('?','');
            console.log(form_data)

            $.ajax({
                url: '<?php print api_url('calendar_change_title');?>',
                data: form_data,
                type: 'POST',
                dataType: 'json',
                success: function (response) {
                    if (response.status == 'success'){
                        getData();
                        $('#calendar').fullCalendar('removeEvents');
                        $('#calendar').fullCalendar('addEventSource', JSON.parse(json_events));
                        reload_calendar_after_save()
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
        <?php print content_title($data['content_id']) ?>
        <?php print content_picture($data['content_id']) ?>

    <?php }  ?>



    <div class="mw-ui-field-holder">
        <label for="postSearch" class="mw-ui-label"><?php _e('Connected post'); ?></label>
        <input id="postSearch" autocomplete="off" class="mw-ui-field colElement w100" type="text" value="<?php print $data['content_id'] ?>"   name="content_id" data-mwcomponent="postSearch" />
    </div>


<hr>
    <button class="mw-ui-btn mw-ui-btn-invert pull-right" onclick='$("#editEventForm").submit();'>Save</button>
    <span class="mw-ui-btn pull-right" onclick="mw_admin_open_module_modal_popup_modal_opened.remove()">Cancel</span>



</form>
