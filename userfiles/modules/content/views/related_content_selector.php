<?php only_admin_access() ?>

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

    mw_admin_add_related_content_to_content_id = function($content_id,$related_content_id){


        var relate_to = {}
        relate_to.content_id = $content_id;
        relate_to.related_content_id = $related_content_id;

        $.post(mw.settings.api_url + 'content/related_content/add', relate_to, function (msg) {

          mw.reload_module_everywhere('content/views/related_content_list');
          mw.reload_module_everywhere('#mw-admin-select-related-content-list');
        });

    }


    mw_admin_remove_related_content_by_related_id= function($id){

        if (confirm("Are you sure?")) {
            var relate_to = {}
            relate_to.id = $id;

            $.post(mw.settings.api_url + 'content/related_content/remove', relate_to, function (msg) {

                mw.reload_module_everywhere('content/views/related_content_list');
                mw.reload_module_everywhere('#mw-admin-select-related-content-list');

            });
        }


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

