<?php if(get_option('is_multilang', 'website')): ?>
<select class="mw-ui-field mw-language" onchange="mw.change_language($(this).val());">
  <?php
  $langs = multilang_locales();
  foreach($langs as $lang): ?>
    <option value="<?php echo $lang; ?>" <?php if($lang == App::getLocale()) echo 'selected'; ?>></option>
  <?php endforeach; ?>
</select>
<script type="text/javascript" src="<?php echo $config['url_to_module']; ?>langs.js"></script>
<?php else: ?>
<?php if(in_live_edit()): ?>
<span>(Open module settings to enable multi language support)</span>
<?php endif; ?>
<?php endif; ?>
