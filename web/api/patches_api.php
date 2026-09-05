<?php
ini_set('display_errors', '0');
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/db.php';
$db = getDb_();

$action = $_POST['action'] ?? '';

try {
    // 1. Список патчей с подсчётом изменений
    if ($action === 'list') {
        $stmt = $db->query('
            SELECT p.id, p.patch_name, p.patch_date, p.is_custom,
                   COUNT(c.id) AS changes_count
            FROM patches p
            LEFT JOIN patch_changes c ON p.id = c.patch_id
            GROUP BY p.id
            ORDER BY p.id DESC
        ');
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
        exit;
    }

    // 2. Получение одного патча для модалки
    if ($action === 'get') {
        $id = (int)($_POST['id'] ?? 0);
        $stmt = $db->prepare('SELECT id, patch_name, patch_date, is_custom FROM patches WHERE id = ?');
        $stmt->execute([$id]);
        echo json_encode(['success' => true, 'data' => $stmt->fetch(PDO::FETCH_ASSOC)]);
        exit;
    }

    // 3. Создание патча
    if ($action === 'create') {
        $name   = trim($_POST['patch_name'] ?? '');
        $date   = !empty($_POST['patch_date']) ? $_POST['patch_date'] : null;
        $custom = filter_var($_POST['is_custom'] ?? false, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false';

        $stmt = $db->prepare('INSERT INTO patches (patch_name, patch_date, is_custom) VALUES (?, ?, ?)');
        $stmt->execute([$name, $date, $custom]);
        echo json_encode(['success' => true]);
        exit;
    }

    // 4. Обновление патча
    if ($action === 'update') {
        $id     = (int)($_POST['id'] ?? 0);
        $name   = trim($_POST['patch_name'] ?? '');
        $date   = !empty($_POST['patch_date']) ? $_POST['patch_date'] : null;
        $custom = filter_var($_POST['is_custom'] ?? false, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false';

        $stmt = $db->prepare('UPDATE patches SET patch_name = ?, patch_date = ?, is_custom = ? WHERE id = ?');
        $stmt->execute([$name, $date, $custom, $id]);
        echo json_encode(['success' => true]);
        exit;
    }

    // 5. Удаление патча (записи в patch_changes удалятся автоматически каскадом)
    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        $stmt = $db->prepare('DELETE FROM patches WHERE id = ?');
        $stmt->execute([$id]);
        echo json_encode(['success' => true]);
        exit;
    }

    echo json_encode(['success' => false, 'message' => 'Неизвестное действие']);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
