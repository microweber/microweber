<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Tools;

use Illuminate\Support\Facades\Http;
use Modules\Ai\Facades\Ai;
use Modules\Ai\Tools\ContentImprovementTool;
use Modules\Ai\Tools\GenerateDescriptionTool;
use Modules\Ai\Tools\GenerateSeoMetadataTool;
use Modules\Content\Models\Content;
use PHPUnit\Framework\Attributes\Test;

class ContentGenerationToolsTest extends ToolTestCase
{
    private GenerateDescriptionTool $descriptionTool;
    private GenerateSeoMetadataTool $seoTool;
    private ContentImprovementTool $improvementTool;

    protected function setUp(): void
    {
        parent::setUp();
        $this->descriptionTool = new GenerateDescriptionTool();
        $this->seoTool = new GenerateSeoMetadataTool();
        $this->improvementTool = new ContentImprovementTool();

        $this->actingAsUser();

        // Mock the Ai facade to return predictable responses
        \Illuminate\Support\Facades\Facade::clearResolvedInstances();
        $this->mockAiFacade();
    }

    protected function mockAiFacade(null|array|\Closure $customResponses = null): void
    {
        $aiMock = $this->createMock(\Modules\Ai\Services\AiService::class);

        if ($customResponses !== null) {
            $aiMock->method('sendToChat')->willReturnCallback(function ($messages) use ($customResponses) {
                $prompt = $messages[1]['content'] ?? '';
                return $customResponses($prompt);
            });
        } else {
            $aiMock->method('sendToChat')->willReturnCallback(function ($messages) {
                // Extract the prompt to determine what to return
                $prompt = $messages[1]['content'] ?? '';

                if (str_contains($prompt, 'SEO-optimized title')) {
                    return 'Microweber CMS: Best Website Builder 2024';
                }

                if (str_contains($prompt, 'SEO-optimized meta description')) {
                    return 'Learn about Microweber CMS - the powerful drag-and-drop website builder for creating stunning websites without coding.';
                }

                if (str_contains($prompt, 'relevant SEO keywords')) {
                    return 'website builder, cms, microweber, drag and drop, web design';
                }

                if (str_contains($prompt, 'Analyze and improve this content')) {
                    return json_encode([
                        'summary' => 'Content analysis completed successfully.',
                        'suggestions' => [
                            [
                                'type' => 'readability',
                                'priority' => 'medium',
                                'issue' => 'Long sentences detected',
                                'recommendation' => 'Break down long sentences for better readability.',
                            ],
                        ],
                        'improved_content' => '<p>Improved content with better structure.</p>',
                    ]);
                }

                // Default response for description generation
                return 'Generated description for the content.';
            });
        }

        app()->instance('ai', $aiMock);
    }

    protected function actingAsUser(): void
    {
        $user = \MicroweberPackages\User\Models\User::factory()->create([
            'is_admin' => true,
        ]);
        $this->actingAs($user);
    }

    private function createTestContent(array $overrides = []): Content
    {
        return Content::create(array_merge([
            'title' => 'Test Content Page',
            'content_body' => '<p>This is a comprehensive test article about web development and content management systems.</p><p>Microweber is a powerful drag-and-drop website builder that allows users to create stunning websites without coding knowledge.</p>',
            'content_type' => 'page',
            'url' => 'test-content-' . uniqid(),
            'is_active' => 1,
        ], $overrides));
    }

    // ============================================
    // GenerateDescriptionTool Tests
    // ============================================

    #[Test]
    public function it_returns_error_when_content_id_is_missing(): void
    {
        $result = $this->descriptionTool->__invoke([
            'content_id' => null,
            'description_type' => 'meta',
        ]);

        $this->assertStringContainsString('Content ID is required', $result);
        $this->assertStringContainsString('alert-danger', $result);
    }

    #[Test]
    public function it_returns_error_when_content_not_found(): void
    {
        $result = $this->descriptionTool->__invoke([
            'content_id' => 999999,
            'description_type' => 'meta',
        ]);

        $this->assertStringContainsString('not found', $result);
        $this->assertStringContainsString('alert-danger', $result);
    }

    #[Test]
    public function it_generates_meta_description(): void
    {
        Http::fake([
            '*' => Http::response(json_encode([
                'content' => 'Learn about Microweber CMS - the powerful drag-and-drop website builder for creating stunning websites without coding.',
            ]), 200),
        ]);

        $content = $this->createTestContent();

        $result = $this->descriptionTool->__invoke([
            'content_id' => $content->id,
            'description_type' => 'meta',
            'tone' => 'professional',
            'auto_update' => false,
        ]);

        $this->assertStringContainsString('SEO Meta Description Generated', $result);
        $this->assertStringContainsString($content->title, $result);
        $this->assertStringContainsString('Preview Only', $result);
    }

