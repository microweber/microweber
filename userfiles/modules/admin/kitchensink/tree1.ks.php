<div class="mw__example" data-tags="tree,navigation">
<div class="mw-ui-box mw-ui-box-content" data-view  >
    <div class="tree-example"></div>
    <script>
        $(document).ready(function(){
            $.get("<?php print $apiurl; ?>content/get_admin_js_tree_json", function(tdata){
                new mw.tree({
                    element:'.tree-example',
                    data:tdata,
                    selectable:true,
                    singleSelect:true,
                    filter:{type:'page'}
                });
            });
        });
    </script>
</div>
</div>