<script>
    function openModalHelpReadmeMd(for_module = false) {
        var modal_title = '<?php _e('How to use this module?'); ?>';

        mw_admin_help_modal = mw.top().dialog({
            content: '<div id="mw_admin_help_modal">Loading...</div>',
            title: modal_title,
            width: 1000,
            height: 700,
            id: 'mw_admin_help_item_popup_modal'
        });

        var params = {}
        params.for_module = for_module;

        mw.top().load_module('help/read', '#mw_admin_help_modal', null, params);
    }
</script>

<a href="javascript:;" onclick="openModalHelpReadmeMd('<?php echo $params['for_module']; ?>');"><i class="fa fa-info-circle"></i> <?php _e('Help'); ?></a>
