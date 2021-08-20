<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 7/30/2021
 * Time: 12:21 PM
 */

namespace MicroweberPackages\Option\Repositories;


use MicroweberPackages\Option\Models\ModuleOption;
use MicroweberPackages\Option\Models\Option;
use MicroweberPackages\Repository\Repositories\AbstractRepository;


class OptionRepository extends AbstractRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public $model = Option::class;

    public function getWebsiteOptions()
    {
        $websiteOptions = [
            'favicon_image'=>'',
            'website_footer'=>'',
            'website_head'=>'',
            'website_title'=>'',
            'website_keywords'=>'',
            'website_description'=>'',
            'date_format'=>'',
            'enable_full_page_cache'=>'',
            'google-site-verification-code'=>'',
            'bing-site-verification-code'=>'',
            'alexa-site-verification-code'=>'',
            'pinterest-site-verification-code'=>'',
            'yandex-site-verification-code'=>'',
            'google-analytics-id'=>'',
            'facebook-pixel-id'=>'',
            'robots_txt'=>'' ,
            'maintenance_mode'=>'',
            'maintenance_mode_text'=>''
        ];

        if (!mw_is_installed()) {
            return $websiteOptions;
        }

        $getWebsiteOptions = ModuleOption::where('option_group', 'website')->get();
        if (!empty($getWebsiteOptions)) {
            foreach ($getWebsiteOptions as $websiteOption) {
                $websiteOptions[$websiteOption['option_key']] = $websiteOption['option_value'];
            }
        }

        return $websiteOptions;
    }



    public function getAllExistingOptionGroups()
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () {

            $allOptions = [];
            $getAllOptions = \DB::table('options')
                ->select('option_group')
                ->groupBy('option_group')
                ->get();
            $getAllOptions = collect($getAllOptions)->map(function ($option) {
                return (array)$option;
            })->toArray();

            if ($getAllOptions and is_array($getAllOptions)) {
                $allOptions = array_flatten($getAllOptions);
            }

            return $allOptions;
        });
    }

    public function optionGroupExists($optionGroup)
    {
        $existingGroups = $this->getAllExistingOptionGroups();

        if ($existingGroups) {
            $existingGroups = array_flip($existingGroups);
            if (isset($existingGroups[$optionGroup])) {
                return true;
            }
        }

        return false;
    }

    public function getOptionsByGroup($optionGroup)
    {
        $isExsitOptionGroup = $this->optionGroupExists($optionGroup);

        if (!$isExsitOptionGroup) {
            return false;
        }

        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($optionGroup) {
            $allOptions = \DB::table('options')->where('option_group', $optionGroup)->get();
            $allOptions = collect($allOptions)->map(function ($option) {
                return (array)$option;
            })->toArray();

            return $allOptions;
        });
    }
}
