<?php

namespace Modules\Billing\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Modules\Billing\Notifications\UserDemoExpiredNotification;
use MicroweberPackages\Notification\Models\NotificationMailLog;
use MicroweberPackages\User\Models\User;

class UserDemoActivate
{
    public static function activate($userId)
    {
        $user = User::where('id', $userId)->first();

        if (!empty($user->demo_started_at)) {
            return [
                'error' => 'You already have a free trial',
            ];
        }

        $user->demo_started_at = \Illuminate\Support\Carbon::now();
        $user->demo_expired = 0;
        $user->demo_expired_at = Carbon::now()->addDays(15);
        $user->save();

        return [
            'success' => 'Free trial activated',
        ];
    }

    public static function deactivate($userId)
    {
        $user = User::where('id', $userId)->first();
        $user->demo_expired = 1;
        $user->demo_expired_at = Carbon::now();
        $user->save();

        $checkMailLog = NotificationMailLog::where('type', 'UserDemoExpired')
            ->where('notifiable_type', 'user')
            ->where('notifiable_id', $user->id)
            ->first();

        if ($checkMailLog == null) {

            // Send deactivation email
            $mailLog = new NotificationMailLog();
            $mailLog->notifiable_type = 'user';
            $mailLog->notifiable_id = $user->id;
            $mailLog->type = 'UserDemoExpired';
            $mailLog->save();

            Notification::sendNow($user, new UserDemoExpiredNotification());
        }

        return [
            'success' => 'Free trial deactivated',
        ];
    }

    public static function get($userId)
    {
        $user = User::where('id', $userId)->first();
        if(!$user){
            return [
                'active' => false,
                'expired' => true,
                'user_not_found' => 'User not found.',
            ];
        }

        $activeSubscription = getUserActiveSubscriptionPlanBySKU($user->id, 'hosting');
        if ($activeSubscription) {
            return [
                'active' => true,
                'expired' => false,
            ];
        }

        $active = false;
        $expired = false;

        if ($user->demo_expired == 1) {
            $active = false;
            $expired = true;
        } else {
            if (!empty($user->demo_expired_at)) {
                $isValidDemoExpiredAtDate = true;
                if ($user->demo_expired_at == '0000-00-00 00:00:00') {
                    $isValidDemoExpiredAtDate = false;
                }
                if ($isValidDemoExpiredAtDate && Carbon::parse($user->demo_expired_at)->isPast()) {
                    self::deactivate($userId);
                    return self::get($userId);
                }
            }

            if ($user->demo_started_at > 0) {
                $expired = false;
                $active = true;
            }
        }

        return [
            'startedAt' => $user->demo_started_at,
            'startedAtFormated' => Carbon::create($user->demo_started_at)
                ->rawFormat('d F, Y'),

            'expiredAtFormated' => Carbon::create($user->demo_expired_at)
                ->rawFormat('d F, Y'),
            'expiredAtText' => Carbon::create($user->demo_expired_at)->diffForHumans(),

            'expiredAt' => $user->demo_expired_at,

            'active' => $active,
            'expired' => $expired,
        ];
    }
}
