<?php must_have_access(); ?>

<?php
if (isset($params['id'])) {
    $subscriber = newsletter_get_subscriber($params['id']);
}
?>

<style>
    .mw-ui-field-full-width {
        width:100%;
    }
    .js-danger-text {
        padding-top: 5px;
        color: #c21f1f;
    }
</style>
<script>
    mw.require("<?php print $config['url_to_module']; ?>/js/js-helper.js");

    $(document).ready(function () {

        $(document).on("change", ".js-validation", function () {
            $('.js-edit-subscriber-form :input').each(function () {
                if ($(this).hasClass('js-validation')) {
                    runFieldsValidation(this);
                }
            });
        });

        $(".js-edit-subscriber-form").submit(function (e) {

            e.preventDefault(e);

            var errors = {};
            var data = mw.serializeFields(this);

            $('.js-edit-subscriber-form :input').each(function (k, v) {
                console.log($(this));
                if ($(this).hasClass('js-validation')) {
                    if (runFieldsValidation(this) == false) {
                        errors[k] = true;
                    }
                }
            });

            if (isEmpty(errors)) {

                $.ajax({
                    url: mw.settings.api_url + 'newsletter_save_subscriber',
                    type: 'POST',
                    data: data,
                    success: function (result) {

                        mw.notification.success('<?php _ejs('Subscriber saved'); ?>');

                        // Remove modal
                        if (typeof (edit_subscriber_modal) != 'undefined' && edit_subscriber_modal.modal) {
                            edit_subscriber_modal.modal.remove();
                        }

                        // Reload the modules
                        mw.reload_module('newsletter/subscribers_list')
                        mw.reload_module_parent('newsletter');

                    },
                    error: function (e) {
                        alert('Error processing your request: ' + e.responseText);
                    }
                });
            } else {
                console.log(errors);
                mw.notification.error('<?php _ejs('Please fill correct data.'); ?>');
            }
        });

    });

    function runFieldsValidation(instance) {

        var ok = true;
        var inputValue = $(instance).val().trim();

        $(instance).removeAttr("style");
        $(instance).parent().find(".js-field-message").html('');

        if (inputValue == "") {
            $(instance).css("border", "1px solid #b93636");
            $(instance).parent().find('.js-field-message').html(errorText('<?php _e('The field cannot be empty'); ?>'));
            ok = false;
        }

        if ($(instance).hasClass('js-validation-email')) {
            if (validateEmail(inputValue) == false) {
                $(instance).css("border", "1px solid #b93636");
                $(instance).parent().find('.js-field-message').html(errorText('<?php _e('The email address is not valid.'); ?>'));
                ok = false;
            }
        }

        return ok;
    }
</script>


<form class="js-edit-subscriber-form">
    <div class="form-group">
        <h5 class="settings-title-inside mb-1"><?php _e('Subscriber Name'); ?></h5>
        <small class="text-muted d-block mb-1"><?php _e('Enter the full name of the subscriber'); ?></small>
        <input name="name" type="text" value="<?php if (isset($subscriber['name'])): ?><?php echo $subscriber['name']; ?><?php endif; ?>" class="form-control js-validation" />
        <div class="js-field-message"></div>
    </div>
    <div class="form-group">
        <h5 class="settings-title-inside mb-1"><?php _e('Subscriber Email'); ?></h5>
        <small class="text-muted d-block mb-1"><?php _e('Enter the email address of the subscriber'); ?></small>
        <input name="email" type="text"  value="<?php if (isset($subscriber['email'])): ?><?php echo $subscriber['email']; ?><?php endif; ?>" class="form-control js-validation js-validation-email" />
        <div class="js-field-message"></div>
    </div>

    <div class="form-group">
        <h5 class="settings-title-inside mb-1"><?php _e('Subscribed for'); ?></h5>

        <small class="text-muted d-block mb-1">Choose list to subscribe or <a href="javascript:;" onclick="edit_list();">create a new list</a></small>
    </div>
    <div class="form-group">
        <?php
        if (isset($subscriber['id'])) {
            $subscriber_lists = newsletter_get_subscriber_lists($subscriber['id']);
        }
        $list_params = array();
        $list_params['no_limit'] = true;
        $list_params['order_by'] = "created_at desc";
        $lists = newsletter_get_lists($list_params);
        if (array_search_multidimensional($lists, 'list_id', '0') === false) {
            $newLists[] = array('id' => '0', 'name' => 'Default');
            $lists = array_merge($newLists, $lists);
        }
        ?>
        <?php if ($lists): ?>
            <?php
            foreach ($lists as $list):
                if (!empty($subscriber_lists)) {
                    $inList = array_search_multidimensional($subscriber_lists, 'list_id', $list['id']);
                } else {
                    $inList = false;
                }
                ?>
                <div class="custom-control custom-checkbox d-flex align-items-center gap-2 mt-2">
                    <label class="d-flex gap-1">
                    <input <?php if ($inList !== false): ?>checked<?php endif; ?> class="form-check-input" id="inlist-<?php echo $list['id']; ?>" name="subscribed_for[]" type="checkbox" value="<?php echo $list['id']; ?>" />
                    <span class="form-check-label" for="inlist-<?php echo $list['id']; ?>"><?php echo $list['name']; ?></span>
                    </label>
                </div>

            <?php endforeach; ?>
        <?php else: ?>

            <div class="custom-control custom-checkbox d-flex align-items-center gap-2">
                <input type="checkbox" class="form-check-input" name="subscribed_for[]" id="inlist-default" value="0" checked>
                <span class="form-check-label"><?php _e("Default") ?> </span>
            </div>
        <?php endif; ?>
    </div>
    <div class="d-flex justify-content-between">
        <div>
            <?php if (isset($subscriber['id'])): ?>
                <a class="btn btn-outline-danger btn-sm" href="javascript:;" onclick="delete_subscriber('<?php print $subscriber['id']; ?>')">Delete</a>
                <input type="hidden" value="<?php echo $subscriber['id']; ?>" name="id" />
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-dark"><?php _e('Save'); ?></button>
    </div>
</form>
