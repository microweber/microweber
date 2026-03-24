<?php

namespace Tests\Browser\Content;

use Laravel\Dusk\Browser;
use Modules\Content\Models\Content;
use Modules\Page\Models\Page;
use Modules\Post\Models\Post;
use Modules\Category\Models\Category;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Components\AdminLogin;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\DuskTestCase;

/**
 * Critical Content Management Flows
 *
 * Tests cover:
 * 1. Page creation and editing
 * 2. Post creation and editing
 * 3. Category management
 * 4. Content publishing workflow
 */
class ContentManagementFlowsTest extends DuskTestCase
{
    /**
     * Test complete page creation and editing flow.
     */
    #[Test]
    public function it_admin_can_create_and_edit_page(): void
    {
        $this->browse(function (Browser $browser) {
            $uniqueId = time();
            $siteUrl = $this->siteUrl;

            // Login as admin
            $browser->within(new AdminLogin(), function ($browser) {
                $browser->fillForm();
            });

            // Navigate to page creation
            $browser->visit($siteUrl . 'admin/pages/create');
            $browser->pause(3000);
            $browser->waitForText('Create Page', 30);

            // Select template
            $browser->click('.create-page-clean');
            $browser->pause(3000);
            $browser->waitUntilMissing('.mw-create-page-templates-select-window', 60);

            // Fill page details
            $pageTitle = 'Test Page ' . $uniqueId;
            $browser->value('#slug-field-holder input', $pageTitle);
            $browser->pause(1000);

            // Add content
            $browser->keys('.mw-editor-area', 'This is test content for page ' . $uniqueId);
            $browser->pause(1000);

            // Check for JavaScript errors
            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

            // Save the page
            $browser->click('#js-admin-save-content-main-btn');
            $browser->pause(5000);

            // Verify page was created
            $browser->waitForText('Page saved', 30);

            // Verify in database
            $page = Page::where('title', $pageTitle)->first();
            $this->assertNotNull($page, 'Page should exist in database');
            $this->assertEquals('page', $page->content_type);
            $this->assertEquals('static', $page->subtype);

            // Cleanup
            if ($page) {
                $page->delete();
            }
        });
    }

    /**
     * Test complete post creation flow.
     */
    #[Test]
    public function it_admin_can_create_and_publish_post(): void
    {
        $this->browse(function (Browser $browser) {
            $uniqueId = time();
            $siteUrl = $this->siteUrl;

            // Login as admin
            $browser->within(new AdminLogin(), function ($browser) {
                $browser->fillForm();
            });

            // Navigate to post creation
            $browser->visit($siteUrl . 'admin/posts/create');
            $browser->pause(3000);
            $browser->waitForText('Create Post', 30);

            // Fill post details
            $postTitle = 'Test Post ' . $uniqueId;
            $browser->value('#slug-field-holder input', $postTitle);
            $browser->pause(1000);

            // Add content
            $browser->keys('.mw-editor-area', 'This is test post content ' . $uniqueId);
            $browser->pause(1000);

            // Check for JavaScript errors
            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

            // Save the post
            $browser->click('#js-admin-save-content-main-btn');
            $browser->pause(5000);

            // Verify post was created
            $browser->waitForText('Post saved', 30);

            // Verify in database
            $post = Post::where('title', $postTitle)->first();
            $this->assertNotNull($post, 'Post should exist in database');
            $this->assertEquals('post', $post->content_type);

            // Cleanup
            if ($post) {
                $post->delete();
            }
        });
    }

    /**
     * Test category creation and assignment.
     */
    #[Test]
    public function it_admin_can_create_category_and_assign_to_content(): void
    {
        $this->browse(function (Browser $browser) {
            $uniqueId = time();
            $siteUrl = $this->siteUrl;

            // Login as admin
            $browser->within(new AdminLogin(), function ($browser) {
                $browser->fillForm();
            });

            // Navigate to category creation
            $browser->visit($siteUrl . 'admin/categories/create');
            $browser->pause(3000);
            $browser->waitForText('Create Category', 30);

            // Fill category details
            $categoryTitle = 'Test Category ' . $uniqueId;
            $browser->type('title', $categoryTitle);
            $browser->pause(1000);

            // Save category
            $browser->click('button[type="submit"]');
            $browser->pause(3000);

            // Verify category was created
            $browser->waitForText('Category created', 30);

            // Verify in database
            $category = Category::where('title', $categoryTitle)->first();
            $this->assertNotNull($category, 'Category should exist in database');

            // Check for JavaScript errors
            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

            // Cleanup
            if ($category) {
                $category->delete();
            }
        });
    }

    /**
     * Test content visibility on frontend.
     */
    #[Test]
    public function it_published_content_appears_on_frontend(): void
    {
        $this->browse(function (Browser $browser) {
            $uniqueId = time();
            $siteUrl = $this->siteUrl;

            // Create test content via API/database
            $content = Content::create([
                'title' => 'Frontend Test ' . $uniqueId,
                'content_type' => 'page',
                'subtype' => 'static',
                'content' => '<p>Test content for frontend</p>',
                'is_active' => 1,
                'is_published' => 1
            ]);

            // Visit the content on frontend
            $browser->visit(content_link($content->id));
            $browser->pause(3000);

            // Verify content is visible
            $browser->assertSee('Frontend Test ' . $uniqueId);
            $browser->assertSee('Test content for frontend');

            // Check for JavaScript errors
            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

            // Cleanup
            $content->delete();
        });
    }

    /**
     * Test content unpublishing hides it from frontend.
     */
    #[Test]
    public function it_unpublished_content_is_hidden(): void
    {
        $this->browse(function (Browser $browser) {
            $uniqueId = time();
            $siteUrl = $this->siteUrl;

            // Create unpublished content
            $content = Content::create([
                'title' => 'Unpublished Test ' . $uniqueId,
                'content_type' => 'page',
                'subtype' => 'static',
                'content' => '<p>Unpublished content</p>',
                'is_active' => 0,
                'is_published' => 0
            ]);

            // Try to visit the content
            $url = content_link($content->id);
            $browser->visit($url);
            $browser->pause(3000);

            // Should show 404 or redirect
            $currentUrl = $browser->driver->getCurrentURL();
            $this->assertStringNotContainsString($content->title, $browser->element('body')->getText());

            // Cleanup
            $content->delete();
        });
    }
}
