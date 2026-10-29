<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function csrfToken(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf'];
}

function verifyCsrf(): void
{
    $sent = (string) ($_POST['csrf'] ?? '');
    $expected = (string) ($_SESSION['csrf'] ?? '');
    if ($expected === '' || !hash_equals($expected, $sent)) {
        $message = 'Invalid request token. Reload the page and try again.';
        if (isAjax()) {
            jsonResponse(['ok' => false, 'type' => 'error', 'message' => $message], 400);
        }
        http_response_code(400);
        exit($message);
    }
}
function e(?string $s): string
{
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
}
function isAjax(): bool
{
    return ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'fetch';
}

function jsonResponse(array $data, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}
function setFlash(string $message, string $type = 'success'): void
{
    $_SESSION['flash'] = ['message' => $message, 'type' => $type];
}
function pullFlash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return is_array($flash) ? $flash : null;
}
function finish(string $message, string $type, string $redirect, bool $flashOnAjax = false): void
{
    if (!isAjax() || $flashOnAjax) {
        setFlash($message, $type);
    }
    if (isAjax()) {
        jsonResponse(['ok' => $type === 'success', 'type' => $type, 'message' => $message, 'redirect' => $redirect]);
    }
    header('Location: ../../public/' . $redirect);
    exit;
}
function failValidation(array $errors, array $old, string $formUrl): void
{
    if (isAjax()) {
        jsonResponse(['ok' => false, 'type' => 'error', 'message' => 'Please fix the errors below.', 'errors' => $errors], 422);
    }
    $_SESSION['form'] = ['errors' => $errors, 'old' => $old];
    header('Location: ../../public/' . $formUrl);
    exit;
}

function failForm(string $message, array $old, string $formUrl): void
{
    if (isAjax()) {
        jsonResponse(['ok' => false, 'type' => 'error', 'message' => $message], 500);
    }
    setFlash($message, 'error');
    $_SESSION['form'] = ['errors' => [], 'old' => $old];
    header('Location: ../../public/' . $formUrl);
    exit;
}
