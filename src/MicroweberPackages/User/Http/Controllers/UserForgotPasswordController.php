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
        event_trigger('mw.init');
    }
    public function showForgotForm()
    {
        return app()->parser->process(view('user::auth.forgot-password'));
    }

    public function send(Request $request)
    {
        $rules = [];
        if (get_option('captcha_disabled', 'users') !== 'y') {
            $rules['captcha'] = 'captcha';
        }

        if (is_admin()) {
            unset($rules['captcha']);
        }


        if (!isset($request['email']) and isset($request['username'])) {
            $user_id = detect_user_id_from_params($request);
            if($user_id){
                $email_user = User::where('id',$user_id)->first();
                if($email_user){
                    $request->merge(['email' => $email_user->email]);
                }
            }
        }

         $rules['email'] = 'required|email';

        $request->validate($rules);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($request->expectsJson()) {
            if ($status === Password::RESET_LINK_SENT) {
                return response()->json(['message' => __($status)], 200);
            } else {
                return response()->json(['message' => __($status)], 422);
            }
        }

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetForm(Request $request)
    {
        $expiredText = "Password reset link is expired";

        $check = DB::table('password_resets')
            ->where('email', '=', $request->email)
            ->first();
        if (!$check) {
            return abort(response($expiredText, 401));
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

        if($abort){
               DB::table('password_resets')
                ->where('email', '=', $request->email)
                ->delete();

            return abort(response($expiredText, 401));
        }

        return view('user::auth.reset-password', [
            'email' => $request->email,
            'token' => $request->token,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:1|confirmed',
        ]);

        $tokenMd5 = \MicroweberPackages\User\Models\PasswordReset::where('email', $request->get('email'))->where(\DB::raw('md5(token)'), $request->get('token'))->first();
        if (!empty($tokenMd5)) {

            $user = User::where('email', $request->get('email'))->first();
            if ($user != null) {

                tap($user->forceFill([
                    'password' => Hash::make($request->get('password')),
                ]))->save();


                app()->auth->logoutOtherDevices($request->get('password'));
                event(new PasswordReset($request->get('email')));


                Auth::loginUsingId($user->id);
                $user->setRememberToken(Str::random(60));

                \MicroweberPackages\User\Models\PasswordReset::where('email', $tokenMd5->email)->where('token', $tokenMd5->token)->delete();

                Session::flash('status', __('Password reset success.'));

                if ($request->expectsJson()) {
                    return response()->json(['message' => __('Password reset success.')], 200);
                }
            }

        } else {

            Session::flash('status', __('Invalid token.'));

            if ($request->expectsJson()) {
                return response()->json(['message' => __('Invalid token.')], 422);
            }
        }

        return back();
    }
}
