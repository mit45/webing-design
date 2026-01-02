# Webing Design - Marketplace (PHP + MySQL)

Bu proje, PHP 8+ ve MySQL 8+ için kurumsal mimaride örnek bir dijital ürün satış platformu iskeletidir.

Özellikler
- Katmanlı mimari (Presentation / Application / Domain / Infrastructure)
- PDO + prepared statements
- PSR-4 autoloading
- .env yapılandırması (vlucas/phpdotenv)

Kurulum
1. PHP 8+ ve MySQL 8+ kurun.
2. Depoyu klonlayın veya dosyaları workspace içine koyun.
3. `.env.example` dosyasını kopyalayın ve proje kökünde `.env` olarak düzenleyin.
4. Composer bağımlılıklarını yükleyin:

```bash
composer install
```

5. Veritabanını oluşturun ve `database/migrations/001_create_tables.sql` dosyasındaki SQL'i import edin (phpMyAdmin veya CLI ile).
6. Geliştirme sunucusunu çalıştırın:

```bash
php -S 127.0.0.1:8080 -t public
```

Testler
```bash
composer test
```

Varsayılan admin
- e-posta: admin@example.com
- parola: ChangeMe123!

Daha fazla bilgi için `docs/` veya proje içindeki comments/ PHPDoc'ları inceleyin.
