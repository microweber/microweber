<?php

$require_terms = get_option('require_terms', $params['module']);

if ($params['id'] == 'edit_template_iframe') {
	include 'edit_template_iframe.php';
	return;
}

$module_template = get_option('data-template', $params['id']);
if ($module_template == false and isset($params['template'])) {
    $module_template = $params['template'];
}


if ($module_template != false) {
    $template_file = module_templates($config['module'], $module_template);
} else {
    $template_file = module_templates($config['module'], 'default');
}


if (isset($template_file) and is_file($template_file) != false) {
    include($template_file);
} else {
    $template_file = module_templates($config['module'], 'default');
    include($template_file);
}
?>
<script>


    (function () {
       var init_newsletter_form = function (id, url) {
           mw.newsletters_is_saving = false;
           var form = mw.$('form#newsletters-form-' + id);
           if(form.length){
               form.append('<input type="hidden" name="mod_id" value="' + id + '" />');
           }
           form.submit(function () {
               if (mw.newsletters_is_saving === true) {
                   return false;
               }
               mw.newsletters_is_saving = true;
               mw.form.post('form#newsletters-form-' + id, url,
                   function (msg) {
                       mw.newsletters_is_saving = false;
                       mw.response(mw.$('form#newsletters-form-', id), resp);
                       if (typeof(this.success) != 'undefined') {
                           mw.$('form#newsletters-form-' + id + ' .hide-on-success').hide();
                           mw.$('form#newsletters-form-' + id + ' .show-on-success').show();
                       }
                       if (!!this.redirect) {
                           window.location.href = resp.redirect;
                       }
                   });
               return false;
           });
       };
        $(document).ready(function () {
            init_newsletter_form('<?php print $params['id'] ?>', '<?php print api_link('newsletter_subscribe'); ?>');
        });
    })();

</script>
