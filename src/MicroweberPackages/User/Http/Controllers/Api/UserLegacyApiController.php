<?php

declare(strict_types=1);

namespace MicroweberPackages\User\Http\Controllers\Api;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use MicroweberPackages\User\Http\Requests\ForgotPasswordEmailSendTestRequest;
use MicroweberPackages\User\Http\Requests\RegisterEmailSendRequest;
use MicroweberPackages\User\Http\Requests\RegisterEmailSendTestRequest;
use MicroweberPackages\User\Http\Requests\SearchAuthorsRequest;
use MicroweberPackages\User\Http\Requests\UserMakeLoggedRequest;
use MicroweberPackages\User\Http\Requests\UserResetPasswordFromLinkRequest;
use MicroweberPackages\User\Http\Requests\UserSendForgotPasswordRequest;
use MicroweberPackages\User\Http\Requests\UserSocialLoginRequest;
use MicroweberPackages\User\Http\Requests\VerifyEmailLinkRequest;
use MicroweberPackages\User\Models\User;
use MicroweberPackages\User\Services\UserManager;
use Symfony\Component\HttpFoundation\Response;

/**
 * Legacy /api/* user endpoints previously registered via api_expose*.
 */
class UserLegacyApiController extends Controller
{
    private function userManager(): UserManager
    {
        /** @var UserManager $manager */
        $manager = app('user_manager');

        return $manager;
    }

    /**
     * POST/ANY api/user_social_login
     */
    public function socialLogin(UserSocialLoginRequest $request): mixed
    {
        return user_social_login($request->validated());
    }

    /**
     * ANY api/social_login_process
     */
    public function socialLoginProcess(Request $request): mixed
    {
        return social_login_process();
    }

    /**
     * ANY api/user_reset_password_from_link
     */
    public function resetPasswordFromLink(UserResetPasswordFromLinkRequest $request): mixed
    {
        return user_reset_password_from_link($request->validated());
    }

    /**
     * ANY api/user_make_logged (admin)
     */
    public function makeLogged(UserMakeLoggedRequest $request): mixed
    {
        return user_make_logged($request->validated());
    }

    /**
     * ANY api/is_logged
     */
    public function isLogged(): \Illuminate\Http\JsonResponse
    {
        if (!defined('MW_API_CALL')) {
            define('MW_API_CALL', true);
        }

        return response()->json((bool) is_logged());
    }

    /**
     * ANY api/user_send_forgot_password
     */
    public function sendForgotPassword(UserSendForgotPasswordRequest $request): mixed
    {
        return user_send_forgot_password($request->validated());
    }

    /**
     * ANY api/users/register_email_send_test (admin)
     */
    public function registerEmailSendTest(RegisterEmailSendTestRequest $request): Response|string
    {
        try {
            app('option_manager')->override('users', 'register_email_enabled', true);
            $send = $this->userManager()->register_email_send();
            if ($send) {
                $user = Auth::user();

                return 'Email is send successfully to <b>' . e($user?->email) . '</b>.';
            }
        } catch (Exception $e) {
            return response('Error Message: <br />' . e($e->getMessage()), 500);
        }

        return response('Failed to send email', 500);
    }

    /**
     * ANY api/users/register_email_send
     */
    public function registerEmailSend(RegisterEmailSendRequest $request): mixed
    {
        $uid = null;
        $params = $request->validated();
        if (isset($params['user_id']) && is_admin()) {
            $uid = (int) $params['user_id'];
        }

        return $this->userManager()->register_email_send($uid);
    }

    /**
     * ANY api/users/forgot_password_email_send_test (admin)
     */
    public function forgotPasswordEmailSendTest(ForgotPasswordEmailSendTestRequest $request): Response|string
    {
        try {
            $user = Auth::user();
            app('option_manager')->override('users', 'forgot_pass_email_enabled', true);
            $send = $this->userManager()->send_forgot_password([
                'email' => $user?->email,
            ]);
            if ($send) {
                return 'Email is send successfully to <b>' . e($user?->email) . '</b>.';
            }
        } catch (Exception $e) {
            return response('Error Message: <br />' . e($e->getMessage()), 500);
        }

        return response('Failed to send email', 500);
    }

    /**
     * ANY api/users/search_authors (admin)
     *
     * @return array<int, array<string, mixed>>
     */
    public function searchAuthors(SearchAuthorsRequest $request): array
    {
        $params = $request->validated();
        $return = [];

        $kw = $params['kw'] ?? false;
        $limit = isset($params['limit']) ? (int) $params['limit'] : 100;

        $allUsersSearch = [
            'limit' => $limit,
            'fields' => 'id,username,first_name,last_name,email,is_admin',
        ];

        if ($kw) {
            $allUsersSearch['keyword'] = $kw;
            $allUsersSearch['search_in_fields'] = 'id,username,first_name,last_name,email';
        }

        $allUsers = get_users($allUsersSearch);
        if ($allUsers) {
            foreach ($allUsers as $user) {
                if (isset($user['id'])) {
                    $user['display_name'] = user_name($user['id']);
                    $user['picture'] = user_picture($user['id']);
                    $return[] = $user;
                }
            }
        }

        return $return;
    }

    /**
     * ANY api/users/verify_email_link
     */
    public function verifyEmailLink(VerifyEmailLinkRequest $request): RedirectResponse|Response|string
    {
        $params = $request->validated();

        if (!isset($params['key'])) {
            return response('Missing key', 400);
        }

        try {
            $decoded = app('format')->decrypt($params['key']);
            if ($decoded) {
                $decoded = (int) $decoded;
                /** @var User $adminUser */
                $adminUser = User::findOrFail($decoded);
                $adminUser->is_verified = 1;
                $adminUser->save();
                app('cache_manager')->delete('users');
                app('cache_manager')->delete('users/' . $decoded);
                $params['user_id'] = $decoded;
                app('event_manager')->trigger('mw.user.verify_email_link', $params);

                return app('url_manager')->redirect(site_url());
            }
        } catch (Exception $e) {
            return response('Exception: ' . e($e->getMessage()), 400);
        }

        return response('Invalid key', 400);
    }
}
