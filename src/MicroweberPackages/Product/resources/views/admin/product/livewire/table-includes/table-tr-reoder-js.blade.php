
<style>
    #content-results-table tr .mw_admin_posts_sortable_handle {
        visibility: hidden;
    }
    #content-results-table tr:hover .mw_admin_posts_sortable_handle {
        visibility: visible;
    }
</style>


<script>
    mw.manage_content_sort = function () {
        if (!mw.$("#content-results-table").hasClass("ui-sortable")) {
            mw.$("#content-results-table").sortable({
                items: '.manage-post-item',
                axis: 'y',
                handle: '.mw_admin_posts_sortable_handle',
                update: function () {
                    var obj = {ids: []}
                    $(this).find('.js-select-posts-for-action').each(function () {
                        var id = this.attributes['value'].nodeValue;
                        obj.ids.push(id);
                    });
                    $.post("<?php print api_link('content/reorder'); ?>", obj, function () {
                        mw.reload_module('#mw_page_layout_preview');
                        mw.reload_module_parent('posts');
                        mw.reload_module_parent('content');
                        mw.reload_module_parent('shop/products');
                        mw.notification.success('<?php _e("All changes are saved"); ?>.');
                    });
                },
                start: function (a, ui) {
                    $(this).height($(this).outerHeight());
                    $(ui.placeholder).height($(ui.item).outerHeight())
                    $(ui.placeholder).width($(ui.item).outerWidth())
                },
                scroll: false
            });
        }
    }
</script>

