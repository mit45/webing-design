<?php
namespace App\Controllers;

class HomeController extends BaseController
{
    public function index()
    {
        $products = [];
        $this->view('home', ['products' => $products]);
    }
}
