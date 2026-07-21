<?php

namespace MicroweberPackages\User\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use MicroweberPackages\User\Models\User;
use Auth;


class UserForgotPasswordController extends Controller
{
    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct()
    {
        //event_trigger('mw.init');
    }

    public function showForgotForm()
    {
        return app()->parser->process(view('user::auth.forgot-password'));
    }

    public function send(Request $request)
    {
        $rules = [];
        if (!option_is_yes('captcha_disabled', 'users')) {
            $rules['captcha'] = 'captcha';
        }
        $inputs = $request->only(['captcha', 'email', 'username','format']);
        if (is_admin()) {
            unset($rules['captcha']);
        }


        if (!isset($inputs['email']) and isset($inputs['username'])) {

            $rules['username'] = 'required:min:1|max:255';

        } else {
            $rules['email'] = 'required|:min:1|max:255';

        }

        $user_id = false;

        if (!$user_id and !isset($inputs['email']) and isset($inputs['username'])) {
            $email_user = User::where('username', $inputs['username'])->first();
            if ($email_user) {
                $user_id = $email_user->id;
            }
        }

        if (!$user_id and isset($inputs['email']) and $inputs['email'] != '') {
            $email_user = User::where('email', $inputs['email'])->first();
            if ($email_user) {
                $user_id = $email_user->id;
            }
        }
        if (!$user_id and isset($inputs['email']) and $inputs['email'] != '') {
            $email_user = User::where('username', $inputs['email'])->first();
            if ($email_user) {
                $user_id = $email_user->id;
            }
        }

        if (!$user_id and isset($inputs['username']) and $inputs['username'] != '') {
            $email_user = User::where('username', $inputs['username'])->first();
            if ($email_user) {
                $user_id = $email_user->id;
            }
        }

        $validation = $request->validate($rules);

        if (!$user_id) {
            return response()->json(['error' => true, 'message' => __('passwords.user')], 422);
        }


//        $status = Password::sendResetLink(
//            $request->only('email')
//        );


        // from https://laracasts.com/discuss/channels/laravel/reset-password-token-in-email-link-does-not-match-in-database-table?page=1&replyId=732755
        $status = Password::sendResetLink(
            $request->only('email'),
            function ($user, $token) {
                $hashedToken = hash('sha256', $token);
                (DB::table('password_resets')
                    ->updateOrInsert(
                        ['email' => $user->email],
                        [
                            'token' => $hashedToken
                        ]
                    ))
                    ? $user->sendPasswordResetNotification($hashedToken)
                    : null;
            }

        );
        $returnJson = false;
        if (isset($inputs['format']) and $inputs['format'] == 'json') {
            $returnJson = true;
        }
        if ($request->expectsJson()) {
            $returnJson = true;
        }

        if ($returnJson) {
            if ($status === Password::RESET_LINK_SENT) {
                return response()->json(['success' => true, 'message' => __($status)], 200);
            } else if ($status == 'passwords.throttled') {
                return response()->json(['error' => true, 'message' => __('passwords.throttled')], 422);
            } else {
                return response()->json(['success' => true, 'message' => __($status)], 422);
            }
        }

        if ($status == 'passwords.throttled') {
            return back()->withErrors(['email' => __($status)]);
        }

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetForm(Request $request)
    {
        $check = DB::table('password_resets')
            ->where('email', '=', $request->email)
            ->first();
        if (!$check) {
            // task-2026-05-17-06892a / AI-794b — CHANGE 2 absorbed.
            // Pre-CHANGE this returned `abort(response("Password reset
            // link is expired", 401))` which rendered bare text on a
            // blank page (no auth chrome). Now routes through the
            // user::auth.reset-password-expired view which extends
            // user::layout — same AI-794 chrome as the form surfaces
            // + a "Request a new reset link" CTA so the user can
            // recover without copying the URL by hand.
            return $this->expiredResetLinkResponse();
        }

        $abort = false;

        $createdAt = Carbon::parse($check->created_at);
        $now = Carbon::now();

        $diffInHours = $createdAt->diffInHours($now);


        if (!$check) {
            $abort = true;
        }

        if ($check) {
            if ($diffInHours > 1) {
                $abort = true;
            }
        }

        if ($abort) {
            DB::table('password_resets')
                ->where('email', '=', $request->email)
                ->delete();

            // task-2026-05-17-06892a / AI-794b — see comment above.
            return $this->expiredResetLinkResponse();
        }

        return view('user::auth.reset-password', [
            'email' => $request->email,
            'token' => $request->token,
        ]);
    }

    /**
     * task-2026-05-17-06892a / AI-794b — CHANGE 2 absorbed.
     * Render the chrome-wrapped expired-reset-link response.
     *
     * Pre-AI-794b the controller called
     * `abort(response("Password reset link is expired", 401))` which
     * emitted bare text on a blank page — losing the AI-794 auth
     * chrome at the exact moment the user is actively trying to
     * recover access. Same propagation-without-renderer-update
     * family as AI-735→AI-793 admin-404 (the form-rendering path
     * got the AI-794 layout wrap; the controller's error path
     * bypassed user::layout entirely).
     *
     * Returns 401 with the same status code + an HTML body that
     * extends user::layout (active-template master + .mw-auth-card
     * chrome + brand logo + footer + AI-794a brand-blue CTA).
     *
     * @return \Illuminate\Http\Response
     */
    protected function expiredResetLinkResponse()
    {
        return response()->view('user::auth.reset-password-expired', [], 401);
    }

    public function update(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:1|confirmed|max:500',
        ]);

        $tokenMd5 = \MicroweberPackages\User\Models\PasswordReset::where('email', $request->get('email'))
            //->where(\DB::raw('md5(token)'), $request->get('token'))
            ->where('token', $request->get('token'))
            ->first();


        if (!empty($tokenMd5)) {

            $createdAt = Carbon::parse($tokenMd5->created_at);
            $diffInHours = $createdAt->diffInHours(Carbon::now());
            if ($diffInHours > 1) {
                DB::table('password_resets')
                    ->where('email', '=', $request->get('email'))
                    ->delete();
                // task-2026-05-17-06892a / AI-794b — CHANGE 2 absorbed.
                // Pre-CHANGE this 3rd error path also emitted bare-text
                // 401. Routed through the same chrome-wrapped expired-
                // reset-link view as showResetForm() so the user gets
                // identical recovery UX whether the link is expired at
                // page-load OR at form-submit time.
                return $this->expiredResetLinkResponse();
            }

            $user = User::where('email', $request->get('email'))->first();
            if ($user != null) {

                tap($user->forceFill([
                    'password' => Hash::make($request->get('password')),
                ]))->save();

                //Auth::logoutOtherDevices($request->get('password'));

                event(new PasswordReset($request->get('email')));

                Auth::loginUsingId($user->id);
                $user->setRememberToken(Str::random(60));

                \MicroweberPackages\User\Models\PasswordReset::where('email', $tokenMd5->email)->where('token', $tokenMd5->token)->delete();

                Session::flash('status', __('Password has been reset'));

                if ($request->expectsJson()) {
                    return response()->json(['message' => __('Password has been reset')], 200);
                }

                if ($user->is_admin) {
                    return redirect(admin_url());
                } else {
                    return redirect(site_url());
                }
            }

        } else {

            Session::flash('status', __('Expired or token is invalid'));

            if ($request->expectsJson()) {
                return response()->json(['message' => __('Expired or token is invalid')], 422);
            }
        }

        return back();
    }
}
