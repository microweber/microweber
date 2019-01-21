<?php
$selected_category = get_option('fromcategory', $params['id']);
$selected_page = get_option('frompage', $params['id']);
$show_category_header = get_option('show_category_header', $params['id']);
$my_tree_id = ''
?>

<style type="text/css" scoped="scoped">
    #parentcat .depth-1 {
        padding-left: 10px;
    }

    #parentcat .depth-2 {
        padding-left: 20px;
    }

    #parentcat .depth-3 {
        padding-left: 30px;
    }

    #parentcat .depth-4 {
        padding-left: 40px;
    }
</style>





<script type="text/javascript">

    mw.require('tree.js')

</script>


<style>
    .module-categories-image-settings .level-1:not(.has-children):not(.type-category){
        display: none;
    }
</style>





<script type="text/javascript">
    var selectedData = [];

    <?php if($selected_category){ ?>
    selectedData.push({
        id: <?php print $selected_category; ?>,
        type: 'category'
    })

    <?php } ?>


    $( document ).ready(function() {

        $.get("<?php print api_url('content/get_admin_js_tree_json'); ?>", function(data){

            if(!Array.isArray(data)){
                var data = [];
            }


            data.unshift({
                id: 0,
                type: 'category',
                title: 'None',
                "parent_id":0,
                "parent_type":"category"
            });


            var categoryParentSelector =    new mw.tree({
                element:"#category-parent-selector",
                selectable: true,
                selectedData: selectedData,
                singleSelect: true,
                data: data
            })

            $(categoryParentSelector).on("selectionChange", function (e, selected) {
                var parent = selected[0];

                if(parent.type){
                    if(parent.type == 'page'){
                        $('#parentpage').val(parent.id).change();
                        $('#parentcat').val('').change();
                    }
                    if(parent.type == 'category'){
                        $('#parentcat').val(parent.id).change();
                        $('#parentpage').val('').change();
                    }

                }

            })
        });


    });




</script>


















<div class="mw-modules-tabs">
    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-gear"></i> <?php print _e('Settings'); ?>
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">

            <div class="module-live-edit-settings module-categories-image-settings">
                <input type="hidden" name="settings" id="settingsfield" value="" class="mw_option_field"/>


                <input type="hidden" name="fromcategory" id="parentcat" value="<?php print $selected_category; ?>" class="mw_option_field"/>
                <input type="hidden" name="frompage" id="parentpage" value="<?php print $selected_page; ?>" class="mw_option_field"/>

                <label class="mw-ui-label"><?php _e('Select parent category'); ?></label>


                <div id="category-parent-selector"></div>


            </div>
        </div>
    </div>
</div>