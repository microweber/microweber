<?php

namespace MicroweberPackages\CustomField\tests;

use MicroweberPackages\Core\tests\TestCase;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\CustomField\Traits\CustomFieldsTrait;


class CustomFieldTestModel extends Model
{
    use CustomFieldsTrait;

    protected $table = 'content';

}


class CustomFieldTestModelTest extends TestCase
{
    public function testAddCustomFieldToModel()
    {
        $newProduct = new CustomFieldTestModel();
        $newProduct->title = 'Samo Levski3';

        $newProduct->addCustomField(
            [
                'type'=>'price',
                'name'=>'цена на едро',
                'value'=>['цска', 'цска 1948'],
                'options'=>['team1' => 'levski', 'team2' => 'cska'],
            ]
        );

        $newProduct->addCustomField(
            [
                'type'=>'dropdown',
                'name'=>'цена 2',
                'value'=>['цска2', 'цска2 1948'],
                'options'=>['team1' => 'levski', 'team2' => 'cska'],
            ]
        );

        $newProduct->save();
        $this->assertEquals($newProduct->customField[0]->name, 'цена на едро');
//        foreach($newProduct->customField as $customField) {
//            $this->assertEquals($customField->name, 'цена на едро');
//        }
    }

    public function testSetCustomFieldToModel()
    {
        $newProduct = new CustomFieldTestModel();
        $newProduct->title = 'Samo Levski2';

        $newProduct->setCustomField(
            [
                'type'=>'price',
                'name'=>'цена на едро',
                'value'=>['цска', 'цска 1948'],
                'options'=>['team1' => 'levski', 'team2' => 'cska'],
            ]
        );

        $newProduct->save();

        foreach($newProduct->customField as $customField) {
            $this->assertEquals($customField->name, 'цена на едро');
            $customFieldValue = $customField->fieldValue;

            $this->assertEquals('цска', $customFieldValue[0]->value);
            $this->assertEquals('цска 1948', $customFieldValue[1]->value);
        }
    }

    public function testGetCustomFieldModel()
    {
        $newProduct = new CustomFieldTestModel();
        $newProduct->title = 'Samo Levski2';

        $newProduct->setCustomField(
            [
                'type'=>'dropdown',
                'name'=>'цвят',
                'value'=>['red', 'blue', 'зелен'],
                'options'=>[],

            ]
        );
       $newProduct->setCustomField(
            [
                'type'=>'dropdown',
                'name'=>'size',
                'value'=>['XL', 'M'],
                'options'=>[],

            ]
        );


        $newProduct->setCustomField(
            [
                'type'=>'dropdown',
                'name'=>'material',
                'value'=>['jeans', 'cotton'],
                'options'=>[],

            ]
        );


        $newProduct->save();


       // Article::withAnyTag(['Gardening','Cooking'])->get(); // fetch articles with any tag listed



        $prod = CustomFieldTestModel::whereCustomField([
         'цвят'=>'red',
            'size'=>'M',
            'material'=>'jeans',
        ])->get();

    }
}
