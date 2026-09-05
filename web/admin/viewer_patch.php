<?php
if (!isset($_SESSION['user_name'])) {
    header('Location: /admin/login');
    exit;
}

require_once __DIR__ . '/../api/db.php';
$db = getDb_();

// Получаем ID из переменной роутера или из GET
$id = (int)($patchId ?? 0);

if ($id == 0) {
    header('Location: /admin/patches');
    exit;
}

$stmt = $db->prepare('SELECT id, patch_name, patch_date, is_custom FROM patches WHERE id = ?');
$stmt->execute([$id]);
$patch = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$patch) {
    echo '<div style="padding: 40px; text-align: center; color: #fff;">';
    echo '<h2>Патч не найден</h2>';
    echo '<a href="/admin/patches" class="btn btn-primary">Вернуться к списку</a>';
    echo '</div>';
    exit;
}

// Запрашиваем изменения данного патча
$stmtChanges = $db->prepare('
    SELECT id, entity_type, entity_name, ability_name, note_text, icon, is_scepter, is_shard, indent_level
    FROM patch_changes
    WHERE patch_id = ?
    ORDER BY id ASC
');
$stmtChanges->execute([$patchId]);
$changes = $stmtChanges->fetchAll(PDO::FETCH_ASSOC);
?>

<br><br><br>
<div class="patch-viewer-wrapper">
    <!-- Кнопка возврата с якорем -->
    <a href="/admin/patches#patch-<?= $patch['id'] ?>" class="back-btn">
        <span>←</span> Вернуться к списку патчей
    </a>

    <div class="patch-card">
        <div class="patch-header">
            <div class="viewer-badge">
                <span>🛡️</span>
                <span class="viewer-badge-text">
                    <?= $patch['is_custom'] ? 'Кастомный патч (ButBalanced)' : 'Официальный патч' ?>
                </span>
            </div>

            <h1 class="patch-title">
                Патч <span class="patch-title-highlight"><?= htmlspecialchars($patch['patch_name']) ?></span>
            </h1>

            <div class="patch-divider"></div>

            <div class="patch-meta-bar">
                <span>📅 Дата выхода: <strong><?= htmlspecialchars($patch['patch_date'] ?: 'Не указана') ?></strong></span>
                <span>•</span>
                <span>📋 Записей в списке: <strong><?= count($changes) ?></strong></span>
            </div>
        </div>

        <?php if (empty($changes)): ?>
            <div class="empty-state">
                <p>Для этого патча нет зарегистрированных изменений в базе данных.</p>
            </div>
        <?php else: ?>
            <div class="changes-list">
                <?php foreach ($changes as $c): ?>
                    <div class="change-item" <?= ($c['indent_level'] > 0) ? 'style="margin-left: ' . ($c['indent_level'] * 24) . 'px;"' : '' ?>>
                        <div class="change-top-row">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <span class="entity-tag"><?= htmlspecialchars(strtoupper($c['entity_type'])) ?></span>
                                <span class="entity-title"><?= htmlspecialchars($c['entity_name']) ?></span>
                            </div>

                            <?php if (!empty($c['ability_name'])): ?>
                                <span class="ability-tag"><?= htmlspecialchars($c['ability_name']) ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="change-text">
                            <?= !empty($c['note_text']) ? $c['note_text'] : '<em style="color: #64748b;">Описание изменения отсутствует</em>' ?>
                        </div>

                        <?php if ($c['is_scepter'] || $c['is_shard']): ?>
                            <div class="modifier-badges">
                                <?php if ($c['is_scepter']): ?>
                                    <span class="mod-badge mod-scepter">🔱 Aghanim's Scepter</span>
                                <?php endif; ?>
                                <?php if ($c['is_shard']): ?>
                                    <span class="mod-badge mod-shard">🔹 Aghanim's Shard</span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
