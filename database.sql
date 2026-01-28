-- Create database
CREATE DATABASE IF NOT EXISTS suii_vaccination;
USE suii_vaccination;

-- Users table (for admin, parents, hospitals)
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'parent', 'hospital') NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Children table
CREATE TABLE children (
    id INT PRIMARY KEY AUTO_INCREMENT,
    parent_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    dob DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Hospitals table
CREATE TABLE hospitals (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT UNIQUE NOT NULL,
    name VARCHAR(200) NOT NULL,
    address TEXT NOT NULL,
    phone VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Vaccines table
CREATE TABLE vaccines (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    status ENUM('available', 'unavailable') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Appointments table
CREATE TABLE appointments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    child_id INT NOT NULL,
    hospital_id INT NOT NULL,
    vaccine_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    status ENUM('pending', 'approved', 'rejected', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (child_id) REFERENCES children(id) ON DELETE CASCADE,
    FOREIGN KEY (hospital_id) REFERENCES hospitals(id) ON DELETE CASCADE,
    FOREIGN KEY (vaccine_id) REFERENCES vaccines(id) ON DELETE CASCADE
);

-- Vaccination records table
CREATE TABLE vaccination_records (
    id INT PRIMARY KEY AUTO_INCREMENT,
    child_id INT NOT NULL,
    vaccine_id INT NOT NULL,
    hospital_id INT NOT NULL,
    vaccination_date DATE NOT NULL,
    status ENUM('given', 'missed', 'scheduled') DEFAULT 'scheduled',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (child_id) REFERENCES children(id) ON DELETE CASCADE,
    FOREIGN KEY (vaccine_id) REFERENCES vaccines(id) ON DELETE CASCADE,
    FOREIGN KEY (hospital_id) REFERENCES hospitals(id) ON DELETE CASCADE
);

-- Insert default admin user (password: admin123)
INSERT INTO users (name, email, password, role) VALUES 
('Admin User', 'admin@suii.com', 'admin123', 'admin');

-- Insert sample vaccines
INSERT INTO vaccines (name, status) VALUES 
('BCG', 'available'),
('Hepatitis B', 'available'),
('Polio', 'available'),
('DPT', 'available'),
('MMR', 'available'),
('Varicella', 'available'),
('Hepatitis A', 'available'),
('Typhoid', 'available');