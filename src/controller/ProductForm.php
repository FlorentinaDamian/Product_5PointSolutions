<?php
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/../bd/select.php';
$id = (int) ($_GET['id'] ?? 0);
try {
    $product = $id ? getProductById($id) : null;
} catch (Throwable $ex) {
    error_log($ex->getMessage());
    http_response_code(503);
    exit('The database is currently unavailable. Please try again later.');
}
if ($id && !$product) {
    setFlash('Product not found.', 'error');
    header('Location: index.php');
    exit;
}
$editing = $product !== null;
$flash = pullFlash();
$flashForm = $_SESSION['form'] ?? null;
unset($_SESSION['form']);
$errors = $flashForm['errors'] ?? [];
$old = $flashForm ? $flashForm['old'] : ($product ?? []);
$inStock = $flashForm ? isset($old['in_stock']) : (bool) ($old['in_stock'] ?? false);
return compact('id', 'product', 'editing', 'errors', 'old', 'inStock', 'flash');
