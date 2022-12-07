@once
    <small class="text-muted">Attached files:</small>
@endonce
@foreach ($val as $keyInner => $valInner)
    @foreach($valInner as $k => $v)
        <div>
            <small class="text-muted"><?php echo $k; ?></small> <br />
            @if(isset($v['url']))
            <a href="<?php echo $v['url']; ?>" target="_blank">
                <i class="mdi {{ pathinfo($v['file_name'], PATHINFO_EXTENSION) == 'pdf' ? 'mdi-pdf-box' : 'mdi-file-check' }} text-primary mdi-18px"></i> <?php echo str_limit(basename($v['url']),30); ?>
                (<?php echo app()->format->human_filesize($v['file_size']); ?>)
            </a>
            @endif
        </div>
    @endforeach
@endforeach
