@php
    $state = $getState();
    if ($state instanceof \BackedEnum) {
        $state = $state->value;
    }
    $state = strval($state);

    $options = $getOptions();
    $size =  $getSize() ?? \BobiMicroweber\FilamentDropdownColumn\Enums\ButtonSize::Small;
    $color = $getColor($state) ?? 'gray';
    $recordKeyValue = $getRecordKey();

@endphp

<div
    wire:key="{{ $this->getId() }}.table.record.{{ $recordKeyValue }}.column.{{ $getName() }}.toggle-column.{{ $state ? 'true' : 'false' }}"
>


    <div
        x-data="{
            error: undefined,

            isLoading: false,

            name: @js($getName()),

            recordKey: @js($recordKeyValue),

            state: @js($state),
        }"

        {{
   $attributes
       ->merge($getExtraAttributes(), escape: false)
       ->class([
           'fi-ta-dropdown',
           'px-3 py-4' => ! $isInline(),
       ])
}}
    >


        <x-filament::dropdown placement="bottom-end">
            <x-slot name="trigger">


                <x-filament::button :size="$size" :color="$color">

                    <div class="flex gap-2 items-center">

                        @if ($icon = $getIcon($state))
                            <x-filament::icon
                                :icon="$icon"
                                class="fi-ta-icon-item h-5 w-5"
                            />
                        @endif

                        @if(isset($options[$state]))
                            {{ $options[$state] }}
                        @else
                            Unknown
                        @endif

                        <x-filament::icon icon="heroicon-o-chevron-down" class="w-5 h-5"/>

                    </div>
                </x-filament::button>


            </x-slot>

            <x-filament::dropdown.list>
                @foreach($options as $optionKey => $optionValue)
                    @php
                        $optionIcon = $getIcon($optionKey);
                    @endphp
                    <x-filament::dropdown.list.item

                        x-data="{
                           currentOptionKey: '{{ $optionKey }}'
                        }"

                        x-tooltip="error"
                        x-bind:class="{
                            'opacity-50 pointer-events-none': isLoading,
                        }"
                        x-on:click="async () => {

                             isLoading = true

                            const response = await $wire.updateTableColumnState(
                                name,
                                recordKey,
                               currentOptionKey,
                            )

                            error = response?.error ?? undefined

                            if (! error) {
                                state = response
                            }

                            isLoading = false

                           close();

                        }"
                        :icon="$optionIcon"
                    >
                        {{ $optionValue }}
                    </x-filament::dropdown.list.item>
                @endforeach
            </x-filament::dropdown.list>

        </x-filament::dropdown>

    </div>

</div>
