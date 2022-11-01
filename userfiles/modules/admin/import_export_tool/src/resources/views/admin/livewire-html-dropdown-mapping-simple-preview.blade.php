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
                    @php
                        $data = [
                          ['key'=>'any','value'=>'Any'],
                          ['key'=>'published','value'=>'Published'],
                          ['key'=>'unpublished','value'=>'Unpublished'],
                        ];
                    @endphp
                    @livewire('admin-filter-item', [
                        'name'=> 'Map to',
                        'data'=>$data
                    ])
                    </td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>

</div>
