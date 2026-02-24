CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category_id INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    image_url VARCHAR(255),
    specifications JSON,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Insert sample users
INSERT IGNORE INTO users (username, password, role) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'), -- password: admin123
('user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');   -- password: admin123 (reused hash for user123 simplicity in setup if needed, but original comments said user123)

-- Insert Categories
INSERT IGNORE INTO categories (name) VALUES 
('cpu'), ('mainboard'), ('ram'), ('gpu'), ('psu'), ('case'), ('monitor'), ('ssd'), ('cooler');

-- Insert Sample Products

-- CPUs
INSERT INTO products (name, category_id, price, stock, image_url, specifications) VALUES
-- CPUs
INSERT INTO products (name, category_id, price, stock, image_url, specifications) VALUES
('Intel Core i5-13600K', 1, 11900.00, 15, 'https://c1.neweggimages.com/productimage/nb640/19-118-416-V01.jpg', '{"socket": "LGA1700", "tdp": 125, "cores": 14, "threads": 20}'),
('Intel Core i9-14900K', 1, 24900.00, 8, 'https://c1.neweggimages.com/productimage/nb640/19-118-477-V01.jpg', '{"socket": "LGA1700", "tdp": 125, "cores": 24, "threads": 32}'),
('AMD Ryzen 7 7800X3D', 1, 14900.00, 12, 'https://c1.neweggimages.com/ProductImageCompressAll300/19-113-793-03.png', '{"socket": "AM5", "tdp": 120, "cores": 8, "threads": 16}'),
('AMD Ryzen 5 7600', 1, 8900.00, 20, 'https://c1.neweggimages.com/ProductImageCompressAll300/19-113-787-03.png', '{"socket": "AM5", "tdp": 65, "cores": 6, "threads": 12}'),
('Intel Core i7-14700K', 1, 15900.00, 10, 'https://c1.neweggimages.com/productimage/nb640/19-118-478-V01.jpg', '{"socket": "LGA1700", "tdp": 125, "cores": 20, "threads": 28}');

-- Mainboards
INSERT INTO products (name, category_id, price, stock, image_url, specifications) VALUES
('MSI MAG Z790 TOMAHAWK WIFI', 2, 9500.00, 10, 'https://storage-asset.msi.com/global/picture/image/feature/mb/Z790/TOMAHAWK/z790-tomahawk-wifi-hero.png', '{"socket": "LGA1700", "chipset": "Z790", "form_factor": "ATX", "memory_type": "DDR5", "memory_slots": 4}'),
('ASUS ROG STRIX B760-A GAMING WIFI', 2, 7900.00, 12, 'https://dlcdnwebbots.asus.com/gain/3D7A1D6B-4D1D-4A2D-B9E4-E6E4D1D1D1D1/with_alpha/nw/', '{"socket": "LGA1700", "chipset": "B760", "form_factor": "ATX", "memory_type": "DDR4", "memory_slots": 4}'),
('GIGABYTE B650 AORUS ELITE AX', 2, 7200.00, 15, 'https://static.gigabyte.com/StaticFile/Image/Global/e6e4d1d1d1d1d1d1d1d1d1d1d1d1d1d1/Product/32571/png/1000', '{"socket": "AM5", "chipset": "B650", "form_factor": "ATX", "memory_type": "DDR5", "memory_slots": 4}'),
('ASUS PRIME A620M-K', 2, 3500.00, 25, 'https://dlcdnwebbots.asus.com/gain/e6e4d1d1d1d1d1d1d1d1d1/with_alpha/nw/', '{"socket": "AM5", "chipset": "A620", "form_factor": "mATX", "memory_type": "DDR5", "memory_slots": 2}'),
('MSI B760M BOMBER DDR4', 2, 3900.00, 30, 'https://storage-asset.msi.com/global/picture/image/feature/mb/B760/BOMBER/b760m-bomber-ddr4-hero.png', '{"socket": "LGA1700", "chipset": "B760", "form_factor": "mATX", "memory_type": "DDR4", "memory_slots": 2}');

