<?php
ini_set('display_errors', '0');
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/db.php';
$db = getDb_();

$action = $_REQUEST['action'] ?? '';

try {
    if ($action === 'list') {
        $stmt = $db->query('SELECT id, internal_name, display_name, is_neutral FROM items ORDER BY id DESC');
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
        exit;
    }

    if ($action === 'get') {
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $db->prepare('SELECT id, internal_name, display_name, is_neutral FROM items WHERE id = ?');
        $stmt->execute([$id]);
        echo json_encode(['success' => true, 'data' => $stmt->fetch(PDO::FETCH_ASSOC)]);
        exit;
    }

    if ($action === 'create') {
        $internal = trim($_POST['internal_name'] ?? '');
        $display  = trim($_POST['display_name'] ?? '');
        $neutral  = filter_var($_POST['is_neutral'] ?? false, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false';

        $stmt = $db->prepare('INSERT INTO items (internal_name, display_name, is_neutral) VALUES (?, ?, ?)');
        $stmt->execute([$internal, $display, $neutral]);
        echo json_encode(['success' => true]);
        exit;
    }

    if ($action === 'update') {
        $id       = (int)($_POST['id'] ?? 0);
        $internal = trim($_POST['internal_name'] ?? '');
        $display  = trim($_POST['display_name'] ?? '');
        $neutral  = filter_var($_POST['is_neutral'] ?? false, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false';

        $stmt = $db->prepare('UPDATE items SET internal_name = ?, display_name = ?, is_neutral = ? WHERE id = ?');
        $stmt->execute([$internal, $display, $neutral, $id]);
        echo json_encode(['success' => true]);
        exit;
    }

    if ($action === 'delete') {
        $id = (int)($_REQUEST['id'] ?? 0);
        $stmt = $db->prepare('DELETE FROM items WHERE id = ?');
        $stmt->execute([$id]);
        echo json_encode(['success' => true]);
        exit;
    }

    echo json_encode(['success' => false, 'message' => 'Неизвестное действие']);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
