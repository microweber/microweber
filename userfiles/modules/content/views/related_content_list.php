<?php only_admin_access() ?>
<?php
if (!isset($params['content-id'])) {
    return;
}
?>
<?php
if(!isset($params['content-id'])){
    return;
}

$content_id = intval($params['content-id']);

$content_type=false;

$content_data = get_content_by_id($content_id);
if(isset($content_data['content_type'])){
    $content_type=$content_data['content_type'];

}

?>




<h3><?php _e("Search for content"); ?></h3>


<script>mw.require('autocomplete.js')</script>

<script>

     function mw_admin_add_related_content_to_content_id($content_id,$related_content_id){


        var relate_to = {}
        relate_to.content_id = $content_id;
        relate_to.related_content_id = $related_content_id;

        $.post(mw.settings.api_url + 'content/related_content/add', relate_to, function (msg) {
            mw_admin_remove_related_content_after_edit()
        });

    }


    function mw_admin_remove_related_content_by_related_id($id){

        if (confirm("Are you sure?")) {
            var relate_to = {}
            relate_to.id = $id;

            $.post(mw.settings.api_url + 'content/related_content/remove', relate_to, function (msg) {


                mw_admin_remove_related_content_after_edit()
            });
        }


    }

     function mw_admin_remove_related_content_after_edit(){

         setTimeout(function(){
           //  mw.reload_module_everywhere('content/views/related_content_list');
             mw.reload_module_everywhere('#mw-admin-select-related-content-list');
             }, 500);




     }

    $(document).ready(function () {


        var search_for_related_content_field = new mw.autoComplete({
            element: "#mw-admin-search-for-related-content",
            placeholder: "Search for related content",
            ajaxConfig: {
                method: 'get',
                url: mw.settings.api_url + 'get_content_admin?get_extra_data=1&exclude_ids=<?php print $content_id ?>&content_type=<?php print $content_type ?>&keyword=${val}'
            },
            map: {
                value: 'id',
                title: 'title',
                image: 'picture'
            }
        });
        $(search_for_related_content_field).on("change", function (e, val) {
            if (val[0].id != undefined) {
                mw_admin_add_related_content_to_content_id($('#mw_admin_edit_related_content_for_id').val(),val[0].id);
            }
        })
    });
</script>

<div class="mw-ui-field-holder">
    <label class="mw-ui-label">In the field below you can search for content from your website.</label>

    <input type="hidden" name="mw_admin_edit_related_content_for_id" id="mw_admin_edit_related_content_for_id" value="<?php print $content_id ?>">
    <div id="mw-admin-search-for-related-content" class="mw_admin_custom_order_item_add_form_toggle"></div>


</div>


<h5><?php _e("List of related content"); ?></h5>

<?php
$related = [];
$content = (new \MicroweberPackages\Content\Content())->where('id', $params['content-id'])->first();

if ($content) {
    $related_cont = $content->related()->get();
    if ($related_cont) {
        $related = $related_cont->toArray();
    }
}
//dump($related);
?>


<?php if (!$related) { ?>

    <div class="alert alert-dismissible alert-secondary">

        <?php _e("There is no selected related content."); ?>
        <br>
        <?php _e("Please use the search."); ?>
    </div>



<?php } else { ?>


    <table class="table table-hover table-sm  ">
        <?php foreach ($related as $related_cont) { ?>


            <tr>

                <td style="width: 20px">
                    <a href="<?php print content_link($related_cont['related_content_id']) ?>" target="_blank"
                       class="btn"><i class="mdi mdi-reorder-horizontal"></i></a>
                </td>


                <td>
                    <div class="img-circle-holder w-60">
                        <img src="<?php print thumbnail(get_picture($related_cont['related_content_id']), 60, 60, true) ?>">
                    </div>
                </td>
                <td><?php print content_title($related_cont['related_content_id']) ?></td>
                <td style="width: 20px">

                    <a href="<?php print content_link($related_cont['related_content_id']) ?>" target="_blank"
                       class="btn btn-link"><i class="mdi mdi-link"></i></a>

                </td>
                <td style="width: 20px">
                    <a href="javascript:;"  onclick="mw_admin_remove_related_content_by_related_id(<?php print ($related_cont['id']) ?>);"
                       class="btn btn-link"><i class="mdi mdi-delete"></i></a>
                </td>
            </tr>


        <?php } ?>
    </table>


<?php } ?>
<?php print uniqid() ?>

