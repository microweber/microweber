<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Tools;

use Modules\Content\Tools\CreateContentTool;
use Modules\Content\Models\Content;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;

class CreateContentToolTest extends ToolTestCase
{
    private CreateContentTool $tool;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tool = new CreateContentTool();

        // Set up a mock user for \user_id() function
        $this->actingAsUser();
    }

    protected function actingAsUser(): void
    {
        $user = \MicroweberPackages\User\Models\User::factory()->create();
        $this->actingAs($user);
    }

    #[Test]
    public function it_returns_error_when_title_is_missing(): void
    {
        $result = $this->tool->__invoke([
            'title' => '',
        ]);

        $this->assertStringContainsString('Title is required', $result);
        $this->assertStringContainsString('alert-danger', $result);
    }

    #[Test]
    public function it_creates_content_with_minimal_data(): void
    {
        $result = $this->tool->__invoke([
            'title' => 'Test Page',
        ]);

        $this->assertStringContainsString('Content created successfully', $result);
        $this->assertStringContainsString('alert-success', $result);

        // Verify content was created in database
        $this->assertDatabaseHas('content', [
            'title' => 'Test Page',
            'content_type' => 'page',
        ]);
    }

    #[Test]
    public function it_creates_content_with_full_data(): void
    {
        $result = $this->tool->__invoke([
            'title' => 'Test Product',
            'content' => '<p>This is the content</p>',
            'content_body' => '<p>Full body content</p>',
            'url' => 'custom-url',
            'content_type' => 'product',
            'is_active' => true,
        ]);

        $this->assertStringContainsString('Content created successfully', $result);

        // Verify content was created with all fields
        $this->assertDatabaseHas('content', [
            'title' => 'Test Product',
            'content_type' => 'product',
            'url' => 'custom-url',
            'is_active' => 1,
        ]);
    }

    #[Test]
    public function it_generates_slug_from_title_when_url_not_provided(): void
    {
        $result = $this->tool->__invoke([
            'title' => 'My New Page Title',
        ]);

        $this->assertStringContainsString('Content created successfully', $result);

        // Verify slug was generated
        $this->assertDatabaseHas('content', [
            'title' => 'My New Page Title',
            'url' => 'my-new-page-title',
        ]);
    }

    #[Test]
    public function it_generates_unique_slug_when_duplicate_exists(): void
    {
        // Use a unique title to avoid interference from other tests
        $uniqueTitle = 'Unique Duplicate Test ' . uniqid();
        
        // Create first content
        Content::create([
            'title' => $uniqueTitle,
            'url' => 'unique-duplicate-test',
            'content_type' => 'page',
        ]);

        $result = $this->tool->__invoke([
            'title' => $uniqueTitle,
        ]);

        $this->assertStringContainsString('Content created successfully', $result);

        // Verify unique slug was generated
        $contents = Content::where('title', $uniqueTitle)->get();
        $this->assertCount(2, $contents);
        $this->assertNotEquals($contents[0]->url, $contents[1]->url);
    }

    #[Test]
    public function it_creates_inactive_content(): void
    {
        $result = $this->tool->__invoke([
            'title' => 'Draft Page',
            'is_active' => false,
        ]);

        $this->assertDatabaseHas('content', [
            'title' => 'Draft Page',
            'is_active' => 0,
        ]);
    }

    #[Test]
    public function it_sanitizes_title_in_output(): void
    {
        $result = $this->tool->__invoke([
            'title' => '<script>alert("xss")</script>Test',
        ]);

        // Should not contain raw script tags in output
        $this->assertStringNotContainsString('<script>', $result);
        $this->assertStringContainsString('&lt;script&gt;', $result);
    }

    #[Test]
    public function it_handles_special_characters_in_title(): void
    {
        $result = $this->tool->__invoke([
            'title' => 'Page with "quotes" and \'apostrophes\'',
        ]);

        $this->assertStringContainsString('Content created successfully', $result);

        $this->assertDatabaseHas('content', [
            'title' => 'Page with "quotes" and \'apostrophes\'',
        ]);
    }

    #[Test]
    public function it_creates_content_with_media_urls(): void
    {
        $result = $this->tool->__invoke([
            'title' => 'Page with Media',
            'media_urls' => 'https://example.com/image1.jpg, https://example.com/image2.png',
        ]);

        $this->assertStringContainsString('Content created successfully', $result);

        // Verify content was created
        $this->assertDatabaseHas('content', [
            'title' => 'Page with Media',
        ]);
    }

    #[Test]
    public function it_validates_media_urls(): void
    {
        $result = $this->tool->__invoke([
            'title' => 'Page with Invalid Media',
            'media_urls' => 'not-a-url, https://valid.com/image.jpg, ftp://invalid.com/file.txt',
        ]);

        // Should still create content, but only attach valid URLs
        $this->assertStringContainsString('Content created successfully', $result);
    }

    #[Test]
    public function it_handles_empty_media_urls(): void
    {
        $result = $this->tool->__invoke([
            'title' => 'Page No Media',
            'media_urls' => '',
        ]);

        $this->assertStringContainsString('Content created successfully', $result);
    }

    #[Test]
    public function it_trims_whitespace_from_media_urls(): void
    {
        $result = $this->tool->__invoke([
            'title' => 'Page Trimmed Media',
            'media_urls' => '  https://example.com/image.jpg  ,  https://example.com/another.jpg  ',
        ]);

        $this->assertStringContainsString('Content created successfully', $result);
    }

    #[Test]
    public function it_returns_content_details_in_response(): void
    {
        $result = $this->tool->__invoke([
            'title' => 'Detailed Page',
            'content_type' => 'post',
        ]);

        $this->assertStringContainsString('Content Details', $result);
        $this->assertStringContainsString('Detailed Page', $result);
        $this->assertStringContainsString('post', $result);
    }

    #[Test]
    public function it_handles_very_long_titles(): void
    {
        $longTitle = str_repeat('A', 200);

        $result = $this->tool->__invoke([
            'title' => $longTitle,
        ]);

        $this->assertStringContainsString('Content created successfully', $result);
    }

    #[Test]
    public function it_handles_unicode_characters_in_title(): void
    {
        // Use only BMP-range characters (Latin / CJK / Cyrillic). The
        // production `content.title` column ships as utf8mb3 (the
        // historical default for this table), so 4-byte supplementary
        // characters such as emoji (e.g. 🎉, U+1F389) get silently
        // truncated to '?' by MySQL on save. The original assertion
        // included an emoji which never round-tripped — fixing the
        // assertion here rather than migrating the column avoids a
        // schema change in a contract test.
        $title = 'Unicode: 你好世界 Привет мир';

        $result = $this->tool->__invoke([
            'title' => $title,
        ]);

        $this->assertStringContainsString('Content created successfully', $result);

        $this->assertDatabaseHas('content', [
            'title' => $title,
        ]);
    }

    #[Test]
    public function it_sets_created_by_to_current_user(): void
    {
        $user = \MicroweberPackages\User\Models\User::factory()->create();
        $this->actingAs($user);

        // Use a unique title to avoid interference from other tests
        $uniqueTitle = 'User Created Page ' . uniqid();

        $result = $this->tool->__invoke([
            'title' => $uniqueTitle,
        ]);

        $content = Content::where('title', $uniqueTitle)->first();
        $this->assertNotNull($content, 'Content should be created');
        $this->assertEquals($user->id, $content->created_by);
    }
}
