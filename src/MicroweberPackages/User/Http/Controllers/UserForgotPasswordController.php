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
        $inputs = $request->only(['captcha','email']);
        if (is_admin()) {
            unset($rules['captcha']);
        }
        $user_id = false;

        if (!$user_id and isset($inputs['email']) and $inputs['email'] != '') {
            $email_user = User::where('email',$inputs['email'])->first();
            if($email_user){
                $user_id = $email_user->id;
            }

        }

         $rules['email'] = 'required|email';

        $request->validate($rules);

        if(!$user_id){
            return response()->json(['error'=>true,'message' => __('passwords.user')], 422);
        }

        $user = User::where('id',$user_id)->first();

//        $status = Password::sendResetLink(
//            $request->only('email')
//        );


        // from https://laracasts.com/discuss/channels/laravel/reset-password-token-in-email-link-does-not-match-in-database-table?page=1&replyId=732755
        $status = Password::sendResetLink(
            $request->only('email'),
            function ($user, $token) {
                (\DB::table('password_resets')
                    ->updateOrInsert(
                        ['email' => $user->email],
                        [
                            'token' => md5($token)
                        ]
                    ))
                    ? $user->sendPasswordResetNotification(md5($token))
                    : null;
            }

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
                return abort(response("Password reset link is expired", 401));
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
