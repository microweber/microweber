<?php

return [
    'name' => 'Register',
    'enable_user_registration' => env('ENABLE_USER_REGISTRATION', false),
    'registration_approval_required' => env('REGISTRATION_APPROVAL_REQUIRED', false),
];
