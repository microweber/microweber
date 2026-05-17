<?php

/*
 * task-2026-05-17-551f7e / AI-774 — admin notifications drawer copy.
 * Jira: https://microweber.atlassian.net/browse/AI-774
 *
 * Override of vendor/filament/notifications/resources/lang/en/database.php.
 *
 * Designer's Round-9 audit flagged the vendor "Please check again later."
 * as passive — it offloads the responsibility for next steps to the user
 * without telling them what triggers a notification or what action they
 * can take. Replaces with action-aware copy that matches the AI-705
 * dashboard "All caught up." pattern shipped 2026-05-16 for the
 * welcome-counter empty state. Other keys passed through verbatim from
 * the vendor file.
 */
return [

    'modal' => [

        'heading' => 'Notifications',

        'actions' => [

            'clear' => [
                'label' => 'Clear',
            ],

            'mark_all_as_read' => [
                'label' => 'Mark all as read',
            ],

        ],

        'empty' => [
            'heading' => 'All caught up',
            'description' => "You'll see new comments, orders, and messages here when they arrive.",
        ],

    ],

];
