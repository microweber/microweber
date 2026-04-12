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
        <section class="section py-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <div class="edit" field="contact-info" rel="page">
                            <h3>Get in Touch</h3>
                            <p>We'd love to hear from you. Whether you have a question about our products, pricing, or anything else, our team is ready to answer all your questions.</p>
                            <div class="mt-4">
                                <p><i class="mdi mdi-map-marker"></i> <strong>Address:</strong> 21 Lebsack Harbor, Palo Alto, CA</p>
                                <p><i class="mdi mdi-phone"></i> <strong>Phone:</strong> 123-456-7890</p>
                                <p><i class="mdi mdi-email"></i> <strong>Email:</strong> mail@yourcompany.com</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <module type="contact_form" template="skin-1"/>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
