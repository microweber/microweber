<?php

namespace Modules\Attributes\Tests\Unit;

use Modules\Attributes\Models\Attribute;
use Modules\Attributes\Concerns\HasAttributes;
use Tests\TestCase;

class AttributesTest extends TestCase
{

    protected $testModel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->testModel = new class {
            use HasAttributes;

            public $id = 1;

            public function save()
            {
                // Simulate saving the model
            }
        };
    }

    public function testSetAttribute()
    {
        $this->testModel->setAttribute('color', 'blue');

        $attribute = Attribute::where('rel_type', get_class($this->testModel))
            ->where('rel_id', $this->testModel->id)
            ->where('attribute_name', 'color')
            ->first();

        $this->assertNotNull($attribute);
        $this->assertEquals('blue', $attribute->attribute_value);
    }

    public function testGetAttribute()
    {
        $this->testModel->setAttribute('size', 'large');

        $value = $this->testModel->getAttribute('size');

        $this->assertEquals('large', $value);
    }

    public function testGetAttributesList()
    {
        $this->testModel->setAttribute('height', 'tall');
        $this->testModel->setAttribute('width', 'wide');

        $attributes = $this->testModel->getAttributesList();

        $this->assertArrayHasKey('height', $attributes);
        $this->assertArrayHasKey('width', $attributes);
        $this->assertEquals('tall', $attributes['height']);
        $this->assertEquals('wide', $attributes['width']);
    }
}