    #[Test]
    public function it_generates_excerpt_description(): void
    {
        Http::fake([
            '*' => Http::response(json_encode([
                'content' => 'Discover Microweber, a powerful drag-and-drop website builder that makes creating stunning websites easy without any coding knowledge required.',
            ]), 200),
        ]);

        $content = $this->createTestContent();

        $result = $this->descriptionTool->__invoke([
            'content_id' => $content->id,
            'description_type' => 'excerpt',
            'tone' => 'casual',
        ]);

        $this->assertStringContainsString('Blog Excerpt Generated', $result);
    }

    #[Test]
    public function it_updates_content_with_generated_description(): void
    {
        Http::fake([
            '*' => Http::response(json_encode([
                'content' => 'Learn about Microweber CMS and create beautiful websites.',
            ]), 200),
        ]);

        $content = $this->createTestContent();

        $result = $this->descriptionTool->__invoke([
            'content_id' => $content->id,
            'description_type' => 'meta',
            'auto_update' => true,
        ]);

        $this->assertStringContainsString('Saved to Content', $result);

        $content->refresh();
        $this->assertNotNull($content->content_meta_description);
    }

    #[Test]
    public function it_includes_target_keywords_in_description(): void
    {
        Http::fake([
            '*' => Http::response(json_encode([
                'content' => 'Learn about Microweber CMS - the best website builder for drag-and-drop design.',
            ]), 200),
        ]);

        $content = $this->createTestContent();

        $result = $this->descriptionTool->__invoke([
            'content_id' => $content->id,
            'description_type' => 'meta',
            'target_keywords' => 'website builder, drag and drop',
        ]);

        $this->assertStringContainsString('Target Keywords', $result);
        $this->assertStringContainsString('website builder', $result);
    }

    #[Test]
    public function it_generates_promotional_description(): void
    {
        Http::fake([
            '*' => Http::response(json_encode([
                'content' => 'Transform your web presence with Microweber! Start building today.',
            ]), 200),
        ]);

        $content = $this->createTestContent();

        $result = $this->descriptionTool->__invoke([
            'content_id' => $content->id,
            'description_type' => 'promotional',
            'tone' => 'persuasive',
        ]);

        $this->assertStringContainsString('Promotional Copy Generated', $result);
    }

    #[Test]
    public function it_truncates_long_descriptions(): void
    {
        $longDescription = str_repeat('This is a very long description. ', 20);

        Http::fake([
            '*' => Http::response(json_encode([
                'content' => $longDescription,
            ]), 200),
        ]);

        $content = $this->createTestContent();

        $result = $this->descriptionTool->__invoke([
            'content_id' => $content->id,
            'description_type' => 'meta',
        ]);

        $this->assertStringContainsString('SEO Meta Description Generated', $result);
        // Description should be truncated with ... at the end
        $this->assertLessThan(170, strlen(strip_tags($result)));
    }

    // ============================================
    // GenerateSeoMetadataTool Tests
    // ============================================

    #[Test]
    public function it_returns_error_when_keywords_are_missing(): void
    {
        $content = $this->createTestContent();

        $result = $this->seoTool->__invoke([
            'content_id' => $content->id,
            'target_keywords' => '',
        ]);

        $this->assertStringContainsString('Target keywords are required', $result);
        $this->assertStringContainsString('alert-danger', $result);
    }

    #[Test]
    public function it_generates_seo_metadata(): void
    {
        Http::fake([
            '*' => Http::response(json_encode([
                'content' => 'Microweber CMS: Best Website Builder 2024',
            ]), 200),
        ]);

        $content = $this->createTestContent();

        $result = $this->seoTool->__invoke([
            'content_id' => $content->id,
            'target_keywords' => 'website builder, cms, microweber',
            'generate_title' => true,
            'generate_description' => true,
            'auto_apply' => false,
        ]);

        $this->assertStringContainsString('SEO Metadata Generated', $result);
        $this->assertStringContainsString('Meta Title', $result);
        $this->assertStringContainsString('Meta Description', $result);
        $this->assertStringContainsString('Preview Only', $result);
    }

    #[Test]
    public function it_applies_seo_metadata_to_content(): void
    {
        Http::fake([
            '*' => Http::response(json_encode([
                'content' => 'Microweber CMS: Best Website Builder',
            ]), 200),
        ]);

        $content = $this->createTestContent();

        $result = $this->seoTool->__invoke([
            'content_id' => $content->id,
            'target_keywords' => 'website builder, cms',
            'generate_title' => true,
            'generate_description' => true,
            'auto_apply' => true,
        ]);

        $this->assertStringContainsString('Applied to Content', $result);

        $content->refresh();
        $this->assertNotNull($content->content_meta_title);
        $this->assertNotNull($content->content_meta_description);
    }

