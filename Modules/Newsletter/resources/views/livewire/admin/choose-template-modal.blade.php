<div>

    <div class="d-flex justify-content-end px-2 py-2">
        <button type="button" class="btn-close" @click="$dispatch('closeModal', true)"></button>
    </div>


    <h2 class="text-center">
        Select a Email Template
    </h2>


    <div class="mt-4 px-5 pb-5">
        <div class="row">
            {{-- audit-test 2026-05-07 PM TICKET-AV bundle: migrated `<div bg-image>`
                 to real `<img>` (closes CSS-injection vector + adds alt text for SR
                 users in the admin choose-template modal). --}}
            @foreach($emailTemplates as $emailTemplate)
                <div class="col-6 cursor-pointer mt-4"
                     wire:click="selectTemplate('{{ $emailTemplate['name'] }}','{{ $emailTemplate['filename'] }}')">
                    {{-- AI-115 / TICKET-CG (cycle-105 2026-05-09):
                         first preview is the LCP candidate — eager-load
                         it so the modal paints the first preview without
                         waiting for the lazy-load IntersectionObserver. --}}
                    <img src="{{ $emailTemplate['screenshot'] }}"
                         alt="{{ $emailTemplate['name'] ?? __('Email template preview') }}"
                         loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                         decoding="async"
                         class="d-block"
                         style="object-fit: contain;
                                object-position: center center;
                                width: 100%;
                                height: 300px;
                                border: 1px solid #ddd;
                                border-radius: 5px;">
                </div>
            @endforeach
        </div>
    </div>

</div>
