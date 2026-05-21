@php
    $columns = get_option('columns', $params['id']);
    if ($columns) {
        $columns = $columns;
    } elseif (isset($params['data-columns'])) {
        $columns = $params['data-columns'];
    } else {
        $columns = 'col-md-6 col-lg-4';
    }

    $columns_xl = get_option('columns-xl', $params['id']);
    $columns_md = get_option('columns-md', $params['id']);
    $columns_sm = get_option('columns-sm', $params['id']);

    if ($columns_xl) {
        $columns .= ' ' . $columns_xl;
    }
    if ($columns_md) {
        $columns .= ' ' . $columns_md;
    }

    // AI-78 / TICKET-AE (cycle-90 2026-05-09): dropped dead `$thumb_quality`
    // + `$thumbs_columns` + dead loose-equality `if ($columns_xl != null
    // || $columns_xl != false || $columns_xl != '')` (the OR-of-three was
    // always true). Neither variable was read anywhere downstream;
    // responsive_thumbnail() handles thumbnail dimensions directly.
    $count = 0;

@endphp



<style>
    #posts-{{ $params['id'] }} .thumbnail-image-holder {
        position: relative;
        overflow: hidden;
        height: 300px;
    }


    #posts-{{ $params['id'] }} .thumbnail-image-holder .thumbnail{
        object-fit: contain;
        width: 100%;
        height: 100%;
        min-width: calc(100% + 2px);
        min-height: 100%;
    }
    #posts-{{ $params['id'] }} .mw-products-title{

        a {

            color: var(--mw-heading-color);
            text-decoration: none;
        }
    }

    #posts-{{ $params['id'] }} .post-bottom-holder {
        /* AI-78 / TICKET-AE (cycle-90): padding-top 0 → 20px after the
           `<br>` between thumbnail and bottom-holder was removed. */
        padding: 25px;
        padding-top: 20px;
    }

    #posts-{{ $params['id'] }} .big-news .post-holder h3 a{
        text-decoration: none;
    }
    #posts-{{ $params['id'] }} .big-news .post-holder {
        background: var(--background);
        box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
        margin: 20px 0;
        text-align: left;
    }

    #posts-{{ $params['id'] }} .post-list-image{
        width: 240px;
    }

   #posts-{{ $params['id'] }} .post-holder.mw-content-skin-1 {
        margin-bottom: 10px;
        padding: 20px;
        overflow: hidden;
    }

   #posts-{{ $params['id'] }} .post-holder.mw-content-skin-1 h3,
   #posts-{{ $params['id'] }} .post-holder.mw-content-skin-1 p {
        overflow-wrap: break-word;
        word-break: break-word;
    }

   #posts-{{ $params['id'] }} .post-holder.mw-content-skin-1:hover {
        box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
        transition: .3s;
    }
</style>

