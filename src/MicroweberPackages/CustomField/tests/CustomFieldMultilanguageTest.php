<?php

namespace MicroweberPackages\CustomField\tests;

use MicroweberPackages\Multilanguage\tests\MultilanguageTestBase;

class CustomFieldMultilanguageTest extends MultilanguageTestBase
{
    public function testAddCustomField()
    {
        $newCustomField = new \MicroweberPackages\CustomField\Models\CustomField();
        $newCustomField->rel_type = 'content';
        $newCustomField->rel_id = 13;
        $newCustomField->type = 'text';
        $newCustomField->name = 'This is the custom field name - EN';
        $newCustomField->placeholder = 'This is the placeholder - EN';
        $newCustomField->error_text = 'This is the error text - EN';

        $multilanguage = [];
        $multilanguage['name']['bg_BG'] = 'This is the custom field name - BG';
        $multilanguage['placeholder']['bg_BG'] = 'This is the placeholder - BG';
        $multilanguage['error_text']['bg_BG'] = 'This is the error text - BG';

        $newCustomField->multilanguage = $multilanguage;
        $newCustomField->save();

        $findCustomField = \MicroweberPackages\CustomField\Models\CustomField::find($newCustomField->id);

        $translations = $findCustomField->getTranslationsFormated();

        $this->assertEquals($translations['en_US']['name'], 'This is the custom field name - EN');
        $this->assertEquals($translations['en_US']['placeholder'], 'This is the placeholder - EN');
        $this->assertEquals($translations['en_US']['error_text'], 'This is the error text - EN');

        $this->assertEquals($translations['bg_BG']['name'], 'This is the custom field name - BG');
        $this->assertEquals($translations['bg_BG']['placeholder'], 'This is the placeholder - BG');
        $this->assertEquals($translations['bg_BG']['error_text'], 'This is the error text - BG');

        $this->assertEquals($findCustomField->name, 'This is the custom field name - EN');
        $this->assertEquals($findCustomField->placeholder, 'This is the placeholder - EN');
        $this->assertEquals($findCustomField->error_text, 'This is the error text - EN');

    }
}
