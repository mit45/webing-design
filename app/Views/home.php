<section class="hero">
    <h1>Script & Uygulama Satış Platformu</h1>
    <p>Bu, temel iskeletin frontend ana sayfasıdır.</p>
</section>

<?php if (!empty($products)): ?>
    <section class="products">
        <?php foreach ($products as $p): ?>
            <article class="product">
                <h3><?= htmlspecialchars($p['title']) ?></h3>
                <p><?= htmlspecialchars($p['short_description']) ?></p>
            </article>
        <?php endforeach; ?>
    </section>
<?php else: ?>
    <p>Henüz ürün eklenmedi.</p>
<?php endif; ?>
