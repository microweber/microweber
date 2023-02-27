<div>

    @if(isset($importFeed['mapped_content']))


        <div class="mb-2 text-center">
            <b>{{count($importFeed['mapped_content'])}} items are imported</b>
        </div>

        @foreach($mappedContent->items() as $content)
            <div class="table-responsive">
              <table class="table table-bordered">
                <tbody>
                @foreach($content as $columnKey=>$columnValue)
                    <tr>
                        <td style="width: 100px">
                            {{$columnKey}}
                        </td>
                        <td>
                            <div style="width:800px; overflow:hidden">
                            @if(is_string($columnValue))
                                {{$columnValue}}
                            @endif

                            @if($columnKey == 'media_urls')
                                @if(is_array($columnValue))
                                    @foreach($columnValue as $picture) <img src="{{$picture}}" style="width:120px;" /> @endforeach
                                @endif
                            @else

                                @if(is_array($columnValue))
                                      <pre>  {!! json_encode($columnValue, JSON_UNESCAPED_UNICODE|JSON_HEX_QUOT|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT) !!} </pre>
                                @endif

                            @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            </div>
        @endforeach

     {{$mappedContent->links('livewire-tables::specific.bootstrap-4.pagination')}}

    @endif
</div>
