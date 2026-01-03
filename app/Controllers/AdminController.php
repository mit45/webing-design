<?php
namespace App\Controllers;

class AdminController extends BaseController
{
    protected function checkAdmin()
    {
        if (empty($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
            header('Location: /?r=admin_login');
            exit;
        }
    }

    public function dashboard()
    {
        $this->checkAdmin();
        $this->view('admin/dashboard');
    }
}
