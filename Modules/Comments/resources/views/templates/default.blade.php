@if($settings['show_on_current_content'])
<section style="background-color: #eee;">
    @if(!$settings['require_login'] || auth()->check())
        <div class="container my-5 py-5 text-dark">
            <div class="row d-flex justify-content-center">
                <div class="col-md-12">
                    <livewire:comments::user-comment-reply
                         wire:key="reply-{{$params['id']}}"
                        :rel_type="$rel_type"
                        :rel_id="$rel_id"
                        :allow_replies="$settings['allow_replies']"
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
                    :rel_type="$rel_type"
                    :rel_id="$rel_id"
                    :show_user_avatar="$settings['show_user_avatar']"
                    :allow_replies="$settings['allow_replies']"
                    :comments_per_page="$settings['comments_per_page']"
                    :sort_order="$settings['sort_order']"
                />
            </div>
        </div>
    </div>

    @stack('comments-scripts')
</section>
@endif
