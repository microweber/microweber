<?php

namespace Modules\Newsletter\Livewire\Admin;

use MicroweberPackages\Admin\Http\Livewire\AdminModalComponent;
use Modules\Newsletter\Services\ImportSubscribersFileReader;
use Modules\Newsletter\Models\NewsletterList;
use Modules\Newsletter\Models\NewsletterSubscriber;
use Modules\Newsletter\Models\NewsletterSubscriberList;

class NewsletterImportSubscribersModal extends AdminModalComponent
{
    public $modalSettings = [
        'width'=>'700px',
        'overlay' => true,
        'overlayClose' => true,
    ];

    public $importSubscribers = [
        'sourceType' => 'uploadFile'
    ];

    public $importDone = [];

    protected $listeners = [
        'uploadEmailList'=>'uploadEmailList'
    ];

    public $lists = [];
    public $list_id = 0;

    public function download()
    {
        $sourceUrl = $this->importSubscribers['sourceUrl'];
        $this->downloadFeed($sourceUrl);

    }

    public function mount()
    {
        $this->importDone = [];
    }

    public function importSubscribersList()
    {
        $subscriberListFile = $this->importSubscribers['sourceFileRealpath'];
        if (is_file($subscriberListFile)) {
            $fileExt = pathinfo($subscriberListFile, PATHINFO_EXTENSION);

            try {
                $fileReader = new ImportSubscribersFileReader();
                $readSubscribers = $fileReader->readContentFromFile($subscriberListFile, $fileExt);
            } catch (\Exception $e) {
                session()->flash('errorMessage', $e->getMessage());
                return;
            }

            if (!empty($readSubscribers)) {
                $imported = 0;
                $skipped = 0;
                $failed = 0;
                foreach($readSubscribers as $subscriber) {

                    if (!isset($subscriber['email']) || empty($subscriber['email'])) {
                        $failed++;
                        continue;
                    }

                    $findSubsciber = NewsletterSubscriber::where('email', $subscriber['email'])->first();
                    if (!$findSubsciber) {
                        $findSubsciber = new NewsletterSubscriber();
                        $findSubsciber->is_subscribed = 1;
                        $findSubsciber->email = $subscriber['email'];
                        $imported++;
                    } else {
                        $skipped++;
                    }
                    $findSubsciber->name = $subscriber['name'];
                    $findSubsciber->save();

                    $findSubscriberList = NewsletterSubscriberList::where('list_id', $this->list_id)
                        ->where('subscriber_id', $findSubsciber->id)->first();
                    if (!$findSubscriberList) {
                        $findSubscriberList = new NewsletterSubscriberList();
                        $findSubscriberList->list_id = $this->list_id;
                        $findSubscriberList->subscriber_id = $findSubsciber->id;
                        $findSubscriberList->save();
                    }

                }
                $this->importDone = [
                    'imported'=>$imported,
                    'skipped'=>$skipped,
                    'failed'=>$failed
                ];
                $this->dispatch('newsletterSubscribersListUpdated');
            }

        } else {
            session()->flash('errorMessage', 'Can\'t read subscribers list file.');
        }
    }

    public function uploadEmailList($fileName)
    {
        $fullPathEmailList = ImportSubscribersFileReader::getImportTempPath() . 'uploaded_files' . DS . $fileName;
        if ($fullPathEmailList && is_file($fullPathEmailList)) {
            $this->importSubscribers['sourceFileRealpath'] = $fullPathEmailList;
        } else {
            $this->importSubscribers['sourceFileRealpath'] = false;
        }
    }

    public function downloadFeed($url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $allowedExt = ['csv','xlsx','xls'];
        $fileExt = pathinfo($url, PATHINFO_EXTENSION);

        $dir = NewsletterImportSubscribersModal . phpstorage_path() . DS . 'newsletter_subscribers';

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
        } else {
            $this->importSubscribers['sourceFileRealpath'] = false;
        }

    }

    public function render()
    {
        $this->lists = NewsletterList::all()->toArray();

        return view('microweber-module-newsletter::livewire.admin.import-subscribers-modal');
    }
}
