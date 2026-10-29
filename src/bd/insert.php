<?php
require_once __DIR__ . '/connection.php';
function insertProduct(array $p): int
{
    $stmt = getConnection()->prepare(
        'INSERT INTO products (name, description, price, image, availability_date, in_stock)
         VALUES (:name, :description, :price, :image, :availability_date, :in_stock)'
    );
    $stmt->execute([
        ':name'              => $p['name'],
        ':description'       => $p['description'],
        ':price'             => $p['price'],
        ':image'             => $p['image'],
        ':availability_date' => $p['availability_date'],
        ':in_stock'          => $p['in_stock'],
    ]);
    return (int) getConnection()->lastInsertId();
}
