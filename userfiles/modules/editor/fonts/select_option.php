<?php must_have_access(); ?>
<script>
    mw.require('options.js');
</script>



<?php

$name = 'font_family';
$group = 'website';
$show_more_link = false;

if(isset($params['group'])){
    $group = $params['group'];
}

if(isset($params['show_more_link'])){
    $show_more_link = $params['show_more_link'];
}
if(isset($params['name'])){
    $name = $params['name'];
}

$enabled_custom_fonts = \MicroweberPackages\Utils\Misc\GoogleFonts::getEnabledFontsAsString();
$selected = get_option($group, $name);

$enabled_custom_fonts_array = array(
    'Arial','Tahoma','Verdana','Georgia','Times New Roman'
);

if (is_string($enabled_custom_fonts) and $enabled_custom_fonts) {
    $enabled_custom_fonts__selected = explode(',', $enabled_custom_fonts);
    if($enabled_custom_fonts__selected){
        $enabled_custom_fonts_array = array_merge($enabled_custom_fonts_array,$enabled_custom_fonts__selected);
    }
}

?>

<script>
    $(document).ready(function () {
        mw.options.form('#<?php print $params['id'] ?>', function () {
            var el = mw.top().$('#mw-custom-user-css')[0];
            if(el){
                el.href = '<?php print api_nosession_url('template/print_custom_css') ?>?v=' + Math.random(0, 10000);
            }
        });
        mw.$("[name=<?php print $name ?>] option").each(function () {
            var val = $(this).text().trim();
            if (val) {
                mw.require('//fonts.googleapis.com/css?family=' + val + '&filetype=.css', true);
                $(this).css({"font-family": val, "font-size": "100%"});
            }
        });
    });

</script>


<select class="mw_option_field mw-ui-field mw-ui-field-small" option-group="<?php print $group ?>" data-width="100%" data-size="5" data-live-search="true" name="<?php print $name ?>">
    <option value=""><?php _e('Choose font'); ?></option>
    <?php foreach ($enabled_custom_fonts_array as $font): ?>
        <option value="<?php print $font; ?>"><?php print $font; ?></option>
    <?php endforeach; ?>
</select>

<?php if($show_more_link){ ?>

<a
    href="javascript:;"
    class="mw-ui-btn mw-ui-btn-small"
    onclick="mw.top().tools.open_global_module_settings_modal('editor/fonts/admin','<?php print $params['id'] ?>');">
    <small><?php _e('More fonts'); ?></small>
</a>
<?php } ?>
