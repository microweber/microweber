<?php

namespace MicroweberPackages\CustomField\tests;

use MicroweberPackages\Core\tests\TestCase;

class CustomFieldMultilanguageTest extends TestCase
{
    public function testAddCustomField()
    {
        $newCustomField = new \MicroweberPackages\CustomField\Models\CustomField();
        $newCustomField->rel_type = 'content';
        $newCustomField->rel_id = 13;
        $newCustomField->type = 'text';
        $newCustomField->name = 'This is the custom field name - EN';

        $multilanguage = [];
        $multilanguage['bg_BG']['name'] = 'This is the custom field name - BG';
        $multilanguage['bg_BG']['placeholder'] = 'This is the placeholder - BG';
        $multilanguage['bg_BG']['error_text'] = 'This is the error text - BG';

        $newCustomField->multilanguage = $multilanguage;
        $newCustomField->save();

//        mw()->fields_manager->save([
//            'id' => $newCustomField->id,
//            'type' => 'text',
//            'name' => 'TOVA E MEGA QKO vRAAAT',
//        ]);

//        $findCustomField = \MicroweberPackages\CustomField\Models\CustomField::find($newCustomField->id);
//
//        dd($findCustomField);
    }
}
