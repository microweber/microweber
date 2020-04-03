<?php

namespace Microweber\Providers;

use League\Csv\Writer;
use Microweber\Utils\MailProvider;


class FormsManager
{
    /** @var \Microweber\Application */
    public $app;

    public function __construct($app = null)
    {
        if (is_object($app)) {
            $this->app = $app;
        } else {
            $this->app = mw();
        }

        if (!defined('MW_DB_TABLE_COUNTRIES')) {
            define('MW_DB_TABLE_COUNTRIES', 'countries');
        }
        if (!defined('MW_DB_TABLE_FORMS_LISTS')) {
            define('MW_DB_TABLE_FORMS_LISTS', 'forms_lists');
        }

        if (!defined('MW_DB_TABLE_FORMS_DATA')) {
            define('MW_DB_TABLE_FORMS_DATA', 'forms_data');
        }
    }

    public function get_entires($params = false)
    {
        $params = parse_params($params);
        $table = MW_DB_TABLE_FORMS_DATA;
        $params['table'] = $table;

        if (!isset($params['order_by'])) {
            $params['order_by'] = 'created_at desc';
        }
        if (isset($params['keyword'])) {
            $params['search_in_fields'] = array('id', 'created_at', 'created_by', 'rel_type', 'user_ip', 'module_name', 'form_values', 'url');
        }

        $is_single = false;

        if (isset($params['single']) and $params['single']) {
            $is_single = true;
            unset($params['single']);

        }

        $data = $this->app->database_manager->get($params);

        $ret = array();
        if (is_array($data)) {
            foreach ($data as $item) {
                $fields = @json_decode($item['form_values'], true);
                if (!$fields) {
                    $fields = @json_decode(html_entity_decode($item['form_values']), true);
                }

                if (is_array($fields)) {
                    ksort($fields);
                    $item['custom_fields'] = array();
                    foreach ($fields as $key => $value) {
                        $item['custom_fields'][$key] = $value;
                    }
                }

                if ($is_single) {
                    return $item;
                }
                $ret[] = $item;
            }

            return $ret;
        } else {
            return $data;
        }
    }

    public function save_list($params)
    {
        $table = MW_DB_TABLE_FORMS_LISTS;

        if (isset($params['mw_new_forms_list'])) {
            $params['id'] = 0;
            $params['id'] = 0;
            $params['title'] = $params['mw_new_forms_list'];
        }
        if (isset($params['for_module'])) {
            $params['module_name'] = $params['for_module'];
        }

        $params['table'] = $table;
        $id = $this->app->database_manager->save($table, $params);
        if (isset($params['for_module_id'])) {
            $data = array();
            $data['module'] = $params['module_name'];
            $data['option_group'] = $params['for_module_id'];
            $data['option_key'] = 'list_id';
            $data['option_value'] = $id;
            $this->app->option_manager->save($data);
        }

        return array('success' => 'List is updated', $params);
    }

