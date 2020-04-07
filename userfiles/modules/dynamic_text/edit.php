<?php only_admin_access(); ?>
<script>
    $(document).ready(function () {
        $("#add-dynamic-text-form").submit(function (event) {
            event.preventDefault();
            var data = $(this).serialize();
            var url = "<?php print api_url('save_dynamic_text'); ?>";
            var post = $.post(url, data);
            post.done(function (data) {
                window.location.href = window.location.href;
            });
        });
    });

    function edit_dynamic_text(id) {
        $('.js-dynamic-text-id').val(id);


    }
</script>

<form id="add-dynamic-text-form">
    <h3>Add new dynamic text</h3>
    <br />
    <label class="mw-ui-label">Variable: (example: <b>my_cool_variable</b>)</label>
    <input type="text" name="variable" class="mw-ui-field js-dynamic-text-variable" required="required" style="width: 310px">
    <br />
    <br />
    <label class="mw-ui-label">Content</label>
    <textarea  name="content" class="mw-ui-field js-dynamic-text-content" required="required" style="width: 310px"></textarea>
    <br />
    <br />
    <inpu type="hidden" value="0" name="id" class="js-dynamic-text-id" />
    <input type="submit" name="submit" value="Add dynamic text" class="mw-ui-btn" style="width: 310px"/>
</form>
