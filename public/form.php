<?php
['id' => $id, 'product' => $product, 'editing' => $editing, 'errors' => $errors, 'old' => $old,
 'inStock' => $inStock, 'flash' => $flash]
    = require __DIR__ . '/../src/controller/ProductForm.php';
$val = fn(string $k) => e((string) ($old[$k] ?? ''));
$err = fn(string $k) => isset($errors[$k]) ? '<span class="error">' . e($errors[$k]) . '</span>' : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $editing ? 'Edit product' : 'Add a new product' ?></title>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/form.css">
</head>
<body>
    <div class="form-container">
        <h2><?= $editing ? 'Edit product' : 'Add a new product' ?></h2>
        <div id="flash" class="flash <?= e($flash['type'] ?? 'success') ?>" role="status" aria-live="polite"<?= $flash ? '' : ' hidden' ?>><?= e($flash['message'] ?? '') ?></div>
        <form method="POST" enctype="multipart/form-data" id="productForm"
              action="../src/controller/<?= $editing ? 'UpdateProduct' : 'AddProduct' ?>.php">
            <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
            <?php if ($editing): ?><input type="hidden" name="id" value="<?= $id ?>"><?php endif; ?>
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required maxlength="255" value="<?= $val('name') ?>" placeholder="Product name">
                <?= $err('name') ?>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" placeholder="Description..."><?= $val('description') ?></textarea>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" step="0.01" min="0" id="price" name="price" required value="<?= $val('price') ?>" placeholder="0.00">
                <?= $err('price') ?>
            </div>
            <div class="form-group">
                <label for="image">Image:</label>
                <?php if ($editing && $product['image']): ?>
                    <img class="current-image" src="uploads/<?= e($product['image']) ?>" alt="Current image">
                    <small>Upload a new file to replace the current image.</small>
                <?php endif; ?>
                <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/gif,image/webp">
                <?= $err('image') ?>
            </div>
            <div class="form-group">
                <label for="availability_date">Availability date:</label>
                <input type="date" id="availability_date" name="availability_date" value="<?= $val('availability_date') ?>">
                <?= $err('availability_date') ?>
            </div>
            <div class="form-group checkbox-group">
                <input type="checkbox" id="in_stock" name="in_stock" value="1" <?= $inStock ? 'checked' : '' ?>>
                <label for="in_stock">In stock</label>
            </div>

            <div class="form-actions">
                <a href="index.php" class="btn-cancel">Cancel</a>
                <button type="submit" class="btn-save">Save</button>
            </div>
        </form>
    </div>
    <script src="js/app.js"></script>
    <script src="js/product-form.js"></script>
</body>
</html>
