-- Создание базы данных
CREATE DATABASE IF NOT EXISTS notebook;
USE notebook;

-- Создание таблицы контактов
CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lastname VARCHAR(100) NOT NULL,
    firstname VARCHAR(100) NOT NULL,
    middlename VARCHAR(100),
    gender ENUM('М', 'Ж'),
    birthdate DATE,
    phone VARCHAR(50),
    address TEXT,
    email VARCHAR(100),
    comment TEXT
);