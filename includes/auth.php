<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';

function is_logged_in(): bool
{
    return !empty($_SESSION['user_id']);
}

function require_login(): void
{
    if (!is_logged_in()) {
        redirect('index.php');
    }
}

function login_user(string $username, string $password): bool
{
    $stmt = db()->prepare('SELECT id, username, password_hash FROM users WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        return true;
    }
    return false;
}

function logout_user(): void
{
    unset($_SESSION['user_id'], $_SESSION['username']);
    session_regenerate_id(true);
}

function current_user(): ?array
{
    if (!is_logged_in()) return null;
    return ['id' => $_SESSION['user_id'], 'username' => $_SESSION['username']];
}
