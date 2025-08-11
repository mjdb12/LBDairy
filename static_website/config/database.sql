-- LBDAIRY Database Schema
-- Livestock Diary Management System

CREATE DATABASE IF NOT EXISTS lbdiary;
USE lbdiary;

-- Users table for authentication and user management
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    user_type ENUM('farmer', 'admin', 'superadmin') NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    contact_number VARCHAR(20),
    address TEXT,
    cooperative VARCHAR(100),
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Livestock table for animal records
CREATE TABLE livestock (
    id INT AUTO_INCREMENT PRIMARY KEY,
    livestock_id VARCHAR(20) UNIQUE NOT NULL,
    owned_by INT NOT NULL,
    dispersal_from VARCHAR(100),
    registry_id VARCHAR(50),
    tag_id VARCHAR(50),
    livestock_name VARCHAR(100) NOT NULL,
    date_of_birth DATE,
    sex ENUM('Male', 'Female') NOT NULL,
    breed VARCHAR(50) NOT NULL,
    sire_id VARCHAR(50),
    dam_id VARCHAR(50),
    sire_name VARCHAR(100),
    dam_name VARCHAR(100),
    sire_breed VARCHAR(50),
    dam_breed VARCHAR(50),
    natural_marks TEXT,
    property_no VARCHAR(50),
    acquisition_date DATE,
    acquisition_cost DECIMAL(10,2),
    source VARCHAR(100),
    remarks TEXT,
    cooperator VARCHAR(100),
    released_date DATE,
    cooperative VARCHAR(100),
    address TEXT,
    contact_no VARCHAR(20),
    in_charge VARCHAR(100),
    status ENUM('active', 'sold', 'deceased', 'transferred') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (owned_by) REFERENCES users(id) ON DELETE CASCADE
);

-- Growth records table
CREATE TABLE growth_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    livestock_id INT NOT NULL,
    record_date DATE NOT NULL,
    weight DECIMAL(6,2),
    height DECIMAL(6,2),
    heart_girth DECIMAL(6,2),
    body_length DECIMAL(6,2),
    remarks TEXT,
    recorded_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (livestock_id) REFERENCES livestock(id) ON DELETE CASCADE,
    FOREIGN KEY (recorded_by) REFERENCES users(id)
);

-- Milk production records
CREATE TABLE milk_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    livestock_id INT NOT NULL,
    calving_date DATE,
    calf_id VARCHAR(50),
    milk_production DECIMAL(6,2),
    days_in_milk INT,
    remarks TEXT,
    recorded_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (livestock_id) REFERENCES livestock(id) ON DELETE CASCADE,
    FOREIGN KEY (recorded_by) REFERENCES users(id)
);

-- Breeding records
CREATE TABLE breeding_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    livestock_id INT NOT NULL,
    breeding_date DATE NOT NULL,
    breeding_type ENUM('Natural', 'Artificial Insemination') NOT NULL,
    sire_id VARCHAR(50),
    dam_id VARCHAR(50),
    pregnancy_result ENUM('Positive', 'Negative', 'Unknown') DEFAULT 'Unknown',
    remarks TEXT,
    recorded_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (livestock_id) REFERENCES livestock(id) ON DELETE CASCADE,
    FOREIGN KEY (recorded_by) REFERENCES users(id)
);

-- Health records
CREATE TABLE health_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    livestock_id INT NOT NULL,
    record_date DATE NOT NULL,
    health_status ENUM('Good', 'Fair', 'Poor', 'Critical') NOT NULL,
    treatment TEXT,
    remarks TEXT,
    recorded_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (livestock_id) REFERENCES livestock(id) ON DELETE CASCADE,
    FOREIGN KEY (recorded_by) REFERENCES users(id)
);

-- Production records (general)
CREATE TABLE production_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    livestock_id INT NOT NULL,
    production_date DATE NOT NULL,
    production_type ENUM('milk', 'eggs', 'meat', 'other') NOT NULL,
    quantity DECIMAL(8,2) NOT NULL,
    unit VARCHAR(20) NOT NULL,
    quality_grade ENUM('A', 'B', 'C', 'D') DEFAULT 'B',
    remarks TEXT,
    recorded_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (livestock_id) REFERENCES livestock(id) ON DELETE CASCADE,
    FOREIGN KEY (recorded_by) REFERENCES users(id)
);

-- Sales records
CREATE TABLE sales_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    livestock_id INT,
    product_type ENUM('livestock', 'milk', 'eggs', 'meat', 'other') NOT NULL,
    sale_date DATE NOT NULL,
    quantity DECIMAL(8,2) NOT NULL,
    unit VARCHAR(20) NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    total_amount DECIMAL(12,2) NOT NULL,
    buyer_name VARCHAR(100),
    buyer_contact VARCHAR(20),
    payment_method ENUM('cash', 'check', 'bank_transfer', 'credit') DEFAULT 'cash',
    payment_status ENUM('paid', 'pending', 'partial') DEFAULT 'paid',
    remarks TEXT,
    recorded_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (livestock_id) REFERENCES livestock(id) ON DELETE SET NULL,
    FOREIGN KEY (recorded_by) REFERENCES users(id)
);

