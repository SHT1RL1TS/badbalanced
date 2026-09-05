<?php
ini_set('display_errors', '0');
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/db.php';
$db = getDb_();

$action = $_POST['action'] ?? '';

try {
    // 1. Список всех героев для выпадающего списка
    if ($action === 'heroes') {
        $stmt = $db->query('SELECT id, display_name FROM heroes ORDER BY display_name ASC');
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
        exit;
    }

    // 2. Список способностей с привязкой к герою
    if ($action === 'list') {
        $stmt = $db->query('
            SELECT a.id, a.internal_name, a.display_name, a.hero_id, a.is_ultimate, a.is_talent,
                   h.display_name AS hero_name
            FROM abilities a
            LEFT JOIN heroes h ON a.hero_id = h.id
            ORDER BY a.id ASC
        ');
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
        exit;
    }

    // 3. Получение одной способности для модалки
    if ($action === 'get') {
        $id = (int)($_POST['id'] ?? 0);
        $stmt = $db->prepare('SELECT id, internal_name, display_name, hero_id, is_ultimate, is_talent FROM abilities WHERE id = ?');
        $stmt->execute([$id]);
        echo json_encode(['success' => true, 'data' => $stmt->fetch(PDO::FETCH_ASSOC)]);
        exit;
    }

    // 4. Создание
    if ($action === 'create') {
        $internal = trim($_POST['internal_name'] ?? '');
        $display  = trim($_POST['display_name'] ?? '');
        $heroId   = !empty($_POST['hero_id']) ? (int)$_POST['hero_id'] : null;
        $ultimate = filter_var($_POST['is_ultimate'] ?? false, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false';
        $talent   = filter_var($_POST['is_talent'] ?? false, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false';

        $stmt = $db->prepare('INSERT INTO abilities (internal_name, display_name, hero_id, is_ultimate, is_talent) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$internal, $display, $heroId, $ultimate, $talent]);
        echo json_encode(['success' => true]);
        exit;
    }

    // 5. Обновление
    if ($action === 'update') {
        $id       = (int)($_POST['id'] ?? 0);
        $internal = trim($_POST['internal_name'] ?? '');
        $display  = trim($_POST['display_name'] ?? '');
        $heroId   = !empty($_POST['hero_id']) ? (int)$_POST['hero_id'] : null;
        $ultimate = filter_var($_POST['is_ultimate'] ?? false, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false';
        $talent   = filter_var($_POST['is_talent'] ?? false, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false';

        $stmt = $db->prepare('UPDATE abilities SET internal_name = ?, display_name = ?, hero_id = ?, is_ultimate = ?, is_talent = ? WHERE id = ?');
        $stmt->execute([$internal, $display, $heroId, $ultimate, $talent, $id]);
        echo json_encode(['success' => true]);
        exit;
    }

    // 6. Удаление
    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        $stmt = $db->prepare('DELETE FROM abilities WHERE id = ?');
        $stmt->execute([$id]);
        echo json_encode(['success' => true]);
        exit;
    }

    echo json_encode(['success' => false, 'message' => 'Неизвестное действие']);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
