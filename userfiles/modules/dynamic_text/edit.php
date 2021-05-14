<?php only_admin_access(); ?>

<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<script>
    $(document).ready(function () {
        $("#save-dynamic-text-form").submit(function (event) {

            var data = $(this).serialize();
            var url = "<?php print api_url('save_dynamic_text'); ?>";
            var post = $.post(url, data);
            post.done(function (data) {
                mw.reload_module_everywhere('dynamic_text/select')
                mw.reload_module_everywhere('dynamic_text/list')
                mw.reload_module_everywhere('dynamic_text')
                $('.js-toggle-form').toggle();
                mw.notification.success("<?php _ejs("All changes are saved"); ?>.");

            });
            event.preventDefault();
        });
    });

    function edit_dynamic_text(id) {

        $('.js-dynamic-text-id').val(id);
        $('#save-dynamic-text-form').show();
        $('#save-dynamic-text-form-add-btn').hide();
        $.get("<?php print api_url('get_dynamic_text'); ?>", {single: 1, id: id})
            .done(function (data) {
                $('.js-dynamic-text-name').val(data.name);
                $('.js-dynamic-text-content').html(data.content);

            });

    }
</script>


<div class="card style-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <?php $module_info = module_info($params['module']); ?>
        <h5>
            <!--            <img src="--><?php //echo $module_info['icon']; ?><!--" class="module-icon-svg-fill"/>-->
            <!--            <strong>--><?php //_e($module_info['name']); ?><!--</strong>-->
        </h5>
    </div>

    <div class="card-body pt-3">
        <script>
            mw.lib.require('jqueryui');
            mw.require("<?php print $config['url_to_module'];?>css/main.css");
        </script>

        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
            <a class="btn btn-outline-secondary justify-content-center active" data-toggle="tab" href="#list"><i class="mdi mdi-format-list-bulleted-square mr-1"></i> <?php _e('List of Dynamic texts'); ?></a>
            <?php if ($from_live_edit) : ?>
                <a class="btn btn-outline-secondary justify-content-center" data-toggle="tab" href="#templates"><i class="mdi mdi-pencil-ruler mr-1"></i> <?php _e('Templates'); ?></a>
            <?php endif; ?>

        </nav>

        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="list">
                <label class="control-label"><?php _e("Dynamic text"); ?></label>
                <small class="text-muted d-block mb-3"><?php _e("Add new dynamic text then drop it in live edit."); ?></small>
                <form id="save-dynamic-text-form" class="form-group js-toggle-form" style="display: none">
                    <div class="col-4">

                        <label><?php _e("Name"); ?>:</label>
                        <small class="text-muted d-block mb-3"><?php _e("Example: 'my-cool-name'"); ?></small>


                        <input type="text" name="name" class="form-control js-dynamic-text-name" required="required">

                        <br>
                        <label><?php _e("Content"); ?>:</label>
                        <small class="text-muted d-block mb-3"><?php _e("Type your dynamic text content in the text area below"); ?></small>

                        <textarea name="content" class="form-control js-dynamic-text-content" required="required"></textarea>
                        <br/>
                        <br/>
                        <input type="hidden" value="0" name="id" class="js-dynamic-text-id"/>
                        <button type="submit" name="submit" class="btn btn-primary"><?php _e("Save dynamic text"); ?></button>
                    </div>
                </form>
                <div class="mb-3">
                    <a class="btn btn-primary btn-rounded js-toggle-form" id="save-dynamic-text-form-add-btn" onclick="$('.js-toggle-form').toggle();" href="#"><?php _e('Add new'); ?></a>
                </div>
                <br>
                <module type="dynamic_text/list" />

            </div>

            <?php if ($from_live_edit) : ?>
                <div class="tab-pane fade" id="templates">
                    <module type="admin/modules/templates"/>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
