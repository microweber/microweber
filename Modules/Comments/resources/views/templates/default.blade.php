<section style="background-color: #eee;">

    <div class="container my-5 py-5 text-dark">
        <div class="row d-flex justify-content-center">
            <div class="col-md-12">
                <livewire:comments::user-comment-reply wire:key="{{uniqid()}}" rel_id="{{content_id()}}" />
            </div>
        </div>
    </div>

    <div class="container py-2 text-dark">
        <div class="row d-flex justify-content-center">
            <div class="col-md-12">
                <livewire:comments::user-comment-list wire:key="{{uniqid()}}" rel_id="{{content_id()}}" />
            </div>
        </div>
    </div>

    @stack('comments-scripts')

</section>
