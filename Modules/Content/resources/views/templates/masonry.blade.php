<?php
/*
type: layout
name: Masonry
description: Masonry
*/
?>

@php
$rand = uniqid();
@endphp

<div class="clearfix module-posts-template-masonry" id="posts-{{ $rand }}">
    @if (!empty($data))
        <style>
            .module-posts-template-masonry .masonry-item{
                cursor: default;
                width: 33%;
                min-width: 300px;
                margin-bottom: 5px;
                box-shadow: 0 0px 2px -1px #999;
                border:1px solid #E0E0E0;
                background: white;
            }

            .module-posts-template-masonry .masonry-item img{
                width: 100%;
            }

            .module-posts-template-masonry .masonry-item-description{
                padding: 7px;
                font-size: 12px;
                color: #353535;
                text-align: center;
            }
            .module-posts-template-masonry .masonry-item-container{
                padding: 10px;
                zoom:1;
            }
            .module-posts-template-masonry .masonry-item-container:after{
                content: ".";
                display: block;
                clear: both;
                visibility: hidden;
                line-height: 0;
                height: 0;
            }
            .module-posts-template-masonry .masonry-item-container .description{
                font-size: 13px;
            }
            .module-posts-template-masonry .masonry-item-container small.muted{
                font-size: 11px;
            }
        </style>

        <script>mw.lib.require("masonry");</script>

        <script>
            mw._masons = mw._masons || [];
            $(document).ready(function(){
                var m = mw.$('#posts-{{ $rand }}');
                m.masonry({
                    "itemSelector": '.masonry-item',
                    "gutter":5
                });
                $(window).on('load', function(){
                    m.masonry({
                        "itemSelector": '.masonry-item',
                        "gutter":5
                    });
                });
                m[0].masonryWidth = m.width();
                mw._masons.push(m);
                if(typeof mw._masons_binded === 'undefined'){
                    mw._masons_binded = true;
                    setInterval(function(){
                        var l = mw._masons.length, i=0;
                        for( ; i<l; i++){
                            var _m = mw._masons[i];
                            if(_m[0].masonryWidth != _m.width() && mw.$(".masonry-item", _m[0]).length > 0){
                                _m.masonry({
                                    "itemSelector": '.masonry-item',
                                    "gutter":5
                                });
                                _m[0].masonryWidth = _m.width();
                            }
                        }
                    }, 700);
                }
            });
        </script>

        @if(empty($data))
            <p class="mw-pictures-clean">No content added. Please add content to the gallery.</p>
        @else
            @foreach ($data as $item)
            <div class="masonry-item" itemscope itemtype="{{ $schema_org_item_type_tag }}">
                @if(!isset($show_fields) || $show_fields == false || in_array('thumbnail', $show_fields))
                    <a class="" itemprop="url" href="{{ $item['link'] }}">
                        <img @if($item['image']==false) class="pixum" @endif itemprop="image" src="{{ thumbnail($item['image'], 290, 120) }}" alt="{{ addslashes($item['title']) }} - {{ __('image') }}" title="{{ addslashes($item['title']) }}" />
                    </a>
                @endif
                <div class="masonry-item-container">
                    <div class="module-posts-head">
                        @if(!isset($show_fields) || $show_fields == false || in_array('title', $show_fields))
                            <h3 itemprop="name"><a class="lead" href="{{ $item['link'] }}" itemprop="url">{{ $item['title'] }}</a></h3>
                        @endif
                        @if(!isset($show_fields) || $show_fields == false || in_array('created_at', $show_fields))
                            <small class="muted" itemprop="dateCreated">{{ __("Posted on") }}: {{ $item['created_at'] }}</small>
                        @endif
                    </div>
                    @if(!isset($show_fields) || $show_fields == false || in_array('description', $show_fields))
                        <p class="description" itemprop="description">{{ $item['description'] }}</p>
                    @endif

                    @if(!isset($show_fields) || $show_fields == false || in_array('read_more', $show_fields))
                        <div class="blog-post-footer">
                            <a href="{{ $item['link'] }}" itemprop="url" class="btn btn-default pull-fleft">
                                {{ $read_more_text ?? __('Continue Reading') }}
                                <i class="icon-chevron-right"></i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
        @endif
    @endif
</div>

@if (isset($pages_count) && $pages_count > 1 && isset($paging_param))
    {!! paging("num={$pages_count}&paging_param={$paging_param}&current_page={$current_page}") !!}
@endif
