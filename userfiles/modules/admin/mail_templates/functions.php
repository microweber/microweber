<?php
function get_mail_template_types()
{

    $email_template_types = array();

    $default_mail_templates = normalize_path(MW_PATH . 'Views/emails');
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

            $template['message'] = file_get_contents(normalize_path(MW_PATH . 'Views/emails') . $template['id']);

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
        $fields[] = array('tag' => '{{id}}', 'name' => 'Order Id');
        $fields[] = array('tag' => '{{date}}', 'name' => 'Date');
        $fields[] = array('tag' => '{{cart_items}}', 'name' => 'Cart items');
        $fields[] = array('tag' => '{{amount}}', 'name' => 'Amount');
        $fields[] = array('tag' => '{{order_status}}', 'name' => 'Order Status');
        $fields[] = array('tag' => '{{currency}}', 'name' => 'Currency');
        $fields[] = array('tag' => '{{first_name}}', 'name' => 'First Name');
        $fields[] = array('tag' => '{{last_name}}', 'name' => 'Last Name');
        $fields[] = array('tag' => '{{email}}', 'name' => 'Email');
        $fields[] = array('tag' => '{{country}}', 'name' => 'Country');
        $fields[] = array('tag' => '{{city}}', 'name' => 'City');
        $fields[] = array('tag' => '{{state}}', 'name' => 'State');
        $fields[] = array('tag' => '{{zip}}', 'name' => 'Zip');
        $fields[] = array('tag' => '{{address}}', 'name' => 'Address');
        $fields[] = array('tag' => '{{phone}}', 'name' => 'Phone');
        $fields[] = array('tag' => '{{transaction_id}}', 'name' => 'Transaction Id');
        $fields[] = array('tag' => '{{order_id}}', 'name' => 'Order Id');

        return $fields;
    }

    if ($type == 'new_comment_reply') {

        $fields = array();
        $fields[] = array('tag' => '{{comment_author}}', 'name' => 'Comment Author');
        $fields[] = array('tag' => '{{comment_reply_author}}', 'name' => 'Comment Reply Author');
        $fields[] = array('tag' => '{{post_url}}', 'name' => 'Post Url');

        return $fields;
    }

    if ($type == 'new_user_registration') {

        $fields = array();
        $fields[] = array('tag' => '{{id}}', 'name' => 'User Id');
        $fields[] = array('tag' => '{{username}}', 'name' => 'Username');
        $fields[] = array('tag' => '{{email}}', 'name' => 'Email');
        $fields[] = array('tag' => '{{first_name}}', 'name' => 'First Name');
        $fields[] = array('tag' => '{{last_name}}', 'name' => 'Last Name');
        $fields[] = array('tag' => '{{created_at}}', 'name' => 'Date of registration');
        $fields[] = array('tag' => '{{verify_email_link}}', 'name' => 'Verify email link');

        return $fields;
    }

    if ($type == 'forgot_password') {

        $fields = array();
        $fields[] = array('tag' => '{{id}}', 'name' => 'User Id');
        $fields[] = array('tag' => '{{username}}', 'name' => 'Username');
        $fields[] = array('tag' => '{{email}}', 'name' => 'Email');
        $fields[] = array('tag' => '{{first_name}}', 'name' => 'First Name');
        $fields[] = array('tag' => '{{last_name}}', 'name' => 'Last Name');
        $fields[] = array('tag' => '{{created_at}}', 'name' => 'Date of registration');
        $fields[] = array('tag' => '{{reset_password_link}}', 'name' => 'Reset password link');
        $fields[] = array('tag' => '{{ip}}', 'name' => 'IP address');

        return $fields;
    }
}

api_expose_admin('save_mail_template');
function save_mail_template($data)
{
    if (!is_admin()) {
        return;
    }

    $data['allow_html'] = '1';

    $table = "mail_templates";
    $save = db_save($table, $data);


    if (isset($data['append_files']) && !empty($data['append_files'])) {

        $option = array();
        $option['option_value'] = $data['append_files'];
        $option['option_key'] = 'append_files';
        $option['option_group'] = 'mail_template_id_' . $save;

        mw()->option_manager->save($option);

    }

    return $save;
}


function get_mail_template_by_id($id, $type = false)
{

    $templates = get_mail_templates();

    foreach ($templates as $template) {

        if ($template['id'] == $id) {

            if (isset($template['is_default'])) {
                $template['message'] = file_get_contents(normalize_path(MW_PATH . 'Views/emails') . $template['id']);
            }

            return $template;
        }
    }

    return get_default_mail_template_by_type($type);
}

function get_default_mail_templates()
{

    $templates = array();

    $default_mail_templates = normalize_path(MW_PATH . 'Views/emails');
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
    if (is_string($params)) {
        $params = parse_params($params);
    }

    $params['table'] = "mail_templates";
    $templates = db_get($params);

    $defaultTemplates = get_default_mail_templates();

    if (empty($templates)) {
        $templates = $defaultTemplates;
    } else {
        if (!$templates) {
            $templates = array();
        }
        if (!$defaultTemplates) {
            $defaultTemplates = array();
        }
        array_merge($templates, $defaultTemplates);
    }

    return $templates;
}

api_expose_admin('delete_mail_template');
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