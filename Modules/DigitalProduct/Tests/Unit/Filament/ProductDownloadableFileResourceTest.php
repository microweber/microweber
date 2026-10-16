<?php

namespace Modules\DigitalProduct\Tests\Unit\Filament;

use Livewire\Livewire;
use Modules\DigitalProduct\Filament\Resources\ProductDownloadableFileResource\Pages\CreateProductDownloadableFile;
use Modules\DigitalProduct\Filament\Resources\ProductDownloadableFileResource\Pages\ListProductDownloadableFiles;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;

class ProductDownloadableFileResourceTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    #[Test]
    public function index_page_loads_without_errors(): void
    {
        Livewire::test(ListProductDownloadableFiles::class)->assertSuccessful();
    }

    #[Test]
    public function create_page_loads_without_errors(): void
    {
        Livewire::test(CreateProductDownloadableFile::class)->assertSuccessful();
    }
}
