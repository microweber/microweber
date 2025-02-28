<?php
namespace MicroweberPackages\Admin\Http\Livewire;

class AdminConfirmModalComponent extends AdminModalComponent
{
    public $action = '';
    public $title = '';
    public $body = '';
    public $button_text = 'Confirm';
    public $data = [];

    public $modalSettings = [
        'overlay' => true,
        'overlayClose' => true,
        'width' => '500px',
    ];

    public $listeners = [
        'closeAdminConfirmModal' => 'closeModal',
    ];

    public function render()
    {
        return view('admin::admin.livewire.modals.confirm');
    }

    public function confirm()
    {
        $this->closeModal();
        if ($this->action) {
            $this->emit($this->action, $this->data);
        }
    }
}
