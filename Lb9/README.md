## 1. Проверить, что MariaDB запущен

```bash
sudo systemctl status mariadb
```
## Если не запущен:
```bash
sudo systemctl start mariadb
```
## 2. Подготовить базу данных (делается 1 раз)
```bash
# Зайти в MariaDB
sudo mysql -u root

# Выполнить SQL (скопируй и вставь целиком)
CREATE DATABASE IF NOT EXISTS notebook;
USE notebook;

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

# Выйти
EXIT;
```
## 3. Создать пользователя для PHP (делается 1 раз)
```bash
sudo mysql -u root




CREATE USER 'php_user'@'localhost' IDENTIFIED BY '123';
GRANT ALL PRIVILEGES ON notebook.* TO 'php_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```
## 4. Запустить PHP-сервер (каждый раз при работе с сайтом)
```bash
php -S localhost:8080
```




