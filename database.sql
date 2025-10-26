-- SQL: create database and table
CREATE DATABASE IF NOT EXISTS demo_crud CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE demo_crud;

CREATE TABLE IF NOT EXISTS contacts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  phone VARCHAR(30),
  city VARCHAR(100),
  status ENUM('Active','Inactive') DEFAULT 'Active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
