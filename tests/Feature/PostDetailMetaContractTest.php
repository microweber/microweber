<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-82 / AI-69 / TICKET-KK — post-detail meta + landmark
 * regression coverage.
 *
 * Pins the four AI-69 deliverables on
 * `Templates/Bootstrap/resources/views/post.blade.php`:
 *
 *   1. ARTICLE WRAPPER — the post body lives in a real <article>
 *      landmark with Schema.org BlogPosting microdata so SR users
 *      can jump to it via landmark nav and search engines understand
 *      the page shape.
 *   2. VISIBLE TIME — <time datetime="<ISO-8601>" itemprop=datePublished>
 *      replaces the bare <p> date paragraph; aria-label carries the
 *      human-readable string for SR users.
 *   3. AUTHOR BYLINE — Schema.org Person microdata-marked byline,
 *      gracefully hidden when the user_id lookup orphaned out.
 *   4. RELATED POSTS — <aside aria-labelledby> carrying the Posts
 *      module with template=related_posts. Internal-link signal +
 *      reader discovery path beyond the single article.
 *
 * Style after the cycle-52..81 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class PostDetailMetaContractTest extends TestCase
{
    private string $postBlade;

    protected function setUp(): void
    {
        parent::setUp();
        $this->postBlade = file_get_contents(base_path(
            'Templates/Bootstrap/resources/views/post.blade.php'
        ));
    }

    #[Test]
    public function post_body_is_wrapped_in_article_landmark_with_schema_org(): void
    {
        // The outer wrapper is now <article> (was <div>). Pin the
        // exact opening shape including Schema.org BlogPosting type.
        $this->assertStringContainsString(
            '<article class="blog-inner-page',
            $this->postBlade,
            'post.blade.php: outer wrapper must be <article> (was <div>)'
        );
        $this->assertStringContainsString(
            'itemtype="https://schema.org/BlogPosting"',
            $this->postBlade,
            'post.blade.php: <article> must declare Schema.org BlogPosting microdata for SEO'
        );
        // Closing tag swap mirrored.
        $this->assertStringContainsString(
            '</article>',
            $this->postBlade,
            'post.blade.php: must close the <article> wrapper'
        );

        // Negative: the prior `<div class="blog-inner-page">` shape
        // must be gone — otherwise the migration was botched and we
        // have BOTH wrappers nested.
        $this->assertDoesNotMatchRegularExpression(
            '/<div\\s+class="blog-inner-page\\s+py-5"/',
            $this->postBlade,
            'post.blade.php: prior <div class="blog-inner-page py-5"> wrapper must be replaced (not augmented) by <article>'
        );
    }

    #[Test]
    public function date_is_emitted_via_time_element_with_iso_datetime(): void
    {
        // Bare <p>YYYY MMM DD</p> is gone; replaced by <time datetime>
        // with both Schema.org datePublished AND an aria-label
        // human-readable form.
        $this->assertMatchesRegularExpression(
            '/<time\\s+datetime="\\{\\{\\s*\\$postCreatedAtIso\\s*\\}\\}"/s',
            $this->postBlade,
            'post.blade.php: <time> must carry datetime="{{ $postCreatedAtIso }}"'
        );
        $this->assertStringContainsString(
            'itemprop="datePublished"',
            $this->postBlade,
            'post.blade.php: <time> must declare itemprop="datePublished"'
        );
        $this->assertStringContainsString(
            'aria-label="{{ __(\'Published on\') }}',
            $this->postBlade,
            'post.blade.php: <time> must carry an aria-label with the translatable "Published on" prefix'
        );

        // ISO and human strings are computed via date() in the PHP
        // setup block; pin both shapes.
        $this->assertStringContainsString(
            "\$postCreatedAtIso = \$postCreatedAt ? date('c', \$postCreatedAt) : '';",
            $this->postBlade,
            'post.blade.php: must compute postCreatedAtIso via date(c, ...) — RFC 3339 ISO-8601 is what <time datetime> consumers expect'
        );
        $this->assertStringContainsString(
            "\$postCreatedAtHuman = \$postCreatedAt ? date('d M Y', \$postCreatedAt) : '';",
            $this->postBlade,
            'post.blade.php: must compute postCreatedAtHuman via date(d M Y, ...) for the visible label'
        );
    }

    #[Test]
    public function author_byline_renders_with_schema_org_person_microdata(): void
    {
        // Author byline gated on $postAuthorName !== '' so orphaned
        // user_id references degrade gracefully to date-only.
        $this->assertStringContainsString(
            "@if (\$postAuthorName !== '')",
            $this->postBlade,
            'post.blade.php: author byline must be gated on $postAuthorName !== \'\' for graceful degradation when the user_id is orphaned'
        );
        // Schema.org Person microdata.
        $this->assertStringContainsString(
            'itemprop="author"',
            $this->postBlade,
            'post.blade.php: author byline must declare itemprop="author"'
        );
        $this->assertStringContainsString(
            'itemtype="https://schema.org/Person"',
            $this->postBlade,
            'post.blade.php: author span must carry Schema.org Person microdata'
        );
        $this->assertStringContainsString(
            '<span itemprop="name">{{ $postAuthorName }}</span>',
            $this->postBlade,
            'post.blade.php: author name must be wrapped in <span itemprop="name"> for the Schema.org Person.name property'
        );

        // Author lookup falls back username → email when both
        // first/last name are empty so legacy installs without
        // populated user profile fields still get a byline.
        $this->assertMatchesRegularExpression(
            "/\\\$postAuthorName\\s*=\\s*\\(string\\)\\s*\\(\\\$authorRow\\['username'\\]\\s*\\?\\?\\s*\\\$authorRow\\['email'\\]/s",
            $this->postBlade,
            'post.blade.php: author lookup must fall back username → email when first/last name unavailable'
        );

        // Decorative middot separator must be aria-hidden so SR
        // doesn't announce "middle dot" between the date and the byline.
        $this->assertStringContainsString(
            'class="post-meta-separator" aria-hidden="true"',
            $this->postBlade,
            'post.blade.php: middot separator between date and byline must carry aria-hidden="true"'
        );
    }

    #[Test]
    public function related_posts_aside_with_aria_labelledby(): void
    {
        // <aside> is a real landmark; aria-labelledby ties it to the
        // h2 heading id so SR users hear "Related posts, region"
        // when they Tab into it.
        $this->assertMatchesRegularExpression(
            '/<aside\\s+class="related-posts[^"]*"\\s*\\n\\s*aria-labelledby="related-posts-heading-/s',
            $this->postBlade,
            'post.blade.php: related-posts wrapper must be an <aside> with aria-labelledby pointing at the heading'
        );
        // Heading id must include CONTENT_ID so multiple <article>
        // pages on a multi-page render don't share heading ids.
        $this->assertMatchesRegularExpression(
            "/id=\"related-posts-heading-<\\?php\\s+print\\s+CONTENT_ID;\\s*\\?>\"/s",
            $this->postBlade,
            'post.blade.php: related-posts heading id must include CONTENT_ID for uniqueness'
        );
        // The Posts module call carries related="true" so the related-posts
        // skin filters by the current post's category.
        $this->assertMatchesRegularExpression(
            '/<module\\s+type="posts"[\\s\\S]*?template="related_posts"[\\s\\S]*?related="true"/s',
            $this->postBlade,
            'post.blade.php: related-posts <module> call must carry template="related_posts" + related="true"'
        );
        $this->assertStringContainsString(
            'exclude_ids="<?php print CONTENT_ID; ?>"',
            $this->postBlade,
            'post.blade.php: related-posts must exclude the current post via exclude_ids=CONTENT_ID'
        );
        $this->assertStringContainsString(
            'hide_paging="true"',
            $this->postBlade,
            'post.blade.php: related-posts must carry hide_paging="true" — pagination on a 3-item module is overkill'
        );
    }
}
