<?php

namespace MicroweberPackages\Form;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use League\Csv\Writer;
use MicroweberPackages\Form\Models\Form;
use MicroweberPackages\Form\Models\FormRecipient;
use MicroweberPackages\Form\Notifications\NewFormEntry;
use MicroweberPackages\Form\Notifications\NewFormEntryAutoRespond;
use MicroweberPackages\Option\Facades\Option;
use MicroweberPackages\User\Models\User;


class FormsManager
{
    /** @var \MicroweberPackages\App\LaravelApplication */
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

        $newsletter_subscription = $this->app->option_manager->get('newsletter_subscription', $for_id) == 'y';
        if (!$newsletter_subscription) {
            $newsletter_subscription = $this->app->option_manager->get('newsletter_subscription', $default_mod_id) == 'y';
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
                    'form_data_required_params' => array('captcha_parent_for_id' => $for_id),
                    'form_data_module' => 'captcha'
                );

            } else {

                $validate_captcha = $this->app->captcha_manager->validate($params['captcha'], $for_id);
                if (!$validate_captcha) {
                    return array(
                        'error' => _e('Invalid captcha answer!', true),
                        'captcha_error' => true,
                        'form_data_required' => 'captcha',
                        'form_data_required_params' => array('captcha_parent_for_id' => $for_id),
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
                        if (stristr($param_k, 'name')) {
                            $name = $param_v;
                        }
                    }
                }
                if ($name) {
                    $subscriber_data['name'] = $name;
                }
                $this->app->database_manager->save('newsletter_subscribers', $subscriber_data);
            }
        }

        $list_id = $this->app->option_manager->get('list_id', $for_id);
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

        $cfToSave = array();
        if (!empty($more)) {
            foreach ($more as $item) {
                 if (isset($item['name'])) {
                    $cfn = ($item['name']);

                  //  $cfn2 = str_replace(' ', '_', $cfn);
                    $cfn2 = str_replace(' ', '_', trim($cfn));

                    if (isset($params[$cfn2]) and $params[$cfn2] != false) {
                        $fields_data[$cfn2] = $params[$cfn2];
                        $item['value'] = $params[$cfn2];
                        $cfToSave[$cfn] = $item;
                    } elseif (isset($params[$cfn]) and $params[$cfn] != false) {
                        $fields_data[$cfn] = $params[$cfn];
                        $item['value'] = $params[$cfn2];
                        $cfToSave[$cfn] = $item;
                    } else {
                        $cfn3 = url_title($item['name']);
                         foreach ($params as $param_key=>$param_vals){
                            $cfn_url = url_title($param_key);
                             if($cfn3 == $cfn_url){

                                 $item['value'] = $params[$param_key];
                                 $cfToSave[$cfn] = $param_vals;
                                 $fields_data[$cfn] = $params[$param_key];

                             }
                        }
                    }
                }
            }
        } else {
            $cfToSave = $params;
        }


          $save = 1;

        $skip_saving_emails = $this->app->option_manager->get('skip_saving_emails', $for_id);
        if (!$skip_saving_emails) {
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

            // Save Atachments
            $files_utils = new \MicroweberPackages\Utils\System\Files();

            $allowedFilesForSave = [];
            $uploadFilesValidation = [];

            if (is_array($more)) {
                foreach ($more as $field) {

                    $fieldRules = [];

                    if ($field['type'] != 'upload') {
                        continue;
                    }

                    if (!isset($_FILES[$field['name_key']]) && isset($field['options']['required']) && $field['options']['required'] == 1) {
                        $fieldRules[] = 'required';
                        $_FILES[$field['name_key']] = true;
                    }

                    if (!isset($_FILES[$field['name_key']])) {
                        continue;
                    }

                    $allowedFilesForSave[$field['name_key']] = $_FILES[$field['name_key']];

                    $mimeTypes = [];

                    if (isset($field['options']['file_types']) && !empty($field['options']['file_types'])) {
                        foreach ($field['options']['file_types'] as $optionFileTypes) {
                            if (!empty($optionFileTypes)) {

                                if ($optionFileTypes == 'images') {
                                    $fieldRules[] = 'valid_image';
                                }

                                $mimeTypesString = $files_utils->get_allowed_files_extensions_for_upload($optionFileTypes);
                                $mimeTypesArray = explode(',', $mimeTypesString);
                                $mimeTypes = array_merge($mimeTypes, $mimeTypesArray);
                            }
                        }
                    }

                    if (empty($mimeTypes)) {
                        $mimeTypes = $files_utils->get_allowed_files_extensions_for_upload('images');
                    }

                    if (!empty($mimeTypes) && is_array($mimeTypes)) {
                        $mimeTypes = implode(',', $mimeTypes);
                    }

                    $fieldRules[] = 'mimes:' . $mimeTypes;

                    if (!empty($fieldRules)) {
                        $uploadFilesValidation[$field['name_key']] = $fieldRules;
                    }
                }
            }

            // Validation is ok
            if (isset($allowedFilesForSave) && !empty($allowedFilesForSave)) {

                $validator = Validator::make($params, $uploadFilesValidation);
                if ($validator->fails()) {
                    $validatorMessages = false;

                    foreach ($validator->messages()->toArray() as $inputFieldErros) {
                        $validatorMessages = reset($inputFieldErros);
                    }
                    return array(
                        'error' => $validatorMessages
                    );
                }

                if (isset($params['module_name'])) {
                    $target_path_name = '/' . $params['module_name'];
                } else {
                    $target_path_name = '/attachments';
                }

                $target_path = media_uploads_path();
                $target_path .= $target_path_name;
                $target_path = normalize_path($target_path, 0);
                if (!is_dir($target_path)) {
                    mkdir_recursive($target_path);
                }
                if ($allowedFilesForSave and !empty($allowedFilesForSave)) {
                    foreach ($allowedFilesForSave as $fieldName => $file) {

                        $targetFileName = $target_path_name . '/' . $file['name'];

                        if (is_file($target_path . '/' . $file['name'])) {
                            $targetFileName = $target_path_name . '/' . date('Ymd-His') . $file['name'];
                        }

                        $fileContent = @file_get_contents($file['tmp_name']);
                        if ($fileContent) {
                            $save = Storage::disk('media')->put($targetFileName, $fileContent);
                            if ($save) {

                                $realPath = Storage::disk('media')->path($targetFileName);

                                $file_mime = \Illuminate\Support\Facades\File::mimeType($realPath);
                                $file_extension = \Illuminate\Support\Facades\File::extension($realPath);
                                $file_size = \Illuminate\Support\Facades\File::size($realPath);

                                $mediaFileUrl = Storage::disk('media')->url($targetFileName);
                                $mediaFileUrl = str_replace(site_url(), '{SITE_URL}', $mediaFileUrl);
                                $fields_data[$fieldName] = [
                                    'type' => 'upload',
                                    'url' => $mediaFileUrl,
                                    'file_name' => $file['name'],
                                    'file_extension' => $file_extension,
                                    'file_mime' => $file_mime,
                                    'file_size' => $file_size,
                                ];
                            }

                        } else {
                            return array(
                                'error' => _e('Invalid file.', true)
                            );
                        }
                    }
                }
            }
            // End of attachments
            if (!empty($fields_data)) {
                $to_save['form_values'] = json_encode($fields_data);
            } else {
                $to_save['form_values'] = json_encode($params);
            }

            $save = $this->app->database_manager->save($table, $to_save);
            $event_params = $params;
            $event_params['saved_form_entry_id'] = $save;

            $formModel = Form::find($save);

            // Notification::send(User::whereIsAdmin(1)->get(), new NewFormEntry($formModel));

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

            $userEmails = array();

            if (isset($save) and $save) {

                if (is_array($params) and !empty($params)) {
                    foreach ($params as $param) {
                        if (is_string($param) and (filter_var($param, FILTER_VALIDATE_EMAIL))) {
                            $userEmails[$param] = $param;
                        }
                    }
                }

                if (isset($cfToSave) and !empty($cfToSave)) {
                    foreach ($cfToSave as $value) {
                        if (is_array($value) and isset($value['value'])) {
                            $mailsFromForm = $value['value'];
                        } else {
                            $mailsFromForm = $value;
                        }
                        if (filter_var($mailsFromForm, FILTER_VALIDATE_EMAIL)) {
                            $userEmails[$mailsFromForm] = $mailsFromForm;
                        }
                    }
                }

                if (!empty($userEmails)) {

                    if (get_option('email_custom_receivers', $for_id)) {
                        $sendFormDataToReceivers = get_option('email_to', $for_id);
                    } else {
                        $sendFormDataToReceivers = get_option('email_to', 'contact_form_default');
                    }

                    if (empty(!$sendFormDataToReceivers)) {
                        $receivers =  $this->explodeMailsFromString($sendFormDataToReceivers);
                        if (!empty($receivers)) {
                            foreach($receivers as $receiver) {
                                Notification::route('mail', $receiver)->notify(new NewFormEntry($formModel));
                            }
                        }
                    }

                    if (get_option('email_autorespond_enable', $for_id) && is_array($userEmails)) {
                        foreach ($userEmails as $userEmail) {

                            $findFormRecipient = FormRecipient::where('email', $userEmail)->first();
                            if ($findFormRecipient == null) {
                                $findFormRecipient = new FormRecipient();
                                $findFormRecipient->email = $userEmail;
                                $findFormRecipient->save();
                            }

                            $findFormRecipient->notifyNow((new NewFormEntryAutoRespond($formModel)));
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

    public function explodeMailsFromString($emailsListString)
    {
        $emailsList = [];
        if (!empty($emailsListString)) {
            if (strpos($emailsListString, ',') !== false) {
                $explodedMails = explode(',', $emailsListString);
                if (is_array($explodedMails)) {
                    foreach ($explodedMails as $email) {
                        $email = trim($email);
                        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $emailsList[] = $email;
                        }
                    }
                }
            } else {
                if (filter_var($emailsListString, FILTER_VALIDATE_EMAIL)) {
                    $emailsList[] = $emailsListString;
                }
            }
        }

        return $emailsList;
    }

    public function getAutoRespondSettings($formId) {

        $systemEmailOptionGroup = 'email';
        $contactFormGlobalOptionGroup = 'contact_form_default';

        /**
         * Auto Respond custom sender
         */
        if (get_option('email_autorespond_custom_sender', $formId)) {
            $emailFrom = get_option('email_autorespond_from', $formId);
            $emailFromName = get_option('email_autorespond_from_name', $formId);
        } else {
            /**
             * Sending options if we dont have a custom auto respond sender
             */
            if (get_option('email_custom_sender', $contactFormGlobalOptionGroup)) {
                // We will get the global contact form options
                $emailFrom = get_option('email_from', $contactFormGlobalOptionGroup);
                $emailFromName = get_option('email_from_name', $contactFormGlobalOptionGroup);
            } else {
                // We will get the system email options
                $emailFrom = get_option('email_from', $systemEmailOptionGroup);
                $emailFromName = get_option('email_from_name', $systemEmailOptionGroup);
            }
        }

        /**
         * Auto Respond to user
         */
        $emailContent = get_option('email_autorespond', $formId);
        $emailSubject = get_option('email_autorespond_subject', $formId);
        $emailReplyTo = get_option('email_autorespond_reply_to', $formId);
        $emailAppendFiles = get_option('email_autorespond_append_files', $formId);

        return [
            'emailContent'=>$emailContent,
            'emailSubject'=>$emailSubject,
            'emailReplyTo'=>$emailReplyTo,
            'emailAppendFiles'=>$emailAppendFiles,
            'emailFrom'=>$emailFrom,
            'emailFromName'=>$emailFromName
        ];
    }

    public function get_lists($params)
    {
        $params = parse_params($params);
        $table = MW_DB_TABLE_FORMS_LISTS;
        $params['table'] = $table;

        return $this->app->database_manager->get($params);
    }

    public function countries_list_from_json()
    {
        $countries_file = normalize_path(dirname(MW_PATH) . '/Utils/ThirdPartyLibs/country.json', false);
        $countries_file = json_decode(file_get_contents($countries_file), true);

        return $countries_file;
    }

    public function countries_list($full = false)
    {
        static $data = array();

        if (empty($data)) {
            $countries_file_userfiles = normalize_path(userfiles_path() . 'country.csv', false);
            $countries_file = normalize_path(dirname(MW_PATH) . '/Utils/ThirdPartyLibs/country.csv', false);

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
        $states = new \MicroweberPackages\Utils\CountryState();
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
           // $data = get_form_entires('limit=100000&list_id=' . $lid);
            $data = get_form_entires('limit=100000');

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
