@php
    $moduleId = $moduleId ?? null;
@endphp
<div>


    <style>
        .card {
            transition: transform 0.2s;
            border: 1px solid rgba(0,0,0,0.1);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .card-img-top {
            object-fit: cover;
        }

        .card-title {
            color: #333;
            margin-bottom: 0.75rem;
        }

        .card-text {
            color: #666;
        }

        .card-footer {
            border-top: 1px solid rgba(0,0,0,0.1);
        }

        .form-select:focus {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.25);
        }
    </style>
    <div class="section container-fluid">
        <div class="row pt-5">
            <div class="col-xl-3 mb-xl-0 mb-3">
                @include('modules.blog::livewire.blog.filters.search.index')
                @include('modules.blog::livewire.blog.filters.categories.index')
                @include('modules.blog::livewire.blog.filters.tags.index')
            </div>

            <div class="col-xl-9">
                @include('modules.blog::livewire.blog.filters.top.index')

                <div class="row">
                    @forelse($posts as $post)
                        <div class="col-md-6 mb-5">
                            @include('modules.blog::livewire.blog.post-card', ['post' => $post])
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info">
                                {{ _e("No posts found") }}
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $posts->links() }}
                </div>
            </div>
        </div>
    </div>


</div>
