<?php must_have_access(); ?>



<script>
    $(document).ready(function () {
        $.get("<?php print api_url('content/get_admin_js_tree_json'); ?>", function (tdata) {

            var selectedPages = [ ];
            var selectedCategories = [ ];

            window.exportContentSelector = new mw.tree({
                data: tdata,
                selectable: true,
                multiPageSelect: true,
                element: '#quick-parent-selector-tree',
                saveState:false
            });



            $(exportContentSelector).on('ready', function () {

            });


            $(exportContentSelector).on('selectionChange', function(event, selectedData){





            });





        });
    });
</script>


<script>
    mw.export_start = function () {

        var selected_content = exportContentSelector.options.selectedData;
        var selected_export_items = exportContentSelector.options.selectedData;



        var selected_export_items = [];

        /* look for all checkboes that have a class 'chk' attached to it and check if it was checked */
        $("[name='export_items']:checked").each(function() {
            selected_export_items.push($(this).val());
        });

        var selected;
        selected = selected_export_items.join(',') ;

        if(selected.length == 0 && selected_content.length == 0){
            mw.alert("Please check at least one of the checkboxes");
            return;
        }


        var export_manifest = {};
         export_manifest.content_ids = [];
         export_manifest.categories_ids = [];
         export_manifest.items = selected;



        selected_content.forEach(function(item, i){
            if(item.type == 'category' ){
                export_manifest.categories_ids.push(item.id);
            } else {
                export_manifest.content_ids.push(item.id);
            }
        });



      //  mw.log(export_manifest);

         mw.admin_import.export(export_manifest);
    }

</script>

<div>
    <h2>Select content to export</h2>
    <a class="mw-ui-btn" onclick="exportContentSelector.select(exportContentSelector.options.data)">all</a>
    <a class="mw-ui-btn" onclick="exportContentSelector.unselectAll()">none</a>

    <div id="quick-parent-selector-tree"></div>



    <h3>Select items to export</h3>
    <label class="mw-ui-check">
        <input  class="js-export-items" type="checkbox" value="comments" name="export_items" checked=""><span></span><span>Export comments</span>
    </label>
    <label class="mw-ui-check">
    <input class="js-export-items" type="checkbox" value="orders" name="export_items" checked=""><span></span><span>Export orders</span>
    </label>
    <label class="mw-ui-check">
    <input class="js-export-items" type="checkbox" value="users" name="export_items" checked=""><span></span><span>Export users</span>
    </label>



    <a href="javascript:;" class="mw-ui-btn " onclick="mw.export_start()">Start export</a>

</div>

