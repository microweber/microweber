<div>

    <table class="table">
        <thead>
            <tr>
                <td>Field</td>
                <td>Field Value</td>
                <td style="width:130px">Map To</td>
            </tr>
        </thead>
        <tbody>
            @if(!empty($this->data))
                @foreach($this->data as $key=>$value)
                <tr>
                    <td>{{$key}}</td>
                    <td>{{$value}}</td>
                    <td>
                    @livewire('import-export-tool::field-map-dropdown-item', [

                    ])
                    </td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>

</div>
