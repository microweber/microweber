<div>

    <style>
        .iframe-container {
            position: relative;
            height:350px;
        }
        .iframe {
            width: 200%;
            height: 200%;
            transform-origin: 0 0;
            position: absolute;
            left: 0;
            top: 0;
            transform: scale(0.5);
            border: 0px transparent;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const iframes = document.querySelectorAll('iframe');
            iframes.forEach(iframe => {
                iframe.src = iframe.dataset.src;
            });
        });
    </script>

       <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
           @foreach($getEmailTemplates() as $template)

               <div class="group rounded-md border border-gray-500/10 hover:shadow-xl">
                   <div class="relative">

                       <div class="iframe-container rounded-md">
                           <iframe
                               data-src="{{admin_url('modules/newsletter/preview-email-template')}}?filename={{$template['name']}}"
                               scrolling="no"
                               class="iframe rounded-md"></iframe>
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
