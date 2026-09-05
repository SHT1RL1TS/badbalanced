<?php
header('Content-Type: application/json; charset=utf-8');

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Подключаем уже готовое подключение к БД из твоего проекта
require_once __DIR__ . '/db.php';

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($username === '' || $password === '') {
    echo json_encode(['success' => false, 'error' => 'Заполните все поля']);
    exit;
}

try {
    /** @var PDO $db */
    $db = getDb();

    // Подготовленный запрос через PDO
    $stmt = $db->prepare('SELECT login, acl FROM users WHERE login = :login AND password = :password');
    $stmt->execute([
        ':login' => $username,
        ':password' => $password
    ]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['user_name'] = $user['login'];
        $_SESSION['user_acl'] = $user['acl'];

        echo json_encode([
            'success' => true,
            'redirect' => '/admin/home'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'Неверный логин или пароль'
        ]);
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Ошибка БД: ' . $e->getMessage()
    ]);
}
