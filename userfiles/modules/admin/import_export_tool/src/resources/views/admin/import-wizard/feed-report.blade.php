<div>

    @if(isset($importFeed['mapped_content']))


        <div class="mb-2 text-center">
            <b>{{count($importFeed['mapped_content'])}} products are imported</b>
        </div>

        @foreach($mappedContent->items() as $content)
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

                            @if($columnKey == 'media_urls')
                                @if(is_array($columnValue))
                                    @foreach($columnValue as $picture) <img src="{{$picture}}" style="width:120px;" /> @endforeach
                                @endif
                            @else

                                @if(is_array($columnValue))
                                        {!! json_encode($columnValue, JSON_PRETTY_PRINT) !!}
                                @endif

                            @endif

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endforeach

     {{$mappedContent->links('livewire-tables::specific.bootstrap-4.pagination')}}

    @endif
</div>
