<?php
if (!isset($_SESSION['user_name'])) {
    header('Location: /admin/login');
    exit;
}
?>
<style>
    @keyframes highlightRow {
        0% { background-color: rgba(225, 112, 85, 0.4); }
        100% { background-color: transparent; }
    }
    .highlight-target {
        animation: highlightRow 2.5s ease-out;
    }
</style>

<br><br><br>
<div class="editor-container">
    <div class="editor-header">
        <h1>📝 Редактор патчей</h1>
        <button class="btn btn-primary" onclick="openPatchModal()">+ Создать патч</button>
    </div>
    <div class="editor-table-wrapper">
        <table class="editor-table" id="patchesTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Название патча</th>
                    <th>Дата выхода</th>
                    <th>Тип</th>
                    <th>Изменений</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div id="patchModal" class="bb-modal" style="display: none;">
    <div class="bb-modal-content" style="max-width: 550px;">
        <span class="bb-modal-close" onclick="closePatchModal()">&times;</span>
        <h2 id="patchModalTitle">Создать патч</h2>

        <form id="patchForm" method="POST" action="javascript:void(0);" onsubmit="event.preventDefault();">
            <input type="hidden" id="patch_id" name="id">

            <div class="form-group">
                <label>Название патча (patch_name)</label>
                <input type="text" id="patch_name" name="patch_name" placeholder="например: patch 7.36c" required autocomplete="off">
            </div>

            <div class="form-group">
                <label>Дата выхода (patch_date)</label>
                <input type="date" id="patch_date" name="patch_date">
            </div>

            <div class="form-group">
                <label><input type="checkbox" id="is_custom" name="is_custom"> 🔥 Кастомный патч (ButBalanced)</label>
            </div>

            <button type="submit" id="saveBtn" class="btn btn-primary">Сохранить</button>
        </form>
    </div>
</div>

<script src="/api/jquery.min.js"></script>
<script>
const PATCHES_API = '/api/patches_api.php';

function loadPatches() {
    $.post(PATCHES_API, { action: 'list' }, function(r) {
        if (!r.success) return;
        let html = '';
        r.data.forEach(p => {
            const badge = p.is_custom
                ? '<span style="color: #ff7675; font-weight: bold;">🔥 Мод</span>'
                : '<span style="color: #74b9ff;">Официальный</span>';

            html += `
            <tr id="patch-${p.id}" onclick="viewPatch(${p.id})" style="cursor: pointer;">
                <td>${p.id}</td>
                <td><strong>${p.patch_name}</strong></td>
                <td>${p.patch_date || '—'}</td>
                <td>${badge}</td>
                <td><span class="badge" style="background:#2d3436; padding:2px 8px; border-radius:10px;">${p.changes_count}</span></td>
                <td>
                    <button class="btn btn-sm btn-outline" onclick="event.stopPropagation(); editPatch(${p.id})">Edit</button>
                    <button class="btn btn-sm btn-danger" onclick="event.stopPropagation(); deletePatch(${p.id})">Del</button>
                </td>
            </tr>`;
        });
        $('#patchesTable tbody').html(html);

        // Скроллим к якорю после отрисовки таблицы
        if (window.location.hash) {
            const $target = $(window.location.hash);
            if ($target.length) {
                $('html, body').animate({
                    scrollTop: $target.offset().top - 100
                }, 300);
                $target.addClass('highlight-target');
            }
        }
    });
}

function viewPatch(id) {
    window.location.href = '/admin/patch/' + id;
}

function openPatchModal() {
    $('#patchForm')[0].reset();
    $('#patch_id').val('');
    $('#patchModalTitle').text('Создать патч');
    $('#patchModal').show();
}

function closePatchModal() {
    $('#patchModal').hide();
}

function editPatch(id) {
    $.post(PATCHES_API, { action: 'get', id: id }, function(r) {
        if (r.success && r.data) {
            const p = r.data;
            $('#patch_id').val(p.id);
            $('#patch_name').val(p.patch_name);
            $('#patch_date').val(p.patch_date || '');
            $('#is_custom').prop('checked', p.is_custom);
            $('#patchModalTitle').text('Редактировать патч');
            $('#patchModal').show();
        }
    });
}

function deletePatch(id) {
    if (!confirm('Удалить патч и все связанные изменения?')) return;
    $.post(PATCHES_API, { action: 'delete', id: id }, function(r) {
        if (r.success) loadPatches();
        else alert(r.message);
    });
}

$('#patchForm').on('submit', function(e) {
    e.preventDefault();
    const id = $('#patch_id').val();
    $.post(PATCHES_API, {
        action: id ? 'update' : 'create',
        id: id,
        patch_name: $('#patch_name').val(),
        patch_date: $('#patch_date').val(),
        is_custom: $('#is_custom').is(':checked')
    }, function(r) {
        if (r.success) {
            closePatchModal();
            loadPatches();
        } else {
            alert(r.message || 'Ошибка сохранения');
        }
    });
});

$(document).ready(loadPatches);
</script>
