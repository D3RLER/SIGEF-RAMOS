CREATE DATABASE IF NOT EXISTS sigef_ramos;
USE sigef_ramos;

CREATE TABLE sedes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    direccion VARCHAR(255),
    telefono VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('Gerente', 'Vendedor', 'Operario') NOT NULL,
    sede_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sede_id) REFERENCES sedes(id) ON DELETE SET NULL
);

-- Inserciones de prueba para sedes
INSERT INTO sedes (nombre, direccion) VALUES 
('Ilo', 'Calle Principal 123, Ilo'),
('Moquegua', 'Av. Central 456, Moquegua');

-- Inserciones de usuarios de prueba (password: '123456' en hash BCRYPT)
-- Inserciones de usuarios de prueba (password: '123456' en hash BCRYPT, y 'admin' con 'admin123')
INSERT INTO usuarios (nombre, username, password, rol, sede_id) VALUES 
('Administrador Principal', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Gerente', NULL), 
('Carlos Gerente', 'gerente1', '$2y$10$wNpw.rCjOhA3V7z9HToXjOovkG.0L6I7j/hB7vXm9tK74lU62Htxm', 'Gerente', 1),
('Ana Vendedora', 'vendedor1', '$2y$10$wNpw.rCjOhA3V7z9HToXjOovkG.0L6I7j/hB7vXm9tK74lU62Htxm', 'Vendedor', 1),
('Luis Operario', 'operario1', '$2y$10$wNpw.rCjOhA3V7z9HToXjOovkG.0L6I7j/hB7vXm9tK74lU62Htxm', 'Operario', 2);


CREATE TABLE deudos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dni VARCHAR(20) NOT NULL UNIQUE,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    direccion VARCHAR(255),
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE difuntos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dni VARCHAR(20),
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    fecha_nacimiento DATE,
    fecha_fallecimiento DATE NOT NULL,
    causa_muerte VARCHAR(255),
    deudo_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (deudo_id) REFERENCES deudos(id) ON DELETE CASCADE
);

CREATE TABLE servicios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    difunto_id INT NOT NULL,
    sede_id INT NOT NULL,
    tipo_servicio VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10, 2) NOT NULL,
    fecha_servicio DATETIME NOT NULL,
    fecha_finalizacion DATETIME NULL,
    estado ENUM('pendiente', 'en_proceso', 'finalizado', 'cancelado') DEFAULT 'pendiente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (difunto_id) REFERENCES difuntos(id) ON DELETE CASCADE,
    FOREIGN KEY (sede_id) REFERENCES sedes(id) ON DELETE RESTRICT
);

CREATE TABLE inventario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sede_id INT NOT NULL,
    producto VARCHAR(100) NOT NULL,
    categoria ENUM('Ataudes', 'Traslados', 'Salas de Velacion', 'Arreglos Florales', 'Recordatorios Funebres', 'Gestion de Tramites') NOT NULL,
    imagen VARCHAR(255) NULL,
    stock INT NOT NULL DEFAULT 0,
    stock_minimo INT NOT NULL DEFAULT 5,
    precio_compra DECIMAL(10, 2),
    precio_venta DECIMAL(10, 2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sede_id) REFERENCES sedes(id) ON DELETE CASCADE
);

CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    p_nombre VARCHAR(50) NOT NULL,
    s_nombre VARCHAR(50),
    a_paterno VARCHAR(50) NOT NULL,
    a_materno VARCHAR(50) NOT NULL,
    dni VARCHAR(20) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    direccion VARCHAR(255),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE proformas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    sede_id INT,
    tipo_ataud VARCHAR(50),
    movilidad ENUM('Si', 'No'),
    capilla ENUM('Si', 'No'),
    cremacion ENUM('Si', 'No'),
    total DECIMAL(10,2),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
    FOREIGN KEY (sede_id) REFERENCES sedes(id) ON DELETE SET NULL
);
