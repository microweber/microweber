<?php
namespace Modules\Captcha\Livewire;

use LivewireUI\Modal\ModalComponent;

class CaptchaConfirmModalComponent extends ModalComponent
{
    public $captcha = '';
    public $action = '';
    public $data = [];

    public $listeners = [
        'closeCaptchaConfirmModal' => 'closeModal',
    ];

    public function render()
    {
        return view('modules.captcha::livewire.modals.captcha-confirm');
    }

    public function confirm()
    {

        if ($this->action) {
            $this->dispatch($this->action, $this->captcha);
        }
    }
}
