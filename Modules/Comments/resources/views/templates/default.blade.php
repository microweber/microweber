@if($settings['show_on_current_content'])
<section style="background-color: #eee;">
    @if(!$settings['require_login'] || auth()->check())
        <div class="container my-5 py-5 text-dark">
            <div class="row d-flex justify-content-center">
                <div class="col-md-12">
                    {{-- Livewire binds kebab-case attributes to camelCase mount params
                         ($relType/$relId). snake_case (:rel_id) does NOT bind and left
                         them null — the list leaked ALL comments onto every page and the
                         reply form saved comments with rel_id=null. task-2026-06-06-cmtbind --}}
                    <livewire:comments::user-comment-reply
                         wire:key="reply-{{$params['id']}}"
                        :rel-type="$rel_type"
                        :rel-id="$rel_id"
                        :allow-replies="$settings['allow_replies']"
                    />
                </div>
            </div>
        </div>
    @else
        <div class="container my-3 py-3 text-dark">
            <div class="alert alert-info">
                {{ _e('Please login to post comments') }}
            </div>
        </div>
    @endif

    <div class="container py-2 text-dark">
        <div class="row d-flex justify-content-center">
            <div class="col-md-12">
                <livewire:comments::user-comment-list
                    wire:key="list-{{$params['id']}}"
                    :rel-type="$rel_type"
                    :rel-id="$rel_id"
                    :show-user-avatar="$settings['show_user_avatar']"
                    :allow-replies="$settings['allow_replies']"
                    :comments-per-page="$settings['comments_per_page']"
                    :sort-order="$settings['sort_order']"
                />
            </div>
        </div>
    </div>

    @stack('comments-scripts')
</section>
@endif