    public function post($params)
    {
        if (isset($params['for_id']) && !empty($params['for_id'])) {
            $params['for_id'] = str_replace("-custom-fields", false, $params['for_id']);
        }

        $adm = $this->app->user_manager->is_admin();
        if (defined('MW_API_CALL')) {
            //            $validate_token = $this->app->user_manager->csrf_validate($params);
//            if (!$adm) {
//                if ($validate_token == false) {
//                    return array('error' => 'Invalid token!');
//                }
//            }
        }
        $before_process = $this->app->event_manager->trigger('mw.forms_manager.before_post', $params);
        if (is_array($before_process) and !empty($before_process)) {
            foreach ($before_process as $before_process_item) {
                if ($before_process_item === false) {
                    return;
                }
            }
        }

        $table = MW_DB_TABLE_FORMS_DATA;
        mw_var('FORCE_SAVE', $table);

        if (isset($params['id'])) {
            if ($adm == false) {
                return array('error' => 'Error: Only admin can edit forms!');
            }
        }
        $for = 'module';
        if (isset($params['for'])) {
            $for = $params['for'];
        }

        if (isset($params['for_id'])) {
            $for_id = $params['for_id'];
        } elseif (isset($params['data-id'])) {
            $for_id = $params['data-id'];
        } elseif (isset($params['id'])) {
            $for_id = $params['id'];
        }

        if (isset($params['rel_id'])) {
            $for_id = $params['rel_id'];
        }

        if (!isset($for_id)) {
            return array('error' => 'Please provide for_id parameter with module id');
        }


        $terms_and_conditions_name = 'terms_contact';

        $default_mod_id = 'contact_form_default';


        $dis_cap = $this->app->option_manager->get('disable_captcha', $for_id) == 'y';
        if (!$dis_cap) {
            $dis_cap = $this->app->option_manager->get('disable_captcha', $default_mod_id) == 'y';
        }

        $email_from = $this->app->option_manager->get('email_from', $for_id);
        if (!$email_from) {
        	$email_from = $this->app->option_manager->get('email_from', $default_mod_id);
        }

        $from_name = $this->app->option_manager->get('email_from_name', $for_id);
        if (!$from_name) {
        	$from_name = $this->app->option_manager->get('email_from_name', $default_mod_id);
        }

        $newsletter_subscription = $this->app->option_manager->get('newsletter_subscription', $for_id) == 'y';
        if (!$newsletter_subscription) {
            $newsletter_subscription = $this->app->option_manager->get('newsletter_subscription', $default_mod_id) == 'y';
        }


        $email_to = $this->app->option_manager->get('email_to', $for_id);
        if (!$email_to) {
            $email_to = $this->app->option_manager->get('email_to', $default_mod_id);
        }

        $email_bcc = $this->app->option_manager->get('email_bcc', $for_id);
        if (!$email_bcc) {
            $email_bcc = $this->app->option_manager->get('email_bcc', $default_mod_id);
        }

        $email_reply = $this->app->option_manager->get('email_reply', $for_id);
        if (!$email_reply) {
            $email_reply = $this->app->option_manager->get('email_reply', $default_mod_id);
        }

        $email_autorespond = $this->app->option_manager->get('email_autorespond', $for_id);
        if (!$email_autorespond) {
            $email_autorespond = $this->app->option_manager->get('email_autorespond', $default_mod_id);
        }

        $email_autorespond_subject = $this->app->option_manager->get('email_autorespond_subject', $for_id);
        $email_notification_subject = $this->app->option_manager->get('email_notification_subject', $for_id);

        if (!$email_notification_subject) {
            $email_notification_subject = $this->app->option_manager->get('email_notification_subject', $default_mod_id);
        }

        if (!$email_autorespond_subject) {
            $email_autorespond_subject = $this->app->option_manager->get('email_autorespond_subject', $default_mod_id);
        }


        $email_redirect_after_submit = $this->app->option_manager->get('email_redirect_after_submit', $for_id);
        if (!$email_redirect_after_submit) {
            $email_redirect_after_submit = $this->app->option_manager->get('email_redirect_after_submit', $default_mod_id);
        }


        $user_id_or_email = false;
        if (is_logged()) {
            $user_id_or_email = user_id();
        } else {
            foreach ($params as $param_k => $param_v) {
                if (is_string($param_v)) {
                    if (filter_var($param_v, FILTER_VALIDATE_EMAIL)) {
                        $user_id_or_email = $param_v;
                    }
                }
            }
        }



        if (isset($params['captcha'])) {
            $dis_cap = false;
        }

        if ($dis_cap == false) {
            if (!isset($params['captcha'])) {
                return array(
                    'error' => _e('Please enter the captcha answer!', true),
                    'form_data_required' => 'captcha',
                    'form_data_module' => 'captcha'
                );


            } else {
//                if ($for_id != false) {
//                    $validate_captcha = mw()->captcha_manager->validate($params['captcha'], $for_id);
//                    if (!$validate_captcha) {
//                        $validate_captcha = mw()->captcha_manager->validate($params['captcha']);
//                    }
//                } else {
//                    $validate_captcha = mw()->captcha_manager->validate($params['captcha']);
//                }

                $validate_captcha = $this->app->captcha_manager->validate($params['captcha'], $for_id);
                if (!$validate_captcha) {

                    return array(
                        'error' => _e('Invalid captcha answer!', true),
                        'captcha_error' => true,
                        'form_data_required' => 'captcha',
                        'form_data_module' => 'captcha'
                    );


                }
            }
        }


        if (isset($params['_token'])) {
            unset($params['_token']);
        }
        if (isset($params['token'])) {
            unset($params['token']);
        }
        if (isset($params['captcha'])) {
            unset($params['captcha']);
        }
        if (isset($params['id'])) {
            unset($params['id']);
        }



        $user_require_terms = $this->app->option_manager->get('require_terms', $for_id);


        if (!$user_require_terms) {
            $user_require_terms = $this->app->option_manager->get('require_terms', $default_mod_id);
        }

        if ($user_require_terms) {

            if (!$user_id_or_email) {
                return array(
                    'error' => _e('You must provide email address', true),
                    'form_data_required' => 'email'
                );

            } else {

                $check_term = $this->app->user_manager->terms_check($terms_and_conditions_name, $user_id_or_email);

                if (!$check_term) {
                    if (isset($params['terms']) and $params['terms']) {
                        $this->app->user_manager->terms_accept($terms_and_conditions_name, $user_id_or_email);
                    } else {
                        return array(
                            'error' => _e('You must agree to The Terms and Conditions', true),
                            'form_data_required' => 'terms',
                            'form_data_module' => 'users/terms'
                        );
                    }
                }
            }
        }


        // ezyweb added newsletter subscription
        if ($newsletter_subscription and isset($params['newsletter_subscribe']) and $params['newsletter_subscribe']) {

            if ($user_require_terms and $user_id_or_email) {

                // terms_contact already logged now log terms_newsletter using the same authorisation
                $check_term = $this->app->user_manager->terms_check('terms_newsletter', $user_id_or_email);

                if (!$check_term) {
                    if (isset($params['terms']) and $params['terms']) {
                        $this->app->user_manager->terms_accept('terms_newsletter', $user_id_or_email);
                    } else {
                        return array(
                            'error' => _e('You must agree to The Terms and Conditions', true),
                            'form_data_required' => 'terms',
                            'form_data_module' => 'users/terms'
                        );
                    }
                }
            }

            if ($user_id_or_email) {

                if (is_numeric($user_id_or_email)) {
                    $user = $this->app->user_manager->get_by_id($user_id_or_email);
                    $email = $user['email'];
                } else {
                    $email = $user_id_or_email;
                }

                $subscriber_data = [
                    'email' => $email,
                    'confirmation_code' => str_random(30),
                    'is_subscribed' => 1
                ];
                $name = false;

                foreach ($params as $param_k => $param_v) {
                    if (!$name and is_string($param_v) and is_string($param_k)) {
                        if(stristr($param_k,'name')){
                            $name = $param_v;
                        }
                    }
                }
                if($name){
                    $subscriber_data['name'] = $name;
                }
                $this->app->database_manager->save('newsletter_subscribers', $subscriber_data);
            }
        }


        // if ($for=='module'){
        $list_id = $this->app->option_manager->get('list_id', $for_id);
        //  }


        if (isset($params['subject'])) {
            $email_notification_subject = $params['subject'];
        }

        if (!isset($list_id) or $list_id == false) {
            $list_id = 0;
        }

        $to_save = array();
        $fields_data = array();

        $get_fields = array();
        $get_fields['rel_type'] = $for;
        $get_fields['rel_id'] = $for_id;
        $get_fields['return_full'] = true;

        $more = $this->app->fields_manager->get($get_fields);

        $cf_to_save = array();
        if (!empty($more)) {
            foreach ($more as $item) {
                if (isset($item['name'])) {
                    $cfn = ($item['name']);

                    $cfn2 = str_replace(' ', '_', $cfn);

                    if (isset($params[$cfn2]) and $params[$cfn2] != false) {
                        $fields_data[$cfn2] = $params[$cfn2];
                        $item['value'] = $params[$cfn2];
                        $cf_to_save[$cfn] = $item;
                    } elseif (isset($params[$cfn]) and $params[$cfn] != false) {
                        $fields_data[$cfn] = $params[$cfn];
                        $item['value'] = $params[$cfn2];
                        $cf_to_save[$cfn] = $item;
                    }
                }
            }
        } else {
            $cf_to_save = $params;
        }
        $save = 1;

        $skip_saving_emails = $this->app->option_manager->get('skip_saving_emails', $for_id);
        if (!$skip_saving_emails) {
            $skip_saving_emails = $this->app->option_manager->get('skip_saving_emails', $default_mod_id);
            $skip_saving_emails = $this->app->option_manager->get('skip_saving_emails', $default_mod_id);
        }
        if ($skip_saving_emails !== 'y') {

            $to_save['list_id'] = $list_id;
            $to_save['rel_id'] = $for_id;
            $to_save['rel_type'] = $for;

            $to_save['user_ip'] = MW_USER_IP;

            if (isset($params['module_name'])) {
                $to_save['module_name'] = $params['module_name'];
            }

            if (!empty($fields_data)) {
                $to_save['form_values'] = json_encode($fields_data);
            } else {
                $to_save['form_values'] = json_encode($params);
            }

            $save = $this->app->database_manager->save($table, $to_save);
            $event_params = $params;
            $event_params['saved_form_entry_id'] = $save;

            $this->app->event_manager->trigger('mw.forms_manager.after_post', $event_params);

        }

        if (isset($params['module_name'])) {
            $pp_arr = $params;
            $pp_arr['ip'] = MW_USER_IP;
            unset($pp_arr['module_name']);
            if (isset($pp_arr['rel_type'])) {
                unset($pp_arr['rel_type']);
            }

            if (isset($pp_arr['rel_id'])) {
                unset($pp_arr['rel_id']);
            }

            if (isset($pp_arr['list_id'])) {
                unset($pp_arr['list_id']);
            }

            if (isset($pp_arr['for'])) {
                unset($pp_arr['for']);
            }

            if (isset($pp_arr['for_id'])) {
                unset($pp_arr['for_id']);
            }


            if (isset($pp_arr['message'])) {
                $temp = $pp_arr['message'];
                $temp = nl2br($temp);
                unset($pp_arr['message']);
                $pp_arr['message'] = $temp; // push to end of array
            }
            $user_mails = array();

            $notif = array();
            $notif['module'] = $params['module_name'];
            $notif['rel_type'] = 'forms_data';
            $notif['rel_id'] = $save;
            $notif['title'] = 'New form entry';
            $notif['description'] = $email_notification_subject ?: 'You have new form entry';
            $notif['content'] = 'You have new form entry from ' . $this->app->url_manager->current(1) . '<br />' . $this->app->format->array_to_ul($pp_arr);
            $this->app->notifications_manager->save($notif);

            if ($email_to == false) {
                $email_to = $this->app->option_manager->get('email_from', 'email');
            }


            $admin_user_mails = array();

            if ($email_to == false) {
                $admins = $this->app->user_manager->get_all('is_admin=1');
                if (is_array($admins) and !empty($admins)) {
                    foreach ($admins as $admin) {
                        if (isset($admin['email']) and (filter_var($admin['email'], FILTER_VALIDATE_EMAIL))) {
                            $admin_user_mails[] = $admin['email'];
                            $email_to = $admin['email'];
                            $user_mails[] = $admin['email'];
                        }
                    }
                }

            }

            if (is_array($params) and !empty($params)) {
                foreach ($params as $param) {
                     if (is_string($param) and (filter_var($param, FILTER_VALIDATE_EMAIL))) {
                        $user_mails[] = $param;
                    }
                }

            }


            if ($email_to != false) {
                $mail_autoresp = 'Thank you for your request!';

                if ($email_autorespond != false) {
                    $mail_autoresp = $email_autorespond;
                }

                if ($mail_autoresp) {
                    foreach ($params as $k => $v) {
                        if (is_string($v) and !is_array($k)) {
                            $rk = '{' . $k . '}';
                            $mail_autoresp = str_replace($rk, $v, $mail_autoresp);
                        }
                    }
                }

                $user_mails[] = $email_to;
                if (isset($email_bcc) and (filter_var($email_bcc, FILTER_VALIDATE_EMAIL))) {
                    $user_mails[] = $email_bcc;
                }

                // $email_from = false;
                if (!$email_from and isset($cf_to_save) and !empty($cf_to_save)) {
                    foreach ($cf_to_save as $value) {
                        if (is_array($value) and isset($value['value'])) {
                            $to = $value['value'];
                        } else {
                            $to = $value;
                        }

                        if (isset($to) and (filter_var($to, FILTER_VALIDATE_EMAIL))) {
                            $user_mails[] = $to;
                            $email_from = $to;
                        }
                    }
                }


               //  $from_name = $email_from;
                if (isset($params['name']) and $params['name']) {
                    $from_name = $params['name'];
                }
                if (isset($params['from_name']) and $params['from_name']) {
                    $from_name = $params['from_name'];
                }

                if (!empty($user_mails)) {
                    array_unique($user_mails);

                    $append_files = $this->app->option_manager->get('append_files', $for_id);
                    if (!$append_files) {
                        $append_files = $this->app->option_manager->get('append_files', $default_mod_id);
                    }

                    $append_files_ready = array();
                    if (!empty($append_files)) {
                        $append_files_ready = explode(",", $append_files);
                    }
                  //  var_dump($user_mails);

                    $email_autorespond = $this->app->option_manager->get('email_autorespond', $for_id);

                    $sender = new \Microweber\Utils\MailSender();
                    $sender->silent_exceptions = true;
                    foreach ($user_mails as $value) {
                        if ($value == $email_to || $value == $email_bcc) {
                            $msg = $notif['content'];
                            $subj = $notif['description'];
                            $from = $email_from;

                            $sender->send($value, $subj, $msg, $from, false, false, $email_from, $from_name, $email_reply, $append_files_ready);
                        } else {

                            $msg = $mail_autoresp;
                            $subj = $email_autorespond_subject ?: 'Thank you!';
                            $from = false;
                            $sender->send($value, $subj, $msg, $from, false, false, false, false, $email_reply, $append_files_ready);
                        }


                    }
                }
            }
        }

        if (isset($params['module_name'])) {

            $params['list_id'] = $list_id;
            $params['option_group'] = $params['module_name'];
            $params['rel'] = $params['for'];
            $params['rel_id'] = $params['for_id'];

            event_trigger('mw.mail_subscribe', $params);
        }

        $success = array();
        $success['id'] = $save;
        $success['success'] = _e('Your message has been sent', true);

        if ($email_redirect_after_submit) {
            $success['redirect'] = $email_redirect_after_submit;
        }

        return $success;

    }

