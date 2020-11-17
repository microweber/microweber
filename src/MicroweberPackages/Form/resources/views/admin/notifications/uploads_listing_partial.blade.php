@once
    <small class="text-muted">Attached files:</small>
@endonce
@foreach ($val as $keyInner => $valInner)
    @foreach($valInner as $k => $v)
         <p>
             <i class="mdi {{ pathinfo($v['file_name'], PATHINFO_EXTENSION) == 'pdf' ? 'mdi-pdf-box' : 'mdi-file-check' }} text-primary mdi-18px"></i>
             {{ $v['file_name'] }}
             <br />
         </p>
    @endforeach
@endforeach