<div>

    <style>
        .iframe-container {
            /*position: relative;*/
        }
        .iframe {
            /*width: 800px;
            height: 800px;
            transform-origin: 0 0;
            position: absolute;
            left: 0;
            top: 0;
            transform: scale(0.37);
            border: 0px transparent;*/
        }
    </style>

       <div class="grid grid-cols-3 gap-4">
           @foreach($getEmailTemplates() as $template)

               <div class="group rounded-md border border-gray-500/10 bg-white hover:shadow-xl">
                   <div class="relative">

                       <div class="iframe-container">
                           <iframe
                               src="{{admin_url('modules/newsletter/preview-email-template')}}?filename={{$template['name']}}"
                               scrolling="no"
                               class="iframe"></iframe>
                       </div>

                       <div class="opacity-0 group-hover:opacity-100 transition absolute top-0 w-full h-full flex items-center gap-4 p-8 backdrop-blur-sm">
                       <div class="w-full flex items-center justify-center gap-4">
                           <x-filament::button
                               size="sm"
                               color="info"
                               wire:click="startWithTemplate('{{$template['name']}}')"
                               tag="a"
                           >
                               Start
                           </x-filament::button>
                           <x-filament::button
                               size="sm"
                               color="gray"
                               href="{{admin_url('modules/newsletter/preview-email-template')}}?filename={{$template['name']}}"
                               tag="a"
                               target="_blank"
                           >
                               Preview
                           </x-filament::button>
                       </div>
                       </div>
                   </div>
               </div>

           @endforeach

       </div>

</div>
