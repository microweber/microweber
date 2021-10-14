<?php

namespace MicroweberPackages\Multilanguage\tests;

class MultilanguageOptionTest extends MultilanguageTestBase
{

    public function testSaveModuleOptionApi()
    {
        $data = [];
        $data['lang'] = 'bg_BG';
        $data['option_key'] = 'settings';
        $data['module'] = 'slider';
        $data['option_value'] = 'ti si manqk';
        $data['option_group'] = 'slider-20211014142244';

        mw()->option_manager->save($data);


    }
}
