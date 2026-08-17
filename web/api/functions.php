<?php

// ========== GET функции ==========

function getAllHeroes($db) {
    $query = "SELECT *
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
