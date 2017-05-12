<?php only_admin_access() ?>
<?php

$settings = get_option('settings', 'faq');

$defaults = array(
    'question' => '',
    'answer' => '',
);

$json = json_decode($settings, true);

if (isset($json) == false or count($json) == 0) {
    $json = array(0 => $defaults);
}

?>
<div class="module-live-edit-settings">
    <style scoped="scoped">
        #faq-settings {
            clear: both;
        }

        #faq-settings > div {
            margin-top: 15px;
            clear: both;
        }

        .add-new {
            float: right;
            margin-bottom: 20px;
            width: 100px;
        }

        .mw-ui-box-header {
            cursor: -moz-grab;
            cursor: -webkit-grab;
            cursor: grab;
        }

    </style>
    <input type="hidden" class="mw_option_field" name="settings" option-group="faq" id="settingsfield"/>
    <a class="mw-ui-btn mw-ui-btn-invert pull-right add-new" href="javascript:faqs.create()">+ <?php _e('Add new'); ?></a>
    <module type="admin/modules/templates" simple="true"/>
    <div id="faq-settings">
        <?php
        $count = 0;
        foreach ($json as $slide) {
            $count++;


            ?>
            <div class="mw-ui-box  faq-setting-item" id="faq-setting-item-<?php print $count; ?>">
                <div class="mw-ui-box-header"><a class="pull-right" href="javascript:faqs.remove('#faq-setting-item-<?php print $count; ?>');">x</a></div>
                <div class="mw-ui-box-content mw-accordion-content">
                    <div class="mw-ui-field-holder">
                        <label class="mw-ui-label"><?php _e('Question'); ?></label>
                        <input type="text" class="mw-ui-field faq-name w100 " value="<?php print $slide['question']; ?>">
                    </div>

                    <div class="mw-ui-field-holder">
                        <label class="mw-ui-label"><?php _e('Answer'); ?></label>
                        <textarea class="mw-ui-field faq-role w100"><?php print $slide['answer']; ?></textarea>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<script>

    faqs = {
        init: function (item) {
            $(item.querySelectorAll('.mw-ui-field')).bind('keyup', function () {
                mw.on.stopWriting(this, function () {
                    faqs.save();
                });
            });
        },

        collect: function () {
            var data = {}, all = mwd.querySelectorAll('.faq-setting-item'), l = all.length, i = 0;
            for (; i < l; i++) {
                var item = all[i];
                data[i] = {};
                data[i]['question'] = item.querySelector('.faq-name').value;
                data[i]['answer'] = item.querySelector('.faq-role').value;

            }
            return data;
        },
        save: function () {
            mw.$('#settingsfield').val(JSON.stringify(faqs.collect())).trigger('change');
        },


        create: function () {
            var last = $('.faq-setting-item:last');
            var html = last.html();
            var item = mwd.createElement('div');
            item.className = last.attr("class");
            item.innerHTML = html;
            $(item.querySelectorAll('input')).val('');
            $(item.querySelectorAll('.mw-uploader')).remove();
            last.after(item);
            faqs.init(item);
        },

        remove: function (element) {
            var txt;
            var r = confirm("<?php _e('Are you sure?'); ?>");
            if (r == true) {
                $(element).remove();
                faqs.save();
            }
        },


    }


    $(document).ready(function () {
        var all = mwd.querySelectorAll('.faq-setting-item'), l = all.length, i = 0;
        for (; i < l; i++) {
            if (!!all[i].prepared) continue;
            var item = all[i];
            item.prepared = true;
            faqs.init(item);
        }
    });


    $(document).ready(function () {

        $('#faq-settings').sortable({
            handle: '.mw-ui-box-header',
            items: ".faq-setting-item",

            update: function (event, ui) {
                faqs.save();
            }
        });
    });

</script>