<?php
/*
type: layout
name: Testimonials 2 - Quote cards
position: 2
categories: Testimonials
*/
?>
@php
    $quotes = [
        ['text' => 'This product changed how our team works. Setup took minutes and the results were immediate.', 'name' => 'Jordan Lee', 'role' => 'Product Lead, Acme'],
        ['text' => 'Support is incredible and the platform just keeps getting better with every release.', 'name' => 'Priya Nair', 'role' => 'Founder, Brightside'],
        ['text' => 'We cut our workflow time in half. I recommend it to every team I talk to.', 'name' => 'Marcus Bell', 'role' => 'Operations Manager, Northwind'],
    ];
@endphp
<x-layout-section
    :params="$params" :classes="$classes" :layout-classes="$layout_classes ?? ''"
    section-class="section testimonials-skin-2"
    field-name="layout-testimonials-skin-2"
>
    <x-row class="text-center">
        <x-col size="12" size-lg="8" class="mx-auto">
            <x-section-heading tag="h2" subtitle="Trusted by teams around the world.">
                Loved by our customers
            </x-section-heading>
        </x-col>
    </x-row>

    <div class="row row-cols-1 row-cols-md-3 g-4 mt-2">
        @foreach ($quotes as $q)
            <div class="col">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <div class="mb-3">
                            <i class="mw-micon-Star text-warning"></i>
                            <i class="mw-micon-Star text-warning"></i>
                            <i class="mw-micon-Star text-warning"></i>
                            <i class="mw-micon-Star text-warning"></i>
                            <i class="mw-micon-Star text-warning"></i>
                        </div>
                        <p class="card-text flex-grow-1" data-mwplaceholder="Quote text">{{ $q['text'] }}</p>
                        <div class="d-flex align-items-center mt-3">
                            <div class="ratio ratio-1x1 rounded-circle bg-light me-3" style="max-width:48px;"></div>
                            <div>
                                <p class="fw-semibold mb-0" data-mwplaceholder="Name">{{ $q['name'] }}</p>
                                <p class="text-muted small mb-0" data-mwplaceholder="Role">{{ $q['role'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-layout-section>
