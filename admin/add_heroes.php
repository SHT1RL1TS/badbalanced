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

    if ($action === 'save') {
        try {
            $hero_id = (int)$_POST['hero_id'];
            $name_hero = $_POST['name_hero'] ?? '';
            $description_hero = $_POST['description_hero'] ?? '';
            $full_description = $_POST['full_description'] ?? '';
            $attack_type = (int)($_POST['attack_type'] ?? 0);
            $complexity = (int)($_POST['complexity'] ?? 0);
            $hp = (float)($_POST['hp'] ?? 0);
            $mana = (float)($_POST['mana'] ?? 0);
            $hp_gain = (float)($_POST['hp_gain'] ?? 0);
            $mana_gain = (float)($_POST['mana_gain'] ?? 0);
            $attribute_id = (int)($_POST['attribute_id'] ?? 0);

            // Собираем JSON для roles
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

            // Собираем JSON для stats
            $stats = [
                'damage' => $_POST['damage'] ?? '',
                'attack_interval' => (float)($_POST['attack_interval'] ?? 0),
                'range' => (int)($_POST['range'] ?? 0),
                'projectile_speed' => (int)($_POST['projectile_speed'] ?? 0),
                'armor' => (float)($_POST['armor'] ?? 0),
                'magic_resist' => (int)($_POST['magic_resist'] ?? 0),
                'move_speed' => (int)($_POST['move_speed'] ?? 0),
                'turn_rate' => (int)($_POST['turn_rate'] ?? 0),
                'vision' => $_POST['vision'] ?? ''
            ];
            $stats_json = json_encode($stats, JSON_UNESCAPED_UNICODE);

            // Обработка загрузки иконки
            $icon_hero = $_POST['icon_url_hero'] ?? '';
            $crop_hero = $_POST['crop_hero'] ?? '';

            if (isset($_FILES['icon_file']) && $_FILES['icon_file']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = $src . 'heroes/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                $filename = basename($_FILES['icon_file']['name']);
                $target_file = $upload_dir . $filename;
                if (move_uploaded_file($_FILES['icon_file']['tmp_name'], $target_file)) {
                    $icon_hero = $filename;
                }
            }

            if (isset($_FILES['crop_hero']) && $_FILES['thumbnail_file']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = $src . 'heroes/crops/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                $filename = basename($_FILES['thumbnail_file']['name']);
                $target_file = $upload_dir . $filename;
                if (move_uploaded_file($_FILES['thumbnail_file']['tmp_name'], $target_file)) {
                    $crop_hero = $filename;
                }
            }
            // Проверяем существует ли герой
            $check_query = "SELECT id_hero FROM heroes WHERE id_hero = :id_hero";
            $check_stmt = $db->prepare($check_query);
            $check_stmt->bindParam(':id_hero', $hero_id);
            $check_stmt->execute();
            $exists = $check_stmt->fetch();

            if ($exists) {
                $query = "
                    UPDATE heroes SET
                        name_hero = :name_hero,
                        attribute_id = :attribute_id,
                        complexity = :complexity
                    WHERE id_hero = :id_hero;
                    UPDATE heroes_stats SET
                        description_hero = :description_hero,
                        full_description = :full_description,
                        attack_type = :attack_type,
                        hp = :hp,
                        mana = :mana,
                        hp_gain = :hp_gain,
                        mana_gain = :mana_gain,
                        roles = :roles,
                        stats = :stats,
                        icon_url_hero = :icon_url_hero,
                        crop_hero = :crop_hero
                    WHERE id_hero = :id_hero;";
            } else {
                $query = "
                    INSERT INTO heroes (
                        attribute_id,
                        name_hero,
                        icon_hero,
                        complexity
                    ) VALUES (
                        :attribute_id,
                        :name_hero,
                        :icon_hero,
                        :complexity
                    )
                    INSERT INTO heroes_stats (
                        id_hero,
                        description_hero,
                        full_description,
                        attack_type,
                        hp,
                        mana,
                        hp_gain,
                        mana_gain,
                        roles,
                        stats
                    ) VALUES (
                        :id_hero,
                        :description_hero,
                        :full_description,
                        :attack_type,
                        :hp,
                        :mana,
                        :hp_gain,
                        :mana_gain,
                        :stats
                        :roles,
                    )";
            }

            $stmt = $db->prepare($query);
            $stmt->bindParam(':id_hero', $hero_id);
            $stmt->bindParam(':name_hero', $name_hero);
            $stmt->bindParam(':description_hero', $description_hero);
            $stmt->bindParam(':full_description', $full_description);
            $stmt->bindParam(':attack_type', $attack_type);
            $stmt->bindParam(':complexity', $complexity);
            $stmt->bindParam(':hp', $hp);
            $stmt->bindParam(':mana', $mana);
            $stmt->bindParam(':hp_gain', $hp_gain);
            $stmt->bindParam(':mana_gain', $mana_gain);
            $stmt->bindParam(':attribute_id', $attribute_id);
            $stmt->bindParam(':roles', $roles_json);
            $stmt->bindParam(':stats', $stats_json);
            $stmt->bindParam(':icon_url_hero', $icon_hero);
            $stmt->bindParam(':crop_hero', $crop_hero);
            $stmt->execute();

            // Обновляем список героев
            $heroes = getAllHeroes($db);

            $message = '<div class="alert alert-success">✅ Герой #' . $hero_id . ' успешно сохранен!</div>';

        } catch (PDOException $e) {
            $message = '<div class="alert alert-danger">❌ Ошибка: ' . $e->getMessage() . '</div>';
        }
    } elseif ($action === 'navigate') {
        $current_index = (int)$_POST['current_index'];
        $hero_id = isset($heroes[$current_index]['id_hero']) ? $heroes[$current_index]['id_hero'] : 0;
    }
}