-- Expenses records
CREATE TABLE expenses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    expense_date DATE NOT NULL,
    expense_type ENUM('feed', 'medicine', 'vaccination', 'equipment', 'labor', 'transport', 'other') NOT NULL,
    description TEXT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    supplier VARCHAR(100),
    receipt_no VARCHAR(50),
    payment_method ENUM('cash', 'check', 'bank_transfer', 'credit') DEFAULT 'cash',
    recorded_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (recorded_by) REFERENCES users(id)
);

-- Suppliers table
CREATE TABLE suppliers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    supplier_name VARCHAR(100) NOT NULL,
    contact_person VARCHAR(100),
    contact_number VARCHAR(20),
    email VARCHAR(100),
    address TEXT,
    supplier_type ENUM('feed', 'medicine', 'equipment', 'livestock', 'other') NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Issues/Problems tracking
CREATE TABLE issues (
    id INT AUTO_INCREMENT PRIMARY KEY,
    livestock_id INT,
    issue_date DATE NOT NULL,
    issue_type ENUM('health', 'behavior', 'production', 'equipment', 'other') NOT NULL,
    topic VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    severity ENUM('low', 'medium', 'high', 'critical') DEFAULT 'medium',
    status ENUM('open', 'in_progress', 'resolved', 'closed') DEFAULT 'open',
    resolution TEXT,
    resolved_date DATE,
    issued_by INT NOT NULL,
    resolved_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (livestock_id) REFERENCES livestock(id) ON DELETE SET NULL,
    FOREIGN KEY (issued_by) REFERENCES users(id),
    FOREIGN KEY (resolved_by) REFERENCES users(id)
);

-- Audit logs for system tracking
CREATE TABLE audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(100) NOT NULL,
    table_name VARCHAR(50),
    record_id INT,
    old_values JSON,
    new_values JSON,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Schedule/Calendar events
CREATE TABLE schedule_events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    event_date DATE NOT NULL,
    event_time TIME,
    event_type ENUM('vaccination', 'breeding', 'checkup', 'feeding', 'other') NOT NULL,
    livestock_id INT,
    user_id INT NOT NULL,
    status ENUM('scheduled', 'completed', 'cancelled') DEFAULT 'scheduled',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (livestock_id) REFERENCES livestock(id) ON DELETE SET NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Insert default super admin user
INSERT INTO users (username, password, email, user_type, first_name, last_name, status) 
VALUES ('superadmin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'lbdairysuperadmin@gmail.com', 'superadmin', 'Super', 'Admin', 'active');

-- Insert sample data for testing
INSERT INTO users (username, password, email, user_type, first_name, last_name, contact_number, address, cooperative, status) VALUES
('farmer1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'farmer1@example.com', 'farmer', 'Juan', 'Dela Cruz', '09123456789', 'Brgy. Kulapi, Lucban', 'Lucban Goat Farmers', 'active'),
('farmer2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'farmer2@example.com', 'farmer', 'Maria', 'Reyes', '09234567890', 'Brgy. Aliliw, Lucban', 'Lucban Cattle Farmers', 'active'),
('admin1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin1@example.com', 'admin', 'Admin', 'User', '09345678901', 'Lucban Municipal Hall', 'Lucban Farmers Cooperative', 'active');

-- Insert sample livestock data
INSERT INTO livestock (livestock_id, owned_by, dispersal_from, registry_id, tag_id, livestock_name, date_of_birth, sex, breed, sire_id, dam_id, sire_name, dam_name, sire_breed, dam_breed, natural_marks, property_no, acquisition_date, acquisition_cost, source, remarks, cooperator, released_date, cooperative, address, contact_no, in_charge) VALUES
('LS001', 2, 'LGU Lucban', 'REG123', 'TAG001', 'Boer Goat Alpha', '2021-03-10', 'Male', 'Boer', 'SR001', 'DR001', 'Billy', 'Nanny', 'Boer', 'Boer', 'White patch on right ear', 'PROP-001', '2022-01-15', 12000.00, 'DA Dispersal', 'Healthy and active', 'Maria Clara', '2022-02-01', 'Lucban Goat Farmers', 'Brgy. Kulapi, Lucban', '09123456789', 'Mr. Veterinarian'),
('LS002', 3, 'DA Office', 'REG124', 'TAG002', 'Angus Cow Beta', '2020-05-15', 'Female', 'Angus', 'SR002', 'DR002', 'Bull', 'Daisy', 'Angus', 'Angus', 'Black coat with white star on forehead', 'PROP-002', '2021-06-20', 45000.00, 'Private Purchase', 'Excellent milk producer', 'Pedro Santos', '2021-07-01', 'Lucban Cattle Farmers', 'Brgy. Aliliw, Lucban', '09234567890', 'Dr. Animal Health');

-- Insert sample suppliers
INSERT INTO suppliers (supplier_name, contact_person, contact_number, email, address, supplier_type) VALUES
('ABC Feed Supply', 'John Smith', '09123456789', 'abc@example.com', 'Lucban, Quezon', 'feed'),
('XYZ Veterinary Clinic', 'Dr. Maria Santos', '09234567890', 'xyz@example.com', 'Lucban, Quezon', 'medicine'),
('Farm Equipment Co.', 'Robert Johnson', '09345678901', 'farm@example.com', 'Lucban, Quezon', 'equipment'); 