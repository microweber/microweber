<?php $seach_prefix = crc32($params['id']); ?>
<?php $posts_parent_page = get_option('data-content-id', $params['id']);
if ($posts_parent_page != false) {
    $params['data-parent'] = $posts_parent_page;
}
$hide_paging = '';
if (isset($params['hide_paging'])) {
    $hide_paging = $params['hide_paging'];
}

if (isset($params['data-search-type'])) {
    $searchType = $params['data-search-type'];
} else {
    $searchType = '';
}
?>
    <script type="text/javascript">
        mw.require('events.js', true);
    </script>
    <script type="text/javascript">
        mw.require("url.js", true);
        //mw.require("tools.js", true);
        mw.require("forms.js", true);
    </script>
    <script type="text/javascript">
        mw.search_settings = {
            content_type: 'all',
            limit: 5,
            ajax_paging: true,
            template: 'default',
            done: false,
            hide_paging: true
        }
        mw.search = function (key, holder, obj) {
            if (typeof key === 'undefined' || typeof holder === 'undefined') return false;
            var opt = $.extend(mw.search_settings, obj, {});
            var holder = $(holder);
            holder
                .attr('keyword', key)
                .attr('content_type', opt.content_type)
                .attr('hide_paging', opt.hide_paging)
                .attr('limit', opt.limit)
                .attr('ajax_paging', opt.ajax_paging)
                .attr('search_global', true)

                <?php if (isset($params['data-parent'])) { ?>
                .attr('data-parent', '<?php print $params['data-parent'] ?>')
                .attr('data-search-parent', '<?php print $params['data-parent'] ?>')
                <?php } ?>
                .attr('template', opt.template)
                .show();

            mw.load_module('posts', holder, function () {
                if (typeof opt.done === 'function') {
                    opt.done.call(this);
                }
            });
        }
    </script>
<?php
$module_template = get_option('template', $params['id']);
if ($module_template == false and isset($params['template'])) {
    $module_template = $params['template'];
}

if ($module_template != false) {
    $template_file = module_templates($config['module'], $module_template);
} else {
    $template_file = module_templates($config['module'], 'default');
}
if (isset($template_file) and is_file($template_file) != false) {
    include($template_file);
}

