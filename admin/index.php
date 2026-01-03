<?php
require __DIR__ . '/../app/init.php';

if (empty($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
    header('Location: /?r=admin_login');
    exit;
}

?><!doctype html>
<html><head><meta charset="utf-8"><title>Admin Dashboard</title></head><body>
<h1>Admin Dashboard</h1>
<p>Yönetim paneli iskeleti. Menü ve modüller eklenecek.</p>
<ul>
    <li><a href="/admin?m=products">Ürünler</a></li>
    <li><a href="/admin?m=categories">Kategoriler</a></li>
    <li><a href="/admin?m=orders">Siparişler</a></li>
</ul>
</body></html>
