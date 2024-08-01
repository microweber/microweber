@php
    $content = $getRecord();
@endphp

<script src="https://cdn.tailwindcss.com"></script>

<div class="flex gap-8 items-center w-full">

    <div>
        <img src="{{$content->thumbnail()}}" />
    </div>

   <div>
       @include('content::admin.content.filament.title-and-categories', ['content'=>$content])
   </div>

</div>
