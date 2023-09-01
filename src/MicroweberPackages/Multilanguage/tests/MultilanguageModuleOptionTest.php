<?php

namespace MicroweberPackages\Multilanguage\tests;

use MicroweberPackages\Multilanguage\Models\MultilanguageTranslations;
use MicroweberPackages\Option\Models\ModuleOption;
use MicroweberPackages\Translation\Models\Translation;

class MultilanguageModuleOptionTest extends MultilanguageTestBase
{

    public function testSaveModuleOptionApi()
    {

        $defaultLang = app()->lang_helper->default_lang();
        $currentLang = app()->lang_helper->current_lang();

        $optionKey = 'settings';
        $module = 'slider';
        $optionGroup = 'slider-20211014142244';

        // Save the option on default lang
        $dataDefaultLang = [];
        $dataDefaultLang['option_key'] = $optionKey;;
        $dataDefaultLang['module'] = $module;
        $dataDefaultLang['option_value'] = 'ti si manqk-' . $defaultLang;
        $dataDefaultLang['option_group'] = $optionGroup;
        mw()->option_manager->save($dataDefaultLang);

        // Check the value is the same
        $findModuleOption = ModuleOption::where('option_key', $dataDefaultLang['option_key'])
            ->where('module', $dataDefaultLang['module'])
            ->where('option_key', $dataDefaultLang['option_key'])
            ->where('option_group', $dataDefaultLang['option_group'])->first();
        $this->assertEquals($findModuleOption->option_value, $dataDefaultLang['option_value']);


        // Save mutilang module option
        $data = [];
        $data['lang'] = 'bg_BG';
        $data['option_key'] = $optionKey;;
        $data['module'] = $module;
        $data['option_value'] = 'ti si manqk na BG';
        $data['option_group'] = $optionGroup;
        mw()->option_manager->save($data);

        $findModuleOption = ModuleOption::where('option_key', $data['option_key'])
            ->where('module', $data['module'])
            ->where('option_key', $data['option_key'])
            ->where('option_group', $data['option_group'])->first();


        $findTranslation = MultilanguageTranslations::where('locale', $data['lang'])
            ->where('rel_type','options')
            ->where('field_name', 'option_value')->first();


        $this->assertEquals($findTranslation->rel_id, $findModuleOption->id);
        $this->assertEquals($findTranslation->field_value, $data['option_value']);

        /////////////////////////
        $data = [];
        $data['lang'] = 'ar_SA';
        $data['option_key'] = $optionKey;;
        $data['module'] = $module;
        $data['option_value'] = 'ti si arabski manqk';
        $data['option_group'] = $optionGroup;
        mw()->option_manager->save($data);

        $findModuleOption = ModuleOption::where('option_key', $data['option_key'])
            ->where('module', $data['module'])
            ->where('option_key', $data['option_key'])
            ->where('option_group', $data['option_group'])->first();


        $findTranslation = MultilanguageTranslations::where('locale', $data['lang'])
            ->where('rel_type','options')
            ->where('field_name', 'option_value')->first();

        $this->assertEquals($findTranslation->rel_id, $findModuleOption->id);
        $this->assertEquals($findTranslation->field_value, $data['option_value']);


        // Check the value is the same after many antoher multilanguage saves
        $findModuleOption = ModuleOption::where('option_key', $dataDefaultLang['option_key'])
            ->where('module', $dataDefaultLang['module'])
            ->where('option_key', $dataDefaultLang['option_key'])
            ->where('option_group', $dataDefaultLang['option_group'])->first();
        $this->assertEquals($findModuleOption->option_value, $dataDefaultLang['option_value']);


    }
}
