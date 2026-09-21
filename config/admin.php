<?php

$navigationLayout = env('ADMIN_NAV_LAYOUT', 'sidebar');
$loginLayout = env('ADMIN_LOGIN_LAYOUT', 'panel');

return [
    'navigation_layout' => in_array($navigationLayout, ['sidebar', 'topbar'], true)
        ? $navigationLayout
        : 'sidebar',
    'login_layout' => in_array($loginLayout, ['panel', 'split', 'spotlight'], true)
        ? $loginLayout
        : 'panel',
    'default_email' => env('ADMIN_DEFAULT_EMAIL', 'admin@example.com'),
    'default_password' => env('ADMIN_DEFAULT_PASSWORD', ''),
];
