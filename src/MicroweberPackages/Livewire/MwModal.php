<?php

namespace MicroweberPackages\Livewire;

use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Mechanisms\ComponentRegistry;
use LivewireUI\Modal\Modal;

class MwModal extends Modal
{
    public function openModal($component, $componentAttributes = [], $modalAttributes = []): void
    {
        $requiredInterface = \LivewireUI\Modal\Contracts\ModalComponent::class;
        //  $componentClass = app('livewire')->getClass($component);
        $componentClass = $component;

        if (!livewire_component_exists($componentClass)) {
            throw new \Exception("[{$componentClass}] does not exist as a Livewire component.");
        }

        $id = md5($component . serialize($componentAttributes));

        //   $componentClass = \Livewire\Livewire::new($component,$id);
        $componentClass = app(ComponentRegistry::class)->getClass($component);


        $reflect = new \ReflectionClass($componentClass);

        if ($reflect->implementsInterface($requiredInterface) === false) {
            throw new \Exception("[{$componentClass}] does not implement [{$requiredInterface}] interface.");
        }


        $componentAttributes = collect($componentAttributes)
            ->merge($this->resolveComponentProps($componentAttributes, new $componentClass()))
            ->all();

        $modalSettings = [];

        if ($reflect->hasProperty('modalSettings')) {
            $modalSettings = $reflect->getProperty('modalSettings')->getValue($componentClass);
        }

        $this->components[$id] = [
            'name' => $component,
            //    'id' => $id,
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
        $data = [
            'id' => $id,
            'modalSettings' => $modalSettings
        ];

        $this->dispatch('activeModalComponentChanged', data: $data);
        // $this->render();
    }

    #[On('closeModal')]
    public function closeModal()
    {
        $this->resetState();
    }

    public function render(): View
    {
        return view('livewire::mw-modal');
    }
}
