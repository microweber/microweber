<?php only_admin_access(); ?>

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
    #editEventForm .mw-ui-field, #editEventForm .mw-component-post-search {
        width: 100%;
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
            console.log(editEventModal)

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

    <div class="mw-ui-field-holder">
        <label class="mw-ui-label">Title</label>
        <input type="text" name="title" class="mw-ui-field" value="<?php echo $data['title'] ?>" id="event-title" required/>
    </div>

    <div class="mw-ui-field-holder">
        <label class="mw-ui-label">Short Description</label>
        <input type="text" name="short_description" class="mw-ui-field" value="<?php echo $data['short_description'] ?>" id="event-short_description"/>
    </div>

    <div class="mw-ui-field-holder">
        <label class="mw-ui-label">Description</label>
        <textarea name="description" class="mw-ui-field" id="event-desc"><?php echo $data['description'] ?></textarea>
    </div>

    <div class="mw-ui-field-holder">
        <label class="mw-ui-label">Image</label>
        <input type="hidden" name="image_url" value="<?php echo $data['image_url'] ?>" id="event-img"/>
        <span id="mw_uploader" class="mw-ui-btn">
            <span class="ico iupload"></span> &nbsp;
            <span>Upload file<span id="upload_info"></span></span>
        </span>
        <br/>
        <img src="<?php echo $data['image_url'] ?>" style="margin-top:15px;width:250px;"/>
    </div>


    <div class="mw-ui-row">
        <div class="mw-ui-col">
            <div class="mw-ui-col-container">
                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label">Link</label>
                    <input type="text" name="link_url" autocomplete="off" class="mw-ui-field" value="<?php echo $data['link_url'] ?>"/>
                </div>
            </div>
        </div>

        <div class="mw-ui-col">
            <div class="mw-ui-col-container">
                <?php
                $post_title = '';
                if (isset($data['content_id']) and $data['content_id'] == 1) {
                    $post = get_content_by_id($data['content_id']);

                    if (isset($post['title'])) {
                        $post_title = $post['title'];
                    }
                }
                ?>

                <div class="mw-ui-field-holder">
                    <label for="postSearch" class="mw-ui-label"><?php _e('Connected post'); ?></label>
                    <input id="postSearch" autocomplete="off" class="mw-ui-field colElement w100" type="text" value="<?php echo $post_title ?>" name="" data-mwcomponent="postSearch"/>

                    <input type="hidden" name="content_id" id='content_id_from_search' value="<?php echo $data['content_id'] ?>"/>
                </div>
            </div>
        </div>
    </div>


    <hr>

    <div class="mw-ui-row">
        <div class="mw-ui-col">
            <div class="mw-ui-col-container">
                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label">Start Date</label>
                    <input type="text" name="start_date" autocomplete="off" class="mw-ui-field js-start-date" value="<?php echo $data['start_date'] ?>"/>
                </div>
            </div>
        </div>

        <div class="mw-ui-col js-start-time-wrapper">
            <div class="mw-ui-col-container">
                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label">Start Time</label>
                    <input type="text" name="start_time" autocomplete="off" class="mw-ui-field" value="<?php echo $data['start_time'] ?>"/>
                </div>
            </div>
        </div>
        <div class="mw-ui-col">
            <div class="mw-ui-col-container">
                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label">End Date</label>
                    <input type="text" name="end_date" autocomplete="off" class="mw-ui-field" value="<?php echo $data['end_date'] ?>"/>
                </div>
            </div>
        </div>
        <div class="mw-ui-col">
            <div class="mw-ui-col-container">
                <div class="mw-ui-field-holder js-end-time-wrapper">
                    <label class="mw-ui-label">End Time</label>
                    <input type="text" name="end_time" autocomplete="off" class="mw-ui-field" value="<?php echo $data['end_time'] ?>"/>
                </div>
            </div>
        </div>
        <div class="mw-ui-col">
            <div class="mw-ui-col-container">
                <div class="mw-ui-field-holder">
                    <label class="mw-ui-check m-t-30"><input type="checkbox" name="all_day" class="js-all-day" value="1"/><span></span><span>All day</span></label>
                </div>
            </div>
        </div>
    </div>


    <div class="mw-ui-field-holder">
        <select name="recurrence_type" class="mw-ui-field js-select-recurrence mw-full-width">
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
        <div class="mw-ui-field-holder">
            <label class="mw-ui-label">Repeat every</label>


            <div class="mw-ui-row">
                <div class="mw-ui-col">
                    <div class="mw-ui-col-container">
                        <input type="number" name="recurrence_repeat_every" value="<?php echo $data['recurrence_repeat_every']; ?>" class="mw-ui-field">
                    </div>
                </div>
                <div class="mw-ui-col">
                    <div class="mw-ui-col-container">
                        <select name="recurrence_repeat_type" class="mw-ui-field js-recurrence-repeat-type">
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
            <label class="mw-ui-label">Repeat on</label>
            <table class="mw-ui-table">
                <tr>
                    <td>
                        <label class="mw-ui-check"><input type="checkbox" value="1" name="recurrence_repeat_on[monday]"/><span></span><span>Monday</span></label>
                    </td>
                    <td>
                        <label class="mw-ui-check"><input type="checkbox" value="1" name="recurrence_repeat_on[tuesday]"/><span></span><span>Tuesday</span></label>
                    </td>
                    <td>
                        <label class="mw-ui-check"><input type="checkbox" value="1" name="recurrence_repeat_on[wednesday]"/><span></span><span>Wednesday</span></label>
                    </td>
                    <td>
                        <label class="mw-ui-check"><input type="checkbox" value="1" name="recurrence_repeat_on[thursday]"/><span></span><span>Thursday</span></label>
                    </td>
                    <td>
                        <label class="mw-ui-check"><input type="checkbox" value="1" name="recurrence_repeat_on[friday]"/><span></span><span></span>Friday</label>
                    </td>
                    <td>
                        <label class="mw-ui-check"><input type="checkbox" value="1" name="recurrence_repeat_on[saturday]"/><span></span><span>Saturday</span></label>
                    </td>
                    <td>
                        <label class="mw-ui-check"><input type="checkbox" value="1" name="recurrence_repeat_on[sunday]"><span></span><span>Sunday</span></label>
                    </td>
                </tr>
            </table>
        </div>

        <div class="js-recurrence-monthly-on" style="display:none;">
            <select name="recurrence_monthly_on" class="mw-ui-field js-recurrence-monthly-on mw-full-width">
                <option value="the_day_number">Monthly on day</option>
                <option value="the_week_number_day_name">Monthly on the</option>
            </select>
        </div>
    </div>


    <div class="mw-ui-field-holder">
        <module type="calendar/group_select" calendar-event-id="<?php echo $data['id'] ?>"/>
    </div>

    <div class="mw-ui-field-holder">
        <label class="mw-ui-check">
            <input type="checkbox" name="active" class="js-active" value="1" <?php if ($data['active'] == "1"): ?>checked="checked"<?php endif; ?> />
            <span></span><span>Active</span>
        </label>
    </div>

    <hr>

    <div class="mw-ui-btn-nav pull-right">
        <span class="mw-ui-btn " onclick="thismodal.remove()">Cancel</span>
        <button class="mw-ui-btn mw-ui-btn-invert " type="submit">Save</button>
    </div>

    <div class="mw-ui-btn-nav pull-left">
        <?php if (!$add_new): ?>
            <a class="mw-ui-btn" href="javascript:deleteEvent('<?php echo $data['id'] ?>')">Delete</a>
        <?php endif; ?>
    </div>
</form>
