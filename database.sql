-- TechStock Database Schema and Sample Data
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS bundle_items;
DROP TABLE IF EXISTS bundles;
DROP TABLE IF EXISTS product_reservations;
DROP TABLE IF EXISTS rma_requests;
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS inventory;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS suppliers;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category_id INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    image_url VARCHAR(255),
    icon VARCHAR(50),
    specifications JSON,
    is_new BOOLEAN DEFAULT FALSE,
    is_promotion BOOLEAN DEFAULT FALSE,
    sale_price DECIMAL(10, 2),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE suppliers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    contact_info TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    serial_number VARCHAR(100) NOT NULL UNIQUE,
    supplier_id INT,
    status ENUM('available', 'sold', 'rma', 'returned') DEFAULT 'available',
    received_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id)
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'paid', 'processing', 'shipped', 'completed', 'cancelled') DEFAULT 'pending',
    assembly_service BOOLEAN DEFAULT FALSE,
    tax_info JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    inventory_id INT,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (inventory_id) REFERENCES inventory(id)
);

CREATE TABLE rma_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    inventory_id INT NOT NULL,
    reason TEXT NOT NULL,
    status ENUM('received', 'checking', 'vendor_claim', 'returning', 'done') DEFAULT 'received',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (inventory_id) REFERENCES inventory(id)
);

