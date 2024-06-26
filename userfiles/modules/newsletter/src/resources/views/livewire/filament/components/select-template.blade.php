<div>

       <div class="grid grid-cols-3 gap-4">
           @foreach($getEmailTemplates() as $template)

               <div class="group rounded-md border border-gray-500/10 bg-white hover:shadow-xl">
                   <div class="relative">
                       <div>
                           <img src="{{$template['screenshot']}}" alt="{{$template['name']}}" class="rounded-md w-full object-top object-cover max-h-[26rem]">
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
