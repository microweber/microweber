<div class="mwembed mw-pdf text-center" id="pdf-{{ $id }}">
    @if ($pdf != '')
        <iframe src="{{ $pdf }}" width="100%" height="600px"></iframe>
    @else
    <div class="btn btn-primary text-center ">
        {!! lnotif(_lang('Upload PDF File or paste URL.', "modules/pdf", true)) !!}
    </div>

    @endif
</div>
