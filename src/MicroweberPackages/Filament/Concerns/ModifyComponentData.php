<?php

namespace MicroweberPackages\Filament\Concerns;

use Livewire\Attributes\On;

trait ModifyComponentData
{
    #[On('modifyComponentData')]
    public function modifyComponentData($data): void
    {
        if ($data) {
            foreach ($data as $key => $value) {
                $this->data[$key] = $value;
            }
        }

    }
}
