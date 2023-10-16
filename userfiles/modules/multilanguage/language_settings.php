<?php
/**
 * Dev: Bozhidar Slaveykov
 * Emai: bobi@microweber.com
 * Date: 11/18/2019
 * Time: 10:26 AM
 */
?>

<?php
$languages = \MicroweberPackages\Translation\LanguageHelper::getLanguagesWithDefaultLocale();
?>

<script type="text/javascript">
    $(document).ready(function () {
        mw.dropdown();

        $('#is_active_quick').on('change', function () {
            var data = {active: $(this).is(':checked')};
            $.post(mw.settings.api_url + "multilanguage/active_language", data).done(function (data) {

                if(data.active){
                    mw.notification.success('Multilanguage is activated.',10000);
                } else {
                    mw.notification.warning('Multilanguage is deactivated.',10000);
                }

                window.location = window.location;
            });
        });

        makeSortable();
        getInitialOrder('.js-tbody-supported-locales tr');

        //bind stuff to number inputs
        $('.js-tbody-supported-locales tr input[type="number"]').focus(function () {
            $(this).select();
        }).change(function () {
            updateAllNumbers($(this), '.js-tbody-supported-locales input');
            reorderItems('.js-tbody-supported-locales tr', '.js-tbody-supported-locales');
        }).keyup(function () {
            updateAllNumbers($(this), '.js-tbody-supported-locales input');
            reorderItems('.js-tbody-supported-locales tr', '.js-tbody-supported-locales');
        });
    });

    function makeSortable() {
        $('.js-tbody-supported-locales').sortable({
            distance: 40,
            update: function (item) {
                $(item).removeClass("dragged").removeAttr("style");
                $("body").removeClass("dragging");
                getInitialOrder('.js-tbody-supported-locales tr');
            }
        });
    }

    function submitNewOrderNumbers() {
        var languages = [];
        $('.js-supported-language-order-numbers').each(function () {
            languages.push({
                id: $(this).attr('name'),
                position: $(this).attr('data-initial-value')
            });
        });

        $.post(mw.settings.api_url + "multilanguage/sort_language", {ids: languages})
            .done(function (data) {
                mw.reload_module_everywhere('multilanguage/list')
                mw.reload_module_everywhere('multilanguage')
            });
    }

    function updateOrderNumber(id, direct) {
        var new_val = parseInt($('.js-supported-language-order-number-' + id).val());
        if (direct == 'up') {
            new_val = new_val + 1;
        } else {
            new_val = new_val - 1;
        }
        if (new_val < 1) {
            new_val = 1;
        }
        $('.js-supported-language-order-number-' + id).val(new_val);
        updateAllNumbers($('.js-supported-language-order-number-' + id), '.js-tbody-supported-locales input');
        reorderItems('.js-tbody-supported-locales tr', '.js-tbody-supported-locales');
    }

    function getInitialOrder(obj) {
        var num = 1;
        $(obj).each(function () {
            //set object initial order data based on order in DOM
            $(this).find('input[type="number"]').val(num).attr('data-initial-value', num);
            num++;
        });
        $(obj).find('input[type="number"]').attr('max', $(obj).length); //give it an html5 max attr based on num of objects
        $(obj).find('input[type="number"]').last().trigger('change');
    }

    function updateAllNumbers(currObj, targets) {
        var delta = currObj.val() - currObj.attr('data-initial-value'), //if positive, the object went down in order. If negative, it went up.
            c = parseInt(currObj.val(), 10), //value just entered by user
            cI = parseInt(currObj.attr('data-initial-value'), 10), //original object val before change
            top = $(targets).length;

        //if the user enters a number too high or low, cap it
        if (c > top) {
            currObj.val(top);
        } else if (c < 1) {
            currObj.val(1);
        }

        $(targets).not($(currObj)).each(function () { //change all the other objects
            var v = parseInt($(this).val(), 10); //value of object changed

            if (v >= c && v < cI && delta < 0) { //object going up in order pushes same-numbered and in-between objects down
                $(this).val(v + 1);
            } else if (v <= c && v > cI && delta > 0) { //object going down in order pushes same-numbered and in-between objects up
                $(this).val(v - 1);
            }
        }).promise().done(function () {
            //after all the fields update based on new val, set their data element so further changes can be tracked
            //(but ignore if no value given yet)
            $(targets).each(function () {
                if ($(this).val() !== "") {
                    $(this).attr('data-initial-value', $(this).val());
                }
            });
        });
    }

    function reorderItems(things, parent) {
        for (var i = 1; i <= $(things).length; i++) {
            $(things).each(function () {
                var x = parseInt($(this).find('input').val(), 10);
                if (x === i) {
                    $(this).appendTo(parent);
                }
            });
        }
        submitNewOrderNumbers();
    }

    function editSuportedLanguage(id) {
        mw.top().dialog({
            content: '<div id="mw_admin_preview_module_multilanguage_edit"></div>',
            title: '<?php _e('Edit language'); ?>',
            width: 600,
            id: 'mw_admin_preview_module_mutlilanguage_modal'
        });

        var params = {};
        params.locale_id = id;
        mw.top().load_module('multilanguage/edit', '#mw_admin_preview_module_multilanguage_edit', null, params);
    }

    function deleteSuportedLanguage(language_id) {
        mw.tools.confirm('<?php _e('Are you sure you want to delete?'); ?>', function () {
            $.post(mw.settings.api_url + "multilanguage/delete_language", {id: language_id}).done(function (data) {
                mw.reload_module_everywhere('multilanguage/language_settings');
                mw.reload_module_everywhere('settings/group/language_multilanguage');
            });
        });
    }
</script>

<script>mw.lib.require('flag_icons');</script>

<div class="mw-module-language-settings">
    <script type="text/javascript">
        $(document).ready(function () {
            mw.options.form('.module-switch-active-form', function () {
                mw.notification.success("All changes are saved.");
                window.location.href = window.location.href;
            });
        });
    </script>
    <div class="row d-flex justify-content-between px-0">

        <div class="col-md-6 col-12 d-flex align-items-center">
            <module type="multilanguage/admin_add_language" />
        </div>



        <div class="col-md-6 col-12 text-md-end">
            <div class="form-group module-switch-active-form">

                <label class="form-label"><?php _e('Multilanguage is active'); ?></label>

                <div class="form-check form-switch d-flex justify-content-md-end">
                    <label class="d-inline-block mr-5" for="is_active_quick"></label>
                    <input class="form-check-input" id="is_active_quick"
                           type="checkbox"
                           autocomplete="off"
                           name="is_active" <?php if (get_option('is_active', 'multilanguage_settings') == 'y'): ?>checked<?php endif; ?>
                           data-value-checked="y"
                           data-value-unchecked="n">
                    <label class="custom-control-label" for="is_active_quick"></label>
                </div>
            </div>
        </div>
    </div>

   <div>
       <module type="multilanguage/list"/>
   </div>

</div>
