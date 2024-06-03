<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Pages\Abstract\SettingsPageDefault;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use MicroweberPackages\Option\Models\Option;
use function Clue\StreamFilter\fun;

class SettingsGeneral extends SettingsPageDefault
{

    protected static ?string $slug = 'settings/general';

    protected static string $view = 'filament.admin.pages.settings-general';

    protected static ?string $title = 'General';

    protected static string $description = 'Make basic settings for your website.';
    protected static ?string $navigationGroup = 'Website Settings';

    public $options = [
        'website'=>[]
    ];

    public function updatedOptions()
    {
        $formState = $this->form->getState();
        if (empty($formState['options'])) {
            return;
        }

        foreach ($formState['options'] as $key => $value) {

            $parseKey = explode('[', $key);
            $optionGroup = $parseKey[0];
            $optionKey = str_replace(']', '', $parseKey[1]);

            $option = Option::where('option_group', $optionGroup)->where('option_key', $optionKey)->first();
            if ($option) {
                $option->option_value = $value;
                $option->save();
            } else {
                Option::create([
                    'option_group' => $optionGroup,
                    'option_key' => $optionKey,
                    'option_value' => $value,
                ]);
            }

        }

        Notification::make()
            ->title('Settings Updated')
            ->success()
            ->send();

    }
    public function mount()
    {
        $getOptions = Option::whereIn('option_group', [
            'website',
        ])->get();
        if ($getOptions) {
            foreach ($getOptions as $option) {
                $this->options[$option->option_group . '['.$option->option_key.']'] = $option->option_value;
            }
        }

        return [];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Seo Settings')
                    ->view('filament-forms::sections.section')
                    ->description(' Fill in the fields for maximum results when finding your website in search engines.')
                    ->schema([

                        TextInput::make('options.website[website_title]')
                            ->live()
                            ->label('Website Name')
                            ->helperText('This is very important for search engines. Your website will be categorized by many criteria and its name is one of them.')
                            ->placeholder('Enter your website name'),

                        Textarea::make('options.website[website_description]')
                            ->live()
                            ->label('Website Description')
                            ->helperText('This is very important for search engines. Your website will be categorized by many criteria and its description is one of them.')
                            ->placeholder('Enter your website description'),

                        TextInput::make('options.website[website_keywords]')
                            ->live()
                            ->label('Website Keywords')
                            ->helperText('This is very important for search engines. Your website will be categorized by many criteria and its keywords are one of them.')
                            ->placeholder('Enter your website keywords'),

                        Select::make('options.website[permalink_structure]')
                            ->label('Permalink Structure')
                            ->live()
                            ->options([
                                'post' => 'sample-post',
                                'page_post' => 'page/sample-post',
                                'category_post' => 'sample-category/sample-post',
                                'page_category_post' => 'sample-page/sample-category/sample-post',
                            ])
                            ->placeholder('Select Permalink Structure'),

                    ]),

            ]);
    }
}
