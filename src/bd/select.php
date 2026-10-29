<?php
require_once __DIR__ . '/connection.php';
function countProducts(string $search = ''): int
{
    $stmt = getConnection()->prepare('SELECT COUNT(*) FROM products WHERE name LIKE :s');
    $stmt->execute([':s' => likePattern($search)]);
    return (int) $stmt->fetchColumn();
}
function getProducts(string $search, int $limit, int $offset): array
{
    $stmt = getConnection()->prepare(
        'SELECT * FROM products WHERE name LIKE :s ORDER BY id DESC LIMIT :limit OFFSET :offset'
    );
    $stmt->bindValue(':s', likePattern($search));
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}
function getProductById(int $id): ?array
{
    $stmt = getConnection()->prepare('SELECT * FROM products WHERE id = :id');
    $stmt->execute([':id' => $id]);
    return $stmt->fetch() ?: null;
}
function likePattern(string $search): string
{
    return '%' . addcslashes($search, '\\%_') . '%';
}
