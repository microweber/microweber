<?php only_admin_access() ?>
<?php
if (!isset($params['content_id'])) {
    return;
}


$content_id = intval($params['content_id']);

$content_type = false;

$content_data = get_content_by_id($content_id);
if (isset($content_data['content_type'])) {
    $content_type = $content_data['content_type'];

}

?>


<?php

$exclude_ids = [];
$exclude_ids[] = $content_id;


$related = [];
$content = (new \MicroweberPackages\Content\Models\Content())->where('id', $content_id)->first();

if ($content) {
    $related_cont = $content->related()->get();
    if ($related_cont) {
        $related = $related_cont->toArray();
        foreach ($related as $related_cont) {
            $exclude_ids[] = $related_cont['related_content_id'];
        }
    }
}
//dump($related);
?>



<?php $rand = md5(uniqid()) ?>


<label class="form-label"><?php _e("Search for content"); ?></label>
<small class="text-muted d-block"><?php _e('In the field below you can search for content from your website.');?></small>

<script>mw.require('autocomplete.js')</script>

<script>

    function mw_admin_add_related_content_to_content_id($content_id, $related_content_id) {


        var relate_to = {}
        relate_to.content_id = $content_id;
        relate_to.related_content_id = $related_content_id;


        $.ajax({
            method: 'post',
            url: mw.settings.api_url + 'content/related_content/add',
            data: relate_to
        }).done(function () {
            mw_admin_edit_related_content_after_edit()

        });


    }


    function mw_admin_remove_related_content_by_related_id($id) {

        if (confirm("Are you sure?")) {
            var relate_to = {}
            relate_to.id = $id;


            $.ajax({
                method: 'post',
                url: mw.settings.api_url + 'content/related_content/remove',
                data: relate_to
            }).done(function () {
                mw_admin_edit_related_content_after_edit()

            });


            // $.post(mw.settings.api_url + 'content/related_content/remove', relate_to, function (msg) {
            //
            //
            //     mw_admin_edit_related_content_after_edit()
            // });
        }


    }

    mw_admin_edit_related_content_after_edit = function () {
        mw.reload_module('content/views/related_content_list');
        mw.reload_module_everywhere('posts');
        mw.reload_module_everywhere('shop/products');
        mw.reload_module_everywhere('content');
        mw.reload_module_everywhere('pages');
        //  mw.reload_module('#mw-admin-select-related-content-list');

    }

    $(document).ready(function () {
        mw.$("#mw-admin-related-content-edit-sort<?php print $rand ?>").sortable({
            items: '.js-admin-related-content-sort-element',
            axis: 'y',
            handle: '.js-admin-related-content-sort-element-handle',
            update: function () {
                var obj = {ids: []}
                $(this).find('.js-admin-related-content-sort-element').each(function () {
                    var id = this.attributes['value'].nodeValue;
                    obj.ids.push(id);
                });


                $.ajax({
                    method: 'post',
                    url: mw.settings.api_url + 'content/related_content/reorder',
                    data: obj
                }).done(function () {
                    mw_admin_edit_related_content_after_edit()

                });


            },
            start: function (a, ui) {
                $(this).height($(this).outerHeight());
                $(ui.placeholder).height($(ui.item).outerHeight())
                $(ui.placeholder).width($(ui.item).outerWidth())
            },
            scroll: false
        });


        var search_for_related_content_field<?php print $rand ?> = new mw.autoComplete({
            element: "#mw-admin-search-for-related-content<?php print $rand ?>",
            placeholder: mw.lang("Search for related content"),
            ajaxConfig: {
                method: 'get',
                url: mw.settings.api_url + 'get_content_admin?get_extra_data=1&exclude_ids=<?php print implode(',', $exclude_ids) ?>&content_type=<?php print $content_type ?>&keyword=${val}'
            },
            map: {
                value: 'id',
                title: 'title',
                image: 'picture'
            }
        });

        $(search_for_related_content_field<?php print $rand ?>).on("change", function (e, val) {
            if (val[0].id != undefined) {
                mw_admin_add_related_content_to_content_id('<?php print $content_id ?>', val[0].id);
            }
        })
    });
</script>
    <div class="mw-ui-field-holder">
        <input type="hidden" name="mw_admin_edit_related_content_for_id" id="mw_admin_edit_related_content_for_id"
               value="<?php print $content_id ?>">
        <div id="mw-admin-search-for-related-content<?php print $rand ?>"></div>
    </div>
<div>
    <label class="form-label my-3"><?php _e("List of related content"); ?></label>
    <?php if (!$related) { ?>

        <div class="alert alert-dismissible alert-secondary">

            <?php _e("There is no selected related content."); ?>
            <br>
            <?php _e("Please use the search."); ?>
            <br>
            <br>
        </div>
    <?php } else { ?>
        <table class="table table-hover table-sm  " id="mw-admin-related-content-edit-sort<?php print $rand ?>">
            <style scoped>
                tr {
                    cursor: pointer;
                }

                tr i.mdi-cursor-move {
                    opacity: 0;
                }

                tr:hover i.mdi-cursor-move {
                    opacity: 1;
                }
            </style>
            <?php foreach ($related as $related_cont) { ?>
                <tr class="js-admin-related-content-sort-element" value="<?php print ($related_cont['id']) ?>">
                    <td class="align-middle" style="width: 20px">
                        <span class="cursor-move-holder me-2 js-admin-related-content-sort-element-handle" onmousedown="mw.manage_content_sort()" style="max-width: 80px;">
                          <span href="javascript:;" class="btn btn-link text-blue-lt">
                              <svg class="mdi-cursor-move" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M360 896q-33 0-56.5-23.5T280 816q0-33 23.5-56.5T360 736q33 0 56.5 23.5T440 816q0 33-23.5 56.5T360 896Zm240 0q-33 0-56.5-23.5T520 816q0-33 23.5-56.5T600 736q33 0 56.5 23.5T680 816q0 33-23.5 56.5T600 896ZM360 656q-33 0-56.5-23.5T280 576q0-33 23.5-56.5T360 496q33 0 56.5 23.5T440 576q0 33-23.5 56.5T360 656Zm240 0q-33 0-56.5-23.5T520 576q0-33 23.5-56.5T600 496q33 0 56.5 23.5T680 576q0 33-23.5 56.5T600 656ZM360 416q-33 0-56.5-23.5T280 336q0-33 23.5-56.5T360 256q33 0 56.5 23.5T440 336q0 33-23.5 56.5T360 416Zm240 0q-33 0-56.5-23.5T520 336q0-33 23.5-56.5T600 256q33 0 56.5 23.5T680 336q0 33-23.5 56.5T600 416Z"></path></svg>
                          </span>
                    </span>
                    </td>
                    <td class="align-middle">
                        <div class="d-flex align-items-center justify-content-center">
                            <img class="rounded-circle" src="<?php print thumbnail(get_picture($related_cont['related_content_id']), 60, 60, true) ?>">
                        </div>
                    </td>
                    <td class="align-middle"><?php print content_title($related_cont['related_content_id']) ?></td>
                    <td class="align-middle" style="width: 20px">

                        <a href="<?php print content_link($related_cont['related_content_id']) ?>" target="_blank"
                           class="btn btn-link"><i class="mdi mdi-link"></i></a>
                    </td>
                    <td class="align-middle" style="width: 20px">
                        <a href="javascript:;"
                           onclick="mw_admin_remove_related_content_by_related_id(<?php print ($related_cont['id']) ?>);"
                           class="btn btn-link"><i class="mdi text-danger mdi-delete"></i></a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    <?php } ?>
</div>


