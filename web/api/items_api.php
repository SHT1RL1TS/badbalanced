<?php
// items_api.php — REST API для предметов (AJAX/jQuery)
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/db.php';
$db = getDb_();
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);
switch ($method) {
    case 'GET':
        $action = $_GET['action'] ?? 'list';
        if ($action === 'list') {
            try {
                $stmt = $db->query('SELECT * FROM items ORDER BY name_item');
                echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        } elseif ($action === 'get' && isset($_GET['id'])) {
            try {
                $stmt = $db->prepare('SELECT * FROM items WHERE id_item = :id');
                $stmt->execute([':id' => $_GET['id']]);
                $item = $stmt->fetch(PDO::FETCH_ASSOC);
                echo json_encode(['success' => true, 'data' => $item]);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }
        break;
    case 'POST':
        $action = $input['action'] ?? $_POST['action'] ?? 'create';
        if ($action === 'create') {
            try {
                $name = $input['name_item'] ?? $_POST['name_item'] ?? '';
                $desc = $input['description_item'] ?? $_POST['description_item'] ?? '';
                $img = $input['image_url_item'] ?? $_POST['image_url_item'] ?? '';
                $is_neutral = $input['is_neutral'] ?? $_POST['is_neutral'] ?? false;
                $stmt = $db->prepare('INSERT INTO items (name_item, description_item, image_url_item, is_neutral) VALUES (:name, :desc, :img, :is_neutral) RETURNING id_item');
                $stmt->execute([':name' => $name, ':desc' => $desc, ':img' => $img, ':is_neutral' => $is_neutral]);
                $id = $stmt->fetchColumn();
                echo json_encode(['success' => true, 'id' => $id, 'message' => 'Item created']);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        } elseif ($action === 'update') {
            try {
                $id = $input['id_item'] ?? $_POST['id_item'] ?? 0;
                $name = $input['name_item'] ?? $_POST['name_item'] ?? '';
                $desc = $input['description_item'] ?? $_POST['description_item'] ?? '';
                $img = $input['image_url_item'] ?? $_POST['image_url_item'] ?? '';
                $is_neutral = $input['is_neutral'] ?? $_POST['is_neutral'] ?? false;
                $stmt = $db->prepare('UPDATE items SET name_item=:name, description_item=:desc, image_url_item=:img, is_neutral=:is_neutral WHERE id_item=:id');
                $stmt->execute([
                    ':id' => $id, ':name' => $name, ':desc' => $desc, ':img' => $img, ':is_neutral' => $is_neutral
                ]);
                echo json_encode(['success' => true, 'message' => 'Item updated']);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }
        break;
    case 'DELETE':
        try {
            $id = $input['id_item'] ?? $_GET['id'] ?? 0;
            $stmt = $db->prepare('DELETE FROM items WHERE id_item = :id');
            $stmt->execute([':id' => $id]);
            echo json_encode(['success' => true, 'message' => 'Item deleted']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}
