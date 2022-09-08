<?php


$selected_page_id = "6";
$categories_active_ids = "281,282,283";

?>
<script type="text/javascript">
    categoryFilterSelectTree = function (){


        var selectedPages = [ <?php print $selected_page_id; ?>];
        var selectedCategories = [ <?php print $categories_active_ids; ?>];




        var ok = mw.element('<button class="btn btn-primary">Apply</button>');
        var btn = ok.get(0);

        var dialog = mw.dialog({
            title: '<?php _ejs('Select categories'); ?>',
            footer: btn,
            onResult: function(result){
                console.log(result)
            }
        });

        var tree;

        mw.admin.tree(dialog.dialogContainer, {
            options: {
                sortable: false,
                singleSelect:false,
                selectable: true,
                multiPageSelect: false
            }
        }, 'treeTags').then(function (res){
            tree = res.tree;
            $(tree).on("selectionChange", function () {
                btn.disabled = tree.getSelected().length === 0;
            });
            $(tree).on("ready", function () {

                if (selectedPages.length) {
                    $.each(selectedPages, function () {
                        tree.select(this, 'page')
                    });
                }
                if (selectedCategories.length) {
                    $.each(selectedCategories, function () {
                        tree.select(this, 'category')
                    });
                }

                dialog.center();
            });
        });

        ok.on('click', function(){ dialog.result(tree.getSelected(), true) })
    }


    $(document).ready(function () {

    });
</script>


<button class="btn btn-outline-primary btn-block" onclick="categoryFilterSelectTree()"><i class="fa fa-list"></i> Select Categories</button>


