<?php
$langs = array(); //mw()->lang_helper->get_all_lang_codes();
$langs['bg'] = 'Bulgarian';
$langs['en'] = 'English';
$langs['ar'] = 'Arabian';

$def_language = get_option('language', 'website');

if ($def_language == false) {
    $def_language = 'en';
}
?>
<script type="text/javascript">
    $(document).ready(function () {
        $('#switch_language_ul li').on('click', function () {
            var selected = $(this).data('value');
            $.post(mw.settings.api_url + "change_language", { locale: selected })
                .done(function(data) {
                    mw.cookie.set('lang', selected);
                    location.reload();
                });
        });
    });
</script>
<script>
    mw.lib.require('flag_icons');
</script>
<?php if($langs) : ?>
        <div class="mw-dropdown mw-dropdown-default">
            <span class="mw-dropdown-value mw-ui-btn mw-ui-btn-info mw-dropdown-val"><span class="flag-icon flag-icon-<?php echo get_flag_icon($def_language); ?> m-r-10"></span> <?php _e('Select Language...'); ?></span>
            <div class="mw-dropdown-content">
                <ul id="switch_language_ul">
                    <?php foreach($langs as $key=>$lang): ?>
                        <li <?php if ($def_language == $key): ?> selected="" <?php endif; ?> data-value="<?php print $key ?>" style="color:#000;"><span class="flag-icon flag-icon-<?php echo get_flag_icon($key); ?> m-r-10"></span><?php print $lang ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
<?php endif; ?>