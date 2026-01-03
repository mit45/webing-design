<?php
// Simple config loader: reads .env if present, otherwise uses defaults

function load_env($path)
{
    $data = [];
    if (!file_exists($path)) return $data;
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        [$k, $v] = array_map('trim', explode('=', $line, 2) + [1 => null]);
        if ($k) $data[$k] = $v;
    }
    return $data;
}

$env = load_env(__DIR__ . '/../.env');

$config = [
    'app_env' => $env['APP_ENV'] ?? 'production',
    'app_debug' => filter_var($env['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),
    'app_url' => $env['APP_URL'] ?? '',
    'db' => [
        'host' => $env['DB_HOST'] ?? '127.0.0.1',
        'port' => $env['DB_PORT'] ?? 3306,
        'name' => $env['DB_NAME'] ?? 'webing_store',
        'user' => $env['DB_USER'] ?? 'root',
        'pass' => $env['DB_PASS'] ?? '',
    ],
    'mail' => [
        'host' => $env['MAIL_HOST'] ?? '',
        'user' => $env['MAIL_USER'] ?? '',
        'pass' => $env['MAIL_PASS'] ?? '',
    ],
    'default_admin' => [
        'email' => $env['DEFAULT_ADMIN_EMAIL'] ?? 'admin@local.test',
        'pass' => $env['DEFAULT_ADMIN_PASSWORD'] ?? 'Password123!'
    ]
];

// Expose $config
return $config;
