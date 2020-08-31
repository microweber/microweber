<?php

namespace MicroweberPackages\CustomField\tests;

use MicroweberPackages\Core\tests\TestCase;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\CustomField\HasCustomFieldsTrait;


class CustomFieldTestModel extends Model
{
    use HasCustomFieldsTrait;

    protected $table = 'content';

}


class CustomFieldTestModelTest extends TestCase
{
    public function xxxxtestCustomFieldTestModel()
    {
//        $newProduct = new CustomFieldTestModel();
//        $newProduct->title = 'Samo Levski2';
//
//        $newProduct->addCustomField(
//            [
//                'type'=>'price',
//                'name'=>'цена на едро',
//                'value'=>['цска', 'цска 1948'],
//                'options'=>['team1' => 'levski', 'team2' => 'cska'],
//            ]
//        );
//
//        $newProduct->save();
//
//        $newProduct->setCustomField([
//            'type'=>'price',
//            'name'=>'цена на едро',
//            'value'=>['цска2', 'цска2 1948'],
//            'options'=>['team' => 'levski2', 'team3' => 'cska2'],
//        ]);

       //dd($newProduct->customField);
        //$newProduct->save();

        $product = CustomFieldTestModel::find(92);
        $product->setCustomField([
            'type'=>'price',
            'name'=>'цена на едро',
            'value'=>['цска3', 'цска3 1948'],
            'options'=>['team' => 'levski3', 'team3' => 'cska3'],
        ]);
        $product->save();
        dd($product->customField);

        //$this->assertEquals($newProduct->customField, 'Test car bmw');



        //    $customField = new CustomField();
//    $customField->value = [23, 'blue4'];
//    $customField->type = 'price';
//    $customField->options = ['team1' => 'levski', 'team2' => 'cska'];
//    $customField->name = 'цена на едро';
//    $customField->rel_type = 'content';
//    $customField->rel_id = 19;
//    $customField->save();

    }

    public function testAddCustomFieldToModel()
    {
        $newProduct = new CustomFieldTestModel();
        $newProduct->title = 'Samo Levski2';

        $newProduct->addCustomField(
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
        }
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
}