<div class="row" id="posts-{{ $params['id'] }}">
    <div class="col-lg-12 mx-auto">
        <div class="row big-news">
            @if(empty($data))
                {{-- AI-104 / TICKET-AI-104 (cycle-101 2026-05-09): wrap empty-content placeholder in is_admin() so it stays visible to editors but doesn't leak onto anonymous public pages. --}}
                @if(is_admin())
                    @php
                        // AI-780a (task-2026-05-17-4c289e) — companion-template
                        // rollout of the AI-780 type-aware empty state shipped
                        // for default.blade.php. Mechanical-copy slice per
                        // designer dispatch: same @php block + same
                        // .mw-canvas-empty-state markup, only the host
                        // template differs. Original AI-780 references at
                        // task-2026-05-17-6d65de.
                        $mwAi780Type = $params['content_type'] ?? null;
                        // task-2026-05-17-fe8f9e / AI-801 -- Stage-1 fix:
                        // infer from $params['type'] when content_type
                        // is missing at runtime. See default.blade.php
                        // for the full lineage docblock.
                        if (! $mwAi780Type) {
                            $mwAi780Type = match ($params['type'] ?? null) {
                                'posts'    => 'post',
                                'pages'    => 'page',
                                'products' => 'product',
                                default    => null,
                            };
                        }
                        if ($mwAi780Type === 'post') {
                            $mwAi780Title = __('No posts yet');
                            $mwAi780Body = __('Add your first post to fill this module.');
                            $mwAi780CtaLabel = __('+ Add post');
                            $mwAi780CtaHref = route('filament.admin.resources.posts.create');
                        } elseif ($mwAi780Type === 'page') {
                            $mwAi780Title = __('No pages yet');
                            $mwAi780Body = __('Add your first page to fill this module.');
                            $mwAi780CtaLabel = __('+ Add page');
                            $mwAi780CtaHref = route('filament.admin.resources.pages.create');
                        } else {
                            $mwAi780Title = __('No content yet');
                            $mwAi780Body = __('Add your first item to fill this module.');
                            $mwAi780CtaLabel = __('+ Add content');
                            $mwAi780CtaHref = route('filament.admin.resources.contents.create');
                        }
                    @endphp
                    <div class="mw-canvas-empty-state" data-mw-ai780-content-type="{{ e($mwAi780Type ?? 'unknown') }}">
                        <h3 class="mw-canvas-empty-state__title">{{ $mwAi780Title }}</h3>
                        <p class="mw-canvas-empty-state__body">{{ $mwAi780Body }}</p>
                        <a class="mw-canvas-empty-state__cta" href="{{ $mwAi780CtaHref }}" aria-label="{{ $mwAi780CtaLabel }}">{{ $mwAi780CtaLabel }}</a>
                    </div>
                @endif
            @else
                @foreach ($data as $key => $item)
                @php
                    $itemData = content_data($item['id']);
                    $itemTags = content_tags($item['id']);
                    $count++;
                @endphp

                {{-- AI-78 / TICKET-AE (cycle-90): AOS-cap. Pre-fix
                     `data-aos-delay="{{ $key }}00"` produced 9.9 s+
                     animation delays for posts beyond the 10th; cap
                     at 800 ms (8 × 100). --}}
                <div class="{{ $columns }}" data-aos="fade-up" data-aos-delay="{{ min($key, 8) * 100 }}" itemscope itemtype="{{ $schema_org_item_type_tag }}">
                    <div class="post-holder mw-content-skin-1">
                        <a href="{{ $item['link'] }}" itemprop="url">
                            <div class="thumbnail-holder">
                                @if ($itemTags)
                                    <div class="tags">
                                        @foreach ($itemTags as $tag)
                                            @if ($key < 3)
                                                <span class="badge badge-primary">{{ $tag }}</span>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif

                                @if (!isset($show_fields) || $show_fields == false || in_array('thumbnail', $show_fields))
                                    <div class="thumbnail-image-holder">
                                        {{-- audit-test 2026-05-08 PM TASK-012 / TICKET-CX (cycle-55): responsive_thumbnail helper. --}}
                                        {!! responsive_thumbnail($item['image'], 535, 285, [
                                            'class' => 'thumbnail img-fluid',
                                        ]) !!}
                                    </div>
                                @endif
                            </div>
                        </a>
                        {{-- AI-78 / TICKET-AE (cycle-90): replaced `<br>`
                             with padding on `.post-bottom-holder`. --}}
                        <div class="post-bottom-holder">
                            @if (!isset($show_fields) || $show_fields == false || in_array('title', $show_fields))
                                {{-- AI-78 / TICKET-AE (cycle-90): dropped
                                     duplicate `itemprop="url"` from inner
                                     anchor — outer thumbnail wrapper
                                     already declares the canonical URL. --}}
                                <h3 itemprop="name" class="mw-products-title m-0"><a href="{{ $item['link'] }}">{{ $item['title'] }}</a></h3>
                            @endif

                            @if (!isset($show_fields) || $show_fields == false || in_array('created_at', $show_fields))
                                <small class="text-muted" itemprop="dateCreated">{{ __("Posted on") }}: {{ date_system_format($item['created_at']) }}</small>
                            @endif

                            @if (!isset($show_fields) || $show_fields == false || in_array('description', $show_fields))
                                <p itemprop="description" class="mt-3">{{ \Illuminate\Support\Str::limit($item['description'], 150) }}</p>
                            @endif


                            @if (!isset($show_fields) || $show_fields == false || in_array('read_more', $show_fields))
                                {{-- AI-78 / TICKET-AE (cycle-90): dropped
                                     duplicate `itemprop="url"` from
                                     "Read more" anchor. --}}
                                <a href="{{ $item['link'] }}" class="button-8 m-t-20">
                                    <span>{{ $read_more_text ?? __('Read more') }}</span>
                                </a>
                            @endif

                            {{-- audit-test 2026-05-07 Post Lists audit finding #6:
                                 swapped operand order — !empty() is null/undefined-safe
                                 and must come first. The previous order raised an
                                 "Undefined array key 'prices'" warning whenever a
                                 plain post (not a product) reached this template. --}}
                            @if (!empty($item['prices']) && is_array($item['prices']))

                                @php
                                    $prices =  $item['prices'];
                                    $val1 = array_shift($prices);
                                @endphp


                                <div class="post-price-holder d-flex align-items-center justify-content-between mt-3">
                                    @if (!$show_fields || in_array('price', $show_fields))
                                        @if (isset($item['prices']) && is_array($item['prices']))

                                            <h5 class="price">{{ currency_format($val1) }}</h5>

                                        @endif
                                    @endif

                                    @if (!$show_fields || in_array('add_to_cart', $show_fields))
                                        @php
                                            $add_cart_text = $add_to_cart_text ?? __('Add to cart');
                                        @endphp
                                        @if (is_array($item['prices']) && !empty($item['prices']))
                                            <button class="btn btn-primary" type="button" data-mw-cart-add-and-checkout="{{ $item['id'] }}">
                                                <i class="mdi mdi-cart icon-shopping-cart glyphicon glyphicon-shopping-cart"></i>&nbsp;{{ $add_cart_text }}
                                            </button>
                                        @endif
                                    @endif
                                </div>

                                @foreach ($item['prices'] as $k => $v)
                                    <div class="clear posts-list-proceholder mw-add-to-cart-{{ $item['id'].$count }}">
                                        <input type="hidden" name="price" value="{{ $v }}"/>
                                        <input type="hidden" name="content_id" value="{{ $item['id'] }}"/>
                                    </div>
                                    @break
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
        </div>
    </div>
</div>

{{-- AI-78 / TICKET-AE (cycle-90 2026-05-09): pagination footer.
     Mirrors the convention already used by sidebar/masonry/search
     templates in this module. --}}
@if (isset($pages_count) && $pages_count > 1 && isset($paging_param))
    @php $current_page = $current_page ?? 1; @endphp
    {!! paging("num={$pages_count}&paging_param={$paging_param}&current_page={$current_page}") !!}
@endif
