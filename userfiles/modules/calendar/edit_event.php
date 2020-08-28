<?php must_have_access(); ?>

<script>
    mw.require("<?php  print  modules_url() ?>calendar/calendar_admin.js");
</script>

<?php
$data = false;
$add_new = false;

if (isset($params['event_id'])) {
    $data = calendar_get_event_by_id($params['event_id']);
    if (!empty($data)) {
        // $data['start_date'] = date("Y-m-d", strtotime($data['start_date']));
        // $data['end_date'] = date("Y-m-d", strtotime($data['end_date']));
        $data['start_time'] = date("H:i", strtotime($data['start_time']));
        $data['end_time'] = date("H:i", strtotime($data['end_time']));
    }
}

if (empty($data)) {
    $add_new = true;
    $data = array(
        'id' => "0",
        'active' => 1,
        'content_id' => "",
        'title' => "",
        'start_date' => date("Y-m-d"),
        'start_time' => date("H:i"),
        'end_date' => date("Y-m-d"),
        'end_time' => date("H:i"),
        'description' => "",
        'short_description' => "",
        'all_day' => "",
        "calendar_group_id" => "",
        "image_url" => "",
        "link_url" => "",
        "recurrence_repeat_every" => 1
    );
}

?>

<style type="text/css">
    #editEventForm .form-control, #editEventForm .mw-component-post-search {
        width: 100%;
    }

    .table td {
        padding-left: 5px;
        padding-right: 5px;
    }

    .table .check span:last-child {
        margin-left: 5px;
    }

    #up_img{
        margin:15px 0;max-width:250px;display: block
    }
</style>

<script type='text/javascript'>mw.lib.require('datepicker');</script>

<script type='text/javascript'>
    var event_data = <?php echo json_encode($data); ?>;
    var calendar_api_save_event = "<?php echo api_url('calendar_save_event'); ?>";

    mw.require("<?php echo $config['url_to_module'];?>js/javascript-helper.js");
    mw.require("<?php echo $config['url_to_module'];?>js/date-helper.js");
    mw.require("<?php echo $config['url_to_module'];?>js/jquery.timepicker.min.css");
    mw.require("<?php echo $config['url_to_module'];?>js/jquery.timepicker.min.js");

    // SEARCH CONTENT
    var content_id = <?php echo !!$data['content_id'] ? $data['content_id'] : 'false'; ?>;

    $(window).on('load', function () {
        $("#postSearch").on("postSelected", function (event, data) {
            content_id = data.id;
            onSelectedPost(content_id);
        });

        editEventModal = window.thismodal;
        //console.log(editEventModal)

        onSelectedPost(content_id);
    })

    function onSelectedPost(content_id) {
        if (content_id) {
            mw.tools.getPostById(content_id, function (posts) {
                var post = posts[0];
                $("#postSearch")[0].value = post.title;
                $("#content_id_from_search").val(post.id);
                $("#postSearch")[0]._value = post;
            });
        }
    }
</script>

<script src="<?php echo $config['url_to_module']; ?>js/image-upload.js" type='text/javascript'></script>
<script src="<?php echo $config['url_to_module']; ?>js/edit-event-ajax.js" type='text/javascript'></script>
<script src="<?php echo $config['url_to_module']; ?>js/edit-event.js" type='text/javascript'></script>

