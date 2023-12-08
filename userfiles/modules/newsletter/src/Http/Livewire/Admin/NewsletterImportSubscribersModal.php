<?php

namespace MicroweberPackages\Modules\Newsletter\Http\Livewire\Admin;

use Carbon\Carbon;
use MicroweberPackages\Admin\Http\Livewire\AdminModalComponent;
use MicroweberPackages\Backup\Loggers\DefaultLogger;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;
use MicroweberPackages\Modules\Newsletter\ImportSubscribersFileReader;
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

    protected $listeners = [
        'readSubscribersListFile' => 'readSubscribersListFile',
    ];

    public function download()
    {
        $sourceUrl = $this->importSubscribers['sourceUrl'];
        if ($this->downloadFeed($sourceUrl)) {
            session()->flash('successMessage', 'Feed is downloaded successfully.');
            $this->dispatchBrowserEvent('read-subscribers-list-from-file');
        } else {
            session()->flash('errorMessage', 'Feed can\'t be downloaded.');
        }

    }

    public function readSubscribersListFile()
    {
        $subscriberListFile = $this->importSubscribers['sourceFileRealpath'];

        if (is_file($subscriberListFile)) {

            $fileExt = pathinfo($subscriberListFile, PATHINFO_EXTENSION);

            $fileReader = new ImportSubscribersFileReader();
            $read = $fileReader->readContentFromFile($subscriberListFile, $fileExt);
            if ($read) {
                session()->flash('successMessage', 'Subscribers list is read successfully.');
            } else {
                session()->flash('errorMessage', 'No data found in subscribers list file.');
            }
        } else {
            session()->flash('errorMessage', 'Can\'t read subscribers list file.');
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
            $this->importSubscribers['sourceFileRealpath'] = $filename;
            return true;
        }

        return false;
    }

    public function render()
    {
        return view('microweber-module-newsletter::livewire.admin.import-subscribers-modal');
    }
}
