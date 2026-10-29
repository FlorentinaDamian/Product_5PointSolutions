<?php
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/../bd/select.php';
const PER_PAGE = 10;
function pageUrl(int $p, string $search): string
{
    return 'index.php?' . http_build_query(['page' => $p, 'search' => $search]);
}
$fragmentOnly = isAjax();
$search = trim((string) ($_GET['search'] ?? ''));
$flash = $fragmentOnly ? null : pullFlash();
try {
    $total = countProducts($search);
    $pages = max(1, (int) ceil($total / PER_PAGE));
    $page = min(max(1, (int) ($_GET['page'] ?? 1)), $pages);
    $products = getProducts($search, PER_PAGE, ($page - 1) * PER_PAGE);
} catch (Throwable $ex) {
    error_log($ex->getMessage());
    $page = 1;
    $pages = 1;
    $products = [];
    $flash = ['message' => 'Could not load the products. Please try again later.', 'type' => 'error'];
    if ($fragmentOnly) {
        http_response_code(500);
    }
}
return compact('search', 'page', 'pages', 'products', 'flash', 'fragmentOnly');
