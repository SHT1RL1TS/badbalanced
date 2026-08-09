<?php
$heroSlug = isset($matches[1]) ? $matches[1] : "";
print_r($heroSlug);
// Получаем все данные героя по slug
try {
    // Сначала получаем ID героя по slug
    $query = "
    SELECT
        id_hero,
        name_hero,
        description_hero,
        stats_hero,
        icon_url_hero,
        thumbnail_url_hero,
        attribute_id
    FROM heroes
    WHERE LOWER(REPLACE(name_hero, ' ', '')) = :slug";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":slug", $heroSlug);
    $stmt->execute();
    $hero = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$hero) {
        header("Location: /cd-project/butbalanced/client/heros");
        exit();
    }

    // Получаем атрибут
    $attrQuery =
        "SELECT attribute_name FROM attribut WHERE attribute_id = :attr_id";
    $attrStmt = $db->prepare($attrQuery);
    $attrStmt->bindParam(":attr_id", $hero["attribute_id"]);
    $attrStmt->execute();
    $attribute = $attrStmt->fetch(PDO::FETCH_ASSOC);

    // Получаем скиллы героя
    $skills = getHeroSkills($db, $hero["id_hero"]);

    // Получаем последний патч для героя
    $patchQuery = "SELECT p.id, p.name, p.description, p.is_major, p.patch_img_url
                       FROM pathes p
                       JOIN skills s ON s.id = p.id
                       WHERE s.id_hero = :hero_id
                       ORDER BY p.id DESC
                       LIMIT 1";
    $patchStmt = $db->prepare($patchQuery);
    $patchStmt->bindParam(":hero_id", $hero["id_hero"]);
    $patchStmt->execute();
    $patch = $patchStmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
    exit();
}

$attrIcon = [
    1 => "/cd-project/butbalanced/src/icons/hero_strength.png",
    2 => "/cd-project/butbalanced/src/icons/hero_universal.png",
    3 => "/cd-project/butbalanced/src/icons/hero_intelligence.png",
    4 => "/cd-project/butbalanced/src/icons/hero_agility.png",
];
?>

<div class="hero-detail-container">
    <div class="hero-detail-header" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); padding: 40px 20px;">
        <div class="hero-detail-content" style="max-width: 1200px; margin: 0 auto; display: flex; align-items: center; gap: 40px;">
            <!-- Иконка героя -->
            <div class="hero-icon-wrapper">
                <img src="/cd-project/butbalanced/src/heroes/<?= $hero[
                    "icon_hero"
                ] ?>" alt="<?= $hero[
    "name_hero"
] ?>" style="width: 200px; height: auto; border-radius: 10px; box-shadow: 0 0 30px rgba(0,0,0,0.5);">
            </div>

            <!-- Информация о герое -->
            <div class="hero-info">
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
                    <h1 style="color: #fff; font-size: 48px; margin: 0; font-family: Reaver, serif;"><?= $hero[
                        "name_hero"
                    ] ?></h1>
                    <img src="<?= $attrIcon[
                        $hero["attribute_id"]
                    ] ?>" alt="<?= $attribute[
    "attribute_name"
] ?>" style="width: 40px; height: 40px;">
                </div>
                <p style="color: #aaa; font-size: 18px; line-height: 1.6; max-width: 600px;"><?= $hero[
                    "description_hero"
                ] ?></p>

                <?php if ($patch): ?>
                <div style="margin-top: 15px; display: flex; align-items: center; gap: 10px;">
                    <span style="color: #888;">Последний патч:</span>
                    <span style="color: #ffd700; font-weight: bold;"><?= $patch[
                        "name"
                    ] ?></span>
                    <?php if ($patch["is_major"]): ?>
                        <span style="background: #ffd700; color: #000; padding: 2px 10px; border-radius: 3px; font-size: 12px; font-weight: bold;">MAJOR</span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Статистика героя -->
    <div style="max-width: 1200px; margin: 30px auto; padding: 0 20px;">
        <h2 style="color: #fff; font-family: Reaver, serif; margin-bottom: 20px;">📊 Характеристики</h2>
        <div style="background: rgba(255,255,255,0.05); border-radius: 10px; padding: 20px; border: 1px solid rgba(255,255,255,0.1);">
            <?php
            $stats = json_decode($hero["stats_hero"], true);
            if ($stats): ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px;">
                <?php foreach ($stats as $key => $value): ?>
                <div>
                    <div style="color: #888; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;"><?= $key ?></div>
                    <div style="color: #fff; font-size: 24px; font-weight: bold;"><?= $value ?></div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <p style="color: #666;">Статистика не доступна</p>
            <?php endif;
            ?>
        </div>
    </div>

    <!-- Скиллы героя -->
    <div style="max-width: 1200px; margin: 30px auto; padding: 0 20px;">
        <h2 style="color: #fff; font-family: Reaver, serif; margin-bottom: 20px;">⚔️ Способности</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
            <?php foreach ($skills as $skill): ?>
            <div style="background: rgba(255,255,255,0.05); border-radius: 10px; padding: 20px; border: 1px solid rgba(255,255,255,0.1); transition: transform 0.3s ease;">
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
                    <?php if ($skill["image_url_skill"]): ?>
                        <img src="/cd-project/butbalanced/src/abilities/<?= $skill[
                            "image_url_skill"
                        ] ?>" alt="<?= $skill[
    "name_skill"
] ?>" style="width: 50px; height: 50px; border-radius: 8px;">
                    <?php endif; ?>
                    <h3 style="color: #fff; margin: 0; font-size: 18px;"><?= $skill[
                        "name_skill"
                    ] ?></h3>
                </div>
                <p style="color: #aaa; font-size: 14px; line-height: 1.6; margin: 0;"><?= $skill[
                    "description_skill"
                ] ?></p>
                <?php if ($skill["patch_name"]): ?>
                    <div style="margin-top: 10px; color: #666; font-size: 12px;">
                        Патч: <?= $skill["patch_name"] ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Кнопка назад -->
    <div style="max-width: 1200px; margin: 30px auto; padding: 0 20px;">
        <a href="/cd-project/butbalanced/client/heros" style="display: inline-block; padding: 12px 30px; background: rgba(255,255,255,0.1); color: #fff; border-radius: 5px; text-decoration: none; transition: background 0.3s ease;">
            ← Назад к списку героев
        </a>
    </div>
</div>
