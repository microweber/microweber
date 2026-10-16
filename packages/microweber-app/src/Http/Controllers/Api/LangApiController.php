<?php

declare(strict_types=1);

namespace MicroweberPackages\App\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use MicroweberPackages\App\Http\Requests\SaveLanguageFileContentRequest;
use MicroweberPackages\App\Http\Requests\SendLangFormToMicroweberRequest;

/**
 * Language admin endpoints formerly registered via api_expose_admin in lang.php.
 */
class LangApiController extends Controller
{
    /**
     * ANY api/send_lang_form_to_microweber
     */
    public function sendLangFormToMicroweber(SendLangFormToMicroweberRequest $request): mixed
    {
        $data = $request->validated();
        $lang = current_lang();
        $send = [
            'function_name' => 'send_lang_form_to_microweber',
            'language' => $lang,
            'data' => $data,
        ];

        return mw_send_anonymous_server_data($send);
    }

    /**
     * ANY api/save_language_file_content
     */
    public function saveLanguageFileContent(SaveLanguageFileContentRequest $request): mixed
    {
        return app('lang_helper')->save_language_file_content($request->all());
    }
}
