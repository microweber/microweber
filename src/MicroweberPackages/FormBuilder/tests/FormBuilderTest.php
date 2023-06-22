<?php

namespace MicroweberPackages\FormBuilder\tests;

use MicroweberPackages\Core\tests\TestCase;

class FormBuilderTest extends TestCase
{

    public function testFormBuilderElements()
    {
        return;
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
            $attributes = $element->getAttributes();
            $this->assertEquals($elementType, $attributes['type']);
        }


    }


}
