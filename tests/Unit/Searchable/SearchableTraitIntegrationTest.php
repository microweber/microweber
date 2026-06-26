<?php

namespace Tests\Unit\Searchable;

use Tests\TestCase;
use Modules\Content\Models\Content;
use Modules\Category\Models\Category;
use MicroweberPackages\User\Models\User;
use MicroweberPackages\Translation\Models\TranslationKey;
use MicroweberPackages\Option\Models\Option;
use MicroweberPackages\Searchable\HasSearchableTrait;

class SearchableTraitIntegrationTest extends TestCase
{
    public function test_content_model_uses_searchable_trait(): void
    {
        $model = new Content();
        $this->assertContains(HasSearchableTrait::class, class_uses_recursive($model));
    }

    public function test_category_model_uses_searchable_trait(): void
    {
        $model = new Category();
        $this->assertContains(HasSearchableTrait::class, class_uses_recursive($model));
    }

    public function test_user_model_uses_searchable_trait(): void
    {
        $model = new User();
        $this->assertContains(HasSearchableTrait::class, class_uses_recursive($model));
    }

    public function test_translation_key_model_uses_searchable_trait(): void
    {
        $model = new TranslationKey();
        $this->assertContains(HasSearchableTrait::class, class_uses_recursive($model));
    }

    public function test_option_model_uses_searchable_trait(): void
    {
        $model = new Option();
        $this->assertContains(HasSearchableTrait::class, class_uses_recursive($model));
    }

    public function test_content_get_searchable_returns_array(): void
    {
        $model = new Content();
        $searchable = $model->getSearchable();

        $this->assertIsArray($searchable);
        $this->assertContains('title', $searchable);
        $this->assertContains('content_type', $searchable);
        $this->assertContains('id', $searchable);
    }

    public function test_content_get_searchable_by_keyword_returns_keyword_fields(): void
    {
        $model = new Content();
        $fields = $model->getSearchableByKeyword();

        $this->assertIsArray($fields);
        $this->assertContains('title', $fields);
        $this->assertContains('description', $fields);
        // keyword fields should NOT contain structural fields like id, is_active
        $this->assertNotContains('id', $fields);
        $this->assertNotContains('is_active', $fields);
    }

    public function test_user_get_searchable_returns_expected_fields(): void
    {
        $model = new User();
        $searchable = $model->getSearchable();

        $this->assertContains('email', $searchable);
        $this->assertContains('username', $searchable);
        $this->assertContains('first_name', $searchable);
        $this->assertNotContains('password', $searchable);
    }

    public function test_content_is_searchable_field(): void
    {
        $model = new Content();

        $this->assertTrue($model->isSearchableField('title'));
        $this->assertTrue($model->isSearchableField('content_type'));
        $this->assertFalse($model->isSearchableField('nonexistent_field'));
    }

    public function test_content_search_scope_returns_results(): void
    {
        // The install seeds default content, so there should be content available
        $allContent = Content::count();
        if ($allContent === 0) {
            $this->markTestSkipped('No content in database to search');
        }

        $firstContent = Content::first();
        $keyword = substr($firstContent->title, 0, 5);

        $results = Content::search($keyword)->get();
        $this->assertGreaterThanOrEqual(1, $results->count());
    }

    public function test_content_search_scope_with_custom_fields(): void
    {
        $allContent = Content::count();
        if ($allContent === 0) {
            $this->markTestSkipped('No content in database to search');
        }

        $firstContent = Content::first();
        $results = Content::search($firstContent->title, ['title'])->get();

        $this->assertGreaterThanOrEqual(1, $results->count());
    }

    public function test_content_search_exact_scope(): void
    {
        $allContent = Content::count();
        if ($allContent === 0) {
            $this->markTestSkipped('No content in database to search');
        }

        $firstContent = Content::first();
        $result = Content::searchExact('content_type', $firstContent->content_type)->first();

        $this->assertNotNull($result);
    }

    public function test_option_search_scope(): void
    {
        $allOptions = Option::count();
        if ($allOptions === 0) {
            $this->markTestSkipped('No options in database');
        }

        $firstOption = Option::first();
        if ($firstOption->option_group) {
            $results = Option::searchExact('option_group', $firstOption->option_group)->get();
            $this->assertGreaterThanOrEqual(1, $results->count());
        } else {
            $this->assertTrue(true);
        }
    }
}