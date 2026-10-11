<?php

namespace MicroweberPackages\User\Notifications;

use \Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

trait MustVerifyEmailTrait
{
    /**
     * Determine if the user has verified their email address.
     *
     * @return bool
     */
    public function hasVerifiedEmail()
    {

        return intval($this->is_verified) != 0;
    }

    /**
     * Mark the given user's email as verified.
     *
     * @return bool
     */
    public function markEmailAsVerified()
    {
        $isActive = 1;

        return $this->forceFill([
            'is_active' => $isActive,
            'is_verified' => 1,
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        try {
            $this->notifyNow(new VerifyEmail());
        } catch (\Exception $e) {
            Log::error($e);
        }
    }

    /**
     * Get the email address that should be used for verification.
     *
     * @return string
     */
    public function getEmailForVerification()
    {
        return $this->email;
    }
}
