<?php
namespace MicroweberPackages\Admin\Http\Livewire;

class AdminConfirmModalComponent extends AdminComponent
{
    public $action = '';
    public $data = [];

    public $listeners = [
        'closeAdminConfirmModal' => 'closeModal',
    ];

    public function render()
    {
        return view('admin::livewire.livewire.modals.confirm');
    }

    public function confirm()
    {
        $this->closeModal();
        if ($this->action) {
            $this->emit($this->action, $this->data);
        }
    }
}
