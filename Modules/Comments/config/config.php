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
];
