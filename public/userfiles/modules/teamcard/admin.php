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
           header: '<span class="cursor-move-holder">   <span href="javascript:;" class="btn btn-link text-blue-lt tblr-body-color"> <svg class="mdi-cursor-move ui-sortable-handle" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M360 896q-33 0-56.5-23.5T280 816q0-33 23.5-56.5T360 736q33 0 56.5 23.5T440 816q0 33-23.5 56.5T360 896Zm240 0q-33 0-56.5-23.5T520 816q0-33 23.5-56.5T600 736q33 0 56.5 23.5T680 816q0 33-23.5 56.5T600 896ZM360 656q-33 0-56.5-23.5T280 576q0-33 23.5-56.5T360 496q33 0 56.5 23.5T440 576q0 33-23.5 56.5T360 656Zm240 0q-33 0-56.5-23.5T520 576q0-33 23.5-56.5T600 496q33 0 56.5 23.5T680 576q0 33-23.5 56.5T600 656ZM360 416q-33 0-56.5-23.5T280 336q0-33 23.5-56.5T360 256q33 0 56.5 23.5T440 336q0 33-23.5 56.5T360 416Zm240 0q-33 0-56.5-23.5T520 336q0-33 23.5-56.5T600 256q33 0 56.5 23.5T680 336q0 33-23.5 56.5T600 416Z"></path></svg> </span></span><span data-bind="name"></span> <a class="ms-auto" data-action="remove"><svg class="me-1 text-danger cursor-pointer" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 0 24 24" width="20px"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M16 9v10H8V9h8m-1.5-6h-5l-1 1H5v2h14V4h-3.5l-1-1zM18 7H6v12c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7z"></path></svg></a>',
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

<div class="card-body mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class=" ">

        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
            <a class="btn btn-link justify-content-center mw-admin-action-links mw-adm-liveedit-tabs  active" data-bs-toggle="tab" href="#listofmembers"> <?php _e('List of Members'); ?></a>
            <a class="btn btn-link justify-content-center mw-admin-action-links mw-adm-liveedit-tabs " data-bs-toggle="tab" href="#templates">   <?php _e('Templates'); ?></a>
        </nav>


        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="listofmembers">
                <div class="module-live-edit-settings module-teamcard-settings">
                    <input type="hidden" class="mw_option_field" name="settings" id="settingsfield"/>
                    <div class="add-new-button text-end my-3">
                            <span
                                class="btn btn-outline-dark btn-rounded icon-left"
                                id="add-team-member">
                                <svg fill="currentColor" class="me-2" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 96 960 960" width="20"><path d="M446.667 856V609.333H200v-66.666h246.667V296h66.666v246.667H760v66.666H513.333V856h-66.666Z"/></svg>
                                <?php _e('Add new'); ?></span>
                    </div>
                    <div id="teamcard-settings"></div>
                </div>

            </div>

            <div class="tab-pane fade" id="templates">
                <module type="admin/modules/templates"/>
            </div>
        </div>
    </div>
</div>
