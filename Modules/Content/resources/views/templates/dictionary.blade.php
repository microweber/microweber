<?php
/*
type: layout
name: Dictionary
description: Dictionary
*/
?>

<style>
    .mw-dictionary .site-content {
        overflow: hidden;
    }

    .mw-dictionary .py-4 {
        padding-top: 4rem;
        padding-bottom: 4rem;
    }

    .mw-dictionary .mb-6 {
        margin-bottom: 6rem;
    }

    .mw-dictionary .form-control {
        box-shadow: none;
        padding: 13px 10px;
        height: 50px;
        background: #FFF;
        color: #3a404d;
        border-radius: 5px;
        border-color: #DFE2E8;
    }

    .mw-dictionary .form-control:focus, .mw-dictionary .form-control.input-focus {
        box-shadow: none !important;
        border-color: #2b98eb;
    }

    .mw-dictionary .card {
        background: #FFF;
        box-shadow: 0 1px 5px 0 rgba(0, 0, 0, 0.15);
        border-radius: 5px;
        overflow: hidden;
    }

    .mw-dictionary .card__content {
        padding: 15px;
        font-size: 14px;
        line-height: 22px;
    }

    .mw-dictionary .card__content p:last-child {
        margin-bottom: 0;
        height: 120px;
    }

    .mw-dictionary .card__title {
        font-weight: 500;
        font-size: 16px;
        line-height: 24px;
        height: 40px;
        margin-top: 15px;
    }

    .mw-dictionary .card__title a {
        color: #3a404d;
        text-decoration: none;
    }

    .mw-dictionary .card__title a:hover, .mw-dictionary .card__title a:focus {
        color: #2b98eb;
        text-decoration: none;
    }

    .mw-dictionary .glossary__nav__item.active a {
        color: #FFF;
        background-color: #2b98eb;
    }

    .mw-dictionary .glossary__nav__item a {
        width: 50px;
        font-size: 16px;
        color: #3a404d;
        text-decoration: none;
        text-align: center;
        display: block;
    }

    .mw-dictionary .glossary__nav__item a:hover, .mw-dictionary .glossary__nav__item a:focus {
        color: #FFF;
        background-color: #2b98eb;
    }

    .mw-dictionary .glossary__nav__item a.card__content {
        padding: 13px 10px;
        margin-bottom: 5px;
        border-radius: 3px;
    }

    .mw-dictionary .glossary__search__form {
        max-width: 500px;
        position: relative;
    }

    .mw-dictionary .glossary__search__form:before {
        content: "\f002";
        color: #aaa;
        font-family: "FontAwesome";
        position: absolute;
        left: 15px;
        top: 12px;
        font-size: 18px;
    }

    .mw-dictionary .glossary__search__form .form-control {
        padding-left: 40px;
    }

    .mw-dictionary .glossary__results__row {
        overflow: hidden;
        margin-bottom: 40px;
        transition: all 0.4s ease-in-out;
    }

    .mw-dictionary .glossary__results__row:last-child {
        margin-bottom: 0;
    }

    .mw-dictionary .glossary__results__row.inactive {
        opacity: 0;
        height: 0;
        margin: 0;
        width: 100%;
    }

    .mw-dictionary .glossary__results__term {
        color: #2b98eb;
    }

    .mw-dictionary .glossary__results__item {
        margin-bottom: 20px;
    }

    .mw-dictionary .glossary__results__item a {
        display: block;
        text-decoration: none;
        color: #3a404d;
    }

    .mw-dictionary .glossary__results__item a.card {
        border: 2px solid transparent;
    }

    .mw-dictionary .glossary__results__item a:hover {
        border-color: #2b98eb;
    }

    .mw-dictionary .glossary__results__item a:hover .card__title {
        color: #2b98eb;
    }

    .mw-dictionary .title-style--three {
        margin-bottom: 15px;
        position: relative;
        padding-top: 20px;
        padding-bottom: 10px;
    }

    .mw-dictionary .title-style--three:after {
        content: "";
        background: #2b98eb;
        width: 60px;
        height: 3px;
        position: absolute;
        left: 0;
        bottom: 0;
        border-radius: 30px;
    }

    .mw-dictionary .thumbnail-image-holder {
        position: relative;
        overflow: hidden;
        height: 200px;
    }


    .mw-dictionary .thumbnail-image-holder .thumbnail{
        object-fit: cover;
        width: 100%;
        height: 100%;
        min-width: calc(100% + 2px);
        min-height: 100%;
    }
</style>

