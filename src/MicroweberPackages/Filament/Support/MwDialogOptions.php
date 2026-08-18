<?php

declare(strict_types=1);

namespace MicroweberPackages\Filament\Support;

/**
 * Default option bag for Filament ->mwDialog() and the livewire-modal
 * mw.dialog wrapper. Keys match mw.Dialog JS defaults.
 */
final class MwDialogOptions
{
    /**
     * @return array<string, mixed>
     */
    public static function defaults(): array
    {
        return [
            'closeButton' => true,
            'overlay' => true,
            'overlayClose' => false,
            'autoHeight' => true,
            'autosize' => true,
            'autoScroll' => true,
            'draggable' => true,
            'closeOnEscape' => true,
            'width' => 800,
            'scrollMode' => 'inside',
            'skin' => 'mw-dialog',
        ];
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    public static function merge(array $overrides = []): array
    {
        return array_merge(self::defaults(), $overrides);
    }
}
