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
<script type='text/javascript'>
    $(document).ready(function() {


        $("#postSearch").on("postSelected", function(event, data){

        })



        //option A
        $("#editEventForm").submit(function(e){


            var form_data = $(this).serialize();
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
            e.preventDefault(e);
        });
    });
</script>

<form id="editEventForm">


    <input type="hidden" name="id" value="<?php print $data['id'] ?>" />
    <br>

    title
    <input type="text" name="title" value="<?php print $data['title'] ?>" />



    <br>

    startdate
    <input type="text" name="startdate" value="<?php print $data['startdate'] ?>" />


    <br>

    enddate
    <input type="text" name="enddate" value="<?php print $data['enddate'] ?>" />


    <br>
    description
    <textarea name="description"><?php print $data['description'] ?></textarea>


    <br>
    allDay

    <input type="radio" name="allDay" value=""  />
    <input type="radio" name="allDay" value="1" <?php if (isset($data['allDay']) and $data['allDay'] == 1) { ?> checked <?php }  ?> />





    <br>
    content


    <?php if (isset($data['content_id']) and $data['content_id'] == 1) { ?>
    <?php $post = get_content_by_id($data['content_id']) ?>
        <?php print content_title($data['content_id']) ?>
        <?php print content_picture($data['content_id']) ?>

    <?php }  ?>



    <div class="mw-ui-field-holder">
        <label for="postSearch" class="mw-ui-label"><?php _e('Connected post'); ?></label>
        <input id="postSearch" class="mw-ui-field colElement w100" type="text" value="<?php print $data['content_id'] ?>"   name="content_id" data-mwcomponent="postSearch" />
    </div>



<button>Save</button>


</form>