CREATE TABLE product_reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    session_id VARCHAR(255) NOT NULL,
    user_id INT,
    quantity INT DEFAULT 1,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE bundles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    total_price DECIMAL(10, 2) NOT NULL,
    discount_price DECIMAL(10, 2),
    image_url VARCHAR(255),
    icon VARCHAR(50) DEFAULT 'fa-box-open',
    is_hot BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE bundle_items (
    bundle_id INT NOT NULL,
    product_id INT NOT NULL,
    FOREIGN KEY (bundle_id) REFERENCES bundles(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

INSERT INTO users (username, password, role) VALUES ('admin', '$2y$10$f/9S5i6fOqO/Qn5mY/K5He1oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
INSERT INTO users (username, password, role) VALUES ('user', '$2y$10$f/9S5i6fOqO/Qn5mY/K5He1oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');

INSERT INTO categories (id, name) VALUES (1, 'cpu');
INSERT INTO categories (id, name) VALUES (2, 'mainboard');
INSERT INTO categories (id, name) VALUES (3, 'ram');
INSERT INTO categories (id, name) VALUES (4, 'gpu');
INSERT INTO categories (id, name) VALUES (5, 'psu');
INSERT INTO categories (id, name) VALUES (6, 'case');
INSERT INTO categories (id, name) VALUES (7, 'monitor');
INSERT INTO categories (id, name) VALUES (8, 'ssd');
INSERT INTO categories (id, name) VALUES (9, 'cooler');
INSERT INTO categories (id, name) VALUES (10, 'keyboard');
INSERT INTO categories (id, name) VALUES (11, 'mouse');

-- Products (11 categories * 5 = 55)
INSERT INTO products (id, category_id, name, price, stock, icon, specifications, is_new, is_promotion, sale_price) VALUES (1, 1, 'Intel i5-13600K', 11900, 5, 'fa-microchip', '{"socket": "LGA1700"}', 0, 1, 10900);
INSERT INTO products (id, category_id, name, price, stock, icon, specifications, is_new, is_promotion, sale_price) VALUES (2, 1, 'Intel i9-14900K', 24900, 5, 'fa-microchip', '{"socket": "LGA1700"}', 1, 0, NULL);
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (3, 1, 'AMD R7 7800X3D', 14900, 5, 'fa-microchip', '{"socket": "AM5"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (4, 1, 'AMD R5 7600', 8900, 5, 'fa-microchip', '{"socket": "AM5"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (5, 1, 'Intel i7-14700K', 15900, 5, 'fa-microchip', '{"socket": "LGA1700"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (6, 2, 'MSI Z790 TOMAHAWK', 9500, 5, 'fa-microchip', '{"socket": "LGA1700"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (7, 2, 'ASUS B760-A GAMING', 7900, 5, 'fa-microchip', '{"socket": "LGA1700"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (8, 2, 'GIGABYTE B650 AORUS', 7200, 5, 'fa-microchip', '{"socket": "AM5"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (9, 2, 'ASUS PRIME A620M-K', 3500, 5, 'fa-microchip', '{"socket": "AM5"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (10, 2, 'MSI B760M BOMBER', 3900, 5, 'fa-microchip', '{"socket": "LGA1700"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (11, 3, 'Corsair D5 32GB', 5200, 5, 'fa-memory', '{"type": "DDR5"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (12, 3, 'Kingston D4 16GB', 1900, 5, 'fa-memory', '{"type": "DDR4"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (13, 3, 'G.Skill D5 32GB', 5900, 5, 'fa-memory', '{"type": "DDR5"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (14, 3, 'Team D4 16GB', 2200, 5, 'fa-memory', '{"type": "DDR4"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (15, 3, 'Corsair D5 64GB', 12000, 5, 'fa-memory', '{"type": "DDR5"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications, is_new, is_promotion, sale_price) VALUES (16, 4, 'RTX 4070 FE', 23000, 5, 'fa-bolt', '{}', 0, 1, 21900);
INSERT INTO products (id, category_id, name, price, stock, icon, specifications, is_new, is_promotion, sale_price) VALUES (17, 4, 'RTX 4090 OC', 75000, 5, 'fa-bolt', '{}', 1, 0, NULL);
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (18, 4, 'RX 7800 XT', 19500, 5, 'fa-bolt', '{}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (19, 4, 'RTX 4060 Ti', 14500, 5, 'fa-bolt', '{}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (20, 4, 'RX 7600', 9900, 5, 'fa-bolt', '{}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (21, 5, 'RM850e 850W', 4200, 5, 'fa-plug', '{}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (22, 5, 'BM3 750W', 2900, 5, 'fa-plug', '{}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (23, 5, 'MWE Gold 1050W', 5900, 5, 'fa-plug', '{}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (24, 5, 'Focus 650W', 3500, 5, 'fa-plug', '{}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (25, 5, 'SilverStone 500W', 1290, 5, 'fa-plug', '{}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (26, 6, 'NZXT H5 Flow', 3200, 5, 'fa-box', '{}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (27, 6, 'O11 Dynamic EVO', 5900, 5, 'fa-box', '{}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (28, 6, 'MasterBox Q300L', 1390, 5, 'fa-box', '{}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (29, 6, '4000D Airflow', 3500, 5, 'fa-box', '{}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (30, 6, 'Fractal North', 5500, 5, 'fa-box', '{}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (31, 7, 'LG 27GP850', 12900, 5, 'fa-desktop', '{}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (32, 7, 'Odyssey G5', 9900, 5, 'fa-desktop', '{}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (33, 7, 'ASUS VG249', 5500, 5, 'fa-desktop', '{}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (34, 7, 'Dell U2723QE', 21900, 5, 'fa-desktop', '{}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (35, 7, 'AOC 24G2SP', 4900, 5, 'fa-desktop', '{}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (36, 8, '990 PRO 2TB', 6900, 5, 'fa-hard-drive', '{}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (37, 8, 'SN850X 1TB', 3900, 5, 'fa-hard-drive', '{}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (38, 8, 'Crucial P3 1TB', 2500, 5, 'fa-hard-drive', '{}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (39, 8, 'Kingston NV2', 1290, 5, 'fa-hard-drive', '{}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (40, 8, 'HIKSEMI 2TB', 4500, 5, 'fa-hard-drive', '{}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (41, 9, 'AK400 Digital', 1290, 5, 'fa-fan', '{}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (42, 9, 'NH-D15 chromax', 4100, 5, 'fa-fan', '{}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (43, 9, 'Kraken 360', 11900, 5, 'fa-fan', '{}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (44, 9, 'H150i iCUE', 7900, 5, 'fa-fan', '{}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (45, 9, 'Hyper 212', 1190, 5, 'fa-fan', '{}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (46, 10, 'G915 TKL', 6900, 5, 'fa-keyboard', '{}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (47, 10, 'BlackWidow V4', 7900, 5, 'fa-keyboard', '{}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (48, 10, 'K70 RGB TKL', 4500, 5, 'fa-keyboard', '{}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (49, 10, 'Keychron V1', 3200, 5, 'fa-keyboard', '{}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (50, 10, 'Apex Pro TKL', 7500, 5, 'fa-keyboard', '{}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (51, 11, 'G Pro X Superlight', 5600, 5, 'fa-mouse', '{}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (52, 11, 'DeathAdder V3', 4900, 5, 'fa-mouse', '{}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (53, 11, 'Aerox 3 Wireless', 2900, 5, 'fa-mouse', '{}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications, is_new, is_promotion, sale_price) VALUES (54, 11, 'Zowie EC2-C', 2400, 5, 'fa-mouse', '{}', 0, 1, 2100);
INSERT INTO products (id, category_id, name, price, stock, icon, specifications, is_new, is_promotion, sale_price) VALUES (55, 11, 'Glorious Model O 2', 2200, 5, 'fa-mouse', '{}', 1, 0, NULL);

INSERT INTO suppliers (id, name) VALUES (1, 'Synnex');

INSERT INTO inventory (product_id, serial_number, supplier_id, status) SELECT id, CONCAT('SN-', category_id, '-', id, '-001'), 1, 'available' FROM products;

-- Bundles (Curated PC Sets)
INSERT INTO bundles (id, name, description, total_price, discount_price, is_hot, icon) VALUES (1, 'Office Performance Set', 'เหมาะสำหรับการทำงานทั่วไป เอกสาร และการเรียนรู้', 20000, 18900, 0, 'fa-briefcase');
INSERT INTO bundles (id, name, description, total_price, discount_price, is_hot, icon) VALUES (2, 'Pro Gaming 2024', 'เซตเกมมิ่งระดับโปร รองรับการเล่นเกมระดับ 2K สบายๆ', 65000, 59900, 1, 'fa-gamepad');
INSERT INTO bundles (id, name, description, total_price, discount_price, is_hot, icon) VALUES (3, 'Elite Creator Workstation', 'ที่สุดของการประมวลผล สำหรับงานตัดต่อ 4K และ 3D เรนเดอร์', 150000, 139000, 1, 'fa-gem');

-- Bundle 1 Items
INSERT INTO bundle_items (bundle_id, product_id) VALUES (1, 4), (1, 9), (1, 12), (1, 25), (1, 28), (1, 39);
-- Bundle 2 Items (Gaming)
INSERT INTO bundle_items (bundle_id, product_id) VALUES (2, 1), (2, 7), (2, 11), (2, 16), (2, 21), (2, 26), (2, 37);
-- Bundle 3 Items (Elite)
INSERT INTO bundle_items (bundle_id, product_id) VALUES (3, 2), (3, 6), (3, 15), (3, 17), (3, 23), (3, 27), (3, 36);

SET FOREIGN_KEY_CHECKS = 1;
