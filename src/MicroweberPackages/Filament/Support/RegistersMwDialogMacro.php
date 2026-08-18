<?php

declare(strict_types=1);

namespace MicroweberPackages\Filament\Support;

use Closure;
use Filament\Actions\Action;
use Filament\Support\Enums\SlideOverPosition;

/**
 * Registers the Filament Action fluent helpers ->mwDialog(), ->slideRight()
 * and ->slideLeft().
 *
 * ->mwDialog() is used instead of slide-over so the action modal is decorated
 * by mw.dialog (drag, autosize, close, backdrop) without tearing Livewire
 * event bindings off the form. ->slideRight()/->slideLeft() are directional
 * conveniences over Filament's own slide-over.
 */
final class RegistersMwDialogMacro
{
    public static function register(): void
    {
        if (! Action::hasMacro('mwDialog')) {
            Action::macro('mwDialog', function (array|bool $options = []): Action {
                /** @var Action $this */
                if ($options === false) {
                    return $this;
                }

                $opts = MwDialogOptions::merge(is_array($options) ? $options : []);

                $this->slideOver(false);

                $this->extraModalWindowAttributes(function () use ($opts): array {
                    $encoded = json_encode(
                        $opts,
                        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT
                    );

                    return [
                        'class' => 'mw-filament-mw-dialog',
                        'data-mw-dialog' => '1',
                        'data-mw-dialog-options' => is_string($encoded) ? $encoded : '{}',
                    ];
                }, merge: true);

                return $this;
            });
        }

        if (! Action::hasMacro('slideRight')) {
            Action::macro('slideRight', function (bool|Closure $condition = true): Action {
                /** @var Action $this */
                return $this->slideOver($condition)->slideOverPosition(SlideOverPosition::End);
            });
        }

        if (! Action::hasMacro('slideLeft')) {
            Action::macro('slideLeft', function (bool|Closure $condition = true): Action {
                /** @var Action $this */
                return $this->slideOver($condition)->slideOverPosition(SlideOverPosition::Start);
            });
        }
    }
}
