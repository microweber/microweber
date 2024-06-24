@props([ 'content_type' => '', 'subtype' => '','parent' => ''])

<div>


    <div id="mw-add-page-tree-holder">

    </div>

    @script




    <script>
        document.addEventListener('livewire:initialized', async () => {

            var opts = {
                 data: {
                    content_type: '{{ $content_type }}',
                    subtype: '{{ $subtype }}',
                    parent: '{{ $parent }}'
                }
            }

            pagesTreeAddPage = await mw.widget.tree('#mw-add-page-tree-holder');

        })
    </script>
    @endscript

</div>


