<?php

return [
    'name' => 'Profile',
    'description' => 'User profile management module with authentication, registration, and profile editing capabilities.',
    'version' => '1.0.0',
    'author' => 'Microweber',
    'email' => 'info@microweber.com',
    'website' => 'http://microweber.com',
    'options' => [
        'enable_registration' => [
            'type' => 'toggle',
            'label' => 'Enable Registration',
            'description' => 'Allow new users to register',
            'default' => true,
        ],
        'enable_password_reset' => [
            'type' => 'toggle',
            'label' => 'Enable Password Reset',
            'description' => 'Allow users to reset their password',
            'default' => true,
        ],
        'enable_two_factor' => [
            'type' => 'toggle',
            'label' => 'Enable Two Factor Authentication',
            'description' => 'Allow users to enable two-factor authentication',
            'default' => true,
        ],
        'require_email_verification' => [
            'type' => 'toggle',
            'label' => 'Require Email Verification',
            'description' => 'Require users to verify their email address',
            'default' => true,
        ],
        'login_redirect' => [
            'type' => 'text',
            'label' => 'Login Redirect',
            'description' => 'Where to redirect users after login',
            'default' => '/profile/dashboard',
        ],
        'logout_redirect' => [
            'type' => 'text',
            'label' => 'Logout Redirect',
            'description' => 'Where to redirect users after logout',
            'default' => '/profile/login',
        ],
    ],


];
