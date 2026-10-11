<div
class="clickable-column"
>

    <a href="{{$getUrl()}}"

        {{
           $attributes
               ->merge($getExtraAttributes(), escape: false)
               ->class([
                   'flex flex-col items-center py-8'
               ])
       }}
    >

        <x-filament-tables::columns.layout
            :components="$getComponents()"
            :record="$getRecord()"
            :record-key="$recordKey"
            :row-loop="$getRowLoop()"
        />

    </a>


</div>
