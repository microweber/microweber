<div class="mb-6 p-2 flex flex-col gap-4">

{{--


    <div
        @click="(() => {
            try {
                let targetElement = null;

                // First try to get the canvas document and find the first edit field with data-layout-container
                if (window.mw?.top()?.app?.canvas?.getDocument) {
                    const canvasDoc = window.mw.top().app.canvas.getDocument();
                    if (canvasDoc) {
                        targetElement = canvasDoc.querySelector('[data-layout-container]');
                        console.log('Found layout container:', targetElement);
                    }
                }

                // Fallback to other methods if no layout container found
                if (!targetElement) {
                    targetElement =
                        window.mw.top().app.liveEdit.getSelectedNode()
                        || window.mw.top().app.liveEdit.getSelectedElementNode()
                        || window.mw.top().app.liveEdit.elementHandle.getTarget()
                        || window.mw.top().app.liveEdit.layoutHandle.getTarget()
                        || document.body;
                }

                console.log('Inserting layout into element:', targetElement);
                if (targetElement && window.mw?.app?.editor?.dispatch) {
                    window.mw.app.editor.dispatch('insertLayoutRequest', targetElement);
                } else {
                    console.warn('Cannot insert layout: target element or editor not available');
                }
            } catch (error) {
                console.error('Error inserting layout into page:', error);
            }
        })()"
        class="cursor-pointer flex gap-6 p-4 group hover:scale-105 transition duration-150 hover:bg-blue-500/10 rounded-md w-full">
        <div class="flex items-center justify-center w-20 h-20 bg-blue-500/5 transition duration-150 group-hover:bg-white shadow-md rounded p-4">
            @svg('heroicon-o-squares-plus', "h-10 w-10 text-black/80 dark:text-white")
        </div>
        <div class="flex flex-col gap-2 w-full">
            <div class="font-bold">
                Add layout to the current page
            </div>
            <div class="text-sm">
                Insert a new layout section into your page
            </div>
        </div>
    </div>



--}}








    @foreach($actions as $action)

        {{-- task-2026-05-05-66e507 (QW1) — drunk-designer external
             audit flagged role=button divs as the most important a11y
             fix on this surface. Switched to a real <button> element
             so keyboard activation, focus rings, and form-control
             semantics work natively without manual wiring. The
             previous onkeydown shim is no longer needed —
             <button>'s default behaviour fires click on Enter and
             on Space-keyup, exactly per ARIA APG. --}}
        <button
            type="button"
            wire:click="replaceMountedAction('{{ $action['action'] }}')"
            aria-label="{{ $action['title'] }}: {{ $action['description'] }}"
            class="mw-add-content-modal-action-wrapper cursor-pointer flex gap-6 p-5 group transition duration-150 hover:bg-blue-500/10 dark:hover:bg-white/5 rounded-lg w-full border border-gray-100 dark:border-gray-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500 text-start bg-transparent">
            <div class="flex items-center justify-center w-20 h-20 bg-blue-500/5 transition duration-150 group-hover:bg-blue-500/10 dark:group-hover:bg-white/10 rounded-lg p-4">
                @svg($action['icon'], "h-10 w-10 text-black/80 dark:text-white")
            </div>
            <div class="flex flex-col gap-2 w-full">
                <div class="font-bold">
                    {{ $action['title'] }}
                </div>
                <div class="text-sm">
                    {{ $action['description'] }}
                </div>
            </div>
        </button>

    @endforeach
</div>
