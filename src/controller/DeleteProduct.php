<?php
require_once __DIR__ . '/validation.php';
require_once __DIR__ . '/../bd/select.php';
require_once __DIR__ . '/../bd/delete.php';
require_once __DIR__ . '/session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../public/index.php');
    exit;
}
verifyCsrf();

$id = (int) ($_POST['id'] ?? 0);
$back = 'index.php?' . http_build_query([
    'page'   => max(1, (int) ($_POST['page'] ?? 1)),
    'search' => (string) ($_POST['search'] ?? ''),
]);

try {
    $existing = getProductById($id);
    if (!$existing) {
        finish('Product not found.', 'error', $back);
    }
    deleteProductById($id);
    removeImageFile($existing['image']);
} catch (Throwable $ex) {
    error_log($ex->getMessage());
    finish('Could not delete the product. Please try again.', 'error', $back);
}

finish('Product deleted.', 'success', $back);
