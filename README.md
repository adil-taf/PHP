# PHP-mvc
**PHP-mvc** is a PHP application that serves as a codebase for various essential concepts used in frameworks, including Slim, Laravel, and Symfony. This application is a showcase of my skills.

## Key Concepts Covered

1. MVC Architecture
2. Routing
3. PSR-4 Autoloader
4. Database Connectivity and Querying
5. Transaction Handling
6. Environment Variables and Configuration (Using PHP dotenv to manage environment-specific settings)
7. Unit Testing
8. Dependency Injection and PSR-11 Container
9. Enumerations
10. Attributes
11. Scheduled Email Sending with Symfony Mailer
12. Database Abstraction Layers
13. Template Engines
14. Step Debugging with Xdebug 3

## Repository Branches
The **PHP-mvc** repository comprises four branches, each exemplifying distinct approaches. Initially, these branches share identical commits, but as development progresses, they diverge:
1. `main` branch: Utilizes PDO with cURL/Guzzle integration for email validation APIs, DTO and xdebug 3
2. `dbal` branch: Implements Doctrine DBAL
3. `orm-dataMapper` branch: Leverages Doctrine Object-Document Mapper (OTM),  Doctrine migrations and Twig templating
4. `orm-activeRecord` branch: Utilizes Eloquent ORM and Blade templating

## Installation using docker
1. Clone the project using git
2. Navigate to `docker/` directory and run `docker-compose up -d`
3. Access the MySQL container `docker exec -it adil-db bash`
4. Create database schema
5. Create `.env` file from `.env.example` file and adjust database parameters
6. Run `composer install`
7. Open a web browser and navigate to http://localhost:8000

## Database Schema

```SQL
USE my_db;

CREATE TABLE users (
  id int UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  email varchar(255) NOT NULL,
  full_name varchar(255) NOT NULL,
  is_active boolean DEFAULT 0 NOT NULL,
  created_at datetime NOT NULL,
  updated_at datetime DEFAULT NULL,
  KEY `is_active`(`is_active`),
  UNIQUE KEY `email`(`email`)
);

CREATE TABLE invoices(
  id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  invoice_number INT UNSIGNED,
  amount  DECIMAL(10, 4),
  user_id INT UNSIGNED,
  status INT UNSIGNED,
  created_at DATETIME,
  FOREIGN KEY (user_id) REFERENCES users(id)
  ON DELETE SET NULL
  ON UPDATE CASCADE
);

CREATE TABLE invoice_items (
  id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  invoice_id INT UNSIGNED,
  description VARCHAR(255),
  quantity INT,
  unit_price  DECIMAL(10, 4),
  FOREIGN KEY (invoice_id) REFERENCES invoices(id)
  ON DELETE SET NULL
  ON UPDATE CASCADE
);

CREATE TABLE emails (
  id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  subject TEXT NOT NULL,
  status TINYINT UNSIGNED NOT NULL,
  text_body LONGTEXT NOT NULL,
  html_body LONGTEXT NOT NULL,
  meta JSON NOT NULL,
  created_at DATETIME NOT NULL,
  sent_at DATETIME
);
```
