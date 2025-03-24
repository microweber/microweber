<?php

namespace Modules\CustomFields\Tests\Unit;

use MicroweberPackages\Core\tests\TestCase;
use Modules\CustomFields\Models\CustomField;
use Modules\Product\Models\Product;

class CustomFieldRenderTest extends TestCase
{
    public function testRenderingTextField()
    {
        $customField = new CustomField();
        $customField->type = 'text';
        $customField->name = 'Test Text Field';
        $customField->value = 'Test Value';
        $customField->save();

        $field = new \Modules\CustomFields\Fields\Text();

        $field->setData($customField->toArray());


        $output = $field->render();


        $this->assertStringContainsString('type="text"', $output);
        $this->assertStringContainsString('name="test-text-field"', $output);
        $this->assertStringContainsString('value="Test Value"', $output);
    }

    public function testRenderingTextAreaField()
    {
        $customField = new CustomField();
        $customField->type = 'text';
        $customField->name = 'Test TextArea';
        $customField->value = 'Test TextArea Value';
        $customField->options = [
            'as_text_area' => true,
            'rows' => 5
        ];
        $customField->save();

        $field = new \Modules\CustomFields\Fields\Text();
        $field->setData($customField->toArray());

        $output = $field->render();

        $this->assertStringContainsString('<textarea', $output);
        $this->assertStringContainsString('rows="5"', $output);
        $this->assertStringContainsString('Test TextArea Value', $output);
    }

    public function testRenderingEmailField()
    {
        $customField = new CustomField();
        $customField->type = 'email';
        $customField->name = 'Test Email';
        $customField->value = 'test@email.com';
        $customField->save();

        $field = new \Modules\CustomFields\Fields\Email();
        $field->setData($customField->toArray());


        $output = $field->render();

        $this->assertStringContainsString('type="email"', $output);
        $this->assertStringContainsString('value="test@email.com"', $output);
    }

    public function testRenderingNumberField()
    {
        $customField = new CustomField();
        $customField->type = 'number';
        $customField->name = 'Test Number';
        $customField->value = '42';
        $customField->save();

        $field = new \Modules\CustomFields\Fields\Number();
        $field->setData($customField->toArray());


        $output =   $field->render();

        $this->assertStringContainsString('type="number"', $output);
        $this->assertStringContainsString('value="42"', $output);
    }

    public function testRenderingCheckboxField()
    {
        $customField = new CustomField();
        $customField->type = 'checkbox';
        $customField->name = 'Test Checkbox';
        $customField->values = [
            'Yes'
        ];
        $customField->save();

        $field = new \Modules\CustomFields\Fields\Checkbox();
        $field->setData($customField->toArray());


        $output = $field->render();


        $this->assertStringContainsString('type="checkbox"', $output);
        $this->assertStringContainsString('value="Yes"', $output);
    }

    public function testRenderingDropdownField()
    {
        $customField = new CustomField();
        $customField->type = 'dropdown';
        $customField->name = 'Test Dropdown';
        $customField->values = [

            'option1' => 'Option 1',
            'option2' => 'Option 2'

        ];
        $customField->save();

        $field = new \Modules\CustomFields\Fields\Dropdown();
        $field->setData($customField->toArray());


        $output =  $field->render();


        $this->assertStringContainsString('<select', $output);
        $this->assertStringContainsString('Option 1', $output);
        $this->assertStringContainsString('Option 2', $output);
    }

    public function testRenderingDateField()
    {
        $customField = new CustomField();
        $customField->type = 'date';
        $customField->name = 'Test Date';
        $customField->value = '2024-03-20';
        $customField->save();

        $field = new \Modules\CustomFields\Fields\Date();
        $field->setData($customField->toArray());


        $output = $field->render();


        $this->assertStringContainsString('type="date"', $output);
        $this->assertStringContainsString('value="2024-03-20"', $output);
    }

    public function testRenderingColorField()
    {
        $customField = new CustomField();
        $customField->type = 'color';
        $customField->name = 'Test Color';
        $customField->value = '#FF0000';
        $customField->save();

        $field = new \Modules\CustomFields\Fields\Color();
        $field->setData($customField->toArray());


        $output =  $field->render();


        $this->assertStringContainsString('type="color"', $output);
        $this->assertStringContainsString('value="#FF0000"', $output);
    }
}
