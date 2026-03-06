<?php

namespace Modules\Content\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;

use Tests\TestCase;

class DataAttributesTest extends TestCase
{

    public function setUp():void
    {
        parent::setUp();

        // set permission to save custom fields (normally available to admin users)
        mw()->database_manager->extended_save_set_permission(true);
    }

    #[Test]

    public function it_save(): void {
        $params = array(
            'title' => 'My post with data attributes test',
            'content_type' => 'post',
            'attribute_something' => 'hello there',
            'attribute_something_else' => 'hello there 2',
            'is_active' => 1, );

        $id = save_content($params);
        $attributes = content_attributes($id);
        $this->assertEquals(intval($id) > 0, true);


        $this->assertEquals($attributes['something'], 'hello there');
        $this->assertEquals($attributes['something_else'], 'hello there 2');
    }
}
