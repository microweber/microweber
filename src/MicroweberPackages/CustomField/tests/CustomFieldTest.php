<?php

namespace MicroweberPackages\CustomField\tests;

use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Product\Product;

class CustomFieldTestModelTest extends TestCase
{
    public function testAddCustomFieldToModel()
    {
        $newProduct = new Product();
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
        $this->assertEquals($newProduct->customField[1]->name, 'цена 2');
    }

    public function testSetCustomFieldToModel()
    {
        $newProduct = new Product();
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

        $this->assertEquals($newProduct->customField[0]->name, 'цена на едро');
    }

    public function testGetCustomFieldModel()
    {
        $newProduct = new Product();
        $newProduct->title = 'Samo Levski3';

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


        $prod = Product::whereCustomField([
         'цвят'=>'red',
            'size'=>'M',
            'material'=>'jeans',
        ])->get();

    }
}
