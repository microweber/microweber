<?php

namespace MicroweberPackages\Modules\Newsletter\Http\Livewire\Admin;

use Carbon\Carbon;
use MicroweberPackages\Admin\Http\Livewire\AdminModalComponent;
use MicroweberPackages\Backup\Loggers\DefaultLogger;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterTemplate;
use MicroweberPackages\Modules\Newsletter\ProcessCampaigns;

class NewsletterImportSubscribersModal extends AdminModalComponent
{
    public $modalSettings = [
        'width'=>'900px',
        'overlay' => true,
        'overlayClose' => true,
    ];

    public $importSubscribers = [
        'sourceType' => 'uploadFile'
    ];

    public function download()
    {
        $sourceUrl = $this->importSubscribers['sourceUrl'];

        if ($this->downloadFeed($sourceUrl)) {
            session()->flash('successMessage', 'Feed is downloaded successfully.');
            $this->dispatchBrowserEvent('read-feed-from-file');
        } else {
            session()->flash('errorMessage', 'Feed can\'t be downloaded.');
        }

    }

    public function downloadFeed($url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $allowedExt = ['csv','xlsx','xls'];
        $fileExt = pathinfo($url, PATHINFO_EXTENSION);

        $dir = storage_path() . DS . 'newsletter_subscribers';

        $filename = $dir . DS . md5($url) . '.txt';
        if (in_array($fileExt, $allowedExt)) {
            $filename = $dir . DS . md5($url) . '.'.$fileExt;
        }

        if (!is_dir($dir)) {
            mkdir_recursive($dir);
        }

        // Delete old file if exist
        if (is_file($filename)) {
            unlink($filename);
        }

        $downloaded = mw()->http->url($url)->download($filename);

        if ($downloaded && is_file($filename)) {

            dd($filename);

            return true;
        }

        return false;
    }

    public function render()
    {
        return view('microweber-module-newsletter::livewire.admin.import-subscribers-modal');
    }
}
