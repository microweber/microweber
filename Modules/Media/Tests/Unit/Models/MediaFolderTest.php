<?php

namespace Modules\Media\Tests\Unit\Models;

use Modules\Media\Models\MediaFolder;
use PHPUnit\Framework\TestCase;

class MediaFolderTest extends TestCase
{
    public function test_can_instantiate_model()
    {
        $folder = new MediaFolder();
        $this->assertInstanceOf(MediaFolder::class, $folder);
    }

    public function test_model_has_expected_attributes()
    {
        $folder = new MediaFolder();

        $this->assertTrue(method_exists($folder, 'getFillable'));
        $this->assertTrue(method_exists($folder, 'getCasts'));
    }

    public function test_model_has_expected_relationships()
    {
        $folder = new MediaFolder();

        $this->assertTrue(method_exists($folder, 'parent'));
        $this->assertTrue(method_exists($folder, 'children'));
        $this->assertTrue(method_exists($folder, 'media'));
        $this->assertTrue(method_exists($folder, 'allMedia'));
        $this->assertTrue(method_exists($folder, 'getAllChildFolderIds'));
    }

    public function test_model_has_expected_accessors()
    {
        $folder = new MediaFolder();

        $this->assertTrue(method_exists($folder, 'getFullPathAttribute'));
    }
}
