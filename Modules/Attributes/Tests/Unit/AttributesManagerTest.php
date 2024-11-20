<?php

namespace Modules\Attributes\Tests\Unit;

use Modules\Attributes\Models\Attribute;
use Modules\Attributes\Repositories\AttributesManager;
use Tests\TestCase;


class AttributesManagerTest extends TestCase
{

    protected $attributesManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->attributesManager = new AttributesManager();
    }

    public function testGetValues()
    {
        Attribute::where('rel_type', 'TestModel')->delete();

        Attribute::create([
            'attribute_name' => 'color',
            'attribute_value' => 'blue',
            'rel_type' => 'TestModel',
            'rel_id' => 1,
        ]);

        $params = ['rel_type' => 'TestModel', 'rel_id' => 1];
        $values = $this->attributesManager->getValues($params);

        $this->assertArrayHasKey('color', $values);
        $this->assertEquals('blue', $values['color']);
    }

    public function testGet()
    {
        // cleanup
        Attribute::where('rel_type', 'TestModel')->delete();

        Attribute::create([
            'attribute_name' => 'size',
            'attribute_value' => 'large',
            'rel_type' => 'TestModel',
            'rel_id' => 1,
        ]);

        $params = ['rel_type' => 'TestModel', 'rel_id' => 1];
        $attributes = $this->attributesManager->get($params);

        $this->assertCount(1, $attributes);
        $this->assertEquals('size', $attributes[0]['attribute_name']);
        $this->assertEquals('large', $attributes[0]['attribute_value']);
    }

    public function testSave()
    {
        $data = [
            'attribute_name' => 'height',
            'attribute_value' => 'tall',
            'rel_type' => 'TestModel',
            'rel_id' => 1,
        ];

        $attribute = $this->attributesManager->save($data);

        $this->assertNotNull($attribute);
        $this->assertEquals('height', $attribute->attribute_name);
        $this->assertEquals('tall', $attribute->attribute_value);
    }
}
