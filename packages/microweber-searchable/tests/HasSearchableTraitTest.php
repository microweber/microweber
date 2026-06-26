<?php

namespace MicroweberPackages\Searchable\Tests;

use MicroweberPackages\Searchable\Tests\Stubs\SearchableItem;
use MicroweberPackages\Searchable\Tests\Stubs\MinimalSearchableItem;
use MicroweberPackages\Searchable\Tests\Stubs\EmptySearchableItem;

class HasSearchableTraitTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Seed test data
        SearchableItem::create([
            'title' => 'Laravel Framework',
            'description' => 'A web application framework with expressive syntax',
            'content' => 'Laravel is a PHP framework for web artisans',
            'email' => 'info@laravel.com',
            'status' => 'active',
            'secret' => 'hidden-value',
        ]);

        SearchableItem::create([
            'title' => 'Symfony Components',
            'description' => 'Reusable PHP components',
            'content' => 'Symfony provides components for building web apps',
            'email' => 'hello@symfony.com',
            'status' => 'active',
            'secret' => 'another-secret',
        ]);

        SearchableItem::create([
            'title' => 'CodeIgniter Legacy',
            'description' => 'A lightweight PHP framework',
            'content' => 'CodeIgniter is a simple and elegant toolkit',
            'email' => 'contact@codeigniter.com',
            'status' => 'inactive',
            'secret' => 'ci-secret',
        ]);
    }

    // ── getSearchable() ──

    public function test_get_searchable_returns_defined_fields(): void
    {
        $model = new SearchableItem();
        $searchable = $model->getSearchable();

        $this->assertIsArray($searchable);
        $this->assertContains('title', $searchable);
        $this->assertContains('description', $searchable);
        $this->assertContains('content', $searchable);
        $this->assertContains('email', $searchable);
        $this->assertContains('status', $searchable);
        $this->assertNotContains('secret', $searchable);
    }

    public function test_get_searchable_returns_empty_array_when_not_defined(): void
    {
        $model = new EmptySearchableItem();
        $this->assertSame([], $model->getSearchable());
    }

    // ── getSearchableByKeyword() ──

    public function test_get_searchable_by_keyword_returns_keyword_fields(): void
    {
        $model = new SearchableItem();
        $keywordFields = $model->getSearchableByKeyword();

        $this->assertIsArray($keywordFields);
        $this->assertContains('title', $keywordFields);
        $this->assertContains('description', $keywordFields);
        $this->assertContains('content', $keywordFields);
        $this->assertNotContains('email', $keywordFields);
    }

    public function test_get_searchable_by_keyword_falls_back_to_searchable(): void
    {
        $model = new MinimalSearchableItem();
        $keywordFields = $model->getSearchableByKeyword();

        // Should fall back to $searchable since $searchableByKeyword is not defined
        $this->assertEquals(['title', 'email'], $keywordFields);
    }

    public function test_get_searchable_by_keyword_returns_empty_when_nothing_defined(): void
    {
        $model = new EmptySearchableItem();
        $this->assertSame([], $model->getSearchableByKeyword());
    }

    // ── isSearchableField() ──

    public function test_is_searchable_field_returns_true_for_searchable(): void
    {
        $model = new SearchableItem();
        $this->assertTrue($model->isSearchableField('title'));
        $this->assertTrue($model->isSearchableField('email'));
    }

    public function test_is_searchable_field_returns_false_for_non_searchable(): void
    {
        $model = new SearchableItem();
        $this->assertFalse($model->isSearchableField('secret'));
        $this->assertFalse($model->isSearchableField('nonexistent'));
    }

    // ── scopeSearch() ──

    public function test_search_scope_finds_matching_records(): void
    {
        $results = SearchableItem::search('Laravel')->get();

        $this->assertCount(1, $results);
        $this->assertEquals('Laravel Framework', $results->first()->title);
    }

    public function test_search_scope_finds_across_multiple_fields(): void
    {
        // 'framework' appears in descriptions of Laravel and CodeIgniter
        $results = SearchableItem::search('framework')->get();

        $this->assertCount(2, $results);
    }

    public function test_search_scope_returns_empty_for_no_match(): void
    {
        $results = SearchableItem::search('nonexistent-keyword-xyz')->get();

        $this->assertCount(0, $results);
    }

    public function test_search_scope_uses_keyword_fields_by_default(): void
    {
        // 'info@laravel.com' is only in email field, which is NOT in searchableByKeyword
        $results = SearchableItem::search('info@laravel.com')->get();

        $this->assertCount(0, $results);
    }

    public function test_search_scope_with_custom_fields_override(): void
    {
        // Override to search email specifically
        $results = SearchableItem::search('info@laravel.com', ['email'])->get();

        $this->assertCount(1, $results);
        $this->assertEquals('Laravel Framework', $results->first()->title);
    }

    public function test_search_scope_with_empty_keyword_returns_all(): void
    {
        $results = SearchableItem::search('')->get();

        $this->assertCount(3, $results);
    }

    public function test_search_scope_case_insensitive_like(): void
    {
        // LIKE is case-insensitive in SQLite by default
        $results = SearchableItem::search('laravel')->get();

        $this->assertCount(1, $results);
    }

    public function test_search_scope_partial_match(): void
    {
        $results = SearchableItem::search('Lara')->get();

        $this->assertCount(1, $results);
        $this->assertEquals('Laravel Framework', $results->first()->title);
    }

    public function test_search_scope_can_be_chained_with_other_queries(): void
    {
        $results = SearchableItem::where('status', 'active')
            ->search('framework')
            ->get();

        // Only Laravel is active + has 'framework' in keyword fields
        // (CodeIgniter has 'framework' in description but is 'inactive')
        $this->assertCount(1, $results);
        $this->assertEquals('Laravel Framework', $results->first()->title);
    }

    // ── scopeSearchExact() ──

    public function test_search_exact_scope_finds_exact_match(): void
    {
        $result = SearchableItem::searchExact('email', 'info@laravel.com')->first();

        $this->assertNotNull($result);
        $this->assertEquals('Laravel Framework', $result->title);
    }

    public function test_search_exact_scope_ignores_non_searchable_field(): void
    {
        $results = SearchableItem::searchExact('secret', 'hidden-value')->get();

        // 'secret' is not in $searchable, so query should return all records
        $this->assertCount(3, $results);
    }

    public function test_search_exact_scope_returns_empty_for_no_match(): void
    {
        $result = SearchableItem::searchExact('email', 'nobody@example.com')->first();

        $this->assertNull($result);
    }

    // ── MinimalSearchableItem (no $searchableByKeyword) ──

    public function test_minimal_model_search_uses_searchable_fields(): void
    {
        // MinimalSearchableItem only has 'title' and 'email' as searchable
        $results = MinimalSearchableItem::search('Laravel')->get();

        $this->assertCount(1, $results);
    }

    public function test_minimal_model_does_not_search_non_searchable_fields(): void
    {
        // 'Reusable' only appears in description, which is NOT in MinimalSearchableItem's $searchable
        $results = MinimalSearchableItem::search('Reusable')->get();

        $this->assertCount(0, $results);
    }

    // ── EmptySearchableItem (no $searchable at all) ──

    public function test_empty_model_search_returns_all(): void
    {
        // With no searchable fields, search scope should return all records
        $results = EmptySearchableItem::search('anything')->get();

        $this->assertCount(3, $results);
    }

    // ── Edge cases ──

    public function test_search_with_special_characters(): void
    {
        SearchableItem::create([
            'title' => 'Test with % percent',
            'description' => 'Has special chars',
            'email' => 'test@example.com',
            'status' => 'active',
        ]);

        $results = SearchableItem::search('% percent')->get();
        $this->assertGreaterThanOrEqual(1, $results->count());
    }

    public function test_multiple_search_scopes_chained(): void
    {
        $results = SearchableItem::search('framework')
            ->searchExact('status', 'active')
            ->get();

        // Only Laravel is active with 'framework'
        $this->assertCount(1, $results);
    }
}