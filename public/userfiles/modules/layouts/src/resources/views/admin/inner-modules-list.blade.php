@php
    $for_id = $this->optionGroup;
@endphp

<script>
    $(document).ready(function () {
        setTimeout(function () {
            if (typeof mw_admin_layouts_list_inner_modules_btns === 'function') {
                mw_admin_layouts_list_inner_modules_btns();
            }
        }, 999);
    });

    function mw_admin_layouts_list_inner_modules_btns() {
        var mod_in_mods_html_btn = '';
        var _win = mw.top().app.canvas.getWindow() || window;
        var mods_in_mod = _win.$('#{{ $for_id }}').find('.module', '#{{ $for_id }}');

        if (mods_in_mod) {
            $(mods_in_mod).each(function () {
                var isInaccessible = mw.top().app.liveEdit.liveEditHelpers.targetIsInacesibleModule(this);
                if (!isInaccessible) {
                    var inner_mod_type = $(this).attr("type") || $(this).attr("data-type");
                    var inner_mod_id = $(this).attr("id");
                    var inner_mod_title = $(this).attr("data-mw-title") || inner_mod_type;

                    if (inner_mod_type) {
                        var inner_mod_type_admin = inner_mod_type + '/admin';
                        mod_in_mods_html_btn += '<a href="javascript:;" class="btn btn-outline-dark btn-sm" onclick=\'window.mw.parent().tools.open_global_module_settings_modal("' + inner_mod_type_admin + '","' + inner_mod_id + '")\'>' + inner_mod_title + '</a>';
                    }
                }
            });
        }

        if (mod_in_mods_html_btn) {
            $('.current-template-modules-list-wrap').show();
            $('.current-template-modules-list-label').show();
            $('.current-template-modules-list').html(mod_in_mods_html_btn);
        } else {
            $('.current-template-modules-list-wrap').hide();
            $('.current-template-modules-list-label').hide();
        }
    }
</script>

<div class="current-template-modules-list-wrap">
    <label class="current-template-modules-list-label live-edit-label" style="display: none" >This layout contains those modules</label>
    <div class="current-template-modules-list d-flex flex-wrap gap-2 ms-2"></div>
</div>
