<?php

api_expose('digital_download_get');
function digital_download_get($params) {


    $download_url = get_option('download_url', $params['id']);
    if (!$download_url) {
        $download_url = '';
    }

    $email = $params['email'];
    if ($email) {

        $optionGroup = 'digital_download';

        save_option(array(
            'option_group' => $optionGroup,
            'option_key' => 'disable_captcha',
            'option_value' => 'y'
        ));

        $isListFinded = false;
        $getLists = mw()->forms_manager->get_lists([]);
        if ($getLists) {
            foreach ($getLists as $list) {
                if ($list['module_name'] == 'digital_download_'.'_'.$params['id']) {
                    $isListFinded = true;
                    break;
                }
            }
        }
        if (!$isListFinded) {
            mw()->forms_manager->save_list([
                'for_module_id' => $optionGroup,
                'for_module'    => 'digital_download_'.'_'.$params['id'],
                'title'         => 'Digital Downloads',
            ]);
        }

        $save = mw()->forms_manager->post([
            'for_id' => $optionGroup,
            'for' => 'digital_download_'.'_'.$params['id'],
            'message' => 'Link is downloaded from ' . $email,
            'email' => $email,
            'module_name' => 'digital_download',
        ]);

        if ($save) {
            return [
                'success' => 'Thank you for downloading the file. Check your email for the download link.',
                'download_url' => $download_url
            ];
        }
    }

    return [
        'error' => 'Error downloading file'
    ];

}
