<?php // patches_api.php — REST API для патчей (AJAX/jQuery)
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
                $patches = getAllPatches($db);
                echo json_encode(['success' => true, 'data' => $patches]);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        } elseif ($action === 'get' && isset($_GET['id'])) {
            try {
                $patch = getPatchById($db, $_GET['id']);
                if ($patch) {
                    // heroes in patch
                    $patch['heroes'] = getPatchHeroes($db, $_GET['id']);
                    // skills in patch
                    $patch['skills'] = getPatchSkills($db, $_GET['id']);
                    echo json_encode(['success' => true, 'data' => $patch]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Patch not found']);
                }
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }
        break;
    case 'POST':
        $action = $input['action'] ?? $_POST['action'] ?? 'create';
        if ($action === 'create') {
            try {
                $name = $input['name'] ?? $_POST['name'] ?? '';
                $desc = $input['description'] ?? $_POST['description'] ?? '';
                $is_major = $input['is_major'] ?? $_POST['is_major'] ?? false;
                $img = $input['patch_img_url'] ?? $_POST['patch_img_url'] ?? '';
                $stmt = $db->prepare('INSERT INTO pathes (name, description, is_major, patch_img_url) VALUES (:name, :desc, :is_major, :img) RETURNING id');
                $stmt->execute([':name' => $name, ':desc' => $desc, ':is_major' => $is_major ? 1 : 0, ':img' => $img]);
                $id = $stmt->fetchColumn();
                echo json_encode(['success' => true, 'id' => $id, 'message' => 'Patch created']);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        } elseif ($action === 'update') {
            try {
                $id = $input['id'] ?? $_POST['id'] ?? 0;
                $name = $input['name'] ?? $_POST['name'] ?? '';
                $desc = $input['description'] ?? $_POST['description'] ?? '';
                $is_major = $input['is_major'] ?? $_POST['is_major'] ?? false;
                $img = $input['patch_img_url'] ?? $_POST['patch_img_url'] ?? '';

                $stmt = $db->prepare('UPDATE pathes SET name=:name, description=:desc, is_major=:is_major, patch_img_url=:img WHERE id=:id');
                $stmt->execute([
                    ':id' => $id, ':name' => $name, ':desc' => $desc,
                    ':is_major' => $is_major ? 1 : 0, ':img' => $img
                ]);
                echo json_encode(['success' => true, 'message' => 'Patch updated']);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }
        break;
    case 'DELETE':
        try {
            $id = $input['id'] ?? $_GET['id'] ?? 0;
            $stmt = $db->prepare('DELETE FROM pathes WHERE id = :id');
            $stmt->execute([':id' => $id]);
            echo json_encode(['success' => true, 'message' => 'Patch deleted']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}
