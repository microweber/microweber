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
        <?php
        $settings = get_module_option('settings', 'faq');
        $defaults = array(
            'question' => '',
            'answer' => '',
        );
        $json = json_decode($settings, true);
        if (isset($json) == false or count($json) == 0) {
            $json = array(0 => $defaults);
        }
        $pp = json_encode($json);
        ?>
        <script>mw.lib.require('mwui_init');</script>
        <script>mw.require('prop_editor.js')</script>
        <script>mw.require('module_settings.js')</script>
        <script>
          $(window).on('load', function (){
              window.faqSettings = new mw.moduleSettings({
                  element: '#settings-box',
                  header: '<i class="mdi mdi-drag"></i> Question {count} <a class="pull-right" data-action="remove"><i class="mdi mdi-delete"></i></a>',
                  data: <?php print $pp ?>,
                  key: 'settings',
                  group: '<?php print $params['id']; ?>',
                  autoSave: true,
                  schema: [
                      {
                          interface: 'text',
                          label: ['<?php _e('Question'); ?>'],
                          id: 'question'
                      },

                      {
                          interface: 'richtext',
                          label: ['<?php _e('Answer'); ?>'],
                          id: 'answer',
                          options: null
                      }
                  ]
              });
              setTimeout(function (){
                  $(faqSettings).on("change", function (e, val) {
                      var final = [];
                      $.each(val, function () {
                          var curr = $.extend({}, this);
                          final.push(curr)
                      });
                      var toVal = JSON.stringify(final);
                      mw.$('#settingsfield').val(toVal).trigger('change');
                  });
              }, 300)
          })
        </script>
        <style>
            .faq-setting-item{
                cursor: pointer;
            }
            .faq-setting-item .mdi-cursor-move,
            .faq-setting-item .remove-question {
                visibility: hidden;
            }

            .faq-setting-item:hover .mdi-cursor-move,
            .faq-setting-item:hover .remove-question {
                visibility: visible;
            }
        </style>
        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
            <a class="btn btn-outline-secondary justify-content-center active" data-bs-toggle="tab" href="#list"><i class="mdi mdi-format-list-bulleted-square mr-1"></i><?php _e('List of Questions'); ?> </a>
            <a class="btn btn-outline-secondary justify-content-center" data-bs-toggle="tab" href="#templates"><i class="mdi mdi-pencil-ruler mr-1"></i> <?php _e('Templates'); ?></a>
        </nav>
        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="list">
                <div class="module-live-edit-settings module-faq-settings">
                    <input type="hidden" class="mw_option_field" name="settings" option-group="faq" id="settingsfield"/>

                    <div class="mb-3">
                        <span class="btn btn-primary btn-rounded btn-sm" onclick="faqSettings.addNew()"><?php _e('Add new'); ?></span>
                    </div>
                    <div id="faq-settings">
                        <div id="settings-box"></div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="templates">
                <module type="admin/modules/templates"/>
            </div>
        </div>
    </div>
</div>