    public function get_lists($params)
    {
        $params = parse_params($params);
        $table = MW_DB_TABLE_FORMS_LISTS;
        $params['table'] = $table;

        return $this->app->database_manager->get($params);
    }

    public function countries_list($full = false)
    {
        static $data = array();

        if (empty($data)) {
            $countries_file_userfiles = normalize_path(userfiles_path() . 'country.csv', false);
            $countries_file = normalize_path(MW_PATH . 'Utils/lib/country.csv', false);

            if (is_file($countries_file_userfiles)) {
                $countries_file = $countries_file_userfiles;
            }

            if (is_file($countries_file)) {
                $data = array_map('str_getcsv', file($countries_file));

                if (isset($data[0])) {
                    unset($data[0]);
                }
            }
        }

        if ($full == false and !empty($data)) {
            $res = array();
            foreach ($data as $item) {
                $res[] = $item[1];
            }

            return $res;
        }

        return $data;
    }

    public function states_list($country = false)
    {
        if (!$country) {
            return false;
        }
        $states = new \Microweber\Utils\CountryState();
        $res = $states->getStates($country);

        return $res;
    }

    public function delete_entry($data)
    {
        $adm = $this->app->user_manager->is_admin();
        if ($adm == false) {
            return array('error' => 'Error: not logged in as admin.' . __FILE__ . __LINE__);
        }
        if (isset($data['id'])) {
            $c_id = intval($data['id']);
            $this->app->database_manager->delete_by_id('forms_data', $c_id);
        }

        $this->app->cache_manager->delete('forms_data');
        $this->app->cache_manager->delete('forms');

        return true;
    }

