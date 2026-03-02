-- =========================
-- Second-Hand Market (Minimal DB)
-- MySQL InnoDB + UTF8MB4
-- =========================

CREATE DATABASE IF NOT EXISTS secondhand_market
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE secondhand_market;

-- -------------------------
-- 1) users
-- -------------------------
CREATE TABLE users (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  display_name VARCHAR(100) NOT NULL,
  role ENUM('admin','user') NOT NULL DEFAULT 'user',
  status ENUM('active','banned') NOT NULL DEFAULT 'active',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- -------------------------
-- 2) categories
-- -------------------------
CREATE TABLE categories (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(80) NOT NULL UNIQUE,
  slug VARCHAR(100) NOT NULL UNIQUE,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- -------------------------
-- 3) listings
-- -------------------------
CREATE TABLE listings (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL,
  category_id BIGINT UNSIGNED NOT NULL,

  title VARCHAR(150) NOT NULL,
  description TEXT NOT NULL,
  price DECIMAL(10,2) NOT NULL,

  -- จะลบ condition_level ก็ได้ ถ้าไม่ใช้
  condition_level ENUM('new','like_new','good','fair','poor') NOT NULL DEFAULT 'good',

  location_text VARCHAR(120) NULL,

  sell_status ENUM('available','reserved','sold') NOT NULL DEFAULT 'available',
  visibility ENUM('public','hidden','deleted') NOT NULL DEFAULT 'public',
  hidden_reason VARCHAR(255) NULL,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  CONSTRAINT fk_listings_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE RESTRICT ON UPDATE CASCADE,

  CONSTRAINT fk_listings_category
    FOREIGN KEY (category_id) REFERENCES categories(id)
    ON DELETE RESTRICT ON UPDATE CASCADE,

  INDEX idx_listings_filter (category_id, sell_status, visibility, price),
  INDEX idx_listings_user_time (user_id, created_at)
) ENGINE=InnoDB;

-- ถ้าต้องการค้นหาด้วยคำใน title/description (MySQL 5.6+ / 8+)
-- ALTER TABLE listings ADD FULLTEXT INDEX ft_listings_text (title, description);

-- -------------------------
-- 4) listing_images (หลายรูปต่อ 1 ประกาศ)
-- -------------------------
CREATE TABLE listing_images (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  listing_id BIGINT UNSIGNED NOT NULL,

  file_name VARCHAR(255) NOT NULL,
  file_path VARCHAR(255) NOT NULL,
  mime_type VARCHAR(50) NOT NULL,
  size_bytes INT UNSIGNED NOT NULL,

  width INT UNSIGNED NULL,
  height INT UNSIGNED NULL,

  is_primary TINYINT(1) NOT NULL DEFAULT 0,
  sort_order INT NOT NULL DEFAULT 0,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

  CONSTRAINT fk_images_listing
    FOREIGN KEY (listing_id) REFERENCES listings(id)
    ON DELETE CASCADE ON UPDATE CASCADE,

  INDEX idx_images_listing (listing_id, is_primary),
  INDEX idx_images_sort (listing_id, sort_order)
) ENGINE=InnoDB;

-- -------------------------
-- Seed หมวดหมู่ตัวอย่าง (แก้ได้)
-- -------------------------
INSERT INTO categories (name, slug) VALUES
('อิเล็กทรอนิกส์', 'electronics'),
('เสื้อผ้า', 'fashion'),
('เฟอร์นิเจอร์', 'furniture'),
('หนังสือ', 'books'),
('อื่นๆ', 'others');

-- -------------------------
-- password: admin1234
-- password: user1234
-- -------------------------
-- INSERT INTO users (email, password_hash, display_name, role)
-- VALUES ('admin@local.dev', '$2y$10$fszzX1YRhWysxJwKQsfzROjVdkED.DqT4LSSl/HB9EVug4FiYfpLu', 'Admin', 'admin');
-- INSERT INTO users (email, password_hash, display_name, role)
-- VALUES ('user1@local.dev', '$2y$10$mq1XMRCdyoXO1aeY9oiQ0emjPnNl2BzvFR.NpmyqFCjiU/yQ1YmFa', 'User1', 'user');