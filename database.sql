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
INSERT INTO products (id, category_id, name, price, stock, icon, specifications, is_new, is_promotion, sale_price) VALUES (1, 1, 'Intel i5-13600K', 11900, 5, 'fa-microchip', '{"socket": "LGA1700", "tdp": 125, "cores": 14}', 0, 1, 10900);
INSERT INTO products (id, category_id, name, price, stock, icon, specifications, is_new, is_promotion, sale_price) VALUES (2, 1, 'Intel i9-14900K', 24900, 5, 'fa-microchip', '{"socket": "LGA1700", "tdp": 150, "cores": 24}', 1, 0, NULL);
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (3, 1, 'AMD R7 7800X3D', 14900, 5, 'fa-microchip', '{"socket": "AM5", "tdp": 120, "cores": 8}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (4, 1, 'AMD R5 7600', 8900, 5, 'fa-microchip', '{"socket": "AM5", "tdp": 65, "cores": 6}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (5, 1, 'Intel i7-14700K', 15900, 5, 'fa-microchip', '{"socket": "LGA1700", "tdp": 125, "cores": 20}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (6, 2, 'MSI Z790 TOMAHAWK', 9500, 5, 'fa-microchip', '{"socket": "LGA1700", "memory_type": "DDR5", "memory_slots": 4}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (7, 2, 'ASUS B760-A GAMING', 7900, 5, 'fa-microchip', '{"socket": "LGA1700", "memory_type": "DDR5", "memory_slots": 4}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (8, 2, 'GIGABYTE B650 AORUS', 7200, 5, 'fa-microchip', '{"socket": "AM5", "memory_type": "DDR5", "memory_slots": 4}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (9, 2, 'ASUS PRIME A620M-K', 3500, 5, 'fa-microchip', '{"socket": "AM5", "memory_type": "DDR5", "memory_slots": 2}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (10, 2, 'MSI B760M BOMBER', 3900, 5, 'fa-microchip', '{"socket": "LGA1700", "memory_type": "DDR5", "memory_slots": 2}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (11, 3, 'Corsair D5 32GB', 5200, 5, 'fa-memory', '{"memory_type": "DDR5", "capacity": "32GB", "modules": 2}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (12, 3, 'Kingston D4 16GB', 1900, 5, 'fa-memory', '{"memory_type": "DDR4", "capacity": "16GB", "modules": 2}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (13, 3, 'G.Skill D5 32GB', 5900, 5, 'fa-memory', '{"memory_type": "DDR5", "capacity": "32GB", "modules": 2}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (14, 3, 'Team D4 16GB', 2200, 5, 'fa-memory', '{"memory_type": "DDR4", "capacity": "16GB", "modules": 2}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (15, 3, 'Corsair D5 64GB', 12000, 5, 'fa-memory', '{"memory_type": "DDR5", "capacity": "64GB", "modules": 2}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications, is_new, is_promotion, sale_price) VALUES (16, 4, 'RTX 4070 FE', 23000, 5, 'fa-bolt', '{"tdp": 200, "recommended_psu": 650, "length_mm": 240}', 0, 1, 21900);
INSERT INTO products (id, category_id, name, price, stock, icon, specifications, is_new, is_promotion, sale_price) VALUES (17, 4, 'RTX 4090 OC', 75000, 5, 'fa-bolt', '{"tdp": 450, "recommended_psu": 850, "length_mm": 340}', 1, 0, NULL);
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (18, 4, 'RX 7800 XT', 19500, 5, 'fa-bolt', '{"tdp": 260, "recommended_psu": 700, "length_mm": 267}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (19, 4, 'RTX 4060 Ti', 14500, 5, 'fa-bolt', '{"tdp": 160, "recommended_psu": 550, "length_mm": 240}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (20, 4, 'RX 7600', 9900, 5, 'fa-bolt', '{"tdp": 165, "recommended_psu": 550, "length_mm": 204}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (21, 5, 'RM850e 850W', 4200, 5, 'fa-plug', '{"wattage": 850, "efficiency": "80+ Gold"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (22, 5, 'BM3 750W', 2900, 5, 'fa-plug', '{"wattage": 750, "efficiency": "80+ Bronze"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (23, 5, 'MWE Gold 1050W', 5900, 5, 'fa-plug', '{"wattage": 1050, "efficiency": "80+ Gold"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (24, 5, 'Focus 650W', 3500, 5, 'fa-plug', '{"wattage": 650, "efficiency": "80+ Gold"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (25, 5, 'SilverStone 500W', 1290, 5, 'fa-plug', '{"wattage": 500, "efficiency": "80+ White"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (26, 6, 'NZXT H5 Flow', 3200, 5, 'fa-box', '{"max_gpu_length": 365, "max_cpu_height": 165}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (27, 6, 'O11 Dynamic EVO', 5900, 5, 'fa-box', '{"max_gpu_length": 426, "max_cpu_height": 167}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (28, 6, 'MasterBox Q300L', 1390, 5, 'fa-box', '{"max_gpu_length": 360, "max_cpu_height": 159}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (29, 6, '4000D Airflow', 3500, 5, 'fa-box', '{"max_gpu_length": 360, "max_cpu_height": 170}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (30, 6, 'Fractal North', 5500, 5, 'fa-box', '{"max_gpu_length": 355, "max_cpu_height": 170}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (31, 7, 'LG 27GP850', 12900, 5, 'fa-desktop', '{"resolution": "2K", "refresh_rate": "165Hz"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (32, 7, 'Odyssey G5', 9900, 5, 'fa-desktop', '{"resolution": "2K", "refresh_rate": "144Hz"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (33, 7, 'ASUS VG249', 5500, 5, 'fa-desktop', '{"resolution": "FHD", "refresh_rate": "144Hz"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (34, 7, 'Dell U2723QE', 21900, 5, 'fa-desktop', '{"resolution": "4K", "refresh_rate": "60Hz"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (35, 7, 'AOC 24G2SP', 4900, 5, 'fa-desktop', '{"resolution": "FHD", "refresh_rate": "165Hz"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (36, 8, '990 PRO 2TB', 6900, 5, 'fa-hard-drive', '{"interface": "NVMe Gen4", "read_speed": 7450}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (37, 8, 'SN850X 1TB', 3900, 5, 'fa-hard-drive', '{"interface": "NVMe Gen4", "read_speed": 7300}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (38, 8, 'Crucial P3 1TB', 2500, 5, 'fa-hard-drive', '{"interface": "NVMe Gen3", "read_speed": 3500}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (39, 8, 'Kingston NV2', 1290, 5, 'fa-hard-drive', '{"interface": "NVMe Gen4", "read_speed": 3500}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (40, 8, 'HIKSEMI 2TB', 4500, 5, 'fa-hard-drive', '{"interface": "SATA", "read_speed": 560}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (41, 9, 'AK400 Digital', 1290, 5, 'fa-snowflake', '{"sockets": ["LGA1700", "AM5"], "height_mm": 155}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (42, 9, 'NH-D15 chromax', 4100, 5, 'fa-snowflake', '{"sockets": ["LGA1700", "AM5"], "height_mm": 165}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (43, 9, 'Kraken 360', 11900, 5, 'fa-snowflake', '{"sockets": ["LGA1700", "AM5"], "height_mm": 60}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (44, 9, 'H150i iCUE', 7900, 5, 'fa-snowflake', '{"sockets": ["LGA1700", "AM5"], "height_mm": 60}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (45, 9, 'Hyper 212', 1190, 5, 'fa-snowflake', '{"sockets": ["LGA1700", "AM5"], "height_mm": 154}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (46, 10, 'G915 TKL', 6900, 5, 'fa-keyboard', '{"layout": "TKL", "switch": "GL Tactile"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (47, 10, 'BlackWidow V4', 7900, 5, 'fa-keyboard', '{"layout": "Full", "switch": "Razer Green"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (48, 10, 'K70 RGB TKL', 4500, 5, 'fa-keyboard', '{"layout": "TKL", "switch": "CHERRY MX"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (49, 10, 'Keychron V1', 3200, 5, 'fa-keyboard', '{"layout": "75%", "switch": "Keychron K Pro"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (50, 10, 'Apex Pro TKL', 7500, 5, 'fa-keyboard', '{"layout": "TKL", "switch": "OmniPoint"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (51, 11, 'G Pro X Superlight', 5600, 5, 'fa-mouse', '{"sensor": "HERO 25K", "weight": "63g"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (52, 11, 'DeathAdder V3', 4900, 5, 'fa-mouse', '{"sensor": "Focus Pro 30K", "weight": "63g"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (53, 11, 'Aerox 3 Wireless', 2900, 5, 'fa-mouse', '{"sensor": "TrueMove Air", "weight": "66g"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications, is_new, is_promotion, sale_price) VALUES (55, 11, 'Zowie EC2-C', 2400, 5, 'fa-mouse', '{"sensor": "3360", "weight": "73g"}', 0, 1, 2100);
INSERT INTO products (id, category_id, name, price, stock, icon, specifications, is_new, is_promotion, sale_price) VALUES (56, 11, 'Glorious Model O 2', 2200, 5, 'fa-mouse', '{"sensor": "BAMF 2.0", "weight": "68g"}', 1, 0, NULL);

-- Budget CPUs (Category 1)
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (57, 1, 'Intel Celeron G6900', 1850, 10, 'fa-microchip', '{"socket": "LGA1700", "tdp": 46, "cores": 2}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (58, 1, 'Intel Pentium G7400', 2590, 10, 'fa-microchip', '{"socket": "LGA1700", "tdp": 46, "cores": 2}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (59, 1, 'AMD Athlon 3000G', 1790, 10, 'fa-microchip', '{"socket": "AM4", "tdp": 35, "cores": 2}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (60, 1, 'AMD Ryzen 3 4100', 2250, 10, 'fa-microchip', '{"socket": "AM4", "tdp": 65, "cores": 4}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (61, 1, 'Intel i3-12100F', 3150, 10, 'fa-microchip', '{"socket": "LGA1700", "tdp": 58, "cores": 4}');

-- Budget Motherboards (Category 2)
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (62, 2, 'Gigabyte H610M S2H V2', 2450, 10, 'fa-microchip', '{"socket": "LGA1700", "memory_type": "DDR4", "memory_slots": 2}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (63, 2, 'MSI A520M-A PRO', 1890, 10, 'fa-microchip', '{"socket": "AM4", "memory_type": "DDR4", "memory_slots": 2}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (64, 2, 'ASUS Prime H610M-K', 2550, 10, 'fa-microchip', '{"socket": "LGA1700", "memory_type": "DDR4", "memory_slots": 2}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (65, 2, 'ASRock A520M-HVS', 1750, 10, 'fa-microchip', '{"socket": "AM4", "memory_type": "DDR4", "memory_slots": 2}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (66, 2, 'Biostar H610MHP', 2190, 10, 'fa-microchip', '{"socket": "LGA1700", "memory_type": "DDR4", "memory_slots": 2}');

-- Budget RAM (Category 3)
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (67, 3, 'Hiksemi 8GB DDR4 3200', 650, 20, 'fa-memory', '{"memory_type": "DDR4", "capacity": "8GB", "modules": 1}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (68, 3, 'TeamGroup Elite 8GB DDR4', 690, 20, 'fa-memory', '{"memory_type": "DDR4", "capacity": "8GB", "modules": 1}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (69, 3, 'Kingston Value 8GB DDR4', 750, 20, 'fa-memory', '{"memory_type": "DDR4", "capacity": "8GB", "modules": 1}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (70, 3, 'Apacer Panther 8GB DDR4', 790, 20, 'fa-memory', '{"memory_type": "DDR4", "capacity": "8GB", "modules": 1}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (71, 3, 'Corsair LPX 8GB DDR4', 850, 20, 'fa-memory', '{"memory_type": "DDR4", "capacity": "8GB", "modules": 1}');

-- Budget GPUs (Category 4)
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (72, 4, 'ASUS GT 730 2GB', 1490, 10, 'fa-bolt', '{"tdp": 38, "recommended_psu": 300}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (73, 4, 'MSI GT 1030 2GB GDDR5', 2790, 10, 'fa-bolt', '{"tdp": 30, "recommended_psu": 300}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (74, 4, 'Biostar RX 550 4GB', 2450, 10, 'fa-bolt', '{"tdp": 50, "recommended_psu": 350}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (75, 4, 'Inno3D GTX 1050 Ti', 3900, 10, 'fa-bolt', '{"tdp": 75, "recommended_psu": 300}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (76, 4, 'Sparkle Arc A310', 3500, 10, 'fa-bolt', '{"tdp": 75, "recommended_psu": 350}');

-- Budget PSUs (Category 5)
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (77, 5, 'DTECH 450W', 450, 15, 'fa-plug', '{"wattage": 450, "efficiency": "Standard"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (78, 5, 'Deepcool DE600 v2', 890, 15, 'fa-plug', '{"wattage": 600, "efficiency": "Standard"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (79, 5, 'SilverStone 500W White', 1050, 15, 'fa-plug', '{"wattage": 500, "efficiency": "80+ White"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (80, 5, 'Aerocool United 500W', 950, 15, 'fa-plug', '{"wattage": 500, "efficiency": "80+ White"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (81, 5, 'Plenty 500W Super Black', 650, 15, 'fa-plug', '{"wattage": 500, "efficiency": "Standard"}');

-- Budget Cases (Category 6)
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (82, 6, 'Gview i2-31', 650, 10, 'fa-box', '{"max_gpu_length": 300, "max_cpu_height": 150}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (83, 6, 'Nubwo NPC-01', 790, 10, 'fa-box', '{"max_gpu_length": 320, "max_cpu_height": 160}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (84, 6, 'Tsunami G15 Galaxy', 850, 10, 'fa-box', '{"max_gpu_length": 310, "max_cpu_height": 160}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (85, 6, 'ITSONAS Spark', 590, 10, 'fa-box', '{"max_gpu_length": 280, "max_cpu_height": 145}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (86, 6, 'AeroCool CS-107', 890, 10, 'fa-box', '{"max_gpu_length": 286, "max_cpu_height": 157}');

-- Budget SSDs (Category 8)
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (87, 8, 'Hiksemi Wave(S) 256GB', 590, 20, 'fa-hard-drive', '{"interface": "SATA", "read_speed": 530}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (88, 8, 'Kingston A400 240GB', 750, 20, 'fa-hard-drive', '{"interface": "SATA", "read_speed": 500}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (89, 8, 'Team GX2 256GB', 620, 20, 'fa-hard-drive', '{"interface": "SATA", "read_speed": 500}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (90, 8, 'WD Green 240GB', 820, 20, 'fa-hard-drive', '{"interface": "SATA", "read_speed": 545}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (91, 8, 'Silicon Power 256GB', 680, 20, 'fa-hard-drive', '{"interface": "SATA", "read_speed": 550}');

-- Budget Coolers (Category 9)
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (92, 9, 'Tsunami TSS-1000', 290, 15, 'fa-snowflake', '{"sockets": ["LGA1700", "AM4"], "height_mm": 130}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (93, 9, 'Deepcool AG200', 350, 15, 'fa-snowflake', '{"sockets": ["LGA1700", "AM4"], "height_mm": 133}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (94, 9, 'ID-COOLING DK-03', 250, 15, 'fa-snowflake', '{"sockets": ["LGA1700", "AM4"], "height_mm": 63}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (95, 9, 'Sigma GT400', 450, 15, 'fa-snowflake', '{"sockets": ["LGA1700", "AM4"], "height_mm": 135}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (96, 9, 'Cooler Master T2', 390, 15, 'fa-snowflake', '{"sockets": ["LGA1700", "AM4"], "height_mm": 140}');

-- Budget Keyboards (Category 10)
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (97, 10, 'Logitech K120', 320, 20, 'fa-keyboard', '{"layout": "Full"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (98, 10, 'Nubwo NK-18', 190, 20, 'fa-keyboard', '{"layout": "Full"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (99, 10, 'OKER KB-20', 220, 20, 'fa-keyboard', '{"layout": "Full"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (100, 10, 'Rapoo N1200', 250, 20, 'fa-keyboard', '{"layout": "Full"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (101, 10, 'Microsoft Keyboard 600', 450, 20, 'fa-keyboard', '{"layout": "Full"}');

-- Budget Mice (Category 11)
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (102, 11, 'Logitech M100', 240, 20, 'fa-mouse', '{"sensor": "Optical"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (103, 11, 'OKER L7-300', 150, 20, 'fa-mouse', '{"sensor": "Optical"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (104, 11, 'Nubwo NM-18', 120, 20, 'fa-mouse', '{"sensor": "Optical"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (105, 11, 'HP M150', 180, 20, 'fa-mouse', '{"sensor": "Optical"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (106, 11, 'Microsoft Mouse 200', 290, 20, 'fa-mouse', '{"sensor": "Optical"}');

-- Ultra Budget Collection (107-116)
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (107, 1, 'Intel Celeron G6900', 1490, 10, 'fa-microchip', '{"socket": "LGA1700", "tdp": 46, "cores": 2}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (108, 2, 'ECS H610H7-M12', 1850, 10, 'fa-microchip', '{"socket": "LGA1700", "memory_type": "DDR4", "memory_slots": 2}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (109, 3, 'Generic 4GB DDR4 2400', 390, 20, 'fa-memory', '{"memory_type": "DDR4", "capacity": "4GB", "modules": 1}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (110, 4, 'OEM GT 710 1GB', 890, 10, 'fa-bolt', '{"tdp": 19, "recommended_psu": 250}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (111, 5, 'Generic 450W PSU', 350, 15, 'fa-plug', '{"wattage": 450, "efficiency": "Standard"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (112, 6, 'Simple Office Case', 390, 10, 'fa-box', '{"max_gpu_length": 250, "max_cpu_height": 140}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (113, 8, 'Generic 120GB SSD', 390, 20, 'fa-hard-drive', '{"interface": "SATA", "read_speed": 450}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (114, 9, 'Stock Style Cooler', 150, 15, 'fa-snowflake', '{"sockets": ["LGA1700", "AM4"], "height_mm": 50}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (115, 10, 'Generic Keyboard', 120, 20, 'fa-keyboard', '{"layout": "Full"}');
INSERT INTO products (id, category_id, name, price, stock, icon, specifications) VALUES (116, 11, 'Generic Optical Mouse', 80, 20, 'fa-mouse', '{"sensor": "Optical"}');

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
