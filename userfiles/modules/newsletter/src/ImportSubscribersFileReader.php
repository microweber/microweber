<?php

namespace MicroweberPackages\Modules\Newsletter;

use Illuminate\Support\Facades\App;
use MicroweberPackages\Export\Formats\Helpers\SpreadsheetHelper;
use MicroweberPackages\Export\SessionStepper;
use MicroweberPackages\Import\Formats\CsvReader;

class ImportSubscribersFileReader
{

    public static function getImportTempPath()
    {
        $environment = App::environment();
        $folder = storage_path('newsletter_subscribers_list/') . ('default' . DIRECTORY_SEPARATOR);

        if(defined('MW_IS_MULTISITE') and MW_IS_MULTISITE) {
            $folder = storage_path('newsletter_subscribers_list/') . ($environment . DIRECTORY_SEPARATOR);
        }

        if (!is_dir($folder)) {
            mkdir_recursive($folder);
        }

        return $folder;
    }

    public function readContentFromFile(string $filename, $fileType = false)
    {
        if ($fileType == 'xlsx' || $fileType == 'xls') {
            return $this->readContentFromXlsx($filename);
        } elseif ($fileType == 'csv') {
            return $this->readContentFromCsv($filename);
        } else {
            return false;
        }
    }

    private function readContentFromXlsx(string $filename)
    {
        $spreadshet = SpreadsheetHelper::newSpreadsheet($filename);
        $sheetCount = $spreadshet->getSheetCount();
        if ($sheetCount == 0) {
            //  throw new \Exception('No sheets found');
            return false;
        }
//
//        if (empty($this->content_tag)) {
//            $this->content_tag = $spreadshet->setSheet(0)->getSheet()->getTitle();
//        }
//
//        // Read sheet
//        $readedRows = [];
//        for ($i = 0; $i <= $sheetCount; $i++) {
//
//            try {
//                $getSheet = $spreadshet->setSheet($i)->getSheet();
//                $getRows = $spreadshet->getRows();
//                $sheetNames[$i] = $getSheet->getTitle();
//                $repeatableTargetKeys[$getSheet->getTitle()] = [];
//
//                // unset headers
//                $dataHeader = $getRows[0];
//                unset($getRows[0]);
//                foreach ($getRows as $row) {
//                    $readyRow = array();
//                    foreach ($row as $rowKey => $rowValue) {
//                        $readyRow[$dataHeader[$rowKey]] = $rowValue;
//                    }
//                    $readedRows[$getSheet->getTitle()][] = $readyRow;
//                }
//            } catch (\Exception $e) {
//
//            }
//        }

        return true;
    }

    private function readContentFromCsv(string $filename)  {

        $reader = new CsvReader($filename);
        $subscribersList = $reader->readData();

        $subscribersData = [];
        if (!empty($subscribersList)) {
            foreach ($subscribersList as $subscriber) {
                $subscriberData = [
                    'email' => '',
                    'name' => '',
                    'first_name' => '',
                    'last_name' => '',
                    'phone' => '',
                    'company' => '',
                    'country' => '',
                    'city' => '',
                    'state' => '',
                    'zip' => '',
                    'address' => '',
                    'source' => ''
                ];
                if (isset($subscriber['email'])) {
                    $subscriberData['email'] = $subscriber['email'];
                }
                if (isset($subscriber['name'])) {
                    $subscriberData['name'] = $subscriber['name'];
                }
                if (isset($subscriber['first_name'])) {
                    $subscriberData['first_name'] = $subscriber['first_name'];
                }
                if (isset($subscriber['last_name'])) {
                    $subscriberData['last_name'] = $subscriber['last_name'];
                }
                if (isset($subscriber['phone'])) {
                    $subscriberData['phone'] = $subscriber['phone'];
                }
                if (isset($subscriber['company'])) {
                    $subscriberData['company'] = $subscriber['company'];
                }
                if (isset($subscriber['country'])) {
                    $subscriberData['country'] = $subscriber['country'];
                }
                if (isset($subscriber['city'])) {
                    $subscriberData['city'] = $subscriber['city'];
                }
                if (isset($subscriber['state'])) {
                    $subscriberData['state'] = $subscriber['state'];
                }
                if (isset($subscriber['zip'])) {
                    $subscriberData['zip'] = $subscriber['zip'];
                }
                if (isset($subscriber['address'])) {
                    $subscriberData['address'] = $subscriber['address'];
                }
                if (isset($subscriber['source'])) {
                    $subscriberData['source'] = $subscriber['source'];
                }

                $subscribersData[] = $subscriberData;
            }
        }

        return $subscribersData;
    }

}
