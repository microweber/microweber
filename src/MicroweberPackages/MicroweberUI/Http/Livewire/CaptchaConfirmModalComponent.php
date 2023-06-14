<?php
namespace MicroweberPackages\MicroweberUI\Http\Livewire;

use LivewireUI\Modal\ModalComponent;
use MicroweberPackages\MicroweberUI\Http\Livewire;

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
        return view('microweber-ui::livewire.modals.captcha-confirm');
    }

    public function confirm()
    {
        if ($this->action) {
            $this->emit($this->action, $this->captcha);
        }
    }
}
