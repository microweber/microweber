<div>

    @php
        $editorId = uniqid();
    @endphp



   <div  wire:key="wire-scripts-key-{{$editorId}}">
       <div class="m-1">
           <textarea {!! $attributes->merge([]) !!} id="editor-{{$editorId}}"></textarea>
       </div>
       <script>
           mw.require('editor.js');


               let mwEditorId{{$editorId}} = mw.Editor({
                   selector: '#editor-{{$editorId}}',
                   mode: 'div',
                   smallEditor: false,
                   minHeight: 250,
                   maxHeight: '50vh',
                   interactionControls: [],
                   controls: [
                       ['italic', 'underline', 'strikeThrough', 'removeFormat','textBackgroundColor','textColor']
                   ]

               });

               $(mwEditorId{{$editorId}}).on('change', function (e, val) {

                   let target{{$editorId}} = document.getElementById('editor-{{$editorId}}');

                   var event{{$editorId}} = new Event('input');
                   target{{$editorId}}.dispatchEvent(event{{$editorId}});

               });



       </script>
   </div>
</div>
