
<style>
    #content-results-table tr .mw_admin_posts_sortable_handle {
        opacity: .3;
    }
    #content-results-table tr:hover .mw_admin_posts_sortable_handle {
        opacity: 1;
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
                        mw.reload_module_everywhere('#mw_page_layout_preview');
                        mw.reload_module_everywhere('posts');
                        mw.reload_module_everywhere('content');
                        mw.reload_module_everywhere('shop/products');
                        mw.notification.success('<?php _ejs("All changes are saved"); ?>.');
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

