<?php
require_once __DIR__ . '/connection.php';

function deleteProductById(int $id): void
{
    $stmt = getConnection()->prepare('DELETE FROM products WHERE id = :id');
    $stmt->execute([':id' => $id]);
}
