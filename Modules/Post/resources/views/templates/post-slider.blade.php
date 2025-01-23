@php

/*

type: layout

name: Posts Slider 1

description: Posts Slider 1

*/
@endphp

@include('modules.post::partials.slick_options')


<div class="slick-arrows-1">
    <div class="row py-4 blog-posts-1 slickslider slick-dots-relative">
        @php if (!empty($data)): @endphp
            @php foreach ($data as $item): @endphp
            @php $categories = content_categories($item['id']);

            $itemCats = '';
            if ($categories) {
                foreach ($categories as $category) {
                    $itemCats .= '<small class="text-dark font-weight-bold d-block mb-2" itemprop="description">' . $category['title'] . '</small> ';
                }
            }
            @endphp

        <div class="mx-3 col-sm-10 col-md-6 col-lg-4 mb-5" itemscope itemtype="http://schema.org/Article">
            <div class="overflow-hidden h-100 d-flex flex-column">
                    @php if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): @endphp

                <a itemprop="url" href="@php print $item['link'] @endphp">
                    <div class="img-as-background h-350">
                        <img itemprop="image" loading="lazy" src="@php print $item['image']; @endphp"
                             style="position: relative !important;"/>
                    </div>
                </a>
                @php endif; @endphp

                <div class=" pt-3 pb-5 mt-md-auto mt-5">
                        @php echo $itemCats; @endphp
                        @php if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): @endphp
                    <h4 itemprop="name" class="mb-2">@php print $item['title'] @endphp</h4>
                    @php endif; @endphp

                        @php if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): @endphp
                    <p itemprop="description">@php print {{ \Illuminate\Support\Str::limit($item['description'], 250) }} @endphp</p>
                    @php endif; @endphp

                        @php $text = 'text-text'; @endphp

                    <div class="m-t-auto">
                            @php if (!isset($show_fields) or $show_fields == false or in_array('read_more', $show_fields)): @endphp

                        <a href="@php print $item['link'] @endphp" class="  " itemprop="url">
                                    <span itemprop="name">
                                        @php
                                            if ($read_more_text) {
                                                print $read_more_text;
                                            } else {
                                                print 'Read more';
                                            }
                                            @endphp
                                    </span>
                        </a>
                        @php endif; @endphp


                    </div>
                </div>
            </div>
        </div>
        @php endforeach; @endphp
        @php endif; @endphp
    </div>
</div>

@php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): @endphp
<module type="pagination" pages_count="@php echo $pages_count; @endphp" paging_param="@php echo $paging_param; @endphp"/>
@php endif; @endphp