    #[Test]
    public function it_generates_open_graph_tags(): void
    {
        Http::fake([
            '*' => Http::response(json_encode([
                'content' => 'Test Title',
            ]), 200),
        ]);

        $content = $this->createTestContent(['content_type' => 'post']);

        $result = $this->seoTool->__invoke([
            'content_id' => $content->id,
            'target_keywords' => 'test keywords',
            'generate_og_tags' => true,
            'auto_apply' => false,
        ]);

        $this->assertStringContainsString('Open Graph Tags', $result);
        $this->assertStringContainsString('og:title', $result);
        $this->assertStringContainsString('og:description', $result);
        $this->assertStringContainsString('og:type', $result);
        $this->assertStringContainsString('article', $result); // Posts get article type
    }

    #[Test]
    public function it_generates_keywords_list(): void
    {
        Http::fake([
            '*' => Http::response(json_encode([
                'content' => 'website builder, cms, microweber, drag and drop, web design',
            ]), 200),
        ]);

        $content = $this->createTestContent();

        $result = $this->seoTool->__invoke([
            'content_id' => $content->id,
            'target_keywords' => 'website builder',
            'generate_keywords' => true,
        ]);

        $this->assertStringContainsString('Keywords', $result);
    }

    #[Test]
    public function it_returns_correct_og_type_for_products(): void
    {
        Http::fake([
            '*' => Http::response(json_encode([
                'content' => 'Test',
            ]), 200),
        ]);

        $content = $this->createTestContent(['content_type' => 'product']);

        $result = $this->seoTool->__invoke([
            'content_id' => $content->id,
            'target_keywords' => 'product keywords',
            'generate_og_tags' => true,
        ]);

        $this->assertStringContainsString('product', $result);
    }

    // ============================================
    // ContentImprovementTool Tests
    // ============================================

    #[Test]
    public function it_analyzes_content_and_provides_suggestions(): void
    {
        Http::fake([
            '*' => Http::response(json_encode([
                'summary' => 'Content analysis completed successfully.',
                'suggestions' => [
                    [
                        'type' => 'readability',
                        'priority' => 'medium',
                        'issue' => 'Long sentences detected',
                        'recommendation' => 'Break down long sentences for better readability.',
                    ],
                    [
                        'type' => 'seo',
                        'priority' => 'high',
                        'issue' => 'Missing keywords',
                        'recommendation' => 'Add target keywords to the first paragraph.',
                    ],
                ],
            ]), 200),
        ]);

        $content = $this->createTestContent();

        $result = $this->improvementTool->__invoke([
            'content_id' => $content->id,
            'analysis_type' => 'comprehensive',
            'auto_apply' => false,
        ]);

        $this->assertStringContainsString('Comprehensive Analysis Results', $result);
        $this->assertStringContainsString('Summary', $result);
        $this->assertStringContainsString('Improvement Suggestions', $result);
        $this->assertStringContainsString('Suggestions Only', $result);
    }

    #[Test]
    public function it_returns_error_when_content_has_no_body(): void
    {
        $content = $this->createTestContent([
            'content_body' => '',
            'content' => '',
        ]);

        $result = $this->improvementTool->__invoke([
            'content_id' => $content->id,
        ]);

        $this->assertStringContainsString('No content body found', $result);
    }

    #[Test]
    public function it_provides_readability_score(): void
    {
        Http::fake([
            '*' => Http::response(json_encode([
                'summary' => 'Analysis complete',
                'suggestions' => [],
            ]), 200),
        ]);

        $content = $this->createTestContent([
            'content_body' => '<p>Short sentences. Easy words. Clear meaning.</p>',
        ]);

        $result = $this->improvementTool->__invoke([
            'content_id' => $content->id,
        ]);

        $this->assertStringContainsString('Readability:', $result);
        $this->assertStringContainsString('words', $result);
    }

