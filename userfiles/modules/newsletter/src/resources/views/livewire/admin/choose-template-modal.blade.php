<div>

    <div class="d-flex justify-content-end">
        <button type="button" class="btn-close" wire:click="$emit('closeModal', true)"></button>
    </div>

    <div class="text-center mt-4">
        <h3>
            Select a Email Template
        </h3>
    </div>

    <div class="mt-4 mb-4">
        <div class="row">
            @foreach($emailTemplates as $emailTemplate)
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div>
                                <iframe src="">
                                    <p>Your browser does not support iframes.</p>
                                </iframe>
                            </div>
                            <div class="text-center mt-2">
                                <button class="btn btn-primary" wire:click="selectTemplate({{ $emailTemplate['name'] }})">Select</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
