<?php
if(!isset($_SESSION['user_name'])) {
    Header('Location:home');
    exit;
}
?>
<br>
<br>
<br>
<div class="editor-container">
    <div class="editor-header">
        <h1>📦 Редактор предметов</h1>
        <button class="btn btn-primary" onclick="openItemModal()">+ Добавить предмет</button>
    </div>
    <div class="editor-table-wrapper">
        <table class="editor-table" id="itemsTable">
            <thead><tr><th>ID</th><th>Иконка</th><th>Название</th><th>Нейтральный</th><th>Действия</th></tr></thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div id="itemModal" class="bb-modal">
    <div class="bb-modal-content">
        <span class="bb-modal-close" onclick="closeItemModal()">&times;</span>
        <h2 id="itemModalTitle">Добавить предмет</h2>
        <form id="itemForm">
            <input type="hidden" id="item_id" name="id_item">
            <div class="form-group"><label>Название</label><input type="text" id="item_name" name="name_item" required></div>
            <div class="form-group"><label>Описание</label><textarea id="item_desc" name="description_item" rows="3"></textarea></div>
            <div class="form-group"><label>URL изображения</label><input type="text" id="item_img" name="image_url_item"></div>
            <div class="form-group"><label><input type="checkbox" id="item_neutral" name="is_neutral"> Нейтральный предмет</label></div>
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>
    </div>
</div>

<script>
  const ITEMS_API = '<?=$baseUrl . 'api/items_api.php'?>';
  function loadItems() {
      $.get(ITEMS_API, {action:'list'}, function(r){
          if(r.success){
              let h='';
              r.data.forEach(i=>{
                  h+=`<tr><td>${i.id_item}</td>
                  <td><img src="/cd-project/butbalanced/src/items/${i.image_url_item||''}" style="width:40px;height:40px;border-radius:4px;" onerror="this.style.display='none'"></td>
                  <td>${i.name_item}</td><td>${i.is_neutral?'✅':'❌'}</td>
                  <td><button class="btn btn-sm btn-outline" onclick="editItem(${i.id_item})">Edit</button>
                  <button class="btn btn-sm btn-danger" onclick="deleteItem(${i.id_item})">Del</button></td></tr>`;
              });
              $('#itemsTable tbody').html(h);
          }
      });
  }
  function openItemModal(){$('#itemForm')[0].reset();$('#item_id').val('');$('#itemModalTitle').text('Добавить предмет');$('#itemModal').show();}
  function closeItemModal(){$('#itemModal').hide();}
  function editItem(id){
      $.get(ITEMS_API,{action:'get',id:id},function(r){
          if(r.success&&r.data){const i=r.data;$('#item_id').val(i.id_item);$('#item_name').val(i.name_item);$('#item_desc').val(i.description_item);$('#item_img').val(i.image_url_item);$('#item_neutral').prop('checked',i.is_neutral);$('#itemModalTitle').text('Редактировать предмет');$('#itemModal').show();}
      });
  }
  function deleteItem(id){if(!confirm('Удалить предмет?'))return;$.ajax({url:ITEMS_API+'?id='+id,type:'DELETE',success:function(r){if(r.success)loadItems();else alert(r.message);}});}
  $('#itemForm').on('submit',function(e){e.preventDefault();
      const id=$('#item_id').val();
      $.post(ITEMS_API,{action:id?'update':'create',id_item:id||undefined,name_item:$('#item_name').val(),description_item:$('#item_desc').val(),image_url_item:$('#item_img').val(),is_neutral:$('#item_neutral').is(':checked')},function(r){
          if(r.success){closeItemModal();loadItems();}else alert(r.message);
      });
  });
  $(document).ready(loadItems);
</script>
