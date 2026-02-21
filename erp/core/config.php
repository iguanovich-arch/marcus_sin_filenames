<?php
return [
    'app_name' => 'ERP Multiempresa España',
    'db' => [
        'host' => 'localhost',
        'name' => 'erp_multiempresa',
        'user' => 'root',
        'pass' => '',
        'charset' => 'utf8mb4',
    ],
    'caixabank' => [
        'enabled' => true,
        'merchant_code' => 'DEMO_CAIXABANK',
        'currency' => 'EUR',
        'country' => 'ES',
    ],
    'verifactu' => [
        'enabled_by_default' => false,
    ],
];
