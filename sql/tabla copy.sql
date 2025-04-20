-- Tabla de usuarios para el login
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    nombre_completo VARCHAR(100) NOT NULL,
    rol ENUM('admin', 'editor') NOT NULL DEFAULT 'editor',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultimo_login TIMESTAMP NULL,
    activo BOOLEAN DEFAULT TRUE
);

-- Tabla de productos
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL,
    imagen VARCHAR(255),
    usuario_creador INT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_creador) REFERENCES usuarios(id)
);

-- Tabla de promociones
CREATE TABLE promociones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    producto_id INT NOT NULL,
    descuento DECIMAL(5,2) NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    activa BOOLEAN DEFAULT TRUE,
    usuario_creador INT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (producto_id) REFERENCES productos(id),
    FOREIGN KEY (usuario_creador) REFERENCES usuarios(id)
);

-- Tabla para tokens de recuperación de contraseña
CREATE TABLE password_reset_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    token VARCHAR(64) NOT NULL,
    fecha_expiracion TIMESTAMP NOT NULL,
    usado BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Insertar usuario admin inicial (contraseña: Admin123)
INSERT INTO usuarios (username, email, password_hash, nombre_completo, rol) 
VALUES ('admin', 'admin@tudominio.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador Principal', 'admin');