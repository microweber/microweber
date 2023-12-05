<div>

    <div class="d-flex justify-content-end px-2 py-2">
        <button type="button" class="btn-close" wire:click="$emit('closeModal', true)"></button>
    </div>

    <div class="text-center">
        <h3>
            Select a Email Template
        </h3>
    </div>

    <div class="mt-4 px-5 pb-5">
        <div class="row">
            @foreach($emailTemplates as $emailTemplate)
                <div class="col-6 cursor-pointer mt-4"
                     wire:click="selectTemplate('{{ $emailTemplate['name'] }}','{{ $emailTemplate['filename'] }}')">
                    <div style="
                            background-image: url('{{ $emailTemplate['screenshot'] }}');
                            background-size: contain;
                            background-position: center center;
                            background-repeat: no-repeat;
                            width: 100%;
                            height: 300px;
                            border: 1px solid #ddd;
                            border-radius: 5px;
                        ">
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
