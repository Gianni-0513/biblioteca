-- file: database.sql
CREATE DATABASE IF NOT EXISTS biblioteca;
USE biblioteca;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user','admin') DEFAULT 'user'
);

-- Books table
CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    available BOOLEAN DEFAULT TRUE
);

-- Loans table
CREATE TABLE IF NOT EXISTS loans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    book_id INT NOT NULL,
    request_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    return_date DATETIME NULL,
    status ENUM('pending','approved','returned') DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (book_id) REFERENCES books(id)
);

/**
-- Sample data for testing
-- Admin user (password: adminpass)
INSERT INTO users (email, password, role) VALUES (
    'admin@biblioteca.test',
    '$2y$10$KbQiJfYzQeQHhVbS7q1hCu1oN9YxIxey3HkpFvHhIlzYfhE0zRL.u', -- hashed 'adminpass'
    'admin'
);

-- Normal user (password: userpass)
INSERT INTO users (email, password, role) VALUES (
    'user@biblioteca.test',
    '$2y$10$7GzLx0c1U5X1QeN/lLWuhe5P3V5pQxuYNsGQmTUkMnC3E6kXKPp7G', -- hashed 'userpass'
    'user'
);
**/

-- Sample books
INSERT INTO books (title, author, available) VALUES 
    ('Il Nome della Rosa', 'Umberto Eco', TRUE),
    ('La Divina Commedia', 'Dante Alighieri', TRUE),
    ("Cent'anni di solitudine", 'Gabriel García Márquez', TRUE),
    ('1984', 'George Orwell', TRUE);
