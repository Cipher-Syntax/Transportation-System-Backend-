CREATE DATABASE transportation_system;
USE transportation_system;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    user_profile VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE drivers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    contact_no VARCHAR(11) NOT NULL,
    license_number VARCHAR(255), NOT NULL
    driver_notes VARCHAR(999) NOT NULL,
    ratings VARCHAR(255) NOT NULL,
    driver_profile VARCHAR(255) NOT NULL,
    car_seats VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    user_profile VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE rides (
    ride_id INT AUTO_INCREMENT PRIMARY KEY,
    passenger INT NOT NULL,
    driver INT NOT NULL,
    location VARCHAR(255) NOT NULL,
    destination VARCHAR(255) NOT NULL,
    user_contact VARCHAR(11) NOT NULL,
    amount VARCHAR(255) NOT NULL,
    status ENUM('Completed', 'Pending', 'Active', 'Cancelled'),
    user_notified TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME ON UPDATE CURRENT_TIMESTAMP,
    completed_at DATETIME NULL,
    FOREIGN KEY (passenger) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (driver) REFERENCES drivers(id) ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE TABLE settings (
    settings_id INT AUTO_INCREMENT PRIMARY KEY,
    base_fare DECIMAL(10, 2) NOT NULL,
    per_km_rate DECIMAL(10, 2) NOT NULL,
    driver_commission INT NOT NULL,
    driver_quota INT NOT NULL
);