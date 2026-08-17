<?php
header('Content-Type: application/json');
require_once __DIR__ . '/client/tools/db.php';
require_once __DIR__ . '/client/tools/functions.php';

$db = getDb();
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'get_hero':
        $slug = isset($_GET['slug']) ? $_GET['slug'] : '';
        if ($slug) {
            $query = "SELECT id_hero, name_hero, description_hero, stats_hero, 
                             icon_url_hero, thumbnail_url_hero, attribute_id
                      FROM heroes 
                      WHERE LOWER(REPLACE(name_hero, ' ', '')) = :slug";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':slug', $slug);
            $stmt->execute();
            $hero = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($hero) {
                $skills = getHeroSkills($db, $hero['id_hero']);
                $hero['skills'] = $skills;
                echo json_encode(['success' => true, 'data' => $hero]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Hero not found']);
            }
        }
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}