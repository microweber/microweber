<div>

       <div class="grid grid-cols-2 gap-4">
           @foreach($getEmailTemplates() as $template)

               <button class="group rounded-md border border-gray-500/10 bg-white hover:shadow-xl"
                       wire:click="startWithTemplate('startup')">
                   <div class="relative">
                       <div>
                           <img src="{{$template['screenshot']}}" alt="{{$template['name']}}" class="rounded-md w-full object-cover">
                       </div>
                       <div class="opacity-0 group-hover:opacity-100 transition absolute top-0 w-full h-full flex items-center gap-4 p-8 backdrop-blur-sm">
                       <div class="w-full flex items-center justify-center gap-4">
                           <x-filament::button
                               size="sm"
                               color="info"
                               href="https://filamentphp.com"
                               tag="a"
                           >
                               Start
                           </x-filament::button>
                           <x-filament::button
                               size="sm"
                               color="gray"
                               href="https://filamentphp.com"
                               tag="a"
                           >
                               Preview
                           </x-filament::button>
                       </div>
                       </div>
                   </div>
               </button>

           @endforeach

       </div>

</div>
