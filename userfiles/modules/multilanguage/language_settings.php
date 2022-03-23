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
        add_language_key = false;
        add_language_value = false;

        $('#is_active_quick').on('change', function () {
            $.post(mw.settings.api_url + "multilanguage/active_language", {active: $(this).is(':checked')}).done(function (data) {
                window.location = window.location;
                mw.notification.success('Multilanguage is activated.',10000);
            });
        });

        $('.js-add-language').on('click', function () {

            $('.js-add-language').html('<?php _e('Importing the language..'); ?>');

            if (typeof(mw.notification) != 'undefined') {
                mw.notification.success('Adding language...',10000);
            }

            if (add_language_key == false || add_language_value == false) {
                mw.notification.error('<?php _ejs('Please, select language.'); ?>');
                return;
            }

            $.post(mw.settings.api_url + "multilanguage/add_language", {locale: add_language_key, language: add_language_value}).done(function (data) {

                $('.js-add-language').html('<?php _e('Add'); ?>');

                if (typeof(mw.notification) != 'undefined') {
                    mw.notification.success('Language added...',10000);
                }

                mw.reload_module_everywhere('multilanguage');
                mw.reload_module_everywhere('multilanguage/language_settings', function () {
                });
            });
        });

        $('#add_language_ul').on('change', function () {
            var selectedOption = $(this).find('option:selected');
            var key = selectedOption.data('key');
            var value = selectedOption.data('value');
            add_language_key = key;
            add_language_value = value;
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

    <div class="row d-flex justify-content-between">
        <div class="col-8">
            <div class="form-group">
                <label class="control-label d-block"><?php _e('Add new language'); ?></label>

                <?php if ($languages) : ?>
                    <select autocomplete="off" class="js-dropdown-text-language selectpicker" id="add_language_ul" data-size="5" data-live-search="true">
                        <option>
                            <?php _e('Select language'); ?>
                        </option>

                        <?php foreach ($languages as $languageName => $languageDetails): ?>
                            <option value="<?php echo $languageDetails['locale'] ?>" data-key="<?php echo $languageDetails['locale'] ?>" data-value="<?php echo $languageName ?>" style="color:#000;">
                                <span class="flag-icon flag-icon-fr m-r-10"></span> <?php echo $languageName; ?>
                            </option>


                            <?php if(isset($languageDetails['locales']) and !empty($languageDetails['locales']) and count($languageDetails['locales']) > 1 ): ?>

                                <?php

                                if(is_array($languageDetails['locales'])){
                                    foreach ($languageDetails['locales'] as $languageName2 => $locale2){
                                        ?>
                                        <option value="<?php echo $languageName2 ?>" data-key="<?php echo $languageName2 ?>" data-value="<?php echo $locale2 ?>"  style="color:#000;">
                                            <span class="flag-icon flag-icon-fr m-r-10"></span> <?php echo $locale2; ?>  [<?php echo $languageName2; ?>]
                                        </option>
                                        <?php
                                    }
                                }


                                ?>
                            <?php endif; ?>



                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>

                <button class="btn btn-primary js-add-language"><?php _e('Add'); ?></button>
            </div>
        </div>

        <div class="col-4 text-right">
            <div class="form-group module-switch-active-form">
                <label class="control-label"><?php _e('Multilanguage is active'); ?>?</label>
                <div class="custom-control custom-switch pl-0">
                    <label class="d-inline-block mr-5" for="is_active_quick"><?php _e('No'); ?></label>
                    <input class="custom-control-input" id="is_active_quick"
                           type="checkbox"
                           autocomplete="off"
                           name="is_active" <?php if (get_option('is_active', 'multilanguage_settings') == 'y'): ?>checked<?php endif; ?>
                           data-value-checked="y"
                           data-value-unchecked="n">
                    <label class="custom-control-label" for="is_active_quick"><?php _e('Yes'); ?></label>
                </div>
            </div>
        </div>
    </div>

    <module type="multilanguage/list"/>
</div>
