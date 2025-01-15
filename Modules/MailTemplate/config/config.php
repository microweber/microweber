<?php

return [
    'name' => 'MailTemplate',
    'description' => 'Manage email templates for various system notifications and communications',
    
    /*
    |--------------------------------------------------------------------------
    | Default Email Settings
    |--------------------------------------------------------------------------
    |
    | Default values for new email templates
    |
    */
    'defaults' => [
        'from_name' => env('MAIL_FROM_NAME', config('app.name')),
        'from_email' => env('MAIL_FROM_ADDRESS', 'noreply@example.com'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Template Types
    |--------------------------------------------------------------------------
    |
    | Available template types and their descriptions
    |
    */
    'template_types' => [
        'new_order' => 'New Order Notification',
        'new_comment' => 'New Comment Notification',
        'forgot_password' => 'Password Reset',
        'new_registration' => 'New User Registration',
        'contact_form' => 'Contact Form Submission',
        'newsletter_subscription' => 'Newsletter Subscription'
    ],

    /*
    |--------------------------------------------------------------------------
    | Available Variables
    |--------------------------------------------------------------------------
    |
    | Variables that can be used in email templates
    |
    */
    'variables' => [
        'new_order' => [
            '{order_id}' => 'Order ID',
            '{order_amount}' => 'Order Amount',
            '{customer_name}' => 'Customer Name',
            '{order_status}' => 'Order Status',
            '{order_items}' => 'Order Items',
        ],
        'new_comment' => [
            '{comment_author}' => 'Comment Author',
            '{comment_content}' => 'Comment Content',
            '{post_title}' => 'Post Title',
        ],
        'forgot_password' => [
            '{reset_link}' => 'Password Reset Link',
            '{user_name}' => 'User Name',
        ],
        'new_registration' => [
            '{user_name}' => 'User Name',
            '{user_email}' => 'User Email',
            '{verification_link}' => 'Email Verification Link',
        ],
        'contact_form' => [
            '{name}' => 'Sender Name',
            '{email}' => 'Sender Email',
            '{subject}' => 'Message Subject',
            '{message}' => 'Message Content',
        ],
        'newsletter_subscription' => [
            '{subscriber_email}' => 'Subscriber Email',
            '{confirmation_link}' => 'Confirmation Link',
        ],
    ],
];
