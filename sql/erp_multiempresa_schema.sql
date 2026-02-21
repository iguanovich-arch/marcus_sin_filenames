CREATE DATABASE IF NOT EXISTS erp_multiempresa CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE erp_multiempresa;

CREATE TABLE plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(20) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    monthly_price DECIMAL(10,2) NOT NULL,
    api_quota_monthly INT NOT NULL DEFAULT 10000,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    nif VARCHAR(20),
    plan_id INT NULL,
    status ENUM('active','blocked','cancelled') NOT NULL DEFAULT 'active',
    verifactu_enabled TINYINT(1) NOT NULL DEFAULT 0,
    api_quota_monthly INT NOT NULL DEFAULT 10000,
    api_calls_current_month INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (plan_id) REFERENCES plans(id)
);

CREATE TABLE modules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(60) NOT NULL UNIQUE,
    name VARCHAR(120) NOT NULL
);

CREATE TABLE plan_modules (
    plan_id INT NOT NULL,
    module_id INT NOT NULL,
    enabled TINYINT(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (plan_id, module_id),
    FOREIGN KEY (plan_id) REFERENCES plans(id),
    FOREIGN KEY (module_id) REFERENCES modules(id)
);

CREATE TABLE client_modules (
    client_id INT NOT NULL,
    module_id INT NOT NULL,
    enabled TINYINT(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (client_id, module_id),
    FOREIGN KEY (client_id) REFERENCES clients(id),
    FOREIGN KEY (module_id) REFERENCES modules(id)
);

CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    FOREIGN KEY (client_id) REFERENCES clients(id)
);

CREATE TABLE role_permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_id INT NOT NULL,
    resource VARCHAR(100) NOT NULL,
    action ENUM('view','create','edit','delete','send') NOT NULL,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    role_id INT NOT NULL,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id),
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

CREATE TABLE subscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    plan_id INT NOT NULL,
    status ENUM('active','pending_payment','overdue','cancelled') NOT NULL,
    next_billing_date DATE NULL,
    FOREIGN KEY (client_id) REFERENCES clients(id),
    FOREIGN KEY (plan_id) REFERENCES plans(id)
);

CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    provider ENUM('caixabank','paypal','stripe') NOT NULL DEFAULT 'caixabank',
    amount DECIMAL(10,2) NOT NULL,
    currency CHAR(3) NOT NULL DEFAULT 'EUR',
    status ENUM('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending',
    provider_reference VARCHAR(100),
    paid_at DATETIME NULL,
    FOREIGN KEY (client_id) REFERENCES clients(id)
);

CREATE TABLE invoices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    payment_id INT NULL,
    invoice_number VARCHAR(50) NOT NULL,
    invoice_date DATE NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    verifactu_status ENUM('not_sent','sent','accepted','rejected') NOT NULL DEFAULT 'not_sent',
    FOREIGN KEY (client_id) REFERENCES clients(id),
    FOREIGN KEY (payment_id) REFERENCES payments(id)
);

CREATE TABLE client_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    setting_key VARCHAR(120) NOT NULL,
    setting_value VARCHAR(255) NOT NULL,
    UNIQUE KEY uniq_client_setting (client_id, setting_key),
    FOREIGN KEY (client_id) REFERENCES clients(id)
);

-- Tablas ERP de negocio (todas con client_id)
CREATE TABLE catalog_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    item_type ENUM('product','service','expense') NOT NULL,
    name VARCHAR(150) NOT NULL,
    sku VARCHAR(80),
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (client_id) REFERENCES clients(id)
);

CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    legal_name VARCHAR(150) NOT NULL,
    nif VARCHAR(20),
    email VARCHAR(190),
    FOREIGN KEY (client_id) REFERENCES clients(id)
);

CREATE TABLE suppliers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    legal_name VARCHAR(150) NOT NULL,
    nif VARCHAR(20),
    email VARCHAR(190),
    FOREIGN KEY (client_id) REFERENCES clients(id)
);

INSERT INTO plans (code, name, monthly_price, api_quota_monthly) VALUES
('basic','Plan Basic',49.00,10000),
('medium','Plan Medium',99.00,50000),
('plus','Plan Plus',149.00,150000);

INSERT INTO modules (code, name) VALUES
('catalogo','Catálogo'),('clientes','Clientes'),('proveedores','Proveedores'),('presupuestos','Presupuestos'),('facturacion','Facturación'),('compras','Compras'),('agenda','Agenda'),('informes','Informes'),('configuracion','Configuración'),('api','API'),('connect','Connect'),('inbox','Inbox OCR'),('contabilidad','Contabilidad'),('proyectos','Proyectos'),('multialmacen','Multialmacén'),('gps','GPS');