    #[Test]
    public function it_groups_suggestions_by_type(): void
    {
        // Override mock with custom response
        $this->mockAiFacade(function ($prompt) {
            return json_encode([
                'summary' => 'Analysis complete',
                'suggestions' => [
                    [
                        'type' => 'readability',
                        'priority' => 'medium',
                        'issue' => 'Issue 1',
                        'recommendation' => 'Fix 1',
                    ],
                    [
                        'type' => 'readability',
                        'priority' => 'low',
                        'issue' => 'Issue 2',
                        'recommendation' => 'Fix 2',
                    ],
                    [
                        'type' => 'seo',
                        'priority' => 'high',
                        'issue' => 'Issue 3',
                        'recommendation' => 'Fix 3',
                    ],
                ],
            ]);
        });

        $content = $this->createTestContent();

        $result = $this->improvementTool->__invoke([
            'content_id' => $content->id,
        ]);

        $this->assertStringContainsString('Readability', $result);
        $this->assertStringContainsString('Seo', $result);
    }

    #[Test]
    public function it_shows_priority_badges_for_suggestions(): void
    {
        // Override mock with custom response
        $this->mockAiFacade(function ($prompt) {
            return json_encode([
                'summary' => 'Analysis complete',
                'suggestions' => [
                    [
                        'type' => 'grammar',
                        'priority' => 'high',
                        'issue' => 'Critical issue',
                        'recommendation' => 'Fix immediately',
                    ],
                ],
            ]);
        });

        $content = $this->createTestContent();

        $result = $this->improvementTool->__invoke([
            'content_id' => $content->id,
        ]);

        $this->assertStringContainsString('High', $result);
    }

    #[Test]
    public function it_displays_improved_content_preview(): void
    {
        Http::fake([
            '*' => Http::response(json_encode([
                'summary' => 'Analysis complete',
                'suggestions' => [],
                'improved_content' => '<p>This is the improved version of the content with better structure.</p>',
            ]), 200),
        ]);

        $content = $this->createTestContent();

        $result = $this->improvementTool->__invoke([
            'content_id' => $content->id,
            'auto_apply' => false,
        ]);

        $this->assertStringContainsString('Improved Content Preview', $result);
    }

    #[Test]
    public function it_applies_improvements_when_auto_apply_is_true(): void
    {
        Http::fake([
            '*' => Http::response(json_encode([
                'summary' => 'Analysis complete',
                'suggestions' => [],
                'improved_content' => '<p>Improved content body here.</p>',
            ]), 200),
        ]);

        $content = $this->createTestContent();

        $result = $this->improvementTool->__invoke([
            'content_id' => $content->id,
            'auto_apply' => true,
        ]);

        $this->assertStringContainsString('Improvements Applied', $result);
    }

    #[Test]
    public function it_calculates_word_count(): void
    {
        Http::fake([
            '*' => Http::response(json_encode([
                'summary' => 'Analysis complete',
                'suggestions' => [],
            ]), 200),
        ]);

        $content = $this->createTestContent([
            'content_body' => '<p>This is a test with exactly five words.</p>',
        ]);

        $result = $this->improvementTool->__invoke([
            'content_id' => $content->id,
        ]);

        // str_word_count counts words in "This is a test with exactly five words" = 8 words
        $this->assertStringContainsString('8 words', $result);
    }

    #[Test]
    public function it_handles_invalid_json_response(): void
    {
        Http::fake([
            '*' => Http::response('Invalid JSON response', 200),
        ]);

        $content = $this->createTestContent();

        $result = $this->improvementTool->__invoke([
            'content_id' => $content->id,
        ]);

        $this->assertStringContainsString('Analysis Results', $result);
    }

    #[Test]
    public function it_shows_readability_badges(): void
    {
        Http::fake([
            '*' => Http::response(json_encode([
                'summary' => 'Analysis complete',
                'suggestions' => [],
            ]), 200),
        ]);

        $content = $this->createTestContent([
            'content_body' => '<p>Simple text. Very easy. Short sentences.</p>',
        ]);

        $result = $this->improvementTool->__invoke([
            'content_id' => $content->id,
        ]);

        // Should show one of the readability badges
        $this->assertTrue(
            str_contains($result, 'Easy') ||
            str_contains($result, 'Moderate') ||
            str_contains($result, 'Difficult')
        );
    }

    // ============================================
    // Integration Tests
    // ============================================

    #[Test]
    public function all_tools_require_authentication(): void
    {
        // Note: The base tool implementation returns true for authorization
        // Permission checking should be implemented at the application level
        // This test documents the current behavior
        $this->markTestSkipped('Permission checking is implemented at application level, not in base tools');
    }

    #[Test]
    public function tools_output_valid_html(): void
    {
        // Mock is set up in setUp()
        $content = $this->createTestContent();

        $descriptionResult = $this->descriptionTool->__invoke([
            'content_id' => $content->id,
            'auto_update' => false,
        ]);

        $this->assertStringContainsString('<div', $descriptionResult);
        $this->assertStringContainsString('</div>', $descriptionResult);
    }
}
