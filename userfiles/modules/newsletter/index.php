<?php


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
<script type="text/javascript">


    mw.init_newsletter_form<?php print md5($params['id']) ?> = function () {


        mw.newsletters_is_saving = false;

        mw.$('form#newsletters-form-<?php print $params['id'] ?>').submit(function () {

            if (mw.newsletters_is_saving == true) {
                return false;
            }

            mw.newsletters_is_saving = true;
            mw.form.post('form#newsletters-form-<?php print $params['id'] ?>', '<?php print api_link('newsletter_subscribe'); ?>',
                function (msg) {
                    mw.newsletters_is_saving = false;
                    var resp = this;


                    mw.response(mw.$('form#newsletters-form-<?php print $params['id'] ?>'), resp);
                    if (typeof(resp.success) != 'undefined') {
                        mw.$('form#newsletters-form-<?php print $params['id'] ?> .hide-on-success').hide();
                    }


                });
            return false;
        });
    }


    $(document).ready(function () {
        mw.init_newsletter_form<?php print md5($params['id']) ?>();
    });

</script>
