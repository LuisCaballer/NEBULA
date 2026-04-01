CREATE DATABASE IF NOT EXISTS nebula_gaming CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE nebula_gaming;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    correo VARCHAR(150) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    es_admin TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_producto VARCHAR(150) NOT NULL,
    descripcion TEXT NULL,
    categoria VARCHAR(80) NULL,
    precio_producto DECIMAL(10,2) NOT NULL,
    imagen VARCHAR(255) NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS carrito (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad_producto INT NOT NULL DEFAULT 1,
    comprado TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_carrito_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_carrito_producto FOREIGN KEY (id_producto) REFERENCES productos(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS factura (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_carrito INT NOT NULL,
    id_usuario INT NOT NULL,
    precio_final DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_factura_carrito FOREIGN KEY (id_carrito) REFERENCES carrito(id) ON DELETE RESTRICT,
    CONSTRAINT fk_factura_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
);

INSERT INTO usuarios (nombre, apellido, correo, contrasena, es_admin)
SELECT 'Admin', 'Principal', 'admin@nebula.com', '$2y$10$Tdw7OfynRDWTIaVn2x9xmuAM/0PmrlCu51xq6rcJDbP/9aukRlRmS', 1
WHERE NOT EXISTS (
    SELECT 1 FROM usuarios WHERE correo = 'admin@nebula.com'
);

INSERT INTO productos (nombre_producto, descripcion, categoria, precio_producto, imagen)
SELECT 'PC Gamer Orion X', 'Ryzen 7, 32GB RAM, RTX 4070, SSD 1TB', 'Gaming', 1299.99, 'assets/pc1.jpg'
WHERE NOT EXISTS (SELECT 1 FROM productos WHERE nombre_producto = 'PC Gamer Orion X');

INSERT INTO productos (nombre_producto, descripcion, categoria, precio_producto, imagen)
SELECT 'Workstation Nebula Pro', 'Intel i9, 64GB RAM, RTX A2000, SSD 2TB', 'Workstation', 1899.00, 'assets/pc2.jpg'
WHERE NOT EXISTS (SELECT 1 FROM productos WHERE nombre_producto = 'Workstation Nebula Pro');

INSERT INTO productos (nombre_producto, descripcion, categoria, precio_producto, imagen)
SELECT 'PC Entry Nova', 'Ryzen 5, 16GB RAM, RX 6600, SSD 512GB', 'Entrada', 749.50, 'assets/pc3.jpg'
WHERE NOT EXISTS (SELECT 1 FROM productos WHERE nombre_producto = 'PC Entry Nova');

INSERT INTO productos (nombre_producto, descripcion, categoria, precio_producto, imagen)
SELECT 'PC Esports Pulse', 'Ryzen 7 7800X3D, 32GB RAM, RTX 4070 SUPER, SSD 1TB NVMe', 'Gaming', 1549.99, 'assets/pc-esports.svg'
WHERE NOT EXISTS (SELECT 1 FROM productos WHERE nombre_producto = 'PC Esports Pulse');

INSERT INTO productos (nombre_producto, descripcion, categoria, precio_producto, imagen)
SELECT 'PC Stream Master', 'Intel i7, 32GB RAM, RTX 4060 Ti, capturadora 4K', 'Streaming', 1399.00, 'assets/pc-stream.svg'
WHERE NOT EXISTS (SELECT 1 FROM productos WHERE nombre_producto = 'PC Stream Master');

INSERT INTO productos (nombre_producto, descripcion, categoria, precio_producto, imagen)
SELECT 'PC Creator Forge', 'Ryzen 9, 64GB RAM, RTX 4080, SSD 2TB', 'Creator', 2299.00, 'assets/pc-creator.svg'
WHERE NOT EXISTS (SELECT 1 FROM productos WHERE nombre_producto = 'PC Creator Forge');

INSERT INTO productos (nombre_producto, descripcion, categoria, precio_producto, imagen)
SELECT 'PC Ultra 4K Titan', 'Intel i9, 64GB RAM, RTX 4090, SSD 2TB Gen4', 'Premium', 3199.99, 'assets/pc-ultra4k.svg'
WHERE NOT EXISTS (SELECT 1 FROM productos WHERE nombre_producto = 'PC Ultra 4K Titan');

INSERT INTO productos (nombre_producto, descripcion, categoria, precio_producto, imagen)
SELECT 'PC Compact Mini', 'Ryzen 5, 16GB RAM, RTX 4060, formato Mini-ITX', 'Compacta', 1199.00, 'assets/pc-compact.svg'
WHERE NOT EXISTS (SELECT 1 FROM productos WHERE nombre_producto = 'PC Compact Mini');

INSERT INTO productos (nombre_producto, descripcion, categoria, precio_producto, imagen)
SELECT 'PC Office Pro X', 'Intel i5, 32GB RAM, graficos profesionales, SSD 1TB', 'Workstation', 1299.00, 'assets/pc-officepro.svg'
WHERE NOT EXISTS (SELECT 1 FROM productos WHERE nombre_producto = 'PC Office Pro X');