-- RAM
INSERT INTO products (name, category_id, price, stock, image_url, specifications) VALUES
('Corsair Vengeance DDR5 32GB 6000MHz', 3, 5200.00, 20, 'https://assets.corsair.com/image/upload/f_auto,q_auto/v1/products/Memory/CMK32GX5M2B6000C30/Vengeance_DDR5_Black_01.png', '{"memory_type": "DDR5", "capacity": "32GB", "speed": "6000MHz", "modules": 2}'),
('Kingston Fury Beast DDR4 16GB 3200MHz', 3, 1900.00, 40, 'https://media.kingston.com/kingston/product/kf432c16bbk2_16-1-sm.png', '{"memory_type": "DDR4", "capacity": "16GB", "speed": "3200MHz", "modules": 2}'),
('G.Skill Trident Z5 RGB 32GB DDR5 6400', 3, 5900.00, 15, 'https://www.gskill.com/_upload/images/163351221111.png', '{"memory_type": "DDR5", "capacity": "32GB", "speed": "6400MHz", "modules": 2}'),
('TeamGroup T-Force Delta RGB DDR4 16GB', 3, 2200.00, 25, 'https://www.teamgroupinc.com/en/upload/product/20210323091151_image.png', '{"memory_type": "DDR4", "capacity": "16GB", "speed": "3600MHz", "modules": 2}'),
('Corsair Dominator Titanium 64GB DDR5', 3, 12000.00, 5, 'https://assets.corsair.com/image/upload/f_auto,q_auto/v1/products/Memory/dominator-titanium-ddr5-black.png', '{"memory_type": "DDR5", "capacity": "64GB", "speed": "6600MHz", "modules": 2}');

-- GPU
INSERT INTO products (name, category_id, price, stock, image_url, specifications) VALUES
('NVIDIA GeForce RTX 4070 Founders Edition', 4, 23000.00, 10, 'https://assets.nvidia.university/images/en-us/geforce/rtx-4070-product-photo.png', '{"chipset": "NVIDIA", "vram": "12GB", "length_mm": 244, "tdp": 200}'),
('ASUS ROG Strix RTX 4090 OC', 4, 75000.00, 3, 'https://dlcdnwebbots.asus.com/gain/rtx-4090-strix-oc.png', '{"chipset": "NVIDIA", "vram": "24GB", "length_mm": 357, "tdp": 450}'),
('Sapphire Pulse Radeon RX 7800 XT', 4, 19500.00, 15, 'https://www.sapphiretech.com/-/media/websites/sapphirenext/product-images/pulse-rx-7800-xt-1.png', '{"chipset": "AMD", "vram": "16GB", "length_mm": 280, "tdp": 263}'),
('MSI Ventus 2X RTX 4060 Ti', 4, 14500.00, 20, 'https://storage-asset.msi.com/global/picture/image/feature/vga/NVIDIA/RTX-4060-Ti/RTX-4060-Ti-VENTUS-2X-BLACK-8G-OC-hero.png', '{"chipset": "NVIDIA", "vram": "8GB", "length_mm": 199, "tdp": 160}'),
('Gigabyte RX 7600 Gaming OC', 4, 9900.00, 18, 'https://static.gigabyte.com/StaticFile/Image/Global/Product/32571/png/1000', '{"chipset": "AMD", "vram": "8GB", "length_mm": 282, "tdp": 165}');

-- PSU
INSERT INTO products (name, category_id, price, stock, image_url, specifications) VALUES
('Corsair RM850e 850W Gold', 5, 4200.00, 15, 'https://assets.corsair.com/image/upload/f_auto,q_auto/v1/products/PSU/RM850e/RM850e_01.png', '{"wattage": 850, "efficiency": "80+ Gold", "modular": "Full"}'),
('Thermaltake Smart BM3 750W', 5, 2900.00, 25, 'https://www.thermaltake.com/pub/media/catalog/product/s/m/smart_bm3_750w_01.png', '{"wattage": 750, "efficiency": "80+ Bronze", "modular": "Semi"}'),
('Cooler Master MWE Gold 1050 V2', 5, 5900.00, 10, 'https://www.coolermaster.com/catalog/product/MWE-Gold-1050-V2-hero.png', '{"wattage": 1050, "efficiency": "80+ Gold", "modular": "Full"}'),
('Seasonic Focus GX-650', 5, 3500.00, 20, 'https://seasonic.com/pub/media/catalog/product/f/o/focus-gx-black.png', '{"wattage": 650, "efficiency": "80+ Gold", "modular": "Full"}'),
('SilverStone ST50F-ES230 500W', 5, 1290.00, 50, 'https://www.silverstonetek.com/images/products/st50f-es230/st50f-es230-front-01.png', '{"wattage": 500, "efficiency": "80+ White", "modular": "No"}');

