<?php
if (!isset($_SESSION['user_name'])) {
    header('Location: /admin/login');
    exit;
}
?>
<br><br><br>
<div class="editor-container">
    <div class="editor-header">
        <h1>⚔️ Редактор способностей (Skills)</h1>
        <button class="btn btn-primary" onclick="openSkillModal()">+ Добавить способность</button>
    </div>
    <div class="editor-table-wrapper">
        <table class="editor-table" id="skillsTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Системное имя</th>
                    <th>Название</th>
                    <th>Герой</th>
                    <th>Тип</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div id="skillModal" class="bb-modal" style="display: none;">
    <div class="bb-modal-content">
        <span class="bb-modal-close" onclick="closeSkillModal()">&times;</span>
        <h2 id="modalTitle">Добавить способность</h2>

        <form id="skillForm" method="POST" action="javascript:void(0);" onsubmit="event.preventDefault();">
            <input type="hidden" id="skill_id" name="id">

            <div class="form-group">
                <label>Системное имя (internal_name)</label>
                <input type="text" id="skill_internal" name="internal_name" placeholder="например: abaddon_aphotic_shield" required autocomplete="off">
            </div>

            <div class="form-group">
                <label>Отображаемое имя (display_name)</label>
                <input type="text" id="skill_display" name="display_name" autocomplete="off">
            </div>

            <div class="form-group">
                <label>Герой</label>
                <select id="skill_hero" name="hero_id">
                    <option value="">-- Не привязан --</option>
                </select>
            </div>

            <div class="form-group">
                <label><input type="checkbox" id="skill_ultimate" name="is_ultimate"> Ультимейт</label>
                <label style="margin-left: 20px;"><input type="checkbox" id="skill_talent" name="is_talent"> Талант</label>
            </div>

            <button type="submit" id="saveBtn" class="btn btn-primary">Сохранить</button>
        </form>
    </div>
</div>

<script src="/api/jquery.min.js"></script>
<script>
const SKILLS_API = '/api/skills_api.php';

function loadHeroes() {
    $.post(SKILLS_API, { action: 'heroes' }, function(r) {
        if (!r.success) return;
        let opts = '<option value="">-- Не привязан --</option>';
        r.data.forEach(h => {
            opts += `<option value="${h.id}">${h.display_name}</option>`;
        });
        $('#skill_hero').html(opts);
    });
}

function loadSkills() {
    $.post(SKILLS_API, { action: 'list' }, function(r) {
        if (!r.success) return;
        let html = '';
        r.data.forEach(s => {
            let badge = 'Обычный';
            if (s.is_ultimate) badge = '👑 Ульт';
            if (s.is_talent)   badge = '⭐ Талант';

            html += `
            <tr onclick="editSkill(${s.id})" style="cursor: pointer;">
                <td>${s.id}</td>
                <td><code>${s.internal_name}</code></td>
                <td><strong>${s.display_name || '-'}</strong></td>
                <td>${s.hero_name || '<span style="color:#888;">Общий</span>'}</td>
                <td>${badge}</td>
                <td>
                    <button class="btn btn-sm btn-outline" onclick="event.stopPropagation(); editSkill(${s.id})">Edit</button>
                    <button class="btn btn-sm btn-danger" onclick="event.stopPropagation(); deleteSkill(${s.id})">Del</button>
                </td>
            </tr>`;
        });
        $('#skillsTable tbody').html(html);
    });
}

function openSkillModal() {
    $('#skillForm')[0].reset();
    $('#skill_id').val('');
    $('#modalTitle').text('Добавить способность');
    $('#skillModal').show();
}

function closeSkillModal() {
    $('#skillModal').hide();
}

function editSkill(id) {
    $.post(SKILLS_API, { action: 'get', id: id }, function(r) {
        if (r.success && r.data) {
            const s = r.data;
            $('#skill_id').val(s.id);
            $('#skill_internal').val(s.internal_name);
            $('#skill_display').val(s.display_name || '');
            $('#skill_hero').val(s.hero_id || '');
            $('#skill_ultimate').prop('checked', s.is_ultimate);
            $('#skill_talent').prop('checked', s.is_talent);
            $('#modalTitle').text('Редактировать способность');
            $('#skillModal').show();
        }
    });
}

function deleteSkill(id) {
    if (!confirm('Удалить эту способность?')) return;
    $.post(SKILLS_API, { action: 'delete', id: id }, function(r) {
        if (r.success) {
            loadSkills();
        } else {
            alert(r.message || 'Ошибка при удалении');
        }
    });
}

$('#skillForm').on('submit', function(e) {
    e.preventDefault();
    const id = $('#skill_id').val();

    $.post(SKILLS_API, {
        action: id ? 'update' : 'create',
        id: id,
        internal_name: $('#skill_internal').val(),
        display_name: $('#skill_display').val(),
        hero_id: $('#skill_hero').val() || null,
        is_ultimate: $('#skill_ultimate').is(':checked'),
        is_talent: $('#skill_talent').is(':checked')
    }, function(r) {
        if (r.success) {
            closeSkillModal();
            loadSkills();
        } else {
            alert(r.message || 'Ошибка сохранения');
        }
    });
});

$(document).ready(function() {
    loadHeroes();
    loadSkills();
});
</script>
