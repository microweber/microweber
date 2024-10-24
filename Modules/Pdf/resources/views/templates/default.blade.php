<div class="mwembed mw-pdf" id="pdf-{{ $id }}">
    @if ($pdf != '')
        <iframe src="{{ $pdf }}" width="100%" height="600px"></iframe>
    @else
        {!! lnotif(_lang('Upload PDF File or paste URL.', "modules/pdf", true)) !!}
    @endif
</div>