-- Case
INSERT INTO products (name, category_id, price, stock, image_url, specifications) VALUES
('NZXT H5 Flow Black', 6, 3200.00, 15, 'https://assets.nzxt.com/images/H5_Flow_Black_Hero.png', '{"form_factor": ["ATX", "mATX", "ITX"], "max_gpu_length": 365, "max_cpu_height": 165}'),
('Lian Li O11 Dynamic EVO', 6, 5900.00, 8, 'https://lian-li.com/wp-content/uploads/2021/12/o11d-evo-black-01.png', '{"form_factor": ["E-ATX", "ATX", "mATX", "ITX"], "max_gpu_length": 422, "max_cpu_height": 167}'),
('Cooler Master MasterBox Q300L', 6, 1390.00, 30, 'https://www.coolermaster.com/catalog/product/MasterBox-Q300L-hero.png', '{"form_factor": ["mATX", "ITX"], "max_gpu_length": 360, "max_cpu_height": 159}'),
('Corsair 4000D Airflow', 6, 3500.00, 20, 'https://assets.corsair.com/image/upload/f_auto,q_auto/v1/products/Cases/4000D_Airflow/4000D_Airflow_Black_01.png', '{"form_factor": ["ATX", "mATX", "ITX"], "max_gpu_length": 360, "max_cpu_height": 170}'),
('Fractal Design North Charcoal TG', 6, 5500.00, 5, 'https://www.fractal-design.com/wp-content/uploads/North-Charcoal-TG-Front.png', '{"form_factor": ["ATX", "mATX", "ITX"], "max_gpu_length": 355, "max_cpu_height": 170}');

-- Monitor
INSERT INTO products (name, category_id, price, stock, image_url, specifications) VALUES
('LG UltraGear 27GP850-B', 7, 12900.00, 10, 'https://www.lg.com/th/images/monitors/md07525330/gallery/D-01.jpg', '{"size": "27\"", "resolution": "2K QHD", "refresh_rate": "165Hz", "panel": "Nano IPS"}'),
('Samsung Odyssey G5 32\"', 7, 9900.00, 12, 'https://images.samsung.com/is/image/samsung/p6pim/th/lc32g55tqbexxt/gallery/th-odyssey-g5-g55t-lc32g55tqbexxt-534720194?$650_519_PNG$', '{"size": "32\"", "resolution": "2K QHD", "refresh_rate": "144Hz", "panel": "VA"}'),
('ASUS TUF Gaming VG249Q3A', 7, 5500.00, 20, 'https://dlcdnwebbots.asus.com/gain/TUFGamingVG249Q3A-hero.png', '{"size": "23.8\"", "resolution": "Full HD", "refresh_rate": "180Hz", "panel": "IPS"}'),
('Dell UltraSharp U2723QE', 7, 21900.00, 8, 'https://snpi.dell.com/is/image/DellPhotos/U2723QE_Primary?fmt=png-alpha', '{"size": "27\"", "resolution": "4K UHD", "refresh_rate": "60Hz", "panel": "IPS Black"}'),
('AOC 24G2SP/67', 7, 4900.00, 25, 'https://aoc.com/images/products/24G2SP_67_1.png', '{"size": "23.8\"", "resolution": "Full HD", "refresh_rate": "165Hz", "panel": "IPS"}');

