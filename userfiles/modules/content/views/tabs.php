<?php if (!isset($data)) {
    $data = $params;
}

$custom_tabs = mw()->module_manager->ui('content.edit.tabs');
?>

<script>
    mw.lib.require('colorpicker');
</script>


<div id="settings-tabs">

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

<!--    custom fields-->

    <?php endif; ?>

    <?php /*if (trim($data['content_type']) == 'product'):  */?><!--
        <div class="card-body mb-3">
            <div class=" ">
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
            <div class="card-body mb-3 custom-tabs">
                <div class=" "><?php print $html; ?></div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

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
