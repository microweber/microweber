@extends('admin::layouts.app')

@section('content')

    <div>

        <div class="container px-5 mb-2">

            <div class="mt-3">
                <h1><?php _e('Newsletter PRO v2.0'); ?></h1>
            </div>

            <div>
                <style>
                    .table td{
                        vertical-align: middle;
                    }
                    .newsletter-navigation .mdi {
                        font-size:20px;
                    }
                </style>

                <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3 newsletter-navigation">
                    <a class="btn btn-outline-dark justify-content-center gap-2 <?php if(route_is('admin.newsletter.index')) : ?>active<?php endif;?>" href="<?php print route('admin.newsletter.index'); ?>"><i class="mdi mdi-email-check-outline mr-1"></i> <?php _e('Campaigns'); ?></a>
                    <a class="btn btn-outline-dark justify-content-center gap-2 <?php if(route_is('admin.newsletter.lists')) : ?>active<?php endif;?>" href="<?php print route('admin.newsletter.lists'); ?>"><i class="mdi mdi-clipboard-text-outline mr-1"></i> <?php _e('Lists'); ?></a>
                    <a class="btn btn-outline-dark justify-content-center gap-2 <?php if(route_is('admin.newsletter.subscribers')) : ?>active<?php endif;?>" href="<?php print route('admin.newsletter.subscribers'); ?>"><i class="mdi mdi-format-list-bulleted-square mr-1"></i> <?php _e('Subscribers'); ?></a>
                    <a class="btn btn-outline-dark justify-content-center gap-2 <?php if(route_is('admin.newsletter.templates')) : ?>active<?php endif;?>"  href="<?php print route('admin.newsletter.templates'); ?>"><i class="mdi mdi-view-dashboard-outline mr-1"></i> <?php _e('Templates'); ?></a>
                    <a class="btn btn-outline-dark justify-content-center gap-2 <?php if(route_is('admin.newsletter.sender-accounts')) : ?>active<?php endif;?>" href="<?php print route('admin.newsletter.sender-accounts'); ?>"><i class="mdi mdi-book-account-outline mr-1"></i> <?php _e('Sender Accounts'); ?></a>
                    <a class="btn btn-outline-dark justify-content-center gap-2 <?php if(route_is('admin.newsletter.settings')) : ?>active<?php endif;?>" href="<?php print route('admin.newsletter.settings'); ?>"><i class="mdi mdi-cog-outline mr-1"></i> <?php _e('Settings'); ?></a>
                </nav>

                <div class="py-3">
                    @yield('content-admin-newsletter')
                </div>

            </div>
        </div>
    </div>

@endsection
