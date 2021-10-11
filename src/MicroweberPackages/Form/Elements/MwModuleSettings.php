<?php

namespace MicroweberPackages\Form\Elements;

class MwModuleSettings extends TextArea
{
    public function render()
    {
        $mwModuleSettingsId = rand(1111, 9999) . time();

        $html = '
<script>mw.require(\'prop_editor.js\')</script>
<script>mw.require(\'module_settings.js\')</script>
<script>mw.require(\'icon_selector.js\')</script>
<script>mw.require(\'wysiwyg.css\')</script>

<script>
$(window).on(\'load\', function () {

    var data = [{"images":"","primaryText":"My Slider","secondaryText":"Your slogan here","seemoreText":"See more222","url":"","urlText":"","skin":"default","icon":""}];

    this.bxSettings = new mw.moduleSettings({
        element: \'#settings-box\',
        header: \'<i class="mw-icon-drag"></i> Content #{count} <a class="pull-right" data-action="remove"><i class="mw-icon-close"></i></a>\',
        data: data,
        key: \'settings\',
        group: \'id\',
        autoSave: true,
        schema: []
    });

    alert(2);

});
</script>

<!-- Settings Content -->
<div class="module-live-edit-settings module-'.$mwModuleSettingsId.'-settings">
    <input type="hidden" name="settings" id="settingsfield" value="" class="mw_option_field"/>
    <div class="mb-3">
        <span class="btn btn-primary btn-rounded" onclick="bxSettings.addNew(0, \'blank\');"> '. _e('Add new', true) . '</span>
    </div>
    <div id="settings-box"></div>
</div>
<!-- Settings Content - End -->';

        return $html;
    }
}
