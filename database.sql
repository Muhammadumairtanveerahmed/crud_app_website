-- Run this once to create the database and table
CREATE DATABASE IF NOT EXISTS crud_app;
USE crud_app;

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Optional: sample data
INSERT INTO products (name, description, price, quantity) VALUES
('Wireless Mouse', 'Ergonomic 2.4GHz wireless mouse', 12.99, 50),
('Mechanical Keyboard', 'RGB backlit mechanical keyboard', 45.50, 30),
('USB-C Hub', '7-in-1 USB-C hub with HDMI', 22.00, 75);
