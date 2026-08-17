<?php
// skills_api.php — REST API для скиллов (AJAX/jQuery)
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
                $stmt = $db->query('SELECT s.*, h.name_hero, p.name as patch_name FROM skills s LEFT JOIN heroes h ON s.id_hero = h.id_hero LEFT JOIN pathes p ON s.id = p.id ORDER BY s.name_skill');
                echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        } elseif ($action === 'get' && isset($_GET['id'])) {
            try {
                $skill = getSkillById($db, $_GET['id']);
                echo json_encode(['success' => true, 'data' => $skill]);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        } elseif ($action === 'by_hero' && isset($_GET['hero_id'])) {
            try {
                $skills = getHeroSkills($db, $_GET['hero_id']);
                echo json_encode(['success' => true, 'data' => $skills]);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }
        break;
    case 'POST':
        $action = $input['action'] ?? $_POST['action'] ?? 'create';
        if ($action === 'create') {
            try {
                $name = $input['name_skill'] ?? $_POST['name_skill'] ?? '';
                $desc = $input['description_skill'] ?? $_POST['description_skill'] ?? '';
                $img = $input['image_url_skill'] ?? $_POST['image_url_skill'] ?? '';
                $hero_id = $input['id_hero'] ?? $_POST['id_hero'] ?? null;
                $patch_id = $input['id'] ?? $_POST['id'] ?? null;
                $stmt = $db->prepare('INSERT INTO skills (name_skill, description_skill, image_url_skill, id_hero, id) VALUES (:name, :desc, :img, :hero_id, :patch_id) RETURNING id_skill');
                $stmt->execute([':name' => $name, ':desc' => $desc, ':img' => $img,':hero_id' => $hero_id, ':patch_id' => $patch_id]);
                $id = $stmt->fetchColumn();
                echo json_encode(['success' => true, 'id' => $id, 'message' => 'Skill created']);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        } elseif ($action === 'update') {
            try {
                $id = $input['id_skill'] ?? $_POST['id_skill'] ?? 0;
                $name = $input['name_skill'] ?? $_POST['name_skill'] ?? '';
                $desc = $input['description_skill'] ?? $_POST['description_skill'] ?? '';
                $img = $input['image_url_skill'] ?? $_POST['image_url_skill'] ?? '';
                $hero_id = $input['id_hero'] ?? $_POST['id_hero'] ?? null;
                $patch_id = $input['id'] ?? $_POST['id'] ?? null;

                $stmt = $db->prepare('UPDATE skills SET name_skill=:name, description_skill=:desc, image_url_skill=:img, id_hero=:hero_id, id=:patch_id WHERE id_skill=:id');
                $stmt->execute([
                    ':id' => $id, ':name' => $name, ':desc' => $desc, ':img' => $img,
                    ':hero_id' => $hero_id, ':patch_id' => $patch_id
                ]);
                echo json_encode(['success' => true, 'message' => 'Skill updated']);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }
        break;
    case 'DELETE':
        try {
            $id = $input['id_skill'] ?? $_GET['id'] ?? 0;
            $stmt = $db->prepare('DELETE FROM skills WHERE id_skill = :id');
            $stmt->execute([':id' => $id]);
            echo json_encode(['success' => true, 'message' => 'Skill deleted']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}
