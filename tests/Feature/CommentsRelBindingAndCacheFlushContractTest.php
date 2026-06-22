<?php

declare(strict_types=1);

namespace Tests\Feature;

use Livewire\Livewire;
use Modules\Comments\Livewire\UserCommentListComponent;
use Modules\Comments\Livewire\UserCommentReplyComponent;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Two defects found exercising the named modules end-to-end.
 *
 * task-2026-06-06-cmtbind — the comments template passed snake_case attributes
 *   (:rel_id / :rel_type) to the <livewire:comments::user-comment-list> and
 *   <livewire:comments::user-comment-reply> components, but the components' mount
 *   parameters are camelCase ($relId / $relType). Livewire binds kebab-case
 *   attributes to camelCase params (and exact names) — it does NOT map snake_case,
 *   so relId/relType stayed null. A null relId made the manager return the first
 *   page of EVERY comment in the table, so every post/product showed a global
 *   comment feed; and the reply form saved new comments with rel_id = null.
 *   The template now uses kebab-case (:rel-id / :rel-type / :show-user-avatar / ...).
 *   The list view also gained a @forelse empty branch (was a bare @foreach).
 *
 * task-2026-06-06-tagpath — TaggableFileStore::flush() referenced $tagPath when
 *   deleting the tag-MAP file. $tagPath is scoped to the inner foreach over the
 *   tag's detail files; when a tag had no detail files it was undefined, emitting
 *   "Undefined variable $tagPath" warnings on every tagged-cache flush. The block
 *   now uses $tagMapPath (the file it actually deletes).
 */
class CommentsRelBindingAndCacheFlushContractTest extends TestCase
{
    #[Test]
    public function comments_template_uses_kebab_case_livewire_attributes(): void
    {
        $tpl = (string) file_get_contents(base_path(
            'Modules/Comments/resources/views/templates/default.blade.php'
        ));

        // Both components receive the relation via kebab-case attributes.
        $this->assertStringContainsString(':rel-type="$rel_type"', $tpl);
        $this->assertStringContainsString(':rel-id="$rel_id"', $tpl);

        // The broken snake_case bindings must be gone (strip Blade comments first so
        // the explanatory prose above the tags doesn't self-match the assertion).
        $stripped = preg_replace('~\{\{--.*?--\}\}~s', '', $tpl);
        $this->assertSame(
            0,
            preg_match('/<livewire:comments[^>]*:rel_id=/s', (string) $stripped),
            'The comments template must not bind snake_case :rel_id (Livewire never maps it to $relId).'
        );
        $this->assertSame(
            0,
            preg_match('/<livewire:comments[^>]*:rel_type=/s', (string) $stripped),
            'The comments template must not bind snake_case :rel_type.'
        );
    }

    #[Test]
    public function kebab_case_binding_actually_reaches_the_list_component(): void
    {
        $fqcn = \Modules\Content\Models\Content::class;
        $component = Livewire::test(UserCommentListComponent::class, [
            'rel-id' => 987654321, // an id with no comments
            'rel-type' => $fqcn,
        ]);

        $this->assertSame(987654321, $component->get('relId'));
        $this->assertSame($fqcn, $component->get('relType'));
    }

    #[Test]
    public function reply_component_binds_rel_id_so_new_comments_are_not_orphaned(): void
    {
        $fqcn = \Modules\Content\Models\Content::class;
        $component = Livewire::test(UserCommentReplyComponent::class, [
            'rel-id' => 987654321,
            'rel-type' => $fqcn,
        ]);

        // The reply form copies the bound ids into its state for the create() call.
        $this->assertSame(987654321, $component->get('state.rel_id'));
        $this->assertSame($fqcn, $component->get('state.rel_type'));
    }

    #[Test]
    public function snake_case_attributes_do_not_bind_regression_guard(): void
    {
        // Documents WHY the fix was needed: the old snake_case attribute leaves
        // relId null, which is the global-leak / orphaned-save defect.
        $component = Livewire::test(UserCommentListComponent::class, [
            'rel_id' => 987654321,
            'rel_type' => \Modules\Content\Models\Content::class,
        ]);

        $this->assertNull($component->get('relId'));
    }

    #[Test]
    public function comment_list_view_has_an_empty_state_branch(): void
    {
        $view = (string) file_get_contents(base_path(
            'Modules/Comments/resources/views/livewire/user-comment-list-component.blade.php'
        ));

        $this->assertStringContainsString('@forelse', $view,
            'The list must use @forelse so an empty result renders the no-comments partial.');
        $this->assertStringContainsString("@include('modules.comments::no-comments')", $view,
            'The empty branch must include the no-comments partial.');
        // Pagination only when there is more than one page.
        $this->assertStringContainsString('hasPages()', $view);
    }

    #[Test]
    public function cache_flush_deletes_the_tag_map_path_not_an_undefined_var(): void
    {
        $src = (string) file_get_contents(base_path(
            'packages/microweber-taggable-file-cache/src/TaggableFileStore.php'
        ));

        // Locate the tag-map deletion block and assert it operates on $tagMapPath.
        $anchor = strpos($src, '$tagMapPath = $this->_getTagMapPathByName($tag);');
        $this->assertNotFalse($anchor, 'The tag-map deletion block must exist.');
        $block = substr($src, $anchor, 800);
        // Strip comments so the explanatory note doesn't satisfy the negative check.
        $block = (string) preg_replace('~/\*.*?\*/~s', '', $block);
        $block = (string) preg_replace('~//[^\n]*~', '', $block);

        $this->assertMatchesRegularExpression(
            '/in_array\(\$tagMapPath,\s*\$this->deletedFilesCache\)/',
            $block,
            'The dedup check must reference $tagMapPath.'
        );
        $this->assertMatchesRegularExpression(
            '/\$this->deletedFilesCache\[\]\s*=\s*\$tagMapPath/',
            $block,
            'The dedup record must reference $tagMapPath.'
        );
        $this->assertSame(
            0,
            preg_match('/in_array\(\$tagPath,\s*\$this->deletedFilesCache\)/', $block),
            'The tag-map block must no longer reference the inner-loop $tagPath.'
        );
    }
}
