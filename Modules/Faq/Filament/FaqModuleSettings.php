<?php

namespace Modules\Faq\Filament;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;
use Filament\Forms;

class FaqModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'faq';
    public array $faqs;
    public function mount(): void
    {
        parent::mount();
        $this->faqs = @json_decode($this->getOption('faqs'), true) ?? [];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('options.title')
                    ->label('FAQ Title')
                    ->placeholder('Enter FAQ title')
                    ->live()
                    ->default('Frequently Asked Questions'),

                Repeater::make('faqs')
                    ->addActionLabel('Add FAQ')
                    ->label('FAQs')
                    ->reorderableWithButtons()
                    ->reorderAction( function (Forms\Get $get, Forms\Set $set) {
                       // $this->faqs = $get('faqs');
                      //  $set('faqs', $this->faqs);
                    })
                    ->schema([
                        TextInput::make('question')
                            ->label('Question')
                            ->placeholder('Enter question')
                            ->live(),

                        Textarea::make('answer')
                            ->label('Answer')
                            ->placeholder('Enter answer')
                            ->live(),
                    ])
                    ->minItems(1)
                    ->live(),
            ]);
    }


    public function updated($propertyName, $value): void
    {
        parent::updated($propertyName, $value);
        $this->saveOption('faqs', json_encode($this->faqs));
    }

    public function save(): void
    {
        parent::save();
        $this->saveOption('faqs', json_encode($this->faqs));
    }
}
