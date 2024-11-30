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
        $relId = $this->getOption('rel_id') ?? $this->params['rel_id'] ?? $this->params['id'] ?? null;
        $relType = $this->getOption('rel_type') ?? $this->params['rel_type'] ?? 'module';


        return $form
            ->schema([
                Tabs::make('Testimonials')
                    ->tabs([
                        Tabs\Tab::make('Main settings')
                            ->schema([
                                Livewire::make(TestimonialsTableList::class, [
                                    'rel_id' => $relId,
                                    'rel_type' => $relType,
                                ])->reactive()->live(),

                            ]),
                        Tabs\Tab::make('Design')
                            ->schema($this->getTemplatesFormSchema()),

                        Tabs\Tab::make('Advanced')
                            ->schema([
                                Select::make('options.rel_type')
                                    ->label('rel_type')
                                    ->options(fn() => $this->getRelTypeOptions())
                                    ->live()
                                    ->reactive()
                                    ->searchable()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $this->dispatch('tableFilterUpdated', ['rel_type' => $state]);
                                    }),

                                Select::make('options.rel_id')
                                    ->label('rel_id')
                                    ->options(fn() => $this->getRelIdOptions())
                                    ->live()
                                    ->reactive()
                                    ->searchable()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $this->dispatch('tableFilterUpdated', ['rel_id' => $state]);
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
    protected function getRelIdOptions(): array
    {

        $vals = Testimonial::query()->select('rel_id')->distinct()->pluck('rel_id')->toArray();

        // make the keys to be the same as the values
        return array_combine($vals, $vals);
    }

    protected function getRelTypeOptions(): array
    {

        $vals = Testimonial::query()->select('rel_type')->distinct()->pluck('rel_type')->toArray();
        return array_combine($vals, $vals);

    }
}
