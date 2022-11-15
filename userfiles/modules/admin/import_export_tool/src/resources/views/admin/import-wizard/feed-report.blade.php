<div>

    @if(isset($importFeed['mapped_content']))


        <div class="mb-2 text-center">
            <b>{{count($importFeed['mapped_content'])}} products are imported</b>
        </div>


        @foreach($importFeed['mapped_content'] as $content)
        <table class="table table-bordered">
            <tbody>
                @foreach($content as $columnKey=>$columnValue)
                <tr>
                    <td>
                        {{$columnKey}}
                    </td>
                    <td>
                        @if(is_string($columnValue))
                            {{$columnValue}}
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endforeach

    @endif
</div>
