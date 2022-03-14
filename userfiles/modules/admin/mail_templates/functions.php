<?php

autoload_add_namespace(__DIR__.'/src/', 'MicroweberPackages\\Admin\\MailTemplates\\');

function get_mail_template_types()
{

    $email_template_types = array();

    $default_mail_templates = normalize_path(dirname(MW_PATH) . '/View/emails');
    $default_mail_templates = scandir($default_mail_templates);

    foreach ($default_mail_templates as $template_file) {
        if (strpos($template_file, "blade.php") !== false) {

            $template_type = str_replace('.blade.php', false, $template_file);

            $email_template_types[] = $template_type;
        }
    }

    return $email_template_types;
}

function get_default_mail_template_by_type($type = '')
{

    foreach (get_default_mail_templates() as $template) {
        if ($template['type'] == $type) {

            $template['message'] = file_get_contents(normalize_path(dirname(MW_PATH) . '/View/emails') . $template['id']);

            return $template;
        }
    }

}

function get_mail_templates_by_type($type = '')
{

    $templates = array();

    foreach (get_mail_templates() as $template) {

        if ($template['type'] == $type) {

            if (isset($template['is_default'])) {
                $template['name'] = $template['name'] . ' (default)';
            }

            $templates[] = $template;
        }
    }

    return $templates;
}

function get_mail_template_fields($type = '')
{

    if ($type == 'new_order' || $type == 'order_change_status' || $type == 'receive_payment') {

        $fields = array();
        $fields[] = array('value' => '{{id}}', 'label' => 'Order Id');
        $fields[] = array('value' => '{{date}}', 'label' => 'Date');
        $fields[] = array('value' => '{{cart_items}}', 'label' => 'Cart items');
        $fields[] = array('value' => '{{amount}}', 'label' => 'Amount');
        $fields[] = array('value' => '{{order_status}}', 'label' => 'Order Status');
        $fields[] = array('value' => '{{currency}}', 'label' => 'Currency');
        $fields[] = array('value' => '{{first_name}}', 'label' => 'First Name');
        $fields[] = array('value' => '{{last_name}}', 'label' => 'Last Name');
        $fields[] = array('value' => '{{email}}', 'label' => 'Email');
        $fields[] = array('value' => '{{country}}', 'label' => 'Country');
        $fields[] = array('value' => '{{city}}', 'label' => 'City');
        $fields[] = array('value' => '{{state}}', 'label' => 'State');
        $fields[] = array('value' => '{{zip}}', 'label' => 'Zip');
        $fields[] = array('value' => '{{address}}', 'label' => 'Address');
        $fields[] = array('value' => '{{phone}}', 'label' => 'Phone');
        $fields[] = array('value' => '{{transaction_id}}', 'label' => 'Transaction Id');
        $fields[] = array('value' => '{{order_id}}', 'label' => 'Order Id');

        return $fields;
    }

    if ($type == 'new_comment_reply') {

        $fields = array();
        $fields[] = array('value' => '{{comment_author}}', 'label' => 'Comment Author');
        $fields[] = array('value' => '{{comment_reply_author}}', 'label' => 'Comment Reply Author');
        $fields[] = array('value' => '{{post_url}}', 'label' => 'Post Url');

        return $fields;
    }

    if ($type == 'new_user_registration') {

        $fields = array();
        $fields[] = array('value' => '{{id}}', 'label' => 'User Id');
        $fields[] = array('value' => '{{username}}', 'label' => 'Username');
        $fields[] = array('value' => '{{email}}', 'label' => 'Email');
        $fields[] = array('value' => '{{first_name}}', 'label' => 'First Name');
        $fields[] = array('value' => '{{last_name}}', 'label' => 'Last Name');
        $fields[] = array('value' => '{{created_at}}', 'label' => 'Date of registration');
        $fields[] = array('value' => '{{verify_email_link}}', 'label' => 'Verify email link');

        return $fields;
    }

    if ($type == 'forgot_password') {

        $fields = array();
        $fields[] = array('value' => '{{id}}', 'label' => 'User Id');
        $fields[] = array('value' => '{{username}}', 'label' => 'Username');
        $fields[] = array('value' => '{{email}}', 'label' => 'Email');
        $fields[] = array('value' => '{{first_name}}', 'label' => 'First Name');
        $fields[] = array('value' => '{{last_name}}', 'label' => 'Last Name');
        $fields[] = array('value' => '{{created_at}}', 'label' => 'Date of registration');
        $fields[] = array('value' => '{{reset_password_link}}', 'label' => 'Reset password link');
        $fields[] = array('value' => '{{ip}}', 'label' => 'IP address');

        return $fields;
    }
}

