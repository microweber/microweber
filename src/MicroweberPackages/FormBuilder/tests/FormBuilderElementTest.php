<?php

namespace MicroweberPackages\FormBuilder\tests;

use MicroweberPackages\Core\tests\TestCase;

class FormBuilderElementTest extends TestCase
{

    public function testFormBuilderElementsTypes()
    {

        /**
         * @var \MicroweberPackages\FormBuilder\FormElementBuilder $formBuilder
         */
        $formBuilder = app()->make(\MicroweberPackages\FormBuilder\FormElementBuilder::class);
        $elementTypes = [
            'text',
            'textarea',
            'select',
            'label',
            'checkbox',
            'radio',
            'date',
            'email',
            'file',
            'mw-editor',
            'mw-module-settings',

        ];

        foreach ($elementTypes as $elementType) {
            $element = $formBuilder->make($elementType, 'name of field');
            $this->assertEquals($elementType, $element->getType());
        }


    }


}
