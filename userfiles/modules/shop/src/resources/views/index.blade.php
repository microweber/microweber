<script src="userfiles/modules/blog/js/filter.js" type="text/javascript"></script>
<link href="userfiles/modules/blog/css/filter.css" type="text/css" rel="stylesheet">

<section class="section section-blog">
    <div class="container">
    <div class="row">

        <div class="col-md-3">
            <div class="card">

                {!! $posts->activeFilters('blog::partials.active_filters') !!}

                {!! $posts->search('blog::partials.search') !!}

                {!! $posts->tags('blog::partials.tags') !!}

                {!! $posts->categories('blog::partials.categories') !!}

                {!! $posts->filters('blog::partials.filters') !!}

             </div>
        </div>


        <div class="col-md-9">

            <div class="row">
                <div class="col-md-8"></div>
                <div class="col-md-2">
                    {!! $posts->limit('blog::partials.limit'); !!}
                </div>
                <div class="col-md-2">
                    {!! $posts->sort('blog::partials.sort'); !!}
                </div>
            </div>
            <div class="row">
            @foreach($posts->results() as $post)
                    <div class="col-md-3">
            <div class="post" style="margin-top:25px;">

                <img src="{{$post->media()->first()->filename}}" alt="" width="400px">

                <h4>{{$post->title}}</h4>
                <p>{{$post->content_text}}</p>
                <br />
                <small>Posted At:{{$post->posted_at}}</small>
                <br />
                <a href="{{site_url($post->url)}}">View</a>
                <hr />
                @foreach($post->tags as $tag)
                   <span class="badge badge-success"><a href="?tags={{$tag->slug}}">{{$tag->name}}</a></span>
                @endforeach

                @php
                    $resultCustomFields = $post->customField()->with('fieldValue')->get();
                @endphp
                @foreach ($resultCustomFields as $resultCustomField)
                    {{--@if ($resultCustomField->type !== 'date')
                        @continue
                    @endif--}}
                    {{$resultCustomField->name}}:
                    @php
                        $customFieldValues = $resultCustomField->fieldValue()->get();
                    @endphp
                    @foreach($customFieldValues as $customFieldValue)
                        {{$customFieldValue->value}};
                    @endforeach

                @endforeach
            </div>
            </div>
            @endforeach
            </div>

            {!! $posts->pagination('pagination::bootstrap-4-flex') !!}

            <script type="text/javascript">
                $('#{{$moduleId}}').find('.pagination').find('.page-link').click(function(e) {
                    e.preventDefault();
                  /*  var pageLink = $(this).attr('href');
                    var linkObject = decodeUrlParamsToObject(pageLink);

                    var i;
                    var targetPageNum;
                    for (i = 0; i < linkObject.length; i++) {
                        if (linkObject[i].key == 'page') {
                            targetPageNum = linkObject[i].value;
                        }
                    }*/

                    var targetPageNum = $(this).attr('href').split('page=')[1];

                    var queryParams = [];
                    queryParams.push({
                        key:'page',
                        value:targetPageNum
                    });

                    submitQueryFilter('{{$moduleId}}', queryParams);
                });
            </script>

            <br />
            <p>
                Displaying {{$posts->count()}} of {{ $posts->total() }} result(s).
            </p>
        </div>


        </div>
        </div>
</section>