function save_mail_template($data)
{

    $findMailTemplate = null;
    if (isset($data['id'])) {
        $findMailTemplate = \MicroweberPackages\Admin\MailTemplates\Models\MailTemplate::where('id', $data['id'])->first();
    }
    if ($findMailTemplate == null) {
        $findMailTemplate = new \MicroweberPackages\Admin\MailTemplates\Models\MailTemplate();
    }

    $findMailTemplate->type = $data['type'];

    if (isset($data['name'])) {
        $findMailTemplate->name = $data['name'];
    }

    if (isset($data['subject'])) {
        $findMailTemplate->subject = $data['subject'];
    }

    if (isset($data['from_name'])) {
        $findMailTemplate->from_name = $data['from_name'];
    }

    if (isset($data['from_email'])) {
        $findMailTemplate->from_email = $data['from_email'];
    }

    if (isset($data['copy_to'])) {
        $findMailTemplate->copy_to = $data['copy_to'];
    }

    if (isset($data['is_active'])) {
        $findMailTemplate->is_active = $data['is_active'];
    }

    $findMailTemplate->message = $data['message'];
   // $findMailTemplate->custom = $data['custom'];
   // $findMailTemplate->plain_text = $data['plain_text'];
    $findMailTemplate->save();

    if (isset($data['append_files']) && !empty($data['append_files'])) {

        $option = array();
        $option['option_value'] = $data['append_files'];
        $option['option_key'] = 'append_files';
        $option['option_group'] = 'mail_template_id_' . $findMailTemplate->id;

        mw()->option_manager->save($option);

    }

    return $findMailTemplate->id;
}


function get_mail_template_by_id($id, $type = false)
{

    $templates = get_mail_templates();

    foreach ($templates as $template) {

        if ($template['id'] == $id) {

            if (isset($template['is_default'])) {
                $template['message'] = file_get_contents(normalize_path(dirname(MW_PATH) . '/View/emails') . $template['id']);
            }

            return $template;
        }
    }

    return get_default_mail_template_by_type($type);
}

function get_default_mail_templates()
{

    $templates = array();

    $default_mail_templates = normalize_path(dirname(MW_PATH) . '/View/emails');
    $default_mail_templates = scandir($default_mail_templates);

    foreach ($default_mail_templates as $template_file) {
        if (strpos($template_file, "blade.php") !== false) {

            $template_type = str_replace('.blade.php', false, $template_file);
            $template_name = str_replace('_', ' ', $template_type);
            $template_name = ucfirst($template_name);

            $templates[] = array(
                'id' => $template_file,
                'type' => $template_type,
                'name' => $template_name,
                'subject' => $template_name,
                'from_name' => get_option('email_from_name', 'email'),
                'from_email' => get_option('email_from', 'email'),
                'copy_to' => '',
                'message' => '',
                'is_default' => true,
                'is_active' => 1
            );
        }
    }

    return $templates;
}

function get_mail_templates($params = array())
{
    $showTemplates = [];
    $defaultTemplates = get_default_mail_templates();
    $getTemplates = \MicroweberPackages\Admin\MailTemplates\Models\MailTemplate::all();
    if ($getTemplates->count() > 0) {
        $templates = $getTemplates->toArray();
        foreach ($templates as $template) {
            $showTemplates[] = $template;
        }
    }

    if (empty($showTemplates)) {
        $showTemplates = $defaultTemplates;
    } else {
        foreach ($defaultTemplates as $defaultTemplate) {
            $appendThisDefaultTemplate = true;
            foreach ($showTemplates as $template) {
                if ($template['type'] == $defaultTemplate['type']) {
                    $appendThisDefaultTemplate = false;
                }
            }
            if ($appendThisDefaultTemplate) {
                $showTemplates[] = $defaultTemplate;
            }
        }
    }

    return $showTemplates;
}

function delete_mail_template($params)
{
    if (!is_admin()) {
        return;
    }
    if (isset($params['id'])) {
        $table = "mail_templates";
        $id = $params['id'];
        return db_delete($table, $id);
    }
}

event_bind('mw.cart.confirm_email_send', function ($order_id) {

    $new_order_mail_template_id = mw()->app->option_manager->get('new_order_mail_template', 'orders');
    $mail_template = get_mail_template_by_id($new_order_mail_template_id, 'new_order');

    return array('mail_template'=>$mail_template);

});
