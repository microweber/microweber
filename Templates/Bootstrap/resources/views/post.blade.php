<?php
// task-2026-05-22-f4a791 / AI-791 Slices A–C
$post = get_content_by_id(CONTENT_ID);
$picture = get_picture(CONTENT_ID);

if (!$picture) {
    $picture = '';
}

$itemData = content_data(CONTENT_ID);
$itemTags = content_tags(CONTENT_ID);
$itemCategories = content_categories(CONTENT_ID) ?: [];

// Slice B — reading-time estimate (200 wpm average)
$mwPostWords = str_word_count(strip_tags((string)($post['content_body'] ?? '')));
$mwPostReadingMins = max(1, (int)ceil($mwPostWords / 200));

// Slice C — prev/next navigation
$mwPostPrevPost = function_exists('prev_post') ? prev_post(CONTENT_ID) : null;
$mwPostNextPost = function_exists('next_post') ? next_post(CONTENT_ID) : null;

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
        <x-container>
            <x-row class="justify-content-center">
                {{-- Slice A (AI-791): reading-line discipline — 720px max-width (55-75ch) --}}
                <x-col size="12" style="max-width: 720px; margin-left: auto; margin-right: auto;">

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

                    {{-- Slice B (AI-791): "Published on" prefix + author + reading time --}}
                    <p class="post-meta text-muted text-center mb-3">
                        @if ($postCreatedAtIso)
                            <span class="post-meta-label">{{ __('Published on') }}</span>
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
                        <span class="post-meta-separator" aria-hidden="true"> · </span>
                        <span class="post-reading-time">{{ $mwPostReadingMins }} {{ __('min read') }}</span>
                    </p>

                    {{-- Slice B (AI-791): category chips --}}
                    @if (!empty($itemCategories))
                        <div class="post-categories text-center mb-2">
                            @foreach ($itemCategories as $cat)
                                @if (!empty($cat['title']))
                                    <a href="{{ category_link((int)($cat['id'] ?? 0)) ?: '#' }}"
                                       class="badge bg-secondary text-decoration-none me-1">
                                        {{ e($cat['title']) }}
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    @endif

                    {{-- Slice B (AI-791): tags row --}}
                    @if (!empty($itemTags))
                        <div class="post-tags text-center mb-4">
                            @foreach ($itemTags as $tag)
                                <a href="{{ url('search?keywords=' . urlencode((string)$tag)) }}"
                                   class="badge bg-light text-secondary border text-decoration-none me-1">
                                    #{{ e((string)$tag) }}
                                </a>
                            @endforeach
                        </div>
                    @endif

                    <div class="description edit dropcap typography-area mt-4"
                         field="content"
                         rel="content"
                         itemprop="articleBody">
                        <?php echo $post['content_body']; ?>
                    </div>

                    <hr class="my-4">

                    <module type="sharer" id="post-bottom-sharer" class="py-3"/>

                    {{-- Slice C (AI-791): prev/next post navigation --}}
                    @if ($mwPostPrevPost || $mwPostNextPost)
                        <nav class="post-navigation d-flex justify-content-between mt-5 mb-3 gap-3"
                             aria-label="{{ __('Post navigation') }}">
                            <div class="post-nav-prev">
                                @if ($mwPostPrevPost && !empty($mwPostPrevPost['url']))
                                    <a href="{{ $mwPostPrevPost['url'] }}"
                                       class="post-nav-link d-flex flex-column"
                                       rel="prev">
                                        <small class="text-muted">{{ __('← Previous') }}</small>
                                        <span class="fw-medium">{{ e($mwPostPrevPost['title'] ?? '') }}</span>
                                    </a>
                                @endif
                            </div>
                            <div class="post-nav-next text-end">
                                @if ($mwPostNextPost && !empty($mwPostNextPost['url']))
                                    <a href="{{ $mwPostNextPost['url'] }}"
                                       class="post-nav-link d-flex flex-column align-items-end"
                                       rel="next">
                                        <small class="text-muted">{{ __('Next →') }}</small>
                                        <span class="fw-medium">{{ e($mwPostNextPost['title'] ?? '') }}</span>
                                    </a>
                                @endif
                            </div>
                        </nav>
                    @endif

                    {{-- Slice C (AI-791): comments section — gated on Comments module install --}}
                    @if (function_exists('is_module_installed') && is_module_installed('comments'))
                        <section class="post-comments mt-5"
                                 aria-labelledby="post-comments-heading-<?php print CONTENT_ID; ?>">
                            <h2 id="post-comments-heading-<?php print CONTENT_ID; ?>" class="h4 mb-3">
                                {{ __('Comments') }}
                            </h2>
                            <module type="comments" id="post-comments-<?php print CONTENT_ID; ?>"/>
                        </section>
                    @endif

                    {{-- AI-69 / TICKET-KK: related posts --}}
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
                </x-col>
            </x-row>
        </x-container>
    </article>
@endsection
