<?php

/*

type: layout
content_type: static
name: Contact Us
position: 6
description: Contact Us

*/


?>
@extends('templates.bootstrap::layouts.master')

@section('content')

    <div class="edit main-content" data-layout-container rel="content" field="content">
        <module type="layouts" template="titles/skin-1"/>
        <x-section class="py-5">
            <x-container>
                <x-row>
                    <x-col size="12" size-lg="6" size-xl="6" size-xxl="6" class="mb-4 mb-lg-0">
                        <div class="edit" field="contact-info" rel="page">
                            <x-section-heading tag="h3" align="start">Get in Touch</x-section-heading>
                            <p>We'd love to hear from you. Whether you have a question about our products, pricing, or anything else, our team is ready to answer all your questions.</p>
                            <div class="mt-4">
                                <p><i class="mdi mdi-map-marker"></i> <strong>Address:</strong> 21 Lebsack Harbor, Palo Alto, CA</p>
                                <p><i class="mdi mdi-phone"></i> <strong>Phone:</strong> 123-456-7890</p>
                                <p><i class="mdi mdi-email"></i> <strong>Email:</strong> mail@yourcompany.com</p>
                            </div>
                        </div>
                    </x-col>
                    <x-col size="12" size-lg="6" size-xl="6" size-xxl="6">
                        <module type="contact_form" template="skin-1"/>
                    </x-col>
                </x-row>
            </x-container>
        </x-section>
    </div>
@endsection
