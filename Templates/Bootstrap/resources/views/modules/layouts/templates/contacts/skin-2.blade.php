<?php
/*
type: layout
name: Contacts 2 - Details and form
position: 2
categories: Contact Us
*/
?>
<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section contacts-skin-2"
    field-name="layout-contacts-skin-2"
>
    <x-row class="g-5 align-items-start">
        <x-col size="12" size-md="5" size-lg="5" size-xl="5" size-xxl="5">
            <x-section-heading tag="h2" align="start">Contact information</x-section-heading>
            <p class="text-muted mb-4" data-mwplaceholder="Enter text here">
                Have a question or a project in mind? Use the details below or send us a message and we will respond within one business day.
            </p>

            <div class="d-flex align-items-start mb-4">
                <i class="mw-micon-Map me-3" style="font-size:1.5rem;"></i>
                <div>
                    <h6 class="mb-1" data-mwplaceholder="Enter title here">Our office</h6>
                    <p class="text-muted mb-0" data-mwplaceholder="Enter text here">123 Market Street, San Francisco, CA 94103</p>
                </div>
            </div>
            <div class="d-flex align-items-start mb-4">
                <i class="mw-micon-Phone me-3" style="font-size:1.5rem;"></i>
                <div>
                    <h6 class="mb-1" data-mwplaceholder="Enter title here">Phone</h6>
                    <p class="text-muted mb-0" data-mwplaceholder="Enter text here">+1 (555) 012-3456</p>
                </div>
            </div>
            <div class="d-flex align-items-start mb-4">
                <i class="mw-micon-Mail me-3" style="font-size:1.5rem;"></i>
                <div>
                    <h6 class="mb-1" data-mwplaceholder="Enter title here">Email</h6>
                    <p class="text-muted mb-0" data-mwplaceholder="Enter text here">hello@example.com</p>
                </div>
            </div>
            <div class="d-flex align-items-start">
                <i class="mw-micon-Clock me-3" style="font-size:1.5rem;"></i>
                <div>
                    <h6 class="mb-1" data-mwplaceholder="Enter title here">Working hours</h6>
                    <p class="text-muted mb-0" data-mwplaceholder="Enter text here">Monday &ndash; Friday, 9am to 6pm</p>
                </div>
            </div>
        </x-col>

        <x-col size="12" size-md="7" size-lg="7" size-xl="7" size-xxl="7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-lg-5">
                    <h4 class="mb-4" data-mwplaceholder="Enter title here">Send us a message</h4>
                    <form>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="contact-name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="contact-name" placeholder="Your name">
                            </div>
                            <div class="col-md-6">
                                <label for="contact-email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="contact-email" placeholder="you@example.com">
                            </div>
                            <div class="col-12">
                                <label for="contact-subject" class="form-label">Subject</label>
                                <input type="text" class="form-control" id="contact-subject" placeholder="How can we help?">
                            </div>
                            <div class="col-12">
                                <label for="contact-message" class="form-label">Message</label>
                                <textarea class="form-control" id="contact-message" rows="5" placeholder="Write your message here..."></textarea>
                            </div>
                            <div class="col-12 mt-4">
                                <module type="btn" id="{{ $params['id'] }}-btn-send" button_style="btn-primary" button_size="btn-lg px-5" text="Send message"/>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </x-col>
    </x-row>
</x-layout-section>
