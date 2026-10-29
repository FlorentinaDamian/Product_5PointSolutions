<?php
const UPLOAD_DIR = __DIR__ . '/../../public/uploads/';
const MAX_IMAGE_BYTES = 2 * 1024 * 1024;
const ALLOWED_IMAGE_TYPES = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/gif'  => 'gif',
    'image/webp' => 'webp',
];
function validateProduct(array $post, array $files, ?string $existingImage = null): array
{
    $errors = [];
    $name = trim((string) ($post['name'] ?? ''));
    if ($name === '') {
        $errors['name'] = 'Name is required.';
    } elseif (mb_strlen($name) > 255) {
        $errors['name'] = 'Name must be at most 255 characters.';
    }
    $priceRaw = trim((string) ($post['price'] ?? ''));
    $price = null;
    if ($priceRaw === '') {
        $errors['price'] = 'Price is required.';
    } elseif (!preg_match('/^\d{1,8}(\.\d{1,2})?$/', $priceRaw)) {
        $errors['price'] = 'Price must be a non-negative number with at most 2 decimals.';
    } else {
        $price = $priceRaw;
    }
    $date = trim((string) ($post['availability_date'] ?? ''));
    if ($date !== '') {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        if (!$d || $d->format('Y-m-d') !== $date) {
            $errors['availability_date'] = 'Availability date is not a valid date.';
        }
    }
    $image = $existingImage;
    $upload = $files['image'] ?? null;
    $newImageTmp = null;
    $newImageName = null;
    if ($upload && $upload['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($upload['error'] !== UPLOAD_ERR_OK) {
            $errors['image'] = 'Image upload failed.';
        } elseif ($upload['size'] > MAX_IMAGE_BYTES) {
            $errors['image'] = 'Image must be at most 2 MB.';
        } else {
            $mime = (new finfo(FILEINFO_MIME_TYPE))->file($upload['tmp_name']);
            if (!isset(ALLOWED_IMAGE_TYPES[$mime]) || @getimagesize($upload['tmp_name']) === false) {
                $errors['image'] = 'Image must be a JPG, PNG, GIF or WEBP file.';
            } else {
                $newImageTmp = $upload['tmp_name'];
                $newImageName = bin2hex(random_bytes(12)) . '.' . ALLOWED_IMAGE_TYPES[$mime];
            }
        }
    }
    return [[
        'name'              => $name,
        'description'       => trim((string) ($post['description'] ?? '')) ?: null,
        'price'             => $price,
        'image'             => $image,
        'availability_date' => $date !== '' ? $date : null,
        'in_stock'          => isset($post['in_stock']) ? 1 : 0,
        'new_image_tmp'     => $newImageTmp,
        'new_image_name'    => $newImageName,
    ], $errors];
}
function storeImage(array $data): ?string
{
    if ($data['new_image_tmp'] === null) {
        return $data['image'];
    }
    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0755, true);
    }
    if (!move_uploaded_file($data['new_image_tmp'], UPLOAD_DIR . $data['new_image_name'])) {
        throw new RuntimeException('Could not save the uploaded image.');
    }
    return $data['new_image_name'];
}
function removeImageFile(?string $image): void
{
    if ($image && preg_match('/^[a-f0-9]+\.(jpg|png|gif|webp)$/', $image)) {
        @unlink(UPLOAD_DIR . $image);
    }
}
