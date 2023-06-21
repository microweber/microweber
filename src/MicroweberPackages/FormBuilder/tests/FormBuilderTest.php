<?php

namespace MicroweberPackages\FormBuilder\tests;

use MicroweberPackages\Core\tests\TestCase;

class FormBuilderTest extends TestCase
{

    public function testFormBuilderElements()
    {
        /**
         * @var \MicroweberPackages\FormBuilder\FormElementBuilder $formBuilder
         */
        $formBuilder = app()->make(\MicroweberPackages\FormBuilder\FormElementBuilder::class);
        $elementTypes = [
            'text',
            'textarea',
            ];

        foreach ($elementTypes as $elementType) {
            $element = $formBuilder->make($elementType, 'title');
            $this->assertEquals($elementType, $element->getType());
        }


        $element = $formBuilder->make('text', 'title')
            ->id('form-builder-title-field')
            ->onkeyup('testSomething(this);')
            ->autocomplete(false);


        // dd($element);

    }


}
