<?php
return [
    // paden zonder login
    'public_paths' => ['/', '/login', '/logout'],

    // per prefix de toegestane rollen
    'firewall' => [
        '/admin' => ['admin'],
        '/party' => ['party'],
        '/user'  => ['user', 'admin'],
    ],
];
