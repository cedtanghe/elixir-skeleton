<?php

return [
    'development' => [
        'enable' => [
            'session' => true,
            'db' => false,
            'security' => false,
            'i18n' => false
        ],
        'i18n' => [
            'languages' => ['en'],
            'default' => 'en'
        ],
        'session' => ['name' => '***'],
        'db' => [
            'type' => 'pdo_mysql',
            'host' => '***',
            'username' => '***',
            'password' => '***',
            'dbname' => '***'
        ],
        'security' => [
            'type' => 'rbac'
        ]
    ],
    'preproduction:development' => [],
    'production:development' => []
];
