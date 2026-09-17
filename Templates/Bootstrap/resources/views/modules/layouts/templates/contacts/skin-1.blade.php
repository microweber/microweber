<?php
/*
type: layout
name: Contacts 1 - Info cards
position: 1
categories: Contact Us
*/
?>
<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section contacts-skin-1"
    field-name="layout-contacts-skin-1"
>
    <x-row class="text-center">
        <x-col size="12" size-lg="8" class="mx-auto">
            <x-section-heading tag="h2">Get in touch</x-section-heading>
            <p class="lead text-muted" data-mwplaceholder="Enter text here">
                We would love to hear from you. Reach out through any of the channels below and our team will get back to you shortly.
            </p>
        </x-col>
    </x-row>

    <div class="row row-cols-1 row-cols-md-3 g-4 mt-4">
        <div class="col">
            <div class="card h-100 border-0 shadow-sm text-center">
                <div class="card-body p-4">
                    <i class="mw-micon-Map d-inline-block mb-3" style="font-size:2.5rem;"></i>
                    <h5 class="card-title" data-mwplaceholder="Enter title here">Address</h5>
                    <p class="card-text text-muted mb-0" data-mwplaceholder="Enter text here">
                        123 Market Street, Suite 200<br>San Francisco, CA 94103
                    </p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card h-100 border-0 shadow-sm text-center">
                <div class="card-body p-4">
                    <i class="mw-micon-Phone d-inline-block mb-3" style="font-size:2.5rem;"></i>
                    <h5 class="card-title" data-mwplaceholder="Enter title here">Call us</h5>
                    <p class="card-text text-muted mb-0" data-mwplaceholder="Enter text here">
                        +1 (555) 012-3456<br>Mon &ndash; Fri, 9am to 6pm
                    </p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card h-100 border-0 shadow-sm text-center">
                <div class="card-body p-4">
                    <i class="mw-micon-Mail d-inline-block mb-3" style="font-size:2.5rem;"></i>
                    <h5 class="card-title" data-mwplaceholder="Enter title here">Email</h5>
                    <p class="card-text text-muted mb-0" data-mwplaceholder="Enter text here">
                        hello@example.com<br>support@example.com
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-layout-section>
