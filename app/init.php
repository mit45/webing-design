<?php
// App bootstrap
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/Database.php';

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// Start secure session
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    session_start();
    if (empty($_SESSION['initiated'])) {
        session_regenerate_id(true);
        $_SESSION['initiated'] = true;
    }
}

// DB instance
try {
    $DB = new App\Database($config);
} catch (Exception $e) {
    die('DB connection error: ' . $e->getMessage());
}

// CSRF helpers
function csrf_token() {
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['_csrf'];
}

function verify_csrf($token) {
    return isset($_SESSION['_csrf']) && hash_equals($_SESSION['_csrf'], $token);
}
