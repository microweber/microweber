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
use MicroweberPackages\Backup\Loggers\DefaultLogger;
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
use MicroweberPackages\Modules\Newsletter\ProcessCampaigns;

class ProcessCampaign extends Page
{
    protected static ?string $slug = 'process-campaign/{id}';

    protected static string $view = 'microweber-module-newsletter::livewire.filament.admin.process-campaign';

    protected static bool $shouldRegisterNavigation = false;


    public $log = '';
    private $logger;
    public $logPublicUrl = '';

    public $listeners = [
        'processCampaigns'=>'processCampaigns'
    ];

    public function mount()
    {
        $this->setupLogger();
    }

    public function setupLogger()
    {
        $this->logger = new ProcessCampaignsLogger();
        $this->logger->clearLog();

        $logPublicUrl = $this->logger->getLogFilepath();
        $logPublicUrl = str_replace(userfiles_path(), userfiles_url(), $logPublicUrl);

        $this->logPublicUrl = $logPublicUrl;
    }

    public function processCampaigns()
    {
        $this->setupLogger();

        $processCampaigns = new ProcessCampaigns();
        $processCampaigns->setLogger($this->logger);
        $processCampaigns->run();
    }

}


class ProcessCampaignsLogger extends DefaultLogger {

    public function info($msg) {
        $this->setLogInfo($msg . '<br>');
    }

    public function warn($msg) {
        $this->setLogInfo($msg. '<br>');
    }

    public function error($msg) {
        $this->setLogInfo($msg. '<br>');
    }

}
