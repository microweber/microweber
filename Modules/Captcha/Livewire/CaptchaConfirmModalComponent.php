<?php

namespace Modules\Captcha\Livewire;

use Livewire\Attributes\On;
use LivewireUI\Modal\ModalComponent;

class CaptchaConfirmModalComponent extends ModalComponent
{
    public $captcha = '';
    public $action = '';
    public $data = [];

//    public $listeners = [
//        //  'closeCaptchaConfirmModal' => 'closeModal',
//    ];

    #[On('closeCaptchaConfirmModal')]
    public function closeCaptchaConfirmModal()
    {

        $this->forceClose();
        return $this->closeModal();
    }

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
