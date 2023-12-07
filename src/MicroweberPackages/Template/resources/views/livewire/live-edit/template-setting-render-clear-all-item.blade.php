<div class="mt-2" style="background:#f7f7f7;border-radius:8px;padding: 12px;">
    <div>
        <b>{{$setting['title']}}</b>
    </div>
    <div class="mt-1">
        <small>{{$setting['description']}}</small>
    </div>




    <button
        x-on:click="(e) => {

                            mw.confirm('Are you sure you want to clear styles?',
                                function () {
                                    @foreach($setting['fieldSettings']['properties'] as $property)
                                    mw.top().app.cssEditor.setPropertyForSelector('{{ $selectorToApply }}', '{{$property}}', '');
                                    @endforeach
                                    @if(isset($rootSelector) and $rootSelector)

                                        mw.top().app.cssEditor.setPropertyForSelector('{{ $rootSelector }}', '{{$property}}', '');

                                        @if(isset($setting['selectors']))
                                            @foreach($setting['selectors'] as $selector)
                                                @foreach($setting['fieldSettings']['properties'] as $property)
                                                    @if($selector == ':root')
                                                     mw.top().app.cssEditor.setPropertyForSelector('{{ $rootSelector }}', '{{$property}}', '');
                                                     @else
                                                     mw.top().app.cssEditor.setPropertyForSelector('{{ $rootSelector }} {{ $selector }}', '{{$property}}', '');
                                                     @endif
                                                @endforeach
                                            @endforeach
                                        @endif
                                    @endif
                                   

                                });

                            }"
        class="btn btn-outline-dark" style="width:100%;margin-top:15px">
        <i class="fa fa-trash"></i> &nbsp; {{$setting['title']}}
    </button>
</div>
