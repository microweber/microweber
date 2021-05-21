<section class="section section-blog">
    <div class="container">
    <div class="row">

            <script type="text/javascript">
                function getUrlAsArray() {
                    let url = window.location.href;
                    if (url.indexOf('?') === -1) {
                        return [];
                    }
                    var request = [];
                    var pairs = url.substring(url.indexOf('?') + 1).split('&');
                    for (var i = 0; i < pairs.length; i++) {
                        if(!pairs[i])
                            continue;
                        var pair = pairs[i].split('=');
                        request.push({key:decodeURIComponent(pair[0]), value: decodeURIComponent(pair[1])});
                    }
                    return request;
                }

                encodeDataToURL = (data) => {
                    return data.map(value => `${value.key}=${encodeURIComponent(value.value)}`).join('&');
                };

                $(document).ready(function () {
                    $('.js-filter-option-select').change(function () {
                        var redirectFilterUrl = getUrlAsArray();
                        if (!this.checked) {
                            var currentElement = this;
                            for (var i=0; i< redirectFilterUrl.length; i++) {
                                if ((redirectFilterUrl[i].key === currentElement.name) &&(redirectFilterUrl[i].value === currentElement.value)) {
                                    redirectFilterUrl.splice(i,1);
                                    break;
                                }
                            }
                        }
                        $.each($(this).serializeArray(), function(k,filter) {
                            redirectFilterUrl.push({key:filter.name, value:filter.value});
                        });
                        window.location.href = "{{ URL::current() }}?" + encodeDataToURL(redirectFilterUrl);
                    });
                });
            </script>


            <div class="col-md-3">

            {!! $posts->tags('blog::partials.tags'); !!}

            {!! $posts->categories('blog::partials.categories'); !!}

            {!! $posts->filters('blog::partials.filters'); !!}

            @if (!empty($_GET))
                <br />
                <br />
                <a href="{{ URL::current() }}" class="btn btn-outline-primary btn-sm"><i class="fa fa-times"></i> <?php _e('Reset filter'); ?></a>
            @endif
        </div>

        <div class="col-md-9">

            <div class="row">
                <div class="col-md-8">
                    {!! $posts->search('blog::partials.search'); !!}
                </div>
                <div class="col-md-2 ">
                    {!! $posts->limit('blog::partials.limit'); !!}
                </div>
                <div class="col-md-2">
                    {!! $posts->sort('blog::partials.sort'); !!}
                </div>
            </div>

            @foreach($posts->results() as $post)

            <div class="post" style="margin-top:25px;">

                <img src="{{$post->media()->first()->filename}}" alt="" width="400px">

                <h3>{{$post->title}}</h3>
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
            @endforeach

            {!! $posts->pagination('pagination::bootstrap-4-flex') !!}

            <br />
            <p>
                Displaying {{$posts->count()}} of {{ $posts->total() }} result(s).
            </p>
        </div>

    </div>
    </div>
</section>
