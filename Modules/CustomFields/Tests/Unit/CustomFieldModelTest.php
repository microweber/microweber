<?php

namespace Modules\CustomFields\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;

use Tests\TestCase;
use Modules\CustomFields\Models\CustomField;
use Modules\CustomFields\Models\CustomFieldValue;
use Modules\Product\Models\Product;

class CustomFieldModelTest extends TestCase
{
    #[Test]
    public function it_add_custom_field_to_model(): void {
        Product::where('title', 'Samo Levski3')->delete();
        CustomField::query()->delete();

        $newProduct = new Product();
        $newProduct->title = 'Samo Levski3';

        $newProduct->setCustomFields(
            [
                [
                    'type' => 'price',
                    'name' => 'цена на едро',
                    'value' => ['цска', 'цска 1948'],
                    'options' => ['team1' => 'levski', 'team2' => 'cska'],
                ],
                [
                    'type' => 'dropdown',
                    'name' => 'цена 2',
                    'value' => ['цска2', 'цска2 1948'],
                    'options' => ['team1' => 'levski', 'team2' => 'cska'],
                ]
            ]
        );

        $newProduct->save();


        $this->assertEquals($newProduct->customField[0]->name, 'цена на едро');
        $this->assertEquals($newProduct->customField[1]->name, 'цена 2');
    }

    #[Test]

    public function it_set_custom_field_to_model(): void {
        $newProduct = new Product();
        $newProduct->title = 'Samo Levski2';

        $newProduct->setCustomField(
            [
                'type' => 'price',
                'name' => 'цена на едро',
                'value' => ['цска', 'цска 1948'],
                'options' => ['team1' => 'levski', 'team2' => 'cska'],
            ]
        );

        $newProduct->save();

        $this->assertEquals($newProduct->customField[0]->name, 'цена на едро');
    }

    #[Test]

    public function it_custom_field_model_value_attribute(): void {
        $customField = new CustomField();
        $customField->type = 'text';
        $customField->name = 'Test Text Field';
        $customField->value = 'Test Value';
        $customField->save();

        $customFieldFind = CustomField::where('id', $customField->id)->first();

        $this->assertEquals($customField->value, $customFieldFind->value);

    }

    /**
     * Regression: CustomFieldValue::save() must return parent::save()'s bool.
     * Filament's relationship repeater does `$relationship->save($record)`, and
     * Eloquent's HasMany::save() returns `$model->save() ? $model : false`.
     * When save() returned null the relationship returned false and
     * Repeater::callAfterCreate() threw a TypeError (saving a custom field with
     * dropdown/radio/checkbox values 500'd).
     */
    #[Test]
    public function it_custom_field_value_save_returns_bool_and_persists_via_relationship(): void
    {
        $customField = new CustomField();
        $customField->type = 'dropdown';
        $customField->name = 'Regression Options';
        $customField->save();

        // Bare save() returns a real bool.
        $value = new CustomFieldValue();
        $value->custom_field_id = $customField->id;
        $value->value = 'Option A';
        $this->assertTrue($value->save());

        // The relationship save path (what Filament calls) returns the model,
        // never false — so callAfterCreate() receives a Model.
        $viaRelationship = $customField->fieldValue()->save(
            (new CustomFieldValue())->fill(['value' => 'Option B'])
        );
        $this->assertInstanceOf(CustomFieldValue::class, $viaRelationship);
        $this->assertNotFalse($viaRelationship);

        // An explicitly-set position (reorder via ->orderColumn('position')) is
        // honored rather than overwritten by the auto-increment default.
        $viaRelationship->position = 0;
        $this->assertTrue($viaRelationship->save());
        $this->assertSame(0, (int) $viaRelationship->fresh()->position);
    }

    #[Test]

    public function it_custom_field_model_values_attribute(): void {
        $customField = new CustomField();
        $customField->type = 'dropdown';
        $customField->name = 'Test dropdown Field';
        $customField->values = [
            'Option 1',
            'Option 2',

        ];
        $customField->save();

        $customFieldFind = CustomField::find($customField->id);


        $this->assertEquals($customField->values, $customFieldFind->values);

    }

    #[Test]

    public function it_get_custom_field_model(): void {
        $title = 'Samo Levski3' . uniqid();

        $newProduct = new Product();
        $newProduct->title = $title;

        $newProduct->setCustomField(
            [
                'type' => 'dropdown',
                'name' => 'цвят',
                'value' => ['red', 'blue', 'зелен'],
                'options' => [],

            ]
        );
        $newProduct->setCustomField(
            [
                'type' => 'dropdown',
                'name' => 'size',
                'value' => ['XL', 'M'],
                'options' => [],

            ]
        );


        $some_random = 'some-material-' . uniqid();

        $name = 'material-' . uniqid();
        $newProduct->setCustomField(
            [
                'type' => 'dropdown',
                'name' => $name,
                'value' => ['jeans', 'cotton', $some_random],
                'options' => [],

            ]
        );


        $newProduct->save();

        $prod = Product::whereCustomField([
            $name => $some_random,
        ])->first();

        $this->assertEquals($prod->title, $title);


        // get all fields
        $prod = Product::find($newProduct->id);
        $fields = $prod->customField()->get();
        $this->assertNotEmpty($fields);

        foreach ($fields as $field) {
            $this->assertNotEmpty($field->fieldValue()->get());
        }
        $fields_arr = $fields->toArray();
        $name_keys = array_column($fields_arr, 'name_key');
        $this->assertContains($name, $name_keys);
        $this->assertContains('size', $name_keys);
        $this->assertContains('cviat', $name_keys);

        $names = array_column($fields_arr, 'name');
        $this->assertContains($name, $names);
        $this->assertContains('size', $names);
        $this->assertContains('цвят', $names);

    }
}
