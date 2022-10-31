<?php must_have_access(); ?>
<script type="text/javascript">
    mw.require('options.js');
</script>
<style>
    .more-fonts-btbn-setting{
        text-align: center;
        padding: 20px 0;
    }
</style>
<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('#enabled_custom_fonts_settings_holder', function () {
            mw.clear_cache();
            if (typeof(window.mw.parent().wysiwyg) != 'undefined') {
                var selected = [];
                $('#<?php print $params['id'] ?> .enabled_custom_fonts_table input:checked').each(function () {
                    selected.push($(this).val());
                });
                var custom_fonts_stylesheet = window.parent.document.getElementById("mw-custom-user-css");
                if (custom_fonts_stylesheet != null) {
                    var custom_fonts_stylesheet_restyled = '<?php print api_nosession_url('template/print_custom_css') ?>?v=' + Math.random(0, 10000);
                    custom_fonts_stylesheet.href = custom_fonts_stylesheet_restyled;

                }
                setTimeout(function () {
                    window.mw.parent().wysiwyg.fontFamiliesExtended = [];
                    window.mw.parent().wysiwyg.initExtendedFontFamilies(selected);
                    window.mw.parent().wysiwyg.initFontSelectorBox();
                }, 100)
            }
            if (mw.notification !== undefined) {
                var el = mw.top().$('#mw-custom-user-css')[0];
                if(el){
                    var custom_fonts_stylesheet_restyled = '<?php print api_nosession_url('template/print_custom_css') ?>?v=' + Math.random(0, 10000);
                    el.href = custom_fonts_stylesheet_restyled;
                }
                mw.reload_module_everywhere('editor/fonts/select_option');
                mw.reload_module_everywhere('settings/template');

                var iframe_sidebar_style_editor = mw.top().$('#mw-css-editor-sidebar-iframe');
                if(iframe_sidebar_style_editor.length){
                    iframe_sidebar_style_editor.remove();
                    if(mw.top().liveEditWidgets){
                        mw.top().liveEditWidgets._cssEditorInSidebarAccordion = null
                    }
                }

                mw.notification.success('<?php _ejs('Fonts updated'); ?>');
            }
        });
        $('#<?php print $params['id'] ?> .enabled_custom_fonts_table input:checked').each(function () {
            mw_fonts_preview_load_stylesheet($(this).val());
        });
    });
    mw_fonts_preview_loaded_stylesheets = [];
    mw_fonts_preview_load_stylesheet = function (family) {
        if (mw_fonts_preview_loaded_stylesheets.indexOf(family) === -1) {
            mw_fonts_preview_loaded_stylesheets.push(family);

            var filename = "//fonts.googleapis.com/css?family=" + encodeURIComponent(family) + "&text=" + encodeURIComponent(family);
            var fileref = document.createElement("link")
            fileref.setAttribute("rel", "stylesheet")
            fileref.setAttribute("type", "text/css")
            fileref.setAttribute("href", filename)
            fileref.setAttribute("crossorigin", "anonymous")
            fileref.setAttribute("data-noprefix", "1")




            var fileref2 = document.createElement("link")
            fileref2.setAttribute("rel", "stylesheet")
            fileref2.setAttribute("type", "text/css")
            fileref2.setAttribute("href", filename);
            fileref2.setAttribute("crossorigin", "anonymous")
            fileref2.setAttribute("data-noprefix", "1")

            if(self !== top){

                mw.top().doc.getElementsByTagName("head")[0].appendChild(fileref)
                document.getElementsByTagName("head")[0].appendChild(fileref2)

            } else {
                mw.document.getElementsByTagName("head")[0].appendChild(fileref)

            }


        }
    }


    function load_more_fonts() {
        $('#<?php print $params['id'] ?>').attr('load-more',1)
        mw.reload_module('#<?php print $params['id'] ?>');

    }

</script>


<?php
$is_load_more = false;

if(isset($params['load-more'])){
    $is_load_more = $params['load-more'];
}

if ($is_load_more) {
    $fonts = json_decode(file_get_contents(__DIR__ . DS . 'fonts-more.json'), true);

} else {
    $fonts = json_decode(file_get_contents(__DIR__ . DS . 'fonts.json'), true);


}

