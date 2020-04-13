<?php only_admin_access(); ?>
<script>
    $(document).ready(function () {
        $("#save-dynamic-text-form").submit(function (event) {

            var data = $(this).serialize();
            var url = "<?php print api_url('save_dynamic_text'); ?>";
            var post = $.post(url, data);
            post.done(function (data) {
                mw.reload_module('dynamic_text/list')
                mw.reload_module_everywhere('dynamic_text')
            });
            event.preventDefault();
        });
    });

    function edit_dynamic_text(id) {

        $('.js-dynamic-text-id').val(id);

        $.get("<?php print api_url('get_dynamic_text'); ?>", {single: 1, id: id})
            .done(function (data) {
                $('.js-dynamic-text-name').val(data.name);
                $('.js-dynamic-text-content').html(data.content);

            });

    }
</script>

<form id="save-dynamic-text-form">
    <h3>Add new dynamic text</h3>
    <br/>
    <label class="mw-ui-label">Name: (example: <b>my_cool_name</b>)</label>
    <input type="text" name="name" class="mw-ui-field js-dynamic-text-name" required="required" style="width: 310px">
    <br/>
    <br/>
    <label class="mw-ui-label">Content</label>
    <textarea name="content" class="mw-ui-field js-dynamic-text-content" required="required"
              style="width: 310px"></textarea>
    <br/>
    <br/>
    <input type="hidden" value="0" name="id" class="js-dynamic-text-id"/>
    <input type="submit" name="submit" value="Save dynamic text" class="mw-ui-btn" style="width: 310px"/>
</form>
