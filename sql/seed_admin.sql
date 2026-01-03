-- Seed admin user with hashed password (Password123!)
INSERT INTO `users` (`role_id`,`name`,`email`,`password`,`created_at`) VALUES
(1,'Site Admin','admin@local.test','$2y$10$r4Ut4GZ4RJ1ZEOGYinlGBuddeKF6TlWvJvvBZdvErBXzXwFGXOvnS',NOW())
ON DUPLICATE KEY UPDATE name=VALUES(name), password=VALUES(password);
