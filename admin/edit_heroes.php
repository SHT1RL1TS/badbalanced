<?php

if(!isset($_SESSION['user_name']))
{
    Header('Location:home');
}

$heroes = getAllHeroes($db);
$message = '';
$current_index = isset($_POST['current_index']) ? (int)$_POST['current_index'] : 0;
$hero_id = isset($heroes[$current_index]['id_hero']) ? $heroes[$current_index]['id_hero'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'save' || $action === 'save_and_next') {
        try {
            $hero_id = (int)$_POST['hero_id'];
            $name_hero = $_POST['name_hero'] ?? '';
            $description_hero = $_POST['description_hero'] ?? '';
            $full_description = $_POST['full_description'] ?? '';
            $attack_type = (int)($_POST['attack_type'] ?? 0);
            $complexity = (int)($_POST['complexity'] ?? 0);
            $attribute_id = (int)($_POST['attribute_id'] ?? 0);
            $role = $_POST['role'] ?? '';

            // JSON роли
            $roles = [
                'core' => (int)($_POST['core'] ?? 0),
                'support' => (int)($_POST['support'] ?? 0),
                'burst' => (int)($_POST['burst'] ?? 0),
                'control' => (int)($_POST['control'] ?? 0),
                'jungle' => (int)($_POST['jungle'] ?? 0),
                'tank' => (int)($_POST['tank'] ?? 0),
                'escape' => (int)($_POST['escape'] ?? 0),
                'siege' => (int)($_POST['siege'] ?? 0),
                'initiation' => (int)($_POST['initiation'] ?? 0)
            ];
            $roles_json = json_encode($roles, JSON_UNESCAPED_UNICODE);

            // JSON статы
            $stats = [
                'damage' => $_POST['damage'] ?? '',
                'attack_interval' => (float)($_POST['attack_interval'] ?? 0),
                'range' => (int)($_POST['range'] ?? 0),
                'projectile_speed' => (int)($_POST['projectile_speed'] ?? 0),
                'armor' => (float)($_POST['armor'] ?? 0),
                'magic_resist' => (int)($_POST['magic_resist'] ?? 0),
                'move_speed' => (int)($_POST['move_speed'] ?? 0),
                'turn_rate' => (float)($_POST['turn_rate'] ?? 0),
                'vision' => $_POST['vision'] ?? ''
            ];
            $stats_json = json_encode($stats, JSON_UNESCAPED_UNICODE);

            // --- Обработка загрузки файлов ---
            $icon_hero = $_POST['icon_hero'] ?? '';
            $crop_hero = $_POST['crop_hero'] ?? '';
            $upload_base = $_SERVER['DOCUMENT_ROOT'] . $src;

            if (isset($_FILES['icon_file']) && $_FILES['icon_file']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = rtrim($upload_base, '/\\') . DIRECTORY_SEPARATOR . 'heroes' . DIRECTORY_SEPARATOR;
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                $ext = pathinfo($_FILES['icon_file']['name'], PATHINFO_EXTENSION);
                $safe_name = preg_replace('/[^a-zA-Z0-9_-]/u', '_', $name_hero);
                $filename = $safe_name . '.' . strtolower($ext);
                $target_file = $upload_dir . $filename;
                if (move_uploaded_file($_FILES['icon_file']['tmp_name'], $target_file)) {
                    $icon_hero = $filename;
                }
            }

            if (isset($_FILES['thumbnail_file']) && $_FILES['thumbnail_file']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = rtrim($upload_base, '/\\') . DIRECTORY_SEPARATOR . 'heroes' . DIRECTORY_SEPARATOR . 'crops' . DIRECTORY_SEPARATOR;
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                $ext = pathinfo($_FILES['thumbnail_file']['name'], PATHINFO_EXTENSION);
                $safe_name = preg_replace('/[^a-zA-Z0-9_-]/u', '_', $name_hero);
                $filename = $safe_name . '.' . strtolower($ext);
                $target_file = $upload_dir . $filename;
                if (move_uploaded_file($_FILES['thumbnail_file']['tmp_name'], $target_file)) {
                    $crop_hero = $filename;
                }
            }

            // --- Проверка существования героя ---
            $check_query = "SELECT id_hero FROM heroes WHERE id_hero = :id_hero";
            $check_stmt = $db->prepare($check_query);
            $check_stmt->bindValue(':id_hero', $hero_id, PDO::PARAM_INT);
            $check_stmt->execute();
            $exists = $check_stmt->fetch();

            if ($exists) {
                // UPDATE heroes
                $query = "UPDATE heroes SET
                    name_hero = :name_hero,
                    attribute_id = :attribute_id,
                    icon_hero = :icon_hero,
                    crop_hero = :crop_hero,
                    complexity = :complexity
                    WHERE id_hero = :id_hero";
                $stmt = $db->prepare($query);
                $stmt->bindValue(':id_hero', $hero_id, PDO::PARAM_INT);
                $stmt->bindValue(':name_hero', $name_hero);
                $stmt->bindValue(':attribute_id', $attribute_id, PDO::PARAM_INT);
                $stmt->bindValue(':icon_hero', $icon_hero);
                $stmt->bindValue(':crop_hero', $crop_hero);
                $stmt->bindValue(':complexity', $complexity, PDO::PARAM_INT);
                $stmt->execute();

                // UPSERT heroes_stats
                $query2 = "
                INSERT INTO heroes_stats (
                    id_hero,
                    description_hero,
                    full_description,
                    attack_type,
                    roles,
                    stats
                ) VALUES (
                    :id_hero,
                    :description_hero,
                    :full_description,
                    :attack_type,
                    :roles,
                    :stats
                )
                ON CONFLICT (id_hero) DO UPDATE SET
                    description_hero = EXCLUDED.description_hero,
                    full_description = EXCLUDED.full_description,
                    attack_type = EXCLUDED.attack_type,
                    roles = EXCLUDED.roles,
                    stats = EXCLUDED.stats";
                $stmt2 = $db->prepare($query2);
                $stmt2->bindValue(':id_hero', $hero_id, PDO::PARAM_INT);
                $stmt2->bindValue(':description_hero', $description_hero);
                $stmt2->bindValue(':full_description', $full_description);
                $stmt2->bindValue(':attack_type', $attack_type, PDO::PARAM_INT);
                $stmt2->bindValue(':roles', $roles_json);
                $stmt2->bindValue(':stats', $stats_json);
                $stmt2->execute();

                $message = '<div class="alert alert-success">Герой #' . $hero_id . ' обновлен!</div>';
            } else {
                // INSERT нового героя
                $query = "
                INSERT INTO heroes (
                    attribute_id,
                    name_hero,
                    icon_hero,
                    crop_hero,
                    complexity
                ) VALUES (
                    :attribute_id,
                    :name_hero,
                    :icon_hero,
                    :crop_hero,
                    :complexity
                ) RETURNING id_hero";
                $stmt = $db->prepare($query);
                $stmt->bindValue(':attribute_id', $attribute_id, PDO::PARAM_INT);
                $stmt->bindValue(':name_hero', $name_hero);
                $stmt->bindValue(':icon_hero', $icon_hero);
                $stmt->bindValue(':crop_hero', $crop_hero);
                $stmt->bindValue(':complexity', $complexity, PDO::PARAM_INT);
                $stmt->execute();
                $new_hero_id = $stmt->fetchColumn();

                $query2 = "INSERT INTO heroes_stats (
                    id_hero,
                    description_hero,
                    full_description,
                    attack_type,
                    roles,
                    stats
                ) VALUES (
                    :id_hero,
                    :description_hero,
                    :full_description,
                    :attack_type,
                    :roles,
                    :stats
                )";
                $stmt2 = $db->prepare($query2);
                $stmt2->bindValue(':id_hero', $new_hero_id, PDO::PARAM_INT);
                $stmt2->bindValue(':description_hero', $description_hero);
                $stmt2->bindValue(':full_description', $full_description);
                $stmt2->bindValue(':attack_type', $attack_type, PDO::PARAM_INT);
                $stmt2->bindValue(':roles', $roles_json);
                $stmt2->bindValue(':stats', $stats_json);
                $stmt2->execute();

                $hero_id = $new_hero_id;
                $message = '<div class="alert alert-success">Герой #' . $hero_id . ' создан!</div>';
            }

            $heroes = getAllHeroes($db);

            if ($action === 'save_and_next') {
                $current_index = min($current_index + 1, count($heroes) - 1);
                $hero_id = isset($heroes[$current_index]['id_hero']) ? $heroes[$current_index]['id_hero'] : 0;
            }

        } catch (PDOException $e) {
            $message = '<div class="alert alert-danger">' . $e->getMessage() . '</div>';
        }
    } elseif ($action === 'navigate') {
        $current_index = (int)$_POST['current_index'];
        $hero_id = isset($heroes[$current_index]['id_hero']) ? $heroes[$current_index]['id_hero'] : 0;
    }
}