// Загружаем данные текущего героя
$current_hero = null;
$roles_data = null;
$stats_data = null;

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
        print_r($hero_data);
        if ($hero_data) {
            $roles_data = json_decode($hero_data['roles'], true);
            $stats_data = json_decode($hero_data['stats'], true);
        } else {
            $roles_data = null;
            $stats_data = null;
        }
    }
}

$attributes = getAttributes($db);
$total_heroes = count($heroes);

$page_hero_name = ($current_hero && !empty($current_hero['name_hero'])) ? $current_hero['name_hero'] : 'Новый герой';
?>
<script>
    // Активируем тёмную тему Bootstrap
    document.documentElement.setAttribute('data-bs-theme', 'dark');
</script>

<br>
<br>
<br>
<div class="container-custom">
    <div class="card">
        <h1 class="mb-4" style="color: var(--text-primary);">
            <i class="bi bi-pencil-square" style="color: var(--accent-blue);"></i>
            Редактор героев
            <span id="heroNameInTitle" class="hero-subtitle">
                <?php echo htmlspecialchars($page_hero_name); ?>
            </span>
        </h1>

        <?php echo $message; ?>

        <!-- Навигация -->
        <div class="hero-navigation">
            <div class="hero-info">
                <div class="hero-avatar">
                    <?php if ($current_hero && $current_hero['icon_hero']): ?>
                        <img src="<?= $src; ?>heroes/<?php echo htmlspecialchars($current_hero['icon_hero']); ?>"
                            style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                    <?php else: ?>
                        <i class="bi bi-person-circle" style="font-size: 28px; color: var(--text-secondary);"></i>
                    <?php endif; ?>
                </div>
                <div>
                    <div style="font-weight: 600; color: var(--text-primary);">
                        <?php echo htmlspecialchars($page_hero_name); ?>
                    </div>
                    <div style="font-size: 14px; color: var(--text-secondary);">
                        ID: <?php echo $hero_id ?: '—'; ?>
                        <span class="badge-custom <?php echo $current_hero ? 'active' : ''; ?>">
                            <?php echo $current_index + 1; ?> / <?php echo $total_heroes ?: 1; ?>
                        </span>
                    </div>
                </div>
            </div>
            <div style="display: flex; gap: 10px;">
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="action" value="navigate">
                    <input type="hidden" name="current_index" value="<?php echo max(0, $current_index - 1); ?>">
                    <button type="submit" class="btn btn-outline-secondary" <?php echo $current_index <= 0 ? 'disabled' : ''; ?>>
                        <i class="bi bi-chevron-left"></i> Назад
                    </button>
                </form>
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="action" value="navigate">
                    <input type="hidden" name="current_index" value="<?php echo min($total_heroes - 1, $current_index + 1); ?>">
                    <button type="submit" class="btn btn-outline-secondary" <?php echo $current_index >= $total_heroes - 1 ? 'disabled' : ''; ?>>
                        Вперед <i class="bi bi-chevron-right"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Прогресс -->
        <div class="progress-bar-custom">
            <div class="progress-bar-fill" style="width: <?php echo $total_heroes > 0 ? (($current_index + 1) / $total_heroes * 100) : 0; ?>%;"></div>
        </div>

        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="save">
            <input type="hidden" name="hero_id" value="<?php echo $hero_id; ?>">
            <input type="hidden" name="current_index" value="<?php echo $current_index; ?>">

            <!-- Основная информация -->
            <div class="form-section">
                <h5 class="section-title"><i class="bi bi-info-circle"></i> Основная информация</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name_hero" class="form-label">Имя героя *</label>
                        <input type="text" class="form-control" id="name_hero" name="name_hero"
                            value="<?php echo htmlspecialchars($current_hero['name_hero'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="attribute_id" class="form-label">Атрибут *</label>
                        <select class="form-select" id="attribute_id" name="attribute_id" required>
                            <option value="">Выберите атрибут</option>
                            <?php foreach ($attributes as $attr): ?>
                                <option value="<?php echo $attr['attribute_id']; ?>"
                                    <?php echo (($current_hero['attribute_id'] ?? '') == $attr['attribute_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($attr['attribute_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="attack_type" class="form-label">Тип атаки</label>
                        <select class="form-select" id="attack_type" name="attack_type">
                            <option value="0" <?php echo (($current_hero['attack_type'] ?? '0') == '0') ? 'selected' : ''; ?>>Ближний</option>
                            <option value="1" <?php echo (($current_hero['attack_type'] ?? '0') == '1') ? 'selected' : ''; ?>>Дальний</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="complexity" class="form-label">Сложность</label>
                        <select class="form-select" id="complexity" name="complexity">
                            <option value="1" <?php echo (($current_hero['complexity'] ?? '1') == '1') ? 'selected' : ''; ?>>⭐ Легкий</option>
                            <option value="2" <?php echo (($current_hero['complexity'] ?? '2') == '2') ? 'selected' : ''; ?>>⭐⭐ Средний</option>
                            <option value="3" <?php echo (($current_hero['complexity'] ?? '3') == '3') ? 'selected' : ''; ?>>⭐⭐⭐ Сложный</option>
                        </select>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="description_hero" class="form-label">Краткое описание</label>
                        <textarea class="form-control" id="description_hero" name="description_hero" rows="2"><?php echo htmlspecialchars($current_hero['description_hero'] ?? ''); ?></textarea>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="full_description" class="form-label">Полное описание</label>
                        <textarea class="form-control" id="full_description" name="full_description" rows="4"><?php echo htmlspecialchars($current_hero['full_description'] ?? ''); ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Характеристики -->
            <div class="form-section">
                <h5 class="section-title"><i class="bi bi-bar-chart"></i> Характеристики</h5>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="hp" class="form-label">HP</label>
                        <input type="number" step="0.01" class="form-control" id="hp" name="hp"
                            value="<?php echo htmlspecialchars($current_hero['hp'] ?? ''); ?>">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="mana" class="form-label">Mana</label>
                        <input type="number" step="0.01" class="form-control" id="mana" name="mana"
                            value="<?php echo htmlspecialchars($current_hero['mana'] ?? ''); ?>">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="hp_gain" class="form-label">HP Gain</label>
                        <input type="number" step="0.01" class="form-control" id="hp_gain" name="hp_gain"
                            value="<?php echo htmlspecialchars($current_hero['hp_gain'] ?? ''); ?>">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="mana_gain" class="form-label">Mana Gain</label>
                        <input type="number" step="0.01" class="form-control" id="mana_gain" name="mana_gain"
                            value="<?php echo htmlspecialchars($current_hero['mana_gain'] ?? ''); ?>">
                    </div>
                </div>
            </div>

            <!-- Роли -->
            <div class="form-section">
                <h5 class="section-title"><i class="bi bi-tags"></i> Роли (JSON)</h5>
                <div class="row">
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
                    <div class="col-md-4 mb-2">
                        <label for="<?php echo $field; ?>" class="form-label"><?php echo $label; ?></label>
                        <input type="number" class="form-control" id="<?php echo $field; ?>" name="<?php echo $field; ?>"
                            value="<?php echo htmlspecialchars($roles_data[str_replace('', '', $field)] ?? 0); ?>" min="0" max="5">
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="json-preview">
                    <code id="rolesPreview">{}</code>
                </div>
            </div>

            <!-- Статистика -->
            <div class="form-section">
                <h5 class="section-title"><i class="bi bi-gear"></i> Статистика (JSON)</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="damage" class="form-label">Урон</label>
                        <input type="text" class="form-control" id="damage" name="damage"
                            value="<?php echo htmlspecialchars($stats_data['damage'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="attack_interval" class="form-label">Интервал атак</label>
                        <input type="number" step="0.01" class="form-control" id="attack_interval" name="attack_interval"
                            value="<?php echo htmlspecialchars($stats_data['attack_interval'] ?? ''); ?>">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="range" class="form-label">Дальность</label>
                        <input type="number" class="form-control" id="range" name="range"
                            value="<?php echo htmlspecialchars($stats_data['range'] ?? ''); ?>">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="projectile_speed" class="form-label">Скорость снаряда</label>
                        <input type="number" class="form-control" id="projectile_speed" name="projectile_speed"
                            value="<?php echo htmlspecialchars($stats_data['projectile_speed'] ?? ''); ?>">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="armor" class="form-label">Защита</label>
                        <input type="number" step="0.1" class="form-control" id="armor" name="armor"
                            value="<?php echo htmlspecialchars($stats_data['armor'] ?? ''); ?>">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="magic_resist" class="form-label">Magic Resist</label>
                        <input type="number" class="form-control" id="magic_resist" name="magic_resist"
                            value="<?php echo htmlspecialchars($stats_data['magic_resist'] ?? ''); ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="move_speed" class="form-label">Скорость передвижения</label>
                        <input type="number" min="100" class="form-control" id="move_speed" name="move_speed"
                            value="<?php echo htmlspecialchars($stats_data['move_speed'] ?? ''); ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="turn_rate" class="form-label">Скорость вращения</label>
                        <input type="number" step="0.1" class="form-control" id="turn_rate" name="turn_rate"
                            value="<?php echo htmlspecialchars($stats_data['скорость_вращения'] ?? ''); ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="vision" class="form-label">Дальность видимости</label>
                        <input type="text" pattern="{4}[0-9]/{4}[0-9]" class="form-control" id="vision" name="vision"
                            value="<?php echo htmlspecialchars($stats_data['vision'] ?? ''); ?>" placeholder="1800/800">
                    </div>
                </div>
                <div class="json-preview">
                    <code id="statsPreview">{}</code>
                </div>
            </div>

            <!-- Изображения -->
            <div class="form-section">
                <h5 class="section-title"><i class="bi bi-image"></i> Изображения</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="icon_file" class="form-label">Иконка (загрузить)</label>
                        <div class="file-upload-wrapper">
                            <button type="button" class="btn btn-custom" style="width: 100%;">
                                <i class="bi bi-upload"></i> Выбрать файл
                            </button>
                            <input type="file" class="form-control" id="icon_file" name="icon_file" accept="image/*">
                        </div>
                        <small class="text-secondary">Или введите URL ниже</small>
                        <input type="text" class="form-control mt-2" id="icon_hero" name="icon_hero"
                            placeholder="Или URL иконки"
                            value="<?php echo htmlspecialchars($current_hero['icon_hero'] ?? ''); ?>">
                        <?php if ($current_hero && $current_hero['icon_hero']): ?>
                            <div class="mt-2">
                                <img src="<?= $src ?>/heroes/<?php echo htmlspecialchars($current_hero['icon_hero']); ?>"
                                    style="max-width: 260px; border-radius: 8px; border: 1px solid var(--border-color);">
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="thumbnail_file" class="form-label">Миниатюра (загрузить)</label>
                        <div class="file-upload-wrapper">
                            <button type="button" class="btn btn-custom" style="width: 100%;">
                                <i class="bi bi-upload"></i> Выбрать файл
                            </button>
                            <input type="file" class="form-control" id="thumbnail_file" name="thumbnail_file" accept="image/*">
                        </div>
                        <small class="text-secondary">Или введите URL ниже</small>
                        <input type="text" class="form-control mt-2" id="crop_hero" name="crop_hero"
                            placeholder="Или URL миниатюры"
                            value="<?php echo htmlspecialchars($current_hero['icon_hero'] ?? ''); ?>">
                        <?php if ($current_hero && $current_hero['icon_hero']): ?>
                            <div class="mt-2">
                                <img src="<?= $src ?>/heroes/crops/<?php echo htmlspecialchars($current_hero['icon_hero']); ?>"
                                    style="max-width: 260px; border-radius: 8px; border: 1px solid var(--border-color); object-fit: cover;">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-3" style="margin-top: 30px;">
                <button type="submit" class="btn btn-primary-custom">
                    <i class="bi bi-check2-circle"></i> Сохранить
                </button>
                <button type="submit" name="action" value="save_and_next" class="btn btn-success-custom">
                    <i class="bi bi-arrow-right-circle"></i> Сохранить и далее
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Обновление названия героя в title и заголовке при вводе
document.getElementById('name_hero').addEventListener('input', function(e) {
    const name = e.target.value.trim() || 'Новый герой';
    document.title = name + ' — Редактор героев | ButBalanced';
    document.getElementById('heroNameInTitle').textContent = name;
});

// Обновление JSON превью
document.querySelectorAll('[name^=""]').forEach(input => {
    input.addEventListener('input', updateRolesPreview);
});
function updateRolesPreview() {
    const roles = {
        core: document.getElementById('core').value || 0,
        support: document.getElementById('support').value || 0,
        burst: document.getElementById('burst').value || 0,
        control: document.getElementById('control').value || 0,
        jungle: document.getElementById('jungle').value || 0,
        tank: document.getElementById('tank').value || 0,
        escape: document.getElementById('escape').value || 0,
        siege: document.getElementById('siege').value || 0,
        initiation: document.getElementById('initiation').value || 0
    };
    document.getElementById('rolesPreview').textContent = JSON.stringify(roles, null, 2);
}

document.querySelectorAll('[name^=""]').forEach(input => {
    input.addEventListener('input', updateStatsPreview);
});
function updateStatsPreview() {
    const stats = {
        damage: document.getElementById('damage').value || '',
        attack_interval: parseFloat(document.getElementById('attack_interval').value) || 0,
        range: parseInt(document.getElementById('range').value) || 0,
        projectile_speed: parseInt(document.getElementById('projectile_speed').value) || 0,
        armor: parseFloat(document.getElementById('armor').value) || 0,
        magic_resist: parseInt(document.getElementById('magic_resist').value) || 0,
        move_speed: parseInt(document.getElementById('move_speed').value) || 0,
        turn_rate: parseInt(document.getElementById('turn_rate').value) || 0,
        vision: document.getElementById('vision').value || ''
    };
    document.getElementById('statsPreview').textContent = JSON.stringify(stats, null, 2);
}

// Инициализация
updateRolesPreview();
updateStatsPreview();

// Показываем имя файла при выборе
document.querySelectorAll('input[type="file"]').forEach(input => {
    input.addEventListener('change', function(e) {
        const fileName = this.files[0]?.name || 'Файл не выбран';
        const parent = this.closest('.file-upload-wrapper');
        const button = parent.querySelector('button');
        button.innerHTML = `<i class="bi bi-file-earmark"></i> ${fileName}`;
    });
});
</script>
<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>-->
