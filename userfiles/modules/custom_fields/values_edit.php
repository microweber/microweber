<div id="mw-custom-fields-list-settings-<?php print $params['id']; ?>" class="mw-admin-custom-field-edit-item-wrapper"><?php /*settings are loaded here*/ ?></div>



<script>
    mw.require('custom_fields.js');
$(document).ready(function () {

    var $selector = '#mw-custom-fields-list-settings-<?php print $params['id']; ?>';

    var data = {};
    data.settings = 'y';
    data.field_id = '<?php print $params['field_id']; ?>';

    mw.$($selector).load(mw.settings.api_html + 'fields/make', data, function (a) {

        mw.custom_fields.sort($selector);

        mw.$("input,textarea,select,checkbox,date,radio",$selector).bind("change keyup paste", function () {
			var el = $(this)[0]
			mw.on.stopWriting(el, function () {
				mw.custom_fields.save_form($selector);

				if (mw.$($selector).find('.mw-needs-reload').length > 0) {
					mw.reload_module('custom_fields/values_edit');
				}
				
			});
        });

        mw.$($selector + " input").bind('focus blur', function (e) {
            var func = e.type === 'focus' ? 'addClass' : 'removeClass';
            mw.tools[func](mw.tools.firstParentWithTag(e.target, 'tr'), 'active');
        });
    });
});
</script>

