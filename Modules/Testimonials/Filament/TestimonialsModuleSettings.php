<?php

namespace Modules\Testimonials\Filament;

use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;
use Modules\Testimonials\Models\Testimonial;

class TestimonialsModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'testimonials';

    public function form(Form $form): Form
    {
        $module_id = $this->params['id'] ?? null;
        $rel_id = $this->getOption('rel_id') ?? $this->params['rel_id'] ?? $this->params['id'] ?? null;
        $rel_type = $this->getOption('rel_type') ?? $this->params['rel_type'] ?? 'module';

        return $form
            ->schema([
                Tabs::make('Testimonials')
                    ->tabs([
                        Tabs\Tab::make('Main settings')
                            ->schema([
                                Livewire::make(TestimonialsTableList::class, [
                                    'rel_id' => $rel_id,
                                    'rel_type' => $rel_type,
                                    'module_id' => $module_id,
                                ])->reactive()->live(),
                            ]),
                        Tabs\Tab::make('Design')
                            ->schema($this->getTemplatesFormSchema()),

                        Tabs\Tab::make('Advanced')
                            ->schema([
                                Select::make('options.rel_type')
                                    ->label('Data Source Type')
                                    ->helperText('Select the type of data source for the module')
                                    ->options(fn () => $this->getRelTypeOptions())
                                    ->live()
                                    ->reactive()
                                    ->searchable()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $this->dispatch('resetTableList', ['rel_type' => $state]);
                                    }),

                                Select::make('options.rel_id')
                                    ->label('Data Source Identifier')
                                    ->helperText('Choose the specific identifier for the selected data source type')
                                    ->options(fn () => $this->getRelIdOptions())
                                    ->live()
                                    ->reactive()
                                    ->searchable()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $this->dispatch('resetTableList', ['rel_id' => $state]);
                                    }),
                            ]),


                    ]),
            ]);
    }

    /**
     * Get options for the `rel_id` field dynamically.
     *
     * @return array
     */
    protected function getRelIdOptions($rel_type = false): array
    {

        $vals = Testimonial::query()->select('rel_id');
        if ($rel_type) {
            $vals->where('rel_type', $rel_type);
        }
        $vals = $vals->distinct()->pluck('rel_id')->toArray();
        return array_combine($vals, $vals);
    }

    protected function getRelTypeOptions(): array
    {
        $vals = Testimonial::query()->select('rel_type')->distinct()->pluck('rel_type')->toArray();
        return array_combine($vals, $vals);
    }
}
