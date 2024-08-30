<?php


$field_name = "content_id";
$selected = 0;


if (isset($params['field-name'])) {
    $field_name = $params['field-name'];
}

if (isset($params['selected-id'])) {
    $selected = intval($params['selected-id']);
}

$rand = uniqid();
$no_parent_title = _e("None", 1);
if (isset($params['no-parent-title'])) {
    $no_parent_title = $params['no-parent-title'];
}

$top_item = false;
if (isset($params['recommended-id']) and $params['recommended-id'] != false) {
    $recommended_parent = $params['recommended-id'];
    $top_item = get_content_by_id(intval($recommended_parent));
}

?>


<?php $treeId = 'tree-item-selector-' . $rand; ?>

<div id="<?php print $treeId; ?>"></div>

<script>

    ;(function (){
        function tree() {

            var selected = [];
            mw.admin.tree('#<?php print $treeId; ?>', {
                options: {
                     disableSelectTypes: ['category'],
                    <?php if (isset($params['remove_id']) and $params['remove_id'] != 0): ?>
                    skip: [
                        {
                            'id':<?php print intval($params['remove_id'] )?>,
                            'type':'page'
                        }
                    ],
                    <?php endif; ?>
                },
                params: {
		    skip_categories: 1,
                    content_type: 'page',

                },

            }).then(function (res){
                res.tree.select(<?php print $selected; ?>, 'page');
                res.tree.on('selectionChange', function (res){

                    for (var i = 0; i < res.length; i++) {
                        if (res[i].type === 'page') {
                            window.selectedPages = [res[i].id];
                        }
                    }

                    // console.log(window.selectedPages);

                    <?php if (isset($params['change-field'])): ?>
                    var val = res[0] ? res[0].id : '0';
                    mw.$('#<?php print $params['change-field'] ?>').val(val).trigger("change");
                    mw.$('[name="<?php print $params['change-field'] ?>"]').val(val).trigger("change");
                    <?php endif; ?>
                });
                $('.mw-page-component-disabled').removeClass('mw-page-component-disabled');
            });
        }
        if (document.readyState !== 'loading') {
            tree();
        } else {
            document.addEventListener('DOMContentLoaded', function () {
                tree();
            });
        }


    })();
</script>