$current_hero = null;
$roles_data = null;
$stats_data = null;
$hero_data = null;

if ($hero_id > 0) {
    $query = "SELECT * FROM heroes WHERE id_hero = :hero_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':hero_id', $hero_id);
    $stmt->execute();
    $current_hero = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($current_hero) {
        $query = "SELECT * FROM heroes_stats WHERE id_hero = :hero_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':hero_id', $hero_id);
        $stmt->execute();
        $hero_data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($hero_data) {
            $roles_data = json_decode($hero_data['roles'] ?? '{}', true);
            $stats_data = json_decode($hero_data['stats'] ?? '{}', true);
        }
    }
}

$attributes = getAttributes($db);
$total_heroes = count($heroes);

$page_hero_name = ($current_hero && !empty($current_hero['name_hero'])) ? $current_hero['name_hero'] : 'Новый герой';

// Стандартные значения статов
$default_stats = [
    'damage' => '45-50',
    'attack_interval' => 1.7,
    'range' => 150,
    'projectile_speed' => 900,
    'armor' => 2.5,
    'magic_resist' => 25,
    'move_speed' => 100,
    'turn_rate' => 0.6,
    'vision' => '1800/800'
];
?>

<?php if (!empty($message)): ?>
<script>
    const dbStatus = <?php echo json_encode([
        'success' => strpos($message, 'alert-success') !== false,
        'error' => strpos($message, 'alert-danger') !== false,
        'message' => strip_tags($message)
    ]); ?>;
    if (dbStatus.error) {
        console.error('%c[DB ERROR]', 'color:#ff4444;font-weight:bold;background:#2a0a0a;padding:2px 6px;border-radius:4px;', dbStatus.message);
    } else if (dbStatus.success) {
        console.log('%c[DB OK]', 'color:#44ff44;font-weight:bold;background:#0a2a0a;padding:2px 6px;border-radius:4px;', dbStatus.message);
    }
