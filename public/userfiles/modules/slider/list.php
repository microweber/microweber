<?php
include('skins_variables.php');
?>
<script>mw.require('prop_editor.js')</script>
<script>mw.require('module_settings.js')</script>
<script>mw.require('icon_selector.js')</script>
<script>mw.require('wysiwyg.css')</script>

<script>
    $(document).ready(function () {
        $('select[name="data-template"]').on('change', function () {
            var thisVal = $(this).find(':selected').val();
            var first = thisVal.slice(0, thisVal.lastIndexOf('-skin'));
            $('select[name="engine"]').val(first).trigger('change');
        });
    });

    $(window).on('load', function () {

        var skins = [];
        var fodlerItems = <?php print json_encode($skins); ?>;

        fodlerItems.forEach(function (item) {
            if (item !== '.' && item !== '..') {
                skins.push(item.split('.')[0])
            }
        });

        var data = <?php print json_encode($json); ?>;
        $.each(data, function (key) {
            if (typeof data[key].images === 'string') {
                data[key].images = data[key].images.split(',');
            }
        });

        this.bxSettings = new mw.moduleSettings({
            element: '#settings-box',
            header: '<i class="mw-icon-drag"></i> <?php _e('Slide {count}'); ?> <a class="pull-right" data-action="remove"><i class="mdi mdi-delete"></i></a>',
            data: data,
            key: 'settings',
            group: '<?php print $params['id']; ?>',
            autoSave: true,
            schema: [
                {
                    interface: 'file',
                    id: 'images',
                    label: 'Add Image',
                    types: 'images',
                    multiple: 2
                },
                /*                                {
                                                    interface: 'color',
                                                    label: ['Overlay color'],
                                                    id: 'overlaycolor'
                                                },*/
                {
                    interface: 'select',
                    label: ['Skin'],
                    id: 'skin',
                    options: skins
                },
                {
                    interface: 'icon',
                    label: ['Icon'],
                    id: 'icon'
                },
                {
                    interface: 'text',
                    label: ['Slide Heading'],
                    id: 'primaryText'
                },
                {
                    interface: 'text',
                    label: ['Slide Description'],
                    id: 'secondaryText'
                },
                {
                    interface: 'text',
                    label: ['URL'],
                    id: 'url'
                },
                {
                    interface: 'text',
                    label: ['See more text'],
                    id: 'seemoreText'
                }
            ]
        });
        $(bxSettings).on("change", function (e, val) {
            var final = [];
            $.each(val, function () {
                var curr = $.extend({}, this);
                curr.images = curr.images.join(',');
                final.push(curr)
            });
            $("#settingsfield").val(JSON.stringify(final)).trigger("change")
        });
    });
</script>

<!-- Settings Content -->
<div class="module-live-edit-settings module-bxslider-settings">
    <input type="hidden" name="settings" id="settingsfield" value="" class="mw_option_field"/>

    <div class="mb-3">
        <span class="btn btn-primary btn-rounded" onclick="bxSettings.addNew(0, 'blank');"><?php _e('Add new'); ?></span>
    </div>

    <div id="settings-box"></div>
</div>
<!-- Settings Content - End -->
