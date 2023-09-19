<div>

    @php
        $editorId = uniqid();
    @endphp

 <div class="m-1">
     <textarea {!! $attributes->merge([]) !!} id="editor-{{$editorId}}"></textarea>
 </div>

   <div wire:ignore wire:key="wire-scripts-key-{{$editorId}}">
       <script>
           mw.require('editor.js');
           document.addEventListener('livewire:load', function () {
               let mwEditorId{{$editorId}} = mw.Editor({
                   selector: '#editor-{{$editorId}}',
                   mode: 'div',
                   smallEditor: false,
                   minHeight: 250,
                   maxHeight: '70vh',
                   controls: [
                       [
                           'undoRedo', '|',
                           {
                               group: {
                                   controller: 'bold',
                                   controls: ['italic', 'underline', 'strikeThrough']
                               }
                           },
                           '|',
                           {
                               group: {
                                   icon: 'mdi mdi-format-align-left',
                                   controls: ['align']
                               }
                           },
                           '|', 'format',
                           '|', 'link', 'unlink', 'removeFormat'
                       ],
                   ]
               });

               $(mwEditorId{{$editorId}}).on('change', function (e, val) {

                   let target{{$editorId}} = document.getElementById('editor-{{$editorId}}');

                   var event{{$editorId}} = new Event('input');
                   target{{$editorId}}.dispatchEvent(event{{$editorId}});

               });
           });
       </script>
   </div>
</div>
