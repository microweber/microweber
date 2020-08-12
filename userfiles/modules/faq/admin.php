<?php must_have_access() ?>

<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card style-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <?php $module_info = module_info($params['module']); ?>
        <h5>
            <img src="<?php echo $module_info['icon']; ?>" class="module-icon-svg-fill"/> <strong><?php echo $module_info['name']; ?></strong>
        </h5>
    </div>

    <div class="card-body pt-3">

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
        <script>mw.lib.require('mwui_init');</script>
        <script>mw.lib.require('material_icons');</script>
        <script>
            faqs = {
                init: function (item) {

                    $(item.querySelectorAll('.faq-setting-item textarea')).each(function () {
                        mw.editor({
                            element: this,
                            height: 320,
                            width: '100%',
                            addControls: false,
                            hideControls: false,
                            ready: function () {

                            }
                        })
                    })

                    $(item.querySelectorAll('input[type="text"], textarea')).bind('change', function () {
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
                    var last = $('.faq-setting-item:first');
                    var html = last.html();
                    var item = mwd.createElement('div');
                    item.className = last.attr("class");
                    item.innerHTML = html;
                    $(item.querySelectorAll('input')).val('');
                    $(item.querySelectorAll('textarea')).val('');
                    $(item.querySelectorAll('.mw-uploader')).remove();
                    $(item.querySelectorAll('.mw-iframe-editor')).remove();
                    last.before(item);
                    faqs.init(item);
                },

                remove: function (element) {
                    var txt;
                    var r = confirm("<?php _ejs('Are you sure?'); ?>");
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
                    handle: '.card-header',
                    items: ".faq-setting-item",

                    update: function (event, ui) {
                        faqs.save();
                    }
                });
            });

        </script>

        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
            <a class="btn btn-outline-secondary justify-content-center active" data-toggle="tab" href="#list"><i class="mdi mdi-format-list-bulleted-square mr-1"></i> List of Questions</a>
            <a class="btn btn-outline-secondary justify-content-center" data-toggle="tab" href="#templates"><i class="mdi mdi-pencil-ruler mr-1"></i> <?php print _e('Templates'); ?></a>
        </nav>

        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="list">
                <!-- Settings Content -->
                <div class="module-live-edit-settings module-faq-settings">
                    <input type="hidden" class="mw_option_field" name="settings" option-group="faq" id="settingsfield"/>

                    <div class="mb-3">
                        <a class="btn btn-primary btn-rounded" href="javascript:faqs.create()"><?php _e('Add new'); ?></a>
                    </div>

                    <div id="faq-settings">
                        <?php $count = 0; ?>
                        <?php if ($json and is_array($json)): ?>
                            <?php foreach ($json as $slide): ?>
                                <?php $count++; ?>
                                <div class="card style-1 mb-3 faq-setting-item" id="faq-setting-item-<?php print $count; ?>">
                                    <div class="card-header d-flex align-items center justify-content-between flex-row">
                                        <span class="mdi mdi-cursor-move mdi-20px ui-sortable-handle text-muted"></span>
                                        <a class="remove-question text-danger" data-toggle="tooltip" href="javascript:faqs.remove('#faq-setting-item-<?php print $count; ?>');" title="Remove"><i class="mdi mdi-close mdi-20px"></i></a>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label class="control-label"><?php _e('Question'); ?></label>
                                            <input type="text" class="form-control faq-name " value="<?php print $slide['question']; ?>">
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label"><?php _e('Answer'); ?></label>
                                            <textarea class="form-control faq-role" id="textarea<?php print $count; ?>"><?php print $slide['answer']; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        <?php endif ?>
                    </div>
                </div>
                <!-- Settings Content - End -->
            </div>

            <div class="tab-pane fade" id="templates">
                <module type="admin/modules/templates"/>
            </div>
        </div>
    </div>
</div>