<?php

namespace Modules\ContentDataVariant\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\ContentDataVariant\Models\ContentDataVariant;
use Modules\Currency\Models\Currency;
use Tests\TestCase;

class ContentDataVariantModelTest extends TestCase
{


    public function testCreateContentDataVariant()
    {
        $data = [
            'rel_type' => 'example_type_' . uniqid(),
            'rel_id' => 1,
            'custom_field_id' => 1,
            'custom_field_value_id' => 1,
        ];

        $contentDataVariant = ContentDataVariant::create($data);

        $this->assertDatabaseHas('content_data_variants', $data);
        $this->assertInstanceOf(ContentDataVariant::class, $contentDataVariant);

        // delete
        $contentDataVariant->delete();
        $this->assertDatabaseMissing('content_data_variants', $data);
    }
}
