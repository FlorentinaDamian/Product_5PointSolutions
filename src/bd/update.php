<?php
require_once __DIR__ . '/connection.php';
function updateProduct(int $id, array $p): void
{
    $stmt = getConnection()->prepare(
        'UPDATE products SET name = :name, description = :description, price = :price,
                image = :image, availability_date = :availability_date, in_stock = :in_stock
         WHERE id = :id'
    );
    $stmt->execute([
        ':name'              => $p['name'],
        ':description'       => $p['description'],
        ':price'             => $p['price'],
        ':image'             => $p['image'],
        ':availability_date' => $p['availability_date'],
        ':in_stock'          => $p['in_stock'],
        ':id'                => $id,
    ]);
}
