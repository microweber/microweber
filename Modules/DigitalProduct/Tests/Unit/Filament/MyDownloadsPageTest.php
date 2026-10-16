<?php

namespace Modules\DigitalProduct\Tests\Unit\Filament;

use Livewire\Livewire;
use Modules\DigitalProduct\Filament\Pages\MyDownloads;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;

class MyDownloadsPageTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel('profile');
    }

    #[Test]
    public function my_downloads_page_renders_without_errors(): void
    {
        Livewire::test(MyDownloads::class)->assertSuccessful();
    }
}
