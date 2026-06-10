@php
/*

type: layout

name: Testimonial Cards

description: Testimonials using x-testimonial-card component

*/
@endphp

<style>
    #testimonials-{{ $params['id'] }} {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }
    #testimonials-{{ $params['id'] }} > .col {
        flex: 1 1 calc(33.333% - 20px);
        min-width: 280px;
    }
    @@media (max-width: 768px) {
        #testimonials-{{ $params['id'] }} > .col {
            flex: 1 1 100%;
        }
    }
</style>

<div id="testimonials-{{ $params['id'] }}">
    @if($testimonials->isEmpty())
        <p class="mw-pictures-clean">No testimonials added to the module. Please add your testimonials to see the content.</p>
    @else
        @foreach($testimonials as $item)
            @php
                $pixel = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';
                $src = isset($item->client_image) ? $item->client_image : $pixel;
            @endphp
            <div class="col">
                <x-testimonial-card
                    :name="$item->name ?? ''"
                    :content="$item->content ? \Illuminate\Support\Str::limit(strip_tags($item->content), 250) : ''"
                    :image="$src"
                    :company="$item->client_company ?? ''"
                    :role="$item->client_role ?? ''"
                    class="shadow-sm h-100"
                />
            </div>
        @endforeach
    @endif
</div>