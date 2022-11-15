<div>

    @if(isset($importFeed['mapped_content']))


        <div class="mb-2 text-center">
            <b>{{count($importFeed['mapped_content'])}} products are imported</b>
        </div>

        @php
        $showColumns = [];
         foreach($importFeed['mapped_content'][0] as $field=>$fieldValue) {
            if (is_string($field) && is_string($fieldValue)) {
                $showColumns[] = $field;
            }

            if (is_array($fieldValue)) {
                foreach($fieldValue as $mlField=>$mlFieldValue) {
                  $showColumns[] = $mlField;
                }
            }
          }
        @endphp

        <table class="table">
            <thead>
            <tr>
                @foreach($showColumns as $column)
                    <td>{{$column}}</td>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach($importFeed['mapped_content'] as $content)
                <tr>

                    @foreach($showColumns as $column)

                        @if(isset($content[$column]))

                            @if($column == 'pictures')
                                <td>
                                    @if(is_array($content['pictures']))
                                        @foreach($content['pictures'] as $picture) <img src="{{$picture}}" style="width:120px;" /> @endforeach
                                    @endif
                                </td>
                            @else
                                <td>{{$content[$column]}}</td>
                            @endif

                        @endif

                        @if(isset($content['multilanguage'][$column]))
                            <td>
                                @foreach($content['multilanguage'][$column] as $locale=>$value)

                                    @if(!empty($value))
                                        {{$value}}
                                    @else
                                        <span class="text-muted"> No {{$column}} in feed</span>
                                    @endif
                                    [{{$locale}}]   <br />
                                @endforeach
                            </td>
                        @endif

                    @endforeach

                </tr>
            @endforeach
            </tbody>
        </table>

    @endif
</div>
