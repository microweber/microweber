<?php

namespace MicroweberPackages\Modules\Newsletter\Filament\Admin\Pages;


use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\View;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Page;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\IconSize;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\FormBuilder\Elements\RadioButton;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\SenderAccountsResource;
use MicroweberPackages\Modules\Newsletter\Filament\Components\SelectTemplate;
use MicroweberPackages\Modules\Newsletter\Filament\Imports\NewsletterSubscriberImporter;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaignsSendLog;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterList;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterSenderAccount;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterSubscriber;
use Livewire\Attributes\On;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterTemplate;

class CreateCampaign extends Page
{
//    protected static ?string $slug = 'newsletter/create-campaign';

    protected static string $view = 'microweber-module-newsletter::livewire.filament.admin.create-campaign';

    protected static bool $shouldRegisterNavigation = false;

    public $name = '';

    public function createCampaign()
    {
        $this->validate([
            'name' => 'required',
        ]);

        $campaign = new NewsletterCampaign();
        $campaign->name = $this->name;
        $campaign->status = NewsletterCampaign::STATUS_DRAFT;
        $campaign->email_content_html = "Hello, {{name}}! <br /> How are you today?";
        $campaign->email_content_type = 'design';
        $campaign->save();

        return redirect()->route('filament.admin-newsletter.pages.edit-campaign.{id}', $campaign->id);
    }

    public function form(Form $form): Form
    {
        return $form
            ->model(NewsletterCampaign::class)
            ->schema([

        TextInput::make('name')
            ->label('Name')
            ->hiddenLabel()
            ->required()
            ->placeholder('Enter your campaign name...'),


        ]);


    }

}
