@php
    $content = $getRecord();
@endphp

<div class="flex gap-8 py-4 items-center w-full">

    <div>
        @include('content::admin.content.filament.picture', ['content'=>$content])
    </div>

   <div>
       @include('content::admin.content.filament.title-and-categories', ['content'=>$content])
   </div>

</div>
