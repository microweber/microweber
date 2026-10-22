<?php

namespace MicroweberPackages\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * Handles the link from the email-change verification message. The token in the
 * link is the only authorisation needed, so it works whether or not the visitor
 * is currently logged in.
 */
class UserEmailChangeController extends Controller
{
    public function confirm(Request $request)
    {
        $result = app()->user_manager->confirmEmailChange(
            $request->input('id'),
            $request->input('token')
        );

        $isSuccess = isset($result['success']);
        $message = $result['success'] ?? ($result['error'] ?? '');

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json($result, $isSuccess ? 200 : 422);
        }

        return redirect(function_exists('site_url') ? site_url() : '/')
            ->with($isSuccess ? 'success' : 'error', $message);
    }
}
