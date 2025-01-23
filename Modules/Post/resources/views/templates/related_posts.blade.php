@php

/*

type: layout

name: Related Posts

description: Related Posts

*/
@endphp

<style>
    .site-content .heading {
        background: rgb(35, 144, 193);
        border-color: rgb(35, 144, 193);
        padding: 10px;
        position: relative;
        color: #fff !important;
        display: inline-block;
        margin-bottom: 0;
        align-self: self-start;
        width: auto;
    }

    .site-content .heading:after, .sidebar-area h2:not(.banner-title):after {
        position: absolute;
        content: "";
        top: 0;
        right: -8px;
        border-top: 8px solid #605ca8;
        border-color: inherit;
        background: transparent !important;
        border-top: 8px solid rgb(35, 144, 193);

        border-right: 8px solid transparent;

    }

    .sidebar-related-posts {
        padding-top: 1rem;
    }


    .site-content .heading:not(:last-child)+*:not(a), .site-content .heading:not(:last-child)+a+* {
        border-top: 3px solid rgb(35, 144, 193);
    }

    .sidebar-related-posts .image-container {
        width: 100px;
        height: 70px;
        overflow: hidden;
        flex-shrink: 0; /* Ensures the container keeps its size in a flex layout */
    }

    .sidebar-related-posts img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .site-content .pro-post-title {
        font-size: 1rem;
        margin-bottom: 0;

    }


</style>
<div class="site-content">
    <h6 class="heading">Related posts</h6>
    @php if (!empty($data)): @endphp
        @php foreach ($data as $index => $item): @endphp
            <a href="@php print $item['link'] @endphp" class="sidebar-related-posts mb-0 mx-0" itemscope itemtype="@php print $schema_org_item_type_tag @endphp">
                <div class="d-flex py-3 gap-3" @php if ($index === 0) echo 'style="border-top: 3px solid rgb(35, 144, 193);"'; @endphp>
                    <div class="image-container">
                        <img loading="lazy" src="@php print $item['image']; @endphp" itemprop="image"/>
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



