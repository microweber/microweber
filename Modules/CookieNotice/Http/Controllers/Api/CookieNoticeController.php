<?php

namespace Modules\CookieNotice\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Modules\CookieNotice\Services\CookieNoticeManager;

class CookieNoticeController extends Controller
{


    public function setCookie(Request $request): JsonResponse
    {
        if (isset($request->id)) {
            $cookieName = config('modules.cookie_notice.cookie_name') ?? 'cookie_notice_accepted';

            $response = [];
            $response['success'] = true;
            return response()->json($response)->withCookie(Cookie::forever($cookieName, '1'));
        }

    }


}
