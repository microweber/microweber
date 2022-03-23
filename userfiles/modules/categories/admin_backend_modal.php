<?php must_have_access(); ?>

<script>
    mw.require('content.js')


 </script>
<script>

    function mw_clear_edit_module_attrs() {
        var container = mw.$('#<?php print $params['id']; ?>');
        container
            .removeAttr('content_type')
            .removeAttr('subtype')
            .removeAttr('data-parent-category-id')
            .removeAttr('data-category-id')
            .removeAttr('data-category-id')
            .removeAttr('data-category-id')
            .removeAttr('content-id')
            .removeAttr('data-page-id')
            .removeAttr('content_type_filter')
            .removeAttr('subtype_filter');
    }
    mw.content.deleteCategory = function (id, callback) {
        mw.tools.confirm('Are you sure you want to delete this?', function () {
            $.ajax({
                url: mw.settings.api_url + 'category/delete/' + id,
                method: 'DELETE',
                contentType: 'application/json',
                success: function(result) {

                    mw.notification.success('Category deleted');

                    if (callback) {
                        callback.call(data, data);
                    }

                    mw.reload_module_everywhere('content/manager');
                    mw.reload_module_everywhere('categories/manage');
                    mw.reload_module_everywhere('categories/admin_backend');
                    mw.reload_module_everywhere('categories/admin_backend_modal');

                    mw.url.windowDeleteHashParam('action');
                }
            });
        });
    }

    mw.quick_cat_edit_create = mw.quick_cat_edit_create || function (id) {

        mw.url.windowHashParam('action', 'editcategory:' + id)

    }


    function mw_select_category_for_editing_from_modal($p_id) {

         mw.$(".pages_tree_item.active-bg").removeClass('active-bg');
        mw.$(".category_element.active-bg").removeClass('active-bg');


        mw.$('#<?php print $params['id']; ?>').removeAttr('parent_id');
        mw.$('#<?php print $params['id']; ?>').removeAttr('data-parent-category-id');

        mw.$('#<?php print $params['id']; ?>').attr('data-category-id', $p_id);



        mw.$(".mw_edit_page_right").css("overflow", "hidden");
        cat_edit_load_from_modal('categories/edit_category');
    }


    function mw_select_add_sub_category($p_id) {


        mw.$('#<?php print $params['id']; ?>').removeAttr('parent_id');
        mw.$('#<?php print $params['id']; ?>').attr('data-category-id', 0);
        mw.$('#<?php print $params['id']; ?>').attr('data-parent-category-id', $p_id);
        mw.$(".mw_edit_page_right").css("overflow", "hidden");
        cat_edit_load_from_modal('categories/edit_category');
    }

    cat_edit_load_from_modal = function (module, callback) {


        var action = mw.url.windowHashParam('action');
        var holder = $('#<?php print $params['id'] ?>');

        var time = !action ? 300 : 0;
        if (!action) {
            mw.$('.fade-window').removeClass('active');
        }
        setTimeout(function () {
            mw.load_module(module, holder, function () {

                mw.$('.fade-window').addClass('active')
                if (callback) callback.call();

            });
        }, time)


    }



    $(document).ready(function () {
        mw.quick_cat_edit = mw_select_category_for_editing_from_modal;
        mw.quick_cat_delete =   function (id, callback) {
            mw.tools.confirm('Are you sure you want to delete this?', function () {
                $.ajax({
                    url: mw.settings.api_url + "category/delete/" + id,
                    method: 'DELETE',
                    contentType: 'application/json',
                    success: function(result) {

                        mw.notification.success('Category deleted');

                        if (callback) {
                            callback.call(data, data);
                        }

                        mw.reload_module_everywhere('content/manager');
                        mw.reload_module_everywhere('categories/manage');
                        mw.reload_module_everywhere('categories/admin_backend');
                        mw.reload_module_everywhere('categories/admin_backend_modal');

                        mw.url.windowDeleteHashParam('action');

                    }
                });
            });
        };


        mw.on.hashParam("action", function (pval) {
            //mw_clear_edit_module_attrs()

            if (pval === false) {

                cat_edit_load_from_modal('categories/admin_backend_modal');
                return false;
            } else {


                var arr = pval.split(":");

                if (arr[0] === 'managecats' ) {

                    cat_edit_load_from_modal('categories/admin_backend_modal');

                }
                if ((arr[0] === 'new' && arr[1] === 'category') || arr[0] === 'editcategory') {
                    mw_select_category_for_editing_from_modal(arr[1])
                }if (arr[0] === 'addsubcategory') {
                    mw_select_add_sub_category(arr[1]);
                }
            }


        });





    });
    </script>


<?php if(isset($params['show_add_post_to_category_button'])){ ?>
<module type="categories/manage" id="mw-cats-manage-admin" show_add_post_to_category_button="true" />
<?php } else {  ?>
    <module type="categories/manage" id="mw-cats-manage-admin" />


<?php } ?>
