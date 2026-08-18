<?php

declare(strict_types=1);

namespace Tests\Browser\Support;

/**
 * Shared JS snippets for live-edit tests that may open either the
 * legacy iframe slide-over or the new mw.dialog Livewire host.
 */
final class MwDialogLiveEditHelpers
{
    public static function settingsSurfaceReadyScript(): string
    {
        return <<<'JS'
            return (function () {
                if (document.querySelector('[data-testid="filament-mw-dialog-inner"], .mw-dialog.mw-filament-mw-dialog-window, [data-mw-dialog-skin="1"].active')) {
                    return 1;
                }
                var iframes = Array.from(document.querySelectorAll('iframe'));
                if (iframes.some(function (f) { return (f.src || '').indexOf('module-settings') !== -1; })) {
                    return 1;
                }
                return 0;
            })();
        JS;
    }

    public static function clickCreateTableActionScript(): string
    {
        return <<<'JS'
            return (function () {
                var roots = [document];
                var iframe = Array.from(document.querySelectorAll('iframe')).find(function (f) {
                    return (f.src || '').indexOf('module-settings') !== -1 && f.contentDocument;
                });
                if (iframe && iframe.contentDocument) {
                    roots.push(iframe.contentDocument);
                }
                for (var r = 0; r < roots.length; r++) {
                    var btn = Array.from(roots[r].querySelectorAll('button, a')).find(function (b) {
                        var attrs = b.attributes;
                        for (var i = 0; i < attrs.length; i++) {
                            var a = attrs[i];
                            if (!a.name.startsWith('wire:click')) continue;
                            if ((a.value || '').indexOf("mountAction('create'") !== -1) return true;
                        }
                        return false;
                    });
                    if (btn) {
                        btn.click();
                        return 'OK';
                    }
                }
                return 'NO_NEW_BTN';
            })();
        JS;
    }

    public static function findMountedActionFormScript(): string
    {
        return <<<'JS'
            return (function () {
                var isVisible = function (el) {
                    var rect = el.getBoundingClientRect();
                    if (rect.width === 0 || rect.height === 0) return false;
                    var cs = getComputedStyle(el);
                    return cs.display !== 'none' && cs.visibility !== 'hidden';
                };
                var ok = ['callMountedAction', 'callMountedTableAction'];
                var roots = [document];
                var iframe = Array.from(document.querySelectorAll('iframe')).find(function (f) {
                    return (f.src || '').indexOf('module-settings') !== -1 && f.contentDocument;
                });
                if (iframe && iframe.contentDocument) {
                    roots.push(iframe.contentDocument);
                }
                for (var r = 0; r < roots.length; r++) {
                    var found = Array.from(roots[r].querySelectorAll('form')).some(function (f) {
                        var v = f.getAttribute('wire:submit.prevent') || f.getAttribute('wire:submit');
                        return ok.indexOf(v) !== -1 && isVisible(f);
                    });
                    if (found) return 1;
                }
                return 0;
            })();
        JS;
    }
}
