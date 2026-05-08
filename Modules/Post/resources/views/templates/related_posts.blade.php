@php

/*

type: layout

name: Related Posts

description: Related Posts

*/
@endphp

@once
    <link rel="stylesheet" href="{{ asset('modules/post/css/post-skins.css') }}">
@endonce
<div class="site-content">
    <h6 class="heading">Related posts</h6>
    @php if (!empty($data)): @endphp
        @php foreach ($data as $index => $item): @endphp
            <a href="@php print $item['link'] @endphp" class="sidebar-related-posts mb-0 mx-0" itemscope itemtype="@php print $schema_org_item_type_tag @endphp">
                <div class="d-flex py-3 gap-3" @php if ($index === 0) echo 'style="border-top: 3px solid rgb(35, 144, 193);"'; @endphp>
                    <div class="image-container">
                        {!! responsive_thumbnail($item['image'], 800, null, ['alt' => $item['title'] ?? '', 'class' => 'img-fluid', 'itemprop' => 'image']) !!}
                    </div>
                    @php if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): @endphp
                        <p class="font-weight-bold pro-post-title" itemprop="name">@php print $item['title'] @endphp</p>
                    @php endif; @endphp
                </div>
                @php if ($index < count($data) - 1): @endphp
                    <hr class="my-2">
                @php endif; @endphp
            </a>
        @php endforeach; @endphp
    @php endif; @endphp
</div>



