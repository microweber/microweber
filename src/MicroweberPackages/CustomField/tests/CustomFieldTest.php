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
    public function testCustomFieldTestModel()
    {
        $newProduct = new CustomFieldTestModel();
        $newProduct->title = 'Samo Levski';

        $newProduct->addCustomField(
            [
                'type'=>'price',
                'name'=>'цена на едро',
                'value'=>['цска', 'цска 1948'],
                'options'=>['team1' => 'levski', 'team2' => 'cska'],
            ]

        );

        $newProduct->save();
       // dd($newProduct->customField);
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
}
