<div>

    <div class="cf-custom-fields-table-wrap">
        {{ $this->table }}
    </div>

    {{-- task-2026-09-30-cfreorder — make the custom-fields table drag-reorderable
         WITHOUT toggling Filament's "Reorder records" mode (that mode hides the
         Edit/Delete actions). An always-visible drag handle column (.cf-reorder-handle)
         is added in ListCustomFields; here we bind SortableJS to the table body
         and, on drop, call the component's reorderTable() with the new id order
         (read from each row's wire:key). Wrapped in @assets because this view is
         injected into the Live Edit module-settings modal via a Livewire morph,
         and morphdom does NOT execute injected inline <script> tags. --}}
    @assets
    <script>
        (function () {
            if (window.__cfReorderInit) { return; }
            window.__cfReorderInit = true;

            var recordIdFromRow = function (tr) {
                var key = tr.getAttribute('wire:key') || '';
                var m = key.match(/\.table\.records\.(.+)$/);
                return m ? m[1] : null;
            };

            var bind = function (wrap) {
                if (!wrap || !window.Sortable) { return; }
                var tbody = wrap.querySelector('.fi-ta-table tbody');
                if (!tbody || !tbody.querySelector('.cf-reorder-handle')) { return; }
                // Re-bind if Livewire replaced the tbody element.
                if (tbody.__cfSortable && tbody.__cfSortable.el === tbody) { return; }
                if (tbody.__cfSortable) { try { tbody.__cfSortable.destroy(); } catch (e) {} }

                var component = wrap.closest('[wire\\:id]');

                tbody.__cfSortable = window.Sortable.create(tbody, {
                    handle: '.cf-reorder-handle',
                    draggable: 'tr',
                    animation: 150,
                    // Use the mouse/pointer fallback instead of native HTML5 DnD:
                    // more reliable on touch devices and inside the Live-Edit
                    // modal (native DnD misbehaves with Livewire-managed rows).
                    forceFallback: true,
                    fallbackTolerance: 3,
                    onEnd: function () {
                        // Build the new id order from the real record rows only —
                        // skip SortableJS's ghost/clone/chosen helper rows (they can
                        // carry a duplicated or missing wire:key) and de-duplicate,
                        // so reorderTable never receives an empty/garbage array (which
                        // builds invalid `case end ... 0 = 1` SQL and 500s).
                        var seen = {};
                        var ids = Array.prototype.slice.call(tbody.querySelectorAll('tr'))
                            .filter(function (tr) {
                                return !tr.classList.contains('sortable-ghost')
                                    && !tr.classList.contains('sortable-fallback')
                                    && !tr.classList.contains('sortable-chosen');
                            })
                            .map(recordIdFromRow)
                            .filter(function (v) {
                                if (v === null || v === '' || seen[v]) { return false; }
                                seen[v] = true;
                                return true;
                            });
                        if (ids.length < 2) { return; }
                        var id = component ? component.getAttribute('wire:id') : null;
                        if (id && window.Livewire) {
                            var c = window.Livewire.find(id);
                            if (c) { c.call('reorderTable', ids); }
                        }
                    },
                });
            };

            // task-2026-09-30-cflines — remove ALL the divider lines from the
            // custom-fields table (header band, column-header row, and every body
            // row/cell). The theme draws these with high-specificity !important
            // rules (e.g. body.fi-panel-admin .fi-ta:has(.fi-ta-header-cell)
            // .fi-ta-header-cell), which a scoped stylesheet rule can't reliably
            // out-specify — so set the borders inline (inline !important always
            // wins) and re-apply after every Livewire re-render. Scoped to the
            // wrapper so no other admin table is touched.
            var LINE_SEL = [
                '.fi-ta-header-ctn',
                '.fi-ta-table thead', '.fi-ta-table thead tr', '.fi-ta-table thead th',
                '.fi-ta-table tbody', '.fi-ta-table tbody tr', '.fi-ta-table .fi-ta-row',
                '.fi-ta-table td', '.fi-ta-table th', '.fi-ta-table .fi-ta-cell',
                '.fi-ta-header-cell', '.fi-ta-table-stacked-header-cell',
                '.fi-ta-actions-header-cell', '.fi-ta-selection-cell', '.fi-ta-selection-header-cell'
            ].join(',');
            var stripBorders = function (wrap) {
                wrap.querySelectorAll(LINE_SEL).forEach(function (el) {
                    el.style.setProperty('border-top', '0', 'important');
                    el.style.setProperty('border-bottom', '0', 'important');
                });
            };

            var scan = function () {
                document.querySelectorAll('.cf-custom-fields-table-wrap').forEach(function (wrap) {
                    bind(wrap);
                    stripBorders(wrap);
                });
            };

            // Initial bind + re-bind after Livewire re-renders the table (reorder,
            // add, delete, filter all re-render the tbody).
            var schedule = function () { setTimeout(scan, 60); };
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', schedule);
            } else {
                schedule();
            }
            document.addEventListener('livewire:navigated', schedule);
            if (window.Livewire && window.Livewire.hook) {
                window.Livewire.hook('morph.updated', schedule);
                window.Livewire.hook('commit', function (payload) {
                    if (payload && payload.succeed) { payload.succeed(schedule); }
                });
            }
        })();
    </script>
    @endassets
</div>
