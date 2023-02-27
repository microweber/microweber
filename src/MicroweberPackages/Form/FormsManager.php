<?php

namespace MicroweberPackages\Form;

use Arcanedev\Html\Elements\Form;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use MicroweberPackages\Export\Formats\XlsxExport;
use MicroweberPackages\Form\Models\FormData;
use MicroweberPackages\Form\Models\FormDataValue;
use MicroweberPackages\Form\Models\FormRecipient;
use MicroweberPackages\Form\Notifications\NewFormEntry;
use MicroweberPackages\Form\Notifications\NewFormEntryAutoRespond;
use MicroweberPackages\Form\Notifications\NewFormEntryToMail;
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
        $findFormsDataValues = FormDataValue::where('form_data_id', $data)->get();

        $ret = array();
        if (is_array($data)) {
            foreach ($data as $item) {

                $fields = @json_decode($item['form_values'], true);
                if (!$fields) {
                    $fields = @json_decode(html_entity_decode($item['form_values']), true);
                }
                if (empty($item['form_values'])) {
                    $fields = [];
                    if ($findFormsDataValues->count()>0) {
                        foreach ($findFormsDataValues as $formsDataValue) {
                            if (is_array($formsDataValue->field_value_json) && !empty($formsDataValue->field_value_json)) {
                                $fields[$formsDataValue->field_key] = $formsDataValue->field_value_json;
                            } else {
                                $fields[$formsDataValue->field_key] = $formsDataValue->field_value;
                            }
                        }
                    }
                }

                if (is_array($fields)) {
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
        $params['id'] = $id;
        if (isset($params['for_module_id'])) {
            $data = array();
            $data['module'] = $params['module_name'];
            $data['option_group'] = $params['for_module_id'];
            $data['option_key'] = 'list_id';
            $data['option_value'] = $id;
            $this->app->option_manager->save($data);

        }

        return array('success' => 'List is updated', 'data'=>$params);
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

            if(!isset($params['captcha'])){
                $validate_captcha = false;
            } else {
                $validate_captcha = $this->app->captcha_manager->validate($params['captcha'], $for_id);
            }

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


        if (isset($params['_token'])) {
            unset($params['_token']);
        }
        if (isset($params['token'])) {
            unset($params['token']);
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
        $fieldsData = array();

        $get_fields = array();
        $get_fields['rel_type'] = $for;
        $get_fields['rel_id'] = $for_id;
        $get_fields['return_full'] = true;

        $more = $this->app->fields_manager->get($get_fields);

        $fieldsValidation = [];

        $cfToSave = array();
        if (!empty($more)) {
            foreach ($more as $item) {

                $fieldsValidation[$item['name_key']][] = 'max:500';

                $appendToRequired = false;
                if ($item['required'] == 1) {
                    $appendToRequired = true;
                }
                if (isset($item['type']) && $item['type'] == 'upload') {
                    $appendToRequired = false;
                }

                if ($appendToRequired) {
                    $fieldsValidation[$item['name_key']][] = 'required';
                }

                if (isset($item['name'])) {

                    $customFieldName = $item['name']; // custom field name
                    $customFieldNameKey = $item['name_key']; // custom field name key
                    $customFieldType = 'text'; // custom field type
                    if (isset($item['options']['field_type'])) {
                        $customFieldType = $item['options']['field_type'];
                    } else if(isset($item['type'])){
                        $customFieldType = $item['type'];
                    }

                    foreach ($params as $paramKey => $paramValues) {
                        if ($paramKey == $customFieldNameKey) {

                            $item['value'] = $params[$paramKey];
                            $cfToSave[$customFieldNameKey] = $paramValues;

                            //$paramValues
                            $customFieldValue = '';
                            $customFieldValueJson = [];
                            if (is_array($paramValues) && !empty($paramValues)) {
                                $customFieldValueJson = $paramValues;
                            } else {
                                $customFieldValue = $paramValues;
                            }

                            $fieldsData[] = [
                                'field_type' => $customFieldType,
                                'field_name' => $customFieldName,
                                'field_key' => $customFieldNameKey,
                                'field_value' => $customFieldValue,
                                'field_value_json' => $customFieldValueJson
                            ];
                        }
                    }

                }
            }
        } else {
            // Custom fields are not found in db
            $cfToSave = $params;
            $formsDataClean = $params;
            unset($formsDataClean['for']);
            unset($formsDataClean['for_id']);
            unset($formsDataClean['module_name']);
            if (!empty($formsDataClean)) {
                foreach ($formsDataClean as $formDataName=>$formDataValue) {

                    $formDataKey = str_slug($formDataName);
                    $formDataKey = str_replace('-','_', $formDataKey);

                    if (is_array($formDataValue) && !empty($formDataValue)) {
                        $fieldsData[] = [
                            'field_type' => 'options',
                            'field_name' => $formDataName,
                            'field_key' => $formDataKey,
                            'field_value' => '',
                            'field_value_json' => $formDataValue
                        ];
                    } else {
                        $fieldsData[] = [
                            'field_type' => 'text',
                            'field_name' => $formDataName,
                            'field_key' => $formDataKey,
                            'field_value' => $formDataValue,
                            'field_value_json' => []
                        ];
                    }
                }
            }
        }

        $validationErrorsReturn = [];
        if (!empty($fieldsValidation)) {

            $validator = Validator::make($params, $fieldsValidation);
            if ($validator->fails()) {
                $validatorMessages = false;
                foreach ($validator->messages()->toArray() as $inputFieldErros) {
                   // $validatorMessages = reset($inputFieldErros);
                    $validatorMessages = implode("\n",$inputFieldErros);
                    //$validatorMessages = app()->format->array_to_ul($inputFieldErros);
                }
                $validationErrorsReturn = array(
                    'form_errors' => $validator->messages()->toArray(),
                    'error' => $validatorMessages
                );
            }
        }

        $save = 1;

        $skip_saving_emails = $this->app->option_manager->get('skip_saving_emails', $for_id);
        if (!$skip_saving_emails) {
            $skip_saving_emails = $this->app->option_manager->get('skip_saving_emails', $default_mod_id);
        }

        $to_save['list_id'] = $list_id;
        $to_save['rel_id'] = $for_id;
        $to_save['rel_type'] = $for;

        $to_save['user_ip'] = user_ip();

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

                if ((isset($field['required']) and $field['required']) or (isset($field['options']['required']) && $field['options']['required'] == 1)) {
                    $fieldRules[] = 'required';
                  //  $_FILES[$field['name_key']] = true;
                    $allowedFilesForSave[$field['name_key']] = true;

                } else if (!isset($_FILES[$field['name_key']])) {
                    continue;
                }

                $allowedFilesForSave[$field['name_key']] = true;
             //  $allowedFilesForSave[$field['name_key']] = $_FILES[$field['name_key']];

                $mimeTypes = [];
                if (isset($field['options']['file_types']) && !empty($field['options']['file_types'])) {
                    foreach ($field['options']['file_types'] as $optionFileTypes) {
                        if (!empty($optionFileTypes)) {
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

                if (isset($allowedFilesForSave[$field['name_key']])) {

                    $uploadedField = $allowedFilesForSave[$field['name_key']];
                    if (isset($uploadedField['type']) && strpos($uploadedField['type'], 'image/')) {
                        if ($optionFileTypes == 'images') {
                            $fieldRules[] = 'valid_image';
                        }
                    }
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
                $validationErrorsReturn_upload = array(
                    'form_errors' => $validator->messages()->toArray(),
                    'error' => $validatorMessages
                );

                if($validationErrorsReturn){
                    $validationErrorsReturn = array_merge_recursive($validationErrorsReturn,$validationErrorsReturn_upload);
                } else {
                    $validationErrorsReturn = $validationErrorsReturn_upload;
                }

                return $validationErrorsReturn;
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
                foreach ($allowedFilesForSave as $fieldName => $file_up) {

                    if(!isset($_FILES[$fieldName])){
                        continue;
                    }

                    $file =  $_FILES[$fieldName];

                    if(!is_array($file)){
                        continue;
                    }
                    if(!isset($file['name'])){
                        continue;
                    }

                    $targetFileName = $target_path_name . '/' . $file['name'];

                    if (is_file($target_path . '/' . $file['name'])) {
                        $targetFileName = $target_path_name . '/' . date('Ymd-His') . $file['name'];
                    }

                    $fileContent = @file_get_contents($file['tmp_name']);
                    if ($fileContent) {
                        $save = Storage::disk('media')->put($targetFileName, $fileContent);
                        if ($save) {

                            $realPath = Storage::disk('media')->path($targetFileName);

                            $fileMime = \Illuminate\Support\Facades\File::mimeType($realPath);
                            $fileExtension = \Illuminate\Support\Facades\File::extension($realPath);
                            $fileSize = \Illuminate\Support\Facades\File::size($realPath);

                            $mediaFileUrl = Storage::disk('media')->url($targetFileName);
                            $mediaFileUrl = str_replace(site_url(), '{SITE_URL}', $mediaFileUrl);

                            $fieldsData[] = [
                                'field_type' => 'upload',
                                'field_key' => $file['name'],
                                'field_name' => $fieldName,
                                'field_value' => false,
                                'field_value_json'=> [
                                    'url' => $mediaFileUrl,
                                    'file_name' => $file['name'],
                                    'file_extension' => $fileExtension,
                                    'file_mime' => $fileMime,
                                    'file_size' => $fileSize,
                                ]
                            ];
                        }

                    } else {
                        return array(
                            'error' => _e('Invalid file.', true)
                        );
                    }
                }
            }
        } else  if($validationErrorsReturn)  {
            return $validationErrorsReturn;
        }

        // End of attachments
        if (empty($fieldsData)) {
            return ['errors' => 'Fields data is empty'];
        }

        $save = $this->app->database_manager->save($table, $to_save);
        $event_params = $params;
        $event_params['saved_form_entry_id'] = $save;

        foreach ($fieldsData as $dataValue) {
            $formDataValue = new FormDataValue();
            $formDataValue->field_type = $dataValue['field_type'];
            $formDataValue->field_name = $dataValue['field_name'];
            $formDataValue->field_key = $dataValue['field_key'];
            $formDataValue->field_value = $dataValue['field_value'];
            $formDataValue->field_value_json = $dataValue['field_value_json'];
            $formDataValue->form_data_id = $save;
            $formDataValue->save();
        }

        $formModel = FormData::with('formDataValues')->find($save);

        $this->app->event_manager->trigger('mw.forms_manager.after_post', $event_params);

        Notification::send(User::whereIsAdmin(1)->get(), new NewFormEntry($formModel));

        if ($skip_saving_emails == 'y') {
            // Delete form data when skip saving
            FormData::where('id', $formModel->id)->delete();
        }

        if (isset($params['module_name'])) {

            $pp_arr = $params;
            $pp_arr['ip'] = user_ip();

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

                    if (Option::getValue('email_custom_receivers', $for_id)) {
                        $sendFormDataToReceivers = Option::getValue('email_to', $for_id);
                    } else {
                        $sendFormDataToReceivers = Option::getValue('email_to', 'contact_form_default');
                    }

                    if (empty(!$sendFormDataToReceivers) and isset($formModel)) {
                        $receivers = $this->explodeMailsFromString($sendFormDataToReceivers);
                        if (!empty($receivers)) {
                            foreach ($receivers as $receiver) {
                                Notification::route('mail', $receiver)->notify(new NewFormEntryToMail($formModel));
                            }
                        }
                    }

                    if (Option::getValue('email_autorespond_enable', $for_id) && is_array($userEmails)) {
                        foreach ($userEmails as $userEmail) {

                            $findFormRecipient = FormRecipient::where('email', $userEmail)->first();
                            if ($findFormRecipient == null) {
                                $findFormRecipient = new FormRecipient();
                                $findFormRecipient->email = $userEmail;
                                $findFormRecipient->save();
                            }
                            if (isset($formModel)) {
                                $findFormRecipient->notifyNow((new NewFormEntryAutoRespond($formModel)));
                            }
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

    public function getAutoRespondSettings($formId)
    {

        $systemEmailOptionGroup = 'email';
        $contactFormGlobalOptionGroup = 'contact_form_default';

        /**
         * Auto Respond custom sender
         */
        if (Option::getValue('email_autorespond_custom_sender', $formId)) {
            $emailFrom = Option::getValue('email_autorespond_from', $formId);
            $emailFromName = Option::getValue('email_autorespond_from_name', $formId);
        } else {
            /**
             * Sending options if we dont have a custom auto respond sender
             */
            if (Option::getValue('email_custom_sender', $contactFormGlobalOptionGroup)) {
                // We will get the global contact form options
                $emailFrom = Option::getValue('email_from', $contactFormGlobalOptionGroup);
                $emailFromName = Option::getValue('email_from_name', $contactFormGlobalOptionGroup);
            } else {
                // We will get the system email options
                $emailFrom = Option::getValue('email_from', $systemEmailOptionGroup);
                $emailFromName = Option::getValue('email_from_name', $systemEmailOptionGroup);
            }
        }

        /**
         * Auto Respond to user
         */
        $emailContent = Option::getValue('email_autorespond', $formId);
        $emailSubject = Option::getValue('email_autorespond_subject', $formId);
        $emailReplyTo = Option::getValue('email_autorespond_reply_to', $formId);
        $emailAppendFiles = Option::getValue('email_autorespond_append_files', $formId);

        return [
            'emailContent' => $emailContent,
            'emailSubject' => $emailSubject,
            'emailReplyTo' => $emailReplyTo,
            'emailAppendFiles' => $emailAppendFiles,
            'emailFrom' => $emailFrom,
            'emailFromName' => $emailFromName
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
        set_time_limit(0);

        if (!isset($params['id'])) {
            return array('error' => 'Please specify list id! By posting field id=the list id ');
        } else {
            $listId = intval($params['id']);
            if ($listId == 0) {
                $data = get_form_entires('nolimit=true');
            } else {
                $data = get_form_entires('nolimit=true&list_id=' . $listId);
            }

            if (!$data) {
                return array('warning' => 'This list is empty');
            }

            // First get all keys
            $dataKeysMap = ['id','created_at','user_ip'];
            foreach ($data as $formItem) {
                if (isset($formItem['custom_fields'])) {
                    foreach ($formItem['custom_fields'] as $customFieldKey=>$customFieldData) {
                        $customFieldKey = $this->app->format->no_dashes($customFieldKey);
                        $customFieldKey = str_slug($customFieldKey);
                        $dataKeysMap[] = $customFieldKey;
                    }
                }
            }
            $dataKeysMap = array_filter($dataKeysMap);

            // Next add these values to keys
            $dataValues = [];
            foreach ($data as $formItem) {
                $readyDataValue = [];
                foreach ($dataKeysMap as $dataKey) {
                    $readyDataValue[$dataKey] = '';
                }
                $readyDataValue['id'] = $formItem['id'];
                $readyDataValue['created_at'] = $formItem['created_at'];
                $readyDataValue['user_ip'] = $formItem['user_ip'];
                if (isset($formItem['custom_fields'])) {
                    foreach ($formItem['custom_fields'] as $customFieldKey => $customFieldData) {

                        $customFieldKey = $this->app->format->no_dashes($customFieldKey);
                        $customFieldKey = str_slug($customFieldKey);

                        if (is_array($customFieldData)) {
                            $customFieldData = implode('|', $customFieldData);
                        }

                        $readyDataValue[$customFieldKey] = $customFieldData;
                    }
                }
                $dataValues[] = $readyDataValue;
            }
            $export = new XlsxExport();
            $export->data['mw_export_contact_form_' . date('Y-m-d-H-i-s')] = $dataValues;
            $export = $export->start();
            $exportFile = $export['files']['0']['download'];

            return array('success' => 'Your file has been exported!', 'download' => $exportFile);
        }
    }
}
