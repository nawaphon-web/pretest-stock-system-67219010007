-- TechStock Database Schema and Sample Data

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS product_reservations;
DROP TABLE IF EXISTS rma_requests;
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS inventory;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS suppliers;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

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

-- Insert Users (admin123 / user123)
INSERT INTO users (username, password, role) VALUES 
('admin', '$2y$10$f/9S5i6fOqO/Qn5mY/K5He1oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('user', '$2y$10$f/9S5i6fOqO/Qn5mY/K5He1oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');

-- Insert Categories
INSERT INTO categories (id, name) VALUES 
(1, 'cpu'), (2, 'mainboard'), (3, 'ram'), (4, 'gpu'), (5, 'psu'), (6, 'case'), (7, 'monitor'), (8, 'ssd'), (9, 'cooler');

-- Insert Products
-- CPUs (1)
INSERT INTO products (name, category_id, price, stock, icon, specifications) VALUES
('Intel Core i5-13600K', 1, 11900, 15, 'fa-microchip', '{"socket": "LGA1700", "tdp": 125, "cores": 14, "threads": 20}'),
('Intel Core i9-14900K', 1, 24900, 8, 'fa-microchip', '{"socket": "LGA1700", "tdp": 125, "cores": 24, "threads": 32}'),
('AMD Ryzen 7 7800X3D', 1, 14900, 12, 'fa-microchip', '{"socket": "AM5", "tdp": 120, "cores": 8, "threads": 16}'),
('AMD Ryzen 5 7600', 1, 8900, 20, 'fa-microchip', '{"socket": "AM5", "tdp": 65, "cores": 6, "threads": 12}'),
('Intel Core i7-14700K', 1, 15900, 10, 'fa-microchip', '{"socket": "LGA1700", "tdp": 125, "cores": 20, "threads": 28}');

-- Mainboards (2)
INSERT INTO products (name, category_id, price, stock, icon, specifications) VALUES
('MSI MAG Z790 TOMAHAWK WIFI', 2, 9500, 10, 'fa-microchip', '{"socket": "LGA1700", "chipset": "Z790", "form_factor": "ATX", "memory_type": "DDR5", "memory_slots": 4}'),
('ASUS ROG STRIX B760-A GAMING WIFI', 2, 7900, 12, 'fa-microchip', '{"socket": "LGA1700", "chipset": "B760", "form_factor": "ATX", "memory_type": "DDR4", "memory_slots": 4}'),
('GIGABYTE B650 AORUS ELITE AX', 2, 7200, 15, 'fa-microchip', '{"socket": "AM5", "chipset": "B650", "form_factor": "ATX", "memory_type": "DDR5", "memory_slots": 4}'),
('ASUS PRIME A620M-K', 2, 3500, 25, 'fa-microchip', '{"socket": "AM5", "chipset": "A620", "form_factor": "mATX", "memory_type": "DDR5", "memory_slots": 2}'),
('MSI B760M BOMBER DDR4', 2, 3900, 30, 'fa-microchip', '{"socket": "LGA1700", "chipset": "B760", "form_factor": "mATX", "memory_type": "DDR4", "memory_slots": 2}');

-- RAM (3)
INSERT INTO products (name, category_id, price, stock, icon, specifications) VALUES
('Corsair Vengeance DDR5 32GB 6000MHz', 3, 5200, 20, 'fa-memory', '{"memory_type": "DDR5", "capacity": "32GB", "speed": "6000MHz", "modules": 2}'),
('Kingston Fury Beast DDR4 16GB 3200MHz', 3, 1900, 40, 'fa-memory', '{"memory_type": "DDR4", "capacity": "16GB", "speed": "3200MHz", "modules": 2}'),
('G.Skill Trident Z5 RGB 32GB DDR5 6400', 3, 5900, 15, 'fa-memory', '{"memory_type": "DDR5", "capacity": "32GB", "speed": "6400MHz", "modules": 2}'),
('TeamGroup T-Force Delta RGB DDR4 16GB', 3, 2200, 25, 'fa-memory', '{"memory_type": "DDR4", "capacity": "16GB", "speed": "3600MHz", "modules": 2}'),
('Corsair Dominator Titanium 64GB DDR5', 3, 12000, 5, 'fa-memory', '{"memory_type": "DDR5", "capacity": "64GB", "speed": "6600MHz", "modules": 2}');

-- GPU (4)
INSERT INTO products (name, category_id, price, stock, icon, specifications) VALUES
('NVIDIA GeForce RTX 4070 FE', 4, 23000, 10, 'fa-bolt', '{"chipset": "NVIDIA", "vram": "12GB", "length_mm": 244, "tdp": 200}'),
('ASUS ROG Strix RTX 4090 OC', 4, 75000, 3, 'fa-bolt', '{"chipset": "NVIDIA", "vram": "24GB", "length_mm": 357, "tdp": 450}'),
('Sapphire Pulse Radeon RX 7800 XT', 4, 19500, 15, 'fa-bolt', '{"chipset": "AMD", "vram": "16GB", "length_mm": 280, "tdp": 263}'),
('MSI Ventus 2X RTX 4060 Ti', 4, 14500, 20, 'fa-bolt', '{"chipset": "NVIDIA", "vram": "8GB", "length_mm": 199, "tdp": 160}'),
('Gigabyte RX 7600 Gaming OC', 4, 9900, 18, 'fa-bolt', '{"chipset": "AMD", "vram": "8GB", "length_mm": 282, "tdp": 165}');

-- PSU (5)
INSERT INTO products (name, category_id, price, stock, icon, specifications) VALUES
('Corsair RM850e 850W Gold', 5, 4200, 15, 'fa-plug', '{"wattage": 850, "efficiency": "80+ Gold", "modular": "Full"}'),
('Thermaltake Smart BM3 750W', 5, 2900, 25, 'fa-plug', '{"wattage": 750, "efficiency": "80+ Bronze", "modular": "Semi"}'),
('Cooler Master MWE Gold 1050 V2', 5, 5900, 10, 'fa-plug', '{"wattage": 1050, "efficiency": "80+ Gold", "modular": "Full"}'),
('Seasonic Focus GX-650', 5, 3500, 20, 'fa-plug', '{"wattage": 650, "efficiency": "80+ Gold", "modular": "Full"}'),
('SilverStone ST50F-ES230 500W', 5, 1290, 50, 'fa-plug', '{"wattage": 500, "efficiency": "80+ White", "modular": "No"}');

-- Case (6)
INSERT INTO products (name, category_id, price, stock, icon, specifications) VALUES
('NZXT H5 Flow Black', 6, 3200, 15, 'fa-box', '{"form_factor": ["ATX", "mATX", "ITX"], "max_gpu_length": 365, "max_cpu_height": 165}'),
('Lian Li O11 Dynamic EVO', 6, 5900, 8, 'fa-box', '{"form_factor": ["E-ATX", "ATX", "mATX", "ITX"], "max_gpu_length": 422, "max_cpu_height": 167}'),
('Cooler Master MasterBox Q300L', 6, 1390, 30, 'fa-box', '{"form_factor": ["mATX", "ITX"], "max_gpu_length": 360, "max_cpu_height": 159}'),
('Corsair 4000D Airflow', 6, 3500, 20, 'fa-box', '{"form_factor": ["ATX", "mATX", "ITX"], "max_gpu_length": 360, "max_cpu_height": 170}'),
('Fractal Design North Charcoal', 6, 5500, 5, 'fa-box', '{"form_factor": ["ATX", "mATX", "ITX"], "max_gpu_length": 355, "max_cpu_height": 170}');

-- Monitor (7)
INSERT INTO products (name, category_id, price, stock, icon, specifications) VALUES
('LG UltraGear 27GP850-B', 7, 12900, 10, 'fa-desktop', '{"size": "27\"", "resolution": "2K QHD", "refresh_rate": "165Hz", "panel": "Nano IPS"}'),
('Samsung Odyssey G5 32\"', 7, 9900, 12, 'fa-desktop', '{"size": "32\"", "resolution": "2K QHD", "refresh_rate": "144Hz", "panel": "VA"}'),
('ASUS TUF Gaming VG249Q3A', 7, 5500, 20, 'fa-desktop', '{"size": "23.8\"", "resolution": "Full HD", "refresh_rate": "180Hz", "panel": "IPS"}'),
('Dell UltraSharp U2723QE', 7, 21900, 8, 'fa-desktop', '{"size": "27\"", "resolution": "4K UHD", "refresh_rate": "60Hz", "panel": "IPS Black"}'),
('AOC 24G2SP/67', 7, 4900, 25, 'fa-desktop', '{"size": "23.8\"", "resolution": "Full HD", "refresh_rate": "165Hz", "panel": "IPS"}');

-- SSD (8)
INSERT INTO products (name, category_id, price, stock, icon, specifications) VALUES
('Samsung 990 PRO 2TB', 8, 6900, 15, 'fa-hard-drive', '{"capacity": "2TB", "interface": "PCIe 4.0", "read_speed": "7450MB/s"}'),
('WD Black SN850X 1TB', 8, 3900, 20, 'fa-hard-drive', '{"capacity": "1TB", "interface": "PCIe 4.0", "read_speed": "7300MB/s"}'),
('Crucial P3 Plus 1TB', 8, 2500, 30, 'fa-hard-drive', '{"capacity": "1TB", "interface": "PCIe 4.0", "read_speed": "5000MB/s"}'),
('Kingston NV2 500GB', 8, 1290, 50, 'fa-hard-drive', '{"capacity": "500GB", "interface": "PCIe 4.0", "read_speed": "3500MB/s"}'),
('HIKSEMI FUTURE 2TB', 8, 4500, 15, 'fa-hard-drive', '{"capacity": "2TB", "interface": "PCIe 4.0", "read_speed": "7450MB/s"}');

-- Cooler (9)
INSERT INTO products (name, category_id, price, stock, icon, specifications) VALUES
('DeepCool AK400 Digital', 9, 1290, 20, 'fa-fan', '{"sockets": ["LGA1700", "AM4", "AM5"], "height_mm": 155, "tdp_rating": 220}'),
('Noctua NH-D15 chromax', 9, 4100, 5, 'fa-fan', '{"sockets": ["LGA1700", "AM4", "AM5", "LGA1200"], "height_mm": 165, "tdp_rating": 250}'),
('NZXT Kraken Elite 360', 9, 11900, 10, 'fa-fan', '{"sockets": ["LGA1700", "AM4", "AM5"], "height_mm": 59, "tdp_rating": 300, "type": "Liquid (AIO)"}'),
('Corsair iCUE H150i', 9, 7900, 12, 'fa-fan', '{"sockets": ["LGA1700", "AM4", "AM5"], "height_mm": 52, "tdp_rating": 350, "type": "Liquid (AIO)"}'),
('Cooler Master Hyper 212', 9, 1190, 30, 'fa-fan', '{"sockets": ["LGA1700", "AM4", "AM5"], "height_mm": 154, "tdp_rating": 180}');

INSERT INTO suppliers (id, name) VALUES (1, 'Synnex'), (2, 'Ingram Micro'), (3, 'Ascenti Resources');