?>
<?php if (isset($fonts['items'])): ?>
    <?php $enabled_custom_fonts = \MicroweberPackages\Utils\Misc\GoogleFonts::getEnabledFonts();


    $enabled_custom_fonts_array = array();

    if (is_string($enabled_custom_fonts)) {
        $enabled_custom_fonts_array = explode(',', $enabled_custom_fonts);
    }

    ?>
    <div class="async-css">
        <?php foreach ($fonts['items'] as $font): ?>
            <link family="<?php print ($font['family']); ?>"/>
        <?php endforeach; ?>
    </div>
    <script>
        function load_font_css_async(t) {
            $('link', "div.async-css").each(function () {
                var $family = $(this).attr("family");

                mw_fonts_preview_load_stylesheet($family);
                $(this).remove();


            });
            setTimeout(function () {
                load_font_css_async(t)
            }, t);
        }


        $(document).ready(function () {
            var load_font_init_temp = setTimeout(function () {
                load_font_css_async(3000);
            }, 1000);

            $("#search").on('input', function () {
                var val = this.value.toLowerCase().trim();
                if (!val) {
                    $("[data-fname]").show()
                    return;
                }

                $("[data-fname]").each(function () {
                    var name = this.dataset.fname;
                    if (name.indexOf(val) !== -1) {
                        $(this).show()
                    } else {
                        $(this).hide()
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            $('input[type="checkbox"]', '#<?php print $params['id'] ?>').change(function (event) {
                var checked_fonts_arr = [];
                $('input[type="checkbox"]:checked', '#<?php print $params['id'] ?>').each(function () {
                    checked_fonts_arr.push($(this).val());
                });

                var s = checked_fonts_arr;
                if (s.length > 0) {
                    s = s.join(',')
                } else {
                    s = '';
                }
                $('#enabled_custom_fonts_arr_impode').val(s).trigger('change');
                mw.reload_module_everywhere("settings/template")
                mw.reload_module_everywhere("editor/fonts/select_option")
                //mw.reload_module_parent("editor/fonts")
                //    window.mw.parent().wysiwyg.initExtendedFontFamilies(s)

            });
        });

    </script>
    <style>

        #search {
            position: fixed;
            right: 10px;
            top: 10px;
        }

    </style>
    <div id="enabled_custom_fonts_settings_holder">
        <input autocomplete="off" type="hidden" name="enabled_custom_fonts" class="mw_option_field"
               option-group="template" id="enabled_custom_fonts_arr_impode"
               value="<?php print $enabled_custom_fonts ?>"/>
    </div>
    <div class="module-live-edit-settings enabled_custom_fonts_table">
        <table width="100%" cellspacing="0" cellpadding="0" class="mw-ui-table">
            <thead>
            <tr>
                <th></th>
                <th>
                    <?php _e('Select Fonts'); ?>
                    <input class="mw-ui-searchfield" placeholder="<?php _e("Search.."); ?>" id="search">
                </th>
            </tr>
            </thead>
            <tbody>

            <?php $i = 0; ?>
            <?php foreach ($fonts['items'] as $font): ?>
                <?php if (isset($font['family']) and $font['family'] != ''): ?>
                    <tr
                            data-fname="<?php print strtolower($font['family']); ?>"
                            onMouseOver="mw_fonts_preview_load_stylesheet('<?php print $font['family']; ?>')"
                            onmouseenter="mw_fonts_preview_load_stylesheet('<?php print $font['family']; ?>')">
                        <td width="30">
                            <label class="mw-ui-check">
                                <input id="custom-font-select-<?php print $i; ?>" type="checkbox"
                                       name="REMOVE_____enabled_custom_fonts" <?php if (in_array($font['family'], $enabled_custom_fonts_array)): ?> checked <?php endif; ?>
                                       class="REMOVE___mw_option_field" option-group="REMOVE___template"
                                       value="<?php print $font['family']; ?>"/>
                                <span></span>
                            </label>
                        </td>
                        <td onmouseenter="mw_fonts_preview_load_stylesheet('<?php print $font['family']; ?>')"
                            onMouseOver="mw_fonts_preview_load_stylesheet('<?php print $font['family']; ?>')"><label
                                    for="custom-font-select-<?php print $i; ?>"
                                    style="font-size:14px;  font-family:'<?php print $font['family']; ?>',sans-serif;"><?php print $font['family']; ?></label>
                        </td>
                    </tr>
                    <?php $i++; ?>
                <?php endif; ?>
            <?php endforeach; ?>


            </tbody>
        </table>
        <?php if (!$is_load_more): ?>

        <div class="more-fonts-btbn-setting">
            <a onclick="load_more_fonts();" class="mw-ui-btn mw-ui-btn-primary m-t-20" href="#">Load more</a>
        </div>
        <?php endif; ?>

    </div>
<?php endif; ?>
