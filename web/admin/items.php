<?php
if (!isset($_SESSION['user_name'])) {
    header('Location: /admin/login');
    exit;
}
?>
<br><br><br>
<div class="editor-container">
    <div class="editor-header">
        <h1>📦 Редактор предметов</h1>
        <button class="btn btn-primary" onclick="openItemModal()">+ Добавить предмет</button>
    </div>
    <div class="editor-table-wrapper">
        <table class="editor-table" id="itemsTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Системное имя</th>
                    <th>Название</th>
                    <th>Нейтральный</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div id="itemModal" class="bb-modal" style="display: none;">
    <div class="bb-modal-content">
        <span class="bb-modal-close" onclick="closeItemModal()">&times;</span>
        <h2 id="itemModalTitle">Добавить предмет</h2>
        <form id="itemForm">
            <input type="hidden" id="item_id" name="id">
            <div class="form-group">
                <label>Системное имя (например: item_blink)</label>
                <input type="text" id="internal_name" name="internal_name" required>
            </div>
            <div class="form-group">
                <label>Отображаемое имя</label>
                <input type="text" id="display_name" name="display_name" required>
            </div>
            <div class="form-group">
                <label><input type="checkbox" id="item_neutral" name="is_neutral"> Нейтральный предмет</label>
            </div>
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>
    </div>
</div>
<script src="/api/jquery.min.js"></script>
<script>
const ITEMS_API = '/api/items_api.php';

function loadItems() {
    $.get(ITEMS_API, { action: 'list' }, function(r) {
        if (!r.success) return;
        let html = '';
        r.data.forEach(i => {
            html += `
            <tr onclick="editItem(${i.id})" style="cursor: pointer;">
                <td>${i.id}</td>
                <td><code>${i.internal_name}</code></td>
                <td><strong>${i.display_name || '-'}</strong></td>
                <td>${i.is_neutral ? '✅' : '❌'}</td>
                <td>
                    <button class="btn btn-sm btn-outline" onclick="event.stopPropagation(); editItem(${i.id})">Edit</button>
                    <button class="btn btn-sm btn-danger" onclick="event.stopPropagation(); deleteItem(${i.id})">Del</button>
                </td>
            </tr>`;
        });
        $('#itemsTable tbody').html(html);
    });
}

function openItemModal() {
    $('#itemForm')[0].reset();
    $('#item_id').val('');
    $('#itemModalTitle').text('Добавить предмет');
    $('#itemModal').show();
}

function closeItemModal() {
    $('#itemModal').hide();
}

function editItem(id) {
    $.get(ITEMS_API, { action: 'get', id: id }, function(r) {
        if (r.success && r.data) {
            const item = r.data;
            $('#item_id').val(item.id);
            $('#internal_name').val(item.internal_name);
            $('#display_name').val(item.display_name);
            $('#item_neutral').prop('checked', item.is_neutral);
            $('#itemModalTitle').text('Редактировать предмет');
            $('#itemModal').show();
        }
    });
}

function deleteItem(id) {
    if (!confirm('Удалить предмет?')) return;
    $.post(ITEMS_API, { action: 'delete', id: id }, function(r) {
        if (r.success) loadItems();
        else alert(r.message);
    });
}

$('#itemForm').on('submit', function(e) {
    e.preventDefault();
    const id = $('#item_id').val();
    $.post(ITEMS_API, {
        action: id ? 'update' : 'create',
        id: id,
        internal_name: $('#internal_name').val(),
        display_name: $('#display_name').val(),
        is_neutral: $('#item_neutral').is(':checked')
    }, function(r) {
        if (r.success) {
            closeItemModal();
            loadItems();
        } else {
            alert(r.message);
        }
    });
});

$(document).ready(loadItems);
</script>
