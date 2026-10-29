<?php
['search' => $search, 'page' => $page, 'pages' => $pages, 'products' => $products,
 'flash' => $flash, 'fragmentOnly' => $fragmentOnly] = require __DIR__ . '/../src/controller/ListProducts.php';
if ($fragmentOnly) {
    require __DIR__ . '/partials/product-list.php';
    return;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of products</title>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/table.css">
</head>
<body>
    <h1>List of products</h1>
    <div id="flash" class="flash <?= e($flash['type'] ?? 'success') ?>" role="status" aria-live="polite"<?= $flash ? '' : ' hidden' ?>><?= e($flash['message'] ?? '') ?></div>
    <div class="toolbar">
        <form method="GET" action="index.php" id="searchForm">
            <input type="text" name="search" id="search" value="<?= e($search) ?>" placeholder="Search by name..." autocomplete="off">
            <button type="submit">Search</button>
            <a class="clear" id="clearSearch" href="index.php"<?= $search === '' ? ' hidden' : '' ?>>Clear</a>
        </form>
        <a class="btn" href="form.php">Add product</a>
    </div>
    <div id="product-list">
        <?php require __DIR__ . '/partials/product-list.php'; ?>
    </div>
    <div class="modal" id="deleteModal" hidden>
        <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
            <h2 id="modalTitle">Delete product</h2>
            <p>Are you sure you want to permanently delete <strong id="modalName"></strong>? This cannot be undone.</p>
            <form method="POST" action="../src/controller/DeleteProduct.php" id="deleteForm">
                <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
                <input type="hidden" name="id" id="modalId">
                <input type="hidden" name="page" value="<?= $page ?>">
                <input type="hidden" name="search" value="<?= e($search) ?>">
                <div class="modal-actions">
                    <button type="button" class="secondary" id="modalCancel">Cancel</button>
                    <button type="submit" class="danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
    <script src="js/app.js"></script>
    <script src="js/list.js"></script>
    <script src="js/delete-modal.js"></script>
</body>
</html>
