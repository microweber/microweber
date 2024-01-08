<?php

namespace MicroweberPackages\CustomField\tests;

use MicroweberPackages\CustomField\Models\CustomField;
use MicroweberPackages\CustomField\Models\CustomFieldValue;
use MicroweberPackages\Multilanguage\Models\MultilanguageTranslations;
use MicroweberPackages\Multilanguage\tests\MultilanguageTestBase;

class CustomFieldMultilanguageTest extends MultilanguageTestBase
{

    public function testAddCustomFieldValueAsArray()
    {

//        CustomField::truncate();
//        CustomFieldValue::truncate();
//        MultilanguageTranslations::truncate();

        $simpleCustomField = new CustomField();
        $simpleCustomField->rel_type = 'content';
        $simpleCustomField->rel_id = 13;
        $simpleCustomField->type = 'dropdown';
        $simpleCustomField->name = 'This is the custom field name - EN';
        $simpleCustomField->save();

        $customFieldId = $simpleCustomField->id;

        $newCustomFieldValue = new CustomFieldValue();
        $newCustomFieldValue->custom_field_id = $customFieldId;
        $newCustomFieldValue->value = 'This is the custom field value array #1 - EN';
        $multilanguage = [];
        $multilanguage['value']['bg_BG'] = 'This is the custom field value array #1 - BG';
        $multilanguage['position']['bg_BG'] = 3;
        $newCustomFieldValue->multilanguage = $multilanguage;
        $newCustomFieldValue->save();


        $newCustomFieldValue = new CustomFieldValue();
        $newCustomFieldValue->custom_field_id = $customFieldId;
        $newCustomFieldValue->value = 'This is the custom field value array #2 - EN';
        $multilanguage = [];
        $multilanguage['value']['bg_BG'] = 'This is the custom field value array #2 - BG';
        $multilanguage['position']['bg_BG'] = 2;
        $newCustomFieldValue->multilanguage = $multilanguage;
        $newCustomFieldValue->save();



        $newCustomFieldValue = new CustomFieldValue();
        $newCustomFieldValue->custom_field_id = $customFieldId;
        $newCustomFieldValue->value = 'This is the custom field value array #3 - EN';
        $multilanguage = [];
        $multilanguage['value']['bg_BG'] = 'This is the custom field value array #3 - BG';
        $multilanguage['position']['bg_BG'] = 1;
        $newCustomFieldValue->multilanguage = $multilanguage;
        $newCustomFieldValue->save();


    }

    public function testAddCustomFieldValue()
    {

        $simpleCustomField = new CustomField();
        $simpleCustomField->rel_type = 'content';
        $simpleCustomField->rel_id = 13;
        $simpleCustomField->type = 'text';
        $simpleCustomField->name = 'This is the custom field name - EN';
        $simpleCustomField->save();
        $customFieldId = $simpleCustomField->id;

        $newCustomFieldValue = new CustomFieldValue();
        $newCustomFieldValue->custom_field_id = $customFieldId;
        $newCustomFieldValue->value = 'This is the custom field value - EN';

        $multilanguage = [];
        $multilanguage['value']['bg_BG'] = 'This is the custom field value - BG';

        $newCustomFieldValue->multilanguage = $multilanguage;
        $newCustomFieldValue->save();

        $customFieldValueId = $newCustomFieldValue->id;

        // Check if the custom field value is saved
        $findCustomFieldValue = CustomFieldValue::find($customFieldValueId);
        $this->assertEquals('This is the custom field value - EN', $findCustomFieldValue->value);
        $translations = $findCustomFieldValue->getTranslationsFormated();

        $this->assertEquals($translations['en_US']['value'], 'This is the custom field value - EN');
        $this->assertEquals($translations['bg_BG']['value'], 'This is the custom field value - BG');


        // Try to edit the custom field existing multilanguage value
        $findCustomFieldValue = \MicroweberPackages\CustomField\Models\CustomFieldValue::find($customFieldValueId);
        $findCustomFieldValue->value = 'This is the NEW custom field value - EN';

        $multilanguage = [];
        $multilanguage['value']['bg_BG'] = 'This is the NEW custom field value - BG';

        $findCustomFieldValue->multilanguage = $multilanguage;
        $findCustomFieldValue->save();

        $findCustomFieldValue = \MicroweberPackages\CustomField\Models\CustomFieldValue::find($customFieldValueId);
        $this->assertEquals('This is the NEW custom field value - EN', $findCustomFieldValue->value);

        $translations = $findCustomFieldValue->getTranslationsFormated();

        $this->assertEquals($translations['en_US']['value'], 'This is the NEW custom field value - EN');
        $this->assertEquals($translations['bg_BG']['value'], 'This is the NEW custom field value - BG');


    }

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
