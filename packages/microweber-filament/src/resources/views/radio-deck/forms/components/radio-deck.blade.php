@php
    use Filament\Support\Enums\IconPosition;
    use Filament\Support\Enums\Alignment;
    use Filament\Support\Enums\IconSize;

    $id = $getId();
    $isDisabled = $isDisabled();
    $isMultiple = $isMultiple();
    $statePath = $getStatePath();
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <x-filament::grid :default="$getColumns('default')" :sm="$getColumns('sm')" :md="$getColumns('md')" :lg="$getColumns('lg')" :xl="$getColumns('xl')"
        :two-xl="$getColumns('2xl')" is-grid @class(['gap-5'])>
        @foreach ($getOptions() as $value => $label)
            @php
                $shouldOptionBeDisabled = $isDisabled || $isOptionDisabled($value, $label);
            @endphp

            <label class="flex cursor-pointer gap-x-3">
                <input @disabled($shouldOptionBeDisabled) id="{{ $id }}-{{ $value }}"
                    name="{{ $id }}"
                    type="{{  $isMultiple ? 'checkbox' : 'radio' }}"
                    value="{{ $value }}" wire:loading.attr="disabled"
                    {{ $applyStateBindingModifiers('wire:model') }}="{{ $statePath }}"
                    {{ $getExtraInputAttributeBag()->class(['peer hidden']) }} />

                @php
                    $iconExists = $hasIcons($value);
                    $iconPosition = $getIconPosition();
                    $alignment = $getAlignment();
                    $direction = $getDirection();
                    $gap = $getGap();
                    $padding = $getPadding();
                    $color = $getColor();
                    $icon = $getIcon($value);
                    $iconSize = $getIconSize();
                    $iconSizeSm = $getIconSizes('sm');
                    $iconSizeMd = $getIconSizes('md');
                    $iconSizeLg = $getIconSizes('lg');
                    $descriptionExists = $hasDescription($value);
                    $description = $getDescription($value);
                @endphp
                <div {{ $getExtraCardsAttributeBag()->class([
                    'flex w-full text-sm leading-6 rounded-lg bg-white dark:bg-gray-900',
                    $padding ?: 'px-4 py-2',
                    $gap ?: 'gap-5',
                    match ($direction) {
                        'column' => 'flex-col',
                        default => 'flex-row',
                    },
                    $iconExists
                        ? match ($iconPosition) {
                            IconPosition::Before, 'before' => 'justify-start',
                            IconPosition::After, 'after' => 'justify-between flex-row-reverse',
                            default => 'justify-start',
                        }
                        : 'justify-start',
                    match ($alignment) {
                        Alignment::Center, 'center' => 'items-center',
                        Alignment::Start, 'start' => 'items-start',
                        Alignment::End, 'end' => 'items-end',
                        default => 'items-center',
                    },
                    'ring-1 ring-gray-200 dark:ring-gray-700 peer-checked:ring-2',
                    'peer-disabled:bg-gray-100/50 dark:peer-disabled:bg-gray-700/50 peer-disabled:cursor-not-allowed',
                    match ($color) {
                        'gray' => 'peer-checked:ring-gray-600 dark:peer-checked:ring-gray-500',
                        default
                            => 'fi-color-custom peer-checked:ring-custom-600 dark:peer-checked:ring-custom-500',
                    },
                ]) }} @style([
                    \Filament\Support\get_color_css_variables($color, shades: [600, 500]) => $color !== 'gray',
                ])>
                    @if ($iconExists)
                        <x-filament::icon :icon="$icon" @class([
                            'flex-shrink-0',
                            match ($iconSize) {
                                IconSize::Small => $iconSizeSm ?: 'h-8 w-8',
                                'sm' => $iconSizeSm ?: 'h-8 w-8',
                                IconSize::Medium => $iconSizeMd ?: 'h-9 w-9',
                                'md' => $iconSizeMd ?: 'h-9 w-9',
                                IconSize::Large => $iconSizeLg ?: 'h-10 w-10',
                                'lg' => $iconSizeLg ?: 'h-10 w-10',
                                default => 'h-8 w-8',
                            },
                            match ($color) {
                                'gray' => 'fi-color-gray text-gray-600 dark:text-gray-500',
                                default => 'fi-color-custom text-custom-600 dark:text-custom-500',
                            },
                        ]) @style([
                            \Filament\Support\get_color_css_variables($color, shades: [600, 500]) => $color !== 'gray',
                        ]) />
                    @endif
                    <div {{ $getExtraOptionsAttributeBag()->merge(['class' =>'place-items-start']) }}>
                        <span class="font-medium text-gray-950 dark:text-white">
                            {{ $label }}
                        </span>

                        @if ($descriptionExists)
                            <p {{ $getExtraDescriptionsAttributeBag()->merge(['class' =>'text-gray-500 dark:text-gray-400']) }}>
                                {{ $description }}
                            </p>
                        @endif
                    </div>
                </div>
            </label>
        @endforeach
    </x-filament::grid>
</x-dynamic-component>
