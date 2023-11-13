@php

$editContentLink = '';
$editContentLinkClass = '';
if(isset($content->id)){
    $editContentLink = route('admin.content.edit', $content->id);
}

if($content && method_exists($content, 'editLink')){
   $editContentLink = $content->editLink();
}



if(isset($isIframe) && $isIframe == true) {

    $editContentLinkClass = 'mw-edit-content-link-picture-iframe js-open-in-modal';
}


@endphp

<a href="{{$editContentLink}}" class="mw-edit-content-link-picture {{$editContentLinkClass}}">
@if($content->media()->first())


    <div class="picture-blade-bg-img border-radius-0 border-0" style="background-image: url('{{$content->thumbnail(640,480, true)}}')">


    </div>
@else
    @include('content::admin.content.livewire.components.icon', ['content'=>$content])
@endif
</a>
