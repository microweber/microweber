<?php
namespace MicroweberPackages\Marketplace\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use MicroweberPackages\Package\MicroweberComposerClient;
use MicroweberPackages\Package\MicroweberComposerPackage;
use Sushi\Sushi;

class MarketplaceItem extends Model {

    use Sushi;

    public function getRows()
    {
        $packages = Cache::remember('livewire-marketplace', Carbon::now()->addHours(12), function () {
            $marketplace = new MicroweberComposerClient();
            return $marketplace->search();
        });

        $latestVersions = [];

        $allowedCategories = [
            'microweber-module',
            'microweber-template'
        ];
        if (!empty($this->category)) {
            if ($this->category !== 'all') {
                $allowedCategories = [];
                $allowedCategories[] = $this->category;
            }
        }
        foreach ($packages as $packageName=>$package) {

            $latestVersionPackage = end($package);
            if (!isset($latestVersionPackage['target-dir'])) {
                continue;
            }

            if (isset($latestVersionPackage['type']) && !in_array($latestVersionPackage['type'], $allowedCategories)) {
                continue;
            }

            $searchKeywords = [];
            if (isset($latestVersionPackage['keywords']) && is_array($latestVersionPackage['keywords'])) {
                $searchKeywords = array_merge($searchKeywords, $latestVersionPackage['keywords']);
            }

            if (isset($latestVersionPackage['extra']['categories']) && is_array($latestVersionPackage['extra']['categories'])) {
                $searchKeywords = array_merge($searchKeywords, $latestVersionPackage['extra']['categories']);
            }

            array_walk($searchKeywords, function(&$value) {
                $value = mb_strtolower($value);
            });

            if (!empty($this->keyword)) {
                $founded = false;
                if (in_array(mb_strtolower(trim($this->keyword)), $searchKeywords)) {
                    $founded = true;
                }


                if (isset($latestVersionPackage['description'])) {
                    if (mb_strpos(mb_strtolower($latestVersionPackage['description']), mb_strtolower(trim($this->keyword))) !== false) {
                        $founded = true;
                    }
                }
                if (!$founded) {
                    continue;
                }
            }
            $versions = [];
            foreach ($package as $version=>$versionData) {
                $versions[] = $version;
            }
            // Sort versions by newest
            usort($versions, function($a, $b) {
                return version_compare($a, $b, '<');
            });
            $latestVersionPackage['versions'] = $versions;
            $latestVersionPackage = MicroweberComposerPackage::format($latestVersionPackage);

            $latestVersions[$packageName] = $latestVersionPackage;
        }

        $allItems = [];
        $i = 0;
        foreach ($latestVersions as $latestVersion) {
            $i++;

            $item = [];
            $item['id'] = $i;
            $item['type'] = $latestVersion['type'];
            $item['is_paid'] = $latestVersion['is_paid'];
            $item['available_for_install'] = $latestVersion['available_for_install'];
            $item['is_symlink'] = $latestVersion['is_symlink'];
            $item['has_update'] = $latestVersion['has_update'];
            $item['has_current_install'] = 0;
            if (isset($latestVersion['current_install']['local_type'])) {
                $item['has_current_install'] = 1;
            }
            $item['internal_name'] = $latestVersion['name'];
            $item['name'] = $latestVersion['description'];
            $item['big_screenshot_link'] = $latestVersion['screenshot_link'];
            $item['version'] = $latestVersion['version'];
            $item['versions'] = json_encode($latestVersion['versions']);
            $item['author_name'] = false;
            $item['author_email'] = false;
            $item['description'] = 'No description';
            $item['tags'] = 'No tags';
            $item['request_license'] = 0;

            if (isset($latestVersion['dist']['type'])) {
                if ($latestVersion['dist']['type'] == 'license_key') {
                    $item['request_license'] = 1;
                }
            }

            if (isset($latestVersion['authors'][0]['name'])) {
                $item['author_name'] = $latestVersion['authors'][0]['name'];
                $item['author_email'] = $latestVersion['authors'][0]['email'];
            }
            $item['license'] = 'Unknown';
            if (isset($latestVersion['license'][0])) {
                $item['license'] = $latestVersion['license'][0];
            }

            $item['screenshot_link'] = site_url().'src/MicroweberPackages/Marketplace/resources/images/no-module-image.jpg';
            if (isset($latestVersion['extra']['_meta']['screenshot'])) {
                $item['screenshot_link'] = $latestVersion['extra']['_meta']['screenshot'];
            }

            $allItems[] = $item;
        }

        return $allItems;
    }
}
