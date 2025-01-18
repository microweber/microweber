<?php

return [
    'name' => 'Comments',

    // Comment Functionality
    'enable_comments' => false,
    'allow_replies' => false,
    'allow_guest_comments' => false,
    'require_login' => false,

    // Moderation & Spam
    'enable_moderation' => false,
    'enable_spam_filter' => false,
    'block_spam_keywords' => false,
    'spam_keywords' => '',
    'max_links' => 2,

    // Display Settings
    'sort_order' => 'newest',
    'comments_per_page' => 10,
    'min_comment_length' => 2,
    'max_comment_length' => 1000,

    // Security
    'enable_captcha' => false,

    // Notifications
    'notify_admin' => false,
    'admin_email' => '',
    'notify_users' => false,

    // Avatar Settings
    'avatar_provider' => [
        // UI Avatar provider (https://ui-avatars.com/)
        'ui-avatar' => [

            // UI Avatar source url
            'url' => 'https://ui-avatars.com/api/',

            // User's field used to generate avatar
            'name_field' => 'comment_name',

            // Color used in url text color
            'text_color' => 'FFFFFF',

            // Background color used if the 'dynamic_bg_color' flag is false
            'bg_color' => '111827',

            // If 'true' the provider will generate a dynamic 'bg_color' based on user's name
            'dynamic_bg_color' => true,

            // HSL ranges
            // You can change them as you like to adapt the dynamic background color
            'hRange' => [0, 360],
            'sRange' => [50, 75],
            'lRange' => [25, 60],
        ],

        // Gravatar provider (https://gravatar.com)
        'gravatar' => [

            // Gravatar source url
            'url' => 'https://www.gravatar.com/avatar/',

            // User's field used to generate avatar
            'name_field' => 'comment_email'
        ],
    ],
];
