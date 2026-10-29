<?php
require_once __DIR__ . '/validation.php';
require_once __DIR__ . '/../bd/select.php';
require_once __DIR__ . '/../bd/update.php';
require_once __DIR__ . '/session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../public/index.php');
    exit;
}
verifyCsrf();

$id = (int) ($_POST['id'] ?? 0);
$formUrl = 'form.php?id=' . $id;

try {
    $existing = getProductById($id);
} catch (Throwable $ex) {
    error_log($ex->getMessage());
    failForm('Could not load the product. Please try again.', $_POST, $formUrl);
}
if (!$existing) {
    finish('Product not found.', 'error', 'index.php', true);
}

[$data, $errors] = validateProduct($_POST, $_FILES, $existing['image']);
if ($errors) {
    failValidation($errors, $_POST, $formUrl);
}

try {
    $data['image'] = storeImage($data);
    updateProduct($id, $data);
} catch (Throwable $ex) {
    error_log($ex->getMessage());
    if ($data['new_image_name'] !== null) {
        removeImageFile($data['new_image_name']);
    }
    failForm('Could not update the product. Please try again.', $_POST, $formUrl);
}

if ($data['new_image_name'] !== null) {
    removeImageFile($existing['image']);
}
finish('Product updated.', 'success', 'index.php', true);
