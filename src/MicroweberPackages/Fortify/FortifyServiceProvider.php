<?php

namespace MicroweberPackages\Fortify;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\FailedPasswordConfirmationResponse as FailedPasswordConfirmationResponseContract;
use Laravel\Fortify\Contracts\FailedPasswordResetLinkRequestResponse as FailedPasswordResetLinkRequestResponseContract;
use Laravel\Fortify\Contracts\FailedPasswordResetResponse as FailedPasswordResetResponseContract;
use Laravel\Fortify\Contracts\FailedTwoFactorLoginResponse as FailedTwoFactorLoginResponseContract;
use Laravel\Fortify\Contracts\LockoutResponse as LockoutResponseContract;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;
use Laravel\Fortify\Contracts\PasswordConfirmedResponse as PasswordConfirmedResponseContract;
use Laravel\Fortify\Contracts\PasswordResetResponse as PasswordResetResponseContract;
use Laravel\Fortify\Contracts\PasswordUpdateResponse as PasswordUpdateResponseContract;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Laravel\Fortify\Contracts\ResetPasswordViewResponse;
use Laravel\Fortify\Contracts\SuccessfulPasswordResetLinkRequestResponse as SuccessfulPasswordResetLinkRequestResponseContract;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;
use Laravel\Fortify\Contracts\VerifyEmailResponse as VerifyEmailResponseContract;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Responses\FailedPasswordConfirmationResponse;
use Laravel\Fortify\Http\Responses\FailedPasswordResetLinkRequestResponse;
use Laravel\Fortify\Http\Responses\FailedPasswordResetResponse;
use Laravel\Fortify\Http\Responses\FailedTwoFactorLoginResponse;
use Laravel\Fortify\Http\Responses\LockoutResponse;
use Laravel\Fortify\Http\Responses\LoginResponse;
use Laravel\Fortify\Http\Responses\LogoutResponse;
use Laravel\Fortify\Http\Responses\PasswordConfirmedResponse;
use Laravel\Fortify\Http\Responses\PasswordResetResponse;
use Laravel\Fortify\Http\Responses\PasswordUpdateResponse;
use Laravel\Fortify\Http\Responses\RegisterResponse;
use Laravel\Fortify\Http\Responses\SimpleViewResponse;
use Laravel\Fortify\Http\Responses\SuccessfulPasswordResetLinkRequestResponse;
use Laravel\Fortify\Http\Responses\TwoFactorLoginResponse;
use Laravel\Fortify\Http\Responses\VerifyEmailResponse;
use MicroweberPackages\Core\Providers\Concerns\MergesConfig;
use MicroweberPackages\Fortify\Actions\Fortify\CreateNewUser;
use MicroweberPackages\Fortify\Actions\Fortify\ResetUserPassword;
use MicroweberPackages\Fortify\Actions\Fortify\UpdateUserPassword;
use MicroweberPackages\Fortify\Actions\Fortify\UpdateUserProfileInformation;
use MicroweberPackages\User\Http\Controllers\UserLoginController;
use MicroweberPackages\User\Models\User;

class FortifyServiceProvider extends \Laravel\Fortify\FortifyServiceProvider
{
use MergesConfig;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(){
        parent::register();
        $this->mergeConfigFrom(__DIR__ . '/config/fortify.php', 'fortify');

    }


    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->configurePublishing();
        $this->configureRoutes();

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations/');

        View::addNamespace('fortify', __DIR__.'/resources/views');

//        Fortify::createUsersUsing(CreateNewUser::class);
//        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
//        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
    //   Fortify::resetUserPasswordsUsing(ResetUserPassword::class);


        Fortify::twoFactorChallengeView(function () {
            return view('fortify::two-factor-challenge');
        });

        Fortify::resetPasswordView(function (Request $request) {
            return view('user::auth.reset-password', [
                'email' => $request->email,
                'token' => $request->token,
            ]);
         });


        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('username', $request->username)->first();
            if ($user && Hash::check($request->password, $user->password)) {
                return $user;
            }
        });

        /*
         * AI-129 / SEC-04 (cycle-122 2026-05-09): brief-faithful login
         * throttle. Pre-fix: `perMinute(60)` — 60 attempts per minute,
         * effectively no rate limit (a credential-stuffing attacker
         * tries one password every second). Post-fix: 5 attempts per
         * 15 minutes, keyed by `<email|username><ip>` so an attacker
         * can't simply rotate IPs to bypass the per-account lockout.
         *
         * The brief said "5 attempts → lockout"; using `decayMinutes`
         * of 15 gives a soft 15-minute decay (Laravel's
         * `RateLimiter::tooManyAttempts` returns true for the next
         * 15 minutes after the 5th failure). After 15 minutes the
         * counter resets — we don't permanently lock the account
         * (that would let an attacker DOS legitimate users by
         * burning through 5 attempts on every account).
         */
        RateLimiter::for('login', function (Request $request) {
            $key = (string) ($request->email ?: $request->username ?: '');
            return Limit::perMinutes(15, 5)->by($key . $request->ip());
        });

        /*
         * AI-129 / SEC-04: 2FA challenge throttle. Same shape — a 5x
         * /15min limit per session-bound `login.id` so a stolen
         * session cookie can't brute-force the TOTP.
         */
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinutes(15, 5)->by(
                (string) $request->session()->get('login.id', $request->ip())
            );
        });
    }

    protected function configureRoutes()
    {
        if (Fortify::$registersRoutes) {
            Route::group([
                'namespace' => 'Laravel\Fortify\Http\Controllers',
                'domain' => config('fortify.domain', null),
                'prefix' => config('fortify.prefix'),
            ], function () {
                $this->loadRoutesFrom(__DIR__.'/routes/fortify.php');
            });
        }
    }

}