    public function delete_list($data)
    {
        $adm = $this->app->user_manager->is_admin();
        if ($adm == false) {
            return array('error' => 'Error: not logged in as admin.' . __FILE__ . __LINE__);
        }

        if (isset($data['id'])) {
            $c_id = intval($data['id']);
            $this->app->database_manager->delete_by_id('forms_lists', $c_id);
            $this->app->database_manager->delete_by_id('forms_data', $c_id, 'list_id');
        }

        return true;
    }

    public function export_to_excel($params)
    {
        //this function is experimental
        set_time_limit(0);

        //   $data_for_csv = array();

        $adm = $this->app->user_manager->is_admin();
        if ($adm == false) {
            return array('error' => 'Error: not logged in as admin.' . __FILE__ . __LINE__);
        }
        if (!isset($params['id'])) {
            return array('error' => 'Please specify list id! By posting field id=the list id ');
        } else {
            $lid = intval($params['id']);
            $data = get_form_entires('limit=100000&list_id=' . $lid);

            $surl = $this->app->url_manager->site();
            $csv_output = '';
            /*   if (is_array($data)) {
                   $csv_output = 'id,';
                   $csv_output .= 'created_at,';
                   $csv_output .= 'user_ip,';
                   foreach ($data as $item) {
                       if (isset($item['custom_fields'])) {
                           foreach ($item['custom_fields'] as $k => $v) {
                               $csv_output .= $this->app->format->no_dashes($k) . ',';
                               //      $csv_output .= "\t";
                           }
                       }
                   }

                   $csv_output .= "\n";

                   foreach ($data as $item) {
                       if (isset($item['custom_fields'])) {
                           $csv_output .= $item['id'] . ',';
                           //   $csv_output .= "\t";
                           $csv_output .= $item['created_at'] . ',';
                           //  $csv_output .= "\t";
                           $csv_output .= $item['user_ip'] . ',';
                           //   $csv_output .= "\t";

                           foreach ($item['custom_fields'] as $item1 => $val) {
                               $output_val = $val;

                               if (is_array($output_val)) {
                                   $output_val = mw()->format->array_to_ul($output_val);
                               }
                               //  $output_val = nl2br($output_val);
                               $output_val = str_replace('{SITE_URL}', $surl, $output_val);


                               $csv_output .= $output_val . ',';
                               //   $csv_output .= "\t";
                           }
                           $csv_output .= "\n";
                       }
                   }
               }*/


            $data_for_csv = array();
            $data_known_keys = array();


            foreach ($data as $item) {


                $item_for_csv = array();
                $item_for_csv['id'] = $item['id'];
                $item_for_csv['created_at'] = $item['created_at'];
                $item_for_csv['user_ip'] = $item['user_ip'];
                if (isset($item['custom_fields'])) {
                    foreach ($item['custom_fields'] as $k1 => $v1) {
                        $output_val = $v1;

                        if (is_array($output_val)) {
                            $output_val = mw()->format->array_to_seperator($output_val);
                        }
                        $item_for_csv[$k1] = $output_val;

                    }
                }

                $data_known_keys = array_merge($data_known_keys, array_keys($item_for_csv));
                $data_known_keys = array_unique($data_known_keys);
                $data_for_csv[] = $item_for_csv;
            }

            foreach ($data_known_keys as $k => $v) {
                $data_known_keys[$k] = $this->app->format->no_dashes($v);
            }


            $filename = 'export' . '_' . date('Y-m-d_H-i', time()) . uniqid() . '.csv';
            $filename_path = userfiles_path() . 'export' . DS . 'forms' . DS;
            $filename_path_index = userfiles_path() . 'export' . DS . 'forms' . DS . 'index.php';
            if (!is_dir($filename_path)) {
                mkdir_recursive($filename_path);
            }
            if (!is_file($filename_path_index)) {
                @touch($filename_path_index);
            }
            $filename_path_full = $filename_path . $filename;


            $writer = Writer::createFromPath($filename_path_full, 'w+');
            $writer->setNewline("\r\n");
            $writer->insertOne($data_known_keys);

            $writer->insertAll($data_for_csv);

            $download = $this->app->url_manager->link_to_file($filename_path_full);

            return array('success' => 'Your file has been exported!', 'download' => $download);
        }
    }
}
