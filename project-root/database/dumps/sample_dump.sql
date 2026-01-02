-- Sample SQL dump for quick import via phpMyAdmin
-- Contains schema and seed data (roles, admin user, sample categories/products)

-- Use the migration file in database/migrations for schema; this is a combined dump for convenience.

-- Roles
INSERT INTO roles (id, name, created_at) VALUES (1, 'admin', NOW()), (2, 'customer', NOW());

-- Admin user (password: ChangeMe123!)
INSERT INTO users (name, email, password, role_id, status, created_at) VALUES ('Admin', 'admin@example.com', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8q9hQqY1/1Kq5t1rV8Z6k1qf8aXz0u', 1, 'active', NOW());

-- Categories
INSERT INTO categories (name, slug, created_at) VALUES ('UI Kits', 'ui-kits', NOW()), ('Templates', 'templates', NOW());

-- Sample products
INSERT INTO products (title, slug, description, price, currency, category_id, status, created_at) VALUES
(' Starter UI Kit', 'starter-ui-kit', 'Basit başlangıç UI kiti', 29.99, 'USD', 1, 'published', NOW()),
('Landing Page Template', 'landing-page-template', 'Kurumsal landing page template', 49.00, 'USD', 2, 'published', NOW());

-- Note: For full schema create tables using database/migrations/001_create_tables.sql
