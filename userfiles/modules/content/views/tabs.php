<?php if (!isset($data)) {
    $data = $params;
}

$custom_tabs = mw()->module_manager->ui('content.edit.tabs');
?>

<script>
    mw.lib.require('colorpicker');
</script>


<div id="settings-tabs">
    <div class="card style-1 mb-3 images">
        <div class="card-header no-border" id="post-media-card-header">
            <h6><strong><?php _e('Pictures'); ?></strong></h6>
            <div class="post-media-type-holder">
                <module id="edit-post-gallery-main-source-selector-holder" type="pictures/admin_upload_pic_source_selector" />

            </div>
        </div>
        <div class="card-body pt-3">
            <module
                id="edit-post-gallery-main"
                type="pictures/admin"
                class="pictures-admin-content-type-<?php print trim($data['content_type']) ?>"
                for="content"
                content_type="<?php print trim($data['content_type']) ?>"
                for-id="<?php print $data['id']; ?>"/>
        </div>
    </div>

    <?php event_trigger('mw_admin_edit_page_tab_2', $data); ?>


    <?php
    $showCustomFields = true;
    if ($data['content_type'] == 'product') {
        $showCustomFields = true;
        include_once __DIR__ . DS . 'product' . DS . 'tabs.php';
    }
    if ($data['content_type'] == 'product_variant') {
        $showCustomFields = true;
        include_once __DIR__ . DS . 'product_variant' . DS . 'tabs.php';
    }
    ?>

    <?php if ($showCustomFields): ?>
        <style>
            .fields > .card-body .module > .card {
                background: transparent;
                border: 0;
                box-shadow: unset;
            }

            .fields > .card-body .module > .card > .card-body {
                padding: 0 !important;
            }
            .fields > .card-body .module > .card > .card-header {
                display: none;
            }
        </style>
    <script>
         var variants = ([main, ...[a, ...b]]) => {
             if (!a) return main
             const combined = a.reduce((acc, x) => {
                 return acc.concat(main.map(h => {
                     return [h, x]
                    })
                 )
             }, []).map(node => {
                 var clone = [...node];
                 clone.forEach(nd => {
                     if(Array.isArray(nd)) {
                         nd.forEach(obj => {
                             clone.push(obj);
                         });
                     }
                 })
                 return clone.filter(item => !Array.isArray(item));
             })
             return variants([combined, ...b])
         }
        $(document).ready(function (){
            mw.on('customFieldsRefresh', function (e, data) {
                var fields = data.data.map(function (item){
                    return item.values.map(function (val){
                        return {
                            name: val,
                            customFieldId: item.id
                        }
                    })
                });

            })
        })
    </script>
        <div class="card style-1 mb-3 card-collapse fields js-custom-fields-card-tab">
            <div class="card-header no-border">

                <h6><strong><?php _e("Custom fields"); ?></strong></h6>
                <a href="javascript:;" class="btn btn-link btn-sm js-show-custom-fields" data-bs-toggle="collapse" data-bs-target="#custom-fields-settings"><span class="collapse-action-label"><?php _e('Show') ?></span>&nbsp; Custom fields</a>

            </div>

            <div class="card-body py-0">
                <div class="collapse" id="custom-fields-settings">
                    <module type="custom_fields/admin" <?php if (trim($data['content_type']) == 'product'): ?> default-fields="price" <?php endif; ?> content-id="<?php print $data['id'] ?>" suggest-from-related="true" list-preview="true" id="fields_for_post_<?php print $data['id']; ?>"/>
                    <?php event_trigger('mw_admin_edit_page_tab_3', $data); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php /*if (trim($data['content_type']) == 'product'):  */?><!--
        <div class="card style-1 mb-3">
            <div class="card-body pt-3">
                <?php /*event_trigger('mw_edit_product_admin', $data); */?>
            </div>
        </div>
    --><?php /*endif;  */?>

    <?php event_trigger('mw_admin_edit_page_tab_4', $data); ?>

    <?php if (!empty($custom_tabs)): ?>
        <?php foreach ($custom_tabs as $item): ?>
            <?php $title = (isset($item['title'])) ? ($item['title']) : false; ?>
            <?php $class = (isset($item['class'])) ? ($item['class']) : false; ?>
            <?php $html = (isset($item['html'])) ? ($item['html']) : false; ?>
            <div class="card style-1 mb-3 custom-tabs">
                <div class="card-body pt-3"><?php print $html; ?></div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <module type="content/views/advanced_settings" content-id="<?php print $data['id']; ?>" content-type="<?php print $data['content_type']; ?>" subtype="<?php print $data['subtype']; ?>"/>
    <?php event_trigger('content/views/advanced_settings', $data); ?>


    <div class="mt-9">
        <!--  -->
    </div>

</div>


<script>
    $(document).ready(function () {

        pick1 = mw.colorPicker({
            element: '.mw-ui-color-picker',
            position: 'bottom-left',
            onchange: function (color) {

            }
        });

        setTimeout(function (){
            mw.askusertostay = false;
          //  document.querySelector('.js-bottom-save').disabled = true;
        }, 999)

    });
</script>
