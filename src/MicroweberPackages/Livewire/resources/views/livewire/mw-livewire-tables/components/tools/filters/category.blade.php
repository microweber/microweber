<?php

$data = [];
$data['parent'] = 0;
$categories_active_ids = "0,1";

?>
<script type="text/javascript">
    categoryFilterSelectTree = function (){
        var ok = mw.element('<button class="btn btn-primary">Apply</button>');
        var btn = ok.get(0);

        var dialog = mw.dialog({
            footer: btn,
            onResult: function(result){
                console.log(result)
            }
        });

        var tree;

        mw.admin.tree(dialog.dialogContainer, {
            options: {
                sortable: false,
                selectable: true,
                multiPageSelect: true
            }
        }, 'tree').then(function (res){
            tree = res.tree;
            $(tree).on("selectionChange", function () {
                btn.disabled = tree.getSelected().length === 0;
            });
            $(tree).on("ready", function () {
                dialog.center();
            });
        });

        ok.on('click', function(){ dialog.result(tree.getSelected(), true) })
    }


    $(document).ready(function () {

    });
</script>


<button class="btn btn-outline-primary btn-block" onclick="categoryFilterSelectTree()"><i class="fa fa-list"></i> Select Categories</button>
