DROP DATABASE IF EXISTS fashion_boutique;
CREATE DATABASE fashion_boutique CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE fashion_boutique;

SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS activity_logs;
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','customer') NOT NULL DEFAULT 'customer',
    phone VARCHAR(30) NULL,
    address VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
) ENGINE=InnoDB;

CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL UNIQUE,
    description VARCHAR(255) NULL,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
) ENGINE=InnoDB;

CREATE TABLE products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(160) NOT NULL,
    sku VARCHAR(40) NOT NULL UNIQUE,
    description TEXT NULL,
    price DECIMAL(10,2) NOT NULL,
    stock INT UNSIGNED NOT NULL DEFAULT 0,
    size VARCHAR(30) NULL,
    color VARCHAR(40) NULL,
    image_url VARCHAR(500) NULL,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE orders (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    order_number VARCHAR(40) NOT NULL UNIQUE,
    total DECIMAL(10,2) NOT NULL DEFAULT 0,
    status ENUM('pending','processing','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
    shipping_address VARCHAR(255) NOT NULL,
    payment_method ENUM('cash_on_delivery','gcash','card') NOT NULL DEFAULT 'cash_on_delivery',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE order_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    quantity INT UNSIGNED NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT fk_order_items_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_order_items_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE activity_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    action VARCHAR(40) NOT NULL,
    table_name VARCHAR(80) NOT NULL,
    record_id BIGINT UNSIGNED NULL,
    details VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    CONSTRAINT fk_activity_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

INSERT INTO users (name,email,password,role,phone,address,created_at,updated_at) VALUES
('Admin User','admin@inchangsboutique.test','$2y$10$/7XtU3Epvgh7er6od4mRaOqay5ewv7QDpPdOFJTO6y5Tiqfl9PkrK','admin','09170000001','Davao Oriental',NOW(),NOW()),
('Mia Santos','mia@inchangsboutique.test','$2y$10$tPS5/t2jxr96qefeFg6Rn.cz9hHs/eGHusH.W3JhfrMrFKJhwRyZe','customer','09170000002','Mati City',NOW(),NOW());

INSERT INTO categories (name,description,status,created_at,updated_at) VALUES
('Dresses','Casual and semi-formal dresses','active',NOW(),NOW()),
('Tops','Blouses, tees, and statement tops','active',NOW(),NOW()),
('Sets','Coordinated fashion sets','active',NOW(),NOW()),
('Accessories','Bags, belts, and accents','active',NOW(),NOW()),
('Sale','Discounted seasonal pieces','active',NOW(),NOW());

INSERT INTO products (category_id,name,sku,description,price,stock,size,color,image_url,status,created_at,updated_at) VALUES
(1,'Satin Midi Dress','DRS-001','Soft satin midi dress with relaxed silhouette.',1890.00,12,'S/M/L','Champagne','/images/products/DRS-001.svg','active',NOW(),NOW()),
(2,'Linen Wrap Top','TOP-014','Breathable wrap top for polished daytime looks.',890.00,20,'Free Size','Ivory','/images/products/TOP-014.svg','active',NOW(),NOW()),
(3,'Weekend Knit Set','SET-008','Two-piece knit set for travel and errands.',2150.00,9,'S/M/L','Olive','/images/products/SET-008.svg','active',NOW(),NOW()),
(4,'Pearl Mini Bag','ACC-003','Structured mini bag with pearl handle detail.',1250.00,15,'One Size','Cream','/images/products/ACC-003.svg','active',NOW(),NOW()),
(5,'Pleated Skirt','SAL-021','Flowy pleated skirt from last season collection.',690.00,8,'M','Dusty Rose','/images/products/SAL-021.svg','active',NOW(),NOW()),
(1,'Floral Day Dress','DRS-002','Light floral dress for weekend brunch and warm afternoons.',1490.00,18,'S/M/L','Sage Floral','/images/products/DRS-002.svg','active',NOW(),NOW()),
(1,'Black Slip Dress','DRS-003','Minimal slip dress with adjustable straps.',1650.00,10,'S/M/L','Black','/images/products/DRS-003.svg','active',NOW(),NOW()),
(1,'Ruffle Hem Dress','DRS-004','Soft ruffle hem dress for casual celebrations.',1720.00,11,'S/M/L','Blush','/images/products/DRS-004.svg','active',NOW(),NOW()),
(2,'Silk Button Blouse','TOP-015','Polished silk-feel blouse for office and dinner looks.',990.00,22,'S/M/L','Pearl White','/images/products/TOP-015.svg','active',NOW(),NOW()),
(2,'Cropped Knit Tank','TOP-016','Stretch knit tank with clean neckline.',650.00,25,'Free Size','Mocha','/images/products/TOP-016.svg','active',NOW(),NOW()),
(2,'Tailored Vest Top','TOP-017','Sleeveless vest top with structured fit.',1190.00,14,'S/M/L','Stone','/images/products/TOP-017.svg','active',NOW(),NOW()),
(3,'Linen Co-ord Set','SET-009','Relaxed linen top and shorts set.',2290.00,13,'S/M/L','Sand','/images/products/SET-009.svg','active',NOW(),NOW()),
(3,'Blazer Trouser Set','SET-010','Matching blazer and trouser set for modern workwear.',3190.00,7,'S/M/L','Charcoal','/images/products/SET-010.svg','active',NOW(),NOW()),
(3,'Resort Lounge Set','SET-011','Easy lounge set with breezy resort styling.',1980.00,16,'Free Size','Seafoam','/images/products/SET-011.svg','active',NOW(),NOW()),
(4,'Gold Hoop Earrings','ACC-004','Lightweight everyday hoop earrings.',390.00,30,'One Size','Gold','/images/products/ACC-004.svg','active',NOW(),NOW()),
(4,'Woven Shoulder Bag','ACC-005','Textured woven shoulder bag with roomy interior.',1450.00,12,'One Size','Tan','/images/products/ACC-005.svg','active',NOW(),NOW()),
(4,'Silk Hair Scarf','ACC-006','Printed scarf for hair, bag, or neck styling.',350.00,28,'One Size','Emerald Print','/images/products/ACC-006.svg','active',NOW(),NOW()),
(5,'Denim Mini Skirt','SAL-022','Sale denim skirt with classic five-pocket style.',590.00,9,'S/M','Washed Blue','/images/products/SAL-022.svg','active',NOW(),NOW()),
(5,'Ribbed Cardigan','SAL-023','Soft ribbed cardigan from the seasonal sale rack.',790.00,10,'M/L','Oat','/images/products/SAL-023.svg','active',NOW(),NOW()),
(5,'Wide Leg Trousers','SAL-024','Comfortable wide leg trousers at a special price.',890.00,8,'S/M/L','Navy','/images/products/SAL-024.svg','active',NOW(),NOW());
