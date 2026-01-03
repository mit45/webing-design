<?php
require __DIR__ . '/../app/init.php';

$route = $_GET['r'] ?? '';

if ($route === 'admin') {
    (new App\Controllers\AdminController())->dashboard();
    exit;
}

(new App\Controllers\HomeController())->index();
