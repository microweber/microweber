@php
    $content = $getRecord();
@endphp

<div class="group flex gap-8 py-4 items-center justify-between w-full">

    <div class="w-full flex gap-2 items-center">
        <div>
            @include('modules.content::filament.admin.picture', ['content'=>$content])
        </div>
       <div class="w-full">
           @include('modules.content::filament.admin.title-and-categories', ['content'=>$content])
       </div>
    </div>

    <div class="flex items-center opacity-0 group-hover:opacity-100">
        <a class="" href="{{content_link($content->id)}}" target="_blank" data-bs-toggle="tooltip" x-data="{}" x-tooltip="{
            content: 'View',
            theme: $store.theme,
        }" aria-label="View" data-bs-original-title="View">

            <svg class="me-3" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M440-280H280q-83 0-141.5-58.5T80-480q0-83 58.5-141.5T280-680h160v80H280q-50 0-85 35t-35 85q0 50 35 85t85 35h160v80ZM320-440v-80h320v80H320Zm200 160v-80h160q50 0 85-35t35-85q0-50-35-85t-85-35H520v-80h160q83 0 141.5 58.5T880-480q0 83-58.5 141.5T680-280H520Z"/></svg>
        </a>

        <a class="" href="{{$content->editLink()}}" target="_top" data-bs-toggle="tooltip" aria-label="Live edit" data-bs-original-title="Edit" x-data="{}" x-tooltip="{
            content: 'Live Edit',
            theme: $store.theme,
        }">
            <svg class="me-3" fill="currentColor" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="18px" viewBox="0 0 24 24" width="18px"><g><rect fill="none" height="24" width="24"></rect></g><g><g><g><path d="M3,21l3.75,0L17.81,9.94l-3.75-3.75L3,17.25L3,21z M5,18.08l9.06-9.06l0.92,0.92L5.92,19L5,19L5,18.08z"></path></g><g><path d="M18.37,3.29c-0.39-0.39-1.02-0.39-1.41,0l-1.83,1.83l3.75,3.75l1.83-1.83c0.39-0.39,0.39-1.02,0-1.41L18.37,3.29z"></path></g></g></g></svg>
        </a>

        <a class="" href="{{$content->link()}}?editmode=y" target="_top" data-bs-toggle="tooltip" aria-label="Preview" data-bs-original-title="Customize" x-data="{}" x-tooltip="{
            content: 'Preview',
            theme: $store.theme,
        }">
            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 0 24 24" width="18px"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M12 6c3.79 0 7.17 2.13 8.82 5.5C19.17 14.87 15.79 17 12 17s-7.17-2.13-8.82-5.5C4.83 8.13 8.21 6 12 6m0-2C7 4 2.73 7.11 1 11.5 2.73 15.89 7 19 12 19s9.27-3.11 11-7.5C21.27 7.11 17 4 12 4zm0 5c1.38 0 2.5 1.12 2.5 2.5S13.38 14 12 14s-2.5-1.12-2.5-2.5S10.62 9 12 9m0-2c-2.48 0-4.5 2.02-4.5 4.5S9.52 16 12 16s4.5-2.02 4.5-4.5S14.48 7 12 7z"></path></svg>
        </a>
    </div>

</div>
