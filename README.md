PHP Script & Uygulama Satış Paneli - Scaffold

Bu depo, PHP 8+ + MySQL 8+ kullanan, MVC tarzı basit ve genişletilebilir bir script satış platformu iskeleti sunar.

Kurulum (özet):

1. Dosyaları sunucuya yükleyin (public/ kökü web root olmalı).
2. `sql/schema.sql` dosyasını phpMyAdmin ile yeni bir veritabanına import edin.
3. `.env.example` dosyasını kopyalayıp `.env` olarak düzenleyin ve DB bilgilerinizi ekleyin.
4. `public` klasörünü web sunucunuzun document root'u olarak ayarlayın.
5. Varsayılan admin: `admin@local.test` / `Password123!` (DB importunda oluşturulur).

Bu scaffold; config, DB bağlantısı, basit auth, admin panel iskeleti, site ayarları ve örnek tablolar içerir.

Sonraki adımlar: ürün CRUD, lisans üretimi, ödeme entegrasyonu ve detaylı admin ayarları eklenmelidir.