-- SSD
INSERT INTO products (name, category_id, price, stock, image_url, specifications) VALUES
('Samsung 990 PRO 2TB', 8, 6900.00, 15, 'https://images.samsung.com/is/image/samsung/p6pim/th/mz-v9p2t0bw/gallery/th-990pro-m2-nvme-ssd-mz-v9p2t0bw-534720194?$650_519_PNG$', '{"capacity": "2TB", "interface": "PCIe 4.0", "read_speed": "7450MB/s"}'),
('WD Black SN850X 1TB', 8, 3900.00, 20, 'https://www.westerndigital.com/content/dam/wdc/website/admin/products/internal-ssd/wd-black-sn850x-nvme-ssd/gallery/1tb/wd-black-sn850x-nvme-ssd-1tb-front.png', '{"capacity": "1TB", "interface": "PCIe 4.0", "read_speed": "7300MB/s"}'),
('Crucial P3 Plus 1TB', 8, 2500.00, 30, 'https://content.crucial.com/content/dam/crucial/ssd-products/p3-plus/images/product-render/crucial-p3-plus-ssd-product-render.png', '{"capacity": "1TB", "interface": "PCIe 4.0", "read_speed": "5000MB/s"}'),
('Kingston NV2 500GB', 8, 1290.00, 50, 'https://media.kingston.com/kingston/product/nv2-ssd-front-sm.png', '{"capacity": "500GB", "interface": "PCIe 4.0", "read_speed": "3500MB/s"}'),
('HIKSEMI FUTURE 2TB', 8, 4500.00, 15, 'https://www.hiksemi.tech/content/dam/hiksemi/products/ssd/future/future-2tb.png', '{"capacity": "2TB", "interface": "PCIe 4.0", "read_speed": "7450MB/s"}');

-- Coolers
INSERT INTO products (name, category_id, price, stock, image_url, specifications) VALUES
('DeepCool AK400 Digital', 9, 1290.00, 20, 'https://www.deepcool.com/download/AK400_Digital_Hero.png', '{"sockets": ["LGA1700", "AM4", "AM5"], "height_mm": 155, "tdp_rating": 220}'),
('Noctua NH-D15 chromax.black', 9, 4100.00, 5, 'https://noctua.at/pub/media/catalog/product/n/h/nh_d15_chromax_black_1.jpg', '{"sockets": ["LGA1700", "AM4", "AM5", "LGA1200"], "height_mm": 165, "tdp_rating": 250}'),
('NZXT Kraken Elite 360 RGB', 9, 11900.00, 10, 'https://assets.nzxt.com/images/Kraken_Elite_360_RGB_Black_Hero.png', '{"sockets": ["LGA1700", "AM4", "AM5"], "height_mm": 59, "tdp_rating": 300, "type": "Liquid (AIO)"}'),
('Corsair iCUE H150i ELITE CAPELLIX XT', 9, 7900.00, 12, 'https://assets.corsair.com/image/upload/f_auto,q_auto/v1/products/Cooling/h150i-elite-capellix-xt.png', '{"sockets": ["LGA1700", "AM4", "AM5"], "height_mm": 52, "tdp_rating": 350, "type": "Liquid (AIO)"}'),
('Cooler Master Hyper 212 Halo Black', 9, 1190.00, 30, 'https://www.coolermaster.com/catalog/product/Hyper-212-Halo-Black-hero.png', '{"sockets": ["LGA1700", "AM4", "AM5"], "height_mm": 154, "tdp_rating": 180}');

-- Suppliers
CREATE TABLE IF NOT EXISTS suppliers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    contact_info TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Inventory (S/N Tracking)
CREATE TABLE IF NOT EXISTS inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    serial_number VARCHAR(100) NOT NULL UNIQUE,
    supplier_id INT,
    status ENUM('available', 'sold', 'rma', 'returned') DEFAULT 'available',
    received_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id)
);

-- Orders
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'paid', 'processing', 'shipped', 'completed', 'cancelled') DEFAULT 'pending',
    assembly_service BOOLEAN DEFAULT FALSE,
    tax_info JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Order Items
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    inventory_id INT, -- Linked to specific S/N
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (inventory_id) REFERENCES inventory(id)
);

