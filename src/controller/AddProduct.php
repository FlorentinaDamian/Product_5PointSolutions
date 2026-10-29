<?php
require_once __DIR__ . '/validation.php';
require_once __DIR__ . '/../bd/insert.php';
require_once __DIR__ . '/session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../public/index.php');
    exit;
}
verifyCsrf();

[$data, $errors] = validateProduct($_POST, $_FILES);
if ($errors) {
    failValidation($errors, $_POST, 'form.php');
}

try {
    $data['image'] = storeImage($data);
    insertProduct($data);
} catch (Throwable $ex) {
    error_log($ex->getMessage());
    if ($data['new_image_name'] !== null) {
        removeImageFile($data['new_image_name']);
    }
    failForm('Could not save the product. Please try again.', $_POST, 'form.php');
}

finish('Product created.', 'success', 'index.php', true);
