<?php must_have_access(); ?>

<script>

    Livewire.on('newsletter.templateSelected', function () {
        // Reload the modules
        mw.reload_module('newsletter/templates_list')
        mw.reload_module_parent('newsletter')
    });


    //function edit_template(id = false) {
    //
    //    var data = {};
    //    data.id = id;
    //
    //    mw.notification.success('<?php //_ejs('Loading...'); ?>//');
    //
    //    if (data.id > 0) {
    //        $.ajax({
    //            url: mw.settings.api_url + 'newsletter_get_template',
    //            type: 'POST',
    //            data: data,
    //            success: function (result) {
    //
    //                $('.js-edit-template-id').val(result.id);
    //                $('.js-edit-template-title').val(result.title);
    //                $('.js-edit-template-text').val(result.text);
    //
    //                initEditor(result.text);
    //            }
    //        });
    //    } else {
    //        $('.js-edit-template-id').val('0');
    //        $('.js-edit-template-title').val('');
    //        $('.js-edit-template-text').val('');
    //
    //        initEditor('');
    //    }
    //
    //    $('.js-templates-list-wrapper').slideUp();
    //    $('.js-edit-template-wrapper').slideDown();
    //}

    function delete_template(id) {
        var ask = confirm("<?php _ejs('Are you sure you want to delete this template?'); ?>");
        if (ask == true) {
            var data = {};
            data.id = id;
            $.ajax({
                url: mw.settings.api_url + 'newsletter_delete_template',
                type: 'POST',
                data: data,
                success: function (result) {
                    mw.notification.success('<?php _ejs('Template deleted'); ?>');

                    $(".js-edit-template-form")[0].reset();

                    // Reload the modules
                    mw.reload_module('newsletter/templates_list')
                    mw.reload_module_parent('newsletter')

                }
            });
        }
        return false;
    }
</script>



<div class="js-templates-list-wrapper">
    <module type="newsletter/templates_list" />
</div>

