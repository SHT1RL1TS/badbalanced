<?php
require_once 'db.php';

// ========== GET функции ==========

function getAllHeroes($db) {
    $query = "SELECT id_hero, name_hero, icon_url_hero, thumbnail_url_hero, attribute_id 
              FROM heroes 
              ORDER BY name_hero";
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllSkills($db) {
    $query = "SELECT s.id_skill, s.name_skill, s.description_skill, s.image_url_skill, 
                     h.name_hero as hero_name, p.name as patch_name
              FROM skills s
              LEFT JOIN heroes h ON s.id_hero = h.id_hero
              LEFT JOIN pathes p ON s.id = p.id
              ORDER BY s.name_skill";
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getHeroSkills($db, $hero_id) {
    $query = "SELECT s.id_skill, s.name_skill, s.description_skill, s.image_url_skill,
                     p.name as patch_name, p.is_major
              FROM skills s
              LEFT JOIN pathes p ON s.id = p.id
              WHERE s.id_hero = :hero_id
              ORDER BY s.id_skill";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':hero_id', $hero_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPatchHeroes($db, $patch_id) {
    $query = "SELECT h.id_hero, h.name_hero, h.description_hero, h.stats_hero, 
                     h.icon_url_hero, h.thumbnail_url_hero, a.attribute_name
              FROM heroes h
              LEFT JOIN attribut a ON h.attribute_id = a.attribute_id
              WHERE h.id = :patch_id
              ORDER BY h.name_hero";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':patch_id', $patch_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPatchSkills($db, $patch_id) {
    $query = "SELECT s.id_skill, s.name_skill, s.description_skill, s.image_url_skill,
                     h.name_hero as hero_name
              FROM skills s
              LEFT JOIN heroes h ON s.id_hero = h.id_hero
              WHERE s.id = :patch_id
              ORDER BY s.name_skill";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':patch_id', $patch_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllPatches($db) {
    $query = "SELECT id, name, description, is_major, patch_img_url 
              FROM pathes 
              ORDER BY id DESC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPatchById($db, $patch_id) {
    $query = "SELECT id, name, description, is_major, patch_img_url 
              FROM pathes 
              WHERE id = :patch_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':patch_id', $patch_id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getAttributes($db) {
    $query = "SELECT attribute_id, attribute_name FROM attribut ORDER BY attribute_id";
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getHeroById($db, $hero_id) {
    $query = "SELECT h.id_hero, h.name_hero, h.description_hero, h.stats_hero, 
                     h.icon_url_hero, h.thumbnail_url_hero, h.attribute_id, a.attribute_name,
                     p.id as patch_id, p.name as patch_name
              FROM heroes h
              LEFT JOIN attribut a ON h.attribute_id = a.attribute_id
              LEFT JOIN pathes p ON h.id = p.id
              WHERE h.id_hero = :hero_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':hero_id', $hero_id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getSkillById($db, $skill_id) {
    $query = "SELECT s.id_skill, s.name_skill, s.description_skill, s.image_url_skill,
                     s.id, s.id_hero, h.name_hero as hero_name, p.name as patch_name
              FROM skills s
              LEFT JOIN heroes h ON s.id_hero = h.id_hero
              LEFT JOIN pathes p ON s.id = p.id
              WHERE s.id_skill = :skill_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':skill_id', $skill_id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// ========== CREATE функции ==========

function createPatch($db, $data) {
    $query = "INSERT INTO pathes (name, description, is_major, patch_img_url) 
              VALUES (:name, :description, :is_major, :patch_img_url) 
              RETURNING id";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':name', $data['name']);
    $stmt->bindParam(':description', $data['description']);
    $stmt->bindParam(':is_major', $data['is_major'], PDO::PARAM_BOOL);
    $stmt->bindParam(':patch_img_url', $data['patch_img_url']);
    
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC)['id'];
}

function createHero($db, $data) {
    $query = "INSERT INTO heroes (
                attribute_id, 
                name_hero, 
                description_hero, 
                stats_hero, 
                icon_hero) 
            VALUES (
                :attribute_id, 
                :name_hero, 
                :description_hero, 
                :stats_hero, 
                :icon_hero) 
            RETURNING id_hero";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':attribute_id', $data['attribute_id']);
    $stmt->bindParam(':name_hero', $data['name_hero']);
    $stmt->bindParam(':description_hero', $data['description_hero']);
    $stmt->bindParam(':stats_hero', $data['stats_hero']);
    $stmt->bindParam(':icon_hero', $data['icon_hero']);
    
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC)['id_hero'];
}

function createSkill($db, $data) {
    $query = "INSERT INTO skills (
                    id, 
                    id_hero, 
                    name_skill, 
                    description_skill, 
                    image_url_skill) 
                VALUES (
                    :patch_id, 
                    :hero_id, 
                    :name_skill, 
                    :description_skill, 
                    :image_url_skill) 
                RETURNING id_skill";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':hero_id', $data['hero_id']);
    $stmt->bindParam(':name_skill', $data['name_skill']);
    $stmt->bindParam(':description_skill', $data['description_skill']);
    $stmt->bindParam(':image_url_skill', $data['image_url_skill']);
    
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC)['id_skill'];
}

// ========== UPDATE функции ==========

function updateHero($db, $hero_id, $data) {
    $query = "UPDATE heroes 
              SET name_hero = :name_hero,
                  description_hero = :description_hero,
                  attribute_id = :attribute_id,
                  stats_hero = :stats_hero,
                  icon_url_hero = :icon_url_hero,
                  thumbnail_url_hero = :thumbnail_url_hero,
                  updated_at = CURRENT_TIMESTAMP
              WHERE id_hero = :hero_id";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':hero_id', $hero_id);
    $stmt->bindParam(':name_hero', $data['name_hero']);
    $stmt->bindParam(':description_hero', $data['description_hero']);
    $stmt->bindParam(':attribute_id', $data['attribute_id']);
    $stmt->bindParam(':stats_hero', $data['stats_hero']);
    $stmt->bindParam(':icon_url_hero', $data['icon_url_hero']);
    $stmt->bindParam(':thumbnail_url_hero', $data['thumbnail_url_hero']);
    
    return $stmt->execute();
}

function updateSkill($db, $skill_id, $data) {
    $query = "UPDATE skills 
              SET name_skill = :name_skill,
                  description_skill = :description_skill,
                  image_url_skill = :image_url_skill,
                  id_hero = :hero_id,
                  updated_at = CURRENT_TIMESTAMP
              WHERE id_skill = :skill_id";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':skill_id', $skill_id);
    $stmt->bindParam(':name_skill', $data['name_skill']);
    $stmt->bindParam(':description_skill', $data['description_skill']);
    $stmt->bindParam(':image_url_skill', $data['image_url_skill']);
    $stmt->bindParam(':hero_id', $data['hero_id']);
    
    return $stmt->execute();
}

function updatePatch($db, $patch_id, $data) {
    $query = "UPDATE pathes 
              SET name = :name,
                  description = :description,
                  is_major = :is_major,
                  patch_img_url = :patch_img_url
              WHERE id = :patch_id";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':patch_id', $patch_id);
    $stmt->bindParam(':name', $data['name']);
    $stmt->bindParam(':description', $data['description']);
    $stmt->bindParam(':is_major', $data['is_major'], PDO::PARAM_BOOL);
    $stmt->bindParam(':patch_img_url', $data['patch_img_url']);
    
    return $stmt->execute();
}

// ========== DELETE функции ==========

function deleteHero($db, $hero_id) {
    $query = "DELETE FROM heroes WHERE id_hero = :hero_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':hero_id', $hero_id);
    return $stmt->execute();
}

function deleteSkill($db, $skill_id) {
    $query = "DELETE FROM skills WHERE id_skill = :skill_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':skill_id', $skill_id);
    return $stmt->execute();
}

function deletePatch($db, $patch_id) {
    $query = "DELETE FROM pathes WHERE id = :patch_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':patch_id', $patch_id);
    return $stmt->execute();
}

// ========== Вспомогательные функции ==========

function uploadImage($file, $target_dir = null) {
    if ($target_dir === null) {
        $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/cd-project/butbalanced/src/patches/';
    }
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }
    
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    if (!in_array($file_extension, $allowed_extensions)) {
        return ['success' => false, 'message' => 'Invalid file type'];
    }
    
    $new_filename = uniqid() . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return ['success' => true, 'path' => $new_filename];
    }
    
    return ['success' => false, 'message' => 'Upload failed'];
}
?>