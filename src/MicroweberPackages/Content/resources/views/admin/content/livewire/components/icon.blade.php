
@if(isset($content['is_shop']) && $content['is_shop'] == 1)
<div class="img-circle-holder border-radius-0 border-0">
    <i class="mdi mdi-shopping mdi-48px text-muted text-opacity-5"></i>
</div>
@elseif (isset($content['content_type']) && $content['content_type'] == 'post')
    <div class="img-circle-holder border-radius-0 border-0">
        <i class="mdi mdi-file-document mdi-48px text-muted text-opacity-5"></i>
    </div>
@elseif (isset($content['subtype']) && $content['subtype'] == 'dynamic')
    <div class="img-circle-holder border-radius-0 border-0">
        <i class="mdi mdi-post-outline mdi-48px text-muted text-opacity-5"></i>
    </div>
@else
    <div class="img-circle-holder border-radius-0 border-0">
        <i class="mdi mdi-file-document mdi-48px text-muted text-opacity-5"></i>
    </div>
@endif
