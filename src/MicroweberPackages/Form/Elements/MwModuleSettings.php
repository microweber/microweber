<?php

namespace MicroweberPackages\Form\Elements;

class MwModuleSettings extends TextArea
{
    public function render()
    {
        $mwModuleSettingsId = rand(1111, 9999) . time();

        $fieldsSchema = [];

        $fieldsSchema[] = [
            'interface'=>'text',
            'label'=>['ebasigo'],
            'id'=>'manqk',
        ];

        $fieldsData = [];
        $fieldsData[]['manqk'] = 'ti ibesh lisa';
        $fieldsData[]['manqk'] = 'ti ibesh lisa2';
        $fieldsData[]['manqk'] = 'ti ibesh lisa3';

        $data = json_encode($fieldsData);
        $schema = json_encode($fieldsSchema);

        $html = '
<script>mw.require(\'prop_editor.js\')</script>
<script>mw.require(\'module_settings.js\')</script>
<script>mw.require(\'icon_selector.js\')</script>
<script>mw.require(\'wysiwyg.css\')</script>

<script>
$(window).on(\'load\', function () {

    var data = '.$data.';
    $.each(data, function (key) {
        if (typeof data[key].images === \'string\') {
            data[key].images = data[key].images.split(\',\');
        }
    });

    this.bxSettings_'.$mwModuleSettingsId.' = new mw.moduleSettings({
        element: \'#settings-box\',
        header: \'<i class="mw-icon-drag"></i> Content #{count} <a class="pull-right" data-action="remove"><i class="mw-icon-close"></i></a>\',
        data: data,
        key: \'settings\',
        group: \'id\',
        autoSave: true,
        schema: '.$schema.'
    });

    $(bxSettings_'.$mwModuleSettingsId.').on(\'change\', function (e, val) {
        var final = [];
        $.each(val, function () {
            var curr = $.extend({}, this);
            curr.images = curr.images.join(\',\');
            final.push(curr)
        });
        $(\'#settingsfield'.$mwModuleSettingsId.'\').val(JSON.stringify(final)).trigger(\'change\')
    });
});
</script>

<!-- Settings Content -->
<div class="module-live-edit-settings module-'.$mwModuleSettingsId.'-settings">
    <input type="hidden" name="settings" id="settingsfield'.$mwModuleSettingsId.'" value="" class="mw_option_field" />
    <div class="mb-3">
        <span class="btn btn-primary btn-rounded" onclick="bxSettings_'.$mwModuleSettingsId.'.addNew(0, \'blank\');"> '. _e('Add new', true) . '</span>
    </div>
    <div id="settings-box"></div>
</div>
<!-- Settings Content - End -->';

        return $html;
    }
}