<form id="editEventForm">
    <input type="hidden" name="id" value="<?php echo $data['id'] ?>"/>

    <div class="form-group">
        <label class="control-label">Title</label>
        <input type="text" name="title" class="form-control" value="<?php echo $data['title'] ?>" id="event-title" required/>
    </div>

    <div class="form-group">
        <label class="control-label">Short Description</label>
        <input type="text" name="short_description" class="form-control" value="<?php echo $data['short_description'] ?>" id="event-short_description"/>
    </div>

    <div class="form-group">
        <label class="control-label">Description</label>
        <textarea name="description" class="form-control" id="event-desc"><?php echo $data['description'] ?></textarea>
    </div>

    <div class="form-group">
        <label class="control-label d-block">Image</label>
        <input type="hidden" name="image_url" value="<?php echo $data['image_url'] ?>" id="event-img"/>
        <img id="up_img" src="<?php echo $data['image_url'] ?>"/>

        <span id="mw_uploader" class="btn btn-primary">
            <span class="ico iupload"></span> &nbsp;
            <span>Upload file<span id="upload_info"></span></span>
        </span>
    </div>

    <div class="row">
        <div class="col">
            <div class="form-group">
                <label class="control-label">Link</label>
                <input type="text" name="link_url" autocomplete="off" class="form-control" value="<?php echo $data['link_url'] ?>"/>
            </div>
        </div>

        <div class="col">
            <?php
            $post_title = '';
            if (isset($data['content_id']) and $data['content_id'] == 1) {
                $post = get_content_by_id($data['content_id']);

                if (isset($post['title'])) {
                    $post_title = $post['title'];
                }
            }
            ?>

            <div class="form-group">
                <label for="postSearch" class="control-label"><?php _e('Connected post'); ?></label>
                <input id="postSearch" autocomplete="off" class="form-control colElement" type="text" value="<?php echo $post_title ?>" name="" data-mwcomponent="postSearch"/>

                <input type="hidden" name="content_id" id='content_id_from_search' value="<?php echo $data['content_id'] ?>"/>
            </div>
        </div>
    </div>

    <hr class="thin">

    <div class="row d-flex align-items-center">
        <div class="col">
            <div class="form-group">
                <label class="control-label">Start Date</label>
                <input type="text" name="start_date" autocomplete="off" class="form-control js-start-date" value="<?php echo $data['start_date'] ?>"/>
            </div>
        </div>

        <div class="col js-start-time-wrapper">
            <div class="form-group">
                <label class="control-label">Start Time</label>
                <input type="text" name="start_time" autocomplete="off" class="form-control" value="<?php echo $data['start_time'] ?>"/>
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <label class="control-label">End Date</label>
                <input type="text" name="end_date" autocomplete="off" class="form-control" value="<?php echo $data['end_date'] ?>"/>
            </div>
        </div>
        <div class="col">
            <div class="form-group js-end-time-wrapper">
                <label class="control-label">End Time</label>
                <input type="text" name="end_time" autocomplete="off" class="form-control" value="<?php echo $data['end_time'] ?>"/>
            </div>
        </div>
        <div class="col">
            <div class="form-group m-0">
                <div class="custom-control custom-checkbox m-0">
                    <input type="checkbox" name="all_day" class="js-all-day custom-control-input" id="all_day" value="1" data-value-checked="1" data-value-unchecked="0"/>
                    <label class="custom-control-label" for="all_day">All day</label>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <select name="recurrence_type" class="js-select-recurrence selectpicker" data-width="100%" data-size="5">
            <option value="doesnt_repeat">Doesn't repeat</option>
            <option value="daily">Daily</option>
            <option value="weekly_on_the_day_name">Weekly on the day_name</option>
            <option value="weekly_on_the_days_names" hidden>Weekly on the days_names</option>
            <option value="weekly_on_all_days">Weekly on all days</option>
            <option value="every_weekday">Every weekday (Monday to Friday)</option>
            <option value="monthly_on_the_day_number"></option>
            <option value="monthly_on_the_week_number_day_name"></option>
            <option value="annually_on_the_month_name_day_number"></option>
            <!-- Annually on <?php echo date("d M"); ?> -->
            <option value="custom">Custom</option>
        </select>
    </div>

    <div class="js-custom-recurrence-wrapper" style="display:none;border:1px #e5e5e5 dashed;padding: 15px;">
        <div class="form-group">
            <label class="control-label">Repeat every</label>

            <div class="row">
                <div class="col">
                    <input type="number" name="recurrence_repeat_every" min="1" value="<?php echo $data['recurrence_repeat_every']; ?>" class="form-control">
                </div>
                <div class="col">
                    <div class="col-container">
                        <select name="recurrence_repeat_type" class="js-recurrence-repeat-type selectpicker" data-width="100%" data-size="5">
                            <option value="day">Day</option>
                            <option value="week">Week</option>
                            <option value="month">Month</option>
                            <option value="year">Year</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="js-recurrence-repeat-on" style="display:none;">
            <label class="control-label">Repeat on</label>
            <div class="row">
                <div class="col">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="recurrence_repeat_on[monday]" value="1" name="recurrence_repeat_on[monday]">
                        <label class="custom-control-label" for="recurrence_repeat_on[monday]">Monday</label>
                    </div>
                </div>

                <div class="col">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="recurrence_repeat_on[tuesday]" value="1" name="recurrence_repeat_on[tuesday]">
                        <label class="custom-control-label" for="recurrence_repeat_on[tuesday]">Tuesday</label>
                    </div>
                </div>

                <div class="col">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="recurrence_repeat_on[wednesday]" value="1" name="recurrence_repeat_on[wednesday]">
                        <label class="custom-control-label" for="recurrence_repeat_on[wednesday]">Wednesday</label>
                    </div>
                </div>

                <div class="col">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="recurrence_repeat_on[thursday]" value="1" name="recurrence_repeat_on[thursday]">
                        <label class="custom-control-label" for="recurrence_repeat_on[thursday]">Thursday</label>
                    </div>
                </div>

                <div class="col">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="recurrence_repeat_on[friday]" value="1" name="recurrence_repeat_on[friday]">
                        <label class="custom-control-label" for="recurrence_repeat_on[friday]">Friday</label>
                    </div>
                </div>

                <div class="col">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="recurrence_repeat_on[saturday]" value="1" name="recurrence_repeat_on[saturday]">
                        <label class="custom-control-label" for="recurrence_repeat_on[saturday]">Saturday</label>
                    </div>
                </div>

                <div class="col">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="recurrence_repeat_on[sunday]" value="1" name="recurrence_repeat_on[sunday]">
                        <label class="custom-control-label" for="recurrence_repeat_on[sunday]">Sunday</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="js-recurrence-monthly-on" style="display:none;">
            <select name="recurrence_monthly_on" class="js-recurrence-monthly-on selectpicker" data-width="100%" data-size="5">
                <option value="the_day_number">Monthly on day</option>
                <option value="the_week_number_day_name">Monthly on the</option>
            </select>
        </div>
    </div>

    <module type="calendar/group_select" calendar-event-id="<?php echo $data['id'] ?>"/>

    <div class="form-group">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" name="active" class="js-active custom-control-input" value="1" data-value-checked="1" data-value-unchecked="0" id="active" <?php if ($data['active'] == "1"): ?>checked<?php endif; ?> />
            <label class="custom-control-label" for="active">Active</label>
        </div>
    </div>

    <hr class="thin">

    <div class="d-flex justify-content-between">
        <div>
            <?php if (!$add_new): ?>
                <a class="btn btn-danger btn-sm" href="javascript:deleteEvent('<?php echo $data['id'] ?>')">Delete</a>
            <?php endif; ?>
        </div>

        <div>
            <span class="btn btn-secondary btn-sm" onclick="thismodal.remove()">Cancel</span>
            <button class="btn btn-success btn-sm" type="submit">Save</button>
        </div>
    </div>
</form>
