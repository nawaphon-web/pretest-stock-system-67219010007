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
('Intel Core i5-13600K', 1, 11900.00, 10, 'https://example.com/i5-13600k.jpg', '{"socket": "LGA1700", "tdp": 125, "cores": 14, "threads": 20, "base_clock": "3.5GHz", "boost_clock": "5.1GHz"}'),
('Intel Core i9-14900K', 1, 24900.00, 5, 'https://example.com/i9-14900k.jpg', '{"socket": "LGA1700", "tdp": 125, "cores": 24, "threads": 32, "base_clock": "3.2GHz", "boost_clock": "6.0GHz"}'),
('AMD Ryzen 7 7800X3D', 1, 14900.00, 8, 'https://example.com/7800x3d.jpg', '{"socket": "AM5", "tdp": 120, "cores": 8, "threads": 16, "base_clock": "4.2GHz", "boost_clock": "5.0GHz"}');

-- Mainboards
INSERT INTO products (name, category_id, price, stock, image_url, specifications) VALUES
('MSI MAG Z790 TOMAHAWK WIFI', 2, 9500.00, 10, 'https://example.com/z790.jpg', '{"socket": "LGA1700", "chipset": "Z790", "form_factor": "ATX", "memory_type": "DDR5", "max_memory": 192, "memory_slots": 4}'),
('ASUS ROG STRIX B760-A GAMING WIFI', 2, 7900.00, 12, 'https://example.com/b760.jpg', '{"socket": "LGA1700", "chipset": "B760", "form_factor": "ATX", "memory_type": "DDR4", "max_memory": 128, "memory_slots": 4}'),
('GIGABYTE B650 AORUS ELITE AX', 2, 7200.00, 15, 'https://example.com/b650.jpg', '{"socket": "AM5", "chipset": "B650", "form_factor": "ATX", "memory_type": "DDR5", "max_memory": 128, "memory_slots": 4}');

-- RAM
INSERT INTO products (name, category_id, price, stock, image_url, specifications) VALUES
('Corsair Vengeance DDR5 32GB (2x16GB) 6000MHz', 3, 5200.00, 20, 'https://example.com/ddr5-32gb.jpg', '{"memory_type": "DDR5", "capacity": "32GB", "speed": "6000MHz", "modules": 2}'),
('Kingston Fury Beast DDR4 16GB (2x8GB) 3200MHz', 3, 1900.00, 30, 'https://example.com/ddr4-16gb.jpg', '{"memory_type": "DDR4", "capacity": "16GB", "speed": "3200MHz", "modules": 2}');

-- GPU
INSERT INTO products (name, category_id, price, stock, image_url, specifications) VALUES
('NVIDIA GeForce RTX 4070', 4, 23000.00, 10, 'https://example.com/rtx4070.jpg', '{"chipset": "NVIDIA", "vram": "12GB", "length_mm": 261, "tdp": 200, "recommended_psu": 600}'),
('NVIDIA GeForce RTX 4090', 4, 65000.00, 3, 'https://example.com/rtx4090.jpg', '{"chipset": "NVIDIA", "vram": "24GB", "length_mm": 336, "tdp": 450, "recommended_psu": 850}');

-- PSU
INSERT INTO products (name, category_id, price, stock, image_url, specifications) VALUES
('Corsair RM850e 850W Gold', 5, 4200.00, 15, 'https://example.com/rm850e.jpg', '{"wattage": 850, "efficiency": "80+ Gold", "modular": "Full"}'),
('Thermaltake TR2 S 650W', 5, 1590.00, 20, 'https://example.com/tr2s-650w.jpg', '{"wattage": 650, "efficiency": "80+ White", "modular": "No"}');

-- Case
INSERT INTO products (name, category_id, price, stock, image_url, specifications) VALUES
('NZXT H5 Flow', 6, 3200.00, 8, 'https://example.com/h5-flow.jpg', '{"form_factor": ["ATX", "mATX", "ITX"], "max_gpu_length": 365, "max_cpu_height": 165}'),
('Lian Li O11 Dynamic', 6, 5500.00, 5, 'https://example.com/o11.jpg', '{"form_factor": ["E-ATX", "ATX", "mATX", "ITX"], "max_gpu_length": 420, "max_cpu_height": 155}');

-- SSD
INSERT INTO products (name, category_id, price, stock, image_url, specifications) VALUES
('Samsung 980 PRO 1TB', 8, 3500.00, 25, 'https://example.com/980pro.jpg', '{"interface": "M.2 NVMe", "capacity": "1TB", "read_speed": "7000MB/s"}');

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

-- Insert Sample Suppliers
INSERT IGNORE INTO suppliers (name) VALUES ('Synnex'), ('Ingram Micro'), ('Ascenti Resources');

-- Insert Sample Inventory (S/N)
INSERT IGNORE INTO inventory (product_id, serial_number, supplier_id) VALUES 
(1, 'SN-CPU-I5-001', 1),
(1, 'SN-CPU-I5-002', 1),
(5, 'SN-GPU-4070-001', 3),
(5, 'SN-GPU-4070-002', 3);

