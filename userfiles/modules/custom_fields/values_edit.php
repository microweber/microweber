<div id="mw-custom-fields-list-settings-<?php print $params['id']; ?>" class="mw-admin-custom-field-edit-item-wrapper"><?php /*settings are loaded here*/ ?></div>

<script>
    mw.require('custom_fields.js');

    $(document).ready(function () {
        var $selector = '#mw-custom-fields-list-settings-<?php print $params['id']; ?>';
        var data = {};
        data.settings = 'y';
        data.id = '<?php print $params['id']; ?>';

        mw.$($selector).load(mw.settings.api_html + 'fields/make', data, function (a) {
            mw.custom_fields.sort($selector);
            var time = null;
            mw.$("input,textarea,select", this).on("change", function () {
                var el = this;
                clearTimeout(time);
                time = setTimeout(function (){
                    mw.spinner({
                        element: $selector,
                        decorate: true
                    }).show()
                    mw.custom_fields.save_form($selector, function (){
                        mw.spinner({
                            element: $selector,
                            decorate: true
                        }).hide();
                        mw.notification.success('<?php _ejs("Changes are saved"); ?>')
                    });
                    if (mw.$($selector).find('.mw-needs-reload').length > 0) {
                        mw.reload_module('custom_fields/values_edit');
                    }
                }, 333)
            });

            mw.$($selector + " input").on('focus blur', function (e) {
                var func = e.type === 'focus' ? 'addClass' : 'removeClass';
                mw.tools[func](mw.tools.firstParentWithTag(e.target, 'tr'), 'active');
            });
        });
    });
</script>
