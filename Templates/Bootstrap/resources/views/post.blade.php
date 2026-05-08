<?php
$post = get_content_by_id(CONTENT_ID);
$picture = get_picture(CONTENT_ID);

if (!$picture) {
    $picture = '';
}

$itemData = content_data(CONTENT_ID);
$itemTags = content_tags(CONTENT_ID);

/*
 * AI-69 / TICKET-KK (cycle-82 2026-05-08): post-detail meta
 * resolution. Author lookup falls back gracefully when the
 * created_by user has been deleted (legacy posts with orphaned
 * user_id references shouldn't break rendering); the date string
 * is computed once into both ISO-8601 (machine-readable, for
 * <time datetime>) and human-readable forms.
 */
$postCreatedAt = isset($post['created_at']) ? strtotime((string) $post['created_at']) : false;
$postCreatedAtIso = $postCreatedAt ? date('c', $postCreatedAt) : '';
$postCreatedAtHuman = $postCreatedAt ? date('d M Y', $postCreatedAt) : '';

$postAuthorName = '';
if (isset($post['created_by']) && (int) $post['created_by'] > 0) {
    $authorRow = function_exists('get_user') ? @get_user($post['created_by']) : null;
    if (is_array($authorRow)) {
        $postAuthorName = trim(
            (string) ($authorRow['first_name'] ?? '') . ' ' . (string) ($authorRow['last_name'] ?? '')
        );
        if ($postAuthorName === '') {
            $postAuthorName = (string) ($authorRow['username']
                ?? $authorRow['email']
                ?? '');
        }
    }
}
?>
@extends('templates.bootstrap::layouts.master')

@section('content')
    {{-- AI-69 / TICKET-KK (cycle-82 2026-05-08): wrap post body in
         a real <article> landmark so screen-reader users can jump
         to the post via document landmark navigation. itemscope +
         itemtype add Schema.org BlogPosting microdata for SEO. --}}
    <article class="blog-inner-page py-5"
             id="blog-content-<?php print CONTENT_ID; ?>"
             itemscope
             itemtype="https://schema.org/BlogPosting">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-12">

                    <?php if ($picture != '' AND $picture != false): ?>
                    <div class="mb-4 rounded overflow-hidden">
                        <img src="<?php print $picture; ?>"
                             alt="<?php echo e($post['title']); ?>"
                             class="w-100"
                             style="max-height: 500px; object-fit: cover;"
                             itemprop="image">
                    </div>
                    <?php endif; ?>

                    <h1 class="mt-4 mb-2 text-center" itemprop="headline"><?php echo $post['title']; ?></h1>

                    {{-- AI-69 / TICKET-KK: visible time + author byline.
                         <time datetime=...> is the canonical
                         machine-readable date; aria-label gives SR
                         users the human-readable form. Author byline
                         only renders when the lookup resolved a name
                         (orphaned user_id references gracefully
                         degrade to date-only). --}}
                    <p class="post-meta text-muted text-center mb-5">
                        @if ($postCreatedAtIso)
                            <time datetime="{{ $postCreatedAtIso }}"
                                  itemprop="datePublished"
                                  aria-label="{{ __('Published on') }} {{ $postCreatedAtHuman }}">
                                {{ $postCreatedAtHuman }}
                            </time>
                        @endif
                        @if ($postAuthorName !== '')
                            <span class="post-meta-separator" aria-hidden="true"> · </span>
                            <span class="post-author"
                                  itemprop="author"
                                  itemscope
                                  itemtype="https://schema.org/Person">
                                {{ __('By') }}
                                <span itemprop="name">{{ $postAuthorName }}</span>
                            </span>
                        @endif
                    </p>

                    <div class="description edit dropcap typography-area"
                         field="content"
                         rel="content"
                         itemprop="articleBody">
                        <?php echo $post['content_body']; ?>
                    </div>

                    <hr class="my-4">

                    <module type="sharer" id="post-bottom-sharer" class="py-3"/>

                    {{-- AI-69 / TICKET-KK: related posts. The Post
                         module's related_posts skin reads the
                         current post's category context to surface
                         siblings — gives readers a discovery path
                         beyond the single article AND adds an
                         internal-link signal for SEO. Wrapped in
                         <aside aria-labelledby> so AT users can
                         jump to or skip the section via landmark
                         navigation. --}}
                    <aside class="related-posts mt-5"
                           aria-labelledby="related-posts-heading-<?php print CONTENT_ID; ?>">
                        <h2 id="related-posts-heading-<?php print CONTENT_ID; ?>" class="h4 mb-3">
                            {{ __('Related posts') }}
                        </h2>
                        <module type="posts"
                                template="related_posts"
                                limit="3"
                                hide_paging="true"
                                related="true"
                                exclude_ids="<?php print CONTENT_ID; ?>"
                                id="post-bottom-related-<?php print CONTENT_ID; ?>"/>
                    </aside>
                </div>
            </div>
        </div>
    </article>
@endsection