<script>
    $(function () {
        initGlossaryFilter();
    });

    // Filter Glossary items
    function initGlossaryFilter() {
        // Filter using search box
        $("#glossarySearchInput").on("keyup", function () {
            var inputValue = $(this).val();

            // Hide all the results & Cards
            $(".glossary__results__row").addClass("inactive");
            $(".glossary__results__item").hide();

            $(".glossary__results__row").each(function () {
                $(".glossary__results__item").each(function () {
                    var item = $(this).attr("data-item");

                    if (item.toUpperCase().indexOf(inputValue.toUpperCase()) != -1) {
                        $(this).parents(".glossary__results__row").removeClass("inactive");
                        $(this).show();
                    }
                });
            });
        });

        // Filter using navigation
        $(".glossary__nav a").click(function () {
            var nav = $(this).attr("data-nav");

            // Remove & Add active class
            $(".glossary__nav__item").removeClass("active");
            $(this).parent().toggleClass("active");

            // Hide all the results
            $(".glossary__results__row").addClass("inactive");

            // Loop through the row
            $(".glossary__results__row").each(function () {
                var term = $(this).attr("data-term");

                if (nav == term) {
                    $(this).removeClass("inactive");
                }
            });

            // Only return false if data-toggle is glossary
            if ($(this).attr("data-toggle") == "glossary") {
                return false;
            }
        });

        $(".glossary__nav a.all").click(function () {
            // Loop through the row
            $(".glossary__results__row").each(function () {
                $(this).removeClass("inactive");
            });

            // Only return false if data-toggle is glossary
            if ($(this).attr("data-toggle") == "glossary") {
                return false;
            }
        });
    }
</script>

@if (!empty($data))
    @php
    $sorted = array();
    foreach ($data as $item) {
        $firstLetter = $item['title'];
        $firstLetter = trim(preg_replace('/\s+/', ' ', $firstLetter));
        $firstLetter = mb_substr($firstLetter, 0, 1);
        $sorted[$firstLetter][] = $item;
    }
    ksort($sorted);


    $count = 0;
    @endphp

    <div class="mw-dictionary bootstrap3ns">
        <div class="clearfix"></div>
        <main class="site-content">
            <div class="container py-4">
                <nav class="glossary__nav mb-4">
                    <ul class="list-inline">
                        <li class="glossary__nav__item">
                            <a class="card card__content all" data-bs-toggle="glossary" href="#">All</a>
                        </li>

                        @foreach ($sorted as $key => $list)
                            <li class="glossary__nav__item">
                                <a class="card card__content" data-nav="{{ $key }}" data-bs-toggle="glossary" href="#">{{ strtoupper($key) }}</a>
                            </li>
                        @endforeach
                    </ul>
                </nav>

                <div class="glossary__search mb-4">
                    <form action="#" class="glossary__search__form">
                        <input class="form-control" id="glossarySearchInput" placeholder="Search Keywords" type="search">
                    </form>
                </div>

                <div class="glossary__results mb-6">
                    @foreach ($sorted as $key => $list)

                        <div class="glossary__results__row" data-term="{{ $key }}">
                            <h3 class="glossary__results__term title-style--three mb-3">{{ $key }}</h3>
                            <div class="row">
                                @foreach ($list as $item)
                                    @php
                                        $itemData = content_data($item['id']);
                                        $itemTags = content_tags($item['id']);
                                        $count++;
                                    @endphp
                                    <div class="glossary__results__item col-md-3 col-sm-6" data-item="{{ $item['title'] }}">
                                        <a class="card card__content" href="{{ $item['link'] }}">
                                            @if (!isset($show_fields) || $show_fields == false || in_array('thumbnail', $show_fields))
                                                <div class="thumbnail-image-holder">
                                                    <img class="thumbnail" src="{{ thumbnail($item['image'], 535, 285) }}" alt="">
                                                </div>
                                            @endif

                                            @if (!isset($show_fields) || $show_fields == false || in_array('title', $show_fields))
                                                <h4 itemprop="name" class="card__title">{{ character_limiter($item['title'], 20) }}</h4>
                                            @endif
                                            @if (!isset($show_fields) || $show_fields == false || in_array('description', $show_fields))
                                                <p class="mb-0" itemprop="description">{{ character_limiter($item['description'], 100) }}</p>
                                            @endif

                                                @if (is_array($item['prices']) && !empty($item['prices']))

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
                                                                <button class="btn btn-primary" type="button" onclick="mw.cart.add_and_checkout('{{ $item['id'] }}');">
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
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </main>
        <div class="clearfix"></div>
    </div>
@endif
