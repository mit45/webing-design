<?php
namespace App\Controllers;

class BaseController
{
    protected function view($template, $data = [])
    {
        extract($data, EXTR_SKIP);
        $viewFile = __DIR__ . '/../Views/' . $template . '.php';
        if (!file_exists($viewFile)) {
            echo "View not found: $viewFile";
            return;
        }
        require __DIR__ . '/../Views/layout.php';
    }
}
