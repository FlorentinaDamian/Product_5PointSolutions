<?php
function createProductsTable(PDO $conn): void
{
    $conn->exec("CREATE TABLE IF NOT EXISTS products (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description TEXT NULL,
        price DECIMAL(10,2) NOT NULL,
        image VARCHAR(255) NULL,
        availability_date DATE NULL,
        in_stock TINYINT(1) NOT NULL DEFAULT 0,
        INDEX idx_products_name (name)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}
