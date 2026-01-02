<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use App\Infrastructure\Services\Logger;
use App\Infrastructure\Persistence\MySQL\Connection;
use App\Infrastructure\Container\SimpleContainer;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Repositories\ProductRepositoryInterface;
use App\Infrastructure\Persistence\MySQL\MySQLUserRepository;
use App\Infrastructure\Persistence\MySQL\MySQLProductRepository;
use App\Application\UseCases\RegisterUserService;
use App\Application\Services\AuthenticateUserService;
use App\Application\UseCases\CreateProductService;
use App\Presentation\Controllers\AuthController;
use App\Presentation\Controllers\Admin\ProductController;
use App\Infrastructure\Services\SessionManager;
use App\Infrastructure\Services\PaymentGatewayMock;
use App\Domain\Repositories\OrderRepositoryInterface;
use App\Domain\Repositories\LicenseRepositoryInterface;
use App\Infrastructure\Persistence\MySQL\MySQLOrderRepository;
use App\Infrastructure\Persistence\MySQL\MySQLLicenseRepository;
use App\Application\UseCases\GenerateLicenseService;
use App\Application\UseCases\PurchaseProductService;
use App\Presentation\Controllers\PurchaseController;
use App\Domain\Repositories\DownloadLogRepositoryInterface;
use App\Infrastructure\Persistence\MySQL\MySQLDownloadLogRepository;
use App\Application\Services\AdminReportService;
use App\Presentation\Controllers\Admin\ReportController;

$root = dirname(__DIR__);

if (file_exists($root . '/.env')) {
    $dotenv = Dotenv::createImmutable($root);
    $dotenv->load();
}

// Basit error handling / debug
if (getenv('APP_DEBUG') === 'true' || getenv('APP_DEBUG') === '1') {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
}

// Başlangıç: Logger ve DB bağlantısı
$logger = new Logger($root . '/storage/logs/app.log');
try {
    $pdo = Connection::makeFromEnv();
} catch (PDOException $e) {
    $logger->error('DB connection failed: ' . $e->getMessage());
    http_response_code(500);
    echo 'Database bağlantı hatası.';
    exit;
}

// Basit DI container kur
$container = new SimpleContainer();
$container->set(PDO::class, fn() => $pdo);
$container->set(UserRepositoryInterface::class, fn() => new MySQLUserRepository($pdo));
$container->set(ProductRepositoryInterface::class, fn() => new MySQLProductRepository($pdo));
$container->set(OrderRepositoryInterface::class, fn() => new MySQLOrderRepository($pdo));
$container->set(LicenseRepositoryInterface::class, fn() => new MySQLLicenseRepository($pdo));
$container->set(PaymentGatewayMock::class, fn() => new PaymentGatewayMock());
$container->set(DownloadLogRepositoryInterface::class, fn() => new MySQLDownloadLogRepository($pdo));

// Servisler
$container->set(RegisterUserService::class, fn() => new RegisterUserService($container->get(UserRepositoryInterface::class)));
$container->set(AuthenticateUserService::class, fn() => new AuthenticateUserService($container->get(UserRepositoryInterface::class)));
$container->set(CreateProductService::class, fn() => new CreateProductService($container->get(ProductRepositoryInterface::class)));

// Purchase & license services
$container->set(GenerateLicenseService::class, fn() => new GenerateLicenseService($container->get(LicenseRepositoryInterface::class)));
$container->set(PurchaseProductService::class, fn() => new PurchaseProductService($container->get(OrderRepositoryInterface::class), $container->get(ProductRepositoryInterface::class), $container->get(PaymentGatewayMock::class), $container->get(GenerateLicenseService::class)));

// Purchase controller
$container->set(PurchaseController::class, fn() => new PurchaseController($container->get(PurchaseProductService::class)));

// Admin reports
$container->set(AdminReportService::class, fn() => new AdminReportService($pdo, $container->get(OrderRepositoryInterface::class), $container->get(DownloadLogRepositoryInterface::class)));
$container->set(ReportController::class, fn() => new ReportController($container->get(AdminReportService::class)));

// Controllers
$container->set(AuthController::class, fn() => new AuthController($container->get(RegisterUserService::class), $container->get(AuthenticateUserService::class)));
$container->set(ProductController::class, fn() => new ProductController($container->get(CreateProductService::class)));

// Başlangıç: oturum
SessionManager::start(getenv('SESSION_NAME') ?: 'app_session', getenv('APP_ENV') === 'production');

// Basit router
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($path === '/' || $path === '/index.php') {
    require $root . '/resources/views/frontend/home.php';
    exit;
}

// Auth routes
if ($path === '/register' && $method === 'GET') {
    $container->get(AuthController::class)->showRegister();
    exit;
}
if ($path === '/register' && $method === 'POST') {
    $container->get(AuthController::class)->register();
    exit;
}
if ($path === '/login' && $method === 'GET') {
    $container->get(AuthController::class)->showLogin();
    exit;
}
if ($path === '/login' && $method === 'POST') {
    $container->get(AuthController::class)->login();
    exit;
}
if ($path === '/logout') {
    $container->get(AuthController::class)->logout();
    exit;
}

// Admin product routes
if ($path === '/admin/products/create' && $method === 'GET') {
    $container->get(ProductController::class)->createForm();
    exit;
}
if ($path === '/admin/products' && $method === 'POST') {
    $container->get(ProductController::class)->store();
    exit;
}

// 404
http_response_code(404);
echo 'Sayfa bulunamadı.';
