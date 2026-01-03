<?php
namespace App\Controllers;

use App\Models\Product;

class ProductController extends BaseController
{
    public function index()
    {
        $productModel = new Product($GLOBALS['DB']);
        $products = $productModel->all();
        $this->view('products/list', ['products' => $products]);
    }

    public function adminIndex()
    {
        $this->checkAdmin();
        $productModel = new Product($GLOBALS['DB']);
        $products = $productModel->all();
        require __DIR__ . '/../../admin/products.php';
    }
}
