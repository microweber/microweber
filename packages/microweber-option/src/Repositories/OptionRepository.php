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



    public function getAllExistingOptionGroups()
    {
        try {
            return $this->cacheCallback(__FUNCTION__, func_get_args(), function () {
                return Option::queryAllExistingOptionGroups();
            });
        } catch (\Exception $e) {
            return [];
        }
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

    public function getOptionsByGroup($optionGroup)
    {
        $isExsitOptionGroup = $this->optionGroupExists($optionGroup);
        if (!$isExsitOptionGroup) {
            return false;
        }

        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($optionGroup) {
            return Option::queryOptionsByGroup($optionGroup);
        });
    }
}
