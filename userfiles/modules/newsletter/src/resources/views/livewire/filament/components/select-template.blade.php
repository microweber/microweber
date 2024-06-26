<div>

       <div class="grid grid-cols-3 gap-4">
           @foreach($getEmailTemplates() as $template)

               <a href="{{route('filament.admin.pages.newsletter.template-editor')}}">
                   <div class="bg-white rounded shadow h-[20rem]">
                       <img src="{{$template['screenshot']}}" alt="{{$template['name']}}" class="w-full h-[10rem] object-cover">
                   </div>
               </a>

           @endforeach

       </div>

</div>
