<?php

namespace Modules\Testimonials\Filament;

use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettingsTable;
use Modules\Testimonials\Models\Testimonial;

class TestimonialsModuleSettings extends LiveEditModuleSettingsTable
{
    public string $module = 'testimonials';

    public string $modelName = Testimonial::class;
    public string $tableComponentName = TestimonialsTableList::class;

    public function form(Form $form): Form
    {
        $moduleId = $this->params['id'] ?? null;
        $relId = $this->getOption('rel_id') ?? $this->params['rel_id'] ?? $this->params['id'] ?? null;
        $relType = $this->getOption('rel_type') ?? $this->params['rel_type'] ?? 'module';

        return $form
            ->schema([
                Tabs::make('Testimonials')
                    ->tabs([
                        Tabs\Tab::make('Main settings')
                            ->schema([
                                    Livewire::make($this->tableComponentName, [
                                        'rel_id' => $relId,
                                        'rel_type' => $relType,
                                        'module_id' => $moduleId,
                                    ])
                                    ->reactive()
                                    ->live(),
                            ]),
                        Tabs\Tab::make('Design')
                            ->schema($this->getTemplatesFormSchema()),

                        Tabs\Tab::make('Advanced')
                            ->schema($this->getDataSourceFormSchema()),

                    ]),
            ]);
    }


}
