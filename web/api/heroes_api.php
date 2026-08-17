// <?php
// heroes_api.php — REST API для героев (AJAX/jQuery)
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/db.php';
$db = getDb();
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);
switch ($method) {
    case 'GET':
        $action = $_GET['action'] ?? 'list';
        if ($action === 'list') {
            try {
                $stmt = $db->query('SELECT h.*, a.attribute_name FROM heroes h LEFT JOIN attribut a ON h.attribute_id = a.attribute_id ORDER BY h.name_hero');
                echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        } elseif ($action === 'get' && isset($_GET['id'])) {
            try {
                $hero = getHeroById($db, $_GET['id']);
                if ($hero) {
                    // stats
                    $stmt = $db->prepare('SELECT * FROM heroes_stats WHERE id_hero = :id');
                    $stmt->execute([':id' => $_GET['id']]);
                    $hero['stats'] = $stmt->fetch(PDO::FETCH_ASSOC);
                    // skills
                    $hero['skills'] = getHeroSkills($db, $_GET['id']);
                    echo json_encode(['success' => true, 'data' => $hero]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Hero not found']);
                }
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        } elseif ($action === 'by_slug' && isset($_GET['slug'])) {
            try {
                $stmt = $db->prepare('SELECT * FROM heroes WHERE LOWER(REPLACE(name_hero, ' ', '')) = :slug');
                $stmt->execute([':slug' => $_GET['slug']]);                 $hero = $stmt->fetch(PDO::FETCH_ASSOC);
                echo json_encode(['success' => true, 'data' => $hero]);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }
        break;
    case 'POST':
        $action = $input['action'] ?? $_POST['action'] ?? 'create';
        if ($action === 'create') {
            try {
                $name = $input['name_hero'] ?? $_POST['name_hero'] ?? '';
                $attr = $input['attribute_id'] ?? $_POST['attribute_id'] ?? 1;
                $complexity = $input['complexity'] ?? $_POST['complexity'] ?? 1;
                $icon = $input['icon_hero'] ?? $_POST['icon_hero'] ?? '';
                $crop = $input['crop_hero'] ?? $_POST['crop_hero'] ?? '';
                $stmt = $db->prepare('INSERT INTO heroes (name_hero, attribute_id, complexity, icon_hero, crop_hero) VALUES (:name, :attr, :complexity, :icon, :crop) RETURNING id_hero');
                $stmt->execute([':name' => $name, ':attr' => $attr, ':complexity' => $complexity, ':icon' => $icon, ':crop' => $crop]);
                $id = $stmt->fetchColumn();
                echo json_encode(['success' => true, 'id' => $id, 'message' => 'Hero created']);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        } elseif ($action === 'update') {
            try {
                $id = $input['id_hero'] ?? $_POST['id_hero'] ?? 0;
                $name = $input['name_hero'] ?? $_POST['name_hero'] ?? '';
                $attr = $input['attribute_id'] ?? $_POST['attribute_id'] ?? 1;
                $complexity = $input['complexity'] ?? $_POST['complexity'] ?? 1;
                $icon = $input['icon_hero'] ?? $_POST['icon_hero'] ?? '';
                $crop = $input['crop_hero'] ?? $_POST['crop_hero'] ?? '';
                $stmt = $db->prepare('UPDATE heroes SET name_hero=:name, attribute_id=:attr, complexity=:complexity, icon_hero=:icon, crop_hero=:crop WHERE id_hero=:id');
                $stmt->execute([':id' => $id, ':name' => $name, ':attr' => $attr, ':complexity' => $complexity,':icon' => $icon, ':crop' => $crop]);
                echo json_encode(['success' => true, 'message' => 'Hero updated']);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }
        break;
        case 'DELETE':
            try {
                $id = $input['id_hero'] ?? $_GET['id'] ?? 0;
                $stmt = $db->prepare('DELETE FROM heroes WHERE id_hero = :id');
                $stmt->execute([':id' => $id]);
                echo json_encode(['success' => true, 'message' => 'Hero deleted']);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}
