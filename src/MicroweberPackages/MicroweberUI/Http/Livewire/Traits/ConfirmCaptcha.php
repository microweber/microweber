<?php

namespace MicroweberPackages\MicroweberUI\Http\Livewire\Traits;

trait ConfirmCaptcha
{
    /**
     * Indicates if the captcha is being confirmed.
     *
     * @var bool
     */
    public $confirmingCaptcha = false;

    /**
     * The ID of the operation being confirmed.
     *
     * @var string|null
     */
    public $confirmableId = null;

    /**
     * The captcha.
     *
     * @var string
     */
    public $confirmableCaptcha = '';

    public $captchaIsConfirmed = false;

    /**
     * Start confirming the captcha.
     *
     * @param  string  $confirmableId
     * @return void
     */
    public function startConfirmingPCaptcha(string $confirmableId)
    {
        $this->resetErrorBag();

        if ($this->captchaIsConfirmed()) {
            return $this->dispatchBrowserEvent('captcha-confirmed', [
                'id' => $confirmableId,
            ]);
        }

        $this->captchaIsConfirmed = false;
        $this->confirmingCaptcha = true;
        $this->confirmableId = $confirmableId;
        $this->confirmableCaptcha = '';

        $this->dispatchBrowserEvent('confirming-captcha');
    }

    /**
     * Stop confirming the user's password.
     *
     * @return void
     */
    public function stopConfirmingCaptcha()
    {
        $this->confirmingCaptcha = false;
        $this->confirmableId = null;
        $this->confirmableCaptcha = '';
        $this->captchaIsConfirmed = false;
    }

    /**
     * Confirm the user's password.
     *
     * @return void
     */
    public function confirmCaptcha()
    {
        if (1 !== 2) {
            throw ValidationException::withMessages([
                'confirmable_captcha' => [__('This captcha answer is invalid.')],
            ]);
        }

        $this->captchaIsConfirmed = true;

        $this->dispatchBrowserEvent('captcha-confirmed', [
            'id' => $this->confirmableId,
        ]);

        $this->stopConfirmingCaptcha();
    }

    /**
     * Determine if the user's password has been recently confirmed.
     *
     * @param  null
     * @return bool
     */
    protected function captchaIsConfirmed()
    {
       return $this->captchaIsConfirmed;
    }
}