-- RMA (Return Merchandise Authorization)
CREATE TABLE IF NOT EXISTS rma_requests (
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

-- Product Reservations (Temporarily hold stock for 15 mins)
CREATE TABLE IF NOT EXISTS product_reservations (
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

-- Truncate existing data for a clean state (Optional, but good for resetting pretest)
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE products;
TRUNCATE TABLE categories;
TRUNCATE TABLE suppliers;
TRUNCATE TABLE inventory;
TRUNCATE TABLE product_reservations;
SET FOREIGN_KEY_CHECKS = 1;

-- Insert Sample Users (already present, keeping it)
-- Insert sample users
-- INSERT IGNORE INTO users (username, password, role) VALUES
-- ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'), -- password: admin123
-- ('user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');   -- password: admin123 (reused hash for user123 simplicity in setup if needed, but original comments said user123)

-- Insert Categories
INSERT INTO categories (id, name) VALUES 
(1, 'cpu'), (2, 'mainboard'), (3, 'ram'), (4, 'gpu'), (5, 'psu'), (6, 'case'), (7, 'monitor'), (8, 'ssd'), (9, 'cooler');

-- Insert Sample Products (already present, keeping it)
-- CPUs
-- INSERT INTO products (name, category_id, price, stock, image_url, specifications) VALUES
-- ('Intel Core i5-13600K', 1, 11900.00, 15, 'https://c1.neweggimages.com/productimage/nb640/19-118-416-V01.jpg', '{"socket": "LGA1700", "tdp": 125, "cores": 14, "threads": 20}'),
-- ('Intel Core i9-14900K', 1, 24900.00, 8, 'https://c1.neweggimages.com/productimage/nb640/19-118-477-V01.jpg', '{"socket": "LGA1700", "tdp": 125, "cores": 24, "threads": 32}'),
-- ('AMD Ryzen 7 7800X3D', 1, 14900.00, 12, 'https://c1.neweggimages.com/ProductImageCompressAll300/19-113-793-03.png', '{"socket": "AM5", "tdp": 120, "cores": 8, "threads": 16}'),
-- ('AMD Ryzen 5 7600', 1, 8900.00, 20, 'https://c1.neweggimages.com/ProductImageCompressAll300/19-113-787-03.png', '{"socket": "AM5", "tdp": 65, "cores": 6, "threads": 12}'),
-- ('Intel Core i7-14700K', 1, 15900.00, 10, 'https://c1.neweggimages.com/productimage/nb640/19-118-478-V01.jpg', '{"socket": "LGA1700", "tdp": 125, "cores": 20, "threads": 28}');

-- Mainboards
-- INSERT INTO products (name, category_id, price, stock, image_url, specifications) VALUES
-- ('MSI MAG Z790 TOMAHAWK WIFI', 2, 9500.00, 10, 'https://storage-asset.msi.com/global/picture/image/feature/mb/Z790/TOMAHAWK/z790-tomahawk-wifi-hero.png', '{"socket": "LGA1700", "chipset": "Z790", "form_factor": "ATX", "memory_type": "DDR5", "memory_slots": 4}'),
-- ('ASUS ROG STRIX B760-A GAMING WIFI', 2, 7900.00, 12, 'https://dlcdnwebbots.asus.com/gain/3D7A1D6B-4D1D-4A2D-B9E4-E6E4D1D1D1D1/with_alpha/nw/', '{"socket": "LGA1700", "chipset": "B760", "form_factor": "ATX", "memory_type": "DDR4", "memory_slots": 4}'),
-- ('GIGABYTE B650 AORUS ELITE AX', 2, 7200.00, 15, 'https://static.gigabyte.com/StaticFile/Image/Global/e6e4d1d1d1d1d1d1d1d1d1d1d1d1d1d1/Product/32571/png/1000', '{"socket": "AM5", "chipset": "B650", "form_factor": "ATX", "memory_type": "DDR5", "memory_slots": 4}'),
-- ('ASUS PRIME A620M-K', 2, 3500.00, 25, 'https://dlcdnwebbots.asus.com/gain/e6e4d1d1d1d1d1d1d1d1d1/with_alpha/nw/', '{"socket": "AM5", "chipset": "A620", "form_factor": "mATX", "memory_type": "DDR5", "memory_slots": 2}'),
-- ('MSI B760M BOMBER DDR4', 2, 3900.00, 30, 'https://storage-asset.msi.com/global/picture/image/feature/mb/B760/BOMBER/b760m-bomber-ddr4-hero.png', '{"socket": "LGA1700", "chipset": "B760", "form_factor": "mATX", "memory_type": "DDR4", "memory_slots": 2}');

-- RAM
-- INSERT INTO products (name, category_id, price, stock, image_url, specifications) VALUES
-- ('Corsair Vengeance DDR5 32GB 6000MHz', 3, 5200.00, 20, 'https://assets.corsair.com/image/upload/f_auto,q_auto/v1/products/Memory/CMK32GX5M2B6000C30/Vengeance_DDR5_Black_01.png', '{"memory_type": "DDR5", "capacity": "32GB", "speed": "6000MHz", "modules": 2}'),
-- ('Kingston Fury Beast DDR4 16GB 3200MHz', 3, 1900.00, 40, 'https://media.kingston.com/kingston/product/kf432c16bbk2_16-1-sm.png', '{"memory_type": "DDR4", "capacity": "16GB", "speed": "3200MHz", "modules": 2}'),
-- ('G.Skill Trident Z5 RGB 32GB DDR5 6400', 3, 5900.00, 15, 'https://www.gskill.com/_upload/images/163351221111.png', '{"memory_type": "DDR5", "capacity": "32GB", "speed": "6400MHz", "modules": 2}'),
-- ('TeamGroup T-Force Delta RGB DDR4 16GB', 3, 2200.00, 25, 'https://www.teamgroupinc.com/en/upload/product/20210323091151_image.png', '{"memory_type": "DDR4", "capacity": "16GB", "speed": "3600MHz", "modules": 2}'),
-- ('Corsair Dominator Titanium 64GB DDR5', 3, 12000.00, 5, 'https://assets.corsair.com/image/upload/f_auto,q_auto/v1/products/Memory/dominator-titanium-ddr5-black.png', '{"memory_type": "DDR5", "capacity": "64GB", "speed": "6600MHz", "modules": 2}');

-- GPU
-- INSERT INTO products (name, category_id, price, stock, image_url, specifications) VALUES
-- ('NVIDIA GeForce RTX 4070 Founders Edition', 4, 23000.00, 10, 'https://assets.nvidia.university/images/en-us/geforce/rtx-4070-product-photo.png', '{"chipset": "NVIDIA", "vram": "12GB", "length_mm": 244, "tdp": 200}'),
-- ('ASUS ROG Strix RTX 4090 OC', 4, 75000.00, 3, 'https://dlcdnwebbots.asus.com/gain/rtx-4090-strix-oc.png', '{"chipset": "NVIDIA", "vram": "24GB", "length_mm": 357, "tdp": 450}'),
-- ('Sapphire Pulse Radeon RX 7800 XT', 4, 19500.00, 15, 'https://www.sapphiretech.com/-/media/websites/sapphirenext/product-images/pulse-rx-7800-xt-1.png', '{"chipset": "AMD", "vram": "16GB", "length_mm": 280, "tdp": 263}'),
-- ('MSI Ventus 2X RTX 4060 Ti', 4, 14500.00, 20, 'https://storage-asset.msi.com/global/picture/image/feature/vga/NVIDIA/RTX-4060-Ti/RTX-4060-Ti-VENTUS-2X-BLACK-8G-OC-hero.png', '{"chipset": "NVIDIA", "vram": "8GB", "length_mm": 199, "tdp": 160}'),
-- ('Gigabyte RX 7600 Gaming OC', 4, 9900.00, 18, 'https://static.gigabyte.com/StaticFile/Image/Global/Product/32571/png/1000', '{"chipset": "AMD", "vram": "8GB", "length_mm": 282, "tdp": 165}');

-- PSU
-- INSERT INTO products (name, category_id, price, stock, image_url, specifications) VALUES
-- ('Corsair RM850e 850W Gold', 5, 4200.00, 15, 'https://assets.corsair.com/image/upload/f_auto,q_auto/v1/products/PSU/RM850e/RM850e_01.png', '{"wattage": 850, "efficiency": "80+ Gold", "modular": "Full"}'),
-- ('Thermaltake Smart BM3 750W', 5, 2900.00, 25, 'https://www.thermaltake.com/pub/media/catalog/product/s/m/smart_bm3_750w_01.png', '{"wattage": 750, "efficiency": "80+ Bronze", "modular": "Semi"}'),
-- ('Cooler Master MWE Gold 1050 V2', 5, 5900.00, 10, 'https://www.coolermaster.com/catalog/product/MWE-Gold-1050-V2-hero.png', '{"wattage": 1050, "efficiency": "80+ Gold", "modular": "Full"}'),
-- ('Seasonic Focus GX-650', 5, 3500.00, 20, 'https://seasonic.com/pub/media/catalog/product/f/o/focus-gx-black.png', '{"wattage": 650, "efficiency": "80+ Gold", "modular": "Full"}'),
-- ('SilverStone ST50F-ES230 500W', 5, 1290.00, 50, 'https://www.silverstonetek.com/images/products/st50f-es230/st50f-es230-front-01.png', '{"wattage": 500, "efficiency": "80+ White", "modular": "No"}');

-- Case
-- INSERT INTO products (name, category_id, price, stock, image_url, specifications) VALUES
-- ('NZXT H5 Flow Black', 6, 3200.00, 15, 'https://assets.nzxt.com/images/H5_Flow_Black_Hero.png', '{"form_factor": ["ATX", "mATX", "ITX"], "max_gpu_length": 365, "max_cpu_height": 165}'),
-- ('Lian Li O11 Dynamic EVO', 6, 5900.00, 8, 'https://lian-li.com/wp-content/uploads/2021/12/o11d-evo-black-01.png', '{"form_factor": ["E-ATX", "ATX", "mATX", "ITX"], "max_gpu_length": 422, "max_cpu_height": 167}'),
-- ('Cooler Master MasterBox Q300L', 6, 1390.00, 30, 'https://www.coolermaster.com/catalog/product/MasterBox-Q300L-hero.png', '{"form_factor": ["mATX", "ITX"], "max_gpu_length": 360, "max_cpu_height": 159}'),
-- ('Corsair 4000D Airflow', 6, 3500.00, 20, 'https://assets.corsair.com/image/upload/f_auto,q_auto/v1/products/Cases/4000D_Airflow/4000D_Airflow_Black_01.png', '{"form_factor": ["ATX", "mATX", "ITX"], "max_gpu_length": 360, "max_cpu_height": 170}'),
-- ('Fractal Design North Charcoal TG', 6, 5500.00, 5, 'https://www.fractal-design.com/wp-content/uploads/North-Charcoal-TG-Front.png', '{"form_factor": ["ATX", "mATX", "ITX"], "max_gpu_length": 355, "max_cpu_height": 170}');

-- Monitor
-- INSERT INTO products (name, category_id, price, stock, image_url, specifications) VALUES
-- ('LG UltraGear 27GP850-B', 7, 12900.00, 10, 'https://www.lg.com/th/images/monitors/md07525330/gallery/D-01.jpg', '{"size": "27\"", "resolution": "2K QHD", "refresh_rate": "165Hz", "panel": "Nano IPS"}'),
-- ('Samsung Odyssey G5 32\"', 7, 9900.00, 12, 'https://images.samsung.com/is/image/samsung/p6pim/th/lc32g55tqbexxt/gallery/th-odyssey-g5-g55t-lc32g55tqbexxt-534720194?$650_519_PNG$', '{"size": "32\"", "resolution": "2K QHD", "refresh_rate": "144Hz", "panel": "VA"}'),
-- ('ASUS TUF Gaming VG249Q3A', 7, 5500.00, 20, 'https://dlcdnwebbots.asus.com/gain/TUFGamingVG249Q3A-hero.png', '{"size": "23.8\"", "resolution": "Full HD", "refresh_rate": "180Hz", "panel": "IPS"}'),
-- ('Dell UltraSharp U2723QE', 7, 21900.00, 8, 'https://snpi.dell.com/is/image/DellPhotos/U2723QE_Primary?fmt=png-alpha', '{"size": "27\"", "resolution": "4K UHD", "refresh_rate": "60Hz", "panel": "IPS Black"}'),
-- ('AOC 24G2SP/67', 7, 4900.00, 25, 'https://aoc.com/images/products/24G2SP_67_1.png', '{"size": "23.8\"", "resolution": "Full HD", "refresh_rate": "165Hz", "panel": "IPS"}');

-- SSD
-- INSERT INTO products (name, category_id, price, stock, image_url, specifications) VALUES
-- ('Samsung 990 PRO 2TB', 8, 6900.00, 15, 'https://images.samsung.com/is/image/samsung/p6pim/th/mz-v9p2t0bw/gallery/th-990pro-m2-nvme-ssd-mz-v9p2t0bw-534720194?$650_519_PNG$', '{"capacity": "2TB", "interface": "PCIe 4.0", "read_speed": "7450MB/s"}'),
-- ('WD Black SN850X 1TB', 8, 3900.00, 20, 'https://www.westerndigital.com/content/dam/wdc/website/admin/products/internal-ssd/wd-black-sn850x-nvme-ssd/gallery/1tb/wd-black-sn850x-nvme-ssd-1tb-front.png', '{"capacity": "1TB", "interface": "PCIe 4.0", "read_speed": "7300MB/s"}'),
-- ('Crucial P3 Plus 1TB', 8, 2500.00, 30, 'https://content.crucial.com/content/dam/crucial/ssd-products/p3-plus/images/product-render/crucial-p3-plus-ssd-product-render.png', '{"capacity": "1TB", "interface": "PCIe 4.0", "read_speed": "5000MB/s"}'),
-- ('Kingston NV2 500GB', 8, 1290.00, 50, 'https://media.kingston.com/kingston/product/nv2-ssd-front-sm.png', '{"capacity": "500GB", "interface": "PCIe 4.0", "read_speed": "3500MB/s"}'),
-- ('HIKSEMI FUTURE 2TB', 8, 4500.00, 15, 'https://www.hiksemi.tech/content/dam/hiksemi/products/ssd/future/future-2tb.png', '{"capacity": "2TB", "interface": "PCIe 4.0", "read_speed": "7450MB/s"}');

-- Coolers
-- INSERT INTO products (name, category_id, price, stock, image_url, specifications) VALUES
-- ('DeepCool AK400 Digital', 9, 1290.00, 20, 'https://www.deepcool.com/download/AK400_Digital_Hero.png', '{"sockets": ["LGA1700", "AM4", "AM5"], "height_mm": 155, "tdp_rating": 220}'),
-- ('Noctua NH-D15 chromax.black', 9, 4100.00, 5, 'https://noctua.at/pub/media/catalog/product/n/h/nh_d15_chromax_black_1.jpg', '{"sockets": ["LGA1700", "AM4", "AM5", "LGA1200"], "height_mm": 165, "tdp_rating": 250}'),
-- ('NZXT Kraken Elite 360 RGB', 9, 11900.00, 10, 'https://assets.nzxt.com/images/Kraken_Elite_360_RGB_Black_Hero.png', '{"sockets": ["LGA1700", "AM4", "AM5"], "height_mm": 59, "tdp_rating": 300, "type": "Liquid (AIO)"}'),
-- ('Corsair iCUE H150i ELITE CAPELLIX XT', 9, 7900.00, 12, 'https://assets.corsair.com/image/upload/f_auto,q_auto/v1/products/Cooling/h150i-elite-capellix-xt.png', '{"sockets": ["LGA1700", "AM4", "AM5"], "height_mm": 52, "tdp_rating": 350, "type": "Liquid (AIO)"}'),
-- ('Cooler Master Hyper 212 Halo Black', 9, 1190.00, 30, 'https://www.coolermaster.com/catalog/product/Hyper-212-Halo-Black-hero.png', '{"sockets": ["LGA1700", "AM4", "AM5"], "height_mm": 154, "tdp_rating": 180}');

-- Insert Sample Suppliers
INSERT IGNORE INTO suppliers (id, name) VALUES (1, 'Synnex'), (2, 'Ingram Micro'), (3, 'Ascenti Resources');

-- Insert Sample Inventory (S/N) for the first few products in each category
-- This ensures they show up as available
INSERT IGNORE INTO inventory (product_id, serial_number, supplier_id, status) VALUES 
(1, 'SN-CPU-001', 1, 'available'), (1, 'SN-CPU-002', 1, 'available'),
(2, 'SN-CPU-003', 1, 'available'), (2, 'SN-CPU-004', 1, 'available'),
(6, 'SN-MB-001', 2, 'available'), (6, 'SN-MB-002', 2, 'available'),
(11, 'SN-RAM-001', 2, 'available'), (11, 'SN-RAM-002', 2, 'available'),
(16, 'SN-GPU-001', 3, 'available'), (16, 'SN-GPU-002', 3, 'available'),
(21, 'SN-PSU-001', 1, 'available'), (21, 'SN-PSU-002', 1, 'available'),
(26, 'SN-CASE-001', 3, 'available'), (26, 'SN-CASE-002', 3, 'available'),
(31, 'SN-MON-001', 2, 'available'), (31, 'SN-MON-002', 2, 'available'),
(36, 'SN-SSD-001', 1, 'available'), (36, 'SN-SSD-002', 1, 'available'),
(41, 'SN-COOL-001', 3, 'available'), (41, 'SN-COOL-002', 3, 'available');
