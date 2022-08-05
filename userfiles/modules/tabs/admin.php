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
        <module type="admin/modules/info_module_title" for-module="<?php print $params['module'] ?>"/>
    </div>

    <div class="card-body pt-3">

        <script type="text/javascript">
            mw.require("<?php print mw_includes_url(); ?>css/wysiwyg.css");
            mw.require('icon_selector.js')
        </script>

        <?php
        $settings = get_module_option('settings', $params['id']);

        if ($settings == false) {
            if (isset($params['settings'])) {
                $settings = $params['settings'];
            }
        }

        $defaults = array(
            'title' => '',
            'id' => 'tab-' . time(),
            'icon' => ''
        );

        $json = json_decode($settings, true);

        if (isset($json) == false or count($json) == 0) {
            $json = array(0 => $defaults);
        }
        ?>

        <style>
            .mw-ui-box + .mw-ui-box {
                margin-top: 20px;
            }

            .mw-ui-box-header {
                cursor: -moz-grab;
                cursor: -webkit-grab;
                cursor: grab;
            }
        </style>

        <script>
            mw.require('prop_editor.js');
            mw.require('module_settings.js');
        </script>
        <script>
            $(window).on("load", function () {
                this.tabSettings = new mw.moduleSettings({
                    element: '#settings-box',
                    header: '<i class="mw-icon-drag"></i> <span data-bind="title"><?php _ejs("Move"); ?></span> <a class="pull-right" data-action="remove"><i class="mdi mdi-delete"></i></a>',
                    data: <?php print json_encode($json); ?>,
                    schema: [
                        {
                            interface: 'text',
                            label: ['Title'],
                            id: 'title'
                        },
                        {
                            interface: 'icon',
                            label: ['Icon'],
                            id: 'icon'
                        },
                        {
                            interface: 'hidden',
                            label: [''],
                            id: 'id',
                            value: function () {
                                return 'tab-' + mw.random();
                            }
                        }
                    ]
                });
                $(tabSettings).on("change", function (e, val) {
                    $("#settingsfield").val(tabSettings.toString()).trigger("change")
                });
            });
        </script>

        <input type="hidden" class="mw_option_field" name="settings" id="settingsfield"/>


        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
            <a class="btn btn-outline-secondary justify-content-center active" data-bs-toggle="tab" href="#settings"><i class="mdi mdi-cog-outline mr-1"></i> <?php _e('Settings'); ?></a>
            <a class="btn btn-outline-secondary justify-content-center" data-bs-toggle="tab" href="#templates"><i class="mdi mdi-pencil-ruler mr-1"></i> <?php _e('Templates'); ?></a>
        </nav>

        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="settings">
                <div class="mb-3">
                    <a class="btn btn-primary btn-rounded" href="javascript:tabSettings.addNew(0)"><i class="mdi mdi-plus"></i> <?php _e('Add new'); ?></a>
                </div>

                <div id="settings-box"></div>
            </div>

            <div class="tab-pane fade" id="templates">
                <module type="admin/modules/templates"/>
            </div>
        </div>
    </div>
</div>
