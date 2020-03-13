<script>
function openModalHelpReadmeMd(for_module = false) {

    var modal_title = '<?php _e('How to use this module?'); ?>';

    mw_admin_edit_tag_modal = mw.modal({
        content: '<div id="mw_admin_help_item_module">Loading...</div>',
        title: modal_title,
        width:1000,
        height:700,
        id: 'mw_admin_help_item_popup_modal'
    });

    var params = {}
    params.for_module = for_module;

    mw.load_module('help/read', '#mw_admin_help_item_module', null, params);

}
</script>

<a href="#" onclick="openModalHelpReadmeMd('<?php echo $params['for_module']; ?>');"><i class="fa fa-info-circle"></i> <?php _e('Help'); ?></a>