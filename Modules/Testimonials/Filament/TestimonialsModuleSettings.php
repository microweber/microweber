<?php

namespace Modules\Testimonials\Filament;

use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class TestimonialsModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'testimonials';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Testimonials')
                    ->tabs([
                        Tabs\Tab::make('Main settings')
                            ->schema([
                                Livewire::make(TestimonialsTableList::class, [
                                    'rel_id' => $this->params['id'] ?? null,
                                    'rel_type' => 'module',
                                ])
                            ]),
                        Tabs\Tab::make('Design')
                            ->schema($this->getTemplatesFormSchema()),
                    ]),
            ]);
    }
}
