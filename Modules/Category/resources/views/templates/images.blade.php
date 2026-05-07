<?php
/*
type: layout
name: Images
description: Category Images Layout
*/
?>

@include('modules.category::partials.categories_data')

<style>
.module-categories-template-images:after {
    content: '.';
    font-size: 0;
    overflow: hidden;
    display: block;
    clear: both;
}

.module-categories-template-images {
    text-align: justify;
}

.module-categories-template-images > a {
    text-align: center;
    width: 120px;
    display: inline-block;
    margin: 12px 12px 24px;
    float: left;
    text-decoration: none;
}

.module-categories-template-images > a strong {
    font-weight: normal;
    display: block;
    text-align: center;
    padding-top: 5px;
}

.module-categories-template-images > a:hover strong,
.module-categories-template-images > a:focus strong {
    text-decoration: underline;
}

.module-categories-template-images .category-image {
    width: 120px;
    height: 120px;
    display: block;
    /* audit-test 2026-05-07 post-merge follow-up #2: was background-size:contain
       on a <span>, now object-fit:contain on a real <img> — same visual fit, no
       CSS-injection sink. */
    object-fit: contain;
    object-position: center;
    margin-bottom: 10px;
}
</style>

{{-- audit-test 2026-05-07 Categories audit findings #1 + #4 + #8:
     #1: wrap in <nav> + aria-labelledby for landmark navigation.
     #4: visually-hidden <h2> announce-only label.
     #8: items-count span gets aria-label="N items" so screen readers
         hear "5 items" instead of "open paren five close paren".
     Findings #5 (a-with-background-image → <img>) and #6 (50-line
     inline <style>) are tracked separately as TICKET-SS / TICKET-V
     (refactor scope; this commit is the safe a11y pass only). --}}
<nav class="module-categories module-categories-template-images"
     aria-labelledby="cat-{{ $params['id'] ?? 'images' }}-h">
    <h2 id="cat-{{ $params['id'] ?? 'images' }}-h" class="visually-hidden">{{ __('Product categories') }}</h2>
    @if(!empty($data))
        @foreach($data as $item)
            @php
                $picture = isset($item['picture']) ? $item['picture'] : '';
                $title = isset($item['title']) ? $item['title'] : '';
                $url = isset($item['url']) ? $item['url'] : '';
                $itemsCount = isset($item['content_items_count']) ? $item['content_items_count'] : 0;
            @endphp

            <a href="{{ $url }}" class="category-item">
                {{-- audit-test 2026-05-07 post-merge follow-up #2 (TICKET-AT/CSS-injection):
                     `style="background-image: url('{{ $picture }}')"` was a CSS-
                     injection sink — Blade HTML-escape converts `'` to `&#039;`
                     but the browser HTML-decodes the attribute BEFORE the CSS
                     parser sees it, so `'` returns to literal and the admin-
                     supplied URL could break out of url(...) into arbitrary
                     CSS rules. Page-defacement / phishing-overlay primitive.
                     Converted to a real `<img>` — `src=""` accepts URL
                     content with no parser-context confusion. Bonus: alt for
                     screen readers, lazy-loading, no inline `style=` attribute. --}}
                @if($picture)
                    <img src="{{ $picture }}" alt="{{ $title }}" loading="lazy" class="category-image">
                @else
                    <img src="{{ asset('modules/category/img/category_images.svg') }}" alt="" loading="lazy" class="category-image">
                @endif

                <strong>{{ $title }}</strong>
                @if($itemsCount)
                    {{-- audit-test 2026-05-07 post-merge follow-up #2 issue #2:
                         `{{ $itemsCount . ' items' }}` announced "1 items" for
                         single-item categories (grammatically wrong; SR users
                         notice). trans_choice() picks the right pluralization
                         per locale — English `{1} :count item|[2,*] :count
                         items` is the source-of-truth definition; locale files
                         can override per language (some have 3+ plural forms). --}}
                    <span class="items-count"
                          aria-label="{{ trans_choice('{1} :count item|[2,*] :count items', $itemsCount, ['count' => $itemsCount]) }}">({{ $itemsCount }})</span>
                @endif
            </a>
        @endforeach
    @else
        {{ lnotif(_e('No categories found', true)) }}
    @endif
</nav>
