<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 7/30/2021
 * Time: 12:21 PM
 */

namespace MicroweberPackages\Option\Repositories;


use MicroweberPackages\Option\Models\Option;
use MicroweberPackages\Repository\Repositories\AbstractRepository;


class OptionRepository extends AbstractRepository
{
    /**
     * Specify Models class name
     *
     * @return string
     */
    public $model = Option::class;

    public function getWebsiteOptions()
    {
        if (!mw_is_installed()) {
            return Option::queryWebsiteOptions([]);
        }

        $getWebsiteOptions = $this->getOptionsByGroup('website');

        return Option::queryWebsiteOptions($getWebsiteOptions ?: []);
    }



    public static $_getAllExistingOptionGroups = [];
    public function getAllExistingOptionGroups()
    {
        if (!empty(self::$_getAllExistingOptionGroups)) {
            return self::$_getAllExistingOptionGroups;
        }

        $allOptions = [];
        try {
            $allOptions = $this->cacheCallback(__FUNCTION__, func_get_args(), function () {
                return Option::queryAllExistingOptionGroups();
            });
        } catch (\Exception $e) {
            return [];
        }

        self::$_getAllExistingOptionGroups = $allOptions;

        return self::$_getAllExistingOptionGroups;
    }

    public function optionGroupExists($optionGroup)
    {
        $existingGroups = $this->getAllExistingOptionGroups();

        if ($existingGroups) {
            $existingGroups = array_filter($existingGroups);
            $existingGroups = array_flip($existingGroups);
            if (isset($existingGroups[$optionGroup])) {
                return true;
            }
        }

        return false;
    }

    public function clearCache()
    {

        self::$_getOptionsByGroup = [];
        self::$_getAllExistingOptionGroups = [];
        self::$_cacheCallbackMemory = [];
        parent::clearCache();
    }

    public static $_getOptionsByGroup = [];
    public function getOptionsByGroup($optionGroup)
    {
        if (isset(self::$_getOptionsByGroup[$optionGroup])) {
            return self::$_getOptionsByGroup[$optionGroup];
        }

        $isExsitOptionGroup = $this->optionGroupExists($optionGroup);
        if (!$isExsitOptionGroup) {
            return false;
        }

        $allOptions = $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($optionGroup) {
            return Option::queryOptionsByGroup($optionGroup);
        });

        self::$_getOptionsByGroup[$optionGroup] = $allOptions;

        return $allOptions;
    }
}
