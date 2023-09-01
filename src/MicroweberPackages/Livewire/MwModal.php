<?php

namespace MicroweberPackages\Livewire;

use Illuminate\View\View;
use LivewireUI\Modal\Modal;

class MwModal extends Modal
{
    public function openModal($component, $componentAttributes = [], $modalAttributes = []): void
    {
        $requiredInterface = \LivewireUI\Modal\Contracts\ModalComponent::class;
        $componentClass = app('livewire')->getClass($component);
        $componentClass = app()->make($componentClass);

        $reflect = new \ReflectionClass($componentClass);

        if ($reflect->implementsInterface($requiredInterface) === false) {
            throw new \Exception("[{$componentClass}] does not implement [{$requiredInterface}] interface.");
        }

        $id = md5($component.serialize($componentAttributes));

        $componentAttributes = collect($componentAttributes)
            ->merge($this->resolveComponentProps($componentAttributes, new $componentClass()))
            ->all();

        $modalSettings = [];

        if ($reflect->hasProperty('modalSettings')) {
            $modalSettings = $reflect->getProperty('modalSettings')->getValue($componentClass);
        }

        $this->components[$id] = [
            'name' => $component,
            'attributes' => $componentAttributes,
            'modalAttributes' => array_merge([
                'closeOnClickAway' => $componentClass::closeModalOnClickAway(),
                'closeOnEscape' => $componentClass::closeModalOnEscape(),
                'closeOnEscapeIsForceful' => $componentClass::closeModalOnEscapeIsForceful(),
                'dispatchCloseEvent' => $componentClass::dispatchCloseEvent(),
                'destroyOnClose' => $componentClass::destroyOnClose(),
                'maxWidth' => $componentClass::modalMaxWidth(),
                'maxWidthClass' => $componentClass::modalMaxWidthClass(),
            ], $modalAttributes),
        ];

        $this->activeComponent = $id;

        $this->emit('activeModalComponentChanged', [
            'id' => $id,
            'modalSettings' => $modalSettings,
        ]);
    }

    public function render(): View
    {
        return view('livewire::mw-modal');
    }
}
