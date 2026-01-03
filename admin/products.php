<?php
require __DIR__ . '/../app/init.php';

if (empty($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
    header('Location: /?r=admin_login');
    exit;
}

$productModel = new App\Models\Product($DB);
$products = $productModel->all();

?><!doctype html>
<html><head><meta charset="utf-8"><title>Ürünler - Admin</title></head><body>
<h1>Ürünler</h1>
<p><a href="/admin?m=product_create">Yeni Ürün Ekle</a></p>
<?php if (empty($products)): ?>
    <p>Henüz ürün yok.</p>
<?php else: ?>
    <table border="1" cellpadding="6" cellspacing="0">
        <thead><tr><th>ID</th><th>Başlık</th><th>Fiyat</th><th>Durum</th><th>İşlemler</th></tr></thead>
        <tbody>
        <?php foreach ($products as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['id']) ?></td>
                <td><?= htmlspecialchars($p['title']) ?></td>
                <td><?= htmlspecialchars($p['price']) ?> <?= htmlspecialchars($p['currency']) ?></td>
                <td><?= htmlspecialchars($p['status']) ?></td>
                <td><a href="#">Düzenle</a> | <a href="#">Sil</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
</body></html>
