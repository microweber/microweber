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


<style>
    #teamcard-settings .mw-module-settings-box .mw-ui-box-header{
        border: none;
        border-radius: 0;
        cursor: pointer;
    }
    #teamcard-settings .mw-module-settings-box{
        box-shadow: none !important;
        border: none;
        border-top: 1px solid #ccc;
        border-radius: 0;
        margin: 0 -20px;
    }
</style>

<script>
    mw.require('prop_editor.js');
    mw.require('module_settings.js');


<?php

    $settings = get_module_option('settings', $params['id']);

    $defaults = array(
        'name' => '',
        'role' => '',
        'bio' => '',
        'file' => '',
        'id' => $params['id'] . uniqid(),
    );

    $json = json_decode($settings, true);

    if (isset($json) == false or count($json) == 0) {
        $json = array(0 => $defaults);
    }

    print 'var data = '. json_encode($json);

?>


   addEventListener('load', function (){
       var settings = new mw.moduleSettings({
           element: '#teamcard-settings',
           header: '<i class="mw-icon-drag"></i><span data-bind="name"></span> <a class="pull-right" data-action="remove"><i class="mw-icon-close"></i></a>',
           data: data,
           key: 'settings',
           group: '<?php print $params['id']; ?>',
           autoSave: true,
           schema: [
               {
                   interface: 'file',
                   id: 'file',
                   label: 'Member image',
                   types: 'images',
                   multiple: false
               },
               {
                   interface: 'text',
                   label: ['Name'],
                   id: 'name'
               },
               {
                   interface: 'text',
                   label: ['Position'],
                   id: 'role'
               },
               {
                   interface: 'textArea',
                   label: ['Biography'],
                   id: 'bio'
               }
           ]
       });
       mw.element('#add-team-member').on('click', function () {
           settings.addNew(0, 'blank')
       })
       $(settings).on("change", function (e, val) {
           var final = [];
           $.each(val, function () {
               var curr = $.extend({}, this);
               if(Array.isArray(curr.file)) {
                   curr.file = curr.file[0];
               }
               final.push(curr)
           });
           $("#settingsfield").val(JSON.stringify(final)).trigger("change")
       });
   })

</script>

<div class="card style-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <module type="admin/modules/info_module_title" for-module="<?php print $params['module'] ?>"/>
    </div>
    <div class="card-body pt-3">

        <div class="mw-modules-tabs">
            <div class="mw-accordion-item">
                <div class="mw-ui-box-header mw-accordion-title">
                    <div class="header-holder">
                        <i class="mw-icon-navicon-round"></i> List of Members
                    </div>
                </div>
                <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
                    <div class="module-live-edit-settings module-teamcard-settings">
                        <input type="hidden" class="mw_option_field" name="settings" id="settingsfield"/>
                        <div class="mw-ui-field-holder add-new-button text-end text-right">
                            <span
                                class="btn btn-success btn-rounded icon-left"
                                id="add-team-member"><i class="fas fa-plus-circle"></i> &nbsp;<?php _e('Add new'); ?></span>
                        </div>
                        <div id="teamcard-settings"></div>
                    </div>
                </div>
            </div>

            <div class="mw-accordion-item">
                <div class="mw-ui-box-header mw-accordion-title">
                    <div class="header-holder">
                        <i class="mw-icon-beaker"></i> <?php _e('Templates'); ?>
                    </div>
                </div>
                <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
                    <module type="admin/modules/templates"/>
                </div>
            </div>
        </div>
    </div>
</div>
