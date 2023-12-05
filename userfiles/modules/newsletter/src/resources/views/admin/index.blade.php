@extends('admin::layouts.app')

@section('content')

    <div>

    <div class="container px-5 mb-2">

        <div class="mt-3">
            <h2><?php _e('Newsletter PRO v2.0'); ?></h2>
        </div>

        <div>
            <style>
                .table td{
                    vertical-align: middle;
                }
            </style>

            <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
                <a class="btn btn-outline-dark justify-content-center gap-2 active" data-bs-toggle="tab" href="#subscribers"><i class="mdi mdi-format-list-bulleted-square mr-1"></i> <?php _e('Subscribers'); ?></a>
                <a class="btn btn-outline-dark justify-content-center gap-2" data-bs-toggle="tab" href="#list"><i class="mdi mdi-clipboard-text-outline mr-1"></i> <?php _e('Lists'); ?></a>
                <a class="btn btn-outline-dark justify-content-center gap-2" data-bs-toggle="tab" href="#campaigns"><i class="mdi mdi-email-check-outline mr-1"></i> <?php _e('Campaigns'); ?></a>
                <a class="btn btn-outline-dark justify-content-center gap-2" data-bs-toggle="tab" href="#templates"><i class="mdi mdi-view-dashboard-outline mr-1"></i> <?php _e('Templates'); ?></a>
                <a class="btn btn-outline-dark justify-content-center gap-2" data-bs-toggle="tab" href="#sender_accounts"><i class="mdi mdi-book-account-outline mr-1"></i> <?php _e('Sending accounts'); ?></a>
                <a class="btn btn-outline-dark justify-content-center gap-2" data-bs-toggle="tab" href="#settings"><i class="mdi mdi-cog-outline mr-1"></i> <?php _e('Settings'); ?></a>
            </nav>

            <div class="tab-content py-3">
                <div class="tab-pane fade show active" id="subscribers">
                    @livewire('admin-newsletter-subscribers-list')
                </div>

                <div class="tab-pane fade" id="list">
                    <module type="newsletter/lists"/>
                </div>

                <div class="tab-pane fade" id="campaigns">
                    <module type="newsletter/campaigns"/>
                </div>

                <div class="tab-pane fade" id="templates">
                    <module type="newsletter/templates"/>
                </div>

                <div class="tab-pane fade" id="sender_accounts">
                    <module type="newsletter/sender_accounts"/>
                </div>

                <div class="tab-pane fade" id="settings">
                    <module type="newsletter/privacy_settings" data-no-hr="true"/>
                    <module type="newsletter/settings" />
                </div>
            </div>

        </div>
    </div>
    </div>

@endsection