</script>
<?php endif; ?>

<div class="hero-editor">
    <div class="card">
        <h1>
            <span style="color:#4a9eff;">&#9998;</span>
            Редактор героев
        </h1>

        <?php echo $message; ?>

        <!-- Navigation -->
        <div class="hero-navigation">
            <div class="hero-info">
                <div class="hero-avatar">
                    <?php if ($current_hero && $current_hero['icon_hero']): ?>
                        <img src="<?= $src; ?>heroes/<?php echo htmlspecialchars($current_hero['icon_hero']); ?>" alt="">
                    <?php else: ?>
                        <span class="no-avatar">&#128100;</span>
                    <?php endif; ?>
                </div>
                <div>
                    <div style="font-weight:600;color:#fff;"><?php echo htmlspecialchars($page_hero_name); ?></div>
                    <div style="font-size:13px;color:#888;">
                        <span class="badge-custom"><?php echo $current_index + 1; ?> / <?php echo $total_heroes ?: 1; ?></span>
                    </div>
                </div>
            </div>
            <div class="nav-buttons">
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="action" value="navigate">
                    <input type="hidden" name="current_index" value="<?php echo max(0, $current_index - 1); ?>">
                    <button type="submit" class="btn btn-outline" <?php echo $current_index <= 0 ? 'disabled' : ''; ?>>
                        &#9664; Назад
                    </button>
                </form>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="action" value="navigate">
                    <input type="hidden" name="current_index" value="<?php echo min($total_heroes - 1, $current_index + 1); ?>">
                    <button type="submit" class="btn btn-outline" <?php echo $current_index >= $total_heroes - 1 ? 'disabled' : ''; ?>>
                        Вперед &#9654;
                    </button>
                </form>
            </div>
        </div>

        <!-- Progress -->
        <div class="progress-bar-custom">
            <div class="progress-bar-fill" style="width: <?php echo $total_heroes > 0 ? (($current_index + 1) / $total_heroes * 100) : 0; ?>%;"></div>
        </div>

        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="save">
            <input type="hidden" name="hero_id" value="<?php echo $hero_id; ?>">
            <input type="hidden" name="current_index" value="<?php echo $current_index; ?>">

            <!-- Main Info -->
            <div class="form-section">
                <div class="section-title">&#8505; Основная информация</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name_hero">Имя героя *</label>
                        <input type="text" id="name_hero" name="name_hero"
                            value="<?php echo htmlspecialchars($current_hero['name_hero'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="attribute_id">Атрибут *</label>
                        <select id="attribute_id" name="attribute_id" required>
                            <option value="">Выберите атрибут</option>
                            <?php foreach ($attributes as $attr): ?>
                                <option value="<?php echo $attr['attribute_id']; ?>"
                                    <?php echo (($current_hero['attribute_id'] ?? '') == $attr['attribute_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($attr['attribute_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="attack_type">Тип атаки</label>
                        <select id="attack_type" name="attack_type">
                            <option value="0" <?php echo (($hero_data['attack_type'] ?? '0') == '0') ? 'selected' : ''; ?>>Ближний</option>
                            <option value="1" <?php echo (($hero_data['attack_type'] ?? '0') == '1') ? 'selected' : ''; ?>>Дальний</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="complexity">Сложность</label>
                        <select id="complexity" name="complexity">
                            <option value="1" <?php echo (($current_hero['complexity'] ?? '1') == '1') ? 'selected' : ''; ?>>&#9733; Легкий</option>
                            <option value="2" <?php echo (($current_hero['complexity'] ?? '2') == '2') ? 'selected' : ''; ?>>&#9733;&#9733; Средний</option>
                            <option value="3" <?php echo (($current_hero['complexity'] ?? '3') == '3') ? 'selected' : ''; ?>>&#9733;&#9733;&#9733; Сложный</option>
                        </select>
                    </div>
                    <div class="form-group full-width">
                        <label for="description_hero">Краткое описание</label>
                        <textarea id="description_hero" name="description_hero" rows="2"><?php echo htmlspecialchars($hero_data['description_hero'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group full-width">
                        <label for="full_description">Полное описание</label>
                        <textarea id="full_description" name="full_description" rows="4"><?php echo htmlspecialchars($hero_data['full_description'] ?? ''); ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Roles JSON -->
            <div class="form-section">
                <div class="section-title">&#127991; Роли (JSON)</div>
                <div class="roles-grid">
                    <?php
                    $fields = [
                        'core' => 'Основа',
                        'support' => 'Поддержка',
                        'burst' => 'Быстрый урон',
                        'control' => 'Контроль',
                        'jungle' => 'Лес',
                        'tank' => 'Стойкость',
                        'escape' => 'Побег',
                        'siege' => 'Осада',
                        'initiation' => 'Инициация'
                    ];
                    foreach ($fields as $field => $label):
                    ?>
                    <div class="role-item">
                        <label for="<?php echo $field; ?>"><?php echo $label; ?></label>
                        <input type="number" id="<?php echo $field; ?>" name="<?php echo $field; ?>"
                            value="<?php echo htmlspecialchars($roles_data[$field] ?? 0); ?>" min="0" max="5">
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="json-preview" id="rolesPreview">{}</div>
            </div>

            <!-- Stats -->
            <div class="form-section">
                <div class="section-title">&#9881; Показатели</div>
                <div class="stats-grid">
                    <!-- Attack -->
                    <div class="stats-column">
                        <div class="stats-column-header">Атака</div>
                        <div class="stat-row">
                            <span class="stat-icon">&#9876;</span>
                            <input type="text" id="damage" name="damage" placeholder="Урон"
                                value="<?php echo htmlspecialchars($stats_data['damage'] ?? $default_stats['damage']); ?>">
                        </div>
                        <div class="stat-row">
                            <span class="stat-icon">&#9201;</span>
                            <input type="number" step="0.1" id="attack_interval" name="attack_interval" placeholder="Интервал"
                                value="<?php echo htmlspecialchars($stats_data['attack_interval'] ?? $default_stats['attack_interval']); ?>">
                        </div>
                        <div class="stat-row">
                            <span class="stat-icon">&#8694;</span>
                            <input type="number" id="range" name="range" placeholder="Дальность"
                                value="<?php echo htmlspecialchars($stats_data['range'] ?? $default_stats['range']); ?>">
                        </div>
                        <div class="stat-row">
                            <span class="stat-icon">&#10148;</span>
                            <input type="number" id="projectile_speed" name="projectile_speed" placeholder="Скорость снаряда"
                                value="<?php echo htmlspecialchars($stats_data['projectile_speed'] ?? $default_stats['projectile_speed']); ?>">
                        </div>
                    </div>

                    <!-- Defense -->
                    <div class="stats-column">
                        <div class="stats-column-header">Защита</div>
                        <div class="stat-row">
                            <span class="stat-icon">&#128737;</span>
                            <input type="number" step="0.1" id="armor" name="armor" placeholder="Защита"
                                value="<?php echo htmlspecialchars($stats_data['armor'] ?? $default_stats['armor']); ?>">
                        </div>
                        <div class="stat-row">
                            <span class="stat-icon">&#9937;</span>
                            <input type="number" id="magic_resist" name="magic_resist" placeholder="Magic Resist"
                                value="<?php echo htmlspecialchars($stats_data['magic_resist'] ?? $default_stats['magic_resist']); ?>">
                        </div>
                    </div>

                    <!-- Mobility -->
                    <div class="stats-column">
                        <div class="stats-column-header">Мобильность</div>
                        <div class="stat-row">
                            <span class="stat-icon">&#128095;</span>
                            <input type="number" id="move_speed" name="move_speed" placeholder="Скорость"
                                value="<?php echo htmlspecialchars($stats_data['move_speed'] ?? $default_stats['move_speed']); ?>">
                        </div>
                        <div class="stat-row">
                            <span class="stat-icon">&#9851;</span>
                            <input type="number" step="0.1" id="turn_rate" name="turn_rate" placeholder="Вращение"
                                value="<?php echo htmlspecialchars($stats_data['turn_rate'] ?? $default_stats['turn_rate']); ?>">
                        </div>
                        <div class="stat-row">
                            <span class="stat-icon">&#128065;</span>
                            <input type="text" id="vision" name="vision" placeholder="Видимость"
                                value="<?php echo htmlspecialchars($stats_data['vision'] ?? $default_stats['vision']); ?>">
                        </div>
                    </div>
                </div>
                <div class="json-preview" id="statsPreview">{}</div>
            </div>

            <!-- Images -->
            <div class="form-section">
                <div class="section-title">&#128444; Изображения</div>
                <div class="images-grid">
                    <div class="image-block">
                        <label>Иконка (загрузить)</label>
                        <div class="file-upload-wrapper">
                            <div class="file-upload-btn" id="iconBtn">
                                <span>&#128228;</span> Выбрать файл
                            </div>
                            <input type="file" id="icon_file" name="icon_file" accept="image/*">
                        </div>
                        <span class="small-text">Или введите URL ниже</span>
                        <input type="text" id="icon_hero" name="icon_hero" placeholder="URL иконки"
                            value="<?php echo htmlspecialchars($current_hero['icon_hero'] ?? ''); ?>">
                        <?php if ($current_hero && $current_hero['icon_hero']): ?>
                            <div class="img-preview">
                                <img src="<?= $src ?>heroes/<?php echo htmlspecialchars($current_hero['icon_hero']); ?>" alt="">
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="image-block">
                        <label>Миниатюра (загрузить)</label>
                        <div class="file-upload-wrapper">
                            <div class="file-upload-btn" id="thumbBtn">
                                <span>&#128228;</span> Выбрать файл
                            </div>
                            <input type="file" id="thumbnail_file" name="thumbnail_file" accept="image/*">
                        </div>
                        <span class="small-text">Или введите URL ниже</span>
                        <input type="text" id="crop_hero" name="crop_hero" placeholder="URL миниатюры"
                            value="<?php echo htmlspecialchars($current_hero['crop_hero'] ?? ''); ?>">
                        <?php if ($current_hero && $current_hero['crop_hero']): ?>
                            <div class="img-preview">
                                <img src="<?= $src ?>heroes/crops/<?php echo htmlspecialchars($current_hero['crop_hero']); ?>" alt="">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" name="action" class="btn btn-primary">
                    &#10003; Сохранить
                </button>
                <button type="submit" name="action" value="save_and_next" class="btn btn-success">
                    &#10132; Сохранить и далее
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Title update
document.getElementById('name_hero').addEventListener('input', function(e) {
    const name = e.target.value.trim() || 'Новый герой';
    document.title = name + ' - Редактор героев | ButBalanced';
    document.querySelector('.hero-subtitle').textContent = name;
});

// Roles JSON preview
function updateRolesPreview() {
    const roles = {
        core: +document.getElementById('core').value || 0,
        support: +document.getElementById('support').value || 0,
        burst: +document.getElementById('burst').value || 0,
        control: +document.getElementById('control').value || 0,
        jungle: +document.getElementById('jungle').value || 0,
        tank: +document.getElementById('tank').value || 0,
        escape: +document.getElementById('escape').value || 0,
        siege: +document.getElementById('siege').value || 0,
        initiation: +document.getElementById('initiation').value || 0
    };
    document.getElementById('rolesPreview').textContent = JSON.stringify(roles, null, 2);
}
document.querySelectorAll('#core, #support, #burst, #control, #jungle, #tank, #escape, #siege, #initiation')
    .forEach(input => input.addEventListener('input', updateRolesPreview));

// Stats JSON preview
function updateStatsPreview() {
    const stats = {
        damage: document.getElementById('damage').value || '',
        attack_interval: parseFloat(document.getElementById('attack_interval').value) || 0,
        range: parseInt(document.getElementById('range').value) || 0,
        projectile_speed: parseInt(document.getElementById('projectile_speed').value) || 0,
        armor: parseFloat(document.getElementById('armor').value) || 0,
        magic_resist: parseInt(document.getElementById('magic_resist').value) || 0,
        move_speed: parseInt(document.getElementById('move_speed').value) || 0,
        turn_rate: parseFloat(document.getElementById('turn_rate').value) || 0,
        vision: document.getElementById('vision').value || ''
    };
    document.getElementById('statsPreview').textContent = JSON.stringify(stats, null, 2);
}
document.querySelectorAll('#damage, #attack_interval, #range, #projectile_speed, #armor, #magic_resist, #move_speed, #turn_rate, #vision')
    .forEach(input => input.addEventListener('input', updateStatsPreview));

// Init previews
updateRolesPreview();
updateStatsPreview();

// File upload buttons
document.getElementById('icon_file').addEventListener('change', function() {
    const name = this.files[0]?.name || 'Выбрать файл';
    document.getElementById('iconBtn').innerHTML = '<span>&#128196;</span> ' + name;
});
document.getElementById('thumbnail_file').addEventListener('change', function() {
    const name = this.files[0]?.name || 'Выбрать файл';
    document.getElementById('thumbBtn').innerHTML = '<span>&#128196;</span> ' + name;
});
</script>
