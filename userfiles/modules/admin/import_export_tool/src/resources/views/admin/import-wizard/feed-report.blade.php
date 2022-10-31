<div>

    @if(isset($importFeed['mapped_content']))

        <div class="mb-2 text-center">
            <b>{{count($importFeed['mapped_content'])}} products are imported</b>
        </div>

        <table class="table">
            <thead>
            <tr>
                <td>Title</td>
                <td>Content Body</td>
                <td>Price</td>
                <td>Created at</td>
            </tr>
            </thead>
            <tbody>
            @foreach($importFeed['mapped_content'] as $content)
                <tr>
                    <td>{{$content['title']}}</td>
                    <td>{{$content['content_body']}}</td>
                    <td>
                        @if(isset($content['price']))
                        {{$content['price']}}
                        @endif
                    </td>
                    <td>{{$content['created_at']}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

    @endif
</div>
