<?php

namespace MicroweberPackages\Multilanguage\tests;

use MicroweberPackages\Multilanguage\Models\MultilanguageTranslations;
use MicroweberPackages\Option\Models\ModuleOption;
use MicroweberPackages\Translation\Models\Translation;

class MultilanguageOptionTest extends MultilanguageTestBase
{

    public function testSaveModuleOptionApi()
    {
        // Save mutilang module option
        $data = [];
        $data['lang'] = 'bg_BG';
        $data['option_key'] = 'settings';
        $data['module'] = 'slider';
        $data['option_value'] = 'ti si manqk';
        $data['option_group'] = 'slider-20211014142244';
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
        $data['option_key'] = 'settings';
        $data['module'] = 'slider';
        $data['option_value'] = 'ti si arabski manqk';
        $data['option_group'] = 'slider-20211014142244';
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




    }
}
